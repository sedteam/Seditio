<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/revisions/inc/revisions.functions.php
Version=186
Updated=2026-oct-23
Type=Plugin
Author=Seditio Team
Description=Core API functions for universal revisions system
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once SED_ROOT . '/plugins/revisions/inc/revisions.diff.php';

/**
 * Loads registered revisions types from modules and plugins via revisions.api hook
 * 
 * @return array
 */
function sed_revisions_load_types()
{
	global $sed_revisions_types, $L;

	if (!is_array($sed_revisions_types) || empty($sed_revisions_types)) {
		$sed_revisions_types = array();

		/* === Hook: revisions.api === */
		foreach (sed_getextplugins('revisions.api') as $pl) {
			include $pl;
		}
		/* ===== */
	}

	return $sed_revisions_types;
}

/**
 * Computes a stable MD5 hash of content data, ignoring volatile counter fields
 * 
 * @param array $data Raw data array
 * @param array $ignore_fields Fields to exclude from hash calculation
 * @return string MD5 hash
 */
function sed_revisions_get_content_hash($data, $ignore_fields = array())
{
	if (!is_array($data)) {
		return md5((string)$data);
	}

	$content = $data;
	if (is_array($ignore_fields)) {
		foreach ($ignore_fields as $f) {
			unset($content[$f]);
		}
	}
	foreach ($content as $k => $v) {
		if (is_null($v)) {
			$content[$k] = '';
		}
	}
	ksort($content);

	return md5(serialize($content));
}

/**
 * Creates a new revision checkpoint for an entity if content has changed
 * 
 * @param string $type Entity type (e.g. 'page', 'structure')
 * @param mixed $itemid Entity ID (e.g. page_id or structure_code)
 * @param string $title Human-readable title
 * @param array $data Raw entity data array
 * @param string $comment Optional reason / comment for checkpoint
 * @return int|bool Revision ID if created, false if duplicate or failed
 */
function sed_revision_add($type, $itemid, $title, $data, $comment = '')
{
	global $cfg, $db_revisions, $sys, $usr;

	if (empty($type) || empty($itemid) || !is_array($data)) {
		return false;
	}

	$types = sed_revisions_load_types();
	$default_ignores = array(
		'page' => array('page_count', 'page_filecount', 'page_rating', 'page_comcount'),
		'structure' => array('structure_order')
	);
	$ignore_fields = isset($types[$type]['ignore_fields']) 
		? $types[$type]['ignore_fields'] 
		: (isset($default_ignores[$type]) ? $default_ignores[$type] : array());

	$current_hash = sed_revisions_get_content_hash($data, $ignore_fields);

	// Check hash against last saved revision for this entity to prevent duplicate checkpoints
	$sql_last = sed_sql_query("SELECT rev_id, rev_hash, rev_version FROM $db_revisions 
		WHERE rev_type = '" . sed_sql_prep($type) . "' AND rev_itemid = '" . sed_sql_prep($itemid) . "' 
		ORDER BY rev_version DESC LIMIT 1");

	$next_version = 1;
	if ($last = sed_sql_fetchassoc($sql_last)) {
		if ($last['rev_hash'] === $current_hash) {
			return false; // No content changes
		}
		$next_version = (int)$last['rev_version'] + 1;
	}

	// Prepare data string (optional gzip compression)
	$serialized = serialize($data);
	if (!empty($cfg['plugin']['revisions']['enable_compression']) && function_exists('gzcompress')) {
		$data_to_store = 'GZ:' . base64_encode(gzcompress($serialized, 6));
	} else {
		$data_to_store = $serialized;
	}

	$userid = isset($usr['id']) ? (int)$usr['id'] : 0;
	$user_name = isset($usr['name']) ? $usr['name'] : '';

	$sql = sed_sql_query("INSERT INTO $db_revisions (
		rev_type, rev_itemid, rev_date, rev_userid, rev_user_name, rev_version, rev_title, rev_comment, rev_hash, rev_datas
	) VALUES (
		'" . sed_sql_prep($type) . "',
		'" . sed_sql_prep($itemid) . "',
		" . (int)$sys['now_offset'] . ",
		$userid,
		'" . sed_sql_prep($user_name) . "',
		$next_version,
		'" . sed_sql_prep($title) . "',
		'" . sed_sql_prep($comment) . "',
		'" . sed_sql_prep($current_hash) . "',
		'" . sed_sql_prep($data_to_store) . "'
	)");

	$rev_id = sed_sql_insertid();

	// Rotate old revisions if limit exceeded
	$max_revs = !empty($cfg['plugin']['revisions']['max_revisions']) ? (int)$cfg['plugin']['revisions']['max_revisions'] : 15;
	if ($max_revs > 0) {
		$count_sql = sed_sql_query("SELECT COUNT(*) AS total FROM $db_revisions 
			WHERE rev_type = '" . sed_sql_prep($type) . "' AND rev_itemid = '" . sed_sql_prep($itemid) . "'");
		$total_count = (int)sed_sql_result($count_sql, 0, 'total');

		if ($total_count > $max_revs) {
			$excess = $total_count - $max_revs;
			$old_revs = sed_sql_query("SELECT rev_id FROM $db_revisions 
				WHERE rev_type = '" . sed_sql_prep($type) . "' AND rev_itemid = '" . sed_sql_prep($itemid) . "' 
				ORDER BY rev_version ASC LIMIT $excess");
			while ($row_del = sed_sql_fetchassoc($old_revs)) {
				sed_revision_delete($row_del['rev_id']);
			}
		}
	}

	return $rev_id;
}

/**
 * Gets a revision by ID with unpacked data
 * 
 * @param int $rev_id Revision ID
 * @return array|bool
 */
function sed_revision_get($rev_id)
{
	global $db_revisions;

	$rev_id = (int)$rev_id;
	if ($rev_id <= 0) {
		return false;
	}

	$sql = sed_sql_query("SELECT * FROM $db_revisions WHERE rev_id = $rev_id LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		if (substr($row['rev_datas'], 0, 3) === 'GZ:' && function_exists('gzuncompress')) {
			$decompressed = gzuncompress(base64_decode(substr($row['rev_datas'], 3)));
			$row['rev_datas'] = sed_unserialize($decompressed);
		} else {
			$row['rev_datas'] = sed_unserialize($row['rev_datas']);
		}
		return $row;
	}

	return false;
}

/**
 * Returns a list of revisions for a given entity
 * 
 * @param string $type Entity type
 * @param mixed $itemid Entity ID
 * @param int $limit Optional limit (0 = all)
 * @return array
 */
function sed_revision_list($type, $itemid, $limit = 0)
{
	global $db_revisions;

	$limit_str = ($limit > 0) ? " LIMIT " . (int)$limit : "";
	$sql = sed_sql_query("SELECT rev_id, rev_type, rev_itemid, rev_date, rev_userid, rev_user_name, rev_version, rev_title, rev_comment, rev_hash, LENGTH(rev_datas) as data_size 
		FROM $db_revisions 
		WHERE rev_type = '" . sed_sql_prep($type) . "' AND rev_itemid = '" . sed_sql_prep($itemid) . "' 
		ORDER BY rev_version DESC" . $limit_str);

	$list = array();
	while ($row = sed_sql_fetchassoc($sql)) {
		$list[] = $row;
	}

	return $list;
}

/**
 * Restores an entity from a revision checkpoint via revisions.restore hook
 * 
 * @param int $rev_id Revision ID
 * @return bool
 */
function sed_revision_restore($rev_id)
{
	$res = sed_revision_get($rev_id);
	if (!is_array($res) || empty($res['rev_type'])) {
		return false;
	}

	$types = sed_revisions_load_types();
	$type = $res['rev_type'];

	if (isset($types[$type]['restore']) && function_exists($types[$type]['restore'])) {
		$func = $types[$type]['restore'];
		return $func($res['rev_datas'], $res['rev_itemid'], $res);
	}

	$restored = false;

	/* === Hook: revisions.restore === */
	foreach (sed_getextplugins('revisions.restore') as $pl) {
		include $pl;
	}
	/* ===== */

	return $restored;
}

/**
 * Deletes a single revision checkpoint
 * 
 * @param int $rev_id Revision ID
 * @return bool
 */
function sed_revision_delete($rev_id)
{
	global $db_revisions;

	$rev_id = (int)$rev_id;
	if ($rev_id <= 0) {
		return false;
	}

	/* === Hook: revisions.wipe === */
	foreach (sed_getextplugins('revisions.wipe') as $pl) {
		include $pl;
	}
	/* ===== */

	sed_sql_query("DELETE FROM $db_revisions WHERE rev_id = $rev_id");
	return (sed_sql_affectedrows() > 0);
}

/**
 * Deletes all revisions for a specific entity
 * 
 * @param string $type Entity type (e.g. 'page', 'structure')
 * @param mixed $itemid Entity ID
 * @return int Number of deleted revisions
 */
function sed_revision_wipe_entity($type, $itemid)
{
	global $db_revisions;

	if (empty($type) || empty($itemid)) {
		return 0;
	}

	sed_sql_query("DELETE FROM $db_revisions WHERE rev_type = '" . sed_sql_prep($type) . "' AND rev_itemid = '" . sed_sql_prep($itemid) . "'");
	return sed_sql_affectedrows();
}

/**
 * Prunes revisions older than specified number of days
 * 
 * @param int $days Number of days
 * @return int Number of pruned rows
 */
function sed_revision_prune($days)
{
	global $db_revisions, $sys;

	$days = (int)$days;
	if ($days <= 0) {
		return 0;
	}

	$cutoff = $sys['now_offset'] - ($days * 86400);
	sed_sql_query("DELETE FROM $db_revisions WHERE rev_date < $cutoff");
	return sed_sql_affectedrows();
}

/**
 * Renders tab content template for an entity's revisions
 * 
 * @param string $type Entity type
 * @param mixed $itemid Entity ID
 * @param array $revisions Revisions list
 * @return string Rendered HTML
 */
function sed_revisions_render_tab($type, $itemid, $revisions)
{
	global $cfg, $usr, $sys, $L;

	$skinfile = sed_skinfile(array('revisions', 'tab'), true);
	if (empty($skinfile) || !file_exists($skinfile)) {
		return '';
	}

	$t = new XTemplate($skinfile);

	$total = count($revisions);
	$t->assign(array(
		'REVISIONS_TYPE' => $type,
		'REVISIONS_ITEMID' => $itemid,
		'REVISIONS_TOTAL' => $total,
		'REVISIONS_URL' => sed_url('admin', 'm=revisions&type=' . $type . '&itemid=' . $itemid)
	));

	if ($total > 0) {
		$i = 0;
		foreach ($revisions as $row) {
			$i++;
			$is_latest = ($i === 1);

			$user_link = !empty($row['rev_userid']) 
				? "<a href=\"" . sed_url('users', 'm=details&id=' . $row['rev_userid']) . "\">" . sed_cc($row['rev_user_name']) . "</a>" 
				: sed_cc($row['rev_user_name']);

			$diff_url = sed_url('admin', 'm=revisions&s=diff&id=' . $row['rev_id']);
			$restore_url = sed_url('admin', 'm=revisions&a=restore&id=' . $row['rev_id'] . '&' . sed_xg());
			$delete_url = sed_url('admin', 'm=revisions&a=delete&id=' . $row['rev_id'] . '&' . sed_xg());

			$t->assign(array(
				'REVISIONS_ROW_ID' => $row['rev_id'],
				'REVISIONS_ROW_VERSION' => $row['rev_version'],
				'REVISIONS_ROW_DATE' => sed_build_date($cfg['dateformat'], $row['rev_date']),
				'REVISIONS_ROW_AUTHOR' => $user_link,
				'REVISIONS_ROW_COMMENT' => sed_cc($row['rev_comment']),
				'REVISIONS_ROW_SIZE' => sed_format_size($row['data_size']),
				'REVISIONS_ROW_DIFF_URL' => $diff_url,
				'REVISIONS_ROW_RESTORE_URL' => $restore_url,
				'REVISIONS_ROW_DELETE_URL' => $delete_url,
				'REVISIONS_ROW_IS_LATEST' => $is_latest,
				'REVISIONS_ROW_CAN_RESTORE' => !$is_latest
			));

			if ($is_latest) {
				$t->parse('MAIN.REVISIONS_LIST.REVISIONS_ROW.LATEST_BADGE');
			}

			$t->parse('MAIN.REVISIONS_LIST.REVISIONS_ROW');
		}
		$t->parse('MAIN.REVISIONS_LIST');
	} else {
		$t->parse('MAIN.NO_REVISIONS');
	}

	$t->parse('MAIN');
	return $t->text('MAIN');
}

/**
 * Assigns universal tags to XTemplate object for current entity
 * 
 * @param XTemplate $t Reference to XTemplate object
 * @param string $type Entity type
 * @param mixed $itemid Entity ID
 */
function sed_revisions_assign_tags(&$t, $type, $itemid)
{
	global $usr;

	if (!sed_plug_active('revisions') || !$usr['isadmin'] || empty($itemid)) {
		$t->assign(array(
			'REVISIONS_CONTENT' => '',
			'REVISIONS_COUNT' => 0,
			'REVISIONS_URL' => ''
		));
		return;
	}

	$revisions = sed_revision_list($type, $itemid);
	$count = count($revisions);
	$content = sed_revisions_render_tab($type, $itemid, $revisions);

	$t->assign(array(
		'REVISIONS_CONTENT' => $content,
		'REVISIONS_COUNT' => $count,
		'REVISIONS_URL' => sed_url('admin', 'm=revisions&type=' . $type . '&itemid=' . $itemid)
	));
}
