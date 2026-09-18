<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/slider/slider.header.first.php
Version=186
Updated=2026-sep-18
Type=Plugin
Author=Seditio Team
Description=Loads CSS and JS assets for the native slider plugin.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=slider
Part=header
File=slider.header.first
Hooks=header.first
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

if (defined('SED_ADMIN')) {
	return;
}

sed_add_css('plugins/slider/css/slider.css', true);
sed_add_javascript('plugins/slider/js/slider.js', true);
