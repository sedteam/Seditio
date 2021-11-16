<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=view.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=View loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

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
