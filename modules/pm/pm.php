<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Private messages
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_PM', TRUE);
$location = 'Private_Messages';
$z = 'pm';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
@include(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('pm'));

switch ($m) {
	case 'send':
		sed_dieifdisabled_part('pm', 'pm.send');
		require(SED_ROOT . '/modules/pm/pm.send.php');
		break;

	case 'edit':
		sed_dieifdisabled_part('pm', 'pm.edit');
		require(SED_ROOT . '/modules/pm/pm.edit.php');
		break;

	default:
		sed_dieifdisabled_part('pm', 'pm.main');
		require(SED_ROOT . '/modules/pm/pm.main.php');
		break;
}
