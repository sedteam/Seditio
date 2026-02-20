<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.install.php
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

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}rated'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating rated table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}rated (
  rated_id int(11) unsigned NOT NULL auto_increment,
  rated_code varchar(16) DEFAULT NULL,
  rated_userid int(11) DEFAULT NULL,
  rated_value tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (rated_id),
  KEY rated_code (rated_code)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}ratings'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating ratings table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}ratings (
  rating_id int(11) NOT NULL auto_increment,
  rating_code varchar(16) NOT NULL DEFAULT '',
  rating_state tinyint(2) NOT NULL DEFAULT '0',
  rating_average decimal(5,2) NOT NULL DEFAULT '0.00',
  rating_creationdate int(11) NOT NULL DEFAULT '0',
  rating_text varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (rating_id),
  KEY rating_code (rating_code)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$res .= "Ratings plugin tables installed.<br />";
