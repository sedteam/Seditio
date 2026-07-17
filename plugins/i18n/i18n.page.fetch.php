<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.page.fetch.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=page.fetch
File=i18n.page.fetch
Hooks=page.fetch
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $pag, $db_i18n_pages, $i18n_langs;

if (empty($i18n_langs) || empty($usr['lang']) || $usr['lang'] == $cfg['defaultlang']) {
	return;
}

if (is_array($pag) && isset($pag['page_id']) && in_array($usr['lang'], $i18n_langs)) {
	$page_id = (int)$pag['page_id'];
	$sql_i18n = sed_sql_query("SELECT * FROM $db_i18n_pages WHERE ipt_page_id = $page_id AND ipt_lang = '" . sed_sql_prep($usr['lang']) . "' LIMIT 1");
	if (sed_sql_numrows($sql_i18n) > 0) {
		$row_i18n = sed_sql_fetchassoc($sql_i18n);
		
		if (!empty($row_i18n['ipt_title'])) {
			$pag['page_title'] = $row_i18n['ipt_title'];
		}
		if (!empty($row_i18n['ipt_desc'])) {
			$pag['page_desc'] = $row_i18n['ipt_desc'];
		}
		if (!empty($row_i18n['ipt_text'])) {
			$pag['page_text'] = $row_i18n['ipt_text'];
		}
		if (!empty($row_i18n['ipt_text2'])) {
			$pag['page_text2'] = $row_i18n['ipt_text2'];
		}
		if (!empty($row_i18n['ipt_seo_title'])) {
			$pag['page_seo_title'] = $row_i18n['ipt_seo_title'];
		}
		if (!empty($row_i18n['ipt_seo_desc'])) {
			$pag['page_seo_desc'] = $row_i18n['ipt_seo_desc'];
		}
		if (!empty($row_i18n['ipt_seo_keywords'])) {
			$pag['page_seo_keywords'] = $row_i18n['ipt_seo_keywords'];
		}
		if (!empty($row_i18n['ipt_seo_h1'])) {
			$pag['page_seo_h1'] = $row_i18n['ipt_seo_h1'];
		}
	}
}
