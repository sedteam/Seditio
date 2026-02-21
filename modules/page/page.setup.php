<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Pages and categories module
[END_SED]

[BEGIN_SED_MODULE]
Code=page
Name=Pages
Description=Pages and categories (list/view/add/edit)
Version=1.0.0
Date=2026-feb-15
Author=Seditio Team
Copyright=
Notes=
Requires=
Admin=1
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Lock_module=0
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
listtitle=03:string::{MAINTITLE} - {TITLE}:cfg_listtitle
pagetitle=04:string::{MAINTITLE} - {TITLE}:cfg_pagetitle
showpagesubcatgroup=03:radio::0:Show in groups pages from the subsections
maxrowsperpage=05:select:5,10,15,20,25,30,35,40,45,50,60,70,80,90:15:Maximum lines in lists
genseourls=06:radio::1:Generate SEO url (auto gen* page alias)?
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
