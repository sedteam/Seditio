<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/cookienotice.setup.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Cookie consent and notice plugin.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=cookienotice
Name=Cookie Notice
Description=Displays a cookie usage notice and statistic services consent bar to the website visitors.
Version=1.0.0
Date=2026-jul-14
Author=Seditio Team
Copyright=Copyright (c) Seditio Team
Notes=Add {FOOTER_COOKIENOTICE} tag to the footer.tpl file of your skin.
SQL=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
cookie_text=01:text:::Cookie notice text (leave empty to use default language string)
cookie_url_stat=02:string::/sborstat:URL for Cookie and Statistics info page
cookie_url_policy=03:string::/policy:URL for Privacy Policy page
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}
