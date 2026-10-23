<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/whosonline/inc/whosonline.functions.php
Version=186
Updated=2026-oct-23
Type=Plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_online, $sqldbprefix, $cfg;
if (empty($db_online)) {
	$db_online = (isset($cfg['sqldbprefix']) ? $cfg['sqldbprefix'] : (isset($sqldbprefix) ? $sqldbprefix : 'sed_')) . 'online';
}

/**
 * Tracks current user or guest activity
 *
 * @param string $location Current section location
 * @param string $sublocation Sublocation or section title
 */
function sed_whosonline_track($location, $sublocation = '')
{
	global $db_online, $usr, $sys;

	if (empty($db_online)) {
		return;
	}

	$location = sed_sql_prep($location);
	$sublocation = sed_sql_prep($sublocation);
	$now = (int)$sys['now'];

	if ($usr['id'] > 0) {
		$sql = sed_sql_query("SELECT online_id FROM $db_online WHERE online_userid = " . (int)$usr['id'] . " LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			sed_sql_query("UPDATE $db_online SET online_lastseen = " . $now . ", online_location = '" . $location . "', online_subloc = '" . $sublocation . "' WHERE online_userid = " . (int)$usr['id']);
		} else {
			sed_sql_query("INSERT INTO $db_online (online_ip, online_name, online_lastseen, online_location, online_subloc, online_userid) VALUES ('" . sed_sql_prep($usr['ip']) . "', '" . sed_sql_prep($usr['name']) . "', " . $now . ", '" . $location . "', '" . $sublocation . "', " . (int)$usr['id'] . ")");
		}
	} else {
		$sql = sed_sql_query("SELECT online_id FROM $db_online WHERE online_userid = -1 AND online_ip = '" . sed_sql_prep($usr['ip']) . "' LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			sed_sql_query("UPDATE $db_online SET online_lastseen = " . $now . ", online_location = '" . $location . "', online_subloc = '" . $sublocation . "' WHERE online_userid = -1 AND online_ip = '" . sed_sql_prep($usr['ip']) . "'");
		} else {
			sed_sql_query("INSERT INTO $db_online (online_ip, online_name, online_lastseen, online_location, online_subloc, online_userid) VALUES ('" . sed_sql_prep($usr['ip']) . "', 'v', " . $now . ", '" . $location . "', '" . $sublocation . "', -1)");
		}
	}
}

/**
 * Aggregates and counts users and visitors online with caching
 */
function sed_whosonline_count()
{
	global $db_online, $db_stats, $cfg, $sys, $usr, $out, $L, $sed_usersonline;

	if (empty($db_online)) {
		return;
	}

	$timeout = !empty($cfg['plugin']['whosonline']['timeout']) ? (int)$cfg['plugin']['whosonline']['timeout'] : 900;
	$cache_ttl = isset($cfg['plugin']['whosonline']['cache_ttl']) ? (int)$cfg['plugin']['whosonline']['cache_ttl'] : 30;

	$whosonline_data = ($cache_ttl > 0) ? sed_cache_get('sed_whosonline') : false;

	if ($whosonline_data === false || !is_array($whosonline_data)) {
		// Clean stale sessions
		$online_timedout = (int)$sys['now'] - $timeout;
		sed_sql_query("DELETE FROM $db_online WHERE online_lastseen < " . $online_timedout);

		// Count visitors
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_online WHERE online_name = 'v'");
		$vis_count = (int)sed_sql_result($sql, 0, 'COUNT(*)');

		// Fetch registered users
		$users = array();
		$sql = sed_sql_query("SELECT online_name, online_userid FROM $db_online WHERE online_name NOT LIKE 'v' ORDER BY online_name ASC");
		while ($row = sed_sql_fetchassoc($sql)) {
			$users[] = array(
				'id'   => (int)$row['online_userid'],
				'name' => $row['online_name']
			);
		}

		$whosonline_data = array(
			'vis_count' => $vis_count,
			'users'     => $users
		);

		if ($cache_ttl > 0) {
			sed_cache_store('sed_whosonline', $whosonline_data, $cache_ttl);
		}
	}

	$sys['whosonline_vis_count'] = (int)$whosonline_data['vis_count'];
	$sys['whosonline_reg_count'] = count($whosonline_data['users']);
	$sys['whosonline_all_count'] = $sys['whosonline_reg_count'] + $sys['whosonline_vis_count'];

	$out['whosonline_reg_list'] = '';
	$sed_usersonline = array();

	if (!empty($whosonline_data['users'])) {
		$ii = 0;
		foreach ($whosonline_data['users'] as $user) {
			$out['whosonline_reg_list'] .= ($ii > 0) ? ', ' : '';
			$out['whosonline_reg_list'] .= sed_build_user($user['id'], sed_cc($user['name']));
			$sed_usersonline[] = $user['id'];
			$ii++;
		}
	}

	$members_str = isset($L['com_members']) ? $L['com_members'] : 'members';
	$guests_str = isset($L['com_guests']) ? $L['com_guests'] : 'guests';

	$out['whosonline'] = $sys['whosonline_reg_count'] . ' ' . $members_str . ', ' . $sys['whosonline_vis_count'] . ' ' . $guests_str;
	$out['whosonline_link'] = sed_url("plug", "e=whosonline");

	// Update max users record if enabled
	if (empty($cfg['disablehitstats'])) {
		$maxusers = 0;
		$sql = sed_sql_query("SELECT stat_value FROM $db_stats WHERE stat_name = 'maxusers' LIMIT 1");
		if ($row = sed_sql_fetcharray($sql)) {
			$maxusers = (int)$row[0];
		} else {
			sed_sql_query("INSERT INTO $db_stats (stat_name, stat_value) VALUES ('maxusers', 1)");
		}

		if ($maxusers < $sys['whosonline_all_count']) {
			sed_sql_query("UPDATE $db_stats SET stat_value = '" . (int)$sys['whosonline_all_count'] . "' WHERE stat_name = 'maxusers'");
		}
	}
}
