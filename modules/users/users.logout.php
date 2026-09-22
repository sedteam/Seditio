<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.logout.php
Version=186
Updated=2026-sep-21
Type=Module
Author=Seditio Team
Description=User logout
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

sed_check_xg();

/* === Hook === */
foreach (sed_getextplugins('users.logout') as $pl) {
		include $pl;
	}
/* ===== */

if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
	sed_setcookie($sys['site_id'], "", time() - 63072000, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
}

if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
	session_unset();
	session_destroy();
}

if ($usr['id'] > 0) {
	$rmdpass_secret = md5(sed_unique(16)); // New sed175
	$sql = sed_sql_query("UPDATE $db_users SET user_secret = '" . $rmdpass_secret . "', user_lastip='" . $usr['ip'] . "' WHERE user_id='" . $usr['id'] . "' LIMIT 1");
	sed_redirect(sed_url("message", "msg=102", "", true));
	exit;
} else {
	sed_redirect(sed_url("message", "msg=101", "", true));
	exit;
}
