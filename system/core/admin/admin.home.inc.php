<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.home.inc.php
Version=186
Updated=2026-sep-21
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

/* === Hook: early tasks on admin home (before breadcrumbs / template) === */
foreach (sed_getextplugins('admin.home.first') as $pl) {
	include $pl;
}
/* ===== */

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=home")] = $L['Home'];
$admintitle = $L['Home'];

$pagesqueued = 0;
if (sed_module_active('page')) {
	$sqltmp_pq = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='1'");
	$pagesqueued = sed_sql_result($sqltmp_pq, 0, "COUNT(*)");
}

$upgstat = '';

$sys['user_istopadmin'] = sed_auth('admin', 'a', 'A');

$t = new XTemplate(sed_skinfile('admin.home', false, true));

// --------------------------

// ---------- Clear Cache Action ----------
if ($a == 'clearcache') {
	sed_check_xg();
	sed_cache_clearall();
	sed_redirect(sed_url("admin", "m=home", "", true), false, array('msg' => '922'));
	exit;
}

$t->assign(array(
	"HOME_CLEARCACHE_URL" => sed_url("admin", "m=home&a=clearcache&" . sed_xg()),
	"HOME_PAGE_QUEUED" => sed_module_active('page') ? sed_link(sed_url("admin", "m=page"), $L['Pages'] . " : " . $pagesqueued) : ($L['Pages'] . " : -"),
	"HOME_PAGE_ADDNEWENTRY" => sed_linkif(sed_url("page", "m=add"), $L['addnewentry'], sed_module_active('page') && sed_auth('page', 'any', 'A'))
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
foreach (sed_getextplugins('admin.home') as $pl) {
	include $pl;
}
/* ===== */

$t->parse("ADMIN_HOME");
$adminmain .= $t->text("ADMIN_HOME");


