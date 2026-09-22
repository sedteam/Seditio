<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/inc/trashcan.functions.php
Version=186
Updated=2026-sep-21
Type=Plugin
Description=Trash API (extensible via trashcan.api hook)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Loads trashcan registered types from modules/plugins via trashcan.api hook
 *
 * @return array
 */
function sed_trash_load_types()
{
	global $sed_trashcan_types, $L;

	if (!is_array($sed_trashcan_types)) {
		$sed_trashcan_types = array();

		/* === Hook: trashcan.api === */
		foreach (sed_getextplugins('trashcan.api') as $pl) {
			include $pl;
		}
		/* ===== */
	}

	return $sed_trashcan_types;
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
	global $db_trash, $sed_trashcan_types;

	$res = sed_trash_get($id);

	if (!is_array($res) || empty($res['tr_type'])) {
		return (FALSE);
	}

	sed_trash_load_types();

	$type = $res['tr_type'];

	if (!empty($sed_trashcan_types[$type]['restore']) && function_exists($sed_trashcan_types[$type]['restore'])) {
		$restore_callback = $sed_trashcan_types[$type]['restore'];
		return $restore_callback($res['tr_datas'], $res['tr_itemid']);
	}

	return (FALSE);
}
