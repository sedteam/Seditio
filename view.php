<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=view.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=View loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_VIEW', TRUE);
$location = 'Views';
$z = 'view';

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

switch($m)
	{
	default:
	require('system/core/view/view.inc.php');
	break;
	}

?>
