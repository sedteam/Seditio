<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$c = sed_import('c', 'G', 'TXT');
$id = sed_import('id', 'G', 'TXT');
$m = sed_import('m', 'G', 'ALP', 24);
$mn = sed_import('mn', 'G', 'ALP', 24);
$po = sed_import('po', 'G', 'TXT');
$p = sed_import('p', 'G', 'TXT');
$l = sed_import('l', 'G', 'TXT');
$o = sed_import('o', 'P', 'TXT');
$w = sed_import('w', 'P', 'TXT');
$u = sed_import('u', 'P', 'TXT');
$s = sed_import('s', 'G', 'ALP', 24);
$msg = sed_import('msg', 'G', 'ALP');
$num = sed_import('num', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'any');
sed_block($usr['auth_read']);

$enabled[0] = $L['Disabled'];
$enabled[1] = $L['Enabled'];

$adminmain = '';
$adminwarnings = '';

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}

$sys['inc'] = (empty($m)) ? 'admin.home' : "admin.$m";
$sys['inc'] = (empty($s)) ? $sys['inc'] : $sys['inc'] . ".$s";
$sys['inc'] = SED_ROOT . '/system/core/admin/' . $sys['inc'] . '.inc.php';

if (!file_exists($sys['inc'])) {
	sed_die();
}

require($sys['inc']);

$adminmain .= (empty($adminhelp)) ? '' : "<div class=\"content-box\"><div class=\"content-box-header\"><h3>" . $L['Help'] . "</h3></div>";
$adminmain .= (empty($adminhelp)) ? '' : "<div class=\"content-box-content\">" . $adminhelp . "</div></div>";

$out['subtitle'] = $admintitle;

$adminsubtitle = isset($adminsubtitle) ? $adminsubtitle : '';

/**/
$cfg['admintitle'] = '{TITLE} - {ADMINISTRATION}';
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}', '{ADMINISTRATION}');
$title_tags[] = array('%1$s', '%2$s', '%3$s', '%4$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle'], $L['Administration']);
$out['subtitle'] = sed_title('admintitle', $title_tags, $title_data);
/**/

require(SED_ROOT . "/system/core/admin/admin.header.php");

$t = new XTemplate(sed_skinfile("admin", false, true));

$t->assign(array(
	"ADMIN_TITLE" => $admintitle,
	"ADMIN_BREADCRUMBS" => sed_admin_breadcrumbs($urlpaths),
	"ADMIN_SUBTITLE" => $adminsubtitle,
	"ADMIN_URL" => sed_url('admin'),
	"ADMIN_MAIN" => $adminmain,
));

if (!empty($msg) || !empty($adminwarnings)) {
	require(SED_ROOT . "/system/lang/$lang/message.lang.php");

	$msg_type = (array_key_exists($msg, $cfg['msgtype'])) ? $cfg['msgtype'][$msg] : 'i';

	$t->assign(array(
		"ADMIN_MESSAGE_BODY" => (!empty($adminwarnings)) ? sed_alert($adminwarnings, 'w') : sed_alert(array('title' => $L["msg" . $msg . "_0"], 'text' => $L["msg" . $msg . "_1"]), $msg_type)
	));

	$t->parse("MAIN.ADMIN_MESSAGE");
}

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/core/admin/admin.footer.php");
