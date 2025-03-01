<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/header.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Global header
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

sed_add_javascript('system/javascript/core.js', true);

/* === Hook === */
$extp = sed_getextplugins('header.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$out['logstatus'] = ($usr['id'] > 0) ? $L['hea_youareloggedas'] . ' ' . $usr['name'] : $L['hea_youarenotlogged'];
$out['userlist'] = (sed_auth('users', 'a', 'R')) ? "<a href=\"" . sed_url("users") . "\">" . $L['hea_users'] . "</a>" : '';
$out['metas'] = sed_htmlmetas($out['subdesc'], $out['subkeywords']) . $moremetas;

sed_add_javascript($morejavascript);
$out['javascript'] = sed_javascript();

sed_add_css($morecss);
$out['css'] = sed_css();

$out['pmreminder'] = '';
$out['adminpanel'] = '';

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle']);
$out['subtitle'] = (empty($out['subtitle'])) ? sed_title('defaulttitle', $title_tags, $title_data) : $out['subtitle'];
/**/

$out['currenturl'] = sed_getcurrenturl();
$out['canonical_url'] = empty($out['canonical_url']) ? str_replace('&', '&amp;', $sys['canonical_url']) : $out['canonical_url'];  // New in 175
$out['register_link'] = sed_url("users", "m=register");  // New in 175
$out['auth_link'] = sed_url("users", "m=auth");  // New in 175
$out['whosonline_link'] = sed_url("plug", "e=whosonline");  // New in 175

if (sed_auth('page', 'any', 'A')) {
	$sqltmp2 = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
	$sys['pagesqueued'] = sed_sql_result($sqltmp2, 0, 'COUNT(*)');

	if ($sys['pagesqueued'] > 0) {
		$out['notices'] .= $L['hea_valqueues'];

		if ($sys['pagesqueued'] == 1) {
			$out['notices'] .= "<a href=\"" . sed_url("admin", "m=page") . "\">" . "1 " . $L['Page'] . "</a> ";
		} elseif ($sys['pagesqueued'] > 1) {
			$out['notices'] .= "<a href=\"" . sed_url("admin", "m=page") . "\">" . $sys['pagesqueued'] . " " . $L['Pages'] . "</a> ";
		}
	}
}

if (!empty($msg) && isset($cfg['msg_status'][$msg])) {
	sed_sendheaders('text/html', $cfg['msg_status'][$msg]);
} else {
	sed_sendheaders();
}

/* === Hook === */
$extp = sed_getextplugins('header.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if ($cfg['enablecustomhf']) {
	$mskin = sed_skinfile(array('header', mb_strtolower($location)));
} else {
	$mskin = sed_skinfile('header');
}

$t = new XTemplate($mskin);

$t->assign(array(
	"HEADER_TITLE" => $out['subtitle'],
	"HEADER_METAS" => $out['metas'],
	"HEADER_DOCTYPE" => $cfg['doctype'],
	"HEADER_CSS" => $out['css'],
	"HEADER_CANONICAL_URL" => $out['canonical_url'], // New in 175
	"HEADER_LOGSTATUS" => $out['logstatus'],
	"HEADER_WHOSONLINE" => $out['whosonline'],
	"HEADER_TOPLINE" => $cfg['topline'],
	"HEADER_BANNER" => $cfg['banner'],
	"HEADER_GMTTIME" => $usr['gmttime'],
	"HEADER_USERLIST" => $out['userlist'],
	"HEADER_NOTICES" => $out['notices']
));

if ($usr['id'] > 0) {
	$out['adminpanel'] = (sed_auth('admin', 'any', 'R')) ? "<a href=\"" . sed_url("admin") . "\">" . $L['hea_administration'] . "</a>" : '';
	$out['loginout_url'] = sed_url("users", "m=logout&" . sed_xg());
	$out['loginout'] = "<a href=\"" . $out['loginout_url'] . "\">" . $L['hea_logout'] . "</a>";
	$out['profile'] = "<a href=\"" . sed_url("users", "m=profile") . "\">" . $L['hea_profile'] . "</a>";
	$out['pms'] = ($cfg['disable_pm']) ? '' : "<a href=\"" . sed_url("pm") . "\">" . $L['hea_private_messages'] . "</a>";
	$out['pfs'] = ($cfg['disable_pfs'] || !sed_auth('pfs', 'a', 'R') || $sed_groups[$usr['maingrp']]['pfs_maxtotal'] == 0 || 	$sed_groups[$usr['maingrp']]['pfs_maxfile'] == 0) ? '' : "<a href=\"" . sed_url("pfs") . "\">" . $L['hea_mypfs'] . "</a>";
	$out['pageadd'] = sed_auth('page', 'any', 'W') ? "<a href=\"" . sed_url("page", "m=add") . "\">" . $L['hea_pageadd'] . "</a>" : "";

	if (!$cfg['disable_pm']) {
		if ($usr['newpm']) {
			$sqlpm = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=0");
			$usr['messages'] = sed_sql_result($sqlpm, 0, 'COUNT(*)');
		}
		$out['pmreminder'] = "<a href=\"" . sed_url("pm") . "\">";
		$out['pmreminder'] .= ($usr['messages'] > 0) ? $usr['messages'] . ' ' . $L['hea_privatemessages'] : $L['hea_noprivatemessages'];
		$out['pmreminder'] .= "</a>";
	}

	if (!empty($out['notices'])) $t->parse("HEADER.USER.HEADER_NOTICES");

	$t->assign(array(
		"HEADER_USER_NAME" => $usr['name'],
		"HEADER_USER_ADMINPANEL" => $out['adminpanel'],
		"HEADER_USER_LOGINOUT" => $out['loginout'],
		"HEADER_USER_PROFILE" => $out['profile'],
		"HEADER_USER_PMS" => $out['pms'],
		"HEADER_USER_PFS" => $out['pfs'],
		"HEADER_USER_PMREMINDER" => $out['pmreminder'],
		"HEADER_USER_PAGEADD" => $out['pageadd'],
		"HEADER_USER_MESSAGES" => $usr['messages']
	));

	$t->parse("HEADER.USER");
} else {
	$out['guest_username'] = "<input type=\"text\" name=\"rusername\" size=\"12\" maxlength=\"32\" />";
	$out['guest_password'] = "<input type=\"password\" name=\"rpassword\" size=\"12\" maxlength=\"32\" />";
	$out['guest_register'] = "<a href=\"" . sed_url("users", "m=register") . "\">" . $L["Register"] . "</a>";
	$out['guest_cookiettl'] = "<select name=\"rcookiettl\" size=\"1\">";
	$out['guest_cookiettl'] .= "<option value=\"0\" selected=\"selected\">" . $L['No'] . "</option>";

	$i = array(1800, 3600, 7200, 14400, 28800, 43200, 86400, 172800, 259200, 604800, 1296000, 2592000, 5184000);

	foreach ($i as $k => $x) {
		$out['guest_cookiettl'] .= ($x <= $cfg['cookielifetime']) ? "<option value=\"$x\">" . sed_build_timegap($sys['now_offset'], $sys['now_offset'] + $x) . "</option>" : '';
	}
	$out['guest_cookiettl'] .= "</select>";

	$t->assign(array(
		"HEADER_GUEST_USERNAME" => $out['guest_username'],
		"HEADER_GUEST_PASSWORD" => $out['guest_password'],
		"HEADER_GUEST_REGISTER" => $out['guest_register'],
		"HEADER_GUEST_COOKIETTL" => $out['guest_cookiettl']
	));

	$t->parse("HEADER.GUEST");
}

/* === Hook === */
$extp = sed_getextplugins('header.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("HEADER");
$t->out("HEADER");
