<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/rss.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=RSS module
[END_SED]

[BEGIN_SED_MODULE]
Code=rss
Name=RSS
Description=RSS feeds for pages, comments and forums
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
Lock_module=0
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
disable_rsspages=02:radio::0:Disable RSS feed for pages
disable_rsscomments=03:radio::0:Disable RSS feed for comments
disable_rssforums=04:radio::0:Disable RSS feed for forums
rss_timetolive=05:string::300:Cache time for RSS feed (seconds)
rss_maxitems=06:select:0,5,10,20,30,40,50,60,70,75,80,85,90,95,100:30:Maximum items in RSS feed
rss_defaultcode=07:string::news:Default RSS feed category code
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
