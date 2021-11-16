<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plug.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Plugin loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_PLUG', TRUE);
$location = 'Plugins';
$z = 'plug';

if(!empty($_GET['ajx'])) { define('SED_DISABLE_XFORM', true); } 

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

sed_dieifdisabled($cfg['disable_plug']);

switch($m)
	{
	default:
	require('system/core/plug/plug.inc.php');
	break;
	}

?>
