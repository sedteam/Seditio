<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags
Hooks=direct
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

global $db_tags, $db_tag_references, $db_pages, $db_forum_topics, $db_forum_sections, $db_users, $db_structure, $cfg, $usr, $L, $sys, $sed_cat;

$tag_query = sed_import('t', 'G', 'TXT');
if (empty($tag_query)) {
	$tag_query = sed_import('t', 'P', 'TXT');
}
$tag_query = sed_tag_qs_value($tag_query);
$tag_area = sed_import('a', 'G', 'ALP');
if (empty($tag_area)) $tag_area = 'all';
if (!in_array($tag_area, array('pages', 'forums', 'all'))) $tag_area = 'all';

$page_module_ok = sed_module_active('page');
$forums_module_ok = sed_module_active('forums');
if ($tag_area === 'pages' && !$page_module_ok) $tag_area = ($forums_module_ok) ? 'forums' : 'all';
if ($tag_area === 'forums' && !$forums_module_ok) $tag_area = ($page_module_ok) ? 'pages' : 'all';

$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

if (!empty($tag_query)) {
	$out['subtitle'] = $L['tags_results'] . ': ' . sed_cc($tag_query);
	$out['subdesc'] = $L['tags_results'] . ': ' . sed_cc($tag_query);
	$out['subkeywords'] = sed_cc($tag_query);
	if (!empty($cfg['plugin']['tags']['noindex'])) {
		$out['robots_index'] = 0;
	}
} else {
	$out['subtitle'] = $L['tags_title'];
	$out['subdesc'] = $L['tags_cloud'] . '. ' . $L['tags_search'];
	$out['subkeywords'] = $L['tags_tags'];
}
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$cfg['tagstitle'] = isset($cfg['plugin']['tags']['tagstitle']) ? $cfg['plugin']['tags']['tagstitle'] : '{MAINTITLE} - {TITLE}';
$out['subtitle'] = sed_title('tagstitle', $title_tags, $title_data);

$urlpaths = array();
$urlpaths[sed_url('plug', 'e=tags')] = $L['tags_title'];

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile('tags', true);
$t = new XTemplate($mskin);

$tag_order = isset($cfg['plugin']['tags']['order']) ? $cfg['plugin']['tags']['order'] : 'Alphabetical';

/* --- Search form --- */
$area_options = '<option value="all"' . ($tag_area == 'all' ? ' selected' : '') . '>' . $L['tags_area_all'] . '</option>';
if ($page_module_ok) {
	$area_options .= '<option value="pages"' . ($tag_area == 'pages' ? ' selected' : '') . '>' . $L['tags_area_pages'] . '</option>';
}
if ($forums_module_ok) {
	$area_options .= '<option value="forums"' . ($tag_area == 'forums' ? ' selected' : '') . '>' . $L['tags_area_forums'] . '</option>';
}

$t->assign(array(
	"TAGS_PAGETITLE" => $L['tags_title'],
	"TAGS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"TAGS_FORM_ACTION" => sed_url('plug', 'e=tags'),
	"TAGS_FORM_INPUT" => sed_textbox('t', sed_cc($tag_query), 40, 255, 'autotags'),
	"TAGS_FORM_AREA" => '<select name="a">' . $area_options . '</select>',
	"TAGS_FORM_HINT" => $L['tags_search_hint']
));

/* --- Results --- */
if (!empty($tag_query)) {
	$t->assign("TAGS_RESULTS_TITLE", $L['tags_results'] . ': <strong>' . sed_cc($tag_query) . '</strong>');

	$pages_pagination = '';
	$forums_pagination = '';

	$sort_map = array(
		'ID' => 'page_id',
		'Title' => 'page_title',
		'Date' => 'page_date',
		'Category' => 'page_cat'
	);
	$sort_col = isset($cfg['plugin']['tags']['sort'], $sort_map[$cfg['plugin']['tags']['sort']]) ? $sort_map[$cfg['plugin']['tags']['sort']] : 'page_date';
	$perpage = isset($cfg['plugin']['tags']['maxrowsperpage']) ? (int)$cfg['plugin']['tags']['maxrowsperpage'] : 30;
	if ($perpage <= 0) $perpage = 30;

	/* Pages results */
	if (($tag_area == 'pages' || $tag_area == 'all') && !empty($cfg['plugin']['tags']['pages']) && $page_module_ok) {
		$where_tags = sed_tag_parse_query($tag_query, 'p.page_id', 'pages');
		$where_extra = "p.page_state = 0 AND p.page_begin < " . (int)$sys['now_offset'] . " AND p.page_expire > " . (int)$sys['now_offset'];
		$count_sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages AS p WHERE $where_extra AND $where_tags");
		$totalitems_pages = sed_sql_result($count_sql, 0, 'COUNT(*)');

		if ($totalitems_pages > 0) {
			$sql = sed_sql_query("SELECT p.page_id, p.page_title, p.page_cat, p.page_date, p.page_alias, p.page_ownerid,
				u.user_name, u.user_maingrp
				FROM $db_pages AS p
				LEFT JOIN $db_users AS u ON u.user_id = p.page_ownerid
				WHERE $where_extra AND $where_tags
				ORDER BY p.$sort_col DESC
				LIMIT $d, $perpage");

			while ($row = sed_sql_fetchassoc($sql)) {
				$page_url = (empty($row['page_alias'])) ? sed_url('page', 'id=' . $row['page_id']) : sed_url('page', 'al=' . $row['page_alias']);
				$item_tags = sed_tag_list((int)$row['page_id'], 'pages');
				$cattitle = isset($sed_cat[$row['page_cat']]) ? $sed_cat[$row['page_cat']]['title'] : $row['page_cat'];

				$t->assign(array(
					"TAGS_RESULT_PAGE_ID" => $row['page_id'],
					"TAGS_RESULT_PAGE_URL" => $page_url,
					"TAGS_RESULT_PAGE_TITLE" => sed_cc($row['page_title']),
					"TAGS_RESULT_PAGE_CAT" => $cattitle,
					"TAGS_RESULT_PAGE_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['page_date']),
					"TAGS_RESULT_PAGE_OWNER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name']), $row['user_maingrp']),
					"TAGS_RESULT_PAGE_TAGS" => sed_tag_build_list($item_tags, 'pages', false)
				));
				$t->parse("MAIN.TAGS_RESULTS.TAGS_RESULT_PAGES.TAGS_RESULT_PAGE_ROW");
			}

			if ($totalitems_pages > $perpage) {
				$pages_pagination = sed_pagination(sed_url('plug', 'e=tags&a=' . sed_tag_qs_value($tag_area) . '&t=' . $tag_query), $d, $totalitems_pages, $perpage);
			}

			$t->assign("TAGS_RESULT_PAGES_TITLE", $L['tags_results_pages'] . ' (' . $totalitems_pages . ')');
			$t->parse("MAIN.TAGS_RESULTS.TAGS_RESULT_PAGES");
		}
	}

	/* Forums results */
	if (($tag_area == 'forums' || $tag_area == 'all') && !empty($cfg['plugin']['tags']['forums']) && $forums_module_ok) {
		$where_tags_f = sed_tag_parse_query($tag_query, 'ft.ft_id', 'forums');
		$count_sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics AS ft WHERE ft.ft_state = 0 AND $where_tags_f");
		$totalitems_forums = sed_sql_result($count_sql, 0, 'COUNT(*)');

		if ($totalitems_forums > 0) {
			$sql = sed_sql_query("SELECT ft.ft_id, ft.ft_title, ft.ft_sectionid, ft.ft_creationdate, ft.ft_postcount,
				fs.fs_title, fs.fs_category
				FROM $db_forum_topics AS ft
				LEFT JOIN $db_forum_sections AS fs ON fs.fs_id = ft.ft_sectionid
				WHERE ft.ft_state = 0 AND $where_tags_f
				ORDER BY ft.ft_creationdate DESC
				LIMIT $d, $perpage");

			while ($row = sed_sql_fetchassoc($sql)) {
				$topic_url = sed_url('forums', 'm=posts&q=' . $row['ft_id']);
				$item_tags = sed_tag_list((int)$row['ft_id'], 'forums');

				$t->assign(array(
					"TAGS_RESULT_TOPIC_ID" => $row['ft_id'],
					"TAGS_RESULT_TOPIC_URL" => $topic_url,
					"TAGS_RESULT_TOPIC_TITLE" => sed_cc($row['ft_title']),
					"TAGS_RESULT_TOPIC_SECTION" => sed_cc($row['fs_title']),
					"TAGS_RESULT_TOPIC_DATE" => sed_build_date($cfg['formatmonthdayhourmin'], $row['ft_creationdate']),
					"TAGS_RESULT_TOPIC_POSTS" => $row['ft_postcount'],
					"TAGS_RESULT_TOPIC_TAGS" => sed_tag_build_list($item_tags, 'forums', false)
				));
				$t->parse("MAIN.TAGS_RESULTS.TAGS_RESULT_FORUMS.TAGS_RESULT_TOPIC_ROW");
			}

			if ($totalitems_forums > $perpage) {
				$forums_pagination = sed_pagination(sed_url('plug', 'e=tags&a=' . sed_tag_qs_value($tag_area) . '&t=' . $tag_query), $d, $totalitems_forums, $perpage);
			}

			$t->assign("TAGS_RESULT_FORUMS_TITLE", $L['tags_results_forums'] . ' (' . $totalitems_forums . ')');
			$t->parse("MAIN.TAGS_RESULTS.TAGS_RESULT_FORUMS");
		}
	}

	$has_pages = isset($totalitems_pages) && $totalitems_pages > 0;
	$has_forums = isset($totalitems_forums) && $totalitems_forums > 0;

	if (!$has_pages && !$has_forums) {
		$t->assign("TAGS_NORESULTS_BODY", $L['tags_noresults']);
		$t->parse("MAIN.TAGS_RESULTS.TAGS_NORESULTS");
	}

	$tags_pagination = '';
	if ($tag_area == 'pages') {
		$tags_pagination = $pages_pagination;
	} elseif ($tag_area == 'forums') {
		$tags_pagination = $forums_pagination;
	} elseif ($tag_area == 'all') {
		$tp = isset($totalitems_pages) ? $totalitems_pages : 0;
		$tf = isset($totalitems_forums) ? $totalitems_forums : 0;
		$tags_pagination = ($tp >= $tf && !empty($pages_pagination)) ? $pages_pagination : $forums_pagination;
	}
	$t->assign("TAGS_PAGINATION", $tags_pagination);
	if (!empty($tags_pagination)) {
		$t->parse("MAIN.TAGS_RESULTS.TAGS_PAGINATION_TOP");
		$t->parse("MAIN.TAGS_RESULTS.TAGS_PAGINATION_BOTTOM");
	}

	$t->parse("MAIN.TAGS_RESULTS");
}

/* --- Cloud --- */
$cloud_limit = isset($cfg['plugin']['tags']['perpage']) ? (int)$cfg['plugin']['tags']['perpage'] : 0;
if ($tag_area === 'pages') {
	$cloud_limit = isset($cfg['plugin']['tags']['lim_pages']) ? (int)$cfg['plugin']['tags']['lim_pages'] : $cloud_limit;
} elseif ($tag_area === 'forums') {
	$cloud_limit = isset($cfg['plugin']['tags']['lim_forums']) ? (int)$cfg['plugin']['tags']['lim_forums'] : $cloud_limit;
}
$cloud = sed_tag_cloud($tag_area, $tag_order, $cloud_limit);

if (!empty($cloud)) {
	$cloud_html = sed_tag_build_cloud($cloud, $tag_area);
	$t->assign(array(
		"TAGS_CLOUD_TITLE" => $L['tags_cloud'],
		"TAGS_CLOUD_BODY" => $cloud_html
	));
	$t->parse("MAIN.TAGS_CLOUD");
}

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
