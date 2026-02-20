<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/gallery.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Gallery module
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_GALLERY', TRUE);
$location = 'Gallery';
$z = 'gallery';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('gallery'));

$gd_supported_sql = "('" . implode("','", $cfg['gd_supported']) . "')";

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

$gallery_inc = SED_ROOT . '/modules/gallery/';
switch ($m) {
	case 'details':
		sed_dieifdisabled_part('gallery', 'gallery.details');
		require($gallery_inc . 'gallery.details.php');
		break;
	case 'browse':
		sed_dieifdisabled_part('gallery', 'gallery.browse');
		require($gallery_inc . 'gallery.browse.php');
		break;
	default:
		sed_dieifdisabled_part('gallery', 'gallery.main');
		require($gallery_inc . 'gallery.main.php');
		break;
}
