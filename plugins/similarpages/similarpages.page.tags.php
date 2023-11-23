<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/similarpages/similarpages.page.tags.php
Version=170
Updated=2012-feb-26
Type=Plugin
Author=Amro
Description=The plugin displays a list of similar pages
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=similarpages
Part=main
File=similarpages.page.tags
Hooks=page.tags
Tags=page.tpl {PLUGIN_SIMILAR_PAGES}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$cfg['plu_mask_similar_pages'] = "%3\$s" . " : " . "%1\$s" . " " . $cfg['separator'] . " " . "%2\$s" . "<br />";
// %1\$s = Link to the category
// %2\$s = Link to the page
// %3\$s = Date

$sim_relevance = $cfg['plugin']['similarpages']['sim_relevance'];
$sim_maxcount = $cfg['plugin']['similarpages']['sim_maxcount'];
$sim_category = $cfg['plugin']['similarpages']['sim_category'];

$plu_empty = $L['None'] . "<br />";

/* ================== FUNCTIONS ================== */

function sed_get_similarpages($sim_relevance, $sim_maxcount, $sim_category, $mask)
{
	global $L, $t, $pag, $db_pages, $db_users, $usr, $cfg, $sed_cat, $plu_empty;

	$sql_cat = "";
	$res = "";

	if (!empty($sim_category)) {
		$sim_category = explode(',', $sim_category);
		foreach ($sim_category as $k => $i) {
			$i = rtrim(trim($i));
			$mtch = $sed_cat[$i]['path'] . ".";
			$mtchlen = strlen($mtch);
			$catsub = array();
			$catsub[] = $i;
			@reset($sed_cat);
			foreach ($sed_cat as $j => $x) {
				if (substr($x['path'], 0, $mtchlen) == $mtch) {
					$catsub[] = $j;
				}
			}
		}
		$sql_cat = "AND page_cat IN ('" . implode("','", $catsub) . "')";
	}

	$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";

	$sql = sed_sql_query("SELECT p.page_id, p.page_alias, p.page_cat, p.page_title, p.page_date, p.page_ownerid, p.page_count,
						p.page_comcount, p.page_thumb, u.user_id, u.user_name, u.user_maingrp, u.user_avatar 
						FROM $db_pages AS p LEFT JOIN $db_users AS u ON u.user_id = p.page_ownerid 
						WHERE p.page_state=0 AND p.page_cat NOT LIKE 'system' " . $sql_cat . " AND p.page_id != " . $pag['page_id'] . " AND MATCH (p.page_title) 
                        AGAINST ('" . sed_sql_prep($pag['page_title']) . "') > $sim_relevance 
                        ORDER BY p.page_date DESC LIMIT $sim_maxcount");

	if (sed_sql_numrows($sql) > 0) {
		$jj = 0;
		while ($row = sed_sql_fetchassoc($sql)) {

			if (sed_auth('page', $row['page_cat'], 'R')) {
				$jj++;
				$sys['catcode'] = $row['page_cat']; //new in v175
				$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id']) : sed_url("page", "al=" . $row['page_alias']);
				$row['page_pageurlcom'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id'] . $pcomments) : sed_url("page", "al=" . $row['page_alias'] . $pcomments);

				$t->assign(array(
					"SIMILARPAGES_ROW_URL" => $row['page_pageurl'],
					"SIMILARPAGES_ROW_ID" => $row['page_id'],
					"SIMILARPAGES_ROW_CAT" => $row['page_cat'],
					"SIMILARPAGES_ROW_CATTITLE" => $sed_cat[$row['page_cat']]['title'],
					"SIMILARPAGES_ROW_CATPATH" => sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"),
					"SIMILARPAGES_ROW_SHORTTITLE" => sed_cutstring($row['page_title'], 50),
					"SIMILARPAGES_ROW_TITLE" => $row['page_title'],
					"SIMILARPAGES_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['page_date'], $cfg['plu_mask_pages_date']),
					"SIMILARPAGES_ROW_AUTHOR" => sed_cc($row['user_name']),
					"SIMILARPAGES_ROW_OWNER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name']), $row['user_maingrp']),
					"SIMILARPAGES_ROW_OWNER_AVATAR" => sed_build_userimage($row['user_avatar']),
					"SIMILARPAGES_ROW_USERURL" => sed_url("users", "m=details&id=" . $row['page_ownerid']),
					"SIMILARPAGES_ROW_USER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name']), $row['user_maingrp']),
					"SIMILARPAGES_ROW_COUNT" => $row['page_count'],
					"SIMILARPAGES_ROW_COMMENTS_URL" => $row['page_pageurlcom'],
					"SIMILARPAGES_ROW_COMMENTS_COUNT" => $row['page_comcount']
				));

				// ------- thumb

				if (!empty($row['page_thumb'])) {
					$page_thumbs_array = rtrim($row['page_thumb']);
					if ($page_thumbs_array[mb_strlen($page_thumbs_array) - 1] == ';') {
						$page_thumbs_array = mb_substr($page_thumbs_array, 0, -1);
					}
					$page_thumbs_array = explode(";", $page_thumbs_array);
					if (count($page_thumbs_array) > 0) {
						$t->assign("SIMILARPAGES_ROW_THUMB", $page_thumbs_array[0]);
						$t->parse("MAIN.SIMILARPAGES.SIMILARPAGES_ROW.SIMILARPAGES_ROW_THUMB");
					}
				} else {
					$t->assign("SIMILARPAGES_ROW_THUMB", sed_cc($row['page_thumb']));
				}

				// -------

				$t->parse("MAIN.SIMILARPAGES.SIMILARPAGES_ROW");

				/* old result view use mask */
				$res .= sprintf(
					$mask,
					"<a href=\"" . sed_url("list", "c=" . $row['page_cat']) . "\">" . $sed_cat[$row['page_cat']]['title'] . "</a>",
					"<a href=\"" . $row['page_pageurl'] . "\">" . sed_cc(sed_cutstring(stripslashes($row['page_title']), 36)) . "</a>",
					date($cfg['formatyearmonthday'], $row['page_date'] + $usr['timezone'] * 3600)
				);
			}
		}

		if ($jj > 0) $t->parse("MAIN.SIMILARPAGES");
	}

	$res = (empty($res)) ? $plu_empty : $res;

	return ($res);
}

/* ============= */

if ($sim_maxcount > 0 && !$cfg['disable_page']) {
	$t->assign(array(
		"PLUGIN_SIMILAR_PAGES" => sed_get_similarpages($sim_relevance, $sim_maxcount, $sim_category, $cfg['plu_mask_similar_pages'])
	));
}
