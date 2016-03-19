<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=page.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=Pages loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_PAGE', TRUE);
$location = 'Pages';
$z = 'page';

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

sed_dieifdisabled($cfg['disable_page']);

switch($m)
	{
	case 'add':
	require('system/core/page/page.add.inc.php');
	break;

	case 'edit':
	require('system/core/page/page.edit.inc.php');
	break;

	default:
	require('system/core/page/page.inc.php');
	break;
	}

?>
