<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.translations.inc.php
Version=186
Updated=2026-sep-16
Type=Core.admin
Author=Seditio Team
Description=Translations and Languages administration
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=translations")] = $L['adm_translations'];

$admintitle = $L['adm_translations_title'];

$s = sed_import('s', 'G', 'ALP', 16);
$part = sed_import('part', 'G', 'ALP', 16);
if (empty($s) && !empty($part)) {
	$s = $part;
}
$s = empty($s) ? 'list' : $s;

$a = sed_import('a', 'G', 'ALP', 24);
$tlang = sed_import('tlang', 'G', 'ALP', 16);
$tlang = empty($tlang) ? $usr['lang'] : $tlang;

$scope = sed_import('scope', 'G', 'TXT', 64);
$scope = empty($scope) ? 'all' : $scope;

$type = sed_import('type', 'G', 'ALP', 8);
$type = ($type === '' || $type === null) ? 'all' : $type;

$q = sed_import('q', 'G', 'TXT', 128);
$d = sed_import('d', 'G', 'INT');
$d = empty($d) ? 0 : (int)$d;

$num = sed_import('num', 'G', 'TXT', 8);
$perpage_values = array('50' => '50', '100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500', '1000' => '1000', 'all' => $L['All']);
if (empty($num) || !isset($perpage_values[$num])) {
	$num = '50';
}
$maxrowsperpage = ($num === 'all') ? 0 : (int)$num;

// ---------- Fetch active languages
$languages = array();
$chk_lang = @sed_sql_query("SELECT * FROM $db_languages ORDER BY lang_order ASC, lang_code ASC");
if ($chk_lang && sed_sql_numrows($chk_lang) > 0) {
	while ($row_l = sed_sql_fetchassoc($chk_lang)) {
		$languages[$row_l['lang_code']] = $row_l;
	}
}

if (empty($languages)) {
	$lang_dirs = glob(SED_ROOT . '/system/lang/*', GLOB_ONLYDIR);
	if (!empty($lang_dirs)) {
		$order = 10;
		foreach ($lang_dirs as $ld) {
			$code = basename($ld);
			if (file_exists($ld . '/main.lang.php')) {
				$info = sed_infoget($ld . '/main.lang.php');
				$lang_name = !empty($info['Name']) ? $info['Name'] : ucfirst($code);
				$languages[$code] = array(
					'lang_code' => $code,
					'lang_title' => $lang_name,
					'lang_native' => $lang_name,
					'lang_direction' => 'ltr',
					'lang_active' => 1,
					'lang_is_default' => ($code === $cfg['defaultlang']) ? 1 : 0,
					'lang_order' => $order
				);
				$order += 10;
			}
		}
	}
}

if (!isset($languages[$tlang])) {
	$tlang = isset($languages[$cfg['defaultlang']]) ? $cfg['defaultlang'] : key($languages);
}

// Helper URL parameters string
$base_filter_params = "m=translations&s=" . $s . "&tlang=" . $tlang . ($scope !== 'all' ? "&scope=" . $scope : "") . ($type !== 'all' ? "&type=" . $type : "") . ($num !== '50' ? "&num=" . $num : "") . (!empty($q) ? "&q=" . $q : "");

// ==========================================
// ACTIONS
// ==========================================

if ($a == 'update') {
	sed_check_xg();
	$tra_vals = sed_import('tra_val', 'P', 'ARR');

	if (!empty($tra_vals) && is_array($tra_vals)) {
		$now = time();
		foreach ($tra_vals as $id => $val) {
			$id = (int)$id;
			sed_sql_query("UPDATE $db_translations SET tra_val = '" . sed_sql_prep($val) . "', tra_updated = $now 
				WHERE tra_id = $id AND tra_type IN (0, 1)");
		}
		sed_translations_generate($tlang);
	}
	sed_redirect(sed_url("admin", $base_filter_params . ($d > 0 ? "&d=" . $d : ""), "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'edit_save') {
	sed_check_xg();
	$edit_key = sed_import('key', 'G', 'TXT', 128);
	$edit_scope = sed_import('scope', 'G', 'ALP', 16);
	$edit_code = sed_import('code', 'G', 'TXT', 64);
	$edit_vals = sed_import('edit_val', 'P', 'ARR');

	if (!empty($edit_key) && is_array($edit_vals)) {
		$now = time();
		foreach ($languages as $l_code => $l_data) {
			$val = isset($edit_vals[$l_code]) ? (string)$edit_vals[$l_code] : '';
			sed_sql_query("INSERT INTO $db_translations (tra_lang, tra_scope, tra_code, tra_key, tra_val, tra_type, tra_order, tra_updated)
				VALUES ('" . sed_sql_prep($l_code) . "', '" . sed_sql_prep($edit_scope) . "', '" . sed_sql_prep($edit_code) . "', '" . sed_sql_prep($edit_key) . "', '" . sed_sql_prep($val) . "', 1, 500, $now)
				ON DUPLICATE KEY UPDATE tra_val = VALUES(tra_val), tra_updated = $now");
		}
		sed_translations_generate();
	}
	sed_redirect(sed_url("admin", "m=translations&s=list&tlang=" . $tlang . ($scope !== 'all' ? "&scope=" . $scope : "") . (!empty($q) ? "&q=" . $q : ""), "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'add') {
	sed_check_xg();
	$new_key = sed_import('new_key', 'P', 'TXT', 128);
	$new_scope = sed_import('new_scope', 'P', 'ALP', 16);
	$new_scope = in_array($new_scope, array('core', 'module', 'plugin', 'skin')) ? $new_scope : 'core';
	$new_code = sed_import('new_code', 'P', 'TXT', 64);
	$new_code = empty($new_code) ? 'custom' : preg_replace('/[^a-zA-Z0-9_-]/', '', $new_code);
	$new_vals = sed_import('new_val', 'P', 'ARR');

	$new_key = preg_replace('/[^a-zA-Z0-9_]/', '', $new_key);

	if (!empty($new_key) && is_array($new_vals)) {
		$now = time();
		foreach ($languages as $l_code => $l_data) {
			$val = isset($new_vals[$l_code]) ? (string)$new_vals[$l_code] : '';
			sed_sql_query("INSERT INTO $db_translations (tra_lang, tra_scope, tra_code, tra_key, tra_val, tra_type, tra_order, tra_updated)
				VALUES ('" . sed_sql_prep($l_code) . "', '" . sed_sql_prep($new_scope) . "', '" . sed_sql_prep($new_code) . "', '" . sed_sql_prep($new_key) . "', '" . sed_sql_prep($val) . "', 0, 900, $now)
				ON DUPLICATE KEY UPDATE tra_val = VALUES(tra_val), tra_updated = $now");
		}
		sed_translations_generate();
		sed_redirect(sed_url("admin", "m=translations&s=list&tlang=" . $tlang, "", true), false, array('msg' => '917'));
		exit;
	}

} elseif ($a == 'delete') {
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');

	if ($id > 0) {
		$sql_del = sed_sql_query("SELECT tra_lang, tra_key, tra_type FROM $db_translations WHERE tra_id = $id LIMIT 1");
		if ($row_del = sed_sql_fetchassoc($sql_del)) {
			if ($row_del['tra_type'] == 0) {
				sed_sql_query("DELETE FROM $db_translations WHERE tra_id = $id");
				sed_translations_generate($row_del['tra_lang']);
			}
		}
	}
	sed_redirect(sed_url("admin", $base_filter_params . ($d > 0 ? "&d=" . $d : ""), "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'reset') {
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');

	if ($id > 0) {
		$sql_r = sed_sql_query("SELECT * FROM $db_translations WHERE tra_id = $id LIMIT 1");
		if ($row_r = sed_sql_fetchassoc($sql_r)) {
			if ($row_r['tra_type'] == 1) {
				$orig_file = '';
				$r_lang = $row_r['tra_lang'];
				$r_scope = $row_r['tra_scope'];
				$r_code = $row_r['tra_code'];
				$r_key = $row_r['tra_key'];

				if ($r_scope == 'core') {
					if ($r_code == 'admin') {
						$orig_file = SED_ROOT . '/system/lang/' . $r_lang . '/admin.lang.php';
					} elseif ($r_code == 'message') {
						$orig_file = SED_ROOT . '/system/lang/' . $r_lang . '/message.lang.php';
					} else {
						$orig_file = SED_ROOT . '/system/lang/' . $r_lang . '/main.lang.php';
					}
				} elseif ($r_scope == 'module') {
					$orig_file = SED_ROOT . '/modules/' . $r_code . '/lang/' . $r_code . '.' . $r_lang . '.lang.php';
				} elseif ($r_scope == 'plugin') {
					$orig_file = SED_ROOT . '/plugins/' . $r_code . '/lang/' . $r_code . '.' . $r_lang . '.lang.php';
				} elseif ($r_scope == 'skin') {
					$orig_file = SED_ROOT . '/skins/' . $r_code . '/' . $r_code . '.' . $r_lang . '.lang.php';
					if (!file_exists($orig_file)) {
						$orig_file = SED_ROOT . '/system/adminskin/' . $r_code . '/' . $r_code . '.' . $r_lang . '.lang.php';
					}
				}

				if (file_exists($orig_file)) {
					$L = array();
					@include($orig_file);

					$default_val = null;
					if (isset($L[$r_key])) {
						if (is_array($L[$r_key])) {
							$default_val = isset($L[$r_key][0]) ? $L[$r_key][0] : '';
						} else {
							$default_val = $L[$r_key];
						}
					} elseif (substr($r_key, -5) === '_hint' && isset($L[substr($r_key, 0, -5)])) {
						$base = $L[substr($r_key, 0, -5)];
						if (is_array($base) && isset($base[1])) {
							$default_val = $base[1];
						}
					}

					if ($default_val !== null) {
						$now = time();
						sed_sql_query("UPDATE $db_translations SET tra_val = '" . sed_sql_prep($default_val) . "', tra_updated = $now WHERE tra_id = $id");
						sed_translations_generate($r_lang);
					}
				}
			}
		}
	}
	sed_redirect(sed_url("admin", $base_filter_params . ($d > 0 ? "&d=" . $d : ""), "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'regenerate') {
	sed_check_xg();
	sed_translations_generate();
	sed_redirect(sed_url("admin", "m=translations&s=tools&msg=917", "", true));
	exit;

} elseif ($a == 'import') {
	sed_check_xg();
	sed_translations_import_all();
	sed_translations_generate();
	sed_redirect(sed_url("admin", "m=translations&s=tools&msg=917", "", true));
	exit;

} elseif ($a == 'lang_save') {
	sed_check_xg();
	$l_code = sed_import('lang_code', 'P', 'ALP', 16);
	$l_title = sed_import('lang_title', 'P', 'TXT', 64);
	$l_native = sed_import('lang_native', 'P', 'TXT', 64);
	$l_direction = sed_import('lang_direction', 'P', 'ALP', 4);
	$l_direction = ($l_direction === 'rtl') ? 'rtl' : 'ltr';
	$l_active = sed_import('lang_active', 'P', 'BOL') ? 1 : 0;
	$l_order = sed_import('lang_order', 'P', 'INT');
	$l_base = sed_import('lang_base', 'P', 'ALP', 16);
	$is_edit = sed_import('is_edit', 'P', 'BOL');

	if (!empty($l_code) && !empty($l_title)) {
		$chk = sed_sql_query("SELECT lang_code FROM $db_languages WHERE lang_code = '" . sed_sql_prep($l_code) . "'");
		$exists = ($chk && sed_sql_numrows($chk) > 0);

		sed_sql_query("INSERT INTO $db_languages (lang_code, lang_title, lang_native, lang_direction, lang_active, lang_order)
			VALUES ('" . sed_sql_prep($l_code) . "', '" . sed_sql_prep($l_title) . "', '" . sed_sql_prep($l_native) . "', '" . sed_sql_prep($l_direction) . "', $l_active, " . (int)$l_order . ")
			ON DUPLICATE KEY UPDATE lang_title = VALUES(lang_title), lang_native = VALUES(lang_native), lang_direction = VALUES(lang_direction), lang_active = VALUES(lang_active), lang_order = VALUES(lang_order)");

		sed_cache_clear('sed_languages_active');

		// Clone translations from base language if creating a new language with a base
		if (!$exists && !empty($l_base) && $l_base !== $l_code) {
			$now = time();
			sed_sql_query("INSERT INTO $db_translations (tra_lang, tra_scope, tra_code, tra_key, tra_val, tra_type, tra_order, tra_updated)
				SELECT '" . sed_sql_prep($l_code) . "', tra_scope, tra_code, tra_key, tra_val, 1, tra_order, $now
				FROM $db_translations
				WHERE tra_lang = '" . sed_sql_prep($l_base) . "'
				ON DUPLICATE KEY UPDATE tra_val = VALUES(tra_val)");
		}

		sed_translations_generate($l_code);
	}
	sed_redirect(sed_url("admin", "m=translations&s=languages", "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'lang_delete') {
	sed_check_xg();
	$code = sed_import('code', 'G', 'ALP', 16);
	if (!empty($code) && $code !== $cfg['defaultlang']) {
		sed_sql_query("DELETE FROM $db_languages WHERE lang_code = '" . sed_sql_prep($code) . "' AND lang_is_default = 0");
		sed_sql_query("DELETE FROM $db_translations WHERE tra_lang = '" . sed_sql_prep($code) . "'");
		$cache_file = SED_ROOT . '/' . $cfg['cache_dir'] . '/sed_lang.' . $code . '.php';
		if (file_exists($cache_file)) {
			@unlink($cache_file);
		}
		sed_cache_clear('sed_languages_active');
	}
	sed_redirect(sed_url("admin", "m=translations&s=languages", "", true), false, array('msg' => '917'));
	exit;

} elseif ($a == 'lang_default') {
	sed_check_xg();
	$code = sed_import('code', 'G', 'ALP', 16);
	if (!empty($code)) {
		sed_sql_query("UPDATE $db_languages SET lang_is_default = 0");
		sed_sql_query("UPDATE $db_languages SET lang_is_default = 1, lang_active = 1 WHERE lang_code = '" . sed_sql_prep($code) . "'");
		sed_cache_clear('sed_languages_active');
	}
	sed_redirect(sed_url("admin", "m=translations&s=languages", "", true), false, array('msg' => '917'));
	exit;
}

// ==========================================
// VIEW / TEMPLATE RENDERING
// ==========================================

$t = new XTemplate(sed_skinfile('admin.translations', false, true));

$t->assign(array(
	"ADMIN_TRANSLATIONS_TITLE" => $admintitle,
	"TRANSLATIONS_CURRENT_LANG" => $tlang,
	"TRANSLATIONS_CURRENT_SCOPE" => $scope,
	"TRANSLATIONS_CURRENT_TYPE" => $type,
	"TRANSLATIONS_CURRENT_NUM" => $num,
	"TRANSLATIONS_SUBMIT_URL" => sed_url("admin", "a=update&" . $base_filter_params . ($d > 0 ? "&d=" . $d : "") . "&" . sed_xg()),
	"TRANSLATIONS_ADD_URL" => sed_url("admin", "m=translations&a=add&" . sed_xg()),
	"TRANSLATIONS_REGENERATE_URL" => sed_url("admin", "m=translations&a=regenerate&" . sed_xg()),
	"TRANSLATIONS_IMPORT_URL" => sed_url("admin", "m=translations&a=import&" . sed_xg()),
	"TRANSLATIONS_LANG_SAVE_URL" => sed_url("admin", "m=translations&a=lang_save&" . sed_xg()),
	"SUBNAV_LIST_URL" => sed_url("admin", "m=translations&s=list&tlang=" . $tlang),
	"SUBNAV_ADD_URL" => sed_url("admin", "m=translations&s=add&tlang=" . $tlang),
	"SUBNAV_LANGUAGES_URL" => sed_url("admin", "m=translations&s=languages"),
	"SUBNAV_TOOLS_URL" => sed_url("admin", "m=translations&s=tools"),
	"SUBNAV_LIST_SELECTED" => ($s === 'list') ? 'current' : '',
	"SUBNAV_ADD_SELECTED" => ($s === 'add') ? 'current' : '',
	"SUBNAV_LANGUAGES_SELECTED" => ($s === 'languages' || $s === 'lang_edit') ? 'current' : '',
	"SUBNAV_TOOLS_SELECTED" => ($s === 'tools') ? 'current' : '',
	"SEARCH_ACTION_URL" => sed_url("admin", "m=translations&s=list"),
	"SEARCH_RESET_URL" => sed_url("admin", "m=translations&s=list&tlang=" . $tlang)
));

// Language tabs / selector bar
foreach ($languages as $l_code => $l_data) {
	$flag_file = 'system/img/flags/f-' . $l_code . '.gif';
	$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $l_code . '" />' : '';
	$is_active = ($l_code === $tlang);

	$t->assign(array(
		"LANG_TAB_CODE" => $l_code,
		"LANG_TAB_TITLE" => $l_data['lang_title'],
		"LANG_TAB_NATIVE" => $l_data['lang_native'],
		"LANG_TAB_FLAG" => $flag_img,
		"LANG_TAB_URL" => sed_url("admin", "m=translations&s=" . $s . "&tlang=" . $l_code . ($scope !== 'all' ? "&scope=" . $scope : "") . ($type !== 'all' ? "&type=" . $type : "") . ($num !== '50' ? "&num=" . $num : "") . (!empty($q) ? "&q=" . $q : "")),
		"LANG_TAB_SELECTED" => $is_active ? '' : 'btn-outline',
		"LANG_TAB_CHECK" => $is_active ? '<i class="ic-check"></i> ' : ''
	));
	$t->parse("ADMIN_TRANSLATIONS.LANG_TABS");
}

// Sub-page logic
if ($s == 'languages') {
	// ---------- Languages tab
	foreach ($languages as $l_code => $l_data) {
		$flag_file = 'system/img/flags/f-' . $l_code . '.gif';
		$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $l_code . '" />' : '';

		$t->assign(array(
			"LANG_ROW_CODE" => $l_code,
			"LANG_ROW_TITLE" => $l_data['lang_title'],
			"LANG_ROW_NATIVE" => $l_data['lang_native'],
			"LANG_ROW_DIRECTION" => strtoupper($l_data['lang_direction']),
			"LANG_ROW_FLAG" => $flag_img,
			"LANG_ROW_ACTIVE" => $l_data['lang_active'] ? $L['Yes'] : $L['No'],
			"LANG_ROW_ORDER" => isset($l_data['lang_order']) ? $l_data['lang_order'] : 100,
			"LANG_ROW_EDIT_URL" => sed_url("admin", "m=translations&s=lang_edit&code=" . $l_code),
			"LANG_ROW_DELETE_URL" => sed_url("admin", "m=translations&a=lang_delete&code=" . $l_code . "&" . sed_xg())
		));

		if (!empty($l_data['lang_is_default'])) {
			$t->parse("ADMIN_TRANSLATIONS.LANGUAGES.LANG_ROW.LANG_DEFAULT_YES");
		} else {
			$t->assign("LANG_ROW_DEFAULT_URL", sed_url("admin", "m=translations&a=lang_default&code=" . $l_code . "&" . sed_xg()));
			$t->parse("ADMIN_TRANSLATIONS.LANGUAGES.LANG_ROW.LANG_DEFAULT_SET");
			$t->parse("ADMIN_TRANSLATIONS.LANGUAGES.LANG_ROW.LANG_DELETE_BTN");
		}

		$t->parse("ADMIN_TRANSLATIONS.LANGUAGES.LANG_ROW");
	}

	// Add Language Form Fields
	$base_options = array('' => $L['adm_translations_lang_nobase']);
	foreach ($languages as $bc => $bd) {
		$base_options[$bc] = $bd['lang_title'] . ' (' . $bd['lang_native'] . ') [' . $bc . ']';
	}

	$t->assign(array(
		"LANG_ADD_CODE_INPUT" => sed_textbox('lang_code', '', 16, 16, 'form-control', false, 'text', array('placeholder' => 'de', 'required' => 'required')),
		"LANG_ADD_BASE_SELECT" => sed_selectbox('', 'lang_base', $base_options, false),
		"LANG_ADD_TITLE_INPUT" => sed_textbox('lang_title', '', 40, 64, 'form-control', false, 'text', array('placeholder' => 'German', 'required' => 'required')),
		"LANG_ADD_NATIVE_INPUT" => sed_textbox('lang_native', '', 40, 64, 'form-control', false, 'text', array('placeholder' => 'Deutsch', 'required' => 'required')),
		"LANG_ADD_DIRECTION_SELECT" => sed_selectbox('ltr', 'lang_direction', array('ltr' => 'LTR (Left-to-Right)', 'rtl' => 'RTL (Right-to-Left)'), false),
		"LANG_ADD_ORDER_INPUT" => sed_textbox('lang_order', '100', 5, 5, 'form-control', false, 'number'),
		"LANG_ADD_ACTIVE_CHECKBOX" => sed_checkbox('lang_active', '1', true, $L['Active'])
	));

	$t->parse("ADMIN_TRANSLATIONS.LANGUAGES");

} elseif ($s == 'lang_edit' || ($s == 'languages' && $a == 'lang_edit')) {
	// ---------- Edit Language page
	$edit_code = sed_import('code', 'G', 'ALP', 16);
	if (!empty($edit_code) && isset($languages[$edit_code])) {
		$ed = $languages[$edit_code];

		$urlpaths[sed_url("admin", "m=translations&s=languages")] = $L['adm_translations_languages'];
		$urlpaths[sed_url("admin", "m=translations&s=lang_edit&code=" . $edit_code)] = $L['adm_translations_lang_edit'] . ': ' . $ed['lang_title'] . ' (' . $edit_code . ')';

		$flag_file = 'system/img/flags/f-' . $edit_code . '.gif';
		$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $edit_code . '" /> ' : '';

		$t->assign(array(
			"LANG_EDIT_CODE" => $edit_code,
			"LANG_EDIT_TITLE" => $ed['lang_title'],
			"LANG_EDIT_FLAG" => $flag_img,
			"LANG_EDIT_CODE_INPUT" => '<input type="hidden" name="lang_code" value="' . $edit_code . '" /><input type="hidden" name="is_edit" value="1" /><span class="badge"><strong>' . $flag_img . $edit_code . '</strong></span>',
			"LANG_EDIT_TITLE_INPUT" => sed_textbox('lang_title', $ed['lang_title'], 40, 64, 'form-control', false, 'text', array('required' => 'required')),
			"LANG_EDIT_NATIVE_INPUT" => sed_textbox('lang_native', $ed['lang_native'], 40, 64, 'form-control', false, 'text', array('required' => 'required')),
			"LANG_EDIT_DIRECTION_SELECT" => sed_selectbox($ed['lang_direction'], 'lang_direction', array('ltr' => 'LTR (Left-to-Right)', 'rtl' => 'RTL (Right-to-Left)'), false),
			"LANG_EDIT_ORDER_INPUT" => sed_textbox('lang_order', (isset($ed['lang_order']) ? $ed['lang_order'] : 100), 5, 5, 'form-control', false, 'number'),
			"LANG_EDIT_ACTIVE_CHECKBOX" => sed_checkbox('lang_active', '1', !empty($ed['lang_active']), $L['Active']),
			"LANG_EDIT_CANCEL_URL" => sed_url("admin", "m=translations&s=languages")
		));

		$t->parse("ADMIN_TRANSLATIONS.EDIT_LANGUAGE");
	} else {
		sed_redirect(sed_url("admin", "m=translations&s=languages", "", true));
		exit;
	}

} elseif ($s == 'tools') {
	// ---------- Tools tab
	$t->parse("ADMIN_TRANSLATIONS.TOOLS");

} elseif ($s == 'add') {
	// ---------- Add custom variable tab
	$scopes_add = array('core' => 'Core', 'module' => 'Module', 'plugin' => 'Plugin', 'skin' => 'Skin');

	$t->assign(array(
		"ADD_KEY_INPUT" => sed_textbox('new_key', '', 40, 128, 'form-control', false, 'text', array('placeholder' => 'my_custom_string', 'required' => 'required')),
		"ADD_SCOPE_SELECT" => sed_selectbox('core', 'new_scope', $scopes_add, false),
		"ADD_CODE_INPUT" => sed_textbox('new_code', 'custom', 30, 64, 'form-control')
	));

	foreach ($languages as $l_code => $l_data) {
		$flag_file = 'system/img/flags/f-' . $l_code . '.gif';
		$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $l_code . '" /> ' : '';

		$t->assign(array(
			"ADD_LANG_CODE" => $l_code,
			"ADD_LANG_TITLE" => $flag_img . $l_data['lang_title'] . ' (' . $l_data['lang_native'] . ')',
			"ADD_LANG_VALUE_INPUT" => sed_textarea('new_val[' . $l_code . ']', '', 2, 60, 'noeditor', false, 'form-control')
		));
		$t->parse("ADMIN_TRANSLATIONS.ADD_VARIABLE.ADD_LANG_ROW");
	}
	$t->parse("ADMIN_TRANSLATIONS.ADD_VARIABLE");

} elseif ($s == 'edit') {
	// ---------- Edit variable across all languages tab
	$edit_key = sed_import('key', 'G', 'TXT', 128);
	$edit_scope = sed_import('scope', 'G', 'ALP', 16);
	$edit_code = sed_import('code', 'G', 'TXT', 64);

	$urlpaths[sed_url("admin", "m=translations&s=edit&key=" . $edit_key . "&scope=" . $edit_scope . "&code=" . $edit_code)] = '$L[\'' . $edit_key . '\']';

	$sql_ext = sed_sql_query("SELECT tra_lang, tra_val FROM $db_translations 
		WHERE tra_key = '" . sed_sql_prep($edit_key) . "' 
		AND tra_scope = '" . sed_sql_prep($edit_scope) . "' 
		AND tra_code = '" . sed_sql_prep($edit_code) . "'");

	$existing_vals = array();
	while ($r = sed_sql_fetchassoc($sql_ext)) {
		$existing_vals[$r['tra_lang']] = $r['tra_val'];
	}

	$t->assign(array(
		"EDIT_KEY" => $edit_key,
		"EDIT_DISPLAY_KEY" => '$L[\'' . sed_cc($edit_key) . '\']',
		"EDIT_SCOPE" => sed_cc($edit_scope),
		"EDIT_CODE" => sed_cc($edit_code),
		"EDIT_ACTION_URL" => sed_url("admin", "m=translations&a=edit_save&key=" . $edit_key . "&scope=" . $edit_scope . "&code=" . $edit_code . "&tlang=" . $tlang . (!empty($q) ? "&q=" . $q : "") . "&" . sed_xg()),
		"EDIT_CANCEL_URL" => sed_url("admin", "m=translations&s=list&tlang=" . $tlang . ($scope !== 'all' ? "&scope=" . $scope : "") . (!empty($q) ? "&q=" . $q : ""))
	));

	foreach ($languages as $l_code => $l_data) {
		$flag_file = 'system/img/flags/f-' . $l_code . '.gif';
		$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $l_code . '" /> ' : '';
		$val = isset($existing_vals[$l_code]) ? $existing_vals[$l_code] : '';

		$t->assign(array(
			"EDIT_LANG_CODE" => $l_code,
			"EDIT_LANG_TITLE" => $flag_img . $l_data['lang_title'] . ' (' . $l_data['lang_native'] . ')',
			"EDIT_LANG_VALUE_INPUT" => sed_textarea('edit_val[' . $l_code . ']', $val, 2, 60, 'noeditor', false, 'form-control')
		));
		$t->parse("ADMIN_TRANSLATIONS.EDIT_VARIABLE.EDIT_LANG_ROW");
	}
	$t->parse("ADMIN_TRANSLATIONS.EDIT_VARIABLE");

} else {
	// ---------- List / Edit tab (Default)
	// Dynamic tree scope options with -- indentation
	$tree_scopes = array(
		'core' => array('title' => 'Core', 'items' => array()),
		'module' => array('title' => 'Modules', 'items' => array()),
		'plugin' => array('title' => 'Plugins', 'items' => array()),
		'skin' => array('title' => 'Skins', 'items' => array())
	);

	$sql_codes = sed_sql_query("SELECT DISTINCT tra_scope, tra_code FROM $db_translations WHERE tra_lang = '" . sed_sql_prep($tlang) . "' ORDER BY tra_scope ASC, tra_code ASC");
	while ($rc = sed_sql_fetchassoc($sql_codes)) {
		$sc = $rc['tra_scope'];
		$cd = $rc['tra_code'];
		if (isset($tree_scopes[$sc]) && !in_array($cd, $tree_scopes[$sc]['items'], true)) {
			$tree_scopes[$sc]['items'][] = $cd;
		}
	}

	$scope_options = array('all' => $L['adm_translations_all_scopes']);
	foreach ($tree_scopes as $sc_key => $sc_data) {
		if (empty($sc_data['items'])) continue;
		$scope_options[$sc_key] = $sc_data['title'];
		foreach ($sc_data['items'] as $item_code) {
			$scope_options[$sc_key . ':' . $item_code] = '-- ' . $item_code;
		}
	}

	$type_options = array(
		'all' => $L['adm_translations_all_types'],
		'1' => $L['adm_translations_system'],
		'0' => $L['adm_translations_custom'],
		'2' => $L['adm_translations_locked']
	);

	$where = array();
	$where[] = "tra_lang = '" . sed_sql_prep($tlang) . "'";

	if ($scope !== 'all') {
		if (strpos($scope, ':') !== false) {
			list($s_scope, $s_code) = explode(':', $scope, 2);
			$where[] = "tra_scope = '" . sed_sql_prep($s_scope) . "' AND tra_code = '" . sed_sql_prep($s_code) . "'";
		} else {
			$where[] = "tra_scope = '" . sed_sql_prep($scope) . "'";
		}
	}

	if ($type !== 'all') {
		$where[] = "tra_type = " . (int)$type;
	}

	if (!empty($q)) {
		$q_sql = sed_sql_prep($q);
		$where[] = "(tra_key LIKE '%" . $q_sql . "%' OR tra_val LIKE '%" . $q_sql . "%')";
	}

	$where_str = implode(" AND ", $where);

	$sql_cnt = sed_sql_query("SELECT COUNT(*) AS total FROM $db_translations WHERE $where_str");
	$row_cnt = sed_sql_fetchassoc($sql_cnt);
	$totallines = (int)$row_cnt['total'];

	$pagination_url = sed_url("admin", "m=translations&s=list&tlang=" . $tlang . ($scope !== 'all' ? "&scope=" . $scope : "") . ($type !== 'all' ? "&type=" . $type : "") . ($num !== '50' ? "&num=" . $num : "") . (!empty($q) ? "&q=" . $q : ""));

	if ($maxrowsperpage > 0) {
		$pagination = sed_pagination($pagination_url, $d, $totallines, $maxrowsperpage);
		list($pageprev, $pagenext) = sed_pagination_pn($pagination_url, $d, $totallines, $maxrowsperpage, TRUE);
		$limit_str = "LIMIT $d, $maxrowsperpage";
	} else {
		$pagination = '';
		$pageprev = '';
		$pagenext = '';
		$limit_str = '';
	}

	$sql_tra = sed_sql_query("SELECT * FROM $db_translations WHERE $where_str 
		ORDER BY tra_order ASC, tra_key ASC 
		$limit_str");

	while ($row = sed_sql_fetchassoc($sql_tra)) {
		$is_locked = ($row['tra_type'] == 2);
		$is_system = ($row['tra_type'] == 1);
		$is_custom = ($row['tra_type'] == 0);

		if ($is_locked) {
			$val_input = sed_textbox("tra_val[" . $row['tra_id'] . "]", $row['tra_val'], 56, 255, "form-control", true);
		} else {
			if (mb_strlen($row['tra_val']) > 80 || strpos($row['tra_val'], "\n") !== false) {
				$val_input = sed_textarea("tra_val[" . $row['tra_id'] . "]", $row['tra_val'], 2, 60, "noeditor", false, "form-control");
			} else {
				$val_input = sed_textbox("tra_val[" . $row['tra_id'] . "]", $row['tra_val'], 56, 255, "form-control");
			}
		}

		$t->assign(array(
			"TRA_ROW_ID" => $row['tra_id'],
			"TRA_ROW_KEY" => '$L[\'' . sed_cc($row['tra_key']) . '\']',
			"TRA_ROW_RAW_KEY" => sed_cc($row['tra_key']),
			"TRA_ROW_SCOPE" => sed_cc($row['tra_scope']),
			"TRA_ROW_CODE" => sed_cc($row['tra_code']),
			"TRA_ROW_VALUE_INPUT" => $val_input,
			"TRA_ROW_EDIT_URL" => sed_url("admin", "m=translations&s=edit&key=" . $row['tra_key'] . "&scope=" . $row['tra_scope'] . "&code=" . $row['tra_code'] . "&tlang=" . $tlang . (!empty($q) ? "&q=" . $q : ""))
		));

		// Type badges via TPL blocks
		if ($is_locked) {
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_TYPE_LOCKED");
		} elseif ($is_system) {
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_TYPE_SYSTEM");
		} else {
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_TYPE_CUSTOM");
		}

		// Row actions via TPL blocks
		if (!$is_locked) {
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_ACTION_EDIT");
		}

		if ($is_custom) {
			$t->assign("TRA_ROW_DELETE_URL", sed_url("admin", "m=translations&a=delete&id=" . $row['tra_id'] . "&" . $base_filter_params . ($d > 0 ? "&d=" . $d : "") . "&" . sed_xg()));
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_ACTION_DELETE");
		} elseif ($is_system) {
			$t->assign("TRA_ROW_RESET_URL", sed_url("admin", "m=translations&a=reset&id=" . $row['tra_id'] . "&" . $base_filter_params . ($d > 0 ? "&d=" . $d : "") . "&" . sed_xg()));
			$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW.TRA_ACTION_RESET");
		}

		$t->parse("ADMIN_TRANSLATIONS.LIST.TRA_ROW");
	}

	$perpage_select_top = sed_selectbox($num, 'num', $perpage_values, false, true, false, array('onchange' => 'this.form.submit();'));
	$perpage_base_url = sed_url("admin", "m=translations&s=list&tlang=" . $tlang . ($scope !== 'all' ? "&scope=" . $scope : "") . ($type !== 'all' ? "&type=" . $type : "") . (!empty($q) ? "&q=" . $q : ""));
	$perpage_delim = (strpos($perpage_base_url, '?') !== false) ? '&' : '?';
	$perpage_select_bottom = sed_selectbox($num, 'num_b', $perpage_values, false, true, false, array('onchange' => 'location.href=\'' . $perpage_base_url . $perpage_delim . 'num=\' + this.value;'));

	$cur_lang_title = isset($languages[$tlang]['lang_title']) ? $languages[$tlang]['lang_title'] : $tlang;
	$cur_lang_native = isset($languages[$tlang]['lang_native']) ? $languages[$tlang]['lang_native'] : $tlang;
	$cur_lang_header = $cur_lang_title . ' (' . $cur_lang_native . ')';

	$t->assign(array(
		"TRANSLATIONS_LANG_TITLE" => $cur_lang_title,
		"TRANSLATIONS_LANG_NATIVE" => $cur_lang_native,
		"TRANSLATIONS_LANG_HEADER" => $cur_lang_header,
		"TRANSLATIONS_SEARCH_INPUT" => sed_textbox('q', $q, 30, 128, 'form-control', false, 'text', array('placeholder' => $L['adm_translations_search'])),
		"TRANSLATIONS_SCOPE_SELECT" => sed_selectbox($scope, 'scope', $scope_options, false, true, false, array('onchange' => 'this.form.submit();')),
		"TRANSLATIONS_TYPE_SELECT" => sed_selectbox($type, 'type', $type_options, false, true, false, array('onchange' => 'this.form.submit();')),
		"TRANSLATIONS_PERPAGE_TOP" => $perpage_select_top,
		"TRANSLATIONS_PERPAGE_BOTTOM" => $perpage_select_bottom,
		"TRANSLATIONS_PAGINATION" => $pagination,
		"TRANSLATIONS_PAGEPREV" => $pageprev,
		"TRANSLATIONS_PAGENEXT" => $pagenext,
		"TRANSLATIONS_TOTALITEMS" => $totallines
	));

	$t->parse("ADMIN_TRANSLATIONS.LIST");
}

$t->parse("ADMIN_TRANSLATIONS");
$adminmain .= $t->text("ADMIN_TRANSLATIONS");
