<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/robots/robots.urls.php
Version=185
Updated=2026-feb-21
Type=Plugin
Author=Seditio Team
Description=Robots URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 160;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/robots.txt$#',
		'rule' => 'system/core/plug/plug.php?e=robots'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=robots',
		'rewrite' => 'robots.txt'
	),
);
