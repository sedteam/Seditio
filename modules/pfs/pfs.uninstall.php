<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/pfs.uninstall.php
Version=185
Updated=2026-feb-14
Type=Module.uninstall
Author=Seditio Team
Description=PFS uninstall (destructive actions are commented out)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!isset($res)) {
	$res = '';
}

// Remove all auth (rights) entries for PFS
global $db_auth;
if (isset($db_auth)) {
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='pfs'");
	$res .= "Deleted PFS auth entries: " . sed_sql_affectedrows() . "<br />";
}

if (!empty($sed_uninstall_drop_tables)) {
	global $cfg;
	$prefix = $cfg['sqldbprefix'];
	$db_pfs = $prefix . 'pfs';
	$db_pfs_folders = $prefix . 'pfs_folders';

	sed_sql_query("DROP TABLE IF EXISTS " . $db_pfs);
	sed_sql_query("DROP TABLE IF EXISTS " . $db_pfs_folders);

	$users_dir = SED_ROOT . '/datas/users/';
	if (is_dir($users_dir)) {
		$handle = @opendir($users_dir);
		if ($handle) {
			while ($f = @readdir($handle)) {
				if ($f !== '.' && $f !== '..' && preg_match('/^[0-9]+-/', $f)) {
					@unlink($users_dir . $f);
				}
			}
			@closedir($handle);
		}
	}

	$resized_dir = SED_ROOT . '/datas/resized/';
	if (is_dir($resized_dir)) {
		$handle = @opendir($resized_dir);
		if ($handle) {
			while ($f = @readdir($handle)) {
				if ($f !== '.' && $f !== '..') {
					@unlink($resized_dir . $f);
				}
			}
			@closedir($handle);
		}
	}

	$thumbs_dir = SED_ROOT . '/datas/thumbs/';
	if (is_dir($thumbs_dir)) {
		$handle = @opendir($thumbs_dir);
		if ($handle) {
			while ($f = @readdir($handle)) {
				if ($f !== '.' && $f !== '..') {
					@unlink($thumbs_dir . $f);
				}
			}
			@closedir($handle);
		}
	}

	$res .= "PFS tables dropped and file directories cleaned (users, resized, thumbs).<br />";
} else {
	$res .= "PFS uninstall completed. Tables and file cleanup preserved.<br />";
}
