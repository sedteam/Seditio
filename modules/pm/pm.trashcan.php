<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.trashcan.php
Version=186
Updated=2026-sep-21
Type=Module
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=pm
Part=trashcan
Hooks=trashcan.api
File=pm.trashcan
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Type registry for trashcan API (trashcan.api)
if (isset($sed_trashcan_types) && is_array($sed_trashcan_types)) {
	$sed_trashcan_types['pm'] = array(
		'title'   => isset($L['Private_Messages']) ? $L['Private_Messages'] : 'Private Messages',
		'icon'    => 'system/img/admin/pm.png',
		'restore' => 'sed_trash_pm_restore'
	);
}

/**
 * Restore PM from trashcan
 *
 * @param array $data PM record row data
 * @param mixed $itemid PM ID
 * @return bool
 */
function sed_trash_pm_restore($data, $itemid)
{
	global $db_pm;

	sed_trash_insert($data, $db_pm);
	sed_log("Private message #" . $itemid . " restored from the trash can.", 'adm');

	return true;
}
