<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.sections.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.sections.tags
Hooks=forums.sections.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['forums']) && $cfg['plugin']['tags']['forums'] == 0) {
	return;
}

$cloud_forums_on = !isset($cfg['plugin']['tags']['cloud_forums_on']) || $cfg['plugin']['tags']['cloud_forums_on'] != 0;
if (!$cloud_forums_on) {
	return;
}

$forums_limit = isset($cfg['plugin']['tags']['lim_forums']) ? (int)$cfg['plugin']['tags']['lim_forums'] : 0;
$forums_order = isset($cfg['plugin']['tags']['order']) ? $cfg['plugin']['tags']['order'] : 'Alphabetical';
$forums_cloud = sed_tag_cloud('forums', $forums_order, $forums_limit);
$forums_cloud_html = sed_tag_build_cloud($forums_cloud, 'forums');

if (!empty($forums_cloud_html) && !empty($cfg['plugin']['tags']['more']) && $forums_limit > 0) {
	$forums_cloud_html .= sed_tag_build_more(sed_url('plug', 'e=tags'), $L['tags_alltags']);
}

$t->assign("FORUMS_SECTIONS_TAGS_CLOUD", $forums_cloud_html);
$t->parse("MAIN.FORUMS_SECTIONS_TAGS_CLOUD_BOX");
