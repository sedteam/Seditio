<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.uninstall.php
Version=185
Updated=2026-feb-14
Type=Module.uninstall
Author=Seditio Team
Description=Private messages module uninstall script
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!isset($res)) {
	$res = '';
}

global $db_auth;
if (isset($db_auth)) {
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='pm'");
	$res .= "Deleted pm auth entries: " . sed_sql_affectedrows() . "<br />";
}

/* Table pm is left in place to preserve messages.
   To drop it on uninstall, uncomment the line below:

sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "pm");
*/

$res .= "PM module uninstalled. PM table preserved.<br />";
