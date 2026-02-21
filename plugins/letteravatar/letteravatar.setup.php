<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/letteravatar/letteravatar.setup.php
Version=185
Updated=2026-feb-16
Type=Plugin
Author=Seditio Team
Description=Letter avatar autogeneration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=letteravatar
Name=Letter avatar
Description=Autogenerate avatar image from first letter(s) of username when user has no avatar
Version=1.0
Date=2026-feb-16
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=pfs
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
