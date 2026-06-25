<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/inc/pfs.functions.php
Version=186
Updated=2026-jun-18
Type=Module
Author=Seditio Team
Description=PFS module functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * In-request folder cache storage.
 *
 * @return array
 */
function &sed_pfs_folders_cache()
{
	static $cache = array();
	return $cache;
}

/**
 * Loads all PFS folders for a user into a flat array keyed by pff_id.
 *
 * @param int $userid User ID
 * @return array
 */
function sed_pfs_folders_load($userid)
{
	global $db_pfs_folders;

	$cache = &sed_pfs_folders_cache();
	$uid = (int)$userid;

	if (isset($cache[$uid])) {
		return $cache[$uid];
	}

	$cache[$uid] = array();
	$sql = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_userid='$uid' ORDER BY pff_type DESC, pff_title ASC");

	while ($row = sed_sql_fetchassoc($sql)) {
		$cache[$uid][(int)$row['pff_id']] = $row;
	}

	return $cache[$uid];
}

/**
 * Clears the in-request folder cache for a user (after INSERT/UPDATE/DELETE).
 *
 * @param int $userid User ID
 */
function sed_pfs_folders_cache_clear($userid)
{
	$cache = &sed_pfs_folders_cache();
	unset($cache[(int)$userid]);
}

/**
 * @param array $children
 * @param array $all
 * @param array $exclude_set
 * @param int   $pid
 * @param int   $depth
 * @param array $prefix_continues
 * @return array
 */
function sed_pfs_folders_get_tree_walk($children, $all, $exclude_set, $pid, $depth, $prefix_continues)
{
	$result = array();

	if (!isset($children[$pid])) {
		return $result;
	}

	$filtered = array();

	foreach ($children[$pid] as $nid) {
		if (isset($exclude_set[$nid]) || !isset($all[$nid])) {
			continue;
		}
		$filtered[] = $nid;
	}

	foreach ($filtered as $i => $nid) {
		$is_last = ($i === count($filtered) - 1);
		$item = $all[$nid];
		$item['depth'] = $depth;
		$item['is_last'] = $is_last;
		$item['prefix_continues'] = $prefix_continues;
		$result[] = $item;

		$child_prefix = $prefix_continues;
		$child_prefix[] = !$is_last;
		$sub = sed_pfs_folders_get_tree_walk($children, $all, $exclude_set, $nid, $depth + 1, $child_prefix);
		$result = array_merge($result, $sub);
	}

	return $result;
}

/**
 * Builds a flat ordered folder list with tree metadata for select boxes and UI.
 *
 * @param int   $userid  User ID
 * @param array $exclude Folder IDs to exclude (with all descendants)
 * @return array
 */
function sed_pfs_folders_get_tree($userid, $exclude = array())
{
	$all = sed_pfs_folders_load($userid);

	$children = array();

	foreach ($all as $id => $row) {
		$pid = (int)$row['pff_parentid'];
		$children[$pid][] = $id;
	}

	$exclude_set = array();

	foreach ($exclude as $eid) {
		$exclude_set[(int)$eid] = true;
		foreach (sed_pfs_folders_get_children_ids((int)$eid, $userid, $all) as $cid) {
			$exclude_set[$cid] = true;
		}
	}

	return sed_pfs_folders_get_tree_walk($children, $all, $exclude_set, 0, 0, array());
}

/**
 * Returns the chain of parent folders from root down to (but not including) $folder_id.
 *
 * @param int        $folder_id
 * @param int        $userid
 * @param array|null $all Pre-loaded folders (optional)
 * @return array Ordered from root to direct parent
 */
function sed_pfs_folders_get_parents($folder_id, $userid, $all = null)
{
	if ($all === null) {
		$all = sed_pfs_folders_load($userid);
	}

	$chain = array();
	$current = (int)$folder_id;
	$seen = array();

	while ($current > 0 && isset($all[$current])) {
		if (isset($seen[$current])) {
			break;
		}
		$seen[$current] = true;
		$pid = (int)$all[$current]['pff_parentid'];
		if ($pid > 0 && isset($all[$pid])) {
			array_unshift($chain, $all[$pid]);
		}
		$current = $pid;
	}

	return $chain;
}

/**
 * Returns all descendant folder IDs of the given folder.
 *
 * @param int        $folder_id
 * @param int        $userid
 * @param array|null $all Pre-loaded folders (optional)
 * @return array
 */
function sed_pfs_folders_get_children_ids($folder_id, $userid, $all = null)
{
	if ($all === null) {
		$all = sed_pfs_folders_load($userid);
	}

	$children = array();

	foreach ($all as $id => $row) {
		$pid = (int)$row['pff_parentid'];
		$children[$pid][] = $id;
	}

	$result = array();
	$stack = array((int)$folder_id);

	while (!empty($stack)) {
		$pid = array_pop($stack);
		if (!isset($children[$pid])) {
			continue;
		}
		foreach ($children[$pid] as $cid) {
			$result[] = $cid;
			$stack[] = $cid;
		}
	}

	return $result;
}

/**
 * Validates that $new_parent_id is a legal parent for $folder_id (no self/cycle).
 *
 * @param int $folder_id
 * @param int $new_parent_id
 * @param int $userid
 * @return bool
 */
function sed_pfs_folders_validate_parent($folder_id, $new_parent_id, $userid)
{
	if ($new_parent_id == 0) {
		return true;
	}

	if ($folder_id > 0 && $folder_id == $new_parent_id) {
		return false;
	}

	$all = sed_pfs_folders_load($userid);

	if (!isset($all[(int)$new_parent_id])) {
		return false;
	}

	if ($folder_id > 0) {
		$descendants = sed_pfs_folders_get_children_ids($folder_id, $userid, $all);
		if (in_array($new_parent_id, $descendants)) {
			return false;
		}
	}

	return true;
}

/**
 * Counts direct child folders of a folder.
 *
 * @param int $folder_id Parent folder ID (0 = root)
 * @param int $userid
 * @return int
 */
function sed_pfs_folders_count_children($folder_id, $userid)
{
	global $db_pfs_folders;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pfs_folders WHERE pff_userid='" . (int)$userid . "' AND pff_parentid='" . (int)$folder_id . "'");

	return (int)sed_sql_result($sql, 0, 'COUNT(*)');
}

/**
 * Renders PFS folder selection dropdown (hierarchical).
 *
 * @param int        $user User ID
 * @param int|string $skip Folder ID to exclude with all descendants
 * @param int        $check Selected folder ID
 * @return string
 */
function sed_selectbox_folders($user, $skip, $check)
{
	$extra = '';
	if ($skip != "/" && $skip != "0") {
		$selected = (empty($check) || $check == "/") ? ' selected="selected"' : '';
		$extra = '<option value="0"' . $selected . '>/ &nbsp; &nbsp;</option>';
	}

	$exclude = array();
	if ($skip != "/" && $skip != "0" && $skip != "") {
		$exclude[] = (int)$skip;
	}

	$items = array();
	foreach (sed_pfs_folders_get_tree($user, $exclude) as $item) {
		$items[] = array(
			'value' => $item['pff_id'],
			'title' => $item['pff_title'],
			'depth' => $item['depth'],
			'is_last' => $item['is_last'],
			'prefix_continues' => $item['prefix_continues'],
		);
	}

	return sed_selectbox_tree_html('folderid', $items, $check, $extra);
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

	sed_pfs_folders_cache_clear($userid);

	return $num;
}

/**
 * Resolve PFS filename collision by appending a suffix before the extension.
 *
 * @param string $filename  Sanitized name after sed_newname() (e.g. "5-report.pdf")
 * @param string $dir       PFS directory path (with trailing slash)
 * @param bool   $timestamp When true, use Unix timestamp suffix; otherwise -2, -3, ...
 * @return string           Unique filename for the given directory
 */
function sed_pfs_unique_filename($filename, $dir, $timestamp = false)
{
	$filename = basename(str_replace('\\', '/', $filename));
	$dotpos = mb_strrpos($filename, '.');
	if ($dotpos === false) {
		return $filename;
	}

	$base = mb_substr($filename, 0, $dotpos);
	$ext = mb_substr($filename, $dotpos + 1);

	if (!file_exists($dir . $filename)) {
		return $filename;
	}

	if ($timestamp) {
		$suffix = (string) time();
		$candidate = $base . '-' . $suffix . '.' . $ext;
		while (file_exists($dir . $candidate)) {
			$suffix = (string) ((int) $suffix + 1);
			$candidate = $base . '-' . $suffix . '.' . $ext;
		}
		return $candidate;
	}

	$n = 2;
	while (file_exists($dir . $base . '-' . $n . '.' . $ext)) {
		$n++;
	}
	return $base . '-' . $n . '.' . $ext;
}
