<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/sitemap/sitemap.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Sitemap URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 220;

/* Sitemap: root-level only - /sitemap.xml and /sitemap_{m}.xml (no virtual /sitemap/ directory) */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/sitemap_([a-zA-Z0-9]+)\.xml$#',
		'rule' => 'modules/sitemap/sitemap.php?m=$1'
	),
	array(
		'cond' => '#^/sitemap\.xml$#',
		'rule' => 'modules/sitemap/sitemap.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['sitemap'] = array(
	array(
		'params' => 'm=*',
		'rewrite' => 'sitemap_{m}.xml'
	),
	array(
		'params' => '',
		'rewrite' => 'sitemap.xml'
	)
);
