<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.config.lang.inc.php
Version=186
Updated=2026-sep-17
Type=Core.admin
Author=Seditio Team
Description=Administration panel - Language configuration
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$setlang = sed_import('setlang', 'G', 'ALP');

if ($setlang == "update") {
	sed_check_xg();
	$lang_name = mb_strtolower(sed_import('lang_name', 'G', 'ALP', 16));
	if (!empty($lang_name)) {
		if (!empty($db_languages)) {
			sed_sql_query("UPDATE $db_languages SET lang_is_default = 0");
			sed_sql_query("UPDATE $db_languages SET lang_is_default = 1, lang_active = 1 WHERE lang_code = '" . sed_sql_prep($lang_name) . "'");
			sed_cache_clear('sed_languages_active');
		}
		sed_sql_query("UPDATE $db_config SET config_value = '" . sed_sql_prep($lang_name) . "' WHERE config_name = 'defaultlang' AND config_owner = 'core'");
		sed_redirect(sed_url("admin", "m=config&n=edit&o=core&p=lang", "", true), false, array('msg' => '917'));
		exit;
	}
}

$languages = array();
if (!empty($db_languages)) {
	$sql_langs = @sed_sql_query("SELECT * FROM $db_languages ORDER BY lang_order ASC, lang_code ASC");
	if ($sql_langs) {
		while ($lrow = sed_sql_fetchassoc($sql_langs)) {
			$languages[$lrow['lang_code']] = $lrow;
		}
	}
}

if (empty($languages)) {
	$lang_dirs = glob(SED_ROOT . '/system/lang/*', GLOB_ONLYDIR);
	if (!empty($lang_dirs)) {
		foreach ($lang_dirs as $dir) {
			$code = basename($dir);
			$languages[$code] = array(
				'lang_code' => $code,
				'lang_title' => isset($sed_languages[$code]) ? $sed_languages[$code] : ucfirst($code),
				'lang_native' => '',
				'lang_direction' => 'ltr',
				'lang_active' => 1,
				'lang_is_default' => ($code === $cfg['defaultlang']) ? 1 : 0,
				'lang_order' => 100
			);
		}
	}
}

$t = new XTemplate(sed_skinfile('admin.config.lang', false, true));

foreach ($languages as $x => $ldata) {
	$flag_file = 'system/img/flags/f-' . $x . '.gif';
	$flag_img = file_exists(SED_ROOT . '/' . $flag_file) ? '<img src="' . $flag_file . '" alt="' . $x . '" /> ' : '';

	$is_default = (!empty($ldata['lang_is_default']) || $x == $cfg['defaultlang']);
	$lang_default = $is_default ? '<i class="ic-check"></i>' : '<a href="' . sed_url("admin", "m=config&n=edit&o=core&p=lang&setlang=update&lang_name=" . $x . "&" . sed_xg()) . '" class="lang-set-default" title="' . $L['Default'] . '"><i class="ic-wand"></i></a>';

	$t->assign(array(
		"LANG_LIST_FLAG" => $flag_img,
		"LANG_LIST_CODE" => $x,
		"LANG_LIST_TITLE" => $ldata['lang_title'],
		"LANG_LIST_NATIVE" => !empty($ldata['lang_native']) ? $ldata['lang_native'] : '-',
		"LANG_LIST_DIRECTION" => !empty($ldata['lang_direction']) ? strtoupper($ldata['lang_direction']) : 'LTR',
		"LANG_LIST_ACTIVE" => !empty($ldata['lang_active']) ? $L['Yes'] : $L['No'],
		"LANG_LIST_ORDER" => isset($ldata['lang_order']) ? $ldata['lang_order'] : 100,
		"LANG_LIST_DEFAULT" => $lang_default,
		"LANG_LIST_TRANSLATIONS_URL" => sed_url("admin", "m=translations&s=list&tlang=" . $x)
	));

	$t->parse("ADMIN_CONFIG_LANG.LANG_LIST");
}

$t->parse("ADMIN_CONFIG_LANG");
$adminmain .= $t->text("ADMIN_CONFIG_LANG");
