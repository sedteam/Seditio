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

define('SED_CODE', true);
define('SED_GALLERY', TRUE);
$location = 'Gallery';
$z = 'gallery';

require('system/functions.php');
require('system/config.extensions.php');
require('datas/config.php');
require('system/common.php');

sed_dieifdisabled($cfg['disable_gallery']);
$gd_supported_sql = "('".implode("','", $cfg['gd_supported'])."')";

sed_dieifdisabled($cfg['disable_gallery']);

$m = 'home';

$v = sed_import('v','G','TXT');
$f = sed_import('f','G','INT');
$id = sed_import('id','G','INT');

if ($f>0)
	{ $m = 'browse'; }

if ($id>0)
	{ $m = 'details'; }

switch($m)
	{
  case 'details':
	require('system/core/gallery/gallery.details.inc.php');
	break;

	case 'browse':
	require('system/core/gallery/gallery.browse.inc.php');
	break;

	default:
	require('system/core/gallery/gallery.home.inc.php');
	break;
	}


?>