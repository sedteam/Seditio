<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.main.php
Version=186
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Forums sections (main page)
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', 'any');
sed_block($usr['auth_read']);

$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'ALP');
$q = sed_import('q', 'G', 'INT');
$p = sed_import('p', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');
$o = sed_import('o', 'G', 'ALP');
$w = sed_import('w', 'G', 'ALP', 4);
$c = sed_import('c', 'G', 'ALP');
$quote = sed_import('quote', 'G', 'INT');
$poll = sed_import('poll', 'G', 'INT');
$vote = sed_import('vote', 'G', 'INT');
$unread_done = FALSE;
$filter_cats = FALSE;
$sys['sublocation'] = $L['Home'];

/* === Hook === */
$extp = sed_getextplugins('forums.sections.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if ($n == 'markall' && $usr['id'] > 0) {
	$sql = sed_sql_query("UPDATE $db_users set user_lastvisit='" . $sys['now_offset'] . "' WHERE user_id='" . $usr['id'] . "'");
	$usr['lastvisit'] = $sys['now_offset'];
}

$sql = sed_sql_query("SELECT s.*, n.* FROM $db_forum_sections AS s LEFT JOIN
	$db_forum_structure AS n ON n.fn_code=s.fs_category WHERE fs_parentcat = '0'
    ORDER BY fs_parentcat ASC, fn_path ASC, fs_order ASC");

// All subforums (nested, any depth)

$sql2 = sed_sql_query("SELECT fs_id, fs_title, fs_desc, fs_icon, fs_parentcat, fs_lt_id, fs_lt_title, fs_lt_date, fs_lt_posterid, fs_lt_postername 
	FROM $db_forum_sections WHERE fs_parentcat > 0 ORDER BY fs_order ASC");

$forum_subforums = array();
$subforum_children = array();
while ($fsn_sub = sed_sql_fetchassoc($sql2)) {
	$forum_subforums[$fsn_sub['fs_id']] = $fsn_sub;
	$subforum_children[(int)$fsn_sub['fs_parentcat']][] = $fsn_sub['fs_id'];
}

$forum_children_map = sed_forum_get_children_map();

//---

if (!isset($sed_sections_act)) {
	$sed_sections_act = array();
	$timeback = (int)($sys['now'] - 604800);
	$sqlact = sed_sql_query("SELECT fs_id FROM $db_forum_sections");
	while ($tmprow = sed_sql_fetchassoc($sqlact)) {
		$sed_sections_act[(int)$tmprow['fs_id']] = 0;
	}
	$sqltmp = sed_sql_query("SELECT fp_sectionid, COUNT(*) AS actcnt FROM $db_forum_posts WHERE fp_creation>'$timeback' GROUP BY fp_sectionid");
	while ($row = sed_sql_fetchassoc($sqltmp)) {
		$sed_sections_act[(int)$row['fp_sectionid']] = (int)$row['actcnt'];
	}
	sed_cache_store('sed_sections_act', $sed_sections_act, 600);
}

if (!isset($sed_sections_vw)) {
	$sed_sections_vw = array();
	$sqltmp = sed_sql_query("SELECT online_subloc, COUNT(*) FROM $db_online WHERE online_location='Forums' GROUP BY online_subloc");
	while ($tmprow = sed_sql_fetchassoc($sqltmp)) {
		$sed_sections_vw[$tmprow['online_subloc']] = $tmprow['COUNT(*)'];
	}
	sed_cache_store('sed_sections_vw', $sed_sections_vw, 120);
}

$secact_max = !empty($sed_sections_act) ? max($sed_sections_act) : 0;

$out['markall'] = ($usr['id'] > 0) ? sed_link(sed_url("forums", "n=markall"), $L['for_markallasread']) : '';

$out['subtitle'] = $L['Forums'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('forumstitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("forums")] = $L['Forums'];

/* ===== */
$out['canonical_url'] = ($cfg['absurls']) ? sed_url("forums") : $sys['abs_url'] . sed_url("forums");
/* ===== */

/* === Hook === */
$extp = sed_getextplugins('forums.sections.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile(array('forums', 'sections'));
$t = new XTemplate($mskin);

$t->assign(array(
	"FORUMS_SECTIONS_TITLE" => sed_link(sed_url("forums"), $L['Forums']),
	"FORUMS_SECTIONS_SHORTTITLE" => $L['Forums'],
	"FORUMS_SECTIONS_PAGETITLE" => sed_link(sed_url("forums"), $L['Forums']),
	"FORUMS_SECTIONS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"FORUMS_SECTIONS_MARKALL" =>  $out['markall'],
	"FORUMS_SECTIONS_SEARCH" => sed_url("plug", "e=search&frm=1"),
	"FORUMS_SECTIONS_FOLDALL" => sed_url("forums", "c=fold", "#top"),
	"FORUMS_SECTIONS_UNFOLDALL" => sed_url("forums", "c=unfold", "#top"),
	"FORUMS_SECTIONS_GMTTIME" => $L['Alltimesare'] . " " . $usr['timetext'],
	"FORUMS_SECTIONS_WHOSONLINE" => $out['whosonline'] . " : " . $out['whosonline_reg_list']
));

/* === Hook - Part1 : Set === */
$extp = sed_getextplugins('forums.sections.loop');
/* ===== */

$catnum = 1;

while ($fsn = sed_sql_fetchassoc($sql)) //v178
{
	$fsn_arr[$fsn['fn_id']][$fsn['fs_id']] = $fsn;
	$sect_arr[$fsn['fn_id']] = $fsn;
}

// by structure extending
foreach ($sect_arr as $fsec_key => $fsec) {
	$cattitle = sed_link("javascript:sedjs.toggleblock('blk_" . $fsec['fs_category'] . "')", $sed_forums_str[$fsec['fs_category']]['tpath']);

	if ($c == 'fold') {
		$fold = TRUE;
	} elseif ($c == 'unfold') {
		$fold = FALSE;
	} elseif (!empty($c)) {
		$fold = ($c == $fsec['fs_category']) ? FALSE : TRUE;
	} else {
		$fold = (!$sed_forums_str[$fsec['fs_category']]['defstate']) ? TRUE : FALSE;
	}

	$fsec['toggle_state'] = ($fold) ? " style=\"display:none;\"" : '';
	$fsec['toggle_body'] = "id=\"blk_" . $fsec['fs_category'] . "\" " . $fsec['toggle_state'];

	$t->assign(array(
		"FORUMS_SECTIONS_ROW_CAT_TITLE" => $cattitle,
		"FORUMS_SECTIONS_ROW_CAT_ICON" => $fsec['fn_icon'],
		"FORUMS_SECTIONS_ROW_CAT_SHORTTITLE" => sed_cc($fsec['fn_title']),
		"FORUMS_SECTIONS_ROW_CAT_DESC" => sed_parse($fsec['fn_desc']),
		"FORUMS_SECTIONS_ROW_CAT_DEFSTATE" => sed_cc($fsec['fn_defstate']),
		"FORUMS_SECTIONS_ROW_CAT_TOGGLE" => $fsec['toggle_body'],
		"FORUMS_SECTIONS_ROW_CAT_ID" => $fsec['fs_category']
	));

	// by sections extending v178
	$fs_num = 0;
	foreach ($fsn_arr[$fsec_key] as $fsn_key => $fsn) {
		if (sed_auth('forums', $fsn['fs_id'], 'R')) {

			$fsn['fs_topiccount_all'] = $fsn['fs_topiccount'] + $fsn['fs_topiccount_pruned'];
			$fsn['fs_postcount_all'] = $fsn['fs_postcount'] + $fsn['fs_postcount_pruned'];
			$fsn['fs_newposts'] = '0';
			$fsn['fs_desc'] = sed_parse($fsn['fs_desc']);
			$fsn['fs_desc'] .= ($fsn['fs_state']) ? " " . $L['Locked'] : '';

			$sed_sections_vw_cur = (isset($sed_sections_vw[$fsn['fs_title']]) && $sed_sections_vw[$fsn['fs_title']]) ? $sed_sections_vw[$fsn['fs_title']] : "0";

			if (!$fsn['fs_lt_id']) {
				sed_forum_sectionsetlast($fsn['fs_id']);
			}

			if ($usr['id'] > 0 && $fsn['fs_lt_date'] > $usr['lastvisit'] && $fsn['fs_lt_posterid'] != $usr['id']) {
				$fsn['fs_title'] = "+ " . $fsn['fs_title'];
				$fsn['fs_newposts'] = '1';
			}

			if ($fsn['fs_lt_id'] > 0) {
				$fsn['fs_timago'] = sed_build_timegap($fsn['fs_lt_date'], $sys['now_offset']);
				$url_params_lp = "m=posts&q=" . $fsn['fs_lt_id'] . "&al=" . $fsn['fs_lt_title'];
				$lp_unread = ($usr['id'] > 0 && $fsn['fs_lt_date'] > $usr['lastvisit'] && $fsn['fs_lt_posterid'] != $usr['id']);
				$lp_url = $lp_unread
					? sed_url("forums", $url_params_lp . "&n=unread", "#unread")
					: sed_url("forums", $url_params_lp . "&n=last", "#bottom");
				$fsn['lastpost'] = sed_link($lp_url, sed_cutstring($fsn['fs_lt_title'], 32));
			} else {
				$fsn['fs_timago'] = '&nbsp;';
				$fsn['lastpost'] = '&nbsp;';
				$fsn['fs_lt_date'] = '&nbsp;';
				$fsn['fs_lt_postername'] = '';
				$fsn['fs_lt_posterid'] = 0;
			}

			$lt_date = $fsn['fs_lt_date'];
			$fsn['fs_lt_date'] = ($fsn['fs_lt_date'] > 0) ? sed_build_date($cfg['formatmonthdayhourmin'], $fsn['fs_lt_date']) : '';
			$fsn['fs_viewcount_short'] = ($fsn['fs_viewcount'] > 9999) ? floor($fsn['fs_viewcount'] / 1000) . "k" : $fsn['fs_viewcount'];
			$fsn['fs_lt_postername'] = sed_build_user($fsn['fs_lt_posterid'], sed_cc($fsn['fs_lt_postername']));

			if (!$secact_max) {
				$section_activity = '';
				$section_activity_img = '';
				$secact_num = 0;
			} else {
				$secact_num = round(6.25 * $sed_sections_act[$fsn['fs_id']] / $secact_max);
				if ($secact_num > 5) {
					$secact_num = 5;
				}
				if (!$secact_num && $sed_sections_act[$fsn['fs_id']] > 1) {
					$secact_num = 1;
				}
				$section_activity_img = "<img src=\"skins/" . $skin . "/img/system/activity" . $secact_num . ".gif\" alt=\"\" />";
			}
			$fs_num++;

			$t->assign(array(
				"FORUMS_SECTIONS_ROW_ID" => $fsn['fs_id'],
				"FORUMS_SECTIONS_ROW_CAT" => $fsn['fs_category'],
				"FORUMS_SECTIONS_ROW_STATE" => $fsn['fs_state'],
				"FORUMS_SECTIONS_ROW_ORDER" => $fsn['fs_order'],
				"FORUMS_SECTIONS_ROW_TITLE" => $fsn['fs_title'],
				"FORUMS_SECTIONS_ROW_DESC" => $fsn['fs_desc'],
				"FORUMS_SECTIONS_ROW_ICON" => $fsn['fs_icon'],
				"FORUMS_SECTIONS_ROW_TOPICCOUNT" => $fsn['fs_topiccount'],
				"FORUMS_SECTIONS_ROW_POSTCOUNT" => $fsn['fs_postcount'],
				"FORUMS_SECTIONS_ROW_TOPICCOUNT_ALL" => $fsn['fs_topiccount_all'],
				"FORUMS_SECTIONS_ROW_POSTCOUNT_ALL" => $fsn['fs_postcount_all'],
				"FORUMS_SECTIONS_ROW_VIEWCOUNT" => $fsn['fs_viewcount'],
				"FORUMS_SECTIONS_ROW_VIEWCOUNT_SHORT" => $fsn['fs_viewcount_short'],
				"FORUMS_SECTIONS_ROW_VIEWERS" => $sed_sections_vw_cur,
				"FORUMS_SECTIONS_ROW_URL" => sed_url("forums", "m=topics&s=" . $fsn['fs_id'] . "&al=" . $fsn['fs_title']),
				"FORUMS_SECTIONS_ROW_LASTPOSTDATE" => $fsn['fs_lt_date'],
				"FORUMS_SECTIONS_ROW_LASTPOSTER" => $fsn['fs_lt_postername'],
				"FORUMS_SECTIONS_ROW_LASTPOST" => $fsn['lastpost'],
				"FORUMS_SECTIONS_ROW_TIMEAGO" => $fsn['fs_timago'],
				"FORUMS_SECTIONS_ROW_ACTIVITY" => $section_activity_img,
				"FORUMS_SECTIONS_ROW_ACTIVITYVALUE" => $secact_num,
				"FORUMS_SECTIONS_ROW_NEWPOSTS" => $fsn['fs_newposts'],
				"FORUMS_SECTIONS_ROW_ODDEVEN" => sed_build_oddeven($fs_num),
				"FORUMS_SECTIONS_ROW" => $fsn
			));

			/* ============ For Subforums (direct children only) ================== */
			if (!empty($subforum_children[$fsn['fs_id']])) {
				$ii = 0;

				$latest_desc = sed_forum_latest_in_subtree($fsn['fs_id'], $forum_subforums, $forum_children_map);
				if ($latest_desc && $latest_desc['fs_lt_date'] > $lt_date) {
					$fsnn = $latest_desc;
					$latest_ts = (int)$fsnn['fs_lt_date'];
					$is_unread = ($usr['id'] > 0 && $latest_ts > $usr['lastvisit'] && $fsnn['fs_lt_posterid'] != $usr['id']);
					$url_params_lp = "m=posts&q=" . $fsnn['fs_lt_id'] . "&al=" . $fsnn['fs_lt_title'];
					$lp_url = $is_unread
						? sed_url("forums", $url_params_lp . "&n=unread", "#unread")
						: sed_url("forums", $url_params_lp . "&n=last", "#bottom");
					$fsnn['lastpost'] = sed_link($lp_url, sed_cutstring($fsnn['fs_lt_title'], 32));
					$fsnn['fs_timago'] = sed_build_timegap($latest_ts, $sys['now_offset']);
					$fsnn['fs_lt_date'] = ($latest_ts > 0) ? sed_build_date($cfg['formatmonthdayhourmin'], $latest_ts) : '';

					$t->assign(array(
						"FORUMS_SECTIONS_ROW_LASTPOSTDATE" => $fsnn['fs_lt_date'],
						"FORUMS_SECTIONS_ROW_LASTPOSTER" => sed_build_user($fsnn['fs_lt_posterid'], sed_cc($fsnn['fs_lt_postername'])),
						"FORUMS_SECTIONS_ROW_LASTPOST" => $fsnn['lastpost'],
						"FORUMS_SECTIONS_ROW_TIMEAGO" => $fsnn['fs_timago']
					));

					$lt_date = $latest_ts;
				}

				foreach ($subforum_children[$fsn['fs_id']] as $sub_id) {
					if (!isset($forum_subforums[$sub_id])) continue;
					$row = $forum_subforums[$sub_id];

					$sub_title = $row['fs_title'];
					if ($usr['id'] > 0 && $row['fs_lt_date'] > $usr['lastvisit'] && $row['fs_lt_posterid'] != $usr['id']) {
						$sub_title = "+ " . $sub_title;
					}

					$ii++;
					$t->assign(array(
						"FORUMS_SECTIONS_ROW_SUBFORUMS_ID" => $row['fs_id'],
						"FORUMS_SECTIONS_ROW_SUBFORUMS_TITLE" => $sub_title,
						"FORUMS_SECTIONS_ROW_SUBFORUMS_DESC" => $row['fs_desc'],
						"FORUMS_SECTIONS_ROW_SUBFORUMS_ICON" => $row['fs_icon'],
						"FORUMS_SECTIONS_ROW_SUBFORUMS_URL" => sed_url("forums", "m=topics&s=" . $row['fs_id'] . "&al=" . $row['fs_title']),
						"FORUMS_SECTIONS_ROW_SUBFORUMS_ODDEVEN" => sed_build_oddeven($ii),
						"FORUMS_SECTIONS_ROW_SUBFORUMS_NUM" => $ii,
						"FORUMS_SECTIONS_ROW_SUBFORUMS_DEPTH" => 0,
						"FORUMS_SECTIONS_ROW_SUBFORUMS" => $row
					));

					$t->parse("MAIN.FORUMS_SECTIONS_ROW.FORUMS_SECTIONS_ROW_SECTION.FORUMS_SECTIONS_ROW_SUBFORUMS.FORUMS_SECTIONS_ROW_SUBFORUMS_LIST");
				}

				if ($ii > 0) {
					$t->parse("MAIN.FORUMS_SECTIONS_ROW.FORUMS_SECTIONS_ROW_SECTION.FORUMS_SECTIONS_ROW_SUBFORUMS");
				}
			}
			/* ============ ======== ================== */

			/* === Hook - Part2 : Include === */
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			$t->parse("MAIN.FORUMS_SECTIONS_ROW.FORUMS_SECTIONS_ROW_SECTION");
		}

		$catnum++;
	}
	$t->parse("MAIN.FORUMS_SECTIONS_ROW");
}

/* === Hook === */
$extp = sed_getextplugins('forums.sections.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
