<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/inc/pfs.functions.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PFS module functions (sed_pfs_deleteall_module)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Delete all PFS files and folders for a user.
 * Called from users.edit.inc.php when deleting a user (if PFS delete option is set).
 *
 * @param int $userid User ID
 * @return int Number of affected DB rows
 */
function sed_pfs_deleteall_module($userid)
{
	global $db_pfs_folders, $db_pfs, $cfg;

	if (!$userid) {
		return 0;
	}

	$num = 0;

	$sql = sed_sql_query("DELETE FROM $db_pfs_folders WHERE pff_userid='" . (int)$userid . "'");
	$num = $num + sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_userid='" . (int)$userid . "'");
	$num = $num + sed_sql_affectedrows();

	$bg = $userid . '-';
	$bgl = mb_strlen($bg);

	if (!empty($cfg['pfs_dir']) && is_dir($cfg['pfs_dir'])) {
		$handle = @opendir($cfg['pfs_dir']);
		if ($handle) {
			while ($f = @readdir($handle)) {
				if ($f !== '.' && $f !== '..' && mb_substr($f, 0, $bgl) === $bg) {
					@unlink($cfg['pfs_dir'] . $f);
				}
			}
			@closedir($handle);
		}
	}

	if (!empty($cfg['th_dir']) && is_dir($cfg['th_dir'])) {
		$handle = @opendir($cfg['th_dir']);
		if ($handle) {
			while ($f = @readdir($handle)) {
				if ($f !== '.' && $f !== '..' && mb_substr($f, 0, $bgl) === $bg) {
					@unlink($cfg['th_dir'] . $f);
				}
			}
			@closedir($handle);
		}
	}

	return $num;
}
