<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.newtopic.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.newtopic
Hooks=forums.newtopic.newtopic.done
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

$rtags = sed_import('rtags', 'P', 'TXT');

if (!empty($rtags) && (int)$q > 0) {
	$tags_arr = sed_tag_parse($rtags);
	foreach ($tags_arr as $tag) {
		sed_tag($tag, (int)$q, 'forums');
	}
}
