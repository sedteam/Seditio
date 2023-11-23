<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/slider/slider.php
Version=179
Updated=2022-sep-20
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=slider
Part=main
File=slider
Hooks=index.tags
Tags=index.tpl:{PLUGIN_SLIDER}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$sliderlimit = (int)$cfg['plugin']['slider']['maxslides'];

$cfg['plu_mask_slider'] = empty($cfg['plu_mask_slider']) ? "<div class=\"slide-item\" style=\"background-image: url(datas/users/%5\$s);\"></div>" : $cfg['plu_mask_slider'];
// %1\$s = Link to the category
// %2\$s = Link to the page
// %3\$s = Page title
// %4\$s = Page description
// %5\$s = Page first thumb
// %6\$s = Date

$cfg['plu_mask_pages_date'] = "<span class=\"sdate\">{d-m} {H:i}</span>";

$slider = "<div id=\"slider\">";

$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";

$sql = sed_sql_query("SELECT p.page_id, p.page_alias, p.page_cat, p.page_title, p.page_desc, p.page_date, p.page_text, p.page_thumb, 
					p.page_ownerid, p.page_count, p.page_comcount, u.user_id, u.user_name, u.user_maingrp, u.user_avatar 
					FROM $db_pages AS p LEFT JOIN $db_users AS u ON u.user_id = p.page_ownerid 
					WHERE p.page_state = 0 AND p.page_cat NOT LIKE 'system' AND p.page_slider = 1 
					ORDER BY p.page_date DESC LIMIT $sliderlimit");

if (sed_sql_numrows($sql) > 0) {
	while ($row = sed_sql_fetchassoc($sql)) {
		if (sed_auth('page', $row['page_cat'], 'R')) {
			$sys['catcode'] = $row['page_cat']; //new in v175
			$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id']) : sed_url("page", "al=" . $row['page_alias']);
			$row['page_pageurlcom'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id'] . $pcomments) : sed_url("page", "al=" . $row['page_alias'] . $pcomments);

			$t->assign(array(
				"SLIDER_ROW_URL" => $row['page_pageurl'],
				"SLIDER_ROW_ID" => $row['page_id'],
				"SLIDER_ROW_CAT" => $row['page_cat'],
				"SLIDER_ROW_CATTITLE" => $sed_cat[$row['page_cat']]['title'],
				"SLIDER_ROW_CATPATH" => sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"),
				"SLIDER_ROW_SHORTTITLE" => sed_cutstring($row['page_title'], 50),
				"SLIDER_ROW_TITLE" => $row['page_title'],
				"SLIDER_ROW_DESC" => $row['page_desc'],
				"SLIDER_ROW_TEXT" => $row['page_text'],
				"SLIDER_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['page_date'], $cfg['plu_mask_pages_date']),
				"SLIDER_ROW_AUTHOR" => sed_cc($row['user_name']),
				"SLIDER_ROW_USERURL" => sed_url("users", "m=details&id=" . $row['page_ownerid']),
				"SLIDER_ROW_USER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name']), $row['user_maingrp']),
				"SLIDER_ROW_COUNT" => $row['page_count'],
				"SLIDER_ROW_COMMENTS_URL" => $row['page_pageurlcom'],
				"SLIDER_ROW_COMMENTS_COUNT" => $row['page_comcount'],
				"SLIDER_ROW_AVATAR" => sed_build_userimage($row['user_avatar'])
			));

			// ------- thumb

			$row['page_first_thumb'] = "";
			if (!empty($row['page_thumb'])) {
				$page_thumbs_array = rtrim($row['page_thumb']);
				if ($page_thumbs_array[mb_strlen($page_thumbs_array) - 1] == ';') {
					$page_thumbs_array = mb_substr($page_thumbs_array, 0, -1);
				}
				$page_thumbs_array = explode(";", $page_thumbs_array);
				if (count($page_thumbs_array) > 0) {
					$row['page_first_thumb'] = $page_thumbs_array[0];
					$t->assign("SLIDER_ROW_THUMB", $page_thumbs_array[0]);
					$t->parse("MAIN.SLIDER.SLIDER_ROW.SLIDER_ROW_THUMB");
				}
			} else {
				$row['page_first_thumb'] = sed_cc($row['page_thumb']);
				$t->assign("SLIDER_ROW_THUMB", sed_cc($row['page_thumb']));
			}

			// -------		

			$t->parse("MAIN.SLIDER.SLIDER_ROW");

			/* old result view use mask */
			$slider .= sprintf(
				$cfg['plu_mask_slider'],
				"<a href=\"" . sed_url("list", "c=" . $row['page_cat']) . "\">" . $sed_cat[$row['page_cat']]['title'] . "</a>",
				"<a href=\"" . $row['page_pageurl'] . "\">" . sed_cc(sed_cutstring(stripslashes($row['page_title']), 50)) . "</a>",
				sed_cc(sed_cutstring(stripslashes($row['page_title']), 50)),
				strip_tags($row['page_desc']),
				$row['page_first_thumb'],
				sed_build_date($cfg['formatyearmonthday'], $row['page_date'], $cfg['plu_mask_pages_date'])
			);
		}
	}

	$t->parse("MAIN.SLIDER");

	$slider .= "</div>";

	$t->assign(array(
		"PLUGIN_SLIDER" => $slider
	));
} else {
	$t->assign(array(
		"SLIDER_NOACTIVE" => "home-noslider"
	));
}
