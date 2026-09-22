<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.trashcan.php
Version=186
Updated=2026-sep-21
Type=Module
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=page
Part=trashcan
Hooks=trashcan.api
File=page.trashcan
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Type registry for trashcan API (trashcan.api)
if (isset($sed_trashcan_types) && is_array($sed_trashcan_types)) {
	$sed_trashcan_types['page'] = array(
		'title'   => isset($L['Page']) ? $L['Page'] : 'Page',
		'icon'    => 'system/img/admin/page.png',
		'restore' => 'sed_trash_page_restore'
	);
}

/**
 * Restore page from trashcan
 *
 * @param array $data Page record row data
 * @param mixed $itemid Page ID
 * @return bool
 */
function sed_trash_page_restore($data, $itemid)
{
	global $db_pages, $db_structure;

	sed_trash_insert($data, $db_pages);
	sed_log("Page #" . $itemid . " restored from the trash can.", 'adm');

	$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='" . sed_sql_prep($itemid) . "'");
	$row = sed_sql_fetchassoc($sql);
	$sql = sed_sql_query("SELECT structure_id FROM $db_structure WHERE structure_code='" . sed_sql_prep($row['page_cat']) . "'");
	if (sed_sql_numrows($sql) == 0) {
		sed_structure_newcat('restored', 999, 'RESTORED', '', '', 0);
		sed_sql_query("UPDATE $db_pages SET page_cat='restored' WHERE page_id='" . sed_sql_prep($itemid) . "'");
	}

	return true;
}
