<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.similarpages.php
Version=186
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=similarpages
File=i18n.similarpages
Hooks=similarpages.pages.main
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($similarpages_rows) && is_array($similarpages_rows)) {
	i18n_translate_pages($similarpages_rows);
}
