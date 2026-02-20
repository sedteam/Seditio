<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/inc/ratings.functions.php
Version=185
Type=Plugin
Description=Ratings API
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Builds ratings for an item
 *
 * @param string $code Item code
 * @param string $url Base url
 * @param int $display Display available for edit
 * @param bool $allow Enable or disable ratings an item
 * @return array
 */
function sed_build_ratings($code, $url, $display, $allow = true)
{
	global $db_ratings, $db_rated, $db_pages, $db_users, $cfg, $usr, $sys, $L;

	$code = sed_sql_prep($code);

	$ajax = sed_import('ajax', 'P', 'BOL');
	$ajax = ($cfg['ajax']) ? $ajax : false;

	list($usr['auth_read_rat'], $usr['auth_write_rat'], $usr['isadmin_rat']) = sed_auth('plug', 'ratings');

	if (!$usr['auth_read_rat']) {
		return array('', '');
	}

	if (is_array($url)) {
		$url_part = $url['part'];
		$url_params = $url['params'];
	} else {
		$url = str_replace('&amp;', '&', $url);
		$url_part = mb_substr($url, 0, mb_strpos($url, '.php'));
		$url_params = mb_substr($url, mb_strpos($url, '?') + 1, mb_strlen($url));
	}

	$ina = sed_import('ina', 'G', 'ALP');
	$newrate = sed_import('newrate', 'P', 'INT');

	$alr_rated = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM " . $db_rated . " WHERE rated_userid=" . (int)$usr['id'] . " AND rated_code = '" . $code . "'"), 0, 'COUNT(*)');

	if ($ina == 'send' && $newrate >= 1 && $newrate <= 10 && $usr['auth_write_rat'] && $alr_rated <= 0 && $allow) {

		if ($ajax && !sed_check_csrf()) {
			sed_die(true, 404);
			exit;
		}

		$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE rating_code='" . $code . "' LIMIT 1");

		if ($row = sed_sql_fetchassoc($sql)) {
			$rating_average = $row['rating_average'];
			$yetrated = true;

			if ($rating_average < 1) {
				$rating_average = 1;
			} elseif ($rating_average > 10) {
				$rating_average = 10;
			}

			$rating_cntround = round($rating_average, 0);
		} else {
			$yetrated = false;
			$rating_average = 0;
			$rating_cntround = 0;
		}

		$extp = sed_getextplugins('ratings.send.first');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}

		if (!$yetrated) {
			sed_sql_query("INSERT INTO $db_ratings (rating_code, rating_state, rating_average, rating_creationdate, rating_text) VALUES ('" . $code . "', 0, " . (int)$newrate . ", " . (int)$sys['now_offset'] . ", '') ");
		}

		sed_sql_query("INSERT INTO $db_rated (rated_code, rated_userid, rated_value) VALUES ('" . $code . "', " . (int)$usr['id'] . ", " . (int)$newrate . ")");
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='" . $code . "'");
		$rating_voters = sed_sql_result($sql, 0, "COUNT(*)");
		$ratingnewaverage = ($rating_average * ($rating_voters - 1) + $newrate) / ($rating_voters);
		sed_sql_query("UPDATE $db_ratings SET rating_average='" . sed_sql_prep($ratingnewaverage) . "' WHERE rating_code='" . $code . "'");

		$alr_rated = 1;

		if (mb_substr($code, 0, 1) == 'p') {
			$page_id = (int)mb_substr($code, 1, 10);
			sed_sql_query("UPDATE $db_pages SET page_rating='" . sed_sql_prep($ratingnewaverage) . "' WHERE page_id=" . $page_id);
		}

		$extp = sed_getextplugins('ratings.send.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}

		if (!$ajax) {
			sed_redirect(sed_url($url_part, $url_params . "&ratings=1&ina=added", "", true));
			exit;
		}
	}

	$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE rating_code='" . $code . "' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$rating_average = $row['rating_average'];
		$yetrated = true;

		if ($rating_average < 1) {
			$rating_average = 1;
		} elseif ($rating_average > 10) {
			$rating_average = 10;
		}

		$rating_cntround = round($rating_average, 0);
	} else {
		$yetrated = false;
		$rating_average = 0;
		$rating_cntround = 0;
	}

	$res = "<div class=\"rating-box\" id=\"rat-" . sed_cc($code) . "\"><ul class=\"rating s" . $rating_cntround . "\">\n";
	for ($i = 1; $i <= 10; $i++) {
		$onclick = "javascript:sedjs.ajaxbind({'url': '" . sed_url($url_part, $url_params . "&ratings=1&display=1&ina=send&ajax=1&newrate=" . $i . "&" . sed_xg()) . "', 'format':  'html', 'method':  'POST', 'update':  '#rat-" . sed_cc($code) . "', 'loading': '#rat-" . sed_cc($code) . "'});";
		$res .= "<li class=\"s" . $i . "\">" . sed_link('javascript:void(0);', $i . " - " . $L['rat_choice' . $i], array('onClick' => $onclick, 'title' => $i . " - " . $L['rat_choice' . $i])) . "</li>\n";
	}
	$res .= "</ul></div>";

	if (($usr['id'] == 0) || ($alr_rated > 0) || !$cfg['ajax']) {
		$res = sed_link(sed_url($url_part, $url_params . "&ratings=1"), "<img src=\"skins/" . $usr['skin'] . "/img/system/vote" . $rating_cntround . ".gif\" alt=\"\" />");
	}

	sed_ajax_flush($res, $ajax);

	if (!$display) {
		return array($res, '');
	}

	$votedcasted = ($ina == 'added') ? 1 : 0;
	$alreadyvoted = false;
	$rate_form = '';

	for ($i = 1; $i <= 10; $i++) {
		$rate_form .= sed_radio_item("newrate", $i, "<img src=\"skins/" . $usr['skin'] . "/img/system/vote" . $i . ".gif\" alt=\"\" /> " . $i . " - " . $L['rat_choice' . $i], $i) . "<br />";
	}

	if ($usr['id'] > 0) {
		$sql1 = sed_sql_query("SELECT rated_value FROM $db_rated WHERE rated_code='" . $code . "' AND rated_userid=" . (int)$usr['id'] . " LIMIT 1");

		if ($row1 = sed_sql_fetchassoc($sql1)) {
			$alreadyvoted = true;
			$rating_uservote = $L['rat_alreadyvoted'] . " (" . $row1['rated_value'] . ")";
		}
	}

	$skinfile = SED_ROOT . '/plugins/ratings/tpl/ratings.tpl';
	if (file_exists($skinfile)) {
		$t = new XTemplate($skinfile);
	} else {
		$t = new XTemplate(sed_skinfile('ratings'));
	}

	$extp = sed_getextplugins('ratings.main');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	if (!empty($error_string)) {
		$t->assign("RATINGS_ERROR_BODY", $error_string);
		$t->parse("RATINGS.RATINGS_ERROR");
	}

	if ($yetrated) {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='" . $code . "' ");
		$rating_voters = sed_sql_result($sql, 0, "COUNT(*)");
		$rating_average = $row['rating_average'];
		$rating_since = $L['rat_since'] . " " . sed_build_date($cfg['dateformat'], $row['rating_creationdate']);

		if ($rating_average < 1) {
			$rating_average = 1;
		} elseif ($rating_average > 10) {
			$rating_average = 10;
		}

		$rating = round($rating_average, 0);
		$rating_averageimg = "<img src=\"skins/" . $usr['skin'] . "/img/system/vote" . $rating . ".gif\" alt=\"\" />";
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='" . $code . "' ");
		$rating_voters = sed_sql_result($sql, 0, "COUNT(*)");
	} else {
		$rating_voters = 0;
		$rating_since = '';
		$rating_average = $L['rat_notyetrated'];
		$rating_averageimg = '';
	}

	$t->assign(array(
		"RATINGS_AVERAGE" => $rating_average,
		"RATINGS_AVERAGEIMG" => $rating_averageimg,
		"RATINGS_VOTERS" => $rating_voters,
		"RATINGS_SINCE" => $rating_since
	));

	if ($usr['id'] > 0 && $votedcasted && $allow) {
		$t->assign(array(
			"RATINGS_EXTRATEXT" => $L['rat_votecasted'],
		));
		$t->parse("RATINGS.RATINGS_EXTRA");
	} elseif ($usr['id'] > 0 && $alreadyvoted && $allow) {
		$t->assign(array(
			"RATINGS_EXTRATEXT" => $rating_uservote,
		));
		$t->parse("RATINGS.RATINGS_EXTRA");
	} elseif ($usr['id'] == 0 && $allow) {
		$t->assign(array(
			"RATINGS_EXTRATEXT" => $L['rat_registeredonly'],
		));
		$t->parse("RATINGS.RATINGS_EXTRA");
	} elseif ($usr['id'] > 0 && !$alreadyvoted && $allow) {
		$t->assign(array(
			"RATINGS_NEWRATE_FORM_SEND" => sed_url($url_part, $url_params . "&ratings=1&ina=send"),
			"RATINGS_NEWRATE_FORM_VOTER" => $usr['name'],
			"RATINGS_NEWRATE_FORM_RATE" => $rate_form
		));
		$t->parse("RATINGS.RATINGS_NEWRATE");
	}

	if (!$allow) {
		$t->assign(array(
			"RATINGS_DISABLETEXT" => $L['rat_disable']
		));
		$t->parse("RATINGS.RATINGS_DISABLE");
	}

	$extp = sed_getextplugins('ratings.tags');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	$t->parse("RATINGS");
	$res_display = $t->text("RATINGS");

	return array($res, $res_display);
}

/**
 * Returns URL for a ratings item by code (p/g/u/v).
 *
 * @param string $rating_code Item code (e.g. p123, g5, u1, v2)
 * @param string $anchor Optional fragment
 * @return string URL or empty if module disabled / unknown type
 */
function sed_ratings_item_url($rating_code, $anchor = '')
{
	global $db_pages, $sys;

	$type = mb_substr($rating_code, 0, 1);
	$value = mb_substr($rating_code, 1);

	$params_ratings = '&ratings=1';

	switch ($type) {
		case 'p':
			if (sed_module_active('page')) {
				$sql = sed_sql_query("SELECT page_id, page_cat, page_alias FROM $db_pages WHERE page_id=" . (int)$value . " LIMIT 1");
				if (sed_sql_numrows($sql) > 0) {
					$row = sed_sql_fetchassoc($sql);
					$sys['catcode'] = $row['page_cat'];
					$params = (empty($row['page_alias'])) ? "id=" . $row['page_id'] . $params_ratings : "al=" . $row['page_alias'] . $params_ratings;
					return sed_url("page", $params, $anchor);
				}
				return sed_url("page", "id=" . $value . $params_ratings, $anchor);
			}
			break;
		case 'g':
			if (sed_module_active('gallery')) {
				return sed_url("gallery", "id=" . $value . $params_ratings, $anchor);
			}
			break;
		case 'u':
			if (sed_module_active('users')) {
				return sed_url("users", "m=details&id=" . $value . $params_ratings, $anchor);
			}
			break;
		case 'v':
			if (sed_module_active('polls')) {
				return sed_url("polls", "id=" . $value . $params_ratings, $anchor);
			}
			break;
		default:
			return '';
	}
	return '';
}
