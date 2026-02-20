<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Forums loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_FORUMS', TRUE);
$location = 'Forums';
$z = 'forums';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('forums'));

$module_path = SED_ROOT . '/modules/forums/';

switch ($m) {
	case 'topics':
		sed_dieifdisabled_part('forums', 'forums.topics');
		require($module_path . 'forums.topics.php');
		break;

	case 'posts':
		sed_dieifdisabled_part('forums', 'forums.posts');
		require($module_path . 'forums.posts.php');
		break;

	case 'editpost':
		sed_dieifdisabled_part('forums', 'forums.editpost');
		require($module_path . 'forums.editpost.php');
		break;

	case 'newtopic':
		sed_dieifdisabled_part('forums', 'forums.newtopic');
		require($module_path . 'forums.newtopic.php');
		break;

	default:
		sed_dieifdisabled_part('forums', 'forums.main');
		require($module_path . 'forums.main.php');
		break;
}
