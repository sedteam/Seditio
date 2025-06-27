<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.home.inc.php
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

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=home")] = $L['Home'];
$admintitle = $L['Home'];

$pagesqueued = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='1'");
$pagesqueued = sed_sql_result($pagesqueued, 0, "COUNT(*)");

$upgstat = '';

$sys['user_istopadmin'] = sed_auth('admin', 'a', 'A');

if ($cfg['trash_prunedelay'] > 0) {
	$timeago = $sys['now_offset'] - ($cfg['trash_prunedelay'] * 86400);
	$sqltmp = sed_sql_query("DELETE FROM $db_trash WHERE tr_date<$timeago");
	$deleted = sed_sql_affectedrows();
	if ($deleted > 0) {
		sed_log($deleted . ' old item(s) removed from the trashcan, older than ' . $cfg['trash_prunedelay'] . ' days', 'adm');
	}
}

$t = new XTemplate(sed_skinfile('admin.home', false, true));

// --------------------------

if (!function_exists('gd_info') && $cfg['th_amode'] != 'Disabled') {
	$adminwarnings .= $L['adm_nogd'] . "<br />";
}

$t->assign(array(
	"HOME_PAGE_QUEUED" => sed_link(sed_url("admin", "m=page"), $L['Pages'] . " : " . $pagesqueued),
	"HOME_PAGE_ADDNEWENTRY" => sed_linkif(sed_url("page", "m=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'))
));

// --------------------------

if ($sys['user_istopadmin']) {
	if ($a == 'force') {
		sed_check_xg();
		$forcesql = sed_import('forcesql', 'P', 'INT');
		sed_stat_set('version', $forcesql);
		sed_redirect(sed_url("admin", "", "", true));
		exit;
	}

	if (!($cfg['sqlversion'] = sed_stat_get('version'))) {
		sed_stat_create('version', $cfg['version']);
		$cfg['sqlversion'] = $cfg['version'];
	}

	$t->assign(array(
		"UPG_FORCESQLVERSION_SEND" => sed_url("admin", "a=force&" . sed_xg()),
		"UPG_VERSION" => $cfg['version'],
		"UPG_SQLVERSION" => $cfg['sqlversion']
	));

	if ($cfg['version'] > $cfg['sqlversion']) {
		$upgstat .=  $L['upg_codeisnewer'];
		$upg_file = SED_ROOT . "/system/upgrade/upgrade_" . $cfg['sqlversion'] . "_" . $cfg['version'] . ".php";
		$status_ok = FALSE;

		if (file_exists($upg_file)) {
			$upgstat .= "<br /><strong>" . sed_link(sed_url("admin", "m=upgrade&" . sed_xg()), $L['upg_upgradenow']) . "</strong>";
			$upgstat .= "<br />" . $L['upg_manual'];
		} else {
			$upgstat .= "<br /><strong>" . $L['upg_upgradenotavail'] . "</strong>";
		}
	} elseif ($cfg['version'] == $cfg['sqlversion']) {
		$status_ok = TRUE;
		$upgstat .= $L['upg_codeissame'];
	} elseif ($cfg['version'] < $cfg['sqlversion']) {
		$upgstat .= $L['upg_codeisolder'];
	}

	$forcesql = $L['upg_force'];
	$forcesql .= "<select name=\"forcesql\" size=\"1\">";

	foreach ($cfg['versions_list'] as $x) {
		$selected = ($x == $cfg['sqlversion']) ? "selected=\"selected\"" : '';
		$forcesql .= "<option value=\"$x\" $selected>" . $x . "</option>";
	}

	$forcesql .= "</select>";

	$t->assign(array(
		"UPG_CHECKSTATUS" => $upgstat,
		"UPG_STATUS" => ($status_ok) ? $out['ic_checked'] : $out['ic_warning'],
		"UPG_FORCESQL" => $forcesql
	));
	$t->parse("ADMIN_HOME.ADMIN_UPG_TAB");
	$t->parse("ADMIN_HOME.ADMIN_UPG_TABBODY");
}

$mysql_ver = sed_sql_query("SELECT VERSION() as mysql_version");

$t->assign(array(
	"INFOS_PHPVERSION" => (function_exists('phpversion')) ? @phpversion() : '',
	"INFOS_ZENDVERSION" => (function_exists('zend_version')) ? @zend_version() : '',
	"INFOS_INTERFACE" => (function_exists('php_sapi_name')) ? @php_sapi_name() : '',
	"INFOS_OS" => (function_exists('php_uname')) ? @php_uname() : '',
	"INFOS_MYSQL" => sed_sql_result($mysql_ver, 0, "mysql_version")
));

$t->parse("ADMIN_HOME.ADMIN_INFOS_TAB");
$t->parse("ADMIN_HOME.ADMIN_INFOS_TABBODY");

$t->assign("ADMIN_HOME_TITLE", $admintitle);

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.home');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("ADMIN_HOME");
$adminmain .= $t->text("ADMIN_HOME");
