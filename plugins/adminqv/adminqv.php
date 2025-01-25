<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/adminqv/adminqv.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=adminqv
Part=main
File=adminqv
Hooks=admin.home
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once(SED_ROOT . '/plugins/adminqv/lang/adminqv.' . $usr['lang'] . '.lang.php');

$timeback = $sys['now_offset'] - (7 * 86400); // 7 days
$timeback_stats = 15; // 15 days

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_regdate>'$timeback'");
$newusers = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_date >'$timeback'");
$newpages = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_creationdate>'$timeback'");
$newtopics = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_updated>'$timeback'");
$newposts = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_com WHERE com_date>'$timeback'");
$newcomments = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_date>'$timeback'");
$newpms = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT * FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_name DESC LIMIT " . $timeback_stats);
while ($row = sed_sql_fetchassoc($sql)) {
	$ty = mb_substr($row['stat_name'], 0, 4);
	$tm = mb_substr($row['stat_name'], 5, 2);
	$td = mb_substr($row['stat_name'], 8, 2);
	$dat = @date('d D', mktime(0, 0, 0, $tm, $td, $ty));
	$hits_d[$dat] = $row['stat_value'];
}

$hits_d_max = max($hits_d);

$sql = sed_sql_query("SHOW TABLES");

while ($row = sed_sql_fetchrow($sql)) {
	$table_name = $row[0];
	$status = sed_sql_query("SHOW TABLE STATUS LIKE '$table_name'");
	$status1 = sed_sql_fetcharray($status);
	$tables[] = $status1;
}

$total_length = 0;
$total_rows = 0;
$total_index_length = 0;
$total_fragmented = 0;
$total_data_length = 0;

foreach ($tables as $i => $dat) {
	$table_length = $dat['Index_length'] + $dat['Data_length'];
	$total_length += $table_length;
	$total_rows += $dat['Rows'];
	$total_index_length += $dat['Index_length'];
	$total_fragmented += $dat['Data_free'];
	$total_data_length += $dat['Data_length'];
}

$qv = new XTemplate(SED_ROOT . '/plugins/adminqv/adminqv.tpl');

if (!$cfg['disablereg']) {

	$qv->assign(array(
		"QV_NEWUSERS" => $newusers,
		"QV_NEWUSERS_URL" => sed_url("users", "f=all&s=regdate&w=desc")
	));
	$qv->parse("ADMIN_QV.ADMIN_QV_NEWUSERS");
}

if (!$cfg['disable_page']) {

	$qv->assign(array(
		"QV_NEWPAGES" => $newpages,
		"QV_NEWPAGES_URL" => sed_url("admin", "m=page")
	));
	$qv->parse("ADMIN_QV.ADMIN_QV_NEWPAGES");
}

if (!$cfg['disable_forums']) {

	$qv->assign(array(
		"QV_NEWTOPICS" => $newtopics,
		"QV_NEWPOSTS" => $newposts,
		"QV_NEWFORUMS_URL" => sed_url("forums")
	));
	$qv->parse("ADMIN_QV.ADMIN_QV_NEWONFORUMS");
}

if (!$cfg['disable_comments']) {

	$qv->assign(array(
		"QV_NEWCOMMENTS" => $newcomments,
		"QV_NEWCOMMENTS_URL" => sed_url("admin", "m=comments")
	));
	$qv->parse("ADMIN_QV.ADMIN_QV_NEWCOMMENTS");
}

if (!$cfg['disable_pm']) {

	$qv->assign(array(
		"QV_NEWPMS" => $newcomments
	));
	$qv->parse("ADMIN_QV.ADMIN_QV_NEWPMS");
}

$qv->assign(array(
	"QV_DB_ROWS" => $total_rows,
	"QV_DB_INDEXSIZE" => number_format(($total_index_length / 1024), 1, '.', ' '),
	"QV_DB_DATASSIZE" => number_format(($total_data_length / 1024), 1, '.', ' '),
	"QV_DB_TOTALSIZE" => number_format(($total_length / 1024), 1, '.', ' '),
	"QV_DB_TOTALFRAGMENTED" => number_format(($total_fragmented / 1024), 1, '.', ' ')
));

$qv->parse("ADMIN_QV.ADMIN_QV_DB");

if (!$cfg['disablehitstats']) {
	foreach ($hits_d as $day => $hits) {
		$qv->assign(array(
			"QV_HITS_PERCENTBAR" => floor(($hits / $hits_d_max) * 100),
			"QV_HITS_DAY" => $day,
			"QV_HITS_COUNT" => $hits,
			"QV_HITS_URL" => sed_url("admin", "m=hits")
		));
		$qv->parse("ADMIN_QV.ADMIN_QV_HITS.ADMIN_QV_HITS_DAYLIST");
	}
	$qv->parse("ADMIN_QV.ADMIN_QV_HITS");
}

$qv->parse("ADMIN_QV");
$t->assign("ADMIN_QV", $qv->text("ADMIN_QV"));
