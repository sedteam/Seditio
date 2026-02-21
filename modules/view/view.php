<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/view/view.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=HTML/TXT view loader
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_VIEW', TRUE);
$location = 'Views';
$z = 'view';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('view'));

sed_dieifdisabled_part('view', 'view.main');
require(SED_ROOT . '/modules/view/view.main.php');
