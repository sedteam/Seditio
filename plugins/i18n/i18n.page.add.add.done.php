<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.page.add.add.done.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=page.add.add.done
File=i18n.page.add.add.done
Hooks=page.add.add.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $i18n_langs;

$new_page_id = sed_sql_insertid();

if ($new_page_id > 0 && !empty($i18n_langs) && is_array($i18n_langs)) {
	$data = array();
	foreach ($i18n_langs as $lang) {
		$lang_lower = mb_strtolower($lang);
		$post_key = 'newpage_i18n_' . $lang_lower;
		
		if (isset($_POST[$post_key]) && is_array($_POST[$post_key])) {
			$raw = $_POST[$post_key];
			
			$data[$lang_lower] = array(
				'ipt_title' => isset($raw['ipt_title']) ? sed_import($raw['ipt_title'], 'D', 'TXT') : '',
				'ipt_desc' => isset($raw['ipt_desc']) ? sed_import($raw['ipt_desc'], 'D', 'TXT') : '',
				'ipt_text' => isset($raw['ipt_text']) ? sed_checkmore(sed_import($raw['ipt_text'], 'D', 'HTM'), true) : '',
				'ipt_text2' => isset($raw['ipt_text2']) ? sed_checkmore(sed_import($raw['ipt_text2'], 'D', 'HTM'), true) : '',
				'ipt_seo_title' => isset($raw['ipt_seo_title']) ? sed_import($raw['ipt_seo_title'], 'D', 'TXT') : '',
				'ipt_seo_desc' => isset($raw['ipt_seo_desc']) ? sed_import($raw['ipt_seo_desc'], 'D', 'TXT') : '',
				'ipt_seo_keywords' => isset($raw['ipt_seo_keywords']) ? sed_import($raw['ipt_seo_keywords'], 'D', 'TXT') : '',
				'ipt_seo_h1' => isset($raw['ipt_seo_h1']) ? sed_import($raw['ipt_seo_h1'], 'D', 'TXT') : ''
			);
		}
	}
	
	if (!empty($data)) {
		i18n_save_page_translations($new_page_id, $data);
	}
}
