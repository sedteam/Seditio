<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.edit.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.edit.tags
Hooks=page.edit.tags
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

$existing_tags = sed_tag_list((int)$id, 'pages');
$rtags_val = !empty($existing_tags) ? implode(', ', $existing_tags) : '';

$t->assign("PAGEEDIT_FORM_TAGS", sed_textbox('rtags', $rtags_val, 64, 255, 'autotags'));
$t->assign("PAGEEDIT_FORM_TAGS_HINT", $L['tags_input_hint']);

sed_tags_add_autocomplete();
