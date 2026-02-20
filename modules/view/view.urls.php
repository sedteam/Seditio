<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/view/view.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=View URL rewrite rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 200;

/* View: /view/{v} -> view.php?v=$1 */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/view/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/view/view.php?v=$1'
	),
);

$mod_urltrans = array();
