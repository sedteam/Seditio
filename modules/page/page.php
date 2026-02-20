<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Pages loader (single entry point)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_PAGE', TRUE);
$location = 'Pages';
$z = 'page';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('page'));

$module_path = SED_ROOT . '/modules/page/';
$m = sed_import('m', 'G', 'ALP', 16);
$c = sed_import('c', 'G', 'TXT');
$id = sed_import('id', 'G', 'INT');
$al = sed_import('al', 'G', 'TXT');

if ($m == 'add') {
	sed_dieifdisabled_part('page', 'page.add');
	require($module_path . 'page.add.php');
	return;
}
if ($m == 'edit') {
	sed_dieifdisabled_part('page', 'page.edit');
	require($module_path . 'page.edit.php');
	return;
}
/* List context: category listing (c set, no single page id/al) */
if (!empty($c) && empty($id) && empty($al)) {
	sed_dieifdisabled_part('page', 'page.list');
	require($module_path . 'page.list.php');
	return;
}
/* Default: single page view */
sed_dieifdisabled_part('page', 'page.main');
require($module_path . 'page.main.php');
