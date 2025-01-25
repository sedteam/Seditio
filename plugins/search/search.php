<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/search/search.php
Version=180
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
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

$cfg_maxwords = 5;
$cfg_maxitems = 50;

$sq = sed_import('sq', 'P', 'TXT');
$a = sed_import('a', 'G', 'TXT');
$checked_catarr = array();
$checked_frmarr = array();

$error_string = '';
$total_items = 0;

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("plug", "e=search")] = $L['plu_title'];

if ($a == 'search') {
	if (empty($sq) || mb_strlen($sq) < 3) {
		$error_string .= $L['plu_querytooshort'] . "<br />";
	} else {
		$sq = sed_sql_prep($sq);
		$words = explode(" ", $sq);
		$words_count = count($words);
		if ($words_count > $cfg_maxwords) {
			$error_string .= $L['plu_toomanywords'] . " " . $cfg_maxwords . "<br />";
		}
	}

	if (empty($error_string)) {
		$sqlsearch = (count($words) > 1) ? implode("%", $words) : $words[0];
		$sqlsearch = "%" . $sqlsearch . "%";

		if (!$cfg['disable_page']) {

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

			$pagsql = "(p.page_title LIKE '" . $sqlsearch . "' OR p.page_text LIKE '" . sed_sql_prep($sqlsearch) . "') AND ";

			$sql  = sed_sql_query("SELECT page_id, page_ownerid, page_title, page_cat, page_alias, page_date from $db_pages p, $db_structure s
					WHERE $pagsql (p.page_title LIKE '" . $sqlsearch . "' OR p.page_text LIKE '" . sed_sql_prep($sqlsearch) . "') AND p.page_cat = s.structure_code
					AND p.page_cat NOT LIKE 'system' $sqlsections ORDER by page_date DESC 
					LIMIT $cfg_maxitems");

			$items = sed_sql_numrows($sql);

			if ($items > 0) {
				while ($row = sed_sql_fetchassoc($sql)) {
					if (sed_auth('page', $row['page_cat'], 'R')) {
						$sys['catcode'] = $row['page_cat'];
						$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id']) : sed_url("page", "al=" . $row['page_alias']);
						$ownername = sed_sql_fetchassoc(sed_sql_query("SELECT user_name FROM $db_users WHERE user_id='" . $row['page_ownerid'] . "'"));
						$t->assign(array(
							"PLUGIN_SEARCH_ROW_PAGE_CATEGORY_URL" => sed_url("list", "c=" . $row['page_cat']),
							"PLUGIN_SEARCH_ROW_PAGE_CATEGORY_TITLE" => $sed_cat[$row['page_cat']]['tpath'],
							"PLUGIN_SEARCH_ROW_PAGE_URL" =>	$row['page_pageurl'],
							"PLUGIN_SEARCH_ROW_PAGE_TITLE" => sed_cc($row['page_title']),
							"PLUGIN_SEARCH_ROW_PAGE_DATE" => @date($cfg['dateformat'], $row['page_date'] + $usr['timezone'] * 3600),
							"PLUGIN_SEARCH_ROW_PAGE_OWNER" => sed_build_user($row['page_ownerid'], $ownername['user_name'])
						));
						$t->parse("MAIN.PLUGIN_SEARCH_PAGES.PLUGIN_SEARCH_PAGES_ROW");
					}
				}
				$total_items += $items;
				$t->assign("PLUGIN_SEARCH_PAGE_FOUND", $items);
				$t->parse("MAIN.PLUGIN_SEARCH_PAGES");
			}
		}

		if (!$cfg['disable_forums']) {
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

			$sql = sed_sql_query("SELECT p.fp_id, t.ft_firstposterid, t.ft_firstpostername, t.ft_title, t.ft_id, t.ft_updated, s.fs_id, s.fs_title, s.fs_category
			FROM $db_forum_posts p, $db_forum_topics t, $db_forum_sections s
			WHERE 1 AND (p.fp_text LIKE '" . sed_sql_prep($sqlsearch) . "' OR t.ft_title LIKE '" . sed_sql_prep($sqlsearch) . "')
			AND p.fp_topicid=t.ft_id AND p.fp_sectionid=s.fs_id $sqlsections 
			GROUP BY t.ft_id ORDER BY ft_updated DESC
			LIMIT $cfg_maxitems");

			$items = sed_sql_numrows($sql);

			if ($items > 0) {
				while ($row = sed_sql_fetchassoc($sql)) {
					if (sed_auth('forums', $row['fs_id'], 'R')) {
						$t->assign(array(
							"PLUGIN_SEARCH_ROW_FORUM_SECTION" => sed_build_forums($row['fs_id'], $row['fs_title'], $row['fs_category'], TRUE),
							"PLUGIN_SEARCH_ROW_FORUM_TOPIC_TITLE" => sed_cc($row['ft_title']),
							"PLUGIN_SEARCH_ROW_FORUM_TOPIC_URL" => sed_url("forums", "m=posts&p=" . $row['fp_id'], "#" . $row['fp_id']),
							"PLUGIN_SEARCH_ROW_FORUM_DATE" => @date($cfg['dateformat'], $row['ft_updated'] + $usr['timezone'] * 3600),
							"PLUGIN_SEARCH_ROW_FORUM_POSTER" =>	sed_build_user($row['ft_firstposterid'], $row['ft_firstpostername'])
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

if (!$cfg['disable_page']) {

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

if (!$cfg['disable_forums']) {
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

if ($total_items == 0 && $a == 'search') {
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
