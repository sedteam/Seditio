<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.polls.view.php
Version=185
Type=Plugin
Description=Comments block for poll (variant B, polls.view)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.polls.view
Hooks=polls.view
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$comments = sed_import('comments', 'G', 'BOL');
list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_poll, $comments, true);

if (!empty($comments_link) && !$ajax) {
	$xpoll->assign(array(
		"POLL_COMMENTS" => $comments_link,
		"POLL_COMMENTS_URL" => sed_url('polls', "id=" . $id . $standalone_url . "&comments=1"),
		"POLL_COMMENTS_DISPLAY" => $comments_display,
		"POLL_COMMENTS_COUNT" => $comments_count,
		"POLL_COMMENTS_ISSHOW" => ($comments) ? " active" : "",
		"POLL_COMMENTS_JUMP" => ($comments) ? "<span class=\"spoiler-jump\"></span>" : ""
	));
	$xpoll->parse("POLL.POLL_COMMENTS");
}
