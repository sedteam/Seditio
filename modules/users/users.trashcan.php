<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.trashcan.php
Version=186
Updated=2026-sep-21
Type=Module
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=users
Part=trashcan
Hooks=trashcan.api
File=users.trashcan
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Type registry for trashcan API (trashcan.api)
if (isset($sed_trashcan_types) && is_array($sed_trashcan_types)) {
	$sed_trashcan_types['user'] = array(
		'title'   => isset($L['User']) ? $L['User'] : 'User',
		'icon'    => 'system/img/admin/user.png',
		'restore' => 'sed_trash_user_restore'
	);
}

/**
 * Restore User from trashcan
 *
 * @param array $data User record row data
 * @param mixed $itemid User ID
 * @return bool
 */
function sed_trash_user_restore($data, $itemid)
{
	global $db_users;

	sed_trash_insert($data, $db_users);
	sed_log("User #" . $itemid . " restored from the trash can.", 'adm');

	return true;
}
