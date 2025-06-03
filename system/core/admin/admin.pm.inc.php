<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.pm.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pm', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=pm")] =  $L['Private_Messages'];

$admintitle = $L['Private_Messages'];

$t = new XTemplate(sed_skinfile('admin.pm', false, true));

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_PM_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=core&p=pm"));
	$t->parse("ADMIN_PM.PM_BUTTONS.PM_BUTTONS_CONFIG");
	$t->parse("ADMIN_PM.PM_BUTTONS");
}

$totalpmdb = sed_sql_rowcount($db_pm);
$totalpmsent = sed_stat_get('totalpms');

$t->assign(array(
	"PM_TOTALMP_DB" => $totalpmdb,
	"PM_TOTALMP_SEND" => $totalpmsent
));

$t->assign("ADMIN_PM_TITLE", $admintitle);

$t->parse("ADMIN_PM");

$adminmain .= $t->text("ADMIN_PM");
