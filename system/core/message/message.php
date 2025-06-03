<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=message.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Messages loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_MESSAGE', TRUE);
$location = 'Messages';
$z = 'message';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

switch ($m) {
	default:
		require(SED_ROOT . '/system/core/message/message.inc.php');
		break;
}
