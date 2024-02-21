<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_179_180.php
Version=180
Updated=2023-dec-27
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

$adminmain .= "Adding auth right for log<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'log', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'log', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding auth right for trashcan<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'trash', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'trash', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding auth right for manage<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'manage', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'manage', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 180...<br />";

$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=180 WHERE stat_name='version'");
$upg_status = TRUE;
