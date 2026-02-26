<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.urls.php
Version=185
Updated=2026-feb-26
Type=Plugin
Author=Seditio Team
Description=Captcha URL rewrite rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 135;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/captcha(/?)$#',
		'rule' => 'plugins/sedcaptcha/inc/sedcaptcha.php'
	),
	array(
		'cond' => '#^/captcha\.png$#',
		'rule' => 'plugins/sedcaptcha/inc/sedcaptcha.php'
	),
);
