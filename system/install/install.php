<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=install.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Installation Seditio
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_ADMIN', TRUE);
define('SED_INSTALL', TRUE);
$location = 'Installation';
$z = 'install';

error_reporting(E_ALL ^ E_NOTICE);
require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/functions.admin.php');
@include(SED_ROOT . '/datas/config.php');

if (!empty($cfg['mysqlhost']) || !empty($cfg['mysqldb'])) {
	require(SED_ROOT . '/system/database.' . $cfg['sqldb'] . '.php');
	$connection_id = sed_sql_connect($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword'], $cfg['mysqldb']);
	sed_sql_set_charset($connection_id, 'utf8');

	if (sed_stat_get('installed') == 1) {
		sed_redirect(sed_url("index", "", "", true));
		exit;
	}
}

$cfg['sefurls'] = TRUE;

require(SED_ROOT . '/system/install/install.main.php');
