<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.trashcan.php
Version=186
Updated=2026-sep-21
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=trashcan
Hooks=trashcan.api
File=comments.trashcan
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Type registry for trashcan API (trashcan.api)
if (isset($sed_trashcan_types) && is_array($sed_trashcan_types)) {
	$sed_trashcan_types['comment'] = array(
		'title'   => isset($L['Comment']) ? $L['Comment'] : 'Comment',
		'icon'    => 'system/img/admin/comments.png',
		'restore' => 'sed_trash_comment_restore',
		'wipe'    => 'sed_trash_comment_wipe'
	);
}

/**
 * Restore comment (and nested children) from trashcan
 *
 * @param array $data Comment record row data
 * @param mixed $itemid Comment tree item identifier (path)
 * @return bool
 */
function sed_trash_comment_restore($data, $itemid)
{
	global $db_com, $db_trash;

	$root_id = $itemid;
	if (strpos($root_id, '-') !== false) {
		$root_id = strtok($root_id, '-');
	}

	$to_restore = array();
	$sql = sed_sql_query("SELECT tr_id, tr_itemid, tr_datas FROM $db_trash WHERE tr_type='comment' AND (tr_itemid='" . sed_sql_prep($root_id) . "' OR tr_itemid LIKE '" . sed_sql_prep($root_id) . "-%') ORDER BY tr_itemid");
	while ($row = sed_sql_fetchassoc($sql)) {
		$to_restore[] = array(
			'tr_id' => $row['tr_id'],
			'tr_itemid' => $row['tr_itemid'],
			'tr_datas' => unserialize($row['tr_datas'])
		);
	}

	$com_code = '';
	foreach ($to_restore as $item) {
		sed_trash_insert($item['tr_datas'], $db_com);
		sed_trash_delete($item['tr_id']);
		$com_id = $item['tr_datas']['com_id'];
		$com_code = $item['tr_datas']['com_code'];
		sed_log("Comment #" . $com_id . " restored from the trash can.", 'adm');
	}

	if (!empty($com_code) && mb_substr($com_code, 0, 1) == 'p' && function_exists('sed_get_comcount')) {
		global $db_pages;
		$page_id = mb_substr($com_code, 1, 10);
		sed_sql_query("UPDATE $db_pages SET page_comcount='" . sed_get_comcount($com_code) . "' WHERE page_id='" . (int)$page_id . "'");
	}

	return true;
}

/**
 * Wipe comment tree from trashcan
 *
 * @param array $data Comment record row data
 * @param mixed $itemid Comment tree item identifier (path)
 * @return bool
 */
function sed_trash_comment_wipe($data, $itemid)
{
	global $db_trash;
	$path = $itemid;
	sed_sql_query("DELETE FROM $db_trash WHERE tr_type='comment' AND (tr_itemid='" . sed_sql_prep($path) . "' OR tr_itemid LIKE '" . sed_sql_prep($path) . "-%')");
	return true;
}
