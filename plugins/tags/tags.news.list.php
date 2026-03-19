<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.news.list.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.news.list
Hooks=news.list
Order=5
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['pages']) && $cfg['plugin']['tags']['pages'] == 0) {
	return;
}

if (!empty($news_items)) {
	$_news_page_ids = array();
	foreach ($news_items as $_ni) {
		$_news_page_ids[] = (int)$_ni['page_id'];
	}
	if (!empty($_news_page_ids)) {
		$sed_tags_batch_pages = sed_tag_list($_news_page_ids, 'pages');
	}
}
