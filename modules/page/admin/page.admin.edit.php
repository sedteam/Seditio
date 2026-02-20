<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.edit.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Edit page (admin)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$id = sed_import('id', 'G', 'INT');

$urlpaths = array();
$urlpaths[sed_url("admin", "m=page")] = $L['Pages'];
$urlpaths[sed_url("admin", "m=page&s=edit&id=" . $id)] = $L['Edit'];

$admintitle = $L['Edit'];

sed_dieifdisabled_part('page', 'page.edit');
require(SED_ROOT . '/modules/page/page.edit.php');
