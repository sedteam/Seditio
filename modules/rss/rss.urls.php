<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/rss.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=RSS URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 210;

/* RSS rewriting */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/rss/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/rss/rss.php?m=$1'
	),
	array(
		'cond' => '#^/rss(/?)$#',
		'rule' => 'modules/rss/rss.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['rss'] = array(
	array(
		'params' => 'm=*',
		'rewrite' => 'rss/{m}'
	),
	array(
		'params' => '',
		'rewrite' => 'rss'
	)
);
