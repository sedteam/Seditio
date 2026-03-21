<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.list.loop.php
Version=185
Type=Plugin
Description=Ratings row for list (list.loop)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=main
File=ratings.list.loop
Hooks=list.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg;
$pratings = "&ratings=1";
$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pratings) : sed_url("page", "al=" . $pag['page_alias'] . $pratings);

$rating_value = (float)(isset($pag['page_rating']) ? $pag['page_rating'] : 0);
$rating_widget = function_exists('sed_ratings_render_readonly') ? sed_ratings_render_readonly($rating_value, 'p' . $pag['page_id']) : (string)$rating_value;
$list_row_ratings = sed_link($pag['page_pageurlrat'], $rating_widget);
$item_code = 'p' . $pag['page_id'];

$t->assign(array(
	"LIST_ROW_RATINGS" => $list_row_ratings,
	"LIST_ROW_RATINGS_COUNT" => sprintf($cfg['ratings_count_mask'], sed_cc($item_code), number_format($rating_value, 2, '.', ''))
));
