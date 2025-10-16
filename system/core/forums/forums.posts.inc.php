<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=forums.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Forums
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'INT');
$q = sed_import('q', 'G', 'INT');
$p = sed_import('p', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');
$o = sed_import('o', 'G', 'ALP');
$w = sed_import('w', 'G', 'ALP', 4);
$quote = sed_import('quote', 'G', 'INT');
$poll = sed_import('poll', 'G', 'INT');
$vote = sed_import('vote', 'G', 'INT');
$pvote = sed_import('pvote', 'P', 'INT');
$vote = ($pvote) ? $pvote : $vote;
$ajax = sed_import('ajax', 'G', 'BOL');
$unread_done = FALSE;
$fp_num = 0;
unset($notlastpage);

/* === Hook === */
$extp = sed_getextplugins('forums.posts.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require_once(SED_ROOT . '/system/core/polls/polls.functions.php');

if ($n == 'last' && !empty($q)) {
	$sql = sed_sql_query("SELECT fp_id, fp_topicid, fp_sectionid, fp_posterid
		FROM $db_forum_posts
		WHERE fp_topicid='$q'
		ORDER by fp_id DESC LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$p = $row['fp_id'];
		$q = $row['fp_topicid'];
		$s = $row['fp_sectionid'];
		$fp_posterid = $row['fp_posterid'];
	}
} elseif ($n == 'unread' && !empty($q) && $usr['id'] > 0) {
	$sql = sed_sql_query("SELECT fp_id, fp_topicid, fp_sectionid, fp_posterid
		FROM $db_forum_posts
		WHERE fp_topicid='$q' AND fp_creation>'" . $usr['lastvisit'] . "' AND fp_posterid!='" . $usr['id'] . "'
		ORDER by fp_id ASC LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$p = $row['fp_id'];
		$q = $row['fp_topicid'];
		$s = $row['fp_sectionid'];
		$fp_posterid = $row['fp_posterid'];
	}
}

if (!empty($p)) {
	$sql = sed_sql_query("SELECT fp_topicid, fp_sectionid, fp_posterid FROM $db_forum_posts WHERE fp_id='$p' LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$q = $row['fp_topicid'];
		$s = $row['fp_sectionid'];
		$fp_posterid = $row['fp_posterid'];
	} else {
		sed_die(true, 404);
	}
} elseif (!empty($q)) {
	$sql = sed_sql_query("SELECT ft_sectionid FROM $db_forum_topics WHERE ft_id='$q' LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$s = $row['ft_sectionid'];
	} else {
		sed_die(true, 404);
	}
}

$sql = sed_sql_query("SELECT * FROM $db_forum_sections WHERE fs_id='$s' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	$fs_title = $row['fs_title'];
	$fs_category = $row['fs_category'];
	$fs_parentcat = $row['fs_parentcat'];
	$fs_state = $row['fs_state'];
	$fs_allowusertext = $row['fs_allowusertext'];;
	$fs_countposts = $row['fs_countposts'];

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $s);
	sed_block($usr['auth_read']);

	if ($fs_state) {
		sed_redirect(sed_url("message", "msg=602", "", true));
	}
} else {
	sed_die(true, 404);
}

$sql2 = sed_sql_query("SELECT fp_id FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id ASC LIMIT 2");

while ($row2 = sed_sql_fetchassoc($sql2)) {
	$post12[] = $row2['fp_id'];
}

if ($a == 'newpost' && $usr['auth_write']) {
	sed_shield_protect();

	$sql = sed_sql_query("SELECT ft_state FROM $db_forum_topics WHERE ft_id='$q'");

	if ($row = sed_sql_fetchassoc($sql)) {
		if ($row['ft_state']) {
			sed_die();
		}
	} else {
		sed_die();
	}

	$sql = sed_sql_query("SELECT fp_posterid, fp_posterip FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id DESC LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		if ($cfg['antibumpforums'] && (($usr['id'] == 0 && $row['fp_posterid'] == 0 && $row['fp_posterip'] == $usr['ip']) || ($row['fp_posterid'] > 0 && $row['fp_posterid'] == $usr['id']))) {
			sed_die();
		}
	} else {
		sed_die();
	}

	/* === Hook === */
	$extp = sed_getextplugins('forums.posts.newpost.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$newmsg = sed_import('newmsg', 'P', 'HTM');

	$error_string .= (mb_strlen($newmsg) < 2) ? $L['for_msgtooshort'] . "<br />" : '';

	if (empty($error_string) && !empty($newmsg) && !empty($s) && !empty($q)) {
		$sql = sed_sql_query("INSERT into $db_forum_posts
			(fp_topicid,
			fp_sectionid,
			fp_posterid,
			fp_postername,
			fp_creation,
			fp_updated,
			fp_updater,
			fp_text,
			fp_posterip)
			VALUES
			(" . (int)$q . ",
			" . (int)$s . ",
			" . (int)$usr['id'] . ",
			'" . sed_sql_prep($usr['name']) . "',
			" . (int)$sys['now_offset'] . ",
			" . (int)$sys['now_offset'] . ",
			0,
			'" . sed_sql_prep($newmsg) . "',
			'" . $usr['ip'] . "')");

		$sql = sed_sql_query("UPDATE $db_forum_topics SET
			ft_postcount=ft_postcount+1,
			ft_updated='" . $sys['now_offset'] . "',
			ft_lastposterid='" . $usr['id'] . "',
			ft_lastpostername='" . sed_sql_prep($usr['name']) . "'
			WHERE ft_id='$q'");

		$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_postcount=fs_postcount+1 WHERE fs_id='$s'");

		if ($fs_countposts) {
			$sql = sed_sql_query("UPDATE $db_users SET user_postcount=user_postcount+1 WHERE user_id='" . $usr['id'] . "'");
		}

		/* === Hook === */
		$extp = sed_getextplugins('forums.posts.newpost.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		sed_forum_sectionsetlast($s);
		sed_shield_update(30, "New post");
		sed_redirect(sed_url("forums", "m=posts&q=" . $q . "&n=last", "#bottom", true));
	}
} elseif ($a == 'delete' && $usr['id'] > 0 && !empty($s) && !empty($q) && !empty($p) && ($usr['isadmin'] || $fp_posterid == $usr['id'])) {
	sed_check_xg();

	/* === Hook === */
	$extp = sed_getextplugins('forums.posts.delete.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	if ($post12[0] == $p && $post12[1] > 0) {
		sed_die();
	}

	$sql = sed_sql_query("SELECT * FROM $db_forum_posts WHERE fp_id='$p' AND fp_topicid='$q' AND fp_sectionid='$s'");

	if ($row = sed_sql_fetchassoc($sql)) {
		if ($cfg['trash_forum']) {
			sed_trash_put('forumpost', $L['Post'] . " #" . $p . " from topic #" . $q, "p" . $p . "-q" . $q, $row);
		}
	} else {
		sed_die();
	}

	$sql = sed_sql_query("DELETE FROM $db_forum_posts WHERE fp_id='$p' AND fp_topicid='$q' AND fp_sectionid='$s'");

	if ($fs_countposts) {
		$sql = sed_sql_query("UPDATE $db_users SET user_postcount=user_postcount-1 WHERE user_id='" . $fp_posterid . "' AND user_postcount>0");
	}

	sed_log("Deleted post #" . $p, 'for');

	/* === Hook === */
	$extp = sed_getextplugins('forums.posts.delete.done');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_topicid='$q'");

	if (sed_sql_result($sql, 0, "COUNT(*)") == 0) {
		// No posts left in this topic
		$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_id='$q'");

		if ($row = sed_sql_fetchassoc($sql)) {
			if ($cfg['trash_forum']) {
				sed_trash_put('forumtopic', $L['Topic'] . " #" . $q . " (no post left)", "q" . $q, $row);
			}

			$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_movedto='$q'");
			$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_id='$q'");
			if ($row['ft_poll'] > 0) sed_poll_delete($row['ft_poll']);

			$sql = sed_sql_query("UPDATE $db_forum_sections SET
				fs_topiccount=fs_topiccount-1,
				fs_topiccount_pruned=fs_topiccount_pruned+1,
				fs_postcount=fs_postcount-1,
				fs_postcount_pruned=fs_postcount_pruned+1
				WHERE fs_id='$s'");

			/* === Hook === */
			$extp = sed_getextplugins('forums.posts.emptytopicdel');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			sed_log("Delete topic #" . $q . " (no post left)", 'for');
			sed_forum_sectionsetlast($s);
		}
		sed_redirect(sed_url("forums", "m=topics&s=" . $s, "", true));
		exit;
	} else {
		// There's at least 1 post left, let's resync
		$sql = sed_sql_query("SELECT fp_id, fp_posterid, fp_postername, fp_updated
			FROM $db_forum_posts
			WHERE fp_topicid='$q' AND fp_sectionid='$s'
			ORDER BY fp_id DESC LIMIT 1");

		if ($row = sed_sql_fetchassoc($sql)) {
			$sql = sed_sql_query("UPDATE $db_forum_topics SET
				ft_postcount=ft_postcount-1,
				ft_lastposterid='" . (int)$row['fp_posterid'] . "',
				ft_lastpostername='" . sed_sql_prep($row['fp_postername']) . "',
				ft_updated='" . (int)$row['fp_updated'] . "'
				WHERE ft_id='$q'");

			$sql = sed_sql_query("UPDATE $db_forum_sections SET
				fs_postcount=fs_postcount-1,
				fs_postcount_pruned=fs_postcount_pruned+1
				WHERE fs_id='$s'");

			sed_forum_sectionsetlast($s);

			$sql = sed_sql_query("SELECT fp_id FROM $db_forum_posts
				WHERE fp_topicid='$q' AND fp_sectionid='$s' AND fp_id<'$p'
				ORDER BY fp_id DESC LIMIT 1");

			if ($row = sed_sql_fetchassoc($sql)) {
				sed_redirect(sed_url("forums", "m=posts&p=" . $row['fp_id'], "#" . $row['fp_id'], true));
			}
		}
	}
}

$sql = sed_sql_query("SELECT ft_title, ft_desc, ft_mode, ft_state, ft_poll, ft_firstposterid FROM $db_forum_topics WHERE ft_id='$q'");

if ($row = sed_sql_fetchassoc($sql)) {
	$ft_title = $row['ft_title'];
	$ft_desc = $row['ft_desc'];
	$ft_mode = $row['ft_mode'];
	$ft_state = $row['ft_state'];
	$ft_poll = $row['ft_poll'];
	$ft_firstposterid = $row['ft_firstposterid'];

	if ($ft_mode == 1 && !($usr['isadmin'] || $ft_firstposterid == $usr['id'])) {
		sed_die();
	}
} else {
	sed_die();
}

$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_viewcount=ft_viewcount+1 WHERE ft_id='$q'");
$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_viewcount=fs_viewcount+1 WHERE fs_id='$s'");
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_topicid='$q'");
$totalposts = sed_sql_result($sql, 0, "COUNT(*)");

if (!empty($p)) {
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_topicid='$q' and fp_id<'$p'");
	$postsbefore = sed_sql_result($sql, 0, "COUNT(*)");
	$d = $cfg['maxtopicsperpage'] * floor($postsbefore / $cfg['maxtopicsperpage']);
}

if (empty($d)) {
	$d = '0';
}

if ($usr['id'] > 0) {
	$morejavascript .= sed_build_addtxt('newpost', 'newmsg');
}

// ---------- Users Extra fields - getting
$users_extrafields = array();
$users_extrafields = sed_extrafield_get('users');
$users_extrafields_count = count($users_extrafields);
$sql_extra = "";

if ($users_extrafields_count > 0) {
	foreach ($users_extrafields as $i => $row) {
		$sql_extra .= "u.user_" . $row['code'] . ", ";
	}
}

$sql = sed_sql_query("SELECT p.*, u.user_text, u.user_maingrp, u.user_avatar, u.user_photo, u.user_signature, " . $sql_extra . "	
	   u.user_country, u.user_occupation, u.user_location, u.user_website, u.user_email, u.user_hideemail, u.user_gender, u.user_birthdate,
	   u.user_postcount
		FROM $db_forum_posts AS p LEFT JOIN $db_users AS u ON u.user_id=p.fp_posterid
		WHERE fp_topicid='$q'
		ORDER BY fp_id LIMIT $d, " . $cfg['maxtopicsperpage']);

$nbpages = ceil($totalposts / $cfg['maxtopicsperpage']);
$curpage = $d / $cfg['maxtopicsperpage'];
$notlastpage = (($d + $cfg['maxtopicsperpage']) < $totalposts) ? TRUE : FALSE;

$pages = sed_pagination(sed_url("forums", "m=posts&q=" . $q), $d, $totalposts, $cfg['maxtopicsperpage']);
list($pages_prev, $pages_next) = sed_pagination_pn(sed_url("forums", "m=posts&q=" . $q), $d, $totalposts, $cfg['maxtopicsperpage'], TRUE);

/* ============ For Subforums Sed 172 ================ */

$sql1 = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category, s.fs_parentcat FROM $db_forum_sections AS s LEFT JOIN
	$db_forum_structure AS n ON n.fn_code=s.fs_category
    ORDER by fn_path ASC, fs_order ASC");

$movebox = "<input type=\"submit\" class=\"submit btn\" value=\"" . $L['Move'] . "\" /><select name=\"ns\" size=\"1\">";
$jumpbox = "<select name=\"jumpbox\" size=\"1\" onchange=\"sedjs.redirect(this)\">";
$jumpbox .= "<option value=\"" . sed_url("forums") . "\">" . $L['Forums'] . "</option>";

while ($row1 = sed_sql_fetchassoc($sql1)) {
	if (sed_auth('forums', $row1['fs_id'], 'R')) {
		$forum_sections[$row1['fs_id']] = $row1;
	}
}

foreach ($forum_sections as $key => $value) {
	$pcat = $forum_sections[$key]['fs_parentcat'];
	$parentcat2 = array();
	if ($pcat > 0) {
		$parentcat2['sectionid']  = $forum_sections[$pcat]['fs_id'];
		$parentcat2['title']  = $forum_sections[$pcat]['fs_title'];
	}

	$cfs = sed_build_forums($forum_sections[$key]['fs_id'], $forum_sections[$key]['fs_title'], $forum_sections[$key]['fs_category'], FALSE, $parentcat2);

	if ($forum_sections[$key]['fs_id'] != $s && $usr['isadmin']) {
		$movebox .= "<option value=\"" . $forum_sections[$key]['fs_id'] . "\">" . $cfs . "</option>";
	}

	$selected = ($forum_sections[$key]['fs_id'] == $s) ? "selected=\"selected\"" : '';
	$jumpbox .= "<option $selected value=\"" . sed_url("forums", "m=topics&s=" . $forum_sections[$key]['fs_id']) . "\">" . $cfs . "</option>";
}

$movebox .= "</select> " . $L['Ghost'] . " " . sed_checkbox('ghost', 1, true);
$jumpbox .= "</select>";

$parentcat = array();
if ($fs_parentcat > 0) {
	$parentcat['sectionid']  = $forum_sections[$fs_parentcat]['fs_id'];
	$parentcat['title']  = $forum_sections[$fs_parentcat]['fs_title'];
}

/* ============ ==================== ==================*/

if ($usr['isadmin']) {
	$adminoptions = "<div class=\"box\"><form id=\"movetopic\" action=\"" . sed_url("forums", "m=topics&a=move&" . sed_xg() . "&s=" . $s . "&q=" . $q) . "\" method=\"post\">";
	$adminoptions .= $L['Topicoptions'] . ": " . sed_link(sed_url("forums", "m=topics&a=bump&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Bump']) . " &nbsp;";
	$adminoptions .= sed_link(sed_url("forums", "m=topics&a=lock&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Lock']) . " &nbsp;";
	$adminoptions .= sed_link(sed_url("forums", "m=topics&a=sticky&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Makesticky']) . " &nbsp;";
	$adminoptions .= sed_link(sed_url("forums", "m=topics&a=announcement&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Announcement']) . " &nbsp;";
	$adminoptions .= sed_link(sed_url("forums", "m=topics&a=private&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Private'] . " (#)") . " &nbsp;";
	$adminoptions .= sed_link(sed_url("forums", "m=topics&a=clear&" . sed_xg() . "&q=" . $q . "&s=" . $s), $L['Default']) . " &nbsp;";
	$adminoptions .= $L['Delete'] . ": [" . sed_link(sed_url("forums", "m=topics&a=delete&" . sed_xg() . "&s=" . $s . "&q=" . $q), "x") . "]&nbsp;<br />" . $movebox . "</form></div>";
} else {
	$adminoptions = "";
}

if ($ft_poll > 0) {
	$ft_title = $L['Poll'] . ": " . $ft_title;
}

$ft_title = ($ft_mode == 1) ? "# " . sed_cc($ft_title) : sed_cc($ft_title);

$toptitle = sed_link(sed_url("forums"), $L['Forums']) . " " . $cfg['separator'] . " " . sed_build_forums($s, $fs_title, $fs_category, TRUE, $parentcat);
$toptitle .= " " . $cfg['separator'] . " " . sed_link(sed_url("forums", "m=posts&q=" . $q . "&al=" . $ft_title), $ft_title);
$toptitle .= ($usr['isadmin']) ? " *" : '';

$sys['sublocation'] = $fs_title;
$out['subtitle'] = $L['Forums'] . " - " . sed_cc($ft_title);

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('forumstitle', $title_tags, $title_data);
/**/

/* ===== */
$out['canonical_url'] = ($cfg['absurls']) ? sed_url("forums", "m=posts&q=" . $q . "&al=" . $ft_title . "&d=" . $d) : $sys['abs_url'] . sed_url("forums", "m=posts&q=" . $q . "&al=" . $ft_title . "&d=" . $d);
/* ===== */

/* === Hook === */
$extp = sed_getextplugins('forums.posts.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile(array('forums', 'posts', $fs_category, $s));
$t = new XTemplate($mskin);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("forums")] = $L['Forums'];
sed_build_forums_bc($s, $fs_title, $fs_category, $parentcat);
$urlpaths[sed_url("forums", "m=posts&q=" . $q . "&al=" . $ft_title)] = $ft_title;

// ---------- Polls on forum
if (!$cfg['disable_polls'] && $ft_poll > 0) {
	$sql5 = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id='$ft_poll' AND poll_state='0' AND poll_type='1' LIMIT 1");

	if (sed_sql_numrows($sql5) > 0) {
		$row5 = sed_sql_fetchassoc($sql5);
		$poll_title = $row5['poll_text'];
		$poll_creationdate = $row5['poll_creationdate'];

		list($alreadyvoted, $votecasted) = sed_poll_vote($ft_poll, $vote);

		$sql6 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$ft_poll'");
		$totalvotes = sed_sql_result($sql6, 0, "SUM(po_count)");

		$sql7 = sed_sql_query("SELECT po_id, po_text, po_count FROM $db_polls_options WHERE po_pollid='$ft_poll' ORDER by po_id ASC");

		$xpoll = new XTemplate(sed_skinfile('poll'));

		while ($row7 = sed_sql_fetchassoc($sql7)) {
			$po_id = $row7['po_id'];

			$po_count = $row7['po_count'];
			$po_text = sed_cc($row7['po_text']);
			$percent = ($totalvotes > 0) ? @round(100 * ($po_count / $totalvotes), 1) : 0;
			$percentbar = floor($percent * 2.24);

			$xpoll->assign(array(
				"POLL_ROW_URL" => sed_url("polls", "a=send&" . sed_xg() . "&id=" . $id . "&vote=" . $po_id),
				"POLL_ROW_TEXT" => sed_cc($row7['po_text']),
				"POLL_ROW_PERCENT" => $percent,
				"POLL_ROW_COUNT" => $po_count,
				"POLL_ROW_RADIO_ITEM" => sed_radio_item('pvote', $po_id, $po_text, $po_id, false)
			));

			if ($alreadyvoted) {
				$xpoll->parse("POLL.POLL_RESULT.POLL_ROW_RESULT");
			} else {
				$xpoll->parse("POLL.POLL_FORM.POLL_ROW_OPTIONS");
			}
		}

		if ($alreadyvoted) {
			$polls_info = ($votecasted) ? $L['polls_votecasted'] : $L['polls_alreadyvoted'];
			$xpoll->parse("POLL.POLL_RESULT");
		} else {
			$polls_info = $L['polls_notyetvoted'];

			$ajax_send = sed_url("forums", "m=posts&q=" . $q . "&a=send&" . sed_xg() . "&poll=" . $ft_poll . "&vote=" . $po_id . "&ajax=1");
			$onclick = ($cfg['ajax']) ? "event.preventDefault(); sedjs.ajaxbind({'url': '" . $ajax_send . "', 'format':  'html', 'method':  'POST', 'update':  '#pollajx', 'loading': '#pollvotes', 'formid':  '#pollvotes'});" : "";

			$xpoll->assign(array(
				"POLL_BUTTON_ONCLICK" => $onclick,
				"POLL_SEND_URL" => sed_url("forums", "m=posts&q=" . $q . "&a=send&" . sed_xg() . "&poll=" . $ft_poll)
			));
			$xpoll->parse("POLL.POLL_FORM");
		}

		$xpoll->assign(array(
			"POLL_VOTERS" => $totalvotes,
			"POLL_SINCE" => sed_build_date($cfg['dateformat'], $poll_creationdate),
			"POLL_TITLE" => $poll_title,
			"POLL_INFO" => $polls_info
		));

		$xpoll->parse("POLL");
		$res_poll = $xpoll->text("POLL");
		sed_ajax_flush($res_poll, $ajax);  // AJAX Output

		$t->assign("FORUMS_POLL", $res_poll);
	}
}

if (!empty($pages)) {
	$t->assign(array(
		"FORUMS_POSTS_PAGES" => $pages,
		"FORUMS_POSTS_PAGEPREV" => $pages_prev,
		"FORUMS_POSTS_PAGENEXT" => $pages_next
	));
	$t->parse("MAIN.FORUMS_POSTS_PAGINATION_TP");
	$t->parse("MAIN.FORUMS_POSTS_PAGINATION_BM");
}

$t->assign(array(
	"FORUMS_POSTS_PAGETITLE" => $toptitle,
	"FORUMS_POSTS_FSTITLE" => $fs_title,
	"FORUMS_POSTS_TITLE" => $ft_title,
	"FORUMS_POSTS_SHORTTITLE" => $ft_title,
	"FORUMS_POSTS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"FORUMS_POSTS_TOPICDESC" => sed_cc($ft_desc),
	"FORUMS_POSTS_SUBTITLE" => $adminoptions,
	"FORUMS_POSTS_JUMPBOX" => $jumpbox
));

$totalposts = sed_sql_numrows($sql);

/* === Hook - Part1 : Set === */
$extp = sed_getextplugins('forums.posts.loop');
/* ===== */

while ($row = sed_sql_fetchassoc($sql)) {
	$row['fp_text'] = sed_parse($row['fp_text']);

	$row['fp_created'] = sed_build_date($cfg['dateformat'], $row['fp_creation']) . " " . $usr['timetext'];
	$row['fp_updated_ago'] = sed_build_timegap($row['fp_updated'], $sys['now_offset']);
	$row['fp_updated'] = sed_build_date($cfg['dateformat'], $row['fp_updated']) . " " . $usr['timetext'];
	$row['user_text'] = ($fs_allowusertext) ? $row['user_text'] : '';
	$lastposterid = $row['fp_posterid'];
	$lastposterip = $row['fp_posterip'];
	$fp_num++;

	$adminoptions = ($usr['id'] > 0) ? sed_link(sed_url("forums", "m=posts&s=" . $s . "&q=" . $q . "&quote=" . $row['fp_id'] . "&n=last", "#np"), $L['Quote'], array('class' => 'btn btn-adm')) : "&nbsp;";
	$adminoptions .= (($usr['isadmin'] || $row['fp_posterid'] == $usr['id']) && $usr['id'] > 0) ? " " . sed_link(sed_url("forums", "m=editpost&s=" . $s . "&q=" . $q . "&p=" . $row['fp_id'] . "&" . sed_xg()), $L['Edit'], array('class' => 'btn btn-adm')) : '';
	$adminoptions .= ($usr['id'] > 0 && ($usr['isadmin'] || $row['fp_posterid'] == $usr['id']) && !($post12[0] == $row['fp_id'] && isset($post12[1]) && $post12[1] > 0)) ? " " . sed_link(sed_url("forums", "m=posts&a=delete&" . sed_xg() . "&s=" . $s . "&q=" . $q . "&p=" . $row['fp_id']), $L['Delete'], array('class' => 'btn btn-adm')) : '';
	$adminoptions .= ($fp_num == $totalposts) ? sed_link("", "", array('name' => 'bottom', 'id' => 'bottom')) : '';

	if ($usr['id'] > 0 && $n == 'unread' && !$unread_done && $row['fp_creation'] > $usr['lastvisit']) {
		$unread_done = TRUE;
		$adminoptions .= sed_link("", "", array('name' => 'unread', 'id' => 'unread'));
	}

	$row['fp_posterip'] = ($usr['isadmin']) ? sed_build_ipsearch($row['fp_posterip']) : '';

	$row['user_text'] = sed_build_usertext($row['user_text']);

	if (sed_userisonline($row['fp_posterid'])) {
		$row['fp_useronline'] = "1";
		$row['fp_useronline_text'] = $L['Online'];
	} else {
		$row['fp_useronline'] = "0";
		$row['fp_useronline_text'] = $L['Offline'];
	}

	$row['fp_updatedby'] = (!empty($row['fp_updater'])) ? sprintf($L['for_updatedby'], sed_cc($row['fp_updater']), $row['fp_updated'], $row['fp_updated_ago']) : "";

	$row['user_age'] = ($row['user_birthdate'] != 0) ? sed_build_age($row['user_birthdate']) : '';

	$row['fp_text'] = "<div id=\"fp" . $row['fp_id'] . "\" >" . $row['fp_text'] . "</div>";

	$t->assign(array(
		"FORUMS_POSTS_ROW_ID" => $row['fp_id'],
		"FORUMS_POSTS_ROW_IDURL" => sed_link(sed_url("forums", "m=posts&p=" . $row['fp_id'], "#" . $row['fp_id']), $row['fp_id'], array('id' => $row['fp_id'])),
		"FORUMS_POSTS_ROW_CREATION" => $row['fp_created'],
		"FORUMS_POSTS_ROW_UPDATED" => $row['fp_updated'],
		"FORUMS_POSTS_ROW_UPDATER" => sed_cc($row['fp_updater']),
		"FORUMS_POSTS_ROW_UPDATEDBY" => $row['fp_updatedby'],
		"FORUMS_POSTS_ROW_TEXT" => $row['fp_text'],
		"FORUMS_POSTS_ROW_POSTERNAME" => sed_build_user($row['fp_posterid'], sed_cc($row['fp_postername']), $row['user_maingrp']),
		"FORUMS_POSTS_ROW_POSTERID" => $row['fp_posterid'],
		"FORUMS_POSTS_ROW_MAINGRP" => sed_build_group($row['user_maingrp']),
		"FORUMS_POSTS_ROW_MAINGRPID" => $row['user_maingrp'],
		"FORUMS_POSTS_ROW_MAINGRPSTARS" => sed_build_stars($sed_groups[$row['user_maingrp']]['level']),
		"FORUMS_POSTS_ROW_MAINGRPICON" => sed_build_userimage($sed_groups[$row['user_maingrp']]['icon']),
		"FORUMS_POSTS_ROW_USERTEXT" => sed_parse($row['user_text']),
		"FORUMS_POSTS_ROW_AVATAR" => sed_build_userimage($row['user_avatar']),
		"FORUMS_POSTS_ROW_PHOTO" => sed_build_userimage($row['user_photo']),
		"FORUMS_POSTS_ROW_SIGNATURE" => sed_build_userimage($row['user_signature']),
		"FORUMS_POSTS_ROW_GENDER" => $row['user_gender'] = ($row['user_gender'] == '' || $row['user_gender'] == 'U') ? '' : $L["Gender_" . $row['user_gender']],
		"FORUMS_POSTS_ROW_POSTERIP" => $row['fp_posterip'],
		"FORUMS_POSTS_ROW_USERONLINE" => $row['fp_useronline'],
		"FORUMS_POSTS_ROW_USERONLINE_TEXT" => $row['fp_useronline_text'],
		"FORUMS_POSTS_ROW_ADMIN" => $adminoptions,
		"FORUMS_POSTS_ROW_COUNTRY" => !empty($row['user_country']) ? $sed_countries[$row['user_country']] : $sed_countries['00'],
		"FORUMS_POSTS_ROW_COUNTRYFLAG" => sed_build_flag($row['user_country']),
		"FORUMS_POSTS_ROW_WEBSITE" => sed_build_url($row['user_website'], 36),
		"FORUMS_POSTS_ROW_WEBSITERAW" => $row['user_website'],
		"FORUMS_POSTS_ROW_EMAIL" => sed_build_email($row['user_email'], $row['user_hideemail']),
		"FORUMS_POSTS_ROW_LOCATION" => sed_cc($row['user_location']),
		"FORUMS_POSTS_ROW_OCCUPATION" => sed_cc($row['user_occupation']),
		"FORUMS_POSTS_ROW_AGE" => $row['user_age'],
		"FORUMS_POSTS_ROW_POSTCOUNT" => $row['user_postcount'],
		"FORUMS_POSTS_ROW_ODDEVEN" => sed_build_oddeven($fp_num),
		"FORUMS_POSTS_ROW" => $row,
	));

	/* === Hook - Part2 : Include === */
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$t->parse("MAIN.FORUMS_POSTS_ROW");
}

$allowreplybox = (!$cfg['antibumpforums']) ? TRUE : FALSE;
$allowreplybox = ($cfg['antibumpforums'] && $lastposterid > 0 && $lastposterid == $usr['id'] && $usr['auth_write']) ? FALSE : TRUE;

if (!$notlastpage && !$ft_state && $usr['id'] > 0 && $allowreplybox && $usr['auth_write']) {
	if ($quote > 0) {
		$sql4 = sed_sql_query("SELECT fp_id, fp_text, fp_postername, fp_posterid FROM $db_forum_posts WHERE fp_topicid='$q' AND fp_sectionid='$s' AND fp_id='$quote' LIMIT 1");
		if ($row4 = sed_sql_fetchassoc($sql4)) {
			$newmsg = "<blockquote>" . sed_link(sed_url("forums", "m=posts&p=" . $row4['fp_id'], "#" . $row4['fp_id']), "#" . $row4['fp_id']) . " <strong>" . $row4['fp_postername'] . " :</strong><br />" . $row4['fp_text'] . "</blockquote><br />";
		}
	}

	if (!empty($error_string)) {
		$t->assign("FORUMS_POSTS_NEWPOST_ERROR_BODY", sed_alert($error_string, 'e'));
		$t->parse("MAIN.FORUMS_POSTS_NEWPOST.FORUMS_POSTS_NEWPOST_ERROR");
	}

	$pfs = ($usr['id'] > 0) ? sed_build_pfs($usr['id'], "newpost", "newmsg", $L['Mypfs']) : '';
	$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, "newpost", "newmsg", $L['SFS']) : '';

	$t->assign(array(
		"FORUMS_POSTS_NEWPOST_SEND" => sed_url("forums", "m=posts&a=newpost&s=" . $s . "&q=" . $q),
		"FORUMS_POSTS_NEWPOST_TEXT" => sed_textarea('newmsg', isset($newmsg) ? $newmsg : '', 12, 80, 'Basic') . " " . $pfs,
		"FORUMS_POSTS_NEWPOST_TEXTONLY" => sed_textarea('newmsg', isset($newmsg) ? $newmsg : '', 12, 80, 'Basic'),
		"FORUMS_POSTS_NEWPOST_MYPFS" => $pfs
	));

	/* === Hook  === */
	$extp = sed_getextplugins('forums.posts.newpost.tags');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$t->parse("MAIN.FORUMS_POSTS_NEWPOST");
} elseif ($ft_state) {
	$t->assign("FORUMS_POSTS_TOPICLOCKED_BODY", sed_alert($L['Topiclocked'], 'e'));
	$t->parse("MAIN.FORUMS_POSTS_TOPICLOCKED");
} elseif (!$allowreplybox && !$notlastpage && !$ft_state && $usr['id'] > 0) {
	$t->assign("FORUMS_POSTS_ANTIBUMP_BODY", sed_alert($L['for_antibump'], 'e'));
	$t->parse("MAIN.FORUMS_POSTS_ANTIBUMP");
}

if ($ft_mode == 1) {
	$t->parse("MAIN.FORUMS_POSTS_TOPICPRIVATE");
}

/* === Hook  === */
$extp = sed_getextplugins('forums.posts.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
