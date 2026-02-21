<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.page.tags.php
Version=185
Type=Plugin
Description=Ratings block for page (page.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=main
File=ratings.page.tags
Hooks=page.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$allowratingscat = $sed_cat[$pag['page_cat']]['allowratings'];
$allowratingspage = $pag['page_allowratings'];
$ratings = sed_import('ratings', 'G', 'BOL');

$item_code = 'p' . $pag['page_id'];
$url_param = (empty($pag['page_alias'])) ? "id=" . $pag['page_id'] : "al=" . $pag['page_alias'];
$url_page = array('part' => 'page', 'params' => $url_param);

$ratings_link = '';
$ratings_display = '';
if ($allowratingscat && function_exists('sed_build_ratings')) {
	list($ratings_link, $ratings_display) = sed_build_ratings($item_code, $url_page, $ratings, $allowratingspage);
}

$pratings = ($ratings) ? "" : "&ratings=1";
$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pratings) : sed_url("page", "al=" . $pag['page_alias'] . $pratings);

if (!empty($ratings_link)) {
	$t->assign(array(
		"PAGE_RATINGS_COUNT" => $pag['page_rating'],
		"PAGE_RATINGS_URL" => $pag['page_pageurlrat'],
		"PAGE_RATINGS" => $ratings_link,
		"PAGE_RATINGS_DISPLAY" => $ratings_display
	));
	$t->parse("MAIN.PAGE_RATINGS");
}
