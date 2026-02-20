<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/pfs.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=PFS module install: tables and auth
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

/* ======== Table: pfs ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "pfs'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating pfs table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}pfs (
  pfs_id int(11) NOT NULL auto_increment,
  pfs_userid int(11) NOT NULL DEFAULT '0',
  pfs_date int(11) NOT NULL DEFAULT '0',
  pfs_file varchar(255) NOT NULL DEFAULT '',
  pfs_extension varchar(8) NOT NULL DEFAULT '',
  pfs_folderid int(11) NOT NULL DEFAULT '0',
  pfs_title varchar(255) NOT NULL DEFAULT '',
  pfs_desc text NOT NULL,
  pfs_size int(11) NOT NULL DEFAULT '0',
  pfs_count int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (pfs_id),
  KEY pfs_userid (pfs_userid)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Table: pfs_folders ======== */

$check = sed_sql_query("SHOW TABLES LIKE '" . $prefix . "pfs_folders'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating pfs_folders table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}pfs_folders (
  pff_id int(11) NOT NULL auto_increment,
  pff_userid int(11) NOT NULL DEFAULT '0',
  pff_date int(11) NOT NULL DEFAULT '0',
  pff_updated int(11) NOT NULL DEFAULT '0',
  pff_title varchar(255) NOT NULL DEFAULT '',
  pff_desc text NOT NULL,
  pff_type tinyint(1) NOT NULL DEFAULT '0',
  pff_sample int(11) NOT NULL DEFAULT '0',
  pff_count int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (pff_id),
  KEY pff_userid (pff_userid)
) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

/* ======== Auth for pfs (same pattern as page/forums: sed_auth_install_option + group constants) ======== */

global $sed_groups, $db_auth;
$db_auth = $prefix . 'auth';
$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='pfs' AND auth_option='a' LIMIT 1");
if (sed_sql_numrows($chk) == 0) {
	$res .= "Creating PFS auth entries...<br />";
	$setby = (isset($usr['id']) && $usr['id'] > 0) ? (int)$usr['id'] : 1;
	$pfs_default_rights = array(
		SED_GROUP_GUESTS => '',
		SED_GROUP_INACTIVE => '',
		SED_GROUP_BANNED => '',
		SED_GROUP_MEMBERS => 'RW',
		SED_GROUP_SUPERADMINS => 'RWA12345',
		SED_GROUP_MODERATORS => 'RWA',
	);
	$pfs_default_lock = array(
		SED_GROUP_GUESTS => 'RWA12345',
		SED_GROUP_INACTIVE => 'RWA12345',
		SED_GROUP_BANNED => 'RWA12345',
		SED_GROUP_MEMBERS => 'A',
		SED_GROUP_SUPERADMINS => 'RWA12345',
		SED_GROUP_MODERATORS => '',
	);
	$pfs_rights = array();
	$pfs_lock = array();
	foreach ($sed_groups as $k => $v) {
		$gid = $v['id'];
		$pfs_rights[$gid] = isset($pfs_default_rights[$gid]) ? $pfs_default_rights[$gid] : '';
		$pfs_lock[$gid] = isset($pfs_default_lock[$gid]) ? $pfs_default_lock[$gid] : 'RWA12345';
	}
	sed_auth_install_option('pfs', 'a', $pfs_rights, $pfs_lock, $setby);
}

$res .= "PFS module tables and auth installed.<br />";
