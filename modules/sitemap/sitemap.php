<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/sitemap/sitemap.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=XML Sitemap
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_SITEMAP', TRUE);
$location = 'Sitemap';
$z = 'sitemap';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
@include(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('sitemap'));

sed_dieifdisabled_part('sitemap', 'sitemap.main');
require(SED_ROOT . '/modules/sitemap/sitemap.main.php');
