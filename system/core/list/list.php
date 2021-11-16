<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=list.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Pages loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_LIST', TRUE);
$location = 'Pages';
$z = 'page';

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

sed_dieifdisabled($cfg['disable_page']);

switch($m)
	{
	default:
	require('system/core/list/list.inc.php');
	break;
	}

?>
