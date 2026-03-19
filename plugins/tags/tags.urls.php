<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.urls.php
Version=185
Updated=2026-mar-18
Type=Plugin
Author=Seditio Team
Description=Tags URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Plugin-specific URL rewriting rules (incoming HTTP requests)
$mod_urlrewrite_order = 500;
$mod_urlrewrite = array(
	array(
		'cond' => '#^/tags(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=tags'
	),
);

// URL translation rules (sed_url output when calling sed_url('plug', 'e=tags...'))
$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=tags',
		'rewrite' => 'tags'
	),
);

