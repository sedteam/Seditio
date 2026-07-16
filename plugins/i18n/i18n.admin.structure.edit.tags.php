<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.admin.structure.edit.tags.php
Version=186
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=admin.structure.edit.tags
File=i18n.admin.structure.edit.tags
Hooks=admin.page.structure.edit.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $t, $structure_code, $i18n_langs;

if (!empty($structure_code) && !empty($i18n_langs) && is_array($i18n_langs)) {
	$translations = i18n_get_structure_translations($structure_code);
	
	$tab_headers = i18n_build_tab_headers($i18n_langs);
	$tab_body = i18n_build_structure_tabs_body($i18n_langs, $translations);
	
	$t->assign(array(
		'STRUCTURE_UPDATE_I18N_TABS_HEADERS' => $tab_headers,
		'STRUCTURE_UPDATE_I18N_TABS_BODY' => $tab_body
	));
}
