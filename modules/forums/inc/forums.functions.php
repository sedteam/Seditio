<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/inc/forums.functions.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Forums API functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Register forum database table names
$cfg['forumsefurls'] = TRUE;

$db_forum_sections = $cfg['sqldbprefix'] . 'forum_sections';
$db_forum_structure = $cfg['sqldbprefix'] . 'forum_structure';
$db_forum_topics = $cfg['sqldbprefix'] . 'forum_topics';
$db_forum_posts = $cfg['sqldbprefix'] . 'forum_posts';

/* Forum structure is loaded at end of file after function definitions */

/** 
 * Returns forum thread path 
 * 
 * @param int $sectionid Section ID 
 * @param string $title Thread title 
 * @param string $category Category code 
 * @param bool $link Display as links 
 * @param mixed $parentcat Master section 
 * @return string 
 */
function sed_build_forums($sectionid, $title, $category, $link = true, $parentcat = false)
{
	global $sed_forums_str, $cfg;

	$category = ($category !== null && $category !== '') ? $category : '';
	if ($category === '' || !is_array($sed_forums_str) || !isset($sed_forums_str[$category]['path'])) {
		return sed_cc($title);
	}
	$pathstr = $sed_forums_str[$category]['path'];
	$pathcodes = explode('.', (string) $pathstr);
	$tmp = array();

	$parents = sed_forum_get_parents($sectionid);

	if ($link) {
		foreach ($pathcodes as $k => $x) {
			$x = trim($x);
			if ($x === '' || !isset($sed_forums_str[$x]['title'])) {
				continue;
			}
			$ptitle = sed_cc($sed_forums_str[$x]['title']);
			$tmp[] = sed_link(sed_url("forums", "c=" . $x, "#" . $x), $ptitle);
		}

		foreach ($parents as $p) {
			$ptitle = sed_cc($p['fs_title']);
			$tmp[] = sed_link(sed_url("forums", "m=topics&s=" . $p['fs_id'] . "&al=" . $ptitle), $ptitle);
		}
		$tmp[] = sed_link(sed_url("forums", "m=topics&s=" . $sectionid . "&al=" . $title), sed_cc($title));
	} else {
		foreach ($pathcodes as $k => $x) {
			$x = trim($x);
			if ($x === '' || !isset($sed_forums_str[$x]['title'])) {
				continue;
			}
			$tmp[] = sed_cc($sed_forums_str[$x]['title']);
		}

		foreach ($parents as $p) {
			$tmp[] = sed_cc($p['fs_title']);
		}

		$tmp[] = sed_cc($title);
	}

	$result = implode(' ' . $cfg['separator_symbol'] . ' ', $tmp);

	return ($result);
}

/** 
 * Forums breadcrumbs build path arr
 *
 * @param int $sectionid Forum section id
 * @param string $title Title  
 * @param string $category Category code  
 * @param mixed $parentcat Parent category info
 */
function sed_build_forums_bc($sectionid, $title, $category, $parentcat = false)
{
	global $sed_forums_str, $cfg, $urlpaths;

	$pathcodes = explode('.', $sed_forums_str[$category]['path']);

	foreach ($pathcodes as $k => $x) {
		$ptitle = sed_cc($sed_forums_str[$x]['title']);
		$urlpaths[sed_url("forums", "c=" . $x, "#" . $x)] = $ptitle;
	}

	$parents = sed_forum_get_parents($sectionid);
	foreach ($parents as $p) {
		$ptitle = sed_cc($p['fs_title']);
		$urlpaths[sed_url("forums", "m=topics&s=" . $p['fs_id'] . "&al=" . $ptitle)] = $ptitle;
	}

	$title = sed_cc($title);
	$urlpaths[sed_url("forums", "m=topics&s=" . $sectionid . "&al=" . $title)] = $title;
}

/** 
 * Gets details for forum section 
 * 
 * @param int $id Section ID 
 * @return mixed 
 */
function sed_forum_info($id)
{
	global $db_forum_sections;

	$sql = sed_sql_query("SELECT * FROM $db_forum_sections WHERE fs_id='$id'");
	if ($res = sed_sql_fetchassoc($sql)) {
		return ($res);
	} else {
		return ('');
	}
}

/** 
 * Moves outdated topics to trash 
 * 
 * @param string $mode Selection criteria 
 * @param int $section Section 
 * @param int $param Selection parameter value 
 * @return int 
 */
function sed_forum_prunetopics($mode, $section, $param)
{
	global $cfg, $sys, $db_forum_topics, $db_forum_posts, $db_forum_sections, $L;

	$num = 0;
	$num1 = 0;

	switch ($mode) {
		case 'updated':
			$limit = $sys['now'] - ($param * 86400);
			$sql1 = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_sectionid='$section' AND ft_updated<'$limit' AND ft_sticky='0'");
			break;

		case 'single':
			$sql1 = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_sectionid='$section' AND ft_id='$param'");
			break;
	}

	if (sed_sql_numrows($sql1) > 0) {
		while ($row1 = sed_sql_fetchassoc($sql1)) {
			$q = $row1['ft_id'];

			$sql = sed_sql_query("SELECT * FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id DESC");

			while ($row = sed_sql_fetchassoc($sql)) {
				/* === Hook === */
				$extp = sed_getextplugins('forums.prune.post.delete.first');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}
				/* ===== */

			}

			$sql = sed_sql_query("DELETE FROM $db_forum_posts WHERE fp_topicid='$q'");
			$num += sed_sql_affectedrows();

			/* === Hook === */
			$extp = sed_getextplugins('forums.prune.post.delete.done');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_id='$q'");

			while ($row = sed_sql_fetchassoc($sql)) {
				/* === Hook === */
				$extp = sed_getextplugins('forums.prune.topic.delete.first');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}
				/* ===== */

			}

			$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_id='$q'");
			$num1 += sed_sql_affectedrows();

			/* === Hook === */
			$extp = sed_getextplugins('forums.prune.topic.delete.done');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */
		}

		$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_movedto='$q'");
		$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_topiccount=fs_topiccount-'$num1', fs_postcount=fs_postcount-'$num', fs_topiccount_pruned=fs_topiccount_pruned+'$num1', fs_postcount_pruned=fs_postcount_pruned+'$num' WHERE fs_id='$section'");
	}
	$num1 = ($num1 == '') ? '0' : $num1;
	return ($num1);
}

/** 
 * Changes last message for the section 
 * 
 * @param int $id Section ID 
 */
function sed_forum_sectionsetlast($id)
{
	global $db_forum_topics, $db_forum_sections;

	$sql = sed_sql_query("SELECT ft_id, ft_lastposterid, ft_lastpostername, ft_updated, ft_title, ft_poll FROM $db_forum_topics WHERE ft_sectionid='$id' AND ft_movedto='0' and ft_mode='0' ORDER BY ft_updated DESC LIMIT 1");
	if (sed_sql_numrows($sql) > 0) {
		$row = sed_sql_fetchassoc($sql);
		$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_lt_id=" . (int)$row['ft_id'] . ", fs_lt_title='" . sed_sql_prep($row['ft_title']) . "', fs_lt_date=" . (int)$row['ft_updated'] . ", fs_lt_posterid=" . (int)$row['ft_lastposterid'] . ", fs_lt_postername='" . sed_sql_prep($row['ft_lastpostername']) . "' WHERE fs_id='$id'");
	}
	return;
}

/** 
 * Loads complete forum structure into array 
 * 
 * @return array 
 */
function sed_load_forum_structure()
{
	global $db_forum_structure, $cfg, $L;

	$res = array();

	$path = array(); // code path tree
	$tpath = array(); // title path tree

	$sql = sed_sql_query("SELECT * FROM $db_forum_structure ORDER BY fn_path ASC");

	while ($row = sed_sql_fetchassoc($sql)) {
		if (!empty($row['fn_icon'])) {
			$row['fn_icon'] = "<img src=\"" . $row['fn_icon'] . "\" alt=\"\" />";
		}

		$path2 = mb_strrpos($row['fn_path'], '.');

		$row['fn_tpl'] = (empty($row['fn_tpl'])) ? $row['fn_code'] : $row['fn_tpl'];

		if ($path2 > 0) {
			$path1 = mb_substr($row['fn_path'], 0, ($path2));
			$path[$row['fn_path']] = $path[$path1] . '.' . $row['fn_code'];
			$tpath[$row['fn_path']] = $tpath[$path1] . ' ' . $cfg['separator'] . ' ' . $row['fn_title'];
			$parent_dot = mb_strrpos($path[$path1], '.');
			$parent_tpl = ($parent_dot > 0) ? mb_substr($path[$path1], $parent_dot + 1) : $path[$path1];
			$row['fn_tpl'] = ($row['fn_tpl'] == 'same_as_parent') ? $parent_tpl : $row['fn_tpl'];
		} else {
			$path[$row['fn_path']] = $row['fn_code'];
			$tpath[$row['fn_path']] = $row['fn_title'];
		}

		$res[$row['fn_code']] = array(
			'path' => $path[$row['fn_path']],
			'tpath' => $tpath[$row['fn_path']],
			'rpath' => $row['fn_path'],
			'tpl' => $row['fn_tpl'],
			'title' => $row['fn_title'],
			'desc' => $row['fn_desc'],
			'icon' => $row['fn_icon'],
			'defstate' => $row['fn_defstate']
		);
	}

	return ($res);
}

/**
 * Flat forum category list with depth metadata for tree selects.
 *
 * @return array
 */
function sed_forum_categories_list_with_level()
{
	global $sed_forums_str;

	$rows = array();
	foreach ($sed_forums_str as $code => $x) {
		$rows[] = array(
			'fn_code' => $code,
			'fn_path' => $x['rpath'],
			'fn_title' => $x['title'],
		);
	}

	usort($rows, function ($a, $b) {
		$a_parts = explode('.', $a['fn_path']);
		$b_parts = explode('.', $b['fn_path']);
		$max = max(count($a_parts), count($b_parts));
		for ($i = 0; $i < $max; $i++) {
			$av = isset($a_parts[$i]) ? $a_parts[$i] : '';
			$bv = isset($b_parts[$i]) ? $b_parts[$i] : '';
			if ($av != $bv) {
				return strnatcmp($av, $bv);
			}
		}
		return 0;
	});

	return sed_tree_flat_from_dotpath($rows, 'fn_path');
}

/**
 * Forum category dropdown with tree prefix.
 *
 * @param string $check Selected category code
 * @param string $name  Dropdown name
 * @return string
 */
function sed_selectbox_forumcat($check, $name)
{
	$items = array();

	foreach (sed_forum_categories_list_with_level() as $item) {
		$items[] = array(
			'value' => $item['fn_code'],
			'title' => $item['fn_title'],
			'depth' => $item['depth'],
			'is_last' => $item['is_last'],
			'prefix_continues' => $item['prefix_continues'],
		);
	}

	return sed_selectbox_tree_html($name, $items, $check);
}

/**
 * Forum section selection dropdown.
 *
 * @param string $check Selected section ID
 * @param string $name  Dropdown name
 * @return string
 */
function sed_selectbox_sections($check, $name)
{
	global $cfg;

	$items = array();

	foreach (sed_forum_get_tree() as $item) {
		$label = sed_cutstring($item['fs_category'], 24) . ' ' . $cfg['separator'] . ' ' . sed_cutstring($item['fs_title'], 32);
		$items[] = array(
			'value' => $item['fs_id'],
			'title' => $label,
			'depth' => $item['depth'],
			'is_last' => $item['is_last'],
			'prefix_continues' => $item['prefix_continues'],
		);
	}

	return sed_selectbox_tree_html($name, $items, $check);
}

/**
 * Parent forum section dropdown with optgroups.
 *
 * @param string $name     HTML name attribute
 * @param int    $selected Currently selected parent ID
 * @param string $category Limit to this category code
 * @param array  $exclude  Section IDs to exclude (with descendants)
 * @return string
 */
function sed_selectbox_forum_parent($name, $selected = 0, $category = '', $exclude = array())
{
	global $sed_forums_str;

	$tree = sed_forum_get_tree($category, $exclude);
	$show_cat = empty($category);

	$result = '<select name="' . $name . '"><option value="0">--</option>';
	$prev_cat = '';

	foreach ($tree as $item) {
		if ($show_cat && $item['fs_category'] !== $prev_cat) {
			if ($prev_cat !== '') {
				$result .= '</optgroup>';
			}
			$cat_label = isset($sed_forums_str[$item['fs_category']]['title'])
				? sed_cc($sed_forums_str[$item['fs_category']]['title'])
				: sed_cc($item['fs_category']);
			$result .= '<optgroup label="' . $cat_label . '">';
			$prev_cat = $item['fs_category'];
		}
		$prefix = sed_tree_format_prefix($item['depth'], $item['is_last'], $item['prefix_continues']);
		$sel = ((int)$item['fs_id'] === (int)$selected) ? ' selected="selected"' : '';
		$result .= '<option value="' . $item['fs_id'] . '"' . $sel . '>' . $prefix . sed_cc($item['fs_title']) . '</option>';
	}

	if ($show_cat && $prev_cat !== '') {
		$result .= '</optgroup>';
	}

	$result .= '</select>';

	return $result;
}

/** 
 * Delete forums section 
 * 
 * @param int $id Section ID 
 * @return int Count deleted rows 
 */
function sed_forum_deletesection($id)
{
	global $db_forum_topics, $db_forum_posts, $db_forum_sections, $db_auth;

	$id = (int)$id;
	$num = 0;

	$sql = sed_sql_query("SELECT fs_parentcat FROM $db_forum_sections WHERE fs_id='$id'");
	$parent_id = 0;
	if ($row = sed_sql_fetchassoc($sql)) {
		$parent_id = (int)$row['fs_parentcat'];
	}

	sed_sql_query("UPDATE $db_forum_sections SET fs_parentcat='$parent_id' WHERE fs_parentcat='$id'");

	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='forums' AND auth_option='$id'");
	$num += sed_sql_affectedrows();

	$sql = sed_sql_query("DELETE FROM $db_forum_posts WHERE fp_sectionid='$id'");
	$num += sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_sectionid='$id'");
	$num += sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_forum_sections WHERE fs_id='$id'");
	$num += sed_sql_affectedrows();

	sed_log("Forums : Deleted section " . $id, 'adm');
	return ($num);
}

/** 
 * Recounts posts & topics in section
 * 
 * @param int $id Section ID 
 */
function sed_forum_resync($id)
{
	global $db_forum_topics, $db_forum_posts, $db_forum_sections;

	$id = (int)$id;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_sectionid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE ft_sectionid='$id'");
	while ($row = sed_sql_fetchassoc($sql)) {
		sed_forum_resynctopic($row['ft_id']);
	}

	$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_topiccount='$num' WHERE fs_id='$id'");
	sed_forum_sectionsetlast($id);

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_sectionid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_postcount='$num' WHERE fs_id='$id'");

	sed_log("Forums : Re-synced section " . $id, 'adm');
	return;
}

/** 
 * Recounts posts in a given topic 
 * 
 * @param int $id Topic ID 
 */
function sed_forum_resynctopic($id)
{
	global $db_forum_topics, $db_forum_posts;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_topicid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_postcount='$num' WHERE ft_id='$id'");

	$sql = sed_sql_query("SELECT fp_posterid, fp_postername, fp_updated
		FROM $db_forum_posts
		WHERE fp_topicid='$id'
		ORDER BY fp_id DESC LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$sql = sed_sql_query("UPDATE $db_forum_topics SET
			ft_lastposterid='" . (int)$row['fp_posterid'] . "',
			ft_lastpostername='" . sed_sql_prep($row['fp_postername']) . "',
			ft_updated='" . (int)$row['fp_updated'] . "'
			WHERE ft_id='$id'");
	}
	return;
}

/** 
 * Recounts posts & topics all sections
 */
function sed_forum_resyncall()
{
	global $db_forum_sections;

	$sql = sed_sql_query("SELECT fs_id FROM $db_forum_sections");
	while ($row = sed_sql_fetchassoc($sql)) {
		sed_forum_resync($row['fs_id']);
	}
	return;
}

/**
 * Loads all sections into a flat array keyed by fs_id.
 * Used as the source for tree building, parent lookups, etc.
 *
 * @return array
 */
function sed_forum_load_sections()
{
	global $db_forum_sections, $db_forum_structure;

	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}

	$cache = array();
	$sql = sed_sql_query("SELECT s.*, n.fn_path FROM $db_forum_sections AS s
		LEFT JOIN $db_forum_structure AS n ON n.fn_code = s.fs_category
		ORDER BY fn_path ASC, fs_order ASC");

	while ($row = sed_sql_fetchassoc($sql)) {
		$cache[(int)$row['fs_id']] = $row;
	}
	return $cache;
}

/**
 * @param array $children
 * @param array $all
 * @param array $exclude_set
 * @param string $category
 * @param int $pid
 * @param int $depth
 * @param array $prefix_continues
 * @return array
 */
function sed_forum_get_tree_walk($children, $all, $exclude_set, $category, $pid, $depth, $prefix_continues)
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
		if (!empty($category) && $all[$nid]['fs_category'] !== $category) {
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
		$sub = sed_forum_get_tree_walk($children, $all, $exclude_set, $category, $nid, $depth + 1, $child_prefix);
		$result = array_merge($result, $sub);
	}
	return $result;
}

/**
 * Builds a flat ordered list from the section tree suitable for rendering
 * (admin list, select dropdowns). Each item gets 'depth', 'is_last', 'prefix_continues'.
 *
 * @param string $category  If set, only sections of this category
 * @param array  $exclude   Section IDs to exclude (with all descendants)
 * @return array  Flat list ordered by tree position
 */
function sed_forum_get_tree($category = '', $exclude = array())
{
	$all = sed_forum_load_sections();

	$children = array();
	foreach ($all as $id => $row) {
		if (!empty($category) && $row['fs_category'] !== $category) {
			continue;
		}
		$pid = (int)$row['fs_parentcat'];
		$children[$pid][] = $id;
	}

	$exclude_set = array();
	foreach ($exclude as $eid) {
		$exclude_set[(int)$eid] = true;
		foreach (sed_forum_get_children_ids((int)$eid, $all) as $cid) {
			$exclude_set[$cid] = true;
		}
	}

	return sed_forum_get_tree_walk($children, $all, $exclude_set, $category, 0, 0, array());
}

/**
 * Returns the chain of parent sections from root down to (but not including) $section_id.
 *
 * @param int   $section_id
 * @param array $all  Pre-loaded sections (optional, auto-loaded if null)
 * @return array  Ordered from root to direct parent, each element is a section row
 */
function sed_forum_get_parents($section_id, $all = null)
{
	if ($all === null) {
		$all = sed_forum_load_sections();
	}

	$chain = array();
	$current = (int)$section_id;
	$seen = array();

	while ($current > 0 && isset($all[$current])) {
		if (isset($seen[$current])) {
			break;
		}
		$seen[$current] = true;
		$pid = (int)$all[$current]['fs_parentcat'];
		if ($pid > 0 && isset($all[$pid])) {
			array_unshift($chain, $all[$pid]);
		}
		$current = $pid;
	}
	return $chain;
}

/**
 * Builds parent-id => child fs_id lists for the full section tree.
 *
 * @param array|null $all  From sed_forum_load_sections(); null loads sections once
 * @return array
 */
function sed_forum_get_children_map($all = null)
{
	if ($all === null) {
		$all = sed_forum_load_sections();
	}
	$children_map = array();
	foreach ($all as $id => $row) {
		$pid = (int)$row['fs_parentcat'];
		$children_map[$pid][] = $id;
	}
	return $children_map;
}

/**
 * Returns all descendant IDs of the given section (recursive).
 *
 * @param int   $section_id
 * @param array $all  Pre-loaded sections (optional, ignored if $children_map set)
 * @param array|null $children_map  From sed_forum_get_children_map(); reuse to avoid rebuilding
 * @return array  Flat list of descendant fs_id values
 */
function sed_forum_get_children_ids($section_id, $all = null, $children_map = null)
{
	if ($children_map === null) {
		$children_map = sed_forum_get_children_map($all);
	}

	$result = array();
	$stack = isset($children_map[$section_id]) ? $children_map[$section_id] : array();

	while (!empty($stack)) {
		$nid = array_pop($stack);
		$result[] = $nid;
		if (isset($children_map[$nid])) {
			foreach ($children_map[$nid] as $cid) {
				$stack[] = $cid;
			}
		}
	}
	return $result;
}

/**
 * Latest post row (fs_lt_*) among a section's descendants, using loaded subforum rows.
 *
 * @param int   $section_id      Parent section fs_id
 * @param array $forum_subforums Rows keyed by fs_id (e.g. main page subforum query)
 * @param array|null $children_map Optional; from sed_forum_get_children_map() for one pass per page
 * @return array|null           One section row or null
 */
function sed_forum_latest_in_subtree($section_id, &$forum_subforums, $children_map = null)
{
	$latest = 0;
	$latest_row = null;
	$desc_ids = sed_forum_get_children_ids($section_id, null, $children_map);
	foreach ($desc_ids as $did) {
		if (isset($forum_subforums[$did]) && $forum_subforums[$did]['fs_lt_date'] > $latest) {
			$latest = $forum_subforums[$did]['fs_lt_date'];
			$latest_row = $forum_subforums[$did];
		}
	}
	return $latest_row;
}

/**
 * Validates that $new_parent_id is a legal parent for $section_id:
 * not itself, not a descendant, and depth limit not exceeded.
 *
 * @param int $section_id
 * @param int $new_parent_id
 * @param int $max_depth  Maximum nesting depth (0 = unlimited)
 * @return bool
 */
function sed_forum_validate_parent($section_id, $new_parent_id, $max_depth = 5)
{
	if ($new_parent_id == 0) {
		return true;
	}
	if ($section_id == $new_parent_id) {
		return false;
	}

	$descendants = sed_forum_get_children_ids($section_id);
	if (in_array($new_parent_id, $descendants)) {
		return false;
	}

	if ($max_depth > 0) {
		$all = sed_forum_load_sections();
		$parents = sed_forum_get_parents($new_parent_id, $all);
		$subtree_depth = 0;
		$sub_ids = sed_forum_get_children_ids($section_id, $all);
		if (!empty($sub_ids)) {
			foreach ($sub_ids as $cid) {
				$cp = sed_forum_get_parents($cid, $all);
				$rel_depth = 0;
				foreach ($cp as $p) {
					if ((int)$p['fs_id'] === $section_id) {
						break;
					}
					$rel_depth++;
				}
				$d = count($cp) - $rel_depth;
				if ($d > $subtree_depth) {
					$subtree_depth = $d;
				}
			}
		}
		$new_depth = count($parents) + 1 + $subtree_depth;
		if ($new_depth > $max_depth) {
			return false;
		}
	}

	return true;
}

/**
 * Get forums URL translation suffix (alias)
 */
function sed_get_forums_urltrans(&$args, &$section)
{
	global $cfg;
	$url = (isset($args['al']) && !empty($args['al']) && $cfg['forumsefurls']) ? "-" . sed_translit_seourl($args['al']) : "";
	return $url;
}

/**
 * Ensure every forum section has auth rows for standard groups (fixes installs that missed auth).
 * Runs at most once per request.
 * Uses sed_auth_install_option with letter masks and SED_GROUP_* constants (same as forums.install.php).
 */
function sed_forum_ensure_section_auth()
{
	global $cfg, $db_auth, $db_groups, $db_forum_sections, $sed_default_auth_rights, $sed_default_auth_lock;
	static $ensured = false;
	if ($ensured) {
		return;
	}
	$forums_default_rights = $sed_default_auth_rights;
	$forums_default_lock = $sed_default_auth_lock;
	$forum_rights = array();
	$forum_lock = array();
	$sql_grps = sed_sql_query("SELECT grp_id FROM $db_groups WHERE grp_disabled=0");
	while ($row = sed_sql_fetchassoc($sql_grps)) {
		$gid = (int)$row['grp_id'];
		$forum_rights[$gid] = isset($forums_default_rights[$gid]) ? $forums_default_rights[$gid] : $forums_default_rights[SED_GROUP_DEFAULT];
		$forum_lock[$gid] = isset($forums_default_lock[$gid]) ? $forums_default_lock[$gid] : $forums_default_lock[SED_GROUP_DEFAULT];
	}
	$sql_fs = sed_sql_query("SELECT fs_id FROM $db_forum_sections");
	$did_insert = false;
	while ($fs = sed_sql_fetchassoc($sql_fs)) {
		$sid = (int)$fs['fs_id'];
		$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='forums' AND auth_option='$sid' LIMIT 1");
		if (sed_sql_numrows($chk) == 0) {
			sed_auth_install_option('forums', $sid, $forum_rights, $forum_lock, 1);
			$did_insert = true;
		}
	}
	if ($did_insert && function_exists('sed_auth_clear')) {
		sed_auth_clear('all');
	}
	$ensured = true;
}

/* ======== Load forum structure into global ======== */

if ((!isset($sed_forums_str) || !$sed_forums_str) && sed_module_active('forums')) {
	$sed_forums_str = sed_load_forum_structure();
	sed_cache_store('sed_forums_str', $sed_forums_str, 3600);
	sed_forum_ensure_section_auth();
}
