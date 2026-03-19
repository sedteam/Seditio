<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.common.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=common
File=tags.common
Hooks=common
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg;
$db_tags = $cfg['sqldbprefix'] . 'tags';
$db_tag_references = $cfg['sqldbprefix'] . 'tag_references';

if (!function_exists('sed_tag_parse')) {
	require_once(SED_ROOT . '/plugins/tags/inc/tags.functions.php');
	if ($f = sed_langfile('tags', 'plugin')) {
		require_once($f);
	}
}
