<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=Page module install: tables, default structure, auth, welcome page
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

/* ======== Table: pages ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "pages'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating pages table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "pages (
  page_id int(11) unsigned NOT NULL auto_increment,
  page_state tinyint(1) unsigned NOT NULL DEFAULT '0',
  page_cat varchar(190) DEFAULT NULL,
  page_key varchar(16) DEFAULT NULL,
  page_title varchar(255) DEFAULT NULL,
  page_desc varchar(255) DEFAULT NULL,
  page_text text,
  page_text2 text,
  page_author varchar(24) DEFAULT NULL,
  page_ownerid int(11) NOT NULL DEFAULT '0',
  page_date int(11) NOT NULL DEFAULT '0',
  page_begin int(11) NOT NULL DEFAULT '0',
  page_expire int(11) NOT NULL DEFAULT '0',
  page_file tinyint(1) DEFAULT NULL,
  page_url varchar(255) DEFAULT NULL,
  page_size varchar(16) DEFAULT NULL,
  page_count mediumint(8) unsigned DEFAULT '0',
  page_allowcomments tinyint(1) NOT NULL DEFAULT '1',
  page_allowratings tinyint(1) NOT NULL DEFAULT '1',
  page_rating decimal(5,2) NOT NULL DEFAULT '0.00',
  page_comcount mediumint(8) unsigned DEFAULT '0',
  page_filecount mediumint(8) unsigned DEFAULT '0',
  page_alias varchar(255) NOT NULL DEFAULT '',
  page_seo_title varchar(255) DEFAULT NULL,
  page_seo_desc varchar(255) DEFAULT NULL,
  page_seo_keywords varchar(255) DEFAULT NULL,
  page_seo_h1 varchar(255) DEFAULT NULL,
  page_seo_index tinyint(1) unsigned NOT NULL DEFAULT '1',
  page_seo_follow tinyint(1) unsigned NOT NULL DEFAULT '1',
  page_thumb varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (page_id),
  KEY page_cat (page_cat)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Table: structure ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "structure'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating structure table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "structure (
  structure_id mediumint(8) NOT NULL auto_increment,
  structure_code varchar(190) NOT NULL DEFAULT '',
  structure_path varchar(190) NOT NULL DEFAULT '',
  structure_tpl varchar(64) NOT NULL DEFAULT '',
  structure_title varchar(100) NOT NULL DEFAULT '',
  structure_desc varchar(255) NOT NULL DEFAULT '',
  structure_text text,
  structure_icon varchar(128) NOT NULL DEFAULT '',
  structure_group tinyint(1) NOT NULL DEFAULT '0',
  structure_order varchar(16) NOT NULL DEFAULT 'date.desc',
  structure_allowcomments tinyint(1) NOT NULL DEFAULT '1',
  structure_allowratings tinyint(1) NOT NULL DEFAULT '1',
  structure_thumb varchar(255) NOT NULL DEFAULT '',
  structure_seo_title varchar(255) DEFAULT NULL,
  structure_seo_desc varchar(255) DEFAULT NULL,
  structure_seo_keywords varchar(255) DEFAULT NULL,
  structure_seo_h1 varchar(255) DEFAULT NULL,
  structure_seo_index tinyint(1) unsigned NOT NULL DEFAULT '1',
  structure_seo_follow tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (structure_id)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Default categories (structure) ======== */

$chk = sed_sql_query("SELECT 1 FROM " . $prefix . "structure LIMIT 1");
if (sed_sql_numrows($chk) == 0) {
	$res .= "Inserting default structure (categories)...<br />";
	sed_sql_query("INSERT INTO " . $prefix . "structure (structure_id, structure_code, structure_path, structure_tpl, structure_title, structure_desc, structure_text, structure_icon, structure_group, structure_order, structure_allowcomments, structure_allowratings, structure_thumb, structure_seo_title, structure_seo_desc, structure_seo_keywords, structure_seo_h1, structure_seo_index, structure_seo_follow) VALUES
(1, 'articles', '1', '', 'Articles', '', '', '', 1, 'title.asc', 1, 1, '', '', '', '', '', 1, 1),
(2, 'sample1', '1.1', '', 'Sample category 1', 'Description for the Sample category 1', '', '', 0, 'title.asc', 1, 1, '', '', '', '', '', 1, 1),
(3, 'sample2', '1.2', '', 'Sample category 2', 'Description for the Sample category 2', '', '', 0, 'title.asc', 1, 1, '', '', '', '', '', 1, 1),
(4, 'news', '2', '', 'News', '', '', '', 0, 'date.desc', 1, 1, '', '', '', '', '', 1, 1)");
}

/* ======== Auth for each category (all groups: guests, inactive, banned, members, admins, mods, custom) ======== */

global $sed_groups, $db_auth;
$db_auth = $prefix . 'auth';
$setby = (isset($usr['id']) && $usr['id'] > 0) ? (int)$usr['id'] : 1;

$page_default_rights = array(
	SED_GROUP_DEFAULT => 'RW',
	SED_GROUP_GUESTS => 'R',
	SED_GROUP_INACTIVE => 'R',
	SED_GROUP_BANNED => '',
	SED_GROUP_MEMBERS => 'RW',
	SED_GROUP_MODERATORS => 'RWA',
	SED_GROUP_SUPERADMINS => 'RWA12345',
);
$page_default_lock = array(
	SED_GROUP_DEFAULT => 'A',
	SED_GROUP_GUESTS => 'W12345A',
	SED_GROUP_INACTIVE => 'W12345A',
	SED_GROUP_BANNED => 'RWA12345',
	SED_GROUP_MEMBERS => 'A',
	SED_GROUP_MODERATORS => '',
	SED_GROUP_SUPERADMINS => 'RWA12345',
);
$page_rights = array();
$page_lock = array();
foreach ($sed_groups as $k => $v) {
	$gid = $v['id'];
	$page_rights[$gid] = isset($page_default_rights[$gid]) ? $page_default_rights[$gid] : $page_default_rights[SED_GROUP_DEFAULT];
	$page_lock[$gid] = isset($page_default_lock[$gid]) ? $page_default_lock[$gid] : $page_default_lock[SED_GROUP_DEFAULT];
}

// Create auth for every category that exists in structure (not a fixed list)
$sql_cats = sed_sql_query("SELECT structure_code FROM " . $prefix . "structure ORDER BY structure_path ASC");
while ($row_cat = sed_sql_fetchassoc($sql_cats)) {
	$cat = $row_cat['structure_code'];
	$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='page' AND auth_option='" . sed_sql_prep($cat) . "' LIMIT 1");
	if (sed_sql_numrows($chk) == 0) {
		sed_auth_install_option('page', $cat, $page_rights, $page_lock, $setby);
		$res .= "Auth for category '" . sed_cc($cat) . "' created.<br />";
	}
}

/* ======== Stats: totalpages ======== */

$chk = sed_sql_query("SELECT 1 FROM " . $prefix . "stats WHERE stat_name='totalpages' LIMIT 1");
if (sed_sql_numrows($chk) == 0) {
	sed_sql_query("INSERT INTO " . $prefix . "stats (stat_name, stat_value) VALUES ('totalpages', '0')");
	$res .= "Stats totalpages added.<br />";
}

/* ======== Welcome page ======== */

$chk = sed_sql_query("SELECT 1 FROM " . $prefix . "pages LIMIT 1");
if (sed_sql_numrows($chk) == 0) {
	$res .= "Inserting welcome page...<br />";
	sed_sql_query("INSERT INTO " . $prefix . "pages (page_id, page_state, page_cat, page_key, page_title, page_desc, page_text, page_text2, page_author, page_ownerid, page_date, page_begin, page_expire, page_file, page_url, page_size, page_count, page_allowcomments, page_allowratings, page_rating, page_comcount, page_filecount, page_alias, page_seo_title, page_seo_desc, page_seo_keywords, page_seo_h1, page_seo_index, page_seo_follow, page_thumb) VALUES
(1, 0, 'news', '', 'Welcome !', '...', 'Congratulations, your website is up and running !<br />\r\n<br />\r\nThe next step is to go in the <a href=\"/admin/\">Administration panel</a>, tab <a href=\"admin/config\">Configuration</a>, and there tweak the settings for the system.<br />\r\nYou''ll find more instructions and tutorials in the <a href=\"https://seditio.org/doc/\">Documentation page for Seditio at Seditio.org</a>, and technical support in our <a href=\"https://seditio.org/forums/\">discussion forums</a>.', '', '', 1, 1263945600, 1263942000, 1861959600, 0, '', '', 38, 1, 1, 0.00, 0, 0, '', '', '', '', '', 1, 1, '')");
}

$res .= "Page module tables and data installed.<br />";
