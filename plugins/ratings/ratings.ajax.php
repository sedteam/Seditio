<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.ajax.php
Version=185
Type=Plugin
Description=Ratings AJAX handler
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=AjaxRatings
File=ratings.ajax
Hooks=ajax
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_ratings, $db_rated, $cfg, $usr;

$code = sed_import('code', 'G', 'TXT') ?: sed_import('code', 'P', 'TXT');
$newrate = sed_import('newrate', 'G', 'NUM') ?: sed_import('newrate', 'P', 'NUM');
$url_part = sed_import('url_part', 'G', 'TXT') ?: sed_import('url_part', 'P', 'TXT');
$url_params = sed_import('url_params', 'G', 'TXT') ?: sed_import('url_params', 'P', 'TXT');

$json_err = function($msg) {
	sed_ajax_flush(json_encode(['ok' => false, 'error' => $msg]), true, 'application/json');
};

if (empty($code) || empty($newrate)) {
	$json_err('Invalid request');
	exit;
}

if (!sed_check_csrf()) {
	$json_err('CSRF error');
	exit;
}

$cfg_plug = isset($cfg['plugin']['ratings']) ? $cfg['plugin']['ratings'] : array();
$totalStars = isset($cfg_plug['maxstars']) ? (int)$cfg_plug['maxstars'] : 10;
$ratingstep = isset($cfg_plug['ratingstep']) ? $cfg_plug['ratingstep'] : '1';
$valueMultiplier = ($ratingstep == '0.5') ? 2 : 1;
$maxRating = $totalStars * $valueMultiplier;

list($usr['auth_read_rat'], $usr['auth_write_rat'], $usr['isadmin_rat']) = sed_auth('plug', 'ratings');

if (!$usr['auth_write_rat'] || $usr['id'] <= 0) {
	$json_err('Access denied');
	exit;
}

$alr_rated = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM " . $db_rated . " WHERE rated_userid=" . (int)$usr['id'] . " AND rated_code = '" . sed_sql_prep($code) . "'"), 0, 'COUNT(*)');

if ($alr_rated > 0) {
	$sql = sed_sql_query("SELECT rating_average FROM " . $db_ratings . " WHERE rating_code='" . sed_sql_prep($code) . "' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	$avg = $row ? (float)$row['rating_average'] : 0;
	$cnt = (int)sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM " . $db_rated . " WHERE rated_code='" . sed_sql_prep($code) . "'"), 0, 'COUNT(*)');
	sed_ajax_flush(json_encode(['ok' => true, 'average' => $avg, 'voters' => $cnt]), true, 'application/json');
	exit;
}

if (sed_ratings_vote($code, $newrate, $maxRating)) {
	$sql = sed_sql_query("SELECT rating_average FROM " . $db_ratings . " WHERE rating_code='" . sed_sql_prep($code) . "' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	$avg = $row ? (float)$row['rating_average'] : 0;
	$cnt = (int)sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM " . $db_rated . " WHERE rated_code='" . sed_sql_prep($code) . "'"), 0, 'COUNT(*)');
	sed_ajax_flush(json_encode(['ok' => true, 'average' => $avg, 'voters' => $cnt]), true, 'application/json');
} else {
	$json_err('Vote error');
}
exit;
