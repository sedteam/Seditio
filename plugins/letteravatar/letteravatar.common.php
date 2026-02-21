<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/letteravatar/letteravatar.common.php
Version=185
Updated=2026-feb-16
Type=Plugin
Author=Seditio Team
Description=Letter avatar on common hook
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=letteravatar
Part=common
File=letteravatar.common
Hooks=common
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once(SED_ROOT . '/plugins/letteravatar/inc/letteravatar.functions.php');

if ($usr['id'] > 0 && empty($usr['profile']['user_avatar'])) {
	$gen_avatar = letteravatar_autogen($usr['id']);
	if ($gen_avatar && $gen_avatar['status']) {
		$usr['profile']['user_avatar'] = $gen_avatar['imagepath'];
	}
}
