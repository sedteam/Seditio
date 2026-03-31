<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/rss/rss.setup.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=RSS plugin setup
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=rss
Name=RSS
Description=RSS feeds for pages, comments and forums
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
disable_rsspages=01:radio::0:Disable RSS feed for pages
disable_rsscomments=02:radio::0:Disable RSS feed for comments
disable_rssforums=03:radio::0:Disable RSS feed for forums
rss_timetolive=04:string::300:Cache time for RSS feed (seconds)
rss_maxitems=05:select:0,5,10,20,30,40,50,60,70,75,80,85,90,95,100:30:Maximum items in RSS feed
rss_defaultcode=06:string::news:Default RSS feed category code
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
