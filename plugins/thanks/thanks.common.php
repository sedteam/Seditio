<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.common.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=common
File=thanks.common
Hooks=common
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg;
$db_thanks = $cfg['sqldbprefix'] . 'thanks';

if (!function_exists('thanks_check')) {
	require_once(SED_ROOT . '/plugins/thanks/inc/thanks.functions.php');
	if ($f = sed_langfile('thanks', 'plugin')) {
		require_once($f);
	}
}
