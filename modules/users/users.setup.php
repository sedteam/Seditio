<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.setup.php
Version=185
Updated=2026-feb-21
Type=Module
Author=Seditio Team
Description=Users module
[END_SED]

[BEGIN_SED_MODULE]
Code=users
Name=Users
Description=User management module
Version=1.0.0
Date=2026-feb-21
Author=Seditio Team
Copyright=
Notes=
Requires=
Admin=1
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Lock_module=1
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
disablereg=01:radio::0:Disable registration
defaultcountry=02:string:::Default country code
disablewhosonline=03:radio::0:Disable Who's Online
maxusersperpage=05:select:5,10,15,20,25,30,35,40,45,50,75,100,150,200:50:Maximum users per page
regrequireadmin=07:radio::0:Registration requires admin approval
regnoactivation=10:radio::0:Registration without activation
useremailchange=10:radio::0:Allow users to change email
usertextimg=10:radio::0:Allow images in user text
color_group=10:radio::0:Color usernames by group
av_maxsize=12:string::64000:Avatar max file size (bytes)
av_maxx=12:string::128:Avatar max width (px)
av_maxy=12:string::128:Avatar max height (px)
usertextmax=12:string::300:User text max length
sig_maxsize=13:string::64000:Signature max file size (bytes)
sig_maxx=13:string::640:Signature max width (px)
sig_maxy=13:string::100:Signature max height (px)
ph_maxsize=14:string::64000:Photo max file size (bytes)
ph_maxx=14:string::256:Photo max width (px)
ph_maxy=14:string::256:Photo max height (px)
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
