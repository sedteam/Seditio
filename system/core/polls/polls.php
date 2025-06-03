<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=polls.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Polls
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_POLLS', TRUE);
$location = 'Polls';
$z = 'polls';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_polls']);

switch ($m) {
	default:
		require(SED_ROOT . '/system/core/polls/polls.inc.php');
		break;
}
