<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/inc/page.functions.php
Version=186
Updated=2026-jun-23
Type=Module
Author=Seditio Team
Description=Page structure, list API, list filters
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
 * Flat page category list with depth metadata for tree selects and admin UI.
 *
 * @return array
 */
function sed_page_categories_list_with_level()
{
	global $sed_cat, $cfg;

	$rows = array();
	foreach ($sed_cat as $code => $x) {
		if ($code === 'all') {
			continue;
		}
		$rows[] = array(
			'structure_code' => $code,
			'structure_path' => $x['rpath'],
			'structure_title' => $x['title'],
		);
	}

	if ($cfg['structuresort']) {
		usort($rows, 'sed_structure_sort');
	}

	return sed_tree_flat_from_dotpath($rows, 'structure_path');
}

/**
 * Renders page category dropdown with tree prefix.
 *
 * @param string $check Selected value
 * @param string $name Dropdown name
 * @param bool   $hideprivate Hide private categories
 * @param string $redirecturl Redirect URL
 * @param string $additional Selectbox additional
 * @return string
 */
function sed_selectbox_categories($check, $name, $hideprivate = TRUE, $redirecturl = "", $additional = "")
{
	$onchange = (!empty($redirecturl)) ? " onchange=\"sedjs.redirect(this)\"" : "";
	$items = array();

	foreach (sed_page_categories_list_with_level() as $item) {
		$code = $item['structure_code'];
		$display = ($hideprivate) ? sed_auth('page', $code, 'W') : TRUE;
		if (sed_auth('page', $code, 'R') && $display) {
			$items[] = array(
				'value' => $redirecturl . $code,
				'title' => $item['structure_title'],
				'depth' => $item['depth'],
				'is_last' => $item['is_last'],
				'prefix_continues' => $item['prefix_continues'],
			);
		}
	}

	return sed_selectbox_tree_html($name, $items, $check, $additional, $onchange);
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
	sed_page_clear_menu_cache();
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
	global $db_structure, $sed_groups, $usr, $sed_default_auth_rights, $sed_default_auth_lock;

	$res = FALSE;

	if (!empty($title) && !empty($code) && !empty($path) && $code != 'all') {
		$code = sed_replacespace($code);  //New in175

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code='$code' LIMIT 1");
		if (sed_sql_numrows($sql) == 0) {
			$sql = sed_sql_query("INSERT INTO $db_structure (structure_code, structure_path, structure_title, structure_desc, structure_icon, structure_group, structure_order) 
					VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($path) . "', '" . sed_sql_prep($title) . "', '" . sed_sql_prep($desc) . "', '" . sed_sql_prep($icon) . "', " . (int)$group . ", 'date.desc')");

			$page_default_rights = $sed_default_auth_rights;
			$page_default_lock = $sed_default_auth_lock;
			$page_rights = array();
			$page_lock = array();
			foreach ($sed_groups as $k => $v) {
				$gid = $v['id'];
				$page_rights[$gid] = isset($page_default_rights[$gid]) ? $page_default_rights[$gid] : $page_default_rights[SED_GROUP_DEFAULT];
				$page_lock[$gid] = isset($page_default_lock[$gid]) ? $page_default_lock[$gid] : $page_default_lock[SED_GROUP_DEFAULT];
			}
			sed_auth_install_option('page', $code, $page_rights, $page_lock, $usr['id']);
			$res = TRUE;
			sed_auth_reorder();
			sed_auth_clear('all');
			sed_cache_clear('sed_cat');
	sed_page_clear_menu_cache();
		}
	}
	return ($res);
}

/**
 * Build SQL WHERE fragment and URL query string for page list filters (GET filter_*).
 *
 * @param array  $sort_filterable Column key (without table prefix) => vartype: INT, BOL, TXT, ARR
 * @param array|null $get         Input parameters (null = use $_GET)
 * @param string $column_prefix   SQL column prefix for $db_pages (default page_)
 * @return array  Keys: urlparams (leading "&" or ""), sql_where (" AND ..." or single space)
 */
function sed_page_list_build_filters($sort_filterable, $get = null, $column_prefix = 'page_')
{
	$src = ($get === null) ? $_GET : $get;
	$filter_sql = array();
	$filter_urlparams_arr = array();
	$col = $column_prefix;

	foreach ($sort_filterable as $key => $vartype) {
		if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
			continue;
		}
		if (!in_array($vartype, array('INT', 'BOL', 'TXT', 'ARR'))) {
			continue;
		}

		$param = 'filter_' . $key;
		if (!isset($src[$param])) {
			continue;
		}

		$raw = $src[$param];
		$fv = sed_import($raw, 'D', 'ARR');
		if (!is_array($fv)) {
			$fv = sed_import($raw, 'D', $vartype, 255);
		}

		if (is_array($fv)) {
			if (count($fv) > 50) {
				continue;
			}
			$escaped_values = array();
			$find_parts = array();
			foreach ($fv as $value) {
				if ($vartype === 'ARR') {
					$filtered_value = sed_import($value, 'D', 'TXT', 255);
					if ($filtered_value === null || $filtered_value === '') {
						continue;
					}
					$find_parts[] = "FIND_IN_SET('" . sed_sql_prep($filtered_value) . "', " . $col . $key . ")";
				} else {
					$filtered_value = sed_import($value, 'D', $vartype, 255);
					if ($vartype === 'INT') {
						if ($filtered_value === null) {
							continue;
						}
						$escaped_values[] = sed_sql_prep((string)(int) $filtered_value);
					} elseif ($vartype === 'BOL') {
						if ($filtered_value === null || !is_bool($filtered_value)) {
							continue;
						}
						$escaped_values[] = $filtered_value ? '1' : '0';
					} else {
						if ($filtered_value === null || $filtered_value === '') {
							continue;
						}
						$escaped_values[] = sed_sql_prep($filtered_value);
					}
				}
			}
			if ($vartype === 'ARR') {
				if (count($find_parts) > 0) {
					$filter_sql[] = '(' . implode(' OR ', $find_parts) . ')';
					foreach ($fv as $value) {
						$filter_urlparams_arr[] = $param . '[]=' . urlencode($value);
					}
				}
			} elseif (count($escaped_values) > 0) {
				$filter_sql[] = $col . $key . " IN ('" . implode("','", $escaped_values) . "')";
				foreach ($fv as $value) {
					$filter_urlparams_arr[] = $param . '[]=' . urlencode($value);
				}
			}
		} else {
			if ($vartype === 'ARR') {
				if ($fv === null || $fv === '') {
					continue;
				}
				$filter_sql[] = "FIND_IN_SET('" . sed_sql_prep($fv) . "', " . $col . $key . ")";
				$filter_urlparams_arr[] = $param . '=' . urlencode($fv);
			} elseif ($vartype === 'TXT') {
				if ($fv === null || $fv === '') {
					continue;
				}
				$filter_sql[] = $col . $key . " LIKE '%" . sed_sql_prep($fv) . "%'";
				$filter_urlparams_arr[] = $param . '=' . urlencode($fv);
			} elseif ($vartype === 'INT') {
				if ($fv === null || $fv === '') {
					continue;
				}
				$filter_sql[] = $col . $key . " = '" . sed_sql_prep((string)(int) $fv) . "'";
				$filter_urlparams_arr[] = $param . '=' . urlencode((string)(int) $fv);
			} elseif ($vartype === 'BOL') {
				if ($fv === null || !is_bool($fv)) {
					continue;
				}
				$bval = $fv ? '1' : '0';
				$filter_sql[] = $col . $key . " = '" . $bval . "'";
				$filter_urlparams_arr[] = $param . '=' . $bval;
			}
		}
	}

	$filter_urlparams = (count($filter_urlparams_arr) > 0) ? "&" . implode('&', $filter_urlparams_arr) : "";
	$sql_where = (count($filter_sql) > 0) ? " AND " . implode(' AND ', $filter_sql) : " ";

	return array(
		'urlparams' => $filter_urlparams,
		'sql_where' => $sql_where,
	);
}

/**
 * Direct child categories of a page category (readable by current user).
 *
 * @param string $parent_code Parent category code; empty = root categories
 * @return array code => category row from $sed_cat
 */
function sed_page_category_children($parent_code = '')
{
	global $sed_cat, $cfg;

	$children = array();

	if ($parent_code === '' || $parent_code === 'all') {
		foreach ($sed_cat as $code => $cat) {
			if ($code === 'all' || $code === 'system') {
				continue;
			}
			if (!empty($cat['spath'])) {
				continue;
			}
			if (sed_auth('page', $code, 'R')) {
				$children[$code] = $cat;
			}
		}
	} else {
		if (!isset($sed_cat[$parent_code])) {
			return array();
		}

		$mtch = $sed_cat[$parent_code]['path'] . '.';
		$mtchlen = mb_strlen($mtch);
		$mtchlvl = mb_substr_count($mtch, '.');

		foreach ($sed_cat as $code => $cat) {
			if ($code === 'all' || $code === 'system') {
				continue;
			}
			if (mb_substr($cat['path'], 0, $mtchlen) == $mtch
				&& mb_substr_count($cat['path'], '.') == $mtchlvl
				&& sed_auth('page', $code, 'R')) {
				$children[$code] = $cat;
			}
		}
	}

	if (!empty($cfg['structuresort']) && count($children) > 1) {
		uasort($children, function ($a, $b) {
			return sed_structure_sort(
				array('structure_path' => $a['rpath']),
				array('structure_path' => $b['rpath'])
			);
		});
	}

	return $children;
}

/**
 * Published pages of a category (same rules as page.list.php).
 *
 * @param string $cat_code
 * @return array
 */
function sed_page_category_pages($cat_code)
{
	global $db_pages, $sed_cat;

	if (!isset($sed_cat[$cat_code])) {
		return array();
	}

	$order = $sed_cat[$cat_code]['order'];
	$way = $sed_cat[$cat_code]['way'];

	if (empty($order)) {
		$order = 'title';
	}
	if ($way != 'asc' && $way != 'desc') {
		$way = 'asc';
	}

	$allowed_orders = array(
		'id', 'key', 'title', 'desc', 'author', 'ownerid',
		'date', 'begin', 'expire', 'count', 'filecount'
	);
	if (!in_array($order, $allowed_orders)) {
		$order = 'title';
	}

	$sql = sed_sql_query("SELECT page_id, page_title, page_alias, page_cat
		FROM $db_pages
		WHERE page_cat='" . sed_sql_prep($cat_code) . "'
		AND (page_state='0' OR page_state='2')
		ORDER BY page_" . $order . " " . $way);

	$pages = array();
	while ($row = sed_sql_fetchassoc($sql)) {
		$pages[] = $row;
	}

	return $pages;
}

/**
 * Build public URL for a page row (sets catcode for SEF category path).
 *
 * @param array $page Row with page_id, page_alias, page_cat
 * @return string
 */
function sed_page_build_url($page)
{
	global $sys, $sed_cat;

	if (!isset($page['page_cat']) || !isset($sed_cat[$page['page_cat']])) {
		if (!empty($page['page_alias'])) {
			return sed_url('page', 'al=' . $page['page_alias']);
		}
		return sed_url('page', 'id=' . (int)$page['page_id']);
	}

	$prev_catcode = array_key_exists('catcode', $sys) ? $sys['catcode'] : null;
	$sys['catcode'] = $page['page_cat'];

	if (!empty($page['page_alias'])) {
		$url = sed_url('page', 'al=' . $page['page_alias']);
	} else {
		$url = sed_url('page', 'id=' . (int)$page['page_id']);
	}

	if ($prev_catcode !== null) {
		$sys['catcode'] = $prev_catcode;
	} else {
		unset($sys['catcode']);
	}

	return $url;
}

/**
 * Clear menu cache when page content or structure affects auto menu items.
 */
function sed_page_clear_menu_cache()
{
	sed_cache_clear('sed_menu');
}

/* ======== Load page structure into global $sed_cat when module is active ======== */

if ((!isset($sed_cat) || !$sed_cat) && sed_module_active('page')) {
	$sed_cat = sed_load_page_structure();
	sed_cache_store('sed_cat', $sed_cat, 3600);
}
