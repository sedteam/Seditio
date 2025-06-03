<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=upgrade_173_175.php
Version=180
Updated=2015-jan-25
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
  die('Wrong URL.');
}

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "cache");

//Reinstall plugin CKeditor

if ($cfg['textmode'] == "html") {
  $adminmain .= "Rinstall the plugin Ckeditor<br />";
  $adminmain .= sed_plugin_uninstall('ckeditor');
  $adminmain .= sed_plugin_install('ckeditor');
}

// User group color

$adminmain .= "Adding the 'grp_color' column to table users groups...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "groups ADD grp_color varchar(24) NOT NULL DEFAULT 'inherit' AFTER grp_icon";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'color_group' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'users', '10', 'color_group', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Maintenance Mode

$adminmain .= "Adding the 'maintenance' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '21', 'maintenance', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'maintenancelevel' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '22', 'maintenancelevel', 2, '95', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'maintenancereason' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '23', 'maintenancereason', 1, 'The site is in maintenance mode!', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Reinstall BB parser table

$adminmain .= "Reinstall table " . $cfg['sqldbprefix'] . "parser<br />";
$sqlqr = "TRUNCATE TABLE " . $cfg['sqldbprefix'] . "parser";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$lines = file("system/install/install.parser.sql");
foreach ($lines as $line) {
  $line = str_replace('sed_', $cfg['sqldbprefix'], $line);      //???
  sed_sql_query($line);
}

// SEF URL's

$adminmain .= "Adding the 'absurls' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '04', 'absurls', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'multihost' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '03', 'multihost', 3, '1', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'sefurls' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '04', 'sefurls', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'sefurls301' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '04', 'sefurls301', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Changing the length of columns in tables

$adminmain .= "Changing the length of columns in tables<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure MODIFY structure_title VARCHAR(100)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure MODIFY structure_code VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure MODIFY structure_path VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages MODIFY page_alias VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages MODIFY page_cat VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "auth MODIFY auth_code VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "auth MODIFY auth_option VARCHAR(255)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// PFS Upgrade

$adminmain .= "Adding the 'pfs_title' column to table users groups...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs ADD pfs_title varchar(255) NOT NULL DEFAULT '' AFTER pfs_folderid";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'pfs_desc_ishtml' column to table users groups...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs ADD pfs_desc_ishtml tinyint(1) DEFAULT '1' AFTER pfs_desc";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'pff_desc_ishtml' column to table users groups...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs_folders ADD pff_desc_ishtml tinyint(1) DEFAULT '1' AFTER pff_desc";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs_folders MODIFY pff_desc TEXT";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "SELECT pfs_id, pfs_desc FROM " . $cfg['sqldbprefix'] . "pfs";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

while ($pfs = sed_sql_fetchassoc($sql)) {
  $pfs_title = strip_tags(mb_substr($pfs['pfs_desc'], 0, 255));
  $sql_update = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "pfs SET pfs_title = '" . sed_sql_prep($pfs_title) . "' WHERE pfs_id =" . $pfs['pfs_id']);
}

// Structure Upgrade

$adminmain .= "Adding the 'structure_text' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_text TEXT AFTER structure_desc";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_text_ishtml' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_text_ishtml tinyint(1) DEFAULT '1' AFTER structure_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Users upgrade

$adminmain .= "Delete 'user_msn' and add 'user_skype' column to table users<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users ADD user_skype varchar(64) AFTER user_msn";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users DROP user_msn";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Comments upgrade

$adminmain .= "Adding the 'maxcommentlenght' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'comments', '07', 'maxcommentlenght', 1, '2000', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Pages SEO upgrade

$adminmain .= "Add 'page_seo_title', 'page_seo_desc' and 'page_seo_keywords' columns to table pages<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_title varchar(255) AFTER page_alias";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_desc varchar(255) AFTER page_seo_title";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_keywords varchar(255) AFTER page_seo_desc";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Meta title, new configs

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '01', 'defaulttitle', 1, '{MAINTITLE} - {SUBTITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '02', 'listtitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '03', 'pagetitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '04', 'forumstitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '05', 'userstitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '06', 'pmtitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '07', 'gallerytitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '08', 'pfstitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '09', 'plugtitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// AJAX config

$adminmain .= "Adding the 'ajax' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '13', 'ajax', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Modal windows config

$adminmain .= "Adding the 'enablemodal' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'main', '14', 'enablemodal', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// Thumbnail rel atribute

$adminmain .= "Adding the 'th_rel' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'pfs', '10', 'th_rel', 2, 'sedthumb', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Changing the SQL version number to 175...<br />";
$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=175 WHERE stat_name='version'");

$upg_status = TRUE;
