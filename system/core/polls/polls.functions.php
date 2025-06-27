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
Description=Polls functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

function sed_poll_add($part)
{
	global $t;

	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');

	if (is_array($poll_option_arr)) {
		foreach ($poll_option_arr as $v) {
			if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT');
		}
	} else {
		$poll_options = array(0 => '', 1 => '');
	}

	$i = 1;
	foreach ($poll_options as $nop) {
		$t->assign(array(
			"NEW_POLL_OPTION" => sed_textbox('poll_option[]', $nop, 32, 128),
			"NEW_POLL_NUM" => $i
		));
		$i++;
		if ($i > 2) $t->parse($part . "_NEW_POLL.NEW_POLL_OPTIONS.NEW_POLL_OPTIONS_DELETE");
		$t->parse($part . "_NEW_POLL.NEW_POLL_OPTIONS");
	}

	$t->assign(array(
		"NEW_POLL_TEXT" => sed_textbox('poll_text', $poll_text, 64, 255)
	));

	$t->parse($part . "_NEW_POLL");
}

function sed_poll_addsave($type, $code)
{
	global $cfg, $usr, $L, $sys, $db_polls, $db_polls_options;

	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');

	foreach ($poll_option_arr as $v) {
		if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT');
	}

	$sql = sed_sql_query(
		"INSERT INTO $db_polls 
		(
		poll_type, 
		poll_state, 
		poll_creationdate, 
		poll_text, 
		poll_ownerid, 
		poll_code
		) 
		VALUES 
		(
		'" . sed_sql_prep($type) . "', 
		0, 
		" . (int)$sys['now_offset'] . ", 
		'" . sed_sql_prep($poll_text) . "', 
		'" . (int)$usr['id'] . "', 
		'" . (int)$code . "')"
	);

	$poll_id = sed_sql_insertid();

	foreach ($poll_options as $npo) {
		$sql2 = sed_sql_query(
			"INSERT into $db_polls_options 
			(
			po_pollid, 
			po_text, 
			po_count
			) 
			VALUES 
			(
			'" . $poll_id . "', 
			'" . sed_sql_prep($npo) . "', 
			'0')"
		);
	}
	return ($poll_id);
}

function sed_poll_edit($part, $poll_id)
{
	global $t, $db_polls, $db_polls_options;

	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');

	$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id = '" . $poll_id . "' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	$poll_text = (empty($poll_text)) ? $row['poll_text'] : $poll_text;

	$sql = sed_sql_query("SELECT * FROM $db_polls_options WHERE po_pollid = '" . $poll_id . "' ORDER by po_id ASC");
	if (sed_sql_numrows($sql) > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$poll_options[$row['po_id']] = $row['po_text'];
		}
	}

	if (is_array($poll_option_arr)) {
		foreach ($poll_option_arr as $key => $v) {
			if (!empty($v)) $poll_options[$key] = sed_import($v, 'D', 'TXT');
		}
	} elseif (count($poll_options) == 0) {
		$poll_options = array(0 => '', 1 => '');
	}

	$i = 1;
	foreach ($poll_options as $key => $nop) {
		$t->assign(array(
			"EDIT_POLL_OPTION" => sed_textbox('poll_option[' . $key . ']', $nop, 32, 128),
			"EDIT_POLL_NUM" => $i
		));
		$i++;
		if ($i > 2) $t->parse($part . "_EDIT_POLL.EDIT_POLL_OPTIONS.EDIT_POLL_OPTIONS_DELETE");
		$t->parse($part . "_EDIT_POLL.EDIT_POLL_OPTIONS");
	}

	$t->assign(array(
		"EDIT_POLL_TEXT" => sed_textbox('poll_text', $poll_text, 64, 255)
	));

	$t->parse($part . "_EDIT_POLL");
}

function sed_poll_editsave($poll_id)
{
	global $cfg, $usr, $L, $sys, $db_polls, $db_polls_options;

	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');

	foreach ($poll_option_arr as $key => $v) {
		if (!empty($v)) {
			$poll_options[$key] = sed_import($v, 'D', 'TXT');
			$poll_options_keys[] = $key; //exists's variants
		}
	}

	$sql = sed_sql_query("UPDATE $db_polls SET poll_text='" . sed_sql_prep($poll_text) . "' WHERE poll_id='" . (int)$poll_id . "'");
	$sql2 = sed_sql_query("DELETE FROM $db_polls_options WHERE po_pollid='" . (int)$poll_id . "' AND po_id NOT IN (" . implode(',', $poll_options_keys) . ")");

	foreach ($poll_options as $key => $npo) {
		$sql3 = sed_sql_query("INSERT INTO $db_polls_options SET po_id='" . $key . "', po_pollid='" . $poll_id . "', po_text = '" . sed_sql_prep($npo) . "', po_count = 0 
								ON DUPLICATE KEY UPDATE po_text = '" . sed_sql_prep($npo) . "'");
	}
}

function sed_poll_check()
{
	global $error_string, $L;

	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');

	if (is_array($poll_option_arr)) {
		foreach ($poll_option_arr as $v) {
			if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT');
		}
	}

	if (empty($poll_text) || count($poll_options) < 2) {
		$error_string .= $L['polls_emptytitle'] . "<br />";
	}
}

function sed_poll_delete($id)
{
	global $db_polls, $db_polls_options, $db_polls_voters, $db_com, $db_forum_topics;
	$id = (int) $id;
	$num = 0;
	if ($id != 0) {
		$sql = sed_sql_query("SELECT poll_id FROM $db_polls WHERE poll_code = '" . $id . "' LIMIT 1");
		if (sed_sql_numrows($sql) > 0) {
			$row = sed_sql_fetchassoc($sql);
			if ($row['poll_type'] == 1 && !empty($row['poll_code'])) // forum poll type
			{
				//deattach poll from topic
				$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_poll = 0 WHERE ft_id = " . (int)$row['poll_code']);
			}
		}
		$sql = sed_sql_query("DELETE FROM $db_polls WHERE poll_id=" . $id);
		$num = sed_sql_affectedrows();
		$sql = sed_sql_query("DELETE FROM $db_polls_options WHERE po_pollid=" . $id);
		$num = $num + sed_sql_affectedrows();
		$sql = sed_sql_query("DELETE FROM $db_polls_voters WHERE pv_pollid=" . $id);
		$num = $num + sed_sql_affectedrows();
		$id2 = "v" . $id;
		$sql = sed_sql_query("DELETE FROM $db_com WHERE com_code='$id2'");
		$num = $num + sed_sql_affectedrows();
	}
	return $num;
}

function sed_poll_vote($id, $vote)
{
	global $db_polls_options, $db_polls_voters, $usr;

	$id = (int) $id;
	$votecasted = FALSE;
	$alreadyvoted = FALSE;

	if ($id > 0) {
		if ($usr['id'] > 0) {
			$sql = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND (pv_userid='" . $usr['id'] . "' OR pv_userip='" . $usr['ip'] . "') LIMIT 1");
		} else {
			$sql = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND pv_userip='" . $usr['ip'] . "' LIMIT 1");
		}

		$alreadyvoted = (sed_sql_numrows($sql) > 0) ? TRUE : FALSE;

		if (sed_sql_numrows($sql) == 0 && $vote > 0) {
			$sql2 = sed_sql_query("UPDATE $db_polls_options SET po_count=po_count+1 WHERE po_pollid='$id' AND po_id='$vote'");
			if (sed_sql_affectedrows() == 1) {
				$sql2 = sed_sql_query("INSERT INTO $db_polls_voters (pv_pollid, pv_userid, pv_userip) VALUES (" . (int)$id . ", " . (int)$usr['id'] . ", '" . $usr['ip'] . "')");
				$votecasted = TRUE;
				$alreadyvoted = TRUE;
			}
		}
	}
	return (array($alreadyvoted, $votecasted));
}

function sed_poll_reset($id)
{
	global $db_polls_options, $db_polls_voters;
	$id = (int) $id;
	$sql = sed_sql_query("DELETE FROM $db_polls_voters WHERE pv_pollid='$id'");
	$num = sed_sql_affectedrows();
	$sql = sed_sql_query("UPDATE $db_polls_options SET po_count=0 WHERE po_pollid='$id'");
	$num = $num + sed_sql_affectedrows();
	return $num;
}

function sed_poll_bump($id)
{
	global $db_polls, $sys;
	$id = (int) $id;
	$sql = sed_sql_query("UPDATE $db_polls SET poll_creationdate='" . $sys['now_offset'] . "' WHERE poll_id='$id'");
}
