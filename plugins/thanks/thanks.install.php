<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.install.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg, $db_dic;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];
$db_thanks = $prefix . 'thanks';
$db_users = $prefix . 'users';

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}thanks'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating thanks table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}thanks (
  th_id int(11) NOT NULL AUTO_INCREMENT,
  th_ext varchar(16) NOT NULL DEFAULT '',
  th_item int(11) NOT NULL DEFAULT 0,
  th_fromuser int(11) NOT NULL DEFAULT 0,
  th_touser int(11) NOT NULL DEFAULT 0,
  th_date int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (th_id),
  KEY th_fromuser (th_fromuser),
  KEY th_touser (th_touser),
  KEY th_ext_item (th_ext, th_item)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$col_exists = (sed_sql_numrows(sed_sql_query("SHOW COLUMNS FROM {$db_users} LIKE 'user_thankscount'")) > 0);
$dic_exists = (sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_dic WHERE dic_code = 'thankscount'"), 0, 'COUNT(*)') > 0);

if (!$dic_exists) {
	sed_sql_query("INSERT INTO $db_dic (dic_title, dic_code, dic_type, dic_values, dic_mera, dic_form_title, dic_form_desc, dic_form_size, dic_form_maxsize, dic_form_cols, dic_form_rows, dic_form_wysiwyg, dic_extra_location, dic_extra_type, dic_extra_size, dic_extra_default) VALUES ('Thanks count', 'thankscount', 4, '', '', '', '', 0, 0, 0, 0, 'noeditor', 'users', 'int', 11, '0')");
	$res .= "User extrafield thankscount registered in dictionary.<br />";
}

if (!$col_exists) {
	sed_sql_query("ALTER TABLE {$db_users} ADD user_thankscount INT(11) NOT NULL DEFAULT 0");
	$res .= "Column user_thankscount added.<br />";
	sed_sql_query("UPDATE {$db_users} u SET u.user_thankscount = (SELECT COUNT(*) FROM {$db_thanks} t WHERE t.th_touser = u.user_id)");
	$res .= "Migrated existing thanks counts.<br />";
}

sed_cache_clear('sed_dic');

$res .= "Thanks plugin tables installed.<br />";
