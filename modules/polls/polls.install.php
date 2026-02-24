<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/polls/polls.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=Polls module install: tables
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

/* ======== Table: polls ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "polls'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating polls table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "polls (
  poll_id mediumint(8) NOT NULL auto_increment,
  poll_type tinyint(1) DEFAULT '0',
  poll_state tinyint(1) NOT NULL DEFAULT '0',
  poll_creationdate int(11) NOT NULL DEFAULT '0',
  poll_text varchar(255) NOT NULL DEFAULT '',
  poll_ownerid int(11) NOT NULL DEFAULT '0',
  poll_code varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (poll_id),
  KEY poll_creationdate (poll_creationdate)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Table: polls_options ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "polls_options'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating polls_options table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "polls_options (
  po_id mediumint(8) unsigned NOT NULL auto_increment,
  po_pollid mediumint(8) unsigned NOT NULL DEFAULT '0',
  po_text varchar(128) NOT NULL DEFAULT '',
  po_count mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (po_id),
  KEY po_pollid (po_pollid)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Table: polls_voters ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "polls_voters'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating polls_voters table...<br />";
	sed_sql_query("CREATE TABLE " . $prefix . "polls_voters (
  pv_id mediumint(8) unsigned NOT NULL auto_increment,
  pv_pollid mediumint(8) NOT NULL DEFAULT '0',
  pv_userid mediumint(8) NOT NULL DEFAULT '0',
  pv_userip varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (pv_id),
  KEY pv_pollid (pv_pollid)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Demo poll ======== */

$chk = sed_sql_query("SELECT 1 FROM " . $prefix . "polls LIMIT 1");
if (sed_sql_numrows($chk) == 0) {
	$res .= "Inserting demo poll...<br />";
	$demo_date = time();
	sed_sql_query("INSERT INTO " . $prefix . "polls (poll_id, poll_type, poll_state, poll_creationdate, poll_text, poll_ownerid, poll_code) VALUES (1, 0, 0, " . (int)$demo_date . ", 'Looking forward to a new version of Seditio?', 1, '')");
	sed_sql_query("INSERT INTO " . $prefix . "polls_options (po_id, po_pollid, po_text, po_count) VALUES (1, 1, 'Yes', 0)");
	sed_sql_query("INSERT INTO " . $prefix . "polls_options (po_id, po_pollid, po_text, po_count) VALUES (2, 1, 'No', 0)");
}

$res .= "Polls module tables and data installed.<br />";
