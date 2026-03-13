<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.forums.posts.loop.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks.forums.posts.loop
Hooks=forums.posts.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['thanks']['forums_on']) && $cfg['plugin']['thanks']['forums_on'] == 0) {
	$t->assign("FORUMS_POSTS_ROW_THANKS_DISPLAY", '');
	$t->assign("FORUMS_POSTS_ROW_THANKED", '');
	return;
}

$ext = 'forums';
$item = (int)$row['fp_id'];
$to_userid = (int)$row['fp_posterid'];

$t->assign("FORUMS_POSTS_ROW_THANKS_DISPLAY", sed_build_thanks($ext, $item, $to_userid, true));

$thanked_html = '';
if ($to_userid > 0) {
	$cnt = isset($row['user_thankscount']) ? (int)$row['user_thankscount'] : thanks_user_thanks_count($to_userid);
	$label = isset($L['thanks_thanked_times']) ? sprintf($L['thanks_thanked_times'], $cnt) : "Thanked: {$cnt} times";
	$thanked_html = sed_link(sed_url('plug', 'e=thanks&user=' . $to_userid), $label);
}
$t->assign("FORUMS_POSTS_ROW_THANKED", $thanked_html);
