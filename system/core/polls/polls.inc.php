<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=polls.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Polls
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* === Hook === */
$extp = sed_getextplugins('polls.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('polls', 'a');
sed_block($usr['auth_read']);

$id = sed_import('id', 'G', 'ALP', 8);
$vote = sed_import('vote', 'G', 'INT');
$pvote = sed_import('pvote', 'P', 'INT');
$stndl = sed_import('stndl', 'G', 'BOL');
$comments = sed_import('comments', 'G', 'BOL');
$ratings = sed_import('ratings', 'G', 'BOL');
$ajax = sed_import('ajax', 'G', 'BOL');

$votecasted = FALSE;
$alreadyvoted = FALSE;

$vote = ($pvote) ? $pvote : $vote;
$id = (empty($id)) ? 'viewall' : $id;

$standalone = ($stndl) ? TRUE : FALSE;
$standalone_url = ($stndl) ? "&stndl=1" : "";

$out['subtitle'] = $L['Polls'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("polls")] = $L['polls_viewarchives'];

if (empty($id) || $id == 'viewall') {
	$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_state=0 AND poll_type=0 ORDER BY poll_id DESC");
	$title = $L['polls_viewarchives'];
} else {
	$id = sed_import($id, 'D', 'INT');

	if ($id > 0) {
		$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id='$id' AND poll_state=0");
		sed_die(sed_sql_numrows($sql) == 0);

		if ($row = sed_sql_fetchassoc($sql)) {
			$poll_state = $row['poll_state'];
			$poll_id = $row['poll_id'];
			$poll_title = $row['poll_text'];
			$poll_creationdate = $row['poll_creationdate'];

			$title = $poll_title;

			$urlpaths[sed_url("polls", "id=" . $id)] = $poll_title;

			if ($usr['id'] > 0) {
				$sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND (pv_userid='" . $usr['id'] . "' OR pv_userip='" . $usr['ip'] . "') LIMIT 1");
			} else {
				$sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND pv_userip='" . $usr['ip'] . "' LIMIT 1");
			}

			$alreadyvoted = (sed_sql_numrows($sql2) > 0) ? 1 : 0;

			if ($a == 'send' && empty($error_string) && !$alreadyvoted) {
				$sql2 = sed_sql_query("UPDATE $db_polls_options SET po_count=po_count+1 WHERE po_pollid='$id' AND po_id='$vote'");
				if (sed_sql_affectedrows() == 1) {
					$sql2 = sed_sql_query("INSERT INTO $db_polls_voters (pv_pollid, pv_userid, pv_userip) VALUES (" . (int)$id . ", " . (int)$usr['id'] . ", '" . $usr['ip'] . "')");
					$votecasted = TRUE;
					$alreadyvoted = TRUE;
				}
			}

			$sql2 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$id'");
			$totalvotes = sed_sql_result($sql2, 0, "SUM(po_count)");

			$sql1 = sed_sql_query("SELECT po_id, po_text, po_count FROM $db_polls_options WHERE po_pollid='$id' ORDER by po_id ASC");
			$error_string = (sed_sql_numrows($sql1) < 1) ? $L['msg404_1'] : '';
		} else {
			$error_string = $L['msg404_1'];
		}
	} else {
		sed_die();
	}
}

$out['subtitle'] = $title;

$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('polltitle', $title_tags, $title_data);

/* ============= */

if ($standalone) {
	sed_sendheaders();

	sed_add_javascript('system/javascript/core.js', true);
	sed_add_javascript($morejavascript);
	sed_add_css($morecss);

	/* === Hook === */
	$extp = sed_getextplugins('polls.stndl');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$polls_header1 = $cfg['doctype'] . "\n<html>\n<head>
	<title>" . $cfg['maintitle'] . "</title>" . sed_htmlmetas() . $moremetas . sed_css();
	$polls_header2 = "</head>\n<body>";
	$polls_footer1 = sed_javascript();
	$polls_footer2 = "</body>\n</html>";

	$mskin = sed_skinfile(array('polls', 'standalone'));
	$t = new XTemplate($mskin);

	$t->assign(array(
		"POLLS_STANDALONE_HEADER1" => $polls_header1,
		"POLLS_STANDALONE_HEADER2" => $polls_header2,
		"POLLS_STANDALONE_FOOTER1" => $polls_footer1,
		"POLLS_STANDALONE_FOOTER2" => $polls_footer2
	));

	$t->parse("MAIN.POLLS_STANDALONE_HEADER");
	$t->parse("MAIN.POLLS_STANDALONE_FOOTER");
} else {
	require(SED_ROOT . "/system/header.php");

	/* === Hook === */
	$extp = sed_getextplugins('polls.main');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$mskin = sed_skinfile('polls');
	$t = new XTemplate($mskin);
}


if (empty($id) || $id == 'viewall') {

	$title = $L['polls_viewarchives'];

	if (sed_sql_numrows($sql) == 0) {
		$t->parse("MAIN.POLLS_VIEWALL.POLLS_NONE");
	} else {
		while ($row = sed_sql_fetchassoc($sql)) {
			$t->assign(array(
				"POLLS_LIST_URL" => sed_url("polls", "id=" . $row['poll_id'] . $standalone_url),
				"POLLS_LIST_TEXT" => $row['poll_text'],
				"POLLS_LIST_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['poll_creationdate'])
			));
			$t->parse("MAIN.POLLS_VIEWALL.POLLS_LIST");
		}
	}

	$t->parse("MAIN.POLLS_VIEWALL");
} else {

	if (!empty($error_string)) {
		$t->assign("POLL_ERROR_BODY", $error_string);
		$t->parse("MAIN.POLL_ERROR");
	}

	$xpoll = new XTemplate(sed_skinfile('poll'));

	while ($row1 = sed_sql_fetchassoc($sql1)) {
		$po_id = $row1['po_id'];
		$po_count = $row1['po_count'];
		$po_text = sed_cc($row1['po_text']);
		$percent = ($totalvotes > 0) ? @round(100 * ($po_count / $totalvotes), 1) : 0;
		$percentbar = floor($percent * 2.24);

		$xpoll->assign(array(
			"POLL_ROW_URL" => sed_url("polls", "a=send&" . sed_xg() . "&id=" . $id . "&vote=" . $po_id . $standalone_url),
			"POLL_ROW_TEXT" => sed_cc($row1['po_text']),
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

		$ajax_send = sed_url("polls", "a=send&" . sed_xg() . "&id=" . $id . $standalone_url . "&ajax=1");
		$onclick = ($cfg['ajax']) ? "event.preventDefault(); sedjs.ajaxbind({'url': '" . $ajax_send . "', 'format':  'html', 'method':  'POST', 'update':  '#pollajx', 'loading': '#pollvotes', 'formid':  '#pollvotes'});" : "";

		$xpoll->assign(array(
			"POLL_BUTTON_ONCLICK" => $onclick,
			"POLL_SEND_URL" => sed_url("polls", "a=send&" . sed_xg() . "&id=" . $id . $standalone_url)
		));
		$xpoll->parse("POLL.POLL_FORM");
	}

	$item_code = 'v' . $id;

	$url_poll = array('part' => 'polls', 'params' => "id=" . $id . $standalone_url . "&comments=1");

	list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_poll, $comments);

	$title = $L['Poll'] . "#" . $poll_id;

	$xpoll->assign(array(
		"POLL_VOTERS" => $totalvotes,
		"POLL_SINCE" => sed_build_date($cfg['dateformat'], $poll_creationdate),
		"POLL_TITLE" => $poll_title,
		"POLL_INFO" => $polls_info
	));

	if (!empty($comments_link) && !$ajax) {
		$xpoll->assign(array(
			"POLL_COMMENTS" => $comments_link,
			"POLL_COMMENTS_URL" => sed_url('polls', "id=" . $id . $standalone_url . "&comments=1"),
			"POLL_COMMENTS_DISPLAY" => $comments_display,
			"POLL_COMMENTS_COUNT" => $comments_count,
			"POLL_COMMENTS_ISSHOW" => ($comments) ? " active" : "",
			"POLL_COMMENTS_JUMP" => ($comments) ? "<span class=\"spoiler-jump\"></span>" : ""
		));
		$xpoll->parse("POLL.POLL_COMMENTS");
	}

	$xpoll->parse("POLL");
	$res_poll = $xpoll->text("POLL");
	sed_ajax_flush($res_poll, $ajax);  // AJAX Output	
	$t->assign("POLL_VIEW", $res_poll);
}

$t->assign(array(
	"POLLS_TITLE" => $title,
	"POLLS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"POLLS_VIEWALL" => sed_link(sed_url("polls", "id=viewall" . $standalone_url), $L['polls_viewarchives'])
));

/* === Hook === */
$extp = sed_getextplugins('polls.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

if ($standalone) {
	@ob_end_flush();
	@ob_end_flush();
	sed_sql_close($connection_id);
} else {
	require(SED_ROOT . "/system/footer.php");
}
