<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.common.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=common
Hooks=common
File=trashcan.common
Order=1
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once SED_ROOT . '/plugins/trashcan/inc/trashcan.functions.php';

if ($path_lang = sed_langfile('trashcan', 'plugin')) {
	require($path_lang);
}
