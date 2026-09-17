<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.common.php
Version=186
Updated=2026-sep-17
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=common
File=i18n.common
Hooks=common
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $sys, $db_i18n_pages, $db_i18n_structure, $i18n_langs;

$db_i18n_pages = $cfg['sqldbprefix'] . 'i18n_pages';
$db_i18n_structure = $cfg['sqldbprefix'] . 'i18n_structure';

// Load functions
if (!function_exists('i18n_get_languages')) {
	require_once(SED_ROOT . '/plugins/i18n/inc/i18n.functions.php');
}

if (!isset($i18n_langs)) {
	$i18n_langs = i18n_get_languages();
}

// Load translation strings if compiled cache is disabled/bypassed
if ($f = sed_langfile('i18n', 'plugin', $usr['lang'])) {
	require_once($f);
}
