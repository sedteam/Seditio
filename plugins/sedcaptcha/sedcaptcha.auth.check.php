<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.auth.check.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Amro
Description=Plugin to protect the registration process with a Cool PHP Captcha.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sedcaptcha
Part=validation
File=sedcaptcha.auth.check
Hooks=users.auth.check
Tags=
Order=10
[END_SED_EXTPLUGIN]

==================== */

$captcha_auth = $cfg['plugin']['sedcaptcha']['captcha_auth'];

if ($captcha_auth == "yes") {

	$verify = sed_verify_code();
	$error_string .= (!empty($verify)) ? $verify . "<br />" : "";
}
