<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/search/search.php
Version=185
Date=2022-jul-28
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=search
Part=main
File=search
Hooks=standalone
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

require_once(SED_ROOT . '/plugins/search/inc/search.functions.php');

$cfg_maxwords = 5;
$cfg_maxitems = 50;

$sq = sed_import('sq', 'P', 'TXT');
$sqg = sed_import('sqg', 'G', 'TXT', 64);
$a = sed_import('a', 'G', 'TXT');
$checked_catarr = array();
$checked_frmarr = array();

$error_string = '';
$total_items = 0;
$do_search = false;

if ($a == 'search') {
	$do_search = true;
} elseif ($sqg !== '' && $sqg !== null) {
	$do_search = true;
	$sq = $sqg;
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("plug", "e=search")] = $L['plu_title'];

if ($do_search) {
	if (empty($sq) || mb_strlen($sq) < 3) {
		$error_string .= $L['plu_querytooshort'] . "<br />";
	} else {
		$sq_raw = trim($sq);
		$words = preg_split('/\s+/u', $sq_raw, -1, PREG_SPLIT_NO_EMPTY);
		$words_count = count($words);
		if ($words_count > $cfg_maxwords) {
			$error_string .= $L['plu_toomanywords'] . " " . $cfg_maxwords . "<br />";
		}
		$sq = sed_sql_prep($sq_raw);
	}

	if (empty($error_string)) {
		$words_sql = preg_split('/\s+/u', $sq, -1, PREG_SPLIT_NO_EMPTY);
		$sqlsearch = (count($words_sql) > 1) ? implode("%", $words_sql) : $words_sql[0];
		$sqlsearch = "%" . $sqlsearch . "%";

		if (sed_module_active('page')) {

			$pag_sub = sed_import('pag_sub', 'P', 'ARR');
			if (!is_array($pag_sub) || $pag_sub[0] == 'all') {
				$sqlsections = '';
			} else {
				$sub = array();

				foreach ($pag_sub as $i => $k) {
					$k = sed_import($k, 'D', 'TXT');
					$checked_catarr[] = $k;
					$sub[] = "page_cat='" . sed_sql_prep($k) . "'";
				}
				$sqlsections = "AND (" . implode(' OR ', $sub) . ")";
			}

			$pagsql = "(p.page_title LIKE '" . $sqlsearch . "' OR p.page_text LIKE '" . sed_sql_prep($sqlsearch) . "')";

			$sql  = sed_sql_query("SELECT page_id, page_title, page_text, page_desc, page_cat, page_alias, page_date FROM $db_pages p, $db_structure s
					WHERE $pagsql AND p.page_cat = s.structure_code
					AND p.page_cat NOT LIKE 'system' $sqlsections ORDER BY page_date DESC
					LIMIT $cfg_maxitems");

			$items = sed_sql_numrows($sql);

			if ($items > 0) {
				while ($row = sed_sql_fetchassoc($sql)) {
					if (sed_auth('page', $row['page_cat'], 'R')) {
						$sys['catcode'] = $row['page_cat'];
						$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id']) : sed_url("page", "al=" . $row['page_alias']);
						$snippet_src = $row['page_text'];
						if ($snippet_src === '' && !empty($row['page_desc'])) {
							$snippet_src = $row['page_desc'];
						}
						$t->assign(array(
							"PLUGIN_SEARCH_ROW_PAGE_CATEGORY_URL" => sed_url("page", "c=" . $row['page_cat']),
							"PLUGIN_SEARCH_ROW_PAGE_CATEGORY_TITLE" => $sed_cat[$row['page_cat']]['tpath'],
							"PLUGIN_SEARCH_ROW_PAGE_URL" => $row['page_pageurl'],
							"PLUGIN_SEARCH_ROW_PAGE_TITLE" => sed_plug_search_highlight($row['page_title'], $words),
							"PLUGIN_SEARCH_ROW_PAGE_SNIPPET" => sed_plug_search_snippet($snippet_src, $words)
						));
						$t->parse("MAIN.PLUGIN_SEARCH_PAGES.PLUGIN_SEARCH_PAGES_ROW");
					}
				}
				$total_items += $items;
				$t->assign("PLUGIN_SEARCH_PAGE_FOUND", $items);
				$t->parse("MAIN.PLUGIN_SEARCH_PAGES");
			}
		}

		if (sed_module_active('forums')) {
			$frm_sub = sed_import('frm_sub', 'P', 'ARR');

			if (!is_array($frm_sub) || $frm_sub[0] == 9999) {
				$sqlsections = '';
			} else {
				foreach ($frm_sub as $i => $k) {
					$k = sed_import($k, 'D', 'TXT');
					$checked_frmarr[] = $k;
					$sections1[] = "s.fs_id='" . sed_sql_prep($k) . "'";
				}
				$sqlsections = "AND (" . implode(' OR ', $sections1) . ")";
			}

			$sql = sed_sql_query("SELECT p.fp_id, p.fp_text, t.ft_title, t.ft_id, t.ft_updated, s.fs_id, s.fs_title, s.fs_category
			FROM $db_forum_posts p, $db_forum_topics t, $db_forum_sections s
			WHERE 1 AND (p.fp_text LIKE '" . sed_sql_prep($sqlsearch) . "' OR t.ft_title LIKE '" . sed_sql_prep($sqlsearch) . "')
			AND p.fp_topicid=t.ft_id AND p.fp_sectionid=s.fs_id $sqlsections
			GROUP BY t.ft_id ORDER BY ft_updated DESC
			LIMIT $cfg_maxitems");

			$items = sed_sql_numrows($sql);

			if ($items > 0) {
				while ($row = sed_sql_fetchassoc($sql)) {
					if (sed_auth('forums', $row['fs_id'], 'R')) {
						$snippet_src = $row['fp_text'];
						if ($snippet_src === '') {
							$snippet_src = $row['ft_title'];
						}
						$t->assign(array(
							"PLUGIN_SEARCH_ROW_FORUM_SECTION" => sed_build_forums($row['fs_id'], $row['fs_title'], $row['fs_category'], TRUE),
							"PLUGIN_SEARCH_ROW_FORUM_TOPIC_TITLE" => sed_plug_search_highlight($row['ft_title'], $words),
							"PLUGIN_SEARCH_ROW_FORUM_TOPIC_URL" => sed_url("forums", "m=posts&p=" . $row['fp_id'], "#" . $row['fp_id']),
							"PLUGIN_SEARCH_ROW_FORUM_SNIPPET" => sed_plug_search_snippet($snippet_src, $words)
						));
						$t->parse("MAIN.PLUGIN_SEARCH_FORUMS.PLUGIN_SEARCH_FORUMS_ROW");
					}
				}
				$total_items += $items;
				$t->assign("PLUGIN_SEARCH_FORUM_FOUND", $items);
				$t->parse("MAIN.PLUGIN_SEARCH_FORUMS");
			}
		}
	}
}

if (sed_module_active('page')) {

	$selectboxCatValues = array('all' => $L['plu_allcategories']);
	foreach ($sed_cat as $i => $x) {
		if ($i != 'all' && $i != 'system' && sed_auth('page', $i, 'R')) {
			$selectboxCatValues[$i] = $x['tpath'];
		}
	}

	$checked_catarr = (empty($checked_catarr)) ? array('all') : $checked_catarr;

	$additionalAttributes = array(
		"multiple" => true,
		"size" => 5
	);

	$page_cats = sed_selectbox($checked_catarr, "pag_sub[]", $selectboxCatValues, FALSE, TRUE, TRUE, $additionalAttributes, TRUE);

	$t->assign("PLUGIN_SEARCH_FORM_PAGES", $page_cats);
	$t->parse("MAIN.PLUGIN_SEARCH_FORM.PLUGIN_SEARCH_FORM_PAGES");
}

if (sed_module_active('forums')) {
	$sql1 = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category FROM $db_forum_sections AS s 
			LEFT JOIN $db_forum_structure AS n ON n.fn_code=s.fs_category
			ORDER by fn_path ASC, fs_order ASC");

	$selectboxForumsValues = array('9999' => $L['plu_allsections']);

	while ($row1 = sed_sql_fetchassoc($sql1)) {
		if (sed_auth('forums', $row1['fs_id'], 'R')) {
			$selectboxForumsValues[$row1['fs_id']] = sed_build_forums($row1['fs_id'], $row1['fs_title'], $row1['fs_category'], FALSE);
		}
	}

	$checked_frmarr = (empty($checked_frmarr)) ? array('9999') : $checked_frmarr;

	$additionalAttributes = array(
		"multiple" => true,
		"size" => 5
	);

	$forums_sections = sed_selectbox($checked_frmarr, "frm_sub[]", $selectboxForumsValues, FALSE, TRUE, TRUE, $additionalAttributes, TRUE);

	$t->assign("PLUGIN_SEARCH_FORM_FORUMS", $forums_sections);
	$t->parse("MAIN.PLUGIN_SEARCH_FORM.PLUGIN_SEARCH_FORM_FORUMS");
}

$t->assign(array(
	"PLUGIN_SEARCH_FORM_SEND" => sed_url("plug", "e=search&a=search"),
	"PLUGIN_SEARCH_FORM_INPUT" => sed_textbox('sq', sed_cc($sq), 40, 64)
));

$t->parse("MAIN.PLUGIN_SEARCH_FORM");

if ($total_items == 0 && $do_search) {
	$error_string .= $L['plu_nofound'];
}

if (!empty($error_string)) {
	$t->assign("PLUGIN_SEARCH_ERROR_BODY", sed_alert($error_string, 'e'));
	$t->parse("MAIN.PLUGIN_SEARCH_ERROR");
}

$t->assign(array(
	"PLUGIN_SEARCH_TITLE" => "<a href=\"" . sed_url("plug", "e=search") . "\">" . $L['plu_title'] . "</a>",
	"PLUGIN_SEARCH_SHORTTITLE" => $L['plu_title'],
	"PLUGIN_SEARCH_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PLUGIN_SEARCH_URL" => sed_url("plug", "e=search")
));
