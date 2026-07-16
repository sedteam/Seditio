<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.setup.php
Version=186
Updated=2026-jul-09
Type=Plugin
Author=Seditio Team
Description=Internationalization plugin for pages and categories
Lock=0
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Name=i18n
Description=Internationalization plugin for page content and structure
Version=1.0
Date=2026-jul-09
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
languages=01:string::en:Comma-separated translation language codes (e.g. en,de)
[END_SED_EXTPLUGIN_CONFIG]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
