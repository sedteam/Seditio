<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/upgrade/upgrade_185_186.php
Version=186
Updated=2026-jun-18
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade: PFS nested folders (pff_parentid)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$adminmain .= "Checking pfs_folders table...<br />";
$chk_pff = @sed_sql_query("SHOW TABLES LIKE '$db_pfs_folders'");
if ($chk_pff && sed_sql_numrows($chk_pff) > 0) {
	$chk_col = @sed_sql_query("SHOW COLUMNS FROM $db_pfs_folders LIKE 'pff_parentid'");
	if (!$chk_col || sed_sql_numrows($chk_col) == 0) {
		@sed_sql_query("ALTER TABLE $db_pfs_folders ADD COLUMN pff_parentid int(11) NOT NULL DEFAULT '0' AFTER pff_userid");
		@sed_sql_query("ALTER TABLE $db_pfs_folders ADD KEY pff_userid_parent (pff_userid, pff_parentid)");
		$adminmain .= "pfs_folders: pff_parentid column added.<br />";
	}
}

$adminmain .= "-----------------------<br />";
$adminmain .= "Changing the SQL version number to 186...<br />";
sed_sql_query("UPDATE $db_stats SET stat_value='186' WHERE stat_name='version'");
$upg_status = TRUE;
