<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/otherpages/otherpages.setup.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=otherpages
Name=Other pages
Description=Display other pages in category
Version=180
Date=2021-jul-08
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
maxpages=01:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:3:Other pages displayed
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
