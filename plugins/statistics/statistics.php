<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/statistics/statistics.php
Version=185
Updated=2026-feb-14
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=statistics
Part=main
File=statistics
Hooks=standalone
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

$s = sed_import('s', 'G', 'TXT');
$m = sed_import('m', 'G', 'TXT');

if ($m == 'share') {
	$totaldbposts = 0;
	$totaldbtopics = 0;
	$totaldbviews = 0;
	if (sed_module_active('forums')) {
		$totaldbposts = sed_sql_rowcount($db_forum_posts);
		$totaldbtopics = sed_sql_rowcount($db_forum_topics);
		$totaldbviews = sed_sql_query("SELECT SUM(fs_viewcount) FROM $db_forum_sections");
		$totaldbviews = sed_sql_result($totaldbviews, 0, "SUM(fs_viewcount)");
		$sql = sed_sql_query("SELECT SUM(fs_topiccount_pruned) FROM $db_forum_sections");
		$totaldbtopics += sed_sql_result($sql, 0, "SUM(fs_topiccount_pruned)");
		$sql = sed_sql_query("SELECT SUM(fs_postcount_pruned) FROM $db_forum_sections");
		$totaldbposts += sed_sql_result($sql, 0, "SUM(fs_postcount_pruned)");
	}
	$share_pages = sed_module_active('page') ? sed_sql_rowcount($db_pages) : 0;
	$output = "Seditio - Website engine<br />Copyright Neocrome & Seditio Team<br />";
	$output .= "<a href=\"https://seditio.org\">https://seditio.org</a><br />";
	$output .= "&nbsp;<br />[BEGIN_SED]<br />Title=" . $cfg['maintitle'] . "<br />";
	$output .= "Subtitle=" . $cfg['subtitle'] . "<br />Version=" . $cfg['version'] . "<br />";
	$output .= "Pages=" . $share_pages . "<br />Users=" . sed_sql_rowcount($db_users) . "<br />";
	$output .= "Pms=" . sed_stat_get('totalpms') . "<br />Forum_views=" . $totaldbviews . "<br />";
	$output .= "Forum_posts=" . $totaldbposts . "<br />Forum_topics=" . $totaldbtopics . "<br />[END_SED]<br />&nbsp;";
	die($output);
}

$plugin_title = $L['plu_title'];

$totaldbpages = sed_module_active('page') ? sed_sql_rowcount($db_pages) : 0;
$totaldbcomments = sed_plug_active('comments') ? sed_sql_rowcount($db_com) : 0;
$totaldbratings = sed_plug_active('ratings') ? sed_sql_rowcount($db_ratings) : 0;
$totaldbratingsvotes = sed_plug_active('ratings') ? sed_sql_rowcount($db_rated) : 0;
$totaldbpolls = sed_module_active('polls') ? sed_sql_rowcount($db_polls) : 0;
$totaldbpollsvotes = sed_module_active('polls') ? sed_sql_rowcount($db_polls_voters) : 0;
$totaldbposts = 0;
$totaldbtopics = 0;
$totaldbviews = 0;
$totaldbtopicspruned = 0;
$totaldbpostspruned = 0;
if (sed_module_active('forums')) {
	$totaldbposts = sed_sql_rowcount($db_forum_posts);
	$totaldbtopics = sed_sql_rowcount($db_forum_topics);
	$totaldbviews = sed_sql_query("SELECT SUM(fs_viewcount) FROM $db_forum_sections");
	$totaldbviews = sed_sql_result($totaldbviews, 0, "SUM(fs_viewcount)");
	$sql = sed_sql_query("SELECT SUM(fs_topiccount_pruned) FROM $db_forum_sections");
	$totaldbtopicspruned = sed_sql_result($sql, 0, "SUM(fs_topiccount_pruned)");
	$sql = sed_sql_query("SELECT SUM(fs_postcount_pruned) FROM $db_forum_sections");
	$totaldbpostspruned = sed_sql_result($sql, 0, "SUM(fs_postcount_pruned)");
}
$totaldbfiles = sed_module_active('pfs') ? sed_sql_rowcount($db_pfs) : 0;
$totaldbusers = sed_sql_rowcount($db_users);

$totalpages = sed_stat_get('totalpages');
$totalmailsent = sed_stat_get('totalmailsent');
$totalpmsent = sed_stat_get('totalpms');

$totaldbfilesize = 0;
if (sed_module_active('pfs')) {
	$sql_pfs = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs");
	$totaldbfilesize = (int)sed_sql_result($sql_pfs, 0, "SUM(pfs_size)");
}

$totalpmactive = 0;
$totalpmarchived = 0;
$totalpmold = 0;
if (sed_module_active('pm')) {
	$totalpmactive = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_state<2");
	$totalpmactive = sed_sql_result($totalpmactive, 0, "COUNT(*)");
	$totalpmarchived = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_state=2");
	$totalpmarchived = sed_sql_result($totalpmarchived, 0, "COUNT(*)");
	$totalpmold = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_state=3");
	$totalpmold = sed_sql_result($totalpmold, 0, "COUNT(*)");
}

$sql = sed_sql_query("SELECT stat_name FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_name ASC LIMIT 1");
$row = sed_sql_fetchassoc($sql);
$since = $row['stat_name'];

$sql = sed_sql_query("SELECT * FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_value DESC LIMIT 1");
$row = sed_sql_fetchassoc($sql);
$max_date = $row['stat_name'];
$max_hits = $row['stat_value'];

if ($usr['id'] > 0) {
	$user_postscount = 0;
	$user_topicscount = 0;
	if (sed_module_active('forums')) {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_posterid='" . $usr['id'] . "'");
		$user_postscount = sed_sql_result($sql, 0, "COUNT(*)");
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_firstposterid='" . $usr['id'] . "'");
		$user_topicscount = sed_sql_result($sql, 0, "COUNT(*)");
	}
	$user_comments = sed_plug_active('comments') ? sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_com WHERE com_authorid='" . $usr['id'] . "'"), 0, "COUNT(*)") : 0;

	$t->assign(array(
		"PLUGIN_STATISTICS_USER_POSTSCOUNT" => $user_postscount,
		"PLUGIN_STATISTICS_USER_TOPICSCOUNT" => $user_topicscount,
		"PLUGIN_STATISTICS_USER_COMMENTS" => $user_comments
	));
	if (sed_module_active('forums')) {
		$t->parse('MAIN.PLUGIN_STATISTICS_IS_USER.PLUGIN_STATISTICS_USER_FORUMS');
	}
	if (sed_plug_active('comments')) {
		$t->parse('MAIN.PLUGIN_STATISTICS_IS_USER.PLUGIN_STATISTICS_USER_COMMENTS');
	}
	$t->parse('MAIN.PLUGIN_STATISTICS_IS_USER');
} else {
	$t->parse('MAIN.PLUGIN_STATISTICS_IS_NOT_USER');
}

if ($s == 'usercount') {
	$sql1 = sed_sql_query("DROP TEMPORARY TABLE IF EXISTS tmp1");
	$sql = sed_sql_query("CREATE TEMPORARY TABLE tmp1 SELECT user_country, COUNT(*) as usercount FROM $db_users GROUP BY user_country");
	$sql = sed_sql_query("SELECT * FROM tmp1 WHERE 1 ORDER by usercount DESC");
	$sql1 = sed_sql_query("DROP TEMPORARY TABLE IF EXISTS tmp1");
} else {
	$sql = sed_sql_query("SELECT user_country, COUNT(*) as usercount FROM $db_users GROUP BY user_country ORDER BY user_country ASC");
}

$sqltotal = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE 1");
$totalusers = sed_sql_result($sqltotal, 0, "COUNT(*)");

$ii = 0;

while ($row = sed_sql_fetchassoc($sql)) {
	$country_code = $row['user_country'];

	if (!empty($country_code) && $country_code != '00') {
		$ii = $ii + $row['usercount'];
		$t->assign(array(
			"PLUGIN_STATISTICS_COUNTRY_FLAG" => sed_build_flag($country_code),
			"PLUGIN_STATISTICS_COUNTRY_COUNT" => $row['usercount'],
			"PLUGIN_STATISTICS_COUNTRY_NAME" => sed_build_country($country_code)
		));
		$t->parse('MAIN.PLUGIN_STATISTICS_ROW_COUNTRY');
	}
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("plug", "e=statistics")] = $L['plu_title'];

$t->assign(array(
	"PLUGIN_STATISTICS_TITLE" => $L['plu_title'],
	"PLUGIN_STATISTICS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PLUGIN_STATISTICS_PLU_URL" => sed_url('plug', 'e=statistics'),
	"PLUGIN_STATISTICS_SORT_BY_USERCOUNT" => sed_url('plug', 'e=statistics&s=usercount'),
	"PLUGIN_STATISTICS_MAX_DATE" => $max_date,
	"PLUGIN_STATISTICS_MAX_HITS" => $max_hits,
	"PLUGIN_STATISTICS_SINCE" => $since,
	"PLUGIN_STATISTICS_TOTALPAGES" => $totalpages,
	"PLUGIN_STATISTICS_TOTALDBUSERS" => $totaldbusers,
	"PLUGIN_STATISTICS_TOTALDBPAGES" => $totaldbpages,
	"PLUGIN_STATISTICS_TOTALDBCOMMENTS" => $totaldbcomments,
	"PLUGIN_STATISTICS_TOTALMAILSENT" => $totalmailsent,
	"PLUGIN_STATISTICS_TOTALPMSENT" => $totalpmsent,
	"PLUGIN_STATISTICS_TOTALPMACTIVE" => $totalpmactive,
	"PLUGIN_STATISTICS_TOTALPMARCHIVED" => $totalpmarchived,
	"PLUGIN_STATISTICS_TOTALDBVIEWS" => $totaldbviews,
	"PLUGIN_STATISTICS_TOTALDBPOSTS_AND_TOTALDBPOSTSPRUNED" => ($totaldbposts + $totaldbpostspruned),
	"PLUGIN_STATISTICS_TOTALDBPOSTS" => $totaldbposts,
	"PLUGIN_STATISTICS_TOTALDBPOSTSPRUNED" => $totaldbpostspruned,
	"PLUGIN_STATISTICS_TOTALDBTOPICS_AND_TOTALDBTOPICSPRUNED" => ($totaldbtopics + $totaldbtopicspruned),
	"PLUGIN_STATISTICS_TOTALDBTOPICS" => $totaldbtopics,
	"PLUGIN_STATISTICS_TOTALDBTOPICSPRUNED" => $totaldbtopicspruned,
	"PLUGIN_STATISTICS_TOTALDBRATINGS" => $totaldbratings,
	"PLUGIN_STATISTICS_TOTALDBRATINGSVOTES" => $totaldbratingsvotes,
	"PLUGIN_STATISTICS_TOTALDBPOLLS" => $totaldbpolls,
	"PLUGIN_STATISTICS_TOTALDBPOLLSVOTES" => $totaldbpollsvotes,
	"PLUGIN_STATISTICS_TOTALDBFILES" => $totaldbfiles,
	"PLUGIN_STATISTICS_TOTALDBFILESIZE" => floor($totaldbfilesize / 1024),
	"PLUGIN_STATISTICS_UNKNOWN_COUNT" => $totalusers - $ii,
	"PLUGIN_STATISTICS_TOTALUSERS" => $totalusers
));

if (sed_module_active('page')) {
	$t->parse('MAIN.PLUGIN_STATISTICS_PAGES_ROW');
}
if (sed_plug_active('comments')) {
	$t->parse('MAIN.PLUGIN_STATISTICS_COMMENTS_ROW');
}
if (sed_module_active('pm')) {
	$t->parse('MAIN.PLUGIN_STATISTICS_PM_BLOCK');
}
if (sed_module_active('forums')) {
	$t->parse('MAIN.PLUGIN_STATISTICS_FORUMS_BLOCK');
}
if (sed_plug_active('ratings') || sed_module_active('polls')) {
	if (sed_plug_active('ratings')) {
		$t->parse('MAIN.PLUGIN_STATISTICS_POLLS_RATINGS_BLOCK.PLUGIN_STATISTICS_RATINGS_ROWS');
	}
	if (sed_module_active('polls')) {
		$t->parse('MAIN.PLUGIN_STATISTICS_POLLS_RATINGS_BLOCK.PLUGIN_STATISTICS_POLLS_ROWS');
	}
	$t->parse('MAIN.PLUGIN_STATISTICS_POLLS_RATINGS_BLOCK');
}
if (sed_module_active('pfs')) {
	$t->parse('MAIN.PLUGIN_STATISTICS_PFS_BLOCK');
}
