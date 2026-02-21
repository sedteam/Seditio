<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/polls/polls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Polls
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

define('SED_POLLS', TRUE);
$location = 'Polls';
$z = 'polls';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/config.extensions.php');
@include(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled(!sed_module_active('polls'));

sed_dieifdisabled_part('polls', 'polls.main');
require(SED_ROOT . '/modules/polls/polls.main.php');
