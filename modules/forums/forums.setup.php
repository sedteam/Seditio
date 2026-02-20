<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Forums module
[END_SED]

[BEGIN_SED_MODULE]
Code=forums
Name=Forums
Description=Discussion forums module
Version=1.0.0
Date=2026-feb-10
Author=Seditio Team
Copyright=
Notes=
Requires=
Admin=1
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
forumstitle=05:string::{MAINTITLE} - {TITLE}:Title for forums
hideprivateforums=10:radio::0:Hide private forums
hottopictrigger=10:select:5,10,15,20,25,30,35,40,50:20:Posts for a topic to be hot
maxtopicsperpage=10:select:5,10,15,20,25,30,35,40,45,50,60,70,80,90:30:Maximum topics or posts per page
antibumpforums=12:radio::0:Anti-bump protection
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
