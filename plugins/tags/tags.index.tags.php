<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.index.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.index.tags
Hooks=index.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$cloud_index_on = !isset($cfg['plugin']['tags']['cloud_index_on']) || $cfg['plugin']['tags']['cloud_index_on'] != 0;
if (!$cloud_index_on) {
	return;
}

$index_area = isset($cfg['plugin']['tags']['index']) ? $cfg['plugin']['tags']['index'] : 'pages';
$page_ok = sed_module_active('page');
$forums_ok = sed_module_active('forums');
if ($index_area === 'pages' && !$page_ok) $index_area = $forums_ok ? 'forums' : 'all';
if ($index_area === 'forums' && !$forums_ok) $index_area = $page_ok ? 'pages' : 'all';
$index_limit = isset($cfg['plugin']['tags']['lim_index']) ? (int)$cfg['plugin']['tags']['lim_index'] : 20;
$index_order = isset($cfg['plugin']['tags']['order']) ? $cfg['plugin']['tags']['order'] : 'Alphabetical';

$index_cloud = sed_tag_cloud($index_area, $index_order, $index_limit);
$index_cloud_html = sed_tag_build_cloud($index_cloud, $index_area);

if (!empty($index_cloud_html) && !empty($cfg['plugin']['tags']['more']) && $index_limit > 0) {
	$index_cloud_html .= sed_tag_build_more(sed_url('plug', 'e=tags'), $L['tags_alltags']);
}

$t->assign("INDEX_TAGS_CLOUD", $index_cloud_html);
$t->parse("MAIN.INDEX_TAGS_CLOUD_BOX");
