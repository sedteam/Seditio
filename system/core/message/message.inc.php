<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=message.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Messages
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('message', 'a');
sed_block($usr['auth_read']);

$msg = sed_import('msg', 'G', 'INT');
$num = sed_import('num', 'G', 'INT');
$rc = sed_import('rc', 'G', 'INT');
$redirect = sed_import('redirect', 'G', 'SLU');

$redirect = ($redirect == "/") ? "" : $redirect;

require("system/lang/$lang/message.lang.php");

unset($r, $rd, $ru);

switch ($msg) {

	/* ======== Users ======== */

	case '100':

		$message = $L['msg100_0'];
		$body = $L['msg100_1'];
		$rd = 4;
		$ru = (!empty($redirect)) ? "&redirect=" . $redirect : '';
		$ru = sed_url("users", "m=auth" . $ru, "", true, true);

		break;

	case '101':
		$message = $L['msg101_0'];
		$body = $L['msg101_1'];
		break;

	case '102':
		$message = $L['msg102_0'];
		$body = $L['msg102_1'];
		$r = 1;
		$rd = 4;
		$ru = sed_url("index", "", "", true, true);
		break;

	case '104':
		$message = $L['msg104_0'];
		$body = $L["msg104_1"];
		$rd = 4;
		$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	case '105':
		$message = $L['msg105_0'];
		$body = $L['msg105_1'];
		break;

	case '106':
		$message = $L['msg106_0'];
		$body = $L['msg106_1'];
		break;

	case '109':
		$message = $L['msg109_0'];
		$body = $L['msg109_1'];
		break;

	case '110':
		$message = $L['msg110_0'];
		$body = $L['msg110_1'];
		break;

	case '113':
		$message = $L['msg113_0'];
		$body = $L['msg113_1'];
		$rd = 2;
		$ru = sed_url("users", "m=profile", "", true, true);
		break;

	case '117':
		$message = $L['msg117_0'];
		$body = $L['msg117_1'];
		break;

	case '118':
		$message = $L['msg118_0'];
		$body = $L['msg118_1'];
		break;

	case '151':
		$message = $L['msg151_0'];
		$body = $L['msg151_1'];
		break;

	case '152':
		$message = $L['msg152_0'];
		$body = $L['msg152_1'];
		break;

	case '153':
		$message = $L['msg153_0'];
		$body = $L['msg153_1'];
		if ($num > 0) {
			$body .= "<br />(-> " . date($cfg['dateformat'], $num) . "GMT" . ")";
		}
		break;

	case '157':
		$message = $L['msg157_0'];
		$body = $L['msg157_1'];
		break;

	/* ======== General ======== */

	case '300':
		$message = $L['msg300_0'];
		$body = $L['msg300_1'];
		break;

	/* ======== Error Pages ========= */

	case '400':
		$message = $L['msg400_0'];
		$body = $L["msg400_1"];
		$rd = 5;
		$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	case '401':
		$message = $L['msg401_0'];
		$body = $L["msg401_1"];
		$rd = 5;
		$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	case '403':
		$message = $L['msg403_0'];
		$body = $L["msg403_1"];
		$rd = 5;
		$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	case '404':
		$message = $L['msg404_0'];
		$body = $L["msg404_1"];
		//$rd = 5;
		//$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	case '500':
		$message = $L['msg500_0'];
		$body = $L["msg500_1"];
		$rd = 5;
		$ru = empty($redirect) ? sed_url("index", "", "", true, true) : str_replace("&", "&amp;", base64_decode($redirect));
		break;

	/* ======== Private messages ======== */

	case '502':
		$message = $L['msg502_0'];
		$body = $L['msg502_1'] . sed_link(sed_url("pm", "", "", true, true), $L['msg502_2']) . $L['msg502_3'];
		$rd = 4;
		$ru = sed_url("pm");
		break;

	/* ======== Private messages ======== */

	case '602':
		$message = $L['msg602_0'];
		$body = $L['msg602_1'];
		break;

	case '603':
		$message = $L['msg603_0'];
		$body = $L['msg603_1'];
		break;

	/* ======== System ======== */

	case '900':
		$message = $L['msg900_0'];
		$body = $L['msg900_1'];
		break;

	case '904':
		$message = $L['msg904_0'];
		$body = $L['msg904_1'];
		break;

	case '907':
		$message = $L['msg907_0'];
		$body = $L['msg907_1'];
		break;

	case '911':
		$message = $L['msg911_0'];
		$body = $L['msg911_1'];
		break;

	case '915':
		$message = $L['msg915_0'];
		$body = $L['msg915_1'];
		break;

	case '916':
		$message = $L['msg916_0'];
		$body = $L["msg916_1"];
		$rd = 2;
		$ru = sed_url("admin");
		break;

	case '930':
		$message = $L['msg930_0'];
		$body = $L['msg930_1'];
		if ($usr['id'] == 0) {
			$rd = 4;
			$redir = (!empty($redirect)) ? "&redirect=" . $redirect : '';
			$ru = sed_url("users", "m=auth" . $redir, "", true, true);
		}
		break;

	case '940':
		$message = $L['msg940_0'];
		$body = $L["msg940_1"];
		break;

	case '950':
		$message = $L['msg950_0'];
		$body = $L['msg950_1'];
		break;

	/* ======== Default  ======== */

	default:
		$message = $L['msg950_0'];
		$body = $L['msg950_1'];
		break;
}

/* ============= */

if ($rc != '') {
	$r['100'] = sed_url("admin", "m=plug", "", true, true);
	$r['101'] = sed_url("admin", "m=hitsperday", "", true, true);
	$r['102'] = sed_url("admin", "m=polls", "", true, true);
	$r['103'] = sed_url("admin", "m=forums", "", true, true);
	$r['200'] = sed_url("users", "", "", true, true);

	$moremetas .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $r["$rc"] . "\" /><br />";
	$body .= "<br />&nbsp;<br />" . $L['msgredir'];
} elseif (isset($rd) && $rd != '') {
	$moremetas .= "<meta http-equiv=\"refresh\" content=\"" . $rd . ";url=" . $ru . "\" />";
	$body .= "<br />&nbsp;<br />" . $L['msgredir'];
}

/* === Hook === */
$extp = sed_getextplugins('message.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$out['subtitle'] = $message;

$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('defaulttitle', $title_tags, $title_data);

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/message.tpl");

$errmsg = $message;
$message .= ($usr['isadmin']) ? " (#" . $msg . ")" : '';

$t->assign("MESSAGE_TITLE", $message);
$t->assign("MESSAGE_BODY", $body);

/* === Hook === */
$extp = sed_getextplugins('message.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
