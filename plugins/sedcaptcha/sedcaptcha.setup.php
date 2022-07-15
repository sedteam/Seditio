<?php
/* ====================
Seditio - Website engine
Copyright Neocrome & Russian Seditio Team 
http://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/sedcaptcha.setup.php
Version=179
Updated=2022-jun-09
Type=Plugin
Author=Amro
Description=Plugin to protect the registration process with a Cool PHP Captcha.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sedcaptcha
Name=Sedcaptcha
Description=Plugin to protect the registration process with a Cool PHP Captcha.
Version=179
Date=2022-jun-09
Author=Amro
Copyright=This plugin can be used for free.<br />Cool PHP Captcha is licensed under the GPLv3.
Notes=
SQL=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
captcha_auth=01:select:yes,no:yes:Enable captcha on login?
captcha_register=02:select:yes,no:yes:Enable captcha on registration?
[END_SED_EXTPLUGIN_CONFIG]

==================== */