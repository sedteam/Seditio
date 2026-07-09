<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.recentitems.pages.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=recentitems.pages
File=i18n.recentitems.pages
Hooks=recentitems.pages.main
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($latestpages_rows) && is_array($latestpages_rows)) {
	i18n_translate_pages($latestpages_rows);
}
