<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/pfs.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PFS module setup
[END_SED]

[BEGIN_SED_MODULE]
Code=pfs
Name=Personal File Space
Description=User file storage and gallery base
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
[END_SED_MODULE]

[BEGIN_SED_MODULE_CONFIG]
pfstitle=09:string::{MAINTITLE} - {TITLE}:Title for PFS
pfs_filemask=02:radio::0:Unique filenames (random suffix)
[END_SED_MODULE_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
