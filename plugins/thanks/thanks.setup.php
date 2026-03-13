<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.setup.php
Version=185
Type=Plugin
Description=Thanks plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Name=Thanks
Description=Users can thank each other for pages, posts and comments
Version=1.0
Date=2026-03-12
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
maxday=01:string::20:Max thanks a user can give per day
maxuser=02:string::5:Max thanks per day to one user
maxthanked=03:string::10:How many thankers to show (0=all)
page_on=04:radio:0,1:1:Thanks for pages
forums_on=05:radio:0,1:1:Thanks for forum posts
comments_on=06:radio:0,1:1:Thanks for comments
short=07:radio:0,1:0:Short format (names only)
notify_by_pm=08:radio:0,1:0:Notify by PM on new thank
notify_by_email=09:radio:0,1:0:Notify by email on new thank
notify_from=10:string::noreply@example.com:Email from for notifications
thanksperpage=11:string::20:Thanks per page in lists
format=12:string::d.m.Y:Date format mask (empty = system default)
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
