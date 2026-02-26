<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/inc/sedcaptcha.php
Version=185
Updated=2026-feb-26
Type=Plugin
Author=Seditio Team
Description=Lightweight captcha image launcher (no DB, no common)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	exit();
}

session_start();

if (!isset($cfg)) {
	$cfg = array();
}

if (empty($cfg['font_dir'])) {
	$cfg['font_dir'] = SED_ROOT . '/datas/fonts/';
}

require(SED_ROOT . '/plugins/sedcaptcha/inc/sedcaptcha.functions.php');

$captcha_code = sed_generate_code();
sed_captcha_image($captcha_code);
exit;
