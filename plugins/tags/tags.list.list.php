<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.list.list.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.list.list
Hooks=list.list
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

if (!empty($list_items)) {
	$_list_page_ids = array();
	foreach ($list_items as $_li) {
		$_list_page_ids[] = (int)$_li['page_id'];
	}
	if (!empty($_list_page_ids)) {
		$sed_tags_batch_pages = sed_tag_list($_list_page_ids, 'pages');
	}
}
