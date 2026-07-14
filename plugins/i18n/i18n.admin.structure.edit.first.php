<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.admin.structure.edit.first.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=admin.structure.edit.first
File=i18n.admin.structure.edit.first
Hooks=admin.page.structure.edit.first
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $db_structure, $i18n_langs;

$id = sed_import('id', 'G', 'INT');

if ($id > 0 && !empty($i18n_langs) && is_array($i18n_langs)) {
	$sql_cat = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_id='$id' LIMIT 1");
	if (sed_sql_numrows($sql_cat) > 0) {
		$row_cat = sed_sql_fetchassoc($sql_cat);
		$structure_code = $row_cat['structure_code'];
		
		if (!empty($structure_code)) {
			$data = array();
			foreach ($i18n_langs as $lang) {
				$lang_lower = mb_strtolower($lang);
				$post_key = 'rstructure_i18n_' . $lang_lower;
				
				if (isset($_POST[$post_key]) && is_array($_POST[$post_key])) {
					$raw = $_POST[$post_key];
					
					$data[$lang_lower] = array(
						'ist_title' => isset($raw['ist_title']) ? sed_import($raw['ist_title'], 'D', 'TXT') : '',
						'ist_desc' => isset($raw['ist_desc']) ? sed_import($raw['ist_desc'], 'D', 'TXT') : '',
						'ist_text' => isset($raw['ist_text']) ? sed_import($raw['ist_text'], 'D', 'HTM') : '',
						'ist_seo_title' => isset($raw['ist_seo_title']) ? sed_import($raw['ist_seo_title'], 'D', 'TXT') : '',
						'ist_seo_desc' => isset($raw['ist_seo_desc']) ? sed_import($raw['ist_seo_desc'], 'D', 'TXT') : '',
						'ist_seo_keywords' => isset($raw['ist_seo_keywords']) ? sed_import($raw['ist_seo_keywords'], 'D', 'TXT') : '',
						'ist_seo_h1' => isset($raw['ist_seo_h1']) ? sed_import($raw['ist_seo_h1'], 'D', 'TXT') : ''
					);
				}
			}
			
			if (!empty($data)) {
				i18n_save_structure_translations($structure_code, $data);
			}
		}
	}
}
