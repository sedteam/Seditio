<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.page.tags.php
Version=185
Type=Plugin
Description=Comments block for page (variant B, page.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.page.tags
Hooks=page.tags
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$allowcommentscat = $sed_cat[$pag['page_cat']]['allowcomments'];
$allowcommentspage = $pag['page_allowcomments'];
$showcommentsonpage = !empty($cfg['plugin']['comments']['showcommentsonpage']);
$comments = sed_import('comments', 'G', 'BOL');
$comments = $showcommentsonpage ? $showcommentsonpage : $comments;

$item_code = 'p' . $pag['page_id'];
$url_param = (empty($pag['page_alias'])) ? "id=" . $pag['page_id'] : "al=" . $pag['page_alias'];
$url_page = array('part' => 'page', 'params' => $url_param);

if ($allowcommentscat) {
	list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_page, $comments, $allowcommentspage);
} else {
	$comments_link = '';
	$comments_display = '';
	$comments_count = 0;
}

$pcomments = $showcommentsonpage ? "" : "&comments=1";
$pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pcomments) : sed_url("page", "al=" . $pag['page_alias'] . $pcomments);

if (!empty($comments_link)) {
	$t->assign(array(
		"PAGE_COMMENTS" => $comments_link,
		"PAGE_COMMENTS_DISPLAY" => $comments_display,
		"PAGE_COMMENTS_ISSHOW" => ($showcommentsonpage || $comments) ? " active" : "",
		"PAGE_COMMENTS_JUMP" => ($showcommentsonpage || $comments) ? "<span class=\"spoiler-jump\"></span>" : "",
		"PAGE_COMMENTS_COUNT" => $pag['page_comcount'],
		"PAGE_COMMENTS_RSS" => sed_url("rss", "m=comments&id=" . $pag['page_id']),
		"PAGE_COMMENTS_URL" => $pag['page_pageurlcom']
	));
	$t->parse("MAIN.PAGE_COMMENTS");
}
