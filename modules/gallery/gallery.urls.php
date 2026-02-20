<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/gallery.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Gallery URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 260;

/* Gallery rewriting */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/gallery/pic/([0-9]+)(/?)$#',
		'rule' => 'modules/gallery/gallery.php?id=$1'
	),
	array(
		'cond' => '#^/gallery/([0-9]+)(/?)$#',
		'rule' => 'modules/gallery/gallery.php?f=$1'
	),
	array(
		'cond' => '#^/gallery(/?)$#',
		'rule' => 'modules/gallery/gallery.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['gallery'] = array(
	array(
		'params' => 'f=*',
		'rewrite' => 'gallery/{f}'
	),
	array(
		'params' => 'id=*',
		'rewrite' => 'gallery/pic/{id}'
	),
	array(
		'params' => '',
		'rewrite' => 'gallery'
	)
);
