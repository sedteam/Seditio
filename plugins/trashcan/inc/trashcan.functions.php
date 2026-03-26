<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/inc/trashcan.functions.php
Version=185
Updated=2026-mar-26
Type=Plugin
Description=Trash API (moved from core)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Sends item to trash
 *
 * @param string $type Item type
 * @param string $title Title
 * @param mixed $itemid Item ID (string or int)
 * @param mixed $datas Row data (serialized in DB)
 */
function sed_trash_put($type, $title, $itemid, $datas)
{
	global $db_trash, $sys, $usr;

	$sql = sed_sql_query("INSERT INTO $db_trash (tr_date, tr_type, tr_title, tr_itemid, tr_trashedby, tr_datas)
		VALUES
		(" . $sys['now_offset'] . ", '" . sed_sql_prep($type) . "', '" . sed_sql_prep($title) . "', '" . sed_sql_prep($itemid) . "', " . $usr['id'] . ", '" . sed_sql_prep(serialize($datas)) . "')");

	return;
}

/**
 * Removing an item from trash
 *
 * @param int $id Trash item ID
 * @return int
 */
function sed_trash_delete($id)
{
	global $db_trash;

	$sql = sed_sql_query("DELETE FROM $db_trash WHERE tr_id='$id'");
	return (sed_sql_affectedrows());
}

/**
 * Get an item from trash
 *
 * @param int $id Trash item ID
 * @return mixed
 */
function sed_trash_get($id)
{
	global $db_trash;

	$sql = sed_sql_query("SELECT * FROM $db_trash WHERE tr_id='$id' LIMIT 1");
	if ($res = sed_sql_fetchassoc($sql)) {
		$res['tr_datas'] = unserialize($res['tr_datas']);
		return ($res);
	} else {
		return (FALSE);
	}
}

/**
 * Adding an item to trash (restore path)
 *
 * @param array $dat Data item from trash
 * @param string $db Name of DB table to restore item
 * @return mixed
 */
function sed_trash_insert($dat, $db)
{
	$columns = array();
	$datas = array();
	foreach ($dat as $k => $v) {
		$columns[] = $k;
		$datas[] = "'" . sed_sql_prep($v) . "'";
	}
	$sql = sed_sql_query("INSERT INTO $db (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $datas) . ")");
	return (TRUE);
}

/**
 * Restore an item from trash
 *
 * @param int $id Trash item ID
 * @return mixed
 */
function sed_trash_restore($id)
{
	global $db_trash;

	$res = sed_trash_get($id);

	if (!is_array($res) || empty($res['tr_type'])) {
		return (FALSE);
	}

	switch ($res['tr_type']) {
		case 'comment':
			global $db_com, $db_trash;
			$root_id = $res['tr_itemid'];
			if (strpos($root_id, '-') !== false) {
				$root_id = strtok($root_id, '-');
			}
			$to_restore = array();
			$sql = sed_sql_query("SELECT tr_id, tr_itemid, tr_datas FROM $db_trash WHERE tr_type='comment' AND (tr_itemid='" . sed_sql_prep($root_id) . "' OR tr_itemid LIKE '" . sed_sql_prep($root_id) . "-%') ORDER BY tr_itemid");
			while ($row = sed_sql_fetchassoc($sql)) {
				$to_restore[] = array('tr_id' => $row['tr_id'], 'tr_itemid' => $row['tr_itemid'], 'tr_datas' => unserialize($row['tr_datas']));
			}
			$com_code = '';
			foreach ($to_restore as $item) {
				sed_trash_insert($item['tr_datas'], $db_com);
				sed_trash_delete($item['tr_id']);
				$com_id = $item['tr_datas']['com_id'];
				$com_code = $item['tr_datas']['com_code'];
				sed_log("Comment #" . $com_id . " restored from the trash can.", 'adm');
			}
			if ($com_code && mb_substr($com_code, 0, 1) == 'p') {
				global $db_pages;
				$page_id = mb_substr($com_code, 1, 10);
				sed_sql_query("UPDATE $db_pages SET page_comcount='" . sed_get_comcount($com_code) . "' WHERE page_id='" . (int)$page_id . "'");
			}
			return (TRUE);

		case 'forumpost':
			if (function_exists('sed_forum_resynctopic')) {
				global $db_forum_topics, $db_forum_posts;
				$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE ft_id='" . $res['tr_datas']['fp_topicid'] . "'");

				if ($row = sed_sql_fetchassoc($sql)) {
					sed_trash_insert($res['tr_datas'], $db_forum_posts);
					sed_log("Post #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
					sed_forum_resynctopic($res['tr_datas']['fp_topicid']);
					sed_forum_sectionsetlast($res['tr_datas']['fp_sectionid']);
					sed_forum_resync($res['tr_datas']['fp_sectionid']);
					return (TRUE);
				} else {
					$sql1 = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumtopic' AND tr_itemid='q" . $res['tr_datas']['fp_topicid'] . "'");
					if ($row1 = sed_sql_fetchassoc($sql1)) {
						sed_trash_restore($row1['tr_id']);
						sed_trash_delete($row1['tr_id']);
					}
				}
			}
			break;

		case 'forumtopic':
			if (function_exists('sed_forum_resynctopic')) {
				global $db_forum_topics, $db_forum_posts;
				sed_trash_insert($res['tr_datas'], $db_forum_topics);
				sed_log("Topic #" . $res['tr_datas']['ft_id'] . " restored from the trash can.", 'adm');

				$sql = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumpost' AND tr_itemid LIKE '%-" . $res['tr_itemid'] . "'");

				while ($row = sed_sql_fetchassoc($sql)) {
					$res2 = sed_trash_get($row['tr_id']);
					sed_trash_insert($res2['tr_datas'], $db_forum_posts);
					sed_trash_delete($row['tr_id']);
					sed_log("Post #" . $res2['tr_datas']['fp_id'] . " restored from the trash can (belongs to topic #" . $res2['tr_datas']['fp_topicid'] . ").", 'adm');
				}

				sed_forum_resynctopic($res['tr_itemid']);
				sed_forum_sectionsetlast($res['tr_datas']['ft_sectionid']);
				sed_forum_resync($res['tr_datas']['ft_sectionid']);
				return (TRUE);
			}
			break;

		case 'page':
			global $db_pages, $db_structure;
			sed_trash_insert($res['tr_datas'], $db_pages);
			sed_log("Page #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='" . $res['tr_itemid'] . "'");
			$row = sed_sql_fetchassoc($sql);
			$sql = sed_sql_query("SELECT structure_id FROM $db_structure WHERE structure_code='" . $row['page_cat'] . "'");
			if (sed_sql_numrows($sql) == 0) {
				sed_structure_newcat('restored', 999, 'RESTORED', '', '', 0);
				$sql = sed_sql_query("UPDATE $db_pages SET page_cat='restored' WHERE page_id='" . $res['tr_itemid'] . "'");
			}
			return (TRUE);

		case 'pm':
			global $db_pm;
			sed_trash_insert($res['tr_datas'], $db_pm);
			sed_log("Private message #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			return (TRUE);

		case 'user':
			global $db_users;
			sed_trash_insert($res['tr_datas'], $db_users);
			sed_log("User #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			return (TRUE);

		default:
			return (FALSE);
	}
}
