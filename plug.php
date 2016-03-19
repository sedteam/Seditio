<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plug.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=Plugin loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
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
