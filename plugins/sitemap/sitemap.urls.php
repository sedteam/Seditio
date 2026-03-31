<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sitemap/sitemap.urls.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=Sitemap URL rewrite (plug.php?e=sitemap)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 220;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/sitemap_([a-zA-Z0-9]+)\.xml$#',
		'rule' => 'system/core/plug/plug.php?e=sitemap&m=$1'
	),
	array(
		'cond' => '#^/sitemap\.xml$#',
		'rule' => 'system/core/plug/plug.php?e=sitemap'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=sitemap&m=*',
		'rewrite' => 'sitemap_{m}.xml'
	),
	array(
		'params' => 'e=sitemap',
		'rewrite' => 'sitemap.xml'
	),
);
