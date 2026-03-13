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
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!function_exists('sed_build_comments')) {
	require_once(SED_ROOT . '/plugins/comments/inc/comments.functions.php');
	if ($f = sed_langfile('comments', 'plugin')) {
		require_once($f);
	}
}
