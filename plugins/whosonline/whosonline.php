<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/whosonline/whosonline.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=whosonline
Part=main
File=whosonline
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

$cfg_showavatars = $cfg['plugin']['whosonline']['showavatars'];
$cfg_miniavatar_x = $cfg['plugin']['whosonline']['miniavatar_x'];
$cfg_miniavatar_y = $cfg['plugin']['whosonline']['miniavatar_y'];

$plugin_title = $L['plu_title'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("plug", "e=whosonline")] = $L['plu_title'];

$sql1 = sed_sql_query("SELECT DISTINCT u.user_id, u.user_country, u.user_avatar, u.user_maingrp, o.* FROM $db_online AS o LEFT JOIN $db_users AS u ON u.user_id = o.online_userid WHERE online_name != 'v' ORDER BY u.user_name ASC");
$sql2 = sed_sql_query("SELECT online_ip, online_lastseen, online_location, online_subloc FROM $db_online WHERE online_name LIKE 'v' ORDER BY online_lastseen DESC");
$sql3 = sed_sql_query("SELECT stat_value FROM $db_stats where stat_name='maxusers' LIMIT 1");

$total1 = sed_sql_numrows($sql1);
$total2 = sed_sql_numrows($sql2);

$row = sed_sql_fetcharray($sql3);

$maxusers = $row[0];
$visitornum = 0;

if ($usr['isadmin']) {
	$t->parse("MAIN.PLUGIN_WHOSONLINE_HEAD_ONLINE");
}

$t->assign(array(
	"PLUGIN_WHOSONLINE_MOST_ONLINE" => $maxusers,
	"PLUGIN_WHOSONLINE_TOTAL_VISITORS" => $total1,
	"PLUGIN_WHOSONLINE_THERESCURRENTLY" => $total2
));

while ($row = sed_sql_fetchassoc($sql1)) {
	if ($cfg_showavatars) {
		$user_avatar = "<a href=\"" . sed_url("users", "m=details&id=" . $row['online_userid']) . "\">";
		$user_avatar .= (!empty($row['user_avatar'])) ? "<img src=\"" . $row['user_avatar'] . "\" width=\"" . $cfg_miniavatar_x . "\" height=\"" . $cfg_miniavatar_y . "\" alt=\"\" /></a>" : "<img src=\"plugins/whosonline/img/blank.gif\" width=\"" . $cfg_miniavatar_x . "\" height=\"" . $cfg_miniavatar_y . "\" alt=\"\" /></a>";
	}

	if ($usr['isadmin']) {
		$sublock = (!empty($row['online_subloc'])) ? " " . $cfg['separator'] . " " . sed_cc($row['online_subloc']) : '';
		$t->assign(array(
			"PLUGIN_WHOSONLINE_ROW_ONLINE_LOCATION" => $L[$row['online_location']] . $sublock,
			"PLUGIN_WHOSONLINE_ROW_ONLINE_IP" => $row['online_ip']
		));
		$t->parse("MAIN.PLUGIN_WHOSONLINE_USERS_ROW.PLUGIN_WHOSONLINE_USERS_ROW_ONLINE");
	}

	$t->assign(array(
		"PLUGIN_WHOSONLINE_ROW_USER" => sed_build_user($row['online_userid'], sed_cc($row['online_name'])),
		"PLUGIN_WHOSONLINE_ROW_AVATAR" => $user_avatar,
		"PLUGIN_WHOSONLINE_ROW_MAINGRP_URL" => sed_url('users', 'g=' . $row['user_maingrp']),
		"PLUGIN_WHOSONLINE_ROW_MAINGRP_TITLE" => $sed_groups[$row['user_maingrp']]['title'],
		"PLUGIN_WHOSONLINE_ROW_MAINGRP" => sed_build_group($row['user_maingrp']),
		"PLUGIN_WHOSONLINE_ROW_MAINGRPID" => $row['user_maingrp'],
		"PLUGIN_WHOSONLINE_ROW_MAINGRPSTARS" => sed_build_stars($sed_groups[$row['user_maingrp']]['level']),
		"PLUGIN_WHOSONLINE_ROW_MAINGRPICON" => sed_build_userimage($sed_groups[$row['user_maingrp']]['icon']),
		"PLUGIN_WHOSONLINE_ROW_LASTSEEN" => sed_build_timegap($row['online_lastseen'], $sys['now']),
		"PLUGIN_WHOSONLINE_ROW_GROUPS" => sed_build_groupsms($row['user_id'], FALSE, $row['user_maingrp']),
		"PLUGIN_WHOSONLINE_ROW_COUNTRY" => sed_build_country($row['user_country']),
		"PLUGIN_WHOSONLINE_ROW_COUNTRYFLAG" => sed_build_flag($row['user_country'])
	));

	$t->parse("MAIN.PLUGIN_WHOSONLINE_USERS_ROW");
}

while ($row = sed_sql_fetchassoc($sql2)) {
	$visitornum++;
	$sublock = (!empty($row['online_subloc'])) ? " " . $cfg['separator'] . " " . sed_cc($row['online_subloc']) : '';

	if ($usr['isadmin']) {
		$t->assign(array(
			"PLUGIN_WHOSONLINE_ROW_ONLINE_LOCATION" => $L[$row['online_location']] . $sublock,
			"PLUGIN_WHOSONLINE_ROW_ONLINE_IP" => $row['online_ip']
		));
		$t->parse("MAIN.PLUGIN_WHOSONLINE_GUESTS_ROW.PLUGIN_WHOSONLINE_GUESTS_ROW_ONLINE");
	}

	$t->assign(array(
		"PLUGIN_WHOSONLINE_ROW_USER" => $L['plu_visitor'] . " #" . $visitornum,
		"PLUGIN_WHOSONLINE_ROW_LASTSEEN" => sed_build_timegap($row['online_lastseen'], $sys['now'])
	));

	$t->parse("MAIN.PLUGIN_WHOSONLINE_GUESTS_ROW");
}

$t->assign(array(
	"PLUGIN_WHOSONLINE_TITLE" => $L['plu_title'],
	"PLUGIN_WHOSONLINE_URL" => sed_url("plug", "e=whosonline"),
	"PLUGIN_WHOSONLINE_BREADCRUMBS" => sed_breadcrumbs($urlpaths)
));
