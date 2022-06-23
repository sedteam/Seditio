<?php
/* ====================
Seditio - Website engine
Copyright Neocrome & Russian Seditio Team 
http://www.neocrome.net http://ldu.ru

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.auth.check.php
Version=178
Updated=2022-jun-09
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
	$error_string .= (!empty($verify)) ? $verify."<br />" : "";
	
}

?>