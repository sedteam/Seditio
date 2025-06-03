<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=rss.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Rss Creator
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_RSS', TRUE);
$location = 'Rss';
$z = 'rss';

require(SED_ROOT . '/system/functions.php');
@include('datas/config.php');
require(SED_ROOT . '/system/common.php');

require(SED_ROOT . '/system/core/rss/rss.inc.php');
