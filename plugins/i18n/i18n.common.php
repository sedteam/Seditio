<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.common.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=common
File=i18n.common
Hooks=common
Order=5
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

// Get configured translation languages
$i18n_langs = i18n_get_languages();

// -------------------------------------------------------------
// Language switching and session/cookie persistence
// -------------------------------------------------------------

// Allow language switching via ?lang=XX or ?setlang=XX
$req_lang = sed_import('lang', 'G', 'ALP');
if (empty($req_lang)) {
	$req_lang = sed_import('setlang', 'G', 'ALP');
}

if (!empty($req_lang)) {
	$req_lang = mb_strtolower($req_lang);
	// Validate requested language: it must be either default language or one of the configured translations
	if ($req_lang == mb_strtolower($cfg['defaultlang']) || in_array($req_lang, $i18n_langs)) {
		// Set cookie for 1 year
		sed_setcookie('i18n_lang', $req_lang, time() + 31536000, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
		$usr['lang'] = $req_lang;
	}
} elseif (isset($_COOKIE['i18n_lang'])) {
	$saved_lang = mb_strtolower($_COOKIE['i18n_lang']);
	if ($saved_lang == mb_strtolower($cfg['defaultlang']) || in_array($saved_lang, $i18n_langs)) {
		$usr['lang'] = $saved_lang;
	}
}

// Make sure global system lang is in sync
$lang = $usr['lang'];

// Load translation strings
if ($f = sed_langfile('i18n', 'plugin', $lang)) {
	require_once($f);
}
