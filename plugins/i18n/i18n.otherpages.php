<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.otherpages.php
Version=186
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=otherpages
File=i18n.otherpages
Hooks=otherpages.pages.main
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($otherpages_rows) && is_array($otherpages_rows)) {
	i18n_translate_pages($otherpages_rows);
}
