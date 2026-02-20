<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.users.details.tags.php
Version=185
Type=Plugin
Description=Comments block for user profile (variant B, users.details.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.users.details.tags
Hooks=users.details.tags
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$comments = sed_import('comments', 'G', 'BOL');
$item_code = 'u' . $urr['user_id'];
$url_users = array('part' => 'users', 'params' => 'm=details&id=' . $urr['user_id'] . '&comments=1');

list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_users, $comments, true);

if (!empty($comments_link)) {
	$t->assign(array(
		"USERS_DETAILS_COMMENTS" => $comments_link,
		"USERS_DETAILS_COMMENTS_DISPLAY" => $comments_display,
		"USERS_DETAILS_COMMENTS_COUNT" => $comments_count,
		"USERS_DETAILS_COMMENTS_URL" => sed_url("users", "m=details&id=" . $urr['user_id'] . "&comments=1"),
		"USERS_DETAILS_COMMENTS_ISSHOW" => ($comments) ? " active" : "",
		"USERS_DETAILS_COMMENTS_JUMP" => ($comments) ? "<span class=\"spoiler-jump\"></span>" : ""
	));
	$t->parse("MAIN.USERS_DETAILS_COMMENTS");
}
