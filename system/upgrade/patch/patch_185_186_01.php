<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/upgrade/patch/patch_185_186_01.php
Version=186
Updated=2026-sep-22
Type=Core.patch
Author=Seditio Team
Description=Schema patch for v185->v186: add minimal shield and languages tables required for bootstrap
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* Ensure global table names and configuration are accessible */
global $cfg, $db_shield, $db_languages, $sed_languages;

if (empty($db_shield)) {
	$db_shield = $cfg['sqldbprefix'] . 'shield';
}
if (empty($db_languages)) {
	$db_languages = $cfg['sqldbprefix'] . 'languages';
}

/* ======== Shield table: required in common.php and footer ======== */
$chk_shield = @sed_sql_query("SHOW TABLES LIKE '$db_shield'", false);
if (!$chk_shield || sed_sql_numrows($chk_shield) == 0) {
	@sed_sql_query("CREATE TABLE IF NOT EXISTS $db_shield (
	  shield_ip varchar(45) NOT NULL DEFAULT '',
	  shield_lastseen int(11) NOT NULL DEFAULT '0',
	  shield_hammer tinyint(4) NOT NULL DEFAULT '0',
	  shield_limit int(11) NOT NULL DEFAULT '0',
	  shield_action varchar(32) NOT NULL DEFAULT '',
	  PRIMARY KEY (shield_ip),
	  KEY shield_lastseen (shield_lastseen)
	) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};", false);
}

/* ======== Languages Table: required in common.php ======== */
$chk_lang = @sed_sql_query("SHOW TABLES LIKE '$db_languages'", false);
if (!$chk_lang || sed_sql_numrows($chk_lang) == 0) {
	@sed_sql_query("CREATE TABLE IF NOT EXISTS `$db_languages` (
	  `lang_code` varchar(16) NOT NULL,
	  `lang_title` varchar(64) NOT NULL,
	  `lang_native` varchar(64) NOT NULL,
	  `lang_direction` enum('ltr','rtl') NOT NULL DEFAULT 'ltr',
	  `lang_active` tinyint(1) NOT NULL DEFAULT 1,
	  `lang_is_default` tinyint(1) NOT NULL DEFAULT 0,
	  `lang_order` smallint(5) NOT NULL DEFAULT 100,
	  PRIMARY KEY (`lang_code`)
	) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};", false);

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
				@sed_sql_query("INSERT IGNORE INTO `$db_languages` (`lang_code`, `lang_title`, `lang_native`, `lang_direction`, `lang_active`, `lang_is_default`, `lang_order`) VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($lang_title) . "', '" . sed_sql_prep($lang_native) . "', 'ltr', 1, $is_def, $order)", false);
				$order += 10;
			}
		}
	}
}
