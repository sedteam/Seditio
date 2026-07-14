<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.global.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=global
File=i18n.global
Hooks=global
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $sed_cat, $db_i18n_structure, $i18n_langs;

if (empty($i18n_langs) || empty($usr['lang']) || $usr['lang'] == $cfg['defaultlang']) {
	return;
}

// Check if current language is one of the translation languages
if (in_array($usr['lang'], $i18n_langs)) {
	$sql_i18n = sed_sql_query("SELECT * FROM $db_i18n_structure WHERE ist_lang = '" . sed_sql_prep($usr['lang']) . "'");
	while ($row_i18n = sed_sql_fetchassoc($sql_i18n)) {
		$code = $row_i18n['ist_structure_code'];
		if (isset($sed_cat[$code])) {
			if (!empty($row_i18n['ist_title'])) {
				$sed_cat[$code]['title'] = $row_i18n['ist_title'];
			}
			if (!empty($row_i18n['ist_desc'])) {
				$sed_cat[$code]['desc'] = $row_i18n['ist_desc'];
			}
			if (!empty($row_i18n['ist_text'])) {
				$sed_cat[$code]['text'] = $row_i18n['ist_text'];
			}
			if (!empty($row_i18n['ist_seo_title'])) {
				$sed_cat[$code]['seo_title'] = $row_i18n['ist_seo_title'];
			}
			if (!empty($row_i18n['ist_seo_desc'])) {
				$sed_cat[$code]['seo_desc'] = $row_i18n['ist_seo_desc'];
			}
			if (!empty($row_i18n['ist_seo_keywords'])) {
				$sed_cat[$code]['seo_keywords'] = $row_i18n['ist_seo_keywords'];
			}
			if (!empty($row_i18n['ist_seo_h1'])) {
				$sed_cat[$code]['seo_h1'] = $row_i18n['ist_seo_h1'];
			}
		}
	}
}
