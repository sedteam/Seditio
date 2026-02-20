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

	if ($link) {
		foreach ($pathcodes as $k => $x) {
			$x = trim($x);
			if ($x === '' || !isset($sed_forums_str[$x]['title'])) {
				continue;
			}
			$ptitle = sed_cc($sed_forums_str[$x]['title']);
			$tmp[] = sed_link(sed_url("forums", "c=" . $x, "#" . $x), $ptitle);
		}

		if (is_array($parentcat) && !empty($parentcat)) {
			$ptitle = sed_cc($parentcat['title']);
			$tmp[] = sed_link(sed_url("forums", "m=topics&s=" . $parentcat['sectionid'] . "&al=" . $ptitle), $ptitle);
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

		if (is_array($parentcat) && !empty($parentcat)) {
			$tmp[] = $parentcat['title'];
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

	if (is_array($parentcat) && !empty($parentcat)) {
		$ptitle = sed_cc($parentcat['title']);
		$urlpaths[sed_url("forums", "m=topics&s=" . $parentcat['sectionid'] . "&al=" . $ptitle)] = $ptitle;
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

			if ($cfg['trash_forum']) {
				$sql = sed_sql_query("SELECT * FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id DESC");

				while ($row = sed_sql_fetchassoc($sql)) {
					sed_trash_put('forumpost', $L['Post'] . " #" . $row['fp_id'] . " from topic #" . $q, "p" . $row['fp_id'] . "-q" . $q, $row);
				}
			}

			$sql = sed_sql_query("DELETE FROM $db_forum_posts WHERE fp_topicid='$q'");
			$num += sed_sql_affectedrows();

			if ($cfg['trash_forum']) {
				$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_id='$q'");

				while ($row = sed_sql_fetchassoc($sql)) {
					sed_trash_put('forumtopic', $L['Topic'] . " #" . $q . " (no post left)", "q" . $q, $row);
				}
			}

			$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_id='$q'");
			$num1 += sed_sql_affectedrows();
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
 * Returns forum category dropdown code 
 * 
 * @param int $check Selected category 
 * @param string $name Dropdown name 
 * @return string 
 */
function sed_selectbox_forumcat($check, $name)
{
	global $usr, $sed_forums_str, $L;

	$result =  "<select name=\"$name\" size=\"1\">";

	foreach ($sed_forums_str as $i => $x) {
		$selected = ($i == $check) ? "selected=\"selected\"" : '';
		$result .= "<option value=\"" . $i . "\" $selected> " . $x['tpath'] . "</option>";
	}
	$result .= "</select>";
	return ($result);
}

/** 
 * Renders forum section selection dropdown 
 * 
 * @param string $check Selected value 
 * @param string $name Dropdown name 
 * @return string 
 */
function sed_selectbox_sections($check, $name)
{
	global $db_forum_sections, $cfg;

	$sql = sed_sql_query("SELECT fs_id, fs_title, fs_category FROM $db_forum_sections WHERE 1 ORDER by fs_order ASC");
	$result = "<select name=\"$name\" size=\"1\">";
	while ($row = sed_sql_fetchassoc($sql)) {
		$selected = ($row['fs_id'] == $check) ? "selected=\"selected\"" : '';
		$result .= "<option value=\"" . $row['fs_id'] . "\" $selected>" . sed_cc(sed_cutstring($row['fs_category'], 24));
		$result .= ' ' . $cfg['separator'] . ' ' . sed_cc(sed_cutstring($row['fs_title'], 32));
	}
	$result .= "</select>";
	return ($result);
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

	// Remove rights (auth) for this section first
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

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_sectionid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE 1");
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
 */
function sed_forum_ensure_section_auth()
{
	global $cfg, $db_auth;
	static $ensured = false;
	if ($ensured) {
		return;
	}
	$db_auth = $cfg['sqldbprefix'] . 'auth';
	$sql_fs = sed_sql_query("SELECT fs_id FROM " . $cfg['sqldbprefix'] . "forum_sections");
	$did_insert = false;
	while ($fs = sed_sql_fetchassoc($sql_fs)) {
		$sid = (int)$fs['fs_id'];
		$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='forums' AND auth_option='$sid' LIMIT 1");
		if (sed_sql_numrows($chk) == 0) {
			$forum_auth_defaults = array(
				array(1, 1, 254), array(2, 1, 254), array(3, 0, 255), array(4, 3, 128), array(5, 255, 255), array(6, 131, 0)
			);
			foreach ($forum_auth_defaults as $row) {
				sed_sql_query("INSERT INTO $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$row[0] . ", 'forums', '$sid', " . (int)$row[1] . ", " . (int)$row[2] . ", 1)");
			}
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
