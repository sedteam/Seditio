<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=users.logout.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=User authication
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

sed_check_xg();

/* === Hook === */
$extp = sed_getextplugins('users.logout');
if (is_array($extp)) {
	foreach ($extp as $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
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
	$sql = sed_sql_query("DELETE FROM $db_online WHERE online_ip='" . $usr['ip'] . "'");
	$rmdpass_secret = md5(sed_unique(16)); // New sed175
	$sql = sed_sql_query("UPDATE $db_users SET user_secret = '" . $rmdpass_secret . "', user_lastip='" . $usr['ip'] . "' WHERE user_id='" . $usr['id'] . "' LIMIT 1");
	sed_redirect(sed_url("message", "msg=102", "", true));
	exit;
} else {
	sed_redirect(sed_url("message", "msg=101", "", true));
	exit;
}
