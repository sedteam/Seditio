<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=index.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=Home page loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_INDEX', TRUE);
$location = 'Home';
$z = 'index';

require('system/functions.php');
@include('datas/config.php');

if (empty($cfg['mysqlhost']) && empty($cfg['mysqldb']))
	{
	header("Location: install.php");
	exit;
	}

require('system/common.php');
require('system/core/index/index.inc.php');

?>