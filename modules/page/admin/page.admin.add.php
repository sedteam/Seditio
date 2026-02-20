<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.add.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Add page (admin)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$urlpaths = array();
$urlpaths[sed_url("admin", "m=page")] = $L['Pages'];
$urlpaths[sed_url("admin", "m=page&s=add")] = $L['addnewentry'];

$admintitle = $L['addnewentry'];

sed_dieifdisabled_part('page', 'page.add');
require(SED_ROOT . '/modules/page/page.add.php');
