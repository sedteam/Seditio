<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.install.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

// Create table for pages translations
$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}i18n_pages'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating i18n_pages table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS {$prefix}i18n_pages (
		ipt_id int(11) unsigned NOT NULL AUTO_INCREMENT,
		ipt_page_id int(11) unsigned NOT NULL,
		ipt_lang varchar(5) NOT NULL DEFAULT '',
		ipt_title varchar(255) DEFAULT NULL,
		ipt_desc varchar(255) DEFAULT NULL,
		ipt_text text,
		ipt_text2 text,
		ipt_seo_title varchar(255) DEFAULT NULL,
		ipt_seo_desc varchar(255) DEFAULT NULL,
		ipt_seo_keywords varchar(255) DEFAULT NULL,
		ipt_seo_h1 varchar(255) DEFAULT NULL,
		PRIMARY KEY (ipt_id),
		UNIQUE KEY ipt_page_lang (ipt_page_id, ipt_lang),
		KEY ipt_page_id (ipt_page_id)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate}");
}

// Create table for categories translations
$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}i18n_structure'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating i18n_structure table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS {$prefix}i18n_structure (
		ist_id int(11) unsigned NOT NULL AUTO_INCREMENT,
		ist_structure_code varchar(190) NOT NULL DEFAULT '',
		ist_lang varchar(5) NOT NULL DEFAULT '',
		ist_title varchar(100) DEFAULT NULL,
		ist_desc varchar(255) DEFAULT NULL,
		ist_text text,
		ist_seo_title varchar(255) DEFAULT NULL,
		ist_seo_desc varchar(255) DEFAULT NULL,
		ist_seo_keywords varchar(255) DEFAULT NULL,
		ist_seo_h1 varchar(255) DEFAULT NULL,
		PRIMARY KEY (ist_id),
		UNIQUE KEY ist_structure_lang (ist_structure_code, ist_lang),
		KEY ist_structure_code (ist_structure_code)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate}");
}

$res .= "i18n plugin tables installed.<br />";
