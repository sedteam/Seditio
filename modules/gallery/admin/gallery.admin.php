<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/admin/gallery.admin.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Gallery admin panel router
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$s = sed_import('s', 'G', 'ALP', 16);
if ($s == 'gd') {
	require(SED_ROOT . '/modules/gallery/admin/gallery.admin.gd.php');
} else {
	require(SED_ROOT . '/modules/gallery/admin/gallery.admin.main.php');
}
