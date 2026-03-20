<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.topics.loop.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.topics.loop
Hooks=forums.topics.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['forums']) && $cfg['plugin']['tags']['forums'] == 0) {
	$t->assign("FORUMS_TOPICS_ROW_TAGS", '');
	return;
}

$ft_id = (int)$row['ft_id'];

if (isset($sed_tags_batch_forums) && isset($sed_tags_batch_forums[$ft_id])) {
	$topic_tags = $sed_tags_batch_forums[$ft_id];
} else {
	$topic_tags = sed_tag_list($ft_id, 'forums');
}

$t->assign("FORUMS_TOPICS_ROW_TAGS", sed_tag_build_list($topic_tags, 'forums', false));
