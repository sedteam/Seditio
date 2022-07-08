<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_178_179.php
Version=179
Updated=2022-jul-08
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE ".$cfg['sqldbprefix']."cache");

$adminmain .= "Adding the 'poll_ownerid' column to table polls...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."polls ADD poll_ownerid int(11) NOT NULL DEFAULT '0' AFTER poll_text";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr); 

$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 179...<br />";      

$sql = sed_sql_query("UPDATE ".$cfg['sqldbprefix']."stats SET stat_value=179 WHERE stat_name='version'");
$upg_status = TRUE;

?>
