<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.list.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.list.tags
Hooks=list.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['pages']) && $cfg['plugin']['tags']['pages'] == 0) {
	return;
}

$cloud_list_on = !isset($cfg['plugin']['tags']['cloud_list_on']) || $cfg['plugin']['tags']['cloud_list_on'] != 0;
if (!$cloud_list_on) {
	return;
}

$list_limit = isset($cfg['plugin']['tags']['lim_pages']) ? (int)$cfg['plugin']['tags']['lim_pages'] : 0;
$list_order = isset($cfg['plugin']['tags']['order']) ? $cfg['plugin']['tags']['order'] : 'Alphabetical';
$list_cloud = sed_tag_cloud('pages', $list_order, $list_limit);
$list_cloud_html = sed_tag_build_cloud($list_cloud, 'pages');

if (!empty($list_cloud_html) && !empty($cfg['plugin']['tags']['more']) && $list_limit > 0) {
	$list_cloud_html .= sed_tag_build_more(sed_url('plug', 'e=tags'), $L['tags_alltags']);
}

$t->assign("LIST_TAGS_CLOUD", $list_cloud_html);
$t->parse("MAIN.LIST_TAGS_CLOUD_BOX");

