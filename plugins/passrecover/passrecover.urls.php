<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/passrecover/passrecover.urls.php
Version=185
Updated=2026-feb-21
Type=Plugin
Author=Seditio Team
Description=Passrecover URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 510;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/passrecover(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=passrecover'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=passrecover',
		'rewrite' => 'passrecover'
	),
);
