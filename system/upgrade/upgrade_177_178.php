<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=upgrade_177_178.php
Version=178
Updated=2013-jun-25
Type=Core.upgrade
Author=Neocrome & Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE ".$cfg['sqldbprefix']."cache");

$adminmain .= "Changing the SQL version number to 178...<br />";  

$adminmain .= "Adding the 'structure_thumb' column to table structure...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."structure ADD structure_thumb varchar(255) NOT NULL DEFAULT '' AFTER structure_allowratings";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);   

$sql = sed_sql_query("UPDATE ".$cfg['sqldbprefix']."stats SET stat_value=178 WHERE stat_name='version'");
$upg_status = TRUE;

?>
