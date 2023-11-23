<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://www.seditio.org

[BEGIN_SED]
File=plugins/slider/slider.uninstall.php
Version=179
Updated=2020-feb-26
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
