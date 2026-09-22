<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.auth.php
Version=186
Updated=2026-sep-21
Type=Module
Author=Seditio Team
Description=User authentication
Lock=1
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$v = sed_import('v', 'G', 'H32');
$error_string = '';

if ($usr['id'] > 0) {
	sed_redirect(sed_url("index"));
	exit;
}

/* === Hook === */
foreach (sed_getextplugins('users.auth.first') as $pl) {
		include $pl;
	}
/* ===== */

if ($a == 'check') {
	sed_shield_protect();

	/* === Hook for the plugins === */
	foreach (sed_getextplugins('users.auth.check') as $pl) {
		include $pl;
	}
	/* ===== */

	$rusername = sed_import('rusername', 'P', 'TXT', 24, TRUE);
	$rpassword = sed_import('rpassword', 'P', 'TXT', 16, TRUE);
	$rcookiettl = sed_import('rcookiettl', 'P', 'INT');

	$error_string .= (mb_strlen($rusername) < 2) ? $L['aut_usernametooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($rpassword) < 4) ? $L['aut_passwordtooshort'] . "<br />" : '';

	if (empty($error_string)) {
		$sql = sed_sql_query("SELECT user_id, user_secret, user_maingrp, user_banexpire, user_skin, user_lang, user_password, user_salt, user_passtype 
							FROM $db_users WHERE user_name='" . sed_sql_prep($rusername) . "' OR user_email = '" . sed_sql_prep($rusername) . "'");

		if ($row = sed_sql_fetchassoc($sql)) {
			$mdsalt = $row['user_salt'];
			$rmdpass = ($row['user_passtype'] == 0) ? sed_hash($rpassword, 0) : sed_hash($rpassword, 1, $mdsalt);

			if ($rmdpass != $row['user_password']) {
				sed_shield_update(7, "Log in");
				sed_log("Log in failed, wrong password : " . $rusername, 'usr');
				$error_string .= $L['msg151_1'];
			} else {
				if ($row['user_maingrp'] == 2) {
					sed_log("Log in attempt, user inactive : " . $rusername, 'usr');
					$error_string .= $L['msg152_1'];
				} elseif ($row['user_maingrp'] == 3) {
					if ($sys['now'] > $row['user_banexpire'] && $row['user_banexpire'] > 0) {
						$sql = sed_sql_query("UPDATE $db_users SET user_maingrp='4' WHERE user_id='" . $row['user_id'] . "'");
					} else {
						sed_log("Log in attempt, user banned : " . $rusername, 'usr');
						$error_string .= $L['msg153_1'];
					}
				}

				if (empty($error_string)) {
					$_SESSION['sed_sourcekey_prev'] = sed_sourcekey();
					$ruserid = $row['user_id'];
					$rdefskin = $row['user_skin'];
					$rmdpass_secret = $row['user_secret'];
					
					if ($cfg['authsecret']) {
						$rmdpass_secret = md5(sed_unique(16)); // New sed171
						sed_sql_query("UPDATE $db_users SET user_secret = '" . $rmdpass_secret . "', user_lastip='" . $usr['ip'] . "' WHERE user_id='" . $row['user_id'] . "' LIMIT 1");
					} else {
						sed_sql_query("UPDATE $db_users SET user_lastip='" . $usr['ip'] . "' WHERE user_id='" . $row['user_id'] . "' LIMIT 1");
					}
					
					if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {		
						$rcookiettl = ($rcookiettl == 0) ? 604800 : $rcookiettl;
						$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
						$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
						sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
					}

					if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
						$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
						$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
						$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
					}

					/* === Hook === */
					foreach (sed_getextplugins('users.auth.check.done') as $pl) {
		include $pl;
	}

					sed_redirect(sed_url("message", "msg=104&redirect=" . $redirect, "", true));
					exit;
				}
			}
		} else {
			sed_shield_update(7, "Log in");
			sed_log("Log in failed, user not found : " . $rusername, 'usr');
			$error_string .= $L['msg154_1'];
		}
	}
}

$out['subtitle'] = $L['aut_logintitle'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('userstitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths[sed_url("users", "m=auth")] = $L['Auth'];

/* === Hook === */
foreach (sed_getextplugins('users.auth.main') as $pl) {
		include $pl;
	}
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate(sed_skinfile('users.auth'));

if (!empty($error_string)) {
	$t->assign("USERS_AUTH_ERROR_BODY", $error_string);
	$t->parse("MAIN.USERS_AUTH_ERROR");
}

$t->assign(array(
	"USERS_AUTH_TITLE" => $L['aut_logintitle'],
	"USERS_AUTH_SEND" => sed_url("users", "m=auth&a=check&redirect=" . $redirect),
	"USERS_AUTH_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"USERS_AUTH_USER" => sed_textbox("rusername", (isset($rusername) ? $rusername : ''), 16, 100),
	"USERS_AUTH_PASSWORD" => sed_textbox("rpassword", "", 16, 32, "password", false, "password"),
	"USERS_AUTH_REGISTER" => sed_url("users", "m=register"),
	"USERS_AUTH_LOSTPASSWORD" => sed_url("plug", "e=passrecover")
));

/* === Hook === */
foreach (sed_getextplugins('users.auth.tags') as $pl) {
		include $pl;
	}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
