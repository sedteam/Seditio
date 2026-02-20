<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.install.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $db_com, $cfg;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

/* ======== Table: comments ======== */

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}com'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating comments table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}com (
  com_id int(11) NOT NULL auto_increment,
  com_code varchar(16) NOT NULL DEFAULT '',
  com_author varchar(24) NOT NULL DEFAULT '',
  com_authorid int(11) DEFAULT NULL,
  com_authorip varchar(45) NOT NULL DEFAULT '',
  com_text text NOT NULL,
  com_text_ishtml tinyint(1) DEFAULT '1',
  com_date int(11) NOT NULL DEFAULT '0',
  com_count int(11) NOT NULL DEFAULT '0',
  com_rating tinyint(1) DEFAULT '0',
  com_isspecial tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (com_id),
  KEY com_code (com_code)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$res .= "Comments plugin tables installed.<br />";
