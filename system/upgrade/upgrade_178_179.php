<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=upgrade_178_179.php
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

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "cache");

$adminmain .= "Adding the 'poll_ownerid' column to table polls...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "polls ADD poll_ownerid int(11) NOT NULL DEFAULT '0' AFTER poll_text";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'poll_code' column to table polls...<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "polls ADD poll_code varchar(16) NOT NULL default '' AFTER poll_ownerid";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'indextitle' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'meta', '10', 'indextitle', 1, '{MAINTITLE} - {TITLE}', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'hometitle' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'index', '01', 'hometitle', 1, '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'homemetadescription' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'index', '02', 'homemetadescription', 1, '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'homemetakeywords' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'index', '03', 'homemetakeywords', 1, '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 179...<br />";

$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=179 WHERE stat_name='version'");
$upg_status = TRUE;
