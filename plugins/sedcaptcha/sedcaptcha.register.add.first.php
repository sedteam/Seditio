<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.validate.php
Version=185
Updated=2026-feb-14
Type=Plugin
Author=Amro
Description=Plugin to protect the registration process with a Cool PHP Captcha.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sedcaptcha
Part=validation
File=sedcaptcha.register.add.first
Hooks=users.register.add.first
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

require_once(SED_ROOT . '/plugins/sedcaptcha/inc/sedcaptcha.functions.php');

$captcha_register = $cfg['plugin']['sedcaptcha']['captcha_register'];

if ($captcha_register == "yes") {

	$verify = sed_verify_code();
	$error_string .= (!empty($verify)) ? $verify . "<br />" : "";
}
