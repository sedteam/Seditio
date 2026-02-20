<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.uninstall.php
Version=185
Updated=2026-feb-14
Type=Module.uninstall
Author=Seditio Team
Description=Page module uninstall script
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!isset($res)) {
	$res = '';
}

// Remove all auth (rights) entries for page categories (use global $db_auth from config)
global $db_auth;
if (isset($db_auth)) {
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='page'");
	$res .= "Deleted page auth entries: " . sed_sql_affectedrows() . "<br />";
}

// WARNING: This will drop page tables and all data!
// Uncomment the lines below to enable full cleanup on uninstall.

// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "pages");
// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "structure");

$res .= "Page module uninstalled. Page tables preserved.<br />";
