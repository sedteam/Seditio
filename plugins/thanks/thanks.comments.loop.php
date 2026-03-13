<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.comments.loop.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks.comments.loop
Hooks=comments.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['thanks']['comments_on']) && $cfg['plugin']['thanks']['comments_on'] == 0) {
	$t->assign("COMMENTS_ROW_THANKS_DISPLAY", '');
	return;
}

$ext = 'comments';
$item = (int)$row['com_id'];
$to_userid = (int)$row['com_authorid'];

$t->assign("COMMENTS_ROW_THANKS_DISPLAY", sed_build_thanks($ext, $item, $to_userid, true));
