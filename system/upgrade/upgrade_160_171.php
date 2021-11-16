<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=upgrade_160_171.php
Version=178
Updated=2021-jun-17
Type=Core.upgrade
Author=Neocrome & Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }


// ========================================

$adminmain .= "Cleaning the 2 columns for journals from the table of users...<br />";

$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages DROP `user_jrnpagescount`";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr, FALSE);

$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages DROP `user_jrnupdated`";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr, FALSE);

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE ".$cfg['sqldbprefix']."cache");

$adminmain .= "Changing the SQL version number to 171...<br />";
$sql = sed_sql_query("UPDATE ".$cfg['sqldbprefix']."stats SET stat_value=171 WHERE stat_name='version'");

$upg_status = TRUE;

?>