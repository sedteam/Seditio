<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.setup.php
Version=185
Updated=2026-mar-26
Type=Plugin
Author=Seditio Team
Description=Trash can: soft-delete storage and restore (moved from core)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Name=Trash can
Description=Stores deleted items for restore; configuration for per-area trash and prune delay
Version=1.0
Date=2026-mar-26
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
trash_prunedelay=01:select:0,1,2,3,4,5,7,10,15,20,30,45,60,90,120:7:Remove items from trash after * days (0 = never)
trash_comment=02:radio:0,1:1:Use trash for comments
trash_page=03:radio:0,1:1:Use trash for pages
trash_pm=04:radio:0,1:1:Use trash for private messages
trash_user=05:radio:0,1:1:Use trash for users
trash_forum=06:radio:0,1:1:Use trash for forums
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
