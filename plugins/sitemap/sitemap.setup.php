<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sitemap/sitemap.setup.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=Sitemap plugin setup
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sitemap
Name=Sitemap
Description=XML sitemap for search engines
Version=1.0.0
Date=2026-mar-31
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=
Requires_plugins=
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
disable_sitemap_pages=01:radio::0:Disable sitemap for pages and lists
disable_sitemap_forums=02:radio::0:Disable sitemap for forums
sm_pages_changefreq=03:select:always,hourly,daily,weekly,monthly,yearly,never:daily:Pages changefreq
sm_pages_priority=04:string::0.8:Pages priority
sm_pages_limit=05:string::40000:Pages limit
sm_lists_changefreq=06:select:always,hourly,daily,weekly,monthly,yearly,never:weekly:Lists changefreq
sm_lists_priority=07:string::0.5:Lists priority
sm_lists_limit=08:string::1000:Lists limit
sm_index_changefreq=09:select:always,hourly,daily,weekly,monthly,yearly,never:always:Index changefreq
sm_index_priority=10:string::1.0:Index priority
sm_forums_changefreq=11:select:always,hourly,daily,weekly,monthly,yearly,never:daily:Forums changefreq
sm_forums_priority=12:string::0.2:Forums priority
sm_forums_limit=13:string::3000:Forums limit
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
