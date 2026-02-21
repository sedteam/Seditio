<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/whosonline/whosonline.urls.php
Version=185
Updated=2026-feb-21
Type=Plugin
Author=Seditio Team
Description=Whosonline URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 505;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/whosonline(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=whosonline'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=whosonline',
		'rewrite' => 'whosonline'
	),
);
