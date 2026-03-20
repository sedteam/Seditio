<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.tags
Hooks=page.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['pages']) && $cfg['plugin']['tags']['pages'] == 0) {
	$t->assign("PAGE_TAGS", '');
	return;
}

$page_id = (int)$pag['page_id'];
$page_tags = sed_tag_list($page_id, 'pages');
$t->assign("PAGE_TAGS", sed_tag_build_list($page_tags, 'pages', false));

$cloud_page_on = !isset($cfg['plugin']['tags']['cloud_page_on']) || $cfg['plugin']['tags']['cloud_page_on'] != 0;
if (!$cloud_page_on) {
	return;
}

$page_limit = isset($cfg['plugin']['tags']['lim_pages']) ? (int)$cfg['plugin']['tags']['lim_pages'] : 0;
$page_order = isset($cfg['plugin']['tags']['order']) ? $cfg['plugin']['tags']['order'] : 'Alphabetical';
$page_cloud = sed_tag_cloud('pages', $page_order, $page_limit);
$page_cloud_html = sed_tag_build_cloud($page_cloud, 'pages');
if (!empty($page_cloud_html) && !empty($cfg['plugin']['tags']['more']) && $page_limit > 0) {
	$page_cloud_html .= sed_tag_build_more(sed_url('plug', 'e=tags'), $L['tags_alltags']);
}

$t->assign("PAGE_TAGS_CLOUD", $page_cloud_html);
$t->parse("MAIN.PAGE_TAGS_CLOUD_BOX");
