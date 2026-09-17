<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/upgrade/upgrade_185_186.php
Version=186
Updated=2026-sep-17
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade: PFS nested folders; menu category auto-children; separate shield table; whosonline plugin hooks; dynamic translations & compiled cache
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$adminmain .= "Checking pfs_folders table...<br />";
$chk_pff = @sed_sql_query("SHOW TABLES LIKE '$db_pfs_folders'");
if ($chk_pff && sed_sql_numrows($chk_pff) > 0) {
	$chk_col = @sed_sql_query("SHOW COLUMNS FROM $db_pfs_folders LIKE 'pff_parentid'");
	if (!$chk_col || sed_sql_numrows($chk_col) == 0) {
		@sed_sql_query("ALTER TABLE $db_pfs_folders ADD COLUMN pff_parentid int(11) NOT NULL DEFAULT '0' AFTER pff_userid");
		@sed_sql_query("ALTER TABLE $db_pfs_folders ADD KEY pff_userid_parent (pff_userid, pff_parentid)");
		$adminmain .= "pfs_folders: pff_parentid column added.<br />";
	}
}

/* ======== Menu table: category auto-children (only if table exists) ======== */
$adminmain .= "Checking menu table (category auto-children)...<br />";
$chk_menu = @sed_sql_query("SHOW TABLES LIKE '$db_menu'");
if ($chk_menu && sed_sql_numrows($chk_menu) > 0) {
	$chk_col = @sed_sql_query("SHOW COLUMNS FROM $db_menu LIKE 'menu_cat'");
	if (!$chk_col || sed_sql_numrows($chk_col) == 0) {
		@sed_sql_query("ALTER TABLE $db_menu ADD COLUMN menu_cat varchar(64) NOT NULL DEFAULT '' AFTER menu_cssclass");
		$adminmain .= "menu: menu_cat column added.<br />";
	}
	$chk_col = @sed_sql_query("SHOW COLUMNS FROM $db_menu LIKE 'menu_cat_subcats'");
	if (!$chk_col || sed_sql_numrows($chk_col) == 0) {
		@sed_sql_query("ALTER TABLE $db_menu ADD COLUMN menu_cat_subcats tinyint(1) NOT NULL DEFAULT '0' AFTER menu_cat");
		$adminmain .= "menu: menu_cat_subcats column added.<br />";
	}
	$chk_col = @sed_sql_query("SHOW COLUMNS FROM $db_menu LIKE 'menu_cat_pages'");
	if (!$chk_col || sed_sql_numrows($chk_col) == 0) {
		@sed_sql_query("ALTER TABLE $db_menu ADD COLUMN menu_cat_pages tinyint(1) NOT NULL DEFAULT '0' AFTER menu_cat_subcats");
		$adminmain .= "menu: menu_cat_pages column added.<br />";
	}
}

/* ======== Shield table: separate flood protection table ======== */
$adminmain .= "Checking shield table...<br />";
$chk_shield = @sed_sql_query("SHOW TABLES LIKE '$db_shield'");
if (!$chk_shield || sed_sql_numrows($chk_shield) == 0) {
	sed_sql_query("CREATE TABLE IF NOT EXISTS $db_shield (
	  shield_ip varchar(45) NOT NULL DEFAULT '',
	  shield_lastseen int(11) NOT NULL DEFAULT '0',
	  shield_hammer tinyint(4) NOT NULL DEFAULT '0',
	  shield_limit int(11) NOT NULL DEFAULT '0',
	  shield_action varchar(32) NOT NULL DEFAULT '',
	  PRIMARY KEY (shield_ip),
	  KEY shield_lastseen (shield_lastseen)
	) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");
	$adminmain .= "shield table created.<br />";
}

/* ======== Whosonline plugin: reinstall using core functions ======== */
$adminmain .= "Reinstalling whosonline plugin...<br />";
$chk_plug = @sed_sql_query("SELECT COUNT(*) FROM $db_plugins WHERE pl_code = 'whosonline'");
if ($chk_plug && sed_sql_result($chk_plug, 0, 'COUNT(*)') > 0) {
	$adminmain .= "Removing old whosonline plugin registration...<br />";
	$adminmain .= sed_plugin_uninstall('whosonline', false, false);
}

$adminmain .= "Dropping legacy online table...<br />";
$db_online_legacy = $cfg['sqldbprefix'] . 'online';
@sed_sql_query("DROP TABLE IF EXISTS $db_online_legacy");

$adminmain .= "Installing whosonline plugin (v3.0)...<br />";
$adminmain .= sed_plugin_install('whosonline');

/* ======== Translations & Languages Tables (v186) ======== */
$adminmain .= "Checking translations and languages tables...<br />";

$chk_lang = @sed_sql_query("SHOW TABLES LIKE '$db_languages'");
if (!$chk_lang || sed_sql_numrows($chk_lang) == 0) {
	sed_sql_query("CREATE TABLE IF NOT EXISTS `$db_languages` (
	  `lang_code` varchar(16) NOT NULL,
	  `lang_title` varchar(64) NOT NULL,
	  `lang_native` varchar(64) NOT NULL,
	  `lang_direction` enum('ltr','rtl') NOT NULL DEFAULT 'ltr',
	  `lang_active` tinyint(1) NOT NULL DEFAULT 1,
	  `lang_is_default` tinyint(1) NOT NULL DEFAULT 0,
	  `lang_order` smallint(5) NOT NULL DEFAULT 100,
	  PRIMARY KEY (`lang_code`)
	) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

	global $sed_languages;
	$lang_dirs = glob(SED_ROOT . '/system/lang/*', GLOB_ONLYDIR);
	if (!empty($lang_dirs)) {
		$order = 10;
		foreach ($lang_dirs as $ld) {
			$code = basename($ld);
			if (file_exists($ld . '/main.lang.php')) {
				$info = sed_infoget($ld . '/main.lang.php');
				$lang_title = !empty($info['Name']) ? $info['Name'] : (isset($sed_languages[$code]) ? $sed_languages[$code] : ucfirst($code));
				$lang_native = !empty($info['Native']) ? $info['Native'] : (isset($sed_languages[$code]) ? $sed_languages[$code] : $lang_title);
				$is_def = ($code === $cfg['defaultlang']) ? 1 : (($code === 'en' && empty($cfg['defaultlang'])) ? 1 : 0);
				sed_sql_query("INSERT IGNORE INTO `$db_languages` (`lang_code`, `lang_title`, `lang_native`, `lang_direction`, `lang_active`, `lang_is_default`, `lang_order`) VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($lang_title) . "', '" . sed_sql_prep($lang_native) . "', 'ltr', 1, $is_def, $order)");
				$order += 10;
			}
		}
	}
	$adminmain .= "languages table created and seeded from installed language packs.<br />";
}

$chk_tra = @sed_sql_query("SHOW TABLES LIKE '$db_translations'");
if (!$chk_tra || sed_sql_numrows($chk_tra) == 0) {
	sed_sql_query("CREATE TABLE IF NOT EXISTS `$db_translations` (
	  `tra_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `tra_lang` varchar(16) NOT NULL,
	  `tra_scope` enum('core','module','plugin','skin') NOT NULL DEFAULT 'core',
	  `tra_code` varchar(64) NOT NULL DEFAULT 'main',
	  `tra_key` varchar(128) NOT NULL,
	  `tra_val` mediumtext NOT NULL,
	  `tra_type` tinyint(1) NOT NULL DEFAULT 0,
	  `tra_order` smallint(5) NOT NULL DEFAULT 500,
	  `tra_updated` int(11) NOT NULL DEFAULT 0,
	  PRIMARY KEY (`tra_id`),
	  UNIQUE KEY `idx_tra_unique` (`tra_lang`, `tra_scope`, `tra_code`, `tra_key`),
	  KEY `idx_tra_lookup` (`tra_lang`, `tra_order`),
	  KEY `idx_tra_scope` (`tra_scope`, `tra_code`)
	) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");
	$adminmain .= "translations table created.<br />";

	$adminmain .= "Importing existing translation files into database...<br />";
	sed_translations_import_all();

	$adminmain .= "Compiling initial language cache files...<br />";
	sed_translations_generate();
}

$adminmain .= "-----------------------<br />";
$adminmain .= "Changing the SQL version number to 186...<br />";
sed_sql_query("UPDATE $db_stats SET stat_value='186' WHERE stat_name='version'");
$upg_status = TRUE;
