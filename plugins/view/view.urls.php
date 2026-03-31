<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/view/view.urls.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=View URL rewrite (plug.php?e=view)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 200;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/view/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=view&v=$1'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=view&v=*',
		'rewrite' => 'view/{v}'
	),
);
