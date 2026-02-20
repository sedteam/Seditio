<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=Private messages module install: table
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

/* ======== Table: pm ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "pm'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating pm table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "pm (
  pm_id int(11) unsigned NOT NULL auto_increment,
  pm_state tinyint(2) NOT NULL DEFAULT '0',
  pm_date int(11) NOT NULL DEFAULT '0',
  pm_fromuserid int(11) NOT NULL DEFAULT '0',
  pm_fromuser varchar(24) NOT NULL DEFAULT '0',
  pm_touserid int(11) NOT NULL DEFAULT '0',
  pm_title varchar(64) NOT NULL DEFAULT '0',
  pm_text text NOT NULL,
  PRIMARY KEY (pm_id),
  KEY pm_fromuserid (pm_fromuserid),
  KEY pm_touserid (pm_touserid)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* Права (auth) для модуля pm выставляются из setup (Auth_guests, Auth_members) в Step 8 sed_module_install(). */

$res .= "PM module table installed.<br />";
