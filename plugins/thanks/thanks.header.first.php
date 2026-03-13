<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.header.first.php
Version=185
Type=Plugin
Description=Thanks CSS
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks.header.first
Hooks=header.first
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

sed_add_css('plugins/thanks/css/thanks.css', true);
