<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=upgrade_179_180.php
Version=180
Updated=2023-dec-27
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE " . $cfg['sqldbprefix'] . "cache");

$adminmain .= "Adding auth right for log<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'log', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'log', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'log', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding auth right for trashcan<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'trash', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'trash', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'trash', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding auth right for manage<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (1, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (2, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (3, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (4, 'manage', 'a', 0, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (5, 'manage', 'a', 255, 255, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (6, 'manage', 'a', 0, 0, 1)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

// IPv6 support
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "banlist MODIFY banlist_ip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "com MODIFY com_authorip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "forum_posts MODIFY fp_posterip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "logger MODIFY log_ip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "online MODIFY online_ip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "polls_voters MODIFY pv_userip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "users MODIFY user_lastip VARCHAR(45)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "CREATE UNIQUE INDEX unique_config_owner_cat_name ON " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_name)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "CREATE INDEX idx_config_cat_name ON " . $cfg['sqldbprefix'] . "config (config_cat, config_name)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "config ADD config_id INT(8) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (config_id)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "referers DROP PRIMARY KEY";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "referers ADD ref_id INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (ref_id)";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

foreach ($sed_dbnames as $table_name) {
	$table_name = $cfg['sqldbprefix'] . $table_name;
	$sqlqr = "ALTER TABLE " . $table_name . " CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
	$adminmain .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
}

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "auth DROP KEY auth_code, ADD KEY auth_code (auth_code(190))";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "dic DROP KEY dic_code, ADD KEY dic_code (dic_code(190))";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages DROP KEY page_cat, ADD KEY page_cat (page_cat(190))";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr, false);

$adminmain .= "Adding the 'available_image_sizes' new config into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'pfs', '03', 'available_image_sizes', 1, '', '')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

foreach ($sed_dbnames as $table_name) {
	$table_name = $cfg['sqldbprefix'] . $table_name;
	$sqlqr = "ALTER TABLE " . $table_name . " ENGINE=InnoDB";
	$adminmain .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
}

$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='pfs' AND config_name='th_border'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='pfs' AND config_name='th_colorbg'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='pfs' AND config_name='th_colortext'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "DELETE FROM " . $cfg['sqldbprefix'] . "config WHERE config_cat='pfs' AND config_name='th_textsize'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_index tinyint(1) unsigned NOT NULL default '1' AFTER page_seo_h1";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pages ADD page_seo_follow tinyint(1) unsigned NOT NULL default '1' AFTER page_seo_index";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_index tinyint(1) unsigned NOT NULL default '1' AFTER structure_seo_h1";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "structure ADD structure_seo_follow tinyint(1) unsigned NOT NULL default '1' AFTER structure_seo_index";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 180...<br />";

$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=180 WHERE stat_name='version'");
$upg_status = TRUE;
