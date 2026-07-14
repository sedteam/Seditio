<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/cookienotice.header.first.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Loads CSS and JS files for the cookienotice plugin.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=cookienotice
Part=header
File=cookienotice.header.first
Hooks=header.first
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Do not load resource files in the administration panel
if (defined('SED_ADMIN')) {
	return;
}

// Register CSS and JS via core functions
sed_add_css('plugins/cookienotice/css/cookienotice.css', true);
sed_add_javascript('plugins/cookienotice/js/cookienotice.js', true);
