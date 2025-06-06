<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=index.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Home page loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_INDEX', TRUE);
$location = 'Home';
$z = 'index';

require(SED_ROOT . '/system/functions.php');
@include(SED_ROOT . '/datas/config.php');

if (empty($cfg['mysqlhost']) && empty($cfg['mysqldb'])) {
	error_reporting(E_ALL ^ E_NOTICE);
	sed_redirect(sed_url("install", "", "", true));
	exit;
}

require(SED_ROOT . '/system/common.php');
require(SED_ROOT . '/system/core/index/index.inc.php');
