<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.statistics.hits.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['auth_read']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=hits")] =  $L['Hits'];

$admintitle = $L['Hits'];

$f = sed_import('f', 'G', 'ALP', 10);
$v = sed_import('v', 'G', 'TXT', 8);

$t = new XTemplate(sed_skinfile('admin.hits', false, true));

if ($f == 'year' || $f == 'month') {
	$adminpath[] = array(sed_url("admin", "m=hits&f=" . $f . "&v=" . $v), "(" . $v . ")");
	$sql = sed_sql_query("SELECT * FROM $db_stats WHERE stat_name LIKE '$v%' ORDER BY stat_name DESC");

	while ($row = sed_sql_fetchassoc($sql)) {
		$y = mb_substr($row['stat_name'], 0, 4);
		$m = mb_substr($row['stat_name'], 5, 2);
		$d = mb_substr($row['stat_name'], 8, 2);
		$dat = @date('Y-m-d D', mktime(0, 0, 0, $m, $d, $y));
		$hits_d[$dat] = $row['stat_value'];
	}

	$hits_d_max = max($hits_d);

	foreach ($hits_d as $day => $hits) {
		$percentbar = floor(($hits / $hits_d_max) * 100);

		$t->assign(array(
			"HITS_ROW_DAY" => $day,
			"HITS_ROW_HITS" => $hits,
			"HITS_ROW_PERCENTBAR" => $percentbar
		));

		$t->parse("ADMIN_HITS.YEAR_OR_MONTH.HITS_ROW");
	}

	$t->parse("ADMIN_HITS.YEAR_OR_MONTH");
} else {
	$sql = sed_sql_query("SELECT * FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_name DESC");
	$sqlmax = sed_sql_query("SELECT * FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_value DESC LIMIT 1");
	$rowmax = sed_sql_fetchassoc($sqlmax);
	$max_date = $rowmax['stat_name'];
	$max_hits = $rowmax['stat_value'];

	$L['adm_maxhits'] = (empty($L['adm_maxhits'])) ? "Maximum hitcount was reached %1\$s, %2\$s pages displayed this day." : $L['adm_maxhits'];

	$ii = 0;
	$hits_m = array();
	$hits_w = array();
	$hits_y = array();

	while ($row = sed_sql_fetchassoc($sql)) {
		$y = mb_substr($row['stat_name'], 0, 4);
		$m = mb_substr($row['stat_name'], 5, 2);
		$d = mb_substr($row['stat_name'], 8, 2);
		$w = @date('W', mktime(0, 0, 0, $m, $d, $y));
		$hits_w[$y . "-W" . $w] = isset($hits_w[$y . "-W" . $w]) ? $hits_w[$y . "-W" . $w] + $row['stat_value'] : $row['stat_value'];
		$hits_m[$y . "-" . $m] = isset($hits_m[$y . "-" . $m]) ? $hits_m[$y . "-" . $m] + $row['stat_value'] : $row['stat_value'];
		$hits_y[$y] = isset($hits_y[$y]) ? $hits_y[$y] + $row['stat_value'] : $row['stat_value'];
	}

	$hits_w_max = max($hits_w);
	$hits_m_max = max($hits_m);
	$hits_y_max = max($hits_y);

	foreach ($hits_y as $year => $hits) {
		$percentbar = floor(($hits / $hits_y_max) * 100);

		$t->assign(array(
			"HITS_YEAR_ROW_URL" => sed_url('admin', 'm=hits&f=year&v=' . $year),
			"HITS_YEAR_ROW_YEAR" => $year,
			"HITS_YEAR_ROW_HITS" => $hits,
			"HITS_YEAR_ROW_PERCENTBAR" => $percentbar
		));

		$t->parse("ADMIN_HITS.DEFAULT.HITS_YEAR_ROW");
	}

	foreach ($hits_m as $month => $hits) {
		$percentbar = floor(($hits / $hits_m_max) * 100);

		$t->assign(array(
			"HITS_MONTH_ROW_URL" => sed_url('admin', 'm=hits&f=month&v=' . $month),
			"HITS_MONTH_ROW_MONTH" => $month,
			"HITS_MONTH_ROW_HITS" => $hits,
			"HITS_MONTH_ROW_PERCENTBAR" => $percentbar
		));

		$t->parse("ADMIN_HITS.DEFAULT.HITS_MONTH_ROW");
	}

	foreach ($hits_w as $week => $hits) {
		$ex = explode("-W", $week);
		$percentbar = floor(($hits / $hits_w_max) * 100);

		$t->assign(array(
			"HITS_WEEK_ROW_WEEK" => $week,
			"HITS_WEEK_ROW_HITS" => $hits,
			"HITS_WEEK_ROW_PERCENTBAR" => $percentbar
		));

		$t->parse("ADMIN_HITS.DEFAULT.HITS_WEEK_ROW");
	}

	$t->assign(array(
		"HITS_MAXHITS" => sprintf($L['adm_maxhits'], $max_date, $max_hits)
	));

	$t->parse("ADMIN_HITS.DEFAULT");
}

$t->assign("ADMIN_HITS_TITLE", $admintitle);

$t->parse("ADMIN_HITS");

$adminmain .= $t->text("ADMIN_HITS");
