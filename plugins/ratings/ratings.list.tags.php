<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.list.tags.php
Version=185
Type=Plugin
Description=Ratings block for list (list.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=main
File=ratings.list.tags
Hooks=list.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$allowratingscat = isset($sed_cat[$c]['allowratings']) ? $sed_cat[$c]['allowratings'] : false;
$list_ratings = '';
$list_ratings_display = '';
$ratings_item_code = 'l' . (isset($c) ? $c : '');
$ratings_url_list = array('part' => 'page', 'params' => 'c=' . (isset($c) ? $c : ''));
if (function_exists('sed_build_ratings')) {
	list($list_ratings, $list_ratings_display) = sed_build_ratings($ratings_item_code, $ratings_url_list, $allowratingscat, true);
}

$t->assign(array(
	"LIST_RATINGS" => $list_ratings,
	"LIST_RATINGS_DISPLAY" => $list_ratings_display
));
