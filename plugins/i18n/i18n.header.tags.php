<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.header.tags.php
Version=186
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=header.tags
File=i18n.header.tags
Hooks=header.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $t;

if (function_exists('i18n_build_lang_switcher')) {
	$t->assign('HEADER_I18N_SELECTOR', i18n_build_lang_switcher());
}
