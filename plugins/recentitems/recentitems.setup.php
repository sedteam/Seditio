<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/recentitems/recentitems.setup.php
Version=180
Updated=2022-jul-08
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=recentitems
Name=Recent items
Description=Recent pages, polls, commments and topics in forums, displayed on the home page
Version=180
Date=2022-jul-08
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
maxpages=01:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:5:Recent pages displayed
maxtopics=02:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:5:Recent topics in forums displayed
maxpolls=03:select:0,1,2,3,4,5:1:Recent polls displayed
maxcomments=04:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:10:Recent comments displayed
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
