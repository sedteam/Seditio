<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.trashcan.php
Version=186
Updated=2026-sep-21
Type=Module
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forums
Part=trashcan
Hooks=trashcan.api
File=forums.trashcan
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Type registry for trashcan API (trashcan.api)
if (isset($sed_trashcan_types) && is_array($sed_trashcan_types)) {
	$sed_trashcan_types['forumpost'] = array(
		'title'   => isset($L['Post']) ? $L['Post'] : 'Post',
		'icon'    => 'system/img/admin/forums.png',
		'restore' => 'sed_trash_forumpost_restore'
	);

	$sed_trashcan_types['forumtopic'] = array(
		'title'   => isset($L['Topic']) ? $L['Topic'] : 'Topic',
		'icon'    => 'system/img/admin/forums.png',
		'restore' => 'sed_trash_forumtopic_restore'
	);
}

/**
 * Restore forum post from trashcan
 *
 * @param array $data Post record row data
 * @param mixed $itemid Post item identifier (e.g. p12-q34)
 * @return bool
 */
function sed_trash_forumpost_restore($data, $itemid)
{
	if (function_exists('sed_forum_resynctopic')) {
		global $db_forum_topics, $db_forum_posts, $db_trash;

		$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE ft_id='" . sed_sql_prep($data['fp_topicid']) . "'");

		if ($row = sed_sql_fetchassoc($sql)) {
			sed_trash_insert($data, $db_forum_posts);
			sed_log("Post #" . $itemid . " restored from the trash can.", 'adm');
			sed_forum_resynctopic($data['fp_topicid']);
			sed_forum_sectionsetlast($data['fp_sectionid']);
			sed_forum_resync($data['fp_sectionid']);
			return true;
		} else {
			$sql1 = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumtopic' AND tr_itemid='q" . sed_sql_prep($data['fp_topicid']) . "'");
			if ($row1 = sed_sql_fetchassoc($sql1)) {
				sed_trash_restore($row1['tr_id']);
				sed_trash_delete($row1['tr_id']);
			}
		}
	}

	return false;
}

/**
 * Restore forum topic and its posts from trashcan
 *
 * @param array $data Topic record row data
 * @param mixed $itemid Topic item identifier (e.g. q34 or 34)
 * @return bool
 */
function sed_trash_forumtopic_restore($data, $itemid)
{
	if (function_exists('sed_forum_resynctopic')) {
		global $db_forum_topics, $db_forum_posts, $db_trash;

		sed_trash_insert($data, $db_forum_topics);
		sed_log("Topic #" . $data['ft_id'] . " restored from the trash can.", 'adm');

		$clean_itemid = (strpos($itemid, 'q') === 0) ? substr($itemid, 1) : $itemid;
		$sql = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumpost' AND tr_itemid LIKE '%-" . sed_sql_prep($itemid) . "'");

		while ($row = sed_sql_fetchassoc($sql)) {
			$res2 = sed_trash_get($row['tr_id']);
			if (is_array($res2)) {
				sed_trash_insert($res2['tr_datas'], $db_forum_posts);
				sed_trash_delete($row['tr_id']);
				sed_log("Post #" . $res2['tr_datas']['fp_id'] . " restored from the trash can (belongs to topic #" . $res2['tr_datas']['fp_topicid'] . ").", 'adm');
			}
		}

		sed_forum_resynctopic($clean_itemid);
		sed_forum_sectionsetlast($data['ft_sectionid']);
		sed_forum_resync($data['ft_sectionid']);
		return true;
	}

	return false;
}
