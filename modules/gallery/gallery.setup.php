<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/gallery.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Gallery module
[END_SED]

[BEGIN_SED_MODULE]
Code=gallery
Name=Gallery
Description=Image galleries using PFS folders
Version=1.0.0
Date=2026-feb-10
Author=Seditio Team
Copyright=
Notes=
Requires=pfs
Admin=1
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Lock_module=0
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
gallerytitle=09:string::{MAINTITLE} - {TITLE}:Title for gallery
gallery_gcol=10:select:2,3,4,5,6,8:4:Number of columns for galleries
gallery_bcol=11:select:4,5,6,8:6:Number of columns for pictures
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
