<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/robots/robots.setup.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=robots
Name=Robots generator 
Description=Generates the content of robots.txt
Version=180
Date=2025-mar-26
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
morerules=01:text:::Additional rules added to the base ones
noindex=02:select:yes,no:no:If yes, blocks indexing of the entire site
sitemap=03:select:yes,no:yes:Add sitemap link to robots.txt
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
