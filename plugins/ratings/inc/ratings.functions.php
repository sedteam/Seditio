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
 * Returns label array for rating tooltips by max rating value
 *
 * @param int $maxRating Max rating (3, 5, 6, 10, 20)
 * @return array
 */
function sed_ratings_get_labels($maxRating)
{
	global $L;

	$labels = array();
	$maxRating = (int)$maxRating;

	if ($maxRating <= 0) {
		return $labels;
	}

	/* Use rat_choice1..N for exact match, or rat_labels_N set */
	if (isset($L['rat_labels_' . $maxRating]) && is_array($L['rat_labels_' . $maxRating])) {
		return $L['rat_labels_' . $maxRating];
	}

	for ($i = 1; $i <= $maxRating; $i++) {
		$key = 'rat_choice' . $i;
		if (isset($L[$key])) {
			$labels[] = $L[$key];
		} else {
			$labels[] = (string)$i;
		}
	}

	return $labels;
}

/**
 * Processes a rating vote
 *
 * @param string $code Item code
 * @param int|float $newrate Rating value
 * @param int $maxRating Maximum allowed rating
 * @return bool
 */
function sed_ratings_vote($code, $newrate, $maxRating)
{
	global $db_ratings, $db_rated, $db_pages, $cfg, $usr, $sys;

	$code = sed_sql_prep($code);
	$newrate = (float)$newrate;
	$maxRating = (int)$maxRating;

	if ($newrate < 1 || $newrate > $maxRating) {
		return false;
	}

	$newrate = round($newrate * 2) / 2;
	if ($newrate < 1) {
		$newrate = 1;
	}
	$newrateInt = (int)round($newrate);

	$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE rating_code='" . $code . "' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$rating_average = (float)$row['rating_average'];
		$yetrated = true;
		$rating_average = max(1, min($maxRating, $rating_average));
	} else {
		$yetrated = false;
		$rating_average = 0;
	}

	$extp = sed_getextplugins('ratings.send.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	if (!$yetrated) {
		sed_sql_query("INSERT INTO $db_ratings (rating_code, rating_state, rating_average, rating_creationdate, rating_text) VALUES ('" . $code . "', 0, " . (float)$newrate . ", " . (int)$sys['now_offset'] . ", '') ");
	}

	sed_sql_query("INSERT INTO $db_rated (rated_code, rated_userid, rated_value) VALUES ('" . $code . "', " . (int)$usr['id'] . ", " . $newrateInt . ")");
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='" . $code . "'");
	$rating_voters = (int)sed_sql_result($sql, 0, "COUNT(*)");
	$ratingnewaverage = ($rating_average * ($rating_voters - 1) + $newrate) / $rating_voters;
	sed_sql_query("UPDATE $db_ratings SET rating_average='" . sed_sql_prep($ratingnewaverage) . "' WHERE rating_code='" . $code . "'");

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

	return true;
}

/**
 * Renders a read-only star-rating-svg widget (for list rows, etc.)
 *
 * @param float $rating_value Current rating
 * @param string $code Optional item code
 * @return string HTML
 */
function sed_ratings_render_readonly($rating_value, $code = '')
{
	global $cfg;

	$cfg_plug = isset($cfg['plugin']['ratings']) ? $cfg['plugin']['ratings'] : array();
	$totalStars = isset($cfg_plug['maxstars']) ? (int)$cfg_plug['maxstars'] : 10;
	$ratingstep = isset($cfg_plug['ratingstep']) ? $cfg_plug['ratingstep'] : '1';
	$useFullStars = ($ratingstep == '1' || $ratingstep === 1);
	$valueMultiplier = ($ratingstep == '0.5') ? 2 : 1;

	$attrs = sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, (float)$rating_value, true);
	$cls = 'star-rating-widget ratings-star-widget jq-stars';
	$id = $code ? ' id="rat-' . sed_cc($code) . '"' : '';
	return '<span class="rating-box"' . $id . '><span class="' . $cls . '"' . $attrs . '></span></span>';
}

/**
 * Builds data attributes string for star-rating-svg widget
 *
 * @param array $cfg_plug Plugin ratings config
 * @param int $totalStars
 * @param int $valueMultiplier
 * @param bool $useFullStars
 * @param float $initialRating
 * @param bool $readOnly
 * @return string
 */
function sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, $initialRating = 0, $readOnly = false)
{
	$attrs = array(
		'data-total-stars' => $totalStars,
		'data-value-multiplier' => $valueMultiplier,
		'data-use-full-stars' => $useFullStars ? 'true' : 'false',
		'data-initial-rating' => (float)$initialRating,
		'data-read-only' => $readOnly ? 'true' : 'false',
		'data-star-size' => isset($cfg_plug['starsize']) ? (int)$cfg_plug['starsize'] : 30,
		'data-star-shape' => isset($cfg_plug['starshape']) ? sed_cc($cfg_plug['starshape']) : 'straight',
		'data-stroke-width' => isset($cfg_plug['strokewidth']) ? (int)$cfg_plug['strokewidth'] : 0,
		'data-stroke-color' => isset($cfg_plug['strokecolor']) ? sed_cc($cfg_plug['strokecolor']) : '#333',
		'data-empty-color' => isset($cfg_plug['emptycolor']) ? sed_cc($cfg_plug['emptycolor']) : 'lightgray',
		'data-hover-color' => isset($cfg_plug['hovercolor']) ? sed_cc($cfg_plug['hovercolor']) : 'orange',
		'data-active-color' => isset($cfg_plug['activecolor']) ? sed_cc($cfg_plug['activecolor']) : 'gold',
		'data-rated-color' => isset($cfg_plug['ratedcolor']) ? sed_cc($cfg_plug['ratedcolor']) : 'crimson',
		'data-use-gradient' => !empty($cfg_plug['usegradient']) ? 'true' : 'false',
	);

	$out = '';
	foreach ($attrs as $k => $v) {
		$out .= ' ' . $k . '="' . $v . '"';
	}
	return $out;
}

/**
 * Builds ratings for an item
 *
 * @param string $code Item code
 * @param string|array $url Base url
 * @param int $display Display available for edit
 * @param bool $allow Enable or disable ratings an item
 * @return array
 */
function sed_build_ratings($code, $url, $display, $allow = true)
{
	global $db_ratings, $db_rated, $db_pages, $db_users, $cfg, $usr, $sys, $L;

	$code = sed_sql_prep($code);
	$cfg_plug = isset($cfg['plugin']['ratings']) ? $cfg['plugin']['ratings'] : array();

	$totalStars = isset($cfg_plug['maxstars']) ? (int)$cfg_plug['maxstars'] : 10;
	$ratingstep = isset($cfg_plug['ratingstep']) ? $cfg_plug['ratingstep'] : '1';
	$useFullStars = ($ratingstep == '1' || $ratingstep === 1);
	$valueMultiplier = ($ratingstep == '0.5') ? 2 : 1;
	$maxRating = $totalStars * $valueMultiplier;

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
	$newrate = sed_import('newrate', 'P', 'NUM');

	$alr_rated = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM " . $db_rated . " WHERE rated_userid=" . (int)$usr['id'] . " AND rated_code = '" . $code . "'"), 0, 'COUNT(*)');

	if ($ina == 'send' && $newrate >= 1 && $newrate <= $maxRating && $usr['auth_write_rat'] && $alr_rated <= 0 && $allow) {

		if ($ajax && !sed_check_csrf()) {
			sed_die(true, 404);
			exit;
		}

		sed_ratings_vote($code, $newrate, $maxRating);
		$alr_rated = 1;

		if (!$ajax) {
			sed_redirect(sed_url($url_part, $url_params . "&ratings=1&ina=added", "", true));
			exit;
		}
	}

	$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE rating_code='" . $code . "' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$rating_average = (float)$row['rating_average'];
		$yetrated = true;
		$rating_average = max(0, min($maxRating, $rating_average));
	} else {
		$yetrated = false;
		$rating_average = 0;
	}

	$ajaxUrl = sed_url('plug', 'ajx=ratings&code=' . urlencode($code) . '&url_part=' . urlencode($url_part) . '&url_params=' . urlencode($url_params) . '&' . sed_xg(), '', false, false);
	$attrs = sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, $rating_average, false);

	$gradientStart = isset($cfg_plug['gradient_start']) ? sed_cc($cfg_plug['gradient_start']) : '#FEF7CD';
	$gradientEnd = isset($cfg_plug['gradient_end']) ? sed_cc($cfg_plug['gradient_end']) : '#FF9511';

	$canVoteAjax = ($usr['id'] > 0 && $alr_rated <= 0 && $cfg['ajax'] && $allow);
	$canVoteForm = ($usr['id'] > 0 && $alr_rated <= 0 && $allow);
	$isReadOnly = ($usr['id'] == 0) || ($alr_rated > 0) || !$cfg['ajax'] || !$allow;

	if ($isReadOnly) {
		$attrsReadOnly = sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, $rating_average, true);
		$res = '<div class="rating-box" id="rat-' . sed_cc($code) . '"><div class="star-rating-widget ratings-star-widget jq-stars"' . $attrsReadOnly . '></div></div>';
		if ($canVoteForm) {
			$res = '<a href="' . sed_url($url_part, $url_params . "&ratings=1") . '" class="rating-box-link">' . $res . '</a>';
		}
	} else {
		$res = '<div class="rating-box" id="rat-' . sed_cc($code) . '"><div class="star-rating-widget ratings-star-widget jq-stars" data-code="' . sed_cc($code) . '" data-ajax-url="' . sed_cc($ajaxUrl) . '"' . $attrs . '></div></div>';
	}

	sed_ajax_flush($res, $ajax);

	if (!$display) {
		return array($res, '');
	}

	$votedcasted = ($ina == 'added') ? 1 : 0;
	$alreadyvoted = false;
	$rating_uservote = '';

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
		$rating_average = max(0, min($maxRating, (float)$rating_average));
		$rating_averageimg = '<div class="star-rating-widget ratings-star-widget jq-stars"' . sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, $rating_average, true) . '></div>';
	} else {
		$rating_voters = 0;
		$rating_since = '';
		$rating_average = $L['rat_notyetrated'];
		$rating_averageimg = '';
	}

	$ratingLabels = sed_ratings_get_labels($maxRating);
	$ratingLabelsJson = !empty($ratingLabels) ? json_encode($ratingLabels) : '[]';

	$t->assign(array(
		"RATINGS_AVERAGE" => $rating_average,
		"RATINGS_AVERAGEIMG" => $rating_averageimg,
		"RATINGS_VOTERS" => $rating_voters,
		"RATINGS_SINCE" => isset($rating_since) ? $rating_since : ''
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
		$formAttrs = sed_ratings_widget_attrs($cfg_plug, $totalStars, $valueMultiplier, $useFullStars, 0, false);
		$t->assign(array(
			"RATINGS_NEWRATE_FORM_SEND" => sed_url($url_part, $url_params . "&ratings=1&ina=send"),
			"RATINGS_CODE" => $code,
			"RATINGS_TOTALSTARS" => $totalStars,
			"RATINGS_VALUEMULTIPLIER" => $valueMultiplier,
			"RATINGS_USEFULLSTARS" => $useFullStars ? 'true' : 'false',
			"RATINGS_WIDGET_ATTRS" => $formAttrs,
			"RATINGS_GRADIENT_START" => $gradientStart,
			"RATINGS_GRADIENT_END" => $gradientEnd,
			"RATINGS_LABELS_JSON" => $ratingLabelsJson,
			"RATINGS_AJAX_URL" => $ajaxUrl,
			"RATINGS_NEWRATE_FORM_VOTER" => $usr['name'],
		));
		if ($cfg['ajax']) {
			$t->parse("RATINGS.RATINGS_NEWRATE.RATINGS_NEWRATE_AJAXMODE");
		} else {
			$t->parse("RATINGS.RATINGS_NEWRATE.RATINGS_NEWRATE_FORMMODE.RATINGS_SUBMIT_BTN");
			$t->parse("RATINGS.RATINGS_NEWRATE.RATINGS_NEWRATE_FORMMODE");
		}
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
 * @param string $rating_code Item code (e.g. p123, g5, u1, v2, lnews)
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
		case 'l':
			if (sed_module_active('page')) {
				$sys['catcode'] = $value;
				return sed_url("page", "c=" . $value . $params_ratings, $anchor);
			}
			break;
		default:
			return '';
	}
	return '';
}
