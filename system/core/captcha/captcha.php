<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=captcha.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Amro
Description=Captcha generate
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

session_start();

require(SED_ROOT . '/system/functions.php');

$cfg['font_dir'] = "datas/fonts/";
$captcha_code = sed_generate_code();
sed_captcha_image($captcha_code);
