<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.register.tags.php
Version=185
Updated=2026-feb-14
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
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

require_once(SED_ROOT . '/plugins/sedcaptcha/inc/sedcaptcha.functions.php');

$captcha_register = $cfg['plugin']['sedcaptcha']['captcha_register'];

if ($captcha_register == "yes") {

	if ($f = sed_langfile('sedcaptcha', 'plugin')) {
		require_once($f);
	}

	$captcha_url = ($cfg['sefurls'] && !empty($sed_urltrans['captcha']))
		? sed_url("captcha")
		: rtrim($sys['abs_url'], '/') . '/' . 'plugins/sedcaptcha/inc/sedcaptcha.php';

	$verifyimg = "<img src=\"" . $captcha_url . "\" id=\"cha\" /><br />";
	$verifyimg .= "<a href=\"" . sed_url("users", "m=register", "#change") . "\" id=\"change-image\" style=\"font-size:10px;\" name=\"change-image\">" . $L['plu_scaptcha_noties'] . "</a>";
	$verifyimg .= "<script>
		document.getElementById('change-image').addEventListener('click', function(event) {
			event.preventDefault();
			var img = document.getElementById('cha');
			img.src = img.src.split('?')[0] + '?' + Math.random();
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
