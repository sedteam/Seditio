<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/syntaxhighlight/syntaxhighlight.setup.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=syntaxhighlight
Name=Syntaxhighlight 3
Description=Syntaxhighlight for Seditio 
Version=180
Date=2025-mar-01
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
syntaxhighlight_theme=1:select:Default,Django,Eclipse,Emacs,FadeToGrey,Midnight,RDark:Default:Theme Syntaxhighlight
syntaxhighlight_version=2:select:2,3:3:Version Syntaxhighlight
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
