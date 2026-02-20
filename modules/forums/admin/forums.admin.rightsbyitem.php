<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/admin/forums.admin.rightsbyitem.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Forums rights by item title and back URL
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$rurl = sed_url('admin', 'm=forums');
if (function_exists('sed_forum_info')) {
	$forum = sed_forum_info($io);
	$title = (is_array($forum) && isset($forum['fs_title']))
		? " : " . sed_cc($forum['fs_title']) . " (#" . $io . ")"
		: (($io == 'a') ? '' : " : " . $io);
} else {
	$title = ($io == 'a') ? '' : " : " . $io;
}
