<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.common.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=common
Hooks=common
File=comments.common
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!function_exists('sed_build_comments')) {
	require_once(SED_ROOT . '/plugins/comments/inc/comments.functions.php');
	$comments_lang = SED_ROOT . '/plugins/comments/lang/comments.' . $cfg['defaultlang'] . '.lang.php';
	if (file_exists($comments_lang)) {
		require_once($comments_lang);
	} elseif (file_exists(SED_ROOT . '/plugins/comments/lang/comments.en.lang.php')) {
		require_once(SED_ROOT . '/plugins/comments/lang/comments.en.lang.php');
	}
}
