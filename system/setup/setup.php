<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/setup.php
Version=186
Updated=2026-jul-20
Type=Core
Author=Seditio Team
Description=Modern Seditio installation launcher
[END_SED]
==================== */

if (!defined('SED_CODE')) define('SED_CODE', TRUE);
define('SED_ADMIN', TRUE);
define('SED_INSTALL', TRUE);
$location = 'Installation';
$z = 'setup';

// Define SED_ROOT
if (!defined('SED_ROOT')) {
	$sed_root = realpath(dirname(__FILE__) . '/../../');
	define('SED_ROOT', $sed_root);
}

error_reporting(E_ALL ^ E_NOTICE);
require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/functions.admin.php');
@include(SED_ROOT . '/datas/config.php');

// If configuration exists and database parameters are set, check installation status
if (!empty($cfg['mysqlhost']) && !empty($cfg['mysqldb'])) {
	require(SED_ROOT . '/system/database.' . $cfg['sqldb'] . '.php');
	$connection_id = sed_sql_connect($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword'], $cfg['mysqldb']);
	sed_sql_set_charset($connection_id, 'utf8');

	if (sed_stat_get('installed') == 1) {
		sed_redirect(sed_url("index", "", "", true));
		exit;
	}
}

$cfg['sefurls'] = TRUE;

require(SED_ROOT . '/system/setup/setup.main.php');
