<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.php
Version=185
Updated=2026-feb-21
Type=Module
Author=Seditio Team
Description=Users loader
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_USERS', TRUE);
$location = 'Users';
$z = 'users';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('users'));

$module_path = SED_ROOT . '/modules/users/';

switch ($m) {
	case 'auth':
		require($module_path . 'users.auth.php');
		break;

	case 'register':
		sed_dieifdisabled_part('users', 'users.register');
		require($module_path . 'users.register.php');
		break;

	case 'details':
		sed_dieifdisabled_part('users', 'users.details');
		require($module_path . 'users.details.php');
		break;

	case 'edit':
		sed_dieifdisabled_part('users', 'users.edit');
		require($module_path . 'users.edit.php');
		break;

	case 'logout':
		sed_dieifdisabled_part('users', 'users.logout');
		require($module_path . 'users.logout.php');
		break;

	case 'profile':
		sed_dieifdisabled_part('users', 'users.profile');
		require($module_path . 'users.profile.php');
		break;

	default:
		sed_dieifdisabled_part('users', 'users.main');
		require($module_path . 'users.main.php');
		break;
}
