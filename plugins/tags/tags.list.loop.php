<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.list.loop.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.list.loop
Hooks=list.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['pages']) && $cfg['plugin']['tags']['pages'] == 0) {
	$t->assign("LIST_ROW_TAGS", '');
	return;
}

$list_page_id = (int)$pag['page_id'];

if (isset($sed_tags_batch_pages) && isset($sed_tags_batch_pages[$list_page_id])) {
	$list_tags = $sed_tags_batch_pages[$list_page_id];
} else {
	$list_tags = sed_tag_list($list_page_id, 'pages');
}

$t->assign("LIST_ROW_TAGS", sed_tag_build_list($list_tags, 'pages', false));
