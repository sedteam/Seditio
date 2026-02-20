<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/sitemap/sitemap.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Sitemap module
[END_SED]

[BEGIN_SED_MODULE]
Code=sitemap
Name=Sitemap
Description=XML sitemap for search engines
Version=1.0.0
Date=2026-feb-10
Author=Seditio Team
Copyright=
Notes=
Requires=
Admin=1
Auth_guests=R
Lock_guests=
Auth_members=R
Lock_members=
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
disable_sitemap_pages=01:radio::0:Disable sitemap for pages and lists
disable_sitemap_forums=02:radio::0:Disable sitemap for forums
sm_pages_changefreq=10:select:always,hourly,daily,weekly,monthly,yearly,never:daily:Pages changefreq
sm_pages_priority=11:string::0.8:Pages priority
sm_pages_limit=12:string::40000:Pages limit
sm_lists_changefreq=20:select:always,hourly,daily,weekly,monthly,yearly,never:weekly:Lists changefreq
sm_lists_priority=21:string::0.5:Lists priority
sm_lists_limit=22:string::1000:Lists limit
sm_index_changefreq=30:select:always,hourly,daily,weekly,monthly,yearly,never:always:Index changefreq
sm_index_priority=31:string::1.0:Index priority
sm_forums_changefreq=40:select:always,hourly,daily,weekly,monthly,yearly,never:daily:Forums changefreq
sm_forums_priority=41:string::0.2:Forums priority
sm_forums_limit=42:string::3000:Forums limit
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
