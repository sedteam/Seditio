<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.polls.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('polls', 'a');
sed_block($usr['isadmin']);

require_once(SED_ROOT . '/system/core/polls/polls.functions.php');

$id = sed_import('id', 'G', 'TXT');
$po = sed_import('po', 'G', 'TXT');

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=polls")] =  $L['Polls'];

$admintitle = $L['Polls'];

$adminhelp = $L['adm_help_polls'];

$t = new XTemplate(sed_skinfile('admin.polls', false, true));

if ($n == 'options') {
	if ($a == 'update' && !empty($id) && !empty($po)) {
		$rtext = sed_import('rtext', 'P', 'HTM');
		$sql = sed_sql_query("UPDATE $db_polls_options SET po_text='" . sed_sql_prep($rtext) . "' WHERE po_id='$po' AND po_pollid='$id'");
	} elseif ($a == 'updatetitle' && !empty($id)) {
		$rtitle = sed_import('rtitle', 'P', 'HTM');
		$sql = sed_sql_query("UPDATE $db_polls SET poll_text='" . sed_sql_prep($rtitle) . "' WHERE poll_id='$id'");
	} elseif ($a == 'add' && !empty($id)) {
		$g = array('ntext');
		$ntext = sed_import('ntext', 'P', 'HTM');
		$sql = sed_sql_query("INSERT INTO $db_polls_options (po_pollid, po_text) VALUES (" . (int)$id . ",'" . sed_sql_prep($ntext) . "')");
	} elseif ($a == 'delete') {
		sed_check_xg();
		$sql = sed_sql_query("DELETE FROM $db_polls_options WHERE po_id='$po' AND po_pollid='$id'");
	}

	$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id='$id' ");
	$sql1 = sed_sql_query("SELECT * FROM $db_polls_options WHERE po_pollid='$id' ORDER by po_id ASC");

	$row = sed_sql_fetchassoc($sql);

	$urlpaths[sed_url("admin", "m=polls&n=options&id=" . $id)] =  $L['Options'] . " (#$id)";
	$admintitle = $L['Options'] . " (#$id)";

	$t->assign(array(
		"POLL_EDIT_ID" => $row["poll_id"],
		"POLL_EDIT_SEND" => sed_url("admin", "m=polls&n=options&a=updatetitle&id=" . $id),
		"POLL_EDIT_TEXT" => sed_textbox('rtitle', sed_cc($row['poll_text']), 56, 255),
		"POLL_EDIT_CREATION_DATE" => sed_build_date($cfg['dateformat'], $row["poll_creationdate"])
	));

	while ($row1 = sed_sql_fetchassoc($sql1)) {

		$t->assign(array(
			"POLL_EDIT_OPTIONS_SEND" => sed_url("admin", "m=polls&n=options&a=update&id=" . $row1['po_pollid'] . "&po=" . $row1['po_id']),
			"POLL_EDIT_OPTIONS_DELETE_URL" => sed_url("admin", "m=polls&n=options&a=delete&id=" . $row1['po_pollid'] . "&po=" . $row1['po_id'] . "&" . sed_xg()),
			"POLL_EDIT_OPTIONS_ID" => $row1['po_id'],
			"POLL_EDIT_OPTIONS_TEXT" => sed_textbox('rtext', sed_cc($row1['po_text']), 32, 128)
		));

		$t->parse("ADMIN_POLLS.POLL_EDIT.OPTIONS_LIST");
	}

	$t->assign(array(
		"POLL_OPTIONS_ADD_SEND" => sed_url("admin", "m=polls&n=options&a=add&id=" . $row["poll_id"]),
		"POLL_OPTIONS_ADD_TEXT" => sed_textbox('ntext', '', 32, 128)
	));

	$t->parse("ADMIN_POLLS.POLL_EDIT");
} else {
	if ($a == 'delete') {
		sed_check_xg();
		$num = sed_poll_delete($id);
		sed_redirect(sed_url("admin", "m=polls&msg=916&rc=102&num=" . $num, "", true));
		exit;
	} elseif ($a == 'reset') {
		sed_check_xg();
		$num = sed_poll_reset($id);
		sed_redirect(sed_url("admin", "m=polls&msg=916&rc=102&num=" . $num, "", true));
		exit;
	}

	if ($a == 'bump') {
		sed_check_xg();
		sed_poll_bump($id);
		sed_redirect(sed_url("admin", "m=polls&msg=916&rc=102&num=1", "", true));
		exit;
	}

	if ($a == 'add') {
		$ntext = sed_import('ntext', 'P', 'HTM');
		$sql = sed_sql_query("INSERT INTO $db_polls (poll_state, poll_creationdate, poll_text, poll_ownerid) VALUES (0, " . (int)$sys['now_offset'] . ", '" . sed_sql_prep($ntext) . "', " . $usr['id'] . ")");
	}

	$sql = sed_sql_query("SELECT p.*, t.ft_id FROM $db_polls AS p
		LEFT JOIN $db_forum_topics AS t ON t.ft_poll = p.poll_id
		WHERE 1 ORDER BY p.poll_type ASC, p.poll_id DESC LIMIT 20");

	$ii = 0;
	$prev = -1;

	while ($row = sed_sql_fetchassoc($sql)) {
		$id = $row['poll_id'];
		$type = $row['poll_type'];

		$sql2 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$id'");
		$totalvotes = sed_sql_result($sql2, 0, "SUM(po_count)");

		$t->assign(array(
			"POLLS_LIST_DELETE_URL" => sed_url("admin", "m=polls&a=delete&id=" . $id . "&" . sed_xg()),
			"POLLS_LIST_RESET_URL" => sed_url("admin", "m=polls&a=reset&id=" . $id . "&" . sed_xg()),
			"POLLS_LIST_BUMP_URL" => sed_url("admin", "m=polls&a=bump&id=" . $id . "&" . sed_xg()),
			"POLLS_LIST_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['poll_creationdate']),
			"POLLS_LIST_OPTIONS_URL" => sed_url("admin", "m=polls&n=options&id=" . $row['poll_id']),
			"POLLS_LIST_POLLTEXT" => sed_cc($row['poll_text']),
			"POLLS_LIST_TOTALVOTES" => $totalvotes,
			"POLLS_LIST_OPEN_URL" => ($type == 0) ? sed_url("polls", "id=" . $row['poll_id']) : sed_url("forums", "m=posts&q=" . $row['ft_id'])
		));
		$t->parse("ADMIN_POLLS.POLLS.POLLS_LIST");

		$ii++;
	}

	$t->assign(array(
		"POLLS_TOTAL" => $ii,
		"POLL_ADD_SEND" => sed_url("admin", "m=polls&a=add"),
		"POLL_ADD_TEXT" => sed_textbox('ntext', '', 64, 255)
	));
	$t->parse("ADMIN_POLLS.POLLS");
}

$t->assign("ADMIN_POLLS_TITLE", $admintitle);

$t->parse("ADMIN_POLLS");

$adminmain .= $t->text("ADMIN_POLLS");
