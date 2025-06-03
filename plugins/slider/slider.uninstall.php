<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/slider/slider.uninstall.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Amro
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $db_dic, $db_dic_items, $db_pages;

$sql = sed_sql_query("SELECT dic_id FROM $db_dic WHERE dic_code = 'slider' LIMIT 1");
$did = sed_sql_result($sql, 0, 'dic_id');
if ($did > 0) {
	$sql = sed_sql_query("DELETE FROM $db_dic WHERE dic_id = $did LIMIT 1");
	$sql = sed_sql_query("DELETE FROM $db_dic_items WHERE ditem_dicid = $did");
	$sql = sed_sql_query("ALTER TABLE $db_pages DROP page_slider");
}
