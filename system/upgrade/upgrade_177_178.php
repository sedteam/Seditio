<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_177_178.php
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

$adminmain .= "Adding the 'structure_thumb' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_thumb varchar(255) NOT NULL DEFAULT '' AFTER structure_allowratings";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_seo_title' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_title varchar(255) NOT NULL DEFAULT '' AFTER structure_thumb";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_seo_desc' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_desc varchar(255) NOT NULL DEFAULT '' AFTER structure_seo_title";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_seo_keywords' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_keywords varchar(255) NOT NULL DEFAULT '' AFTER structure_seo_desc";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_seo_h1' column to table structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_h1 varchar(255) NOT NULL DEFAULT '' AFTER structure_seo_keywords";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "core VALUES ('', 'menu', 'Menu manager', '150', 1, 0);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 1, 'menu', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 2, 'menu', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 3, 'menu', 'a', 0, 255, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 4, 'menu', 'a', 3, 128, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 5, 'menu', 'a', 255, 255, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 6, 'menu', 'a', 131, 0, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "CREATE TABLE " . $cfg['sqldbprefix'] . "menu (
  menu_id int(11) unsigned NOT NULL auto_increment,
  menu_pid int(11) NOT NULL DEFAULT '0',
  menu_title varchar(255) NOT NULL DEFAULT '',
  menu_url varchar(255) NOT NULL DEFAULT '',
  menu_position int(11) NOT NULL DEFAULT '0',
  menu_visible tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (menu_id),
  KEY menu_pid (menu_pid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding example menu...<br />";

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(1, 0, 'Menu', '', 1, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(2, 1, 'Home', '/', 2, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(3, 1, 'Forums', '/forums/', 3, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(4, 1, 'Articles', '/articles/', 4, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(5, 1, 'Galleries', '/gallery/', 5, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "menu VALUES(6, 1, 'Contact', '/plug/contact', 6, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the option to set a default country for the new members (Admin > Config > Users)<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default, config_text) VALUES ('core', 'page', '06', 'genseourls', '3', '1', '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_seo_h1' column to table pages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_h1 varchar(255) NOT NULL DEFAULT '' AFTER page_seo_keywords";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 178...<br />";

$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=178 WHERE stat_name='version'");
$upg_status = TRUE;
