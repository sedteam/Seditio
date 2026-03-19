<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.forums.topics.list.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.forums.topics.list
Hooks=forums.topics.list
Order=5
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['tags']['forums']) && $cfg['plugin']['tags']['forums'] == 0) {
	return;
}

if (!empty($topics_items)) {
	$_topics_ft_ids = array();
	foreach ($topics_items as $_ti) {
		$_topics_ft_ids[] = (int)$_ti['ft_id'];
	}
	if (!empty($_topics_ft_ids)) {
		$sed_tags_batch_forums = sed_tag_list($_topics_ft_ids, 'forums');
	}
}
