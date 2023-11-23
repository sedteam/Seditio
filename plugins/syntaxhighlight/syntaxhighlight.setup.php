<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/syntaxhighlight/syntaxhighlight.setup.php
Version=179
Updated=2022-aug-31
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=syntaxhighlight
Name=Syntaxhighlight 1.1
Description=Syntaxhighlight for Seditio 
Version=160
Date=2022-aug-31
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Installer_skip=1
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
syntaxhighlight_theme=12:select:Default,Django,Eclipse,Emacs,FadeToGrey,Midnight,RDark:Default:Theme Syntaxhighlight
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
