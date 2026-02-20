<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.uninstall.php
Version=185
Updated=2026-feb-14
Type=Module.uninstall
Author=Seditio Team
Description=Forums uninstall script
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Remove all auth (rights) entries for forum sections (use global $db_auth from config)
global $db_auth;
if (isset($db_auth)) {
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='forums'");
	$res .= "Deleted forum auth entries: " . sed_sql_affectedrows() . "<br />";
}

// WARNING: This will drop all forum tables and data!
// Uncomment the lines below to enable full cleanup on uninstall.

// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "forum_posts");
// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "forum_topics");
// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "forum_sections");
// sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "forum_structure");

$res .= "Forums module uninstalled. Forum tables preserved.<br />";
