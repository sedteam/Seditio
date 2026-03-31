<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/rss/rss.urls.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=RSS URL rewrite and translation (plug.php?e=rss)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 210;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/rss/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=rss&m=$1'
	),
	array(
		'cond' => '#^/rss(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=rss'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=rss&m=*',
		'rewrite' => 'rss/{m}'
	),
	array(
		'params' => 'e=rss',
		'rewrite' => 'rss'
	),
);
