<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=pfs.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=PFS loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_GALLERY', TRUE);
$location = 'Gallery';
$z = 'gallery';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_gallery']);

$gd_supported_sql = "('" . implode("','", $cfg['gd_supported']) . "')";

sed_dieifdisabled($cfg['disable_gallery']);

$m = 'home';

$v = sed_import('v', 'G', 'TXT');
$f = sed_import('f', 'G', 'INT');
$id = sed_import('id', 'G', 'INT');

if ($f > 0) {
	$m = 'browse';
}

if ($id > 0) {
	$m = 'details';
}

switch ($m) {
	case 'details':
		require(SED_ROOT . '/system/core/gallery/gallery.details.inc.php');
		break;

	case 'browse':
		require(SED_ROOT . '/system/core/gallery/gallery.browse.inc.php');
		break;

	default:
		require(SED_ROOT . '/system/core/gallery/gallery.home.inc.php');
		break;
}
