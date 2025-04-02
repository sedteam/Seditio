<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

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

$table_status = sed_sql_table_status($db_pages);

if (version_compare(sed_sql_version(), '5.6', '>=') || $table_status['Engine'] == 'MyISAM') {
	$sql_drop_index = sed_sql_query("ALTER TABLE $db_pages DROP INDEX page_title");
}
