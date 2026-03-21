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
$cfg['ratings_count_mask'] = '<span class="ratings-count-value" data-ratings-code="%1$s">(%2$s)</span>';

sed_add_javascript('plugins/ratings/js/ratings.js', true);
sed_add_javascript('plugins/ratings/js/ratings-init.js', true);
if (!empty($cfg['plugin']['ratings']['css'])) {
	sed_add_css('plugins/ratings/css/ratings.css', true);
}

if (!function_exists('sed_build_ratings')) {
	require_once(SED_ROOT . '/plugins/ratings/inc/ratings.functions.php');
	if ($f = sed_langfile('ratings', 'plugin')) {
		require_once($f);
	}
}
