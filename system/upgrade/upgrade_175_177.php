<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_175_177.php
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

$adminmain .= "Changing the SQL version number to 177...<br />";

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "core VALUES ('', 'dic', 'Directories', '150', 1, 0);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 1, 'dic', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 2, 'dic', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 3, 'dic', 'a', 0, 255, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 4, 'dic', 'a', 3, 128, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 5, 'dic', 'a', 255, 255, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth VALUES ('', 6, 'dic', 'a', 131, 0, 1);";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "CREATE TABLE " . $cfg['sqldbprefix'] . "dic (
	dic_id mediumint(8) NOT NULL auto_increment,
	dic_title varchar(255) NOT NULL default '',
	dic_code varchar(255) NOT NULL default '',
	dic_type tinyint(1) default '0',
	dic_values text NOT NULL,
	dic_parent mediumint(8) NOT NULL default '0',
	dic_mera varchar(16) NOT NULL default '',
	dic_form_title varchar(255) NOT NULL default '', 
	dic_form_desc varchar(255) NOT NULL default '',
	dic_form_size smallint(5) NOT NULL default '0',
	dic_form_maxsize smallint(5) NOT NULL default '0',
	dic_form_cols smallint(5) NOT NULL default '0',
	dic_form_rows smallint(5) NOT NULL default '0',
	dic_extra_location varchar(40) NOT NULL default '',
	dic_extra_type varchar(20) NOT NULL default '',
	dic_extra_size smallint(5) NOT NULL default '0',
	KEY dic_code (dic_code), 
	KEY dic_parent (dic_parent),
	PRIMARY KEY  (dic_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "CREATE TABLE " . $cfg['sqldbprefix'] . "dic_items (
  ditem_id mediumint(8) NOT NULL auto_increment,
  ditem_dicid mediumint(8) NOT NULL default '0',
  ditem_title varchar(255) NOT NULL default '',
  ditem_code varchar(255) NOT NULL default '',
  ditem_children mediumint(8) NOT NULL DEFAULT '0',
  ditem_defval tinyint(1) default '0',
  KEY ditem_dicid (ditem_dicid), 
  PRIMARY KEY  (ditem_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_price' column to table pages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_price varchar(11) NOT NULL DEFAULT '0' AFTER page_seo_keywords";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_thumb' column to table pages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_thumb varchar(255) NOT NULL DEFAULT '' AFTER page_price";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'user_firstname' column to table users...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users ADD user_firstname varchar(100) NOT NULL DEFAULT '' AFTER user_name";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'user_lastname' column to table users...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users ADD user_lastname varchar(100) NOT NULL DEFAULT '' AFTER user_firstname";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=177 WHERE stat_name='version'");
$upg_status = TRUE;
