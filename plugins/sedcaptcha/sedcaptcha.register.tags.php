<?php
/* ====================
Seditio - Website engine
Copyright Neocrome & Russian Seditio Team 
http://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.register.tags.php
Version=179
Updated=2022-jun-09
Type=Plugin
Author=Amro
Description=Plugin to protect the registration process with a Cool PHP Captcha.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sedcaptcha
Part=register
File=sedcaptcha.register.tags
Hooks=users.register.tags
Tags=users.register.tpl:{USERS_REGISTER_VERIFYIMG},{USERS_REGISTER_VERIFYINPUT}
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

$captcha_register = $cfg['plugin']['sedcaptcha']['captcha_register'];

if ($captcha_register == "yes") {

	require_once("plugins/sedcaptcha/lang/sedcaptcha." . $usr['lang'] . ".lang.php");

	$verifyimg = "<img src=\"captcha/\" id=\"cha\" /><br />";
	$verifyimg .= "<a href=\"" . sed_url("users", "m=register", "#change") . "\" id=\"change-image\" style=\"font-size:10px;\" name=\"change-image\">" . $L['plu_scaptcha_noties'] . "</a>";
	$verifyimg .= "<script>
		document.getElementById('change-image').addEventListener('click', function(event) { 
			event.preventDefault(); 
			document.getElementById('cha').src='captcha/?'+Math.random();  
			document.getElementById('captcha-form').focus(); 
		});
	</script>";
	$verifyinput = "<input type=\"text\" name=\"" . sed_generate_field_code() . "\" id=\"captcha-form\" />";

	$t->assign(array(
		"USERS_REGISTER_VERIFYIMG" => $verifyimg,
		"USERS_REGISTER_VERIFYINPUT" => $verifyinput
	));

	$t->parse("MAIN.USERS_REGISTER_VERIFY");
}
