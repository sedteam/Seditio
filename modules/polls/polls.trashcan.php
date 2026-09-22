<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/polls/polls.trashcan.php
Version=186
Updated=2026-sep-21
Type=Module
Author=Seditio Team
Description=Polls trashcan API integration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=polls
Part=trashcan
File=polls.trashcan
Hooks=trashcan.api
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $sed_trashcan_types, $L;

$sed_trashcan_types['poll'] = array(
	'title'   => isset($L['Poll']) ? $L['Poll'] : 'Poll',
	'icon'    => 'system/img/admin/polls.png',
	'restore' => 'sed_trash_poll_restore'
);

/**
 * Restore poll from trashcan
 *
 * @param array $data Serialized trash data
 * @return bool
 */
function sed_trash_poll_restore($data, $itemid = 0)
{
	global $db_polls, $db_polls_options, $db_polls_voters, $db_forum_topics;

	if (!is_array($data) || empty($data['poll']) || !is_array($data['poll'])) {
		return false;
	}

	$poll = $data['poll'];
	$poll_id = (int)$poll['poll_id'];

	sed_sql_query("DELETE FROM $db_polls WHERE poll_id = " . $poll_id);
	sed_sql_query("DELETE FROM $db_polls_options WHERE po_pollid = " . $poll_id);
	sed_sql_query("DELETE FROM $db_polls_voters WHERE pv_pollid = " . $poll_id);

	$fields = array();
	$values = array();
	foreach ($poll as $k => $v) {
		$fields[] = $k;
		$values[] = "'" . sed_sql_prep($v) . "'";
	}
	sed_sql_query("INSERT INTO $db_polls (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")");

	if (!empty($data['options']) && is_array($data['options'])) {
		foreach ($data['options'] as $opt) {
			if (is_array($opt)) {
				$o_fields = array();
				$o_values = array();
				foreach ($opt as $k => $v) {
					$o_fields[] = $k;
					$o_values[] = "'" . sed_sql_prep($v) . "'";
				}
				sed_sql_query("INSERT INTO $db_polls_options (" . implode(', ', $o_fields) . ") VALUES (" . implode(', ', $o_values) . ")");
			}
		}
	}

	if (!empty($data['voters']) && is_array($data['voters'])) {
		foreach ($data['voters'] as $voter) {
			if (is_array($voter)) {
				$v_fields = array();
				$v_values = array();
				foreach ($voter as $k => $v) {
					$v_fields[] = $k;
					$v_values[] = "'" . sed_sql_prep($v) . "'";
				}
				sed_sql_query("INSERT INTO $db_polls_voters (" . implode(', ', $v_fields) . ") VALUES (" . implode(', ', $v_values) . ")");
			}
		}
	}

	if (!empty($poll['poll_type']) && $poll['poll_type'] == 1 && !empty($poll['poll_code']) && isset($db_forum_topics)) {
		sed_sql_query("UPDATE $db_forum_topics SET ft_poll = " . $poll_id . " WHERE ft_id = " . (int)$poll['poll_code']);
	}

	return true;
}
