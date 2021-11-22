<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=index.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Home page loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_INDEX', TRUE);
$location = 'Home';
$z = 'index';

require(SED_ROOT . '/system/functions.php');
@include('datas/config.php');

if (empty($cfg['mysqlhost']) && empty($cfg['mysqldb']))
	{
	header("Location: install.php");
	exit;
	}

require(SED_ROOT . '/system/common.php');
require(SED_ROOT . '/system/core/index/index.inc.php');

?>