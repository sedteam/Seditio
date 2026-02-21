<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/pfs.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PFS loader
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_PFS', TRUE);
$location = 'PFS';
$z = 'pfs';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('pfs'));

$module_path = SED_ROOT . '/modules/pfs/';
$m = sed_import('m', 'G', 'ALP', 16);

switch ($m) {
	case 'view':
		sed_dieifdisabled_part('pfs', 'pfs.view');
		require($module_path . 'pfs.view.php');
		break;

	case 'edit':
		sed_dieifdisabled_part('pfs', 'pfs.edit');
		require($module_path . 'pfs.edit.php');
		break;

	case 'editfolder':
		sed_dieifdisabled_part('pfs', 'pfs.editfolder');
		require($module_path . 'pfs.editfolder.php');
		break;

	default:
		sed_dieifdisabled_part('pfs', 'pfs.main');
		require($module_path . 'pfs.main.php');
		break;
}
