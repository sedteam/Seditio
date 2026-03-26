<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.install.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

/* ======== Table: {prefix}trash (not created by core install; only this plugin) ======== */

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}trash'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating trash table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}trash (
  tr_id int(11) NOT NULL auto_increment,
  tr_date int(11) unsigned NOT NULL DEFAULT '0',
  tr_type varchar(24) NOT NULL DEFAULT '',
  tr_title varchar(128) NOT NULL DEFAULT '',
  tr_itemid varchar(24) NOT NULL DEFAULT '',
  tr_trashedby int(11) NOT NULL DEFAULT '0',
  tr_datas mediumblob,
  PRIMARY KEY (tr_id)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$res .= "Trash can plugin: table ready.<br />";
