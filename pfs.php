<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=pfs.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=PFS loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_PFS', TRUE);
$location = 'PFS';
$z = 'pfs';

require('system/functions.php');
require('system/config.extensions.php');
require('datas/config.php');
require('system/common.php');

sed_dieifdisabled($cfg['disable_pfs']);

switch($m)
	{
	case 'view':
	require('system/core/pfs/pfs.view.inc.php');
	break;

	case 'edit':
	require('system/core/pfs/pfs.edit.inc.php');
	break;

	case 'editfolder':
	require('system/core/pfs/pfs.editfolder.inc.php');
	break;

	default:
	require('system/core/pfs/pfs.inc.php');
	break;
	}
?>