<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.setup.php
Version=185
Updated=2026-feb-16
Type=Plugin
Author=Seditio Team
Description=Comments plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Name=Comments
Description=Comments for pages, polls, gallery and other content with tree structure and nesting levels
Version=2.0
Date=2026-feb-16
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
showcommentsonpage=01:radio:0,1:0:Show comments on page by default
maxcommentsperpage=02:select:5,10,15,20,25,30,35,40,45,50,60,70,80,90:30:Comments per page
maxtimeallowcomedit=03:select:0,5,10,15,20,25,30,35,40,45,50,60,70,80,90:15:Max time to edit comment (minutes)
maxcommentlenght=04:string::2000:Max comment length
countcomments=05:radio:0,1:1:Count comments in link
commentsorder=06:select:ASC,DESC:ASC:Comments order
commaxlevel=07:select:1,2,3,4,5,6,7,8,9,10:5:Max nesting level
commaxtree=08:select:5,10,15,20,25,30,35,45,50:30:Max comments per tree
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
