<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.setup.php
Version=185
Updated=2026-feb-18
Type=Plugin
Author=Seditio Team
Description=Ratings plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Name=Ratings
Description=Ratings for pages, gallery, users and polls
Version=1.0
Date=2026-feb-18
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
maxratsperpage=01:select:5,10,15,20,25,30,40,50:30:Ratings per page in admin
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
