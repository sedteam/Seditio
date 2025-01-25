<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=sitemap.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=XML Sitemap Generator
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_RSS', TRUE);
$location = 'Sitemap';
$z = 'sitemap';

require(SED_ROOT . '/system/functions.php');
@include('datas/config.php');
require(SED_ROOT . '/system/common.php');

require(SED_ROOT . '/system/core/sitemap/sitemap.inc.php');
