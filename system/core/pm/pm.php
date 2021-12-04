<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=pm.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Private messages
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_PM', TRUE);
$location = 'Private_Messages';
$z = 'pm';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_pm']);

switch($m)
	{
	case 'send':
	require(SED_ROOT . '/system/core/pm/pm.send.inc.php');
	break;

	case 'edit':
	require(SED_ROOT . '/system/core/pm/pm.edit.inc.php');
	break;

	default:
	require(SED_ROOT . '/system/core/pm/pm.inc.php');
	break;
	}

?>