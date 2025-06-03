<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/similarpages/similarpages.setup.php
Version=170
Updated=2012-feb-26
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=similarpages
Name=Similar Pages
Description=The plugin displays a list of similar pages
Version=170
Date=2012-feb-26
Author=Amro
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
sim_maxcount=01:select:1,2,3,4,5,6,7,8,9,10,15,20,25,30:5:Maximum count of similar pages
sim_relevance=02:select:0,1,2,3,4,5,6,7:3:Hardness Relevance. Occurs more than number X
sim_category=03:text:::Categories. Separate by commas. If left this field empty, then will use of all categories.
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
