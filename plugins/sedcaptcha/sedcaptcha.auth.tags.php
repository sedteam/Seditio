<?php
/* ====================
Seditio - Website engine
Copyright Neocrome & Russian Seditio Team 
http://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.auth.tags.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Amro
Description=Plugin to protect the registration process with a Cool PHP Captcha.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sedcaptcha
Part=auth
File=sedcaptcha.auth.tags
Hooks=users.auth.tags
Tags=users.auth.tpl:{USERS_REGISTER_VERIFYIMG},{USERS_REGISTER_VERIFYINPUT}
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

$captcha_auth = $cfg['plugin']['sedcaptcha']['captcha_auth'];

if ($captcha_auth == "yes") {

	require_once("plugins/sedcaptcha/lang/sedcaptcha." . $usr['lang'] . ".lang.php");

	$verifyimg = "<img src=\"captcha/\" id=\"cha\" /><br />";

	$verifyimg .= "<a href=\"" . sed_url("users", "m=auth", "#change") . "\" id=\"change-image\" style=\"font-size:10px;\" name=\"change-image\">" . $L['plu_scaptcha_noties'] . "</a>";
	$verifyimg .= "<script>
		document.getElementById('change-image').addEventListener('click', function(event) { 
			event.preventDefault(); 
			document.getElementById('cha').src='captcha/?'+Math.random();  
			document.getElementById('captcha-form').focus(); 
		});
	</script>";
	$verifyinput = "<input type=\"text\" name=\"" . sed_generate_field_code() . "\" id=\"captcha-form\" />";

	$t->assign(array(
		"USERS_AUTH_VERIFYIMG" => $verifyimg,
		"USERS_AUTH_VERIFYINPUT" => $verifyinput
	));

	$t->parse("MAIN.USERS_AUTH_VERIFY");
}
