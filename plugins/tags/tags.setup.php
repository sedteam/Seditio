<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.setup.php
Version=185
Type=Plugin
Description=Tags plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Name=Tags
Description=Tag system for pages and forum topics with cloud, search and autocomplete
Version=1.0
Date=2026-03-18
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
pages=01:radio:0,1:1:Enable tags for pages
forums=02:radio:0,1:1:Enable tags for forum topics
title=03:radio:0,1:1:Display tags in Title Case
order=04:select:Alphabetical,Frequency,Random:Alphabetical:Tag cloud sort order
limit=05:string::0:Max tags per item (0 = unlimited)
lim_pages=06:string::0:Cloud limit in page listings (0 = unlimited)
lim_forums=07:string::0:Cloud limit in forums (0 = unlimited)
lim_index=08:string::20:Cloud limit on index page (0 = unlimited)
more=09:radio:0,1:1:Show All tags link when cloud is limited
perpage=10:string::0:Tags per page in standalone cloud (0 = unlimited)
index=11:select:pages,forums,all:pages:Index cloud area
noindex=12:radio:0,1:1:Add meta noindex to standalone tag search
sort=13:select:ID,Title,Date,Category:Date:Search results sort
css=14:radio:0,1:1:Include plugin CSS
autocomplete_minlen=15:string::3:Minimum characters for autocomplete
maxrowsperpage=16:select:5,10,15,20,25,30,35,40,45,50,60,70,80,90:30:Maximum lines in tags
cloud_index_on=17:radio:0,1:1:Show tags cloud on index
cloud_list_on=18:radio:0,1:1:Show tags cloud in category list
cloud_page_on=19:radio:0,1:1:Show tags cloud on page view
tagstitle=20:string::{MAINTITLE} - {TITLE}:Page title mask
list_separator=21:string:: :Separator between tags in list
cloud_forums_on=22:radio:0,1:1:Show tags cloud on forum main page
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
