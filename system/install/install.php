<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=install.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
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
@include('datas/config.php');

if (!empty($cfg['mysqlhost']) || !empty($cfg['mysqldb']))
	{
	require(SED_ROOT . '/system/database.'.$cfg['sqldb'].'.php');
	$connection_id = sed_sql_connect($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword'], $cfg['mysqldb']);
	sed_sql_set_charset($connection_id, 'utf8');
	
	if (sed_stat_get('installed')==5)
		{
		header("Location: index.php");
		exit;
		}
	}

require('install.main.php');

?>