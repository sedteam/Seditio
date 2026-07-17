<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.recentitems.comments.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=recentitems.comments
File=i18n.recentitems.comments
Hooks=recentitems.comments.pages
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($pages_data) && is_array($pages_data)) {
	i18n_translate_pages($pages_data);
}
