<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=install.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=Installation Seditio
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_ADMIN', TRUE);
define('SED_INSTALL', TRUE);
$location = 'Installation';
$z = 'install';

error_reporting(E_ALL ^ E_NOTICE);
require('system/functions.php');
require('system/functions.admin.php');
@include('datas/config.php');

if (!empty($cfg['mysqlhost']) || !empty($cfg['mysqldb']))
	{
	require('system/database.'.$cfg['sqldb'].'.php');
  $connection_id = sed_sql_connect($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword'], $cfg['mysqldb']);
  sed_sql_set_charset($connection_id, 'utf8');
	
  if (sed_stat_get('installed')==1)
	 {
	 header("Location: index.php");
	 exit;
	 }
	}

require('system/install/install.main.php');

?>