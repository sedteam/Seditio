<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/inc/page.functions.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Page structure and list API
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/** 
 * Loads complete category structure into array (page/list categories)
 * 
 * @return array 
 */
function sed_load_page_structure()
{
	global $db_structure, $cfg, $L;

	$res = array();

	$path = array(); // code path tree
	$tpath = array(); // title path tree

	$sql = sed_sql_query("SELECT * FROM $db_structure ORDER BY structure_path ASC");

	$rows = array();
	while ($data_rows = sed_sql_fetchassoc($sql)) {
		$rows[] = $data_rows;
	}

	if ($cfg['structuresort']) {
		usort($rows, 'sed_structure_sort');
	}

	foreach ($rows as $row) {
		if (!empty($row['structure_icon'])) {
			$row['structure_icon_src'] = $row['structure_icon'];
			$row['structure_icon'] = "<img src=\"" . $row['structure_icon'] . "\" alt=\"\" />";
		} else {
			$row['structure_icon_src'] = '';
			$row['structure_icon'] = '';
		}

		$last_dot = mb_strrpos($row['structure_path'], '.');

		$row['structure_tpl'] = (empty($row['structure_tpl'])) ? $row['structure_code'] : $row['structure_tpl'];

		if ($last_dot > 0) {
			$path1 = mb_substr($row['structure_path'], 0, ($last_dot));
			$spath = $path[$path1]; //new sed175
			$path[$row['structure_path']] = $path[$path1] . '.' . $row['structure_code'];
			$tpath[$row['structure_path']] = $tpath[$path1] . ' ' . $cfg['separator_symbol'] . ' ' . $row['structure_title'];
			$parent_dot = mb_strrpos($path[$path1], '.');
			$parent_tpl = ($parent_dot > 0) ? mb_substr($path[$path1], $parent_dot + 1) : $path[$path1];
			$row['structure_tpl'] = ($row['structure_tpl'] == 'same_as_parent') ? $parent_tpl : $row['structure_tpl'];
		} else {
			$path[$row['structure_path']] = $row['structure_code'];
			$tpath[$row['structure_path']] = $row['structure_title'];
			$spath = "";
		}

		$order = explode('.', $row['structure_order']);

		$res[$row['structure_code']] = array(
			'id' => $row['structure_id'],
			'path' => $path[$row['structure_path']],
			'tpath' => $tpath[$row['structure_path']],
			'spath' => $spath,
			'rpath' => $row['structure_path'],
			'tpl' => $row['structure_tpl'],
			'title' => $row['structure_title'],
			'desc' => $row['structure_desc'],
			'icon' => $row['structure_icon'],
			'iconsrc' => $row['structure_icon_src'],
			'thumb' => $row['structure_thumb'],
			'seo_h1' => $row['structure_seo_h1'],
			'seo_title' => $row['structure_seo_title'],
			'seo_desc' => $row['structure_seo_desc'],
			'seo_keywords' => $row['structure_seo_keywords'],
			'seo_index' => $row['structure_seo_index'],
			'seo_follow' => $row['structure_seo_follow'],
			'group' => $row['structure_group'],
			'allowcomments' => $row['structure_allowcomments'],
			'allowratings' => $row['structure_allowratings'],
			'order' => $order[0],
			'way' => $order[1]
		);
	}

	return ($res);
}

/**
 * Function for natural sorting by splitting a specified field into parts.
 *
 * @param array $a The first row from the database.
 * @param array $b The second row from the database.
 * @param string $field The field to compare. Default is 'structure_path'.
 * @return int
 */
function sed_structure_sort($a, $b, $field = 'structure_path')
{
	$a_parts = explode('.', $a[$field]);
	$b_parts = explode('.', $b[$field]);

	for ($i = 0; $i < max(count($a_parts), count($b_parts)); $i++) {
		$a_value = isset($a_parts[$i]) ? $a_parts[$i] : '';
		$b_value = isset($b_parts[$i]) ? $b_parts[$i] : '';

		if ($a_value != $b_value) {
			return strnatcmp($a_value, $b_value);
		}
	}

	return 0;
}

/** 
 * List breadcrumbs build path arr
 *
 * @param string $cat Category code  
 */
function sed_build_list_bc($cat)
{
	global $sed_cat, $cfg, $urlpaths;

	$pathcodes = explode('.', $sed_cat[$cat]['path']);
	foreach ($pathcodes as $k => $x) {
		if ($x != 'system') {
			$urlpaths[sed_url("page", "c=" . $x)] = $sed_cat[$x]['title'];
		}
	}
}

/**
 * Get page path for URL translation
 */
function sed_get_pagepath(&$args, &$section)
{
	global $sys, $sed_cat;
	$url = "";
	if (isset($sys['catcode']) && $sys['catcode'] != "system") {
		$cpath = $sed_cat[$sys['catcode']]['path'];
		$cpath_arr = explode('.', $cpath);
		foreach ($cpath_arr as $a) {
			$url .= urlencode($a) . "/";
		}
	}
	return $url;
}

/**
 * Get list path for URL translation
 */
function sed_get_listpath(&$args, &$section)
{
	global $sed_cat;
	$url = '';
	$cpath = $sed_cat[$args['c']]['path'];
	$cpath_arr = explode('.', $cpath);
	foreach ($cpath_arr as $a) {
		$url .= urlencode($a) . "/";
	}
	unset($args['c']);
	return $url;
}

/** 
 * Removes a category 
 * 
 * @param int $id Category ID
 * @param string $c Category code
 */
function sed_structure_delcat($id, $c)
{
	global $db_structure, $db_auth;

	$sql = sed_sql_query("DELETE FROM $db_structure WHERE structure_id='$id'");
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='page' AND auth_option='$c'");
	sed_auth_clear('all');
	sed_cache_clear('sed_cat');
}

/** 
 * Add a new category 
 * 
 * @param string $code Category code 
 * @param string $path Category path
 * @param string $title Category title 
 * @param string $desc Category description
 * @param string $icon Category icon src path
 * @param int $group Category group flag
 * @return bool      
 */
function sed_structure_newcat($code, $path, $title, $desc, $icon, $group)
{
	global $db_structure, $db_auth, $sed_groups, $usr;

	$res = FALSE;

	if (!empty($title) && !empty($code) && !empty($path) && $code != 'all') {
		$code = sed_replacespace($code);  //New in175

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code='$code' LIMIT 1");
		if (sed_sql_numrows($sql) == 0) {
			$sql = sed_sql_query("INSERT INTO $db_structure (structure_code, structure_path, structure_title, structure_desc, structure_icon, structure_group, structure_order) 
					VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($path) . "', '" . sed_sql_prep($title) . "', '" . sed_sql_prep($desc) . "', '" . sed_sql_prep($icon) . "', " . (int)$group . ", 'date.desc')");

			foreach ($sed_groups as $k => $v) {
				if ($v['id'] == 1 || $v['id'] == 2) {
					$ins_auth = 1;
					$ins_lock = 254;
				} elseif ($v['id'] == 3) {
					$ins_auth = 0;
					$ins_lock = 255;
				} elseif ($v['id'] == 5) {
					$ins_auth = 255;
					$ins_lock = 255;
				} else {
					$ins_auth = 3;
					$ins_lock = ($k == 4) ? 128 : 0;
				}
				$sql = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$v['id'] . ", 'page', '$code', " . (int)$ins_auth . ", " . (int)$ins_lock . ", " . (int)$usr['id'] . ")");
				$res = TRUE;
			}
			sed_auth_reorder();
			sed_auth_clear('all');
			sed_cache_clear('sed_cat');
		}
	}
	return ($res);
}

/* ======== Load page structure into global $sed_cat when module is active ======== */

if ((!isset($sed_cat) || !$sed_cat) && sed_module_active('page')) {
	$sed_cat = sed_load_page_structure();
	sed_cache_store('sed_cat', $sed_cat, 3600);
}
