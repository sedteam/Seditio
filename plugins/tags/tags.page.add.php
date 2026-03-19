<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.add.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.add
Hooks=page.add.add.done
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
$new_page_id = sed_sql_insertid();

if (!empty($rtags) && $new_page_id > 0) {
	$tags_arr = sed_tag_parse($rtags);
	foreach ($tags_arr as $tag) {
		sed_tag($tag, $new_page_id, 'pages');
	}
}
