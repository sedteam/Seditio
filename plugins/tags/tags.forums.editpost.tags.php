<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.editpost.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.editpost.tags
Hooks=forums.editpost.tags
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

if (!empty($firstpost) && (int)$q > 0) {
	$existing_tags = sed_tag_list((int)$q, 'forums');
	$rtags_val = !empty($existing_tags) ? implode(', ', $existing_tags) : '';

	$t->assign("FORUMS_EDITPOST_TAGS", sed_textbox('rtags', $rtags_val, 56, 255, 'autotags'));
	$t->assign("FORUMS_EDITPOST_TAGS_HINT", $L['tags_input_hint']);

	sed_tags_add_autocomplete();
}
