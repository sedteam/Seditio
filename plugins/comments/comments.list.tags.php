<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.list.tags.php
Version=185
Type=Plugin
Description=Comments block for list/category (variant B, list.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.list.tags
Hooks=list.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$allowcommentscat = isset($sed_cat[$c]['allowcomments']) ? $sed_cat[$c]['allowcomments'] : false;
list($list_comments, $list_comments_display, $tmp) = sed_build_comments($item_code, $url_list, $allowcommentscat, true);

$t->assign(array(
	"LIST_COMMENTS" => $list_comments,
	"LIST_COMMENTS_DISPLAY" => $list_comments_display
));
