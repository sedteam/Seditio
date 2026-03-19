<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.header.first.php
Version=185
Type=Plugin
Description=Tags CSS and JS
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.header.first
Hooks=header.first
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($cfg['plugin']['tags']['css'])) {
	sed_add_css('plugins/tags/css/tags.css', true);
}
