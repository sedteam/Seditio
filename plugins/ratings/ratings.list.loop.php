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
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$pratings = "&ratings=1";
$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pratings) : sed_url("page", "al=" . $pag['page_alias'] . $pratings);

$rating_img = round((float)isset($pag['page_rating']) ? $pag['page_rating'] : 0, 0);
$list_row_ratings = sed_link($pag['page_pageurlrat'], "<img src=\"skins/" . $usr['skin'] . "/img/system/vote" . $rating_img . ".gif\" alt=\"\" />");

$t->assign("LIST_ROW_RATINGS", $list_row_ratings);
