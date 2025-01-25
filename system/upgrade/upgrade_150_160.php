<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_150_160.php
Version=180
Updated=2025-jan-25
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
    die('Wrong URL.');
}


// ========================================

$adminmain .= "Adding the 'is_html' column to comments...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "com ADD com_text_ishtml TINYINT(1) DEFAULT '0' AFTER com_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'is_html' column to forum posts...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "forum_posts ADD fp_text_ishtml TINYINT(1) DEFAULT '0' AFTER fp_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'is_html' column to pages, ...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_text_ishtml TINYINT(1) DEFAULT '0' AFTER page_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'is_html' column to private messages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pm ADD pm_text_ishtml TINYINT(1) DEFAULT '0' AFTER pm_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'is_html' column to user text...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users ADD user_text_ishtml TINYINT(1) DEFAULT '0' AFTER user_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// ========================================

$adminmain .= "Cleaning the configuration table...<br />";
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parser_vid'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsebbcodeusertext'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsebbcodecom'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsebbcodeforums'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsebbcodepages'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsesmiliesusertext'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsesmiliescom'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsesmiliesforums'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='parser' AND config_name='parsesmiliespages'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='page' AND config_name='allowphp_pages'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='pm' AND config_name='keepoldpms'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Removing the plugin Textboxer 2<br />";
$adminmain .= sed_plugin_uninstall('textboxer2');

// ========================================

$adminmain .= "Clearing the trashcan...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "trash");

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "cache");

$adminmain .= "Changing the SQL version number to 160...<br />";
$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=160 WHERE stat_name='version'");

$upg_status = TRUE;
