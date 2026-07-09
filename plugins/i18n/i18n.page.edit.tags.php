<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.page.edit.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=page.edit.tags
File=i18n.page.edit.tags
Hooks=page.edit.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $t, $id, $pag, $i18n_langs;

$page_id = isset($pag['page_id']) ? (int)$pag['page_id'] : (int)$id;

if ($page_id > 0 && !empty($i18n_langs) && is_array($i18n_langs)) {
	$translations = i18n_get_page_translations($page_id);
	
	$tab_headers = i18n_build_tab_headers($i18n_langs);
	$tab_body = i18n_build_page_tabs_body($i18n_langs, $translations, 'edit');
	
	$t->assign(array(
		'PAGEEDIT_I18N_TABS_HEADERS' => $tab_headers,
		'PAGEEDIT_I18N_TABS_BODY' => $tab_body
	));
}
