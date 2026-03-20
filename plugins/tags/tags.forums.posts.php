<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.posts.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.posts
Hooks=forums.posts.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['forums']) && $cfg['plugin']['tags']['forums'] == 0) {
	$t->assign("FORUMS_POSTS_TAGS", '');
	return;
}

$topic_tags = sed_tag_list((int)$q, 'forums');

$t->assign("FORUMS_POSTS_TAGS", sed_tag_build_list($topic_tags, 'forums', false));
