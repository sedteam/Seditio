<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/polls/polls.uninstall.php
Version=185
Updated=2026-feb-14
Type=Module.uninstall
Author=Seditio Team
Description=Polls module uninstall script
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
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='polls'");
	$res .= "Deleted polls auth entries: " . sed_sql_affectedrows() . "<br />";
}

/* Tables (polls, polls_options, polls_voters) are left in place to preserve data.
   To drop them on uninstall, uncomment the lines below:

sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "polls_voters");
sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "polls_options");
sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "polls");
*/

$res .= "Polls module uninstalled. Poll tables preserved.<br />";
