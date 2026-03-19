<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.add.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.add.tags
Hooks=page.add.tags
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

$rtags_val = isset($rtags) ? sed_cc($rtags) : '';

$t->assign("PAGEADD_FORM_TAGS", sed_textbox('rtags', $rtags_val, 64, 255, 'autotags'));
$t->assign("PAGEADD_FORM_TAGS_HINT", $L['tags_input_hint']);

sed_tags_add_autocomplete();
