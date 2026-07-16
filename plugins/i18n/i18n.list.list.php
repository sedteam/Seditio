<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.list.list.php
Version=186
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=list.list
File=i18n.list.list
Hooks=list.list
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $list_items, $db_i18n_pages, $i18n_langs;

if (empty($i18n_langs) || empty($usr['lang']) || $usr['lang'] == $cfg['defaultlang']) {
	return;
}

if (!empty($list_items) && is_array($list_items) && in_array($usr['lang'], $i18n_langs)) {
	$page_ids = array();
	foreach ($list_items as $pag) {
		if (isset($pag['page_id'])) {
			$page_ids[] = (int)$pag['page_id'];
		}
	}
	
	if (!empty($page_ids)) {
		$sql_i18n = sed_sql_query("SELECT * FROM $db_i18n_pages WHERE ipt_page_id IN (" . implode(',', $page_ids) . ") AND ipt_lang = '" . sed_sql_prep($usr['lang']) . "'");
		$translations = array();
		while ($row_i18n = sed_sql_fetchassoc($sql_i18n)) {
			$translations[$row_i18n['ipt_page_id']] = $row_i18n;
		}
		
		if (!empty($translations)) {
			foreach ($list_items as $key => $pag) {
				$pid = $pag['page_id'];
				if (isset($translations[$pid])) {
					$trans = $translations[$pid];
					if (!empty($trans['ipt_title'])) {
						$list_items[$key]['page_title'] = $trans['ipt_title'];
					}
					if (!empty($trans['ipt_desc'])) {
						$list_items[$key]['page_desc'] = $trans['ipt_desc'];
					}
					if (!empty($trans['ipt_text'])) {
						$list_items[$key]['page_text'] = $trans['ipt_text'];
					}
					if (!empty($trans['ipt_text2'])) {
						$list_items[$key]['page_text2'] = $trans['ipt_text2'];
					}
					if (!empty($trans['ipt_seo_title'])) {
						$list_items[$key]['page_seo_title'] = $trans['ipt_seo_title'];
					}
					if (!empty($trans['ipt_seo_desc'])) {
						$list_items[$key]['page_seo_desc'] = $trans['ipt_seo_desc'];
					}
					if (!empty($trans['ipt_seo_keywords'])) {
						$list_items[$key]['page_seo_keywords'] = $trans['ipt_seo_keywords'];
					}
					if (!empty($trans['ipt_seo_h1'])) {
						$list_items[$key]['page_seo_h1'] = $trans['ipt_seo_h1'];
					}
				}
			}
		}
	}
}
