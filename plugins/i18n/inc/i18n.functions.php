<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/inc/i18n.functions.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Get active translation languages from config
 * 
 * @return array Array of language codes
 */
function i18n_get_languages() {
	global $cfg;
	$langs_str = isset($cfg['plugin']['i18n']['languages']) ? $cfg['plugin']['i18n']['languages'] : '';
	if (empty($langs_str)) {
		return array();
	}
	$langs = explode(',', $langs_str);
	$langs = array_map('trim', $langs);
	$langs = array_filter($langs);
	return $langs;
}

/**
 * Fetch all translations for a specific page
 * 
 * @param int $page_id Page ID
 * @return array Array of translations grouped by language
 */
function i18n_get_page_translations($page_id) {
	global $cfg;
	$db_i18n_pages = $cfg['sqldbprefix'] . 'i18n_pages';
	$page_id = (int)$page_id;
	if ($page_id <= 0) {
		return array();
	}
	
	$translations = array();
	$sql = sed_sql_query("SELECT * FROM $db_i18n_pages WHERE ipt_page_id = $page_id");
	while ($row = sed_sql_fetchassoc($sql)) {
		$translations[$row['ipt_lang']] = $row;
	}
	return $translations;
}

/**
 * Fetch all translations for a specific structure/category code
 * 
 * @param string $structure_code Category code
 * @return array Array of translations grouped by language
 */
function i18n_get_structure_translations($structure_code) {
	global $cfg;
	$db_i18n_structure = $cfg['sqldbprefix'] . 'i18n_structure';
	$structure_code = sed_sql_prep($structure_code);
	if (empty($structure_code)) {
		return array();
	}
	
	$translations = array();
	$sql = sed_sql_query("SELECT * FROM $db_i18n_structure WHERE ist_structure_code = '$structure_code'");
	while ($row = sed_sql_fetchassoc($sql)) {
		$translations[$row['ist_lang']] = $row;
	}
	return $translations;
}

/**
 * Save page translations (UPSERT)
 * 
 * @param int $page_id Page ID
 * @param array $data Array of translation data: [lang] => [title => ..., desc => ..., ...]
 * @return bool
 */
function i18n_save_page_translations($page_id, $data) {
	global $cfg;
	$db_i18n_pages = $cfg['sqldbprefix'] . 'i18n_pages';
	$page_id = (int)$page_id;
	if ($page_id <= 0 || !is_array($data)) {
		return false;
	}
	
	foreach ($data as $lang => $fields) {
		$lang = sed_sql_prep($lang);
		if (empty($lang)) {
			continue;
		}
		
		// Check if translation already exists
		$check = sed_sql_query("SELECT ipt_id FROM $db_i18n_pages WHERE ipt_page_id = $page_id AND ipt_lang = '$lang' LIMIT 1");
		$exists = (sed_sql_numrows($check) > 0);
		
		$ipt_title = isset($fields['ipt_title']) ? sed_sql_prep($fields['ipt_title']) : '';
		$ipt_desc = isset($fields['ipt_desc']) ? sed_sql_prep($fields['ipt_desc']) : '';
		$ipt_text = isset($fields['ipt_text']) ? sed_sql_prep($fields['ipt_text']) : '';
		$ipt_text2 = isset($fields['ipt_text2']) ? sed_sql_prep($fields['ipt_text2']) : '';
		$ipt_seo_title = isset($fields['ipt_seo_title']) ? sed_sql_prep($fields['ipt_seo_title']) : '';
		$ipt_seo_desc = isset($fields['ipt_seo_desc']) ? sed_sql_prep($fields['ipt_seo_desc']) : '';
		$ipt_seo_keywords = isset($fields['ipt_seo_keywords']) ? sed_sql_prep($fields['ipt_seo_keywords']) : '';
		$ipt_seo_h1 = isset($fields['ipt_seo_h1']) ? sed_sql_prep($fields['ipt_seo_h1']) : '';
		
		// If all fields are empty, we don't need to insert it. If it exists, we might want to delete it or leave empty.
		$is_empty = (empty($ipt_title) && empty($ipt_desc) && empty($ipt_text) && empty($ipt_text2) && 
		             empty($ipt_seo_title) && empty($ipt_seo_desc) && empty($ipt_seo_keywords) && empty($ipt_seo_h1));
		
		if ($exists) {
			if ($is_empty) {
				sed_sql_query("DELETE FROM $db_i18n_pages WHERE ipt_page_id = $page_id AND ipt_lang = '$lang'");
			} else {
				sed_sql_query("UPDATE $db_i18n_pages SET
					ipt_title = '$ipt_title',
					ipt_desc = '$ipt_desc',
					ipt_text = '$ipt_text',
					ipt_text2 = '$ipt_text2',
					ipt_seo_title = '$ipt_seo_title',
					ipt_seo_desc = '$ipt_seo_desc',
					ipt_seo_keywords = '$ipt_seo_keywords',
					ipt_seo_h1 = '$ipt_seo_h1'
					WHERE ipt_page_id = $page_id AND ipt_lang = '$lang'");
			}
		} else {
			if (!$is_empty) {
				sed_sql_query("INSERT INTO $db_i18n_pages 
					(ipt_page_id, ipt_lang, ipt_title, ipt_desc, ipt_text, ipt_text2, ipt_seo_title, ipt_seo_desc, ipt_seo_keywords, ipt_seo_h1)
					VALUES
					($page_id, '$lang', '$ipt_title', '$ipt_desc', '$ipt_text', '$ipt_text2', '$ipt_seo_title', '$ipt_seo_desc', '$ipt_seo_keywords', '$ipt_seo_h1')");
			}
		}
	}
	return true;
}

/**
 * Save category translations (UPSERT)
 * 
 * @param string $structure_code Category code
 * @param array $data Array of translation data: [lang] => [title => ..., desc => ..., ...]
 * @return bool
 */
function i18n_save_structure_translations($structure_code, $data) {
	global $cfg;
	$db_i18n_structure = $cfg['sqldbprefix'] . 'i18n_structure';
	$structure_code = sed_sql_prep($structure_code);
	if (empty($structure_code) || !is_array($data)) {
		return false;
	}
	
	foreach ($data as $lang => $fields) {
		$lang = sed_sql_prep($lang);
		if (empty($lang)) {
			continue;
		}
		
		// Check if translation already exists
		$check = sed_sql_query("SELECT ist_id FROM $db_i18n_structure WHERE ist_structure_code = '$structure_code' AND ist_lang = '$lang' LIMIT 1");
		$exists = (sed_sql_numrows($check) > 0);
		
		$ist_title = isset($fields['ist_title']) ? sed_sql_prep($fields['ist_title']) : '';
		$ist_desc = isset($fields['ist_desc']) ? sed_sql_prep($fields['ist_desc']) : '';
		$ist_text = isset($fields['ist_text']) ? sed_sql_prep($fields['ist_text']) : '';
		$ist_seo_title = isset($fields['ist_seo_title']) ? sed_sql_prep($fields['ist_seo_title']) : '';
		$ist_seo_desc = isset($fields['ist_seo_desc']) ? sed_sql_prep($fields['ist_seo_desc']) : '';
		$ist_seo_keywords = isset($fields['ist_seo_keywords']) ? sed_sql_prep($fields['ist_seo_keywords']) : '';
		$ist_seo_h1 = isset($fields['ist_seo_h1']) ? sed_sql_prep($fields['ist_seo_h1']) : '';
		
		$is_empty = (empty($ist_title) && empty($ist_desc) && empty($ist_text) && empty($ist_seo_title) && empty($ist_seo_desc) && 
		             empty($ist_seo_keywords) && empty($ist_seo_h1));
		
		if ($exists) {
			if ($is_empty) {
				sed_sql_query("DELETE FROM $db_i18n_structure WHERE ist_structure_code = '$structure_code' AND ist_lang = '$lang'");
			} else {
				sed_sql_query("UPDATE $db_i18n_structure SET
					ist_title = '$ist_title',
					ist_desc = '$ist_desc',
					ist_text = '$ist_text',
					ist_seo_title = '$ist_seo_title',
					ist_seo_desc = '$ist_seo_desc',
					ist_seo_keywords = '$ist_seo_keywords',
					ist_seo_h1 = '$ist_seo_h1'
					WHERE ist_structure_code = '$structure_code' AND ist_lang = '$lang'");
			}
		} else {
			if (!$is_empty) {
				sed_sql_query("INSERT INTO $db_i18n_structure 
					(ist_structure_code, ist_lang, ist_title, ist_desc, ist_text, ist_seo_title, ist_seo_desc, ist_seo_keywords, ist_seo_h1)
					VALUES
					('$structure_code', '$lang', '$ist_title', '$ist_desc', '$ist_text', '$ist_seo_title', '$ist_seo_desc', '$ist_seo_keywords', '$ist_seo_h1')");
			}
		}
	}
	return true;
}

/**
 * Render tab headers for translations
 * 
 * @param array $langs Array of active language codes
 * @return string HTML of list items for the tabs menu
 */
function i18n_build_tab_headers($langs) {
	global $sys;
	$html = '';
	foreach ($langs as $lang) {
		$lang_upper = mb_strtoupper($lang);
		$html .= '<li><a href="' . $sys['request_uri'] . '#tab_i18n_' . mb_strtolower($lang) . '" data-tabtitle="' . $lang_upper . '">' . $lang_upper . '</a></li>' . "\n";
	}
	return $html;
}

/**
 * Build page translations tab bodies (Page Add or Edit)
 * 
 * @param array $langs Array of language codes
 * @param array $translations Current translation values: [lang] => [ipt_title => ..., ]
 * @param string $action 'add' or 'edit'
 * @return string HTML of all tab content containers
 */
function i18n_build_page_tabs_body($langs, $translations, $action = 'add') {
	global $cfg, $L;
	
	$tpl_file = SED_ROOT . '/plugins/i18n/tpl/i18n.page.' . ($action == 'add' ? 'add' : 'edit') . '.tpl';
	if (!file_exists($tpl_file)) {
		return 'Template file not found: ' . $tpl_file;
	}
	
	$html = '';
	$prefix = ($action == 'add') ? 'PAGEADD_I18N' : 'PAGEEDIT_I18N';
	$name_prefix = ($action == 'add') ? 'newpage' : 'rpage';
	
	foreach ($langs as $lang) {
		$lang_lower = mb_strtolower($lang);
		$lang_upper = mb_strtoupper($lang);
		
		$t_item = new XTemplate($tpl_file);
		
		$val = isset($translations[$lang]) ? $translations[$lang] : array();
		
		$title_val = isset($val['ipt_title']) ? $val['ipt_title'] : '';
		$desc_val = isset($val['ipt_desc']) ? $val['ipt_desc'] : '';
		$text_val = isset($val['ipt_text']) ? sed_checkmore($val['ipt_text'], false) : '';
		$text2_val = isset($val['ipt_text2']) ? sed_checkmore($val['ipt_text2'], false) : '';
		$seo_title_val = isset($val['ipt_seo_title']) ? $val['ipt_seo_title'] : '';
		$seo_desc_val = isset($val['ipt_seo_desc']) ? $val['ipt_seo_desc'] : '';
		$seo_key_val = isset($val['ipt_seo_keywords']) ? $val['ipt_seo_keywords'] : '';
		$seo_h1_val = isset($val['ipt_seo_h1']) ? $val['ipt_seo_h1'] : '';
		
		// Build form inputs using Seditio API
		$input_title = sed_textbox($name_prefix . '_i18n_' . $lang_lower . '[ipt_title]', $title_val);
		$input_desc = sed_textarea($name_prefix . '_i18n_' . $lang_lower . '[ipt_desc]', $desc_val, 3, 75);
		$input_text = sed_textarea($name_prefix . '_i18n_' . $lang_lower . '[ipt_text]', $text_val, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended');
		$input_text2 = sed_textarea($name_prefix . '_i18n_' . $lang_lower . '[ipt_text2]', $text2_val, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended');
		$input_seo_title = sed_textbox($name_prefix . '_i18n_' . $lang_lower . '[ipt_seo_title]', $seo_title_val);
		$input_seo_desc = sed_textbox($name_prefix . '_i18n_' . $lang_lower . '[ipt_seo_desc]', $seo_desc_val);
		$input_seo_key = sed_textbox($name_prefix . '_i18n_' . $lang_lower . '[ipt_seo_keywords]', $seo_key_val);
		$input_seo_h1 = sed_textbox($name_prefix . '_i18n_' . $lang_lower . '[ipt_seo_h1]', $seo_h1_val);
		
		$t_item->assign(array(
			$prefix . '_TITLE' => $input_title,
			$prefix . '_DESC' => $input_desc,
			$prefix . '_TEXT' => $input_text,
			$prefix . '_TEXT2' => $input_text2,
			$prefix . '_SEO_TITLE' => $input_seo_title,
			$prefix . '_SEO_DESC' => $input_seo_desc,
			$prefix . '_SEO_KEYWORDS' => $input_seo_key,
			$prefix . '_SEO_H1' => $input_seo_h1,
			'LANG_LOWER' => $lang_lower,
			'LANG_UPPER' => $lang_upper
		));
		
		$t_item->parse('MAIN');
		$html .= $t_item->text('MAIN') . "\n";
	}
	
	return $html;
}

/**
 * Build category translations tab bodies
 * 
 * @param array $langs Array of language codes
 * @param array $translations Current translation values: [lang] => [ist_title => ..., ]
 * @return string HTML of all tab content containers for Category Edit
 */
function i18n_build_structure_tabs_body($langs, $translations) {
	global $cfg;
	$tpl_file = SED_ROOT . '/plugins/i18n/tpl/i18n.structure.edit.tpl';
	if (!file_exists($tpl_file)) {
		return 'Template file not found: ' . $tpl_file;
	}
	
	$html = '';
	
	foreach ($langs as $lang) {
		$lang_lower = mb_strtolower($lang);
		$lang_upper = mb_strtoupper($lang);
		
		$t_item = new XTemplate($tpl_file);
		
		$val = isset($translations[$lang]) ? $translations[$lang] : array();
		
		$title_val = isset($val['ist_title']) ? $val['ist_title'] : '';
		$desc_val = isset($val['ist_desc']) ? $val['ist_desc'] : '';
		$text_val = isset($val['ist_text']) ? $val['ist_text'] : '';
		$seo_title_val = isset($val['ist_seo_title']) ? $val['ist_seo_title'] : '';
		$seo_desc_val = isset($val['ist_seo_desc']) ? $val['ist_seo_desc'] : '';
		$seo_key_val = isset($val['ist_seo_keywords']) ? $val['ist_seo_keywords'] : '';
		$seo_h1_val = isset($val['ist_seo_h1']) ? $val['ist_seo_h1'] : '';
		
		// Build form inputs using Seditio API
		$input_title = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_title]', $title_val, 48, 64);
		$input_desc = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_desc]', $desc_val, 64, 255);
		$input_text = sed_textarea('rstructure_i18n_' . $lang_lower . '[ist_text]', $text_val, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended');
		$input_seo_title = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_seo_title]', $seo_title_val, 64, 255);
		$input_seo_desc = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_seo_desc]', $seo_desc_val, 64, 255);
		$input_seo_key = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_seo_keywords]', $seo_key_val, 64, 255);
		$input_seo_h1 = sed_textbox('rstructure_i18n_' . $lang_lower . '[ist_seo_h1]', $seo_h1_val, 64, 255);
		
		$t_item->assign(array(
			'STRUCTURE_UPDATE_I18N_TITLE' => $input_title,
			'STRUCTURE_UPDATE_I18N_DESC' => $input_desc,
			'STRUCTURE_UPDATE_I18N_TEXT' => $input_text,
			'STRUCTURE_UPDATE_I18N_SEO_TITLE' => $input_seo_title,
			'STRUCTURE_UPDATE_I18N_SEO_DESC' => $input_seo_desc,
			'STRUCTURE_UPDATE_I18N_SEO_KEYWORDS' => $input_seo_key,
			'STRUCTURE_UPDATE_I18N_SEO_H1' => $input_seo_h1,
			'LANG_LOWER' => $lang_lower,
			'LANG_UPPER' => $lang_upper
		));
		
		$t_item->parse('MAIN');
		$html .= $t_item->text('MAIN') . "\n";
	}
	
	return $html;
}

/**
 * Translate an array of page rows in-place (batch mode)
 * 
 * @param array $pages Reference to the array of pages
 * @param string $id_key Key name for the page ID
 * @return bool
 */
function i18n_translate_pages(&$pages, $id_key = 'page_id') {
	global $cfg, $usr, $i18n_langs, $db_i18n_pages;
	
	if (empty($i18n_langs) || empty($usr['lang']) || $usr['lang'] == $cfg['defaultlang'] || !in_array($usr['lang'], $i18n_langs)) {
		return false;
	}
	
	if (empty($pages) || !is_array($pages)) {
		return false;
	}
	
	$page_ids = array();
	foreach ($pages as $p) {
		if (isset($p[$id_key])) {
			$page_ids[] = (int)$p[$id_key];
		}
	}
	
	if (empty($page_ids)) {
		return false;
	}
	
	$translations = array();
	$sql = sed_sql_query("SELECT * FROM $db_i18n_pages WHERE ipt_page_id IN (" . implode(',', $page_ids) . ") AND ipt_lang = '" . sed_sql_prep($usr['lang']) . "'");
	while ($row = sed_sql_fetchassoc($sql)) {
		$translations[$row['ipt_page_id']] = $row;
	}
	
	if (empty($translations)) {
		return false;
	}
	
	foreach ($pages as $key => $p) {
		$pid = isset($p[$id_key]) ? (int)$p[$id_key] : 0;
		if ($pid > 0 && isset($translations[$pid])) {
			$trans = $translations[$pid];
			
			if (!empty($trans['ipt_title'])) {
				$pages[$key]['page_title'] = $trans['ipt_title'];
			}
			if (!empty($trans['ipt_desc'])) {
				$pages[$key]['page_desc'] = $trans['ipt_desc'];
			}
			if (!empty($trans['ipt_text'])) {
				$pages[$key]['page_text'] = $trans['ipt_text'];
			}
			if (!empty($trans['ipt_text2'])) {
				$pages[$key]['page_text2'] = $trans['ipt_text2'];
			}
			if (!empty($trans['ipt_seo_title'])) {
				$pages[$key]['page_seo_title'] = $trans['ipt_seo_title'];
			}
			if (!empty($trans['ipt_seo_desc'])) {
				$pages[$key]['page_seo_desc'] = $trans['ipt_seo_desc'];
			}
			if (!empty($trans['ipt_seo_keywords'])) {
				$pages[$key]['page_seo_keywords'] = $trans['ipt_seo_keywords'];
			}
			if (!empty($trans['ipt_seo_h1'])) {
				$pages[$key]['page_seo_h1'] = $trans['ipt_seo_h1'];
			}
		}
	}
	
	return true;
}

/**
 * Build language switcher HTML widget for templates
 * 
 * @return string HTML code for switcher
 */
function i18n_build_lang_switcher() {
	global $cfg, $usr, $sys, $i18n_langs;
	
	if (empty($i18n_langs) || !is_array($i18n_langs)) {
		return '';
	}
	
	$all_langs = array_merge(array($cfg['defaultlang']), $i18n_langs);
	$current_lang = $usr['lang'];
	
	// Get query params from native Seditio request URI
	$request_uri = isset($sys['request_uri']) ? $sys['request_uri'] : '/';
	$parts = parse_url($request_uri);
	$path = isset($parts['path']) ? $parts['path'] : '';
	$query = isset($parts['query']) ? $parts['query'] : '';
	
	$params = array();
	if (!empty($query)) {
		parse_str($query, $params);
	}
	
	// Remove temporary switcher flags
	unset($params['setlang']);
	
	$html = '<div class="i18n-switcher">';
	foreach ($all_langs as $lang) {
		$lang_lower = mb_strtolower($lang);
		if ($lang_lower == mb_strtolower($current_lang)) {
			continue;
		}
		$lang_upper = mb_strtoupper($lang);
		
		$params['lang'] = $lang_lower;
		$new_query = http_build_query($params, '', '&');
		$url = $path . ($new_query !== '' ? '?' . $new_query : '');
		
		$html .= '<a href="' . htmlspecialchars($url) . '" class="i18n-lang-' . $lang_lower . '">' . $lang_upper . '</a> ';
	}
	$html .= '</div>';
	
	return $html;
}


