<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Private messages module
[END_SED]

[BEGIN_SED_MODULE]
Code=pm
Name=Private messages
Description=Private messages
Version=1.0.0
Date=2026-feb-10
Author=Seditio Team
Copyright=
Notes=
Requires=
Admin=1
Auth_guests=0
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
pmtitle=05:string::{MAINTITLE} - {TITLE}:Title for PM
pm_maxsize=10:select:200,500,1000,2000,5000,10000,15000,20000,30000,50000,65000:10000:Maximum length for messages
maxpmsperpage=05:select:5,10,15,20,25,30,35,40,50:15:Messages per page in inbox/sentbox
pm_allownotifications=10:radio::1:Allow PM notifications by email
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
