<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.common.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=common
Hooks=common
File=ratings.common
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $db_ratings, $db_rated;
$db_ratings = $cfg['sqldbprefix'] . 'ratings';
$db_rated = $cfg['sqldbprefix'] . 'rated';

if (!function_exists('sed_build_ratings')) {
	require_once(SED_ROOT . '/plugins/ratings/inc/ratings.functions.php');
	$ratings_lang = SED_ROOT . '/plugins/ratings/lang/ratings.' . $cfg['defaultlang'] . '.lang.php';
	if (file_exists($ratings_lang)) {
		require_once($ratings_lang);
	} elseif (file_exists(SED_ROOT . '/plugins/ratings/lang/ratings.en.lang.php')) {
		require_once(SED_ROOT . '/plugins/ratings/lang/ratings.en.lang.php');
	}
}
