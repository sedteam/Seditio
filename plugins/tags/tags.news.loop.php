<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.news.loop.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.news.loop
Hooks=news.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['pages']) && $cfg['plugin']['tags']['pages'] == 0) {
	$news->assign("PAGE_ROW_TAGS", '');
	return;
}

$news_page_id = (int)$pag['page_id'];

if (isset($sed_tags_batch_pages) && isset($sed_tags_batch_pages[$news_page_id])) {
	$news_tags = $sed_tags_batch_pages[$news_page_id];
} else {
	$news_tags = sed_tag_list($news_page_id, 'pages');
}

$news->assign("PAGE_ROW_TAGS", sed_tag_build_list($news_tags, 'pages', false));
