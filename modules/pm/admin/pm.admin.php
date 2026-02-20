<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/admin/pm.admin.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=PM admin (statistics via config page only; no sidebar)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pm', 'a');
sed_block($usr['isadmin']);

$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=pm")] = $L['core_pm'];

$admintitle = $L['core_pm'];

require(SED_ROOT . '/modules/pm/admin/pm.admin.stat.php');
