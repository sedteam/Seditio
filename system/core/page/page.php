<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org

[BEGIN_SED]
File=page.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Pages loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_PAGE', TRUE);
$location = 'Pages';
$z = 'page';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_page']);

switch($m)
	{
	case 'add':
	require(SED_ROOT . '/system/core/page/page.add.inc.php');
	break;

	case 'edit':
	require(SED_ROOT . '/system/core/page/page.edit.inc.php');
	break;

	default:
	require(SED_ROOT . '/system/core/page/page.inc.php');
	break;
	}

?>
