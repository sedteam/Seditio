<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/rss.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=RSS feeds
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_RSS', TRUE);
$location = 'Rss';
$z = 'rss';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
@include(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

if (!sed_module_active('rss')) {
	exit();
}

sed_dieifdisabled_part('rss', 'rss.main');
require(SED_ROOT . '/modules/rss/rss.main.php');
