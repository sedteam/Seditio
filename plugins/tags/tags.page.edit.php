<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.edit.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.edit
Hooks=page.edit.update.done
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

$rtags = sed_import('rtags', 'P', 'TXT');

if ((int)$id > 0) {
	sed_tag_remove_all((int)$id, 'pages');

	if (!empty($rtags)) {
		$tags_arr = sed_tag_parse($rtags);
		foreach ($tags_arr as $tag) {
			sed_tag($tag, (int)$id, 'pages');
		}
	}
}
