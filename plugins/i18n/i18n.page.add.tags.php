<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.page.add.tags.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=page.add.tags
File=i18n.page.add.tags
Hooks=page.add.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $t, $i18n_langs;

if (!empty($i18n_langs) && is_array($i18n_langs)) {
	$tab_headers = i18n_build_tab_headers($i18n_langs);
	$tab_body = i18n_build_page_tabs_body($i18n_langs, array(), 'add');
	
	$t->assign(array(
		'PAGEADD_I18N_TABS_HEADERS' => $tab_headers,
		'PAGEADD_I18N_TABS_BODY' => $tab_body
	));
}
