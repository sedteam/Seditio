<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/admin/pm.admin.stat.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=PM statistics block (fragment)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$totalpmdb = sed_sql_rowcount($db_pm);
$totalpmsent = sed_stat_get('totalpms');

$t = new XTemplate(sed_skinfile(array('admin', 'pm', 'stat'), false, true));
$t->assign(array(
	"PM_TOTALMP_DB" => $totalpmdb,
	"PM_TOTALMP_SEND" => $totalpmsent
));
$t->parse("ADMIN_PM_STAT");
$adminmain .= $t->text("ADMIN_PM_STAT");
