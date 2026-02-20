<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=Administration panel loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_ADMIN', TRUE);
$location = 'Administration';
$z = 'admin';
$adminskin = true;

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/functions.admin.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');
require(SED_ROOT . "/system/lang/" . $usr['lang'] . "/admin.lang.php");
require(SED_ROOT . "/system/core/admin/admin.inc.php");
