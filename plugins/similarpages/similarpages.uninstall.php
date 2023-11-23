<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/similarpages/similarpages.uninstall.php
Version=170
Updated=2012-feb-26
Type=Plugin
Author=Amro
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
    die('Wrong URL.');
}

global $db_pages;

$sql_drop_index = sed_sql_query("ALTER TABLE $db_pages DROP INDEX page_title");
