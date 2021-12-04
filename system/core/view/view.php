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

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

switch($m)
	{
	default:
	require(SED_ROOT . '/system/core/view/view.inc.php');
	break;
	}

?>