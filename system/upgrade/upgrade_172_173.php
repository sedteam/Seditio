<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=upgrade_172_173.php
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

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "cache");

$adminmain .= "Updating parser table<br />";
$sqlqr = "UPDATE " . $cfg['sqldbprefix'] . "parser SET parser_code1 = '<!--readmore-->' WHERE parser_bb1 = '[more]'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

if ($cfg['textmode'] == "html") {
  //Reinstall plugin Jevix, Ckeditor && Removing plugin MarkHTML

  $adminmain .= "Removing the plugin Jevix<br />";
  $adminmain .= sed_plugin_uninstall('jevix');
  $adminmain .= "Removing the plugin Ckeditor<br />";
  $adminmain .= sed_plugin_uninstall('ckeditor');
  $adminmain .= "Removing the plugin MarkHTML<br />";
  $adminmain .= sed_plugin_uninstall('markhtml');
  $adminmain .= "Install the plugin Jevix<br />";
  $adminmain .= sed_plugin_install('jevix');
  $adminmain .= "Install the plugin Ckeditor<br />";
  $adminmain .= sed_plugin_install('ckeditor');
} else {
  //Reinstall plugin Textboxer2

  $adminmain .= "Removing the plugin Textboxer2<br />";
  $adminmain .= sed_plugin_uninstall('textboxer2');
  $adminmain .= "Install the plugin Textboxer2<br />";
  $adminmain .= sed_plugin_install('textboxer2');
}

$adminmain .= "Adding the 'structure_allowcomments' column to structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_allowcomments tinyint(1) NOT NULL DEFAULT '1' AFTER structure_order";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'structure_allowratings' column to structure...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_allowratings tinyint(1) NOT NULL DEFAULT '1' AFTER structure_allowcomments";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_allowcomments' column to pages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_allowcomments tinyint(1) NOT NULL DEFAULT '1' AFTER page_count";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_allowratings' column to pages...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_allowratings tinyint(1) NOT NULL DEFAULT '1' AFTER page_allowcomments";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'maxcommentsperpage' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'comments', '05', 'maxcommentsperpage', 2, '30', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'maxtimeallowcomedit' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'comments', '06', 'maxtimeallowcomedit', 2, '15', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'commentsorder' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'comments', '11', 'commentsorder', 2, 'ASC', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'defskin' new hidden config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'skin', '16', 'defskin', 7, '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the RSS new configs into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '01', 'disable_rss', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '02', 'disable_rsspages', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '03', 'disable_rsscomments', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '04', 'disable_rssforums', 3, '0', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '05', 'rss_timetolive', 2, '300', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '06', 'rss_maxitems', 2, '30', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'rss', '07', 'rss_defaultcode', 2, 'news', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Changing the SQL version number to 173...<br />";
$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=173 WHERE stat_name='version'");

$upg_status = TRUE;
