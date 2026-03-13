<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.admin.plug.php
Version=185
Updated=2026-feb-18
Type=Plugin
Author=Seditio Team
Description=Comments administration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=admin.plug
Hooks=admin.plug
File=comments.admin.plug
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'comments');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=comments")] = $L['Comments'];

$admintitle = $L['Comments'];

$t = new XTemplate(sed_skinfile('admin.comments', false, true));

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_COMMENTS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=plug&p=comments"));
	$t->parse("ADMIN_COMMENTS.COMMENTS_BUTTONS");
}

if ($a == 'delete') {
	sed_check_xg();

	$sql = sed_sql_query("SELECT * FROM $db_com WHERE com_id='$id' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	if (!$row) {
		sed_redirect(sed_url("admin", "m=comments", "", true), false, ['msg' => '302']);
		exit;
	}

	$ids_to_delete = array((int)$id);
	$queue = array((int)$id);
	while (!empty($queue)) {
		$pid = (int)array_shift($queue);
		$ch = sed_sql_query("SELECT com_id FROM $db_com WHERE com_parent='$pid'");
		while ($chr = sed_sql_fetchassoc($ch)) {
			$ids_to_delete[] = (int)$chr['com_id'];
			$queue[] = (int)$chr['com_id'];
		}
	}

	if ($cfg['trash_comment']) {
		$ids_str = implode(',', array_map('intval', $ids_to_delete));
		$sql = sed_sql_query("SELECT * FROM $db_com WHERE com_id IN ($ids_str)");
		$rows_by_id = array();
		$tree = array();
		while ($r = sed_sql_fetchassoc($sql)) {
			$rows_by_id[(int)$r['com_id']] = $r;
			$pid = isset($r['com_parent']) ? (int)$r['com_parent'] : 0;
			if (!isset($tree[$pid])) $tree[$pid] = array();
			$tree[$pid][] = (int)$r['com_id'];
		}
		$path = array();
		$path[(int)$id] = (string)$id;
		$queue = array((int)$id);
		while (!empty($queue)) {
			$pid = array_shift($queue);
			if (isset($tree[$pid])) {
				foreach ($tree[$pid] as $ch_id) {
					$path[$ch_id] = $path[$pid] . '-' . $ch_id;
					$queue[] = $ch_id;
				}
			}
		}
		$order = array((int)$id);
		$queue = array((int)$id);
		while (!empty($queue)) {
			$pid = array_shift($queue);
			if (isset($tree[$pid])) {
				foreach ($tree[$pid] as $ch_id) {
					$order[] = $ch_id;
					$queue[] = $ch_id;
				}
			}
		}
		foreach ($order as $cid) {
			$r = $rows_by_id[$cid];
			sed_trash_put('comment', $L['Comment'] . " #" . $cid . " (" . $r['com_author'] . ")", $path[$cid], $r);
		}
	}

	$ids_str = implode(',', array_map('intval', $ids_to_delete));
	sed_sql_query("DELETE FROM $db_com WHERE com_id IN ($ids_str)");

	if (mb_substr($row['com_code'], 0, 1) == 'p') {
		$page_id = mb_substr($row['com_code'], 1, 10);
		sed_sql_query("UPDATE $db_pages SET page_comcount=" . sed_get_comcount($row['com_code']) . " WHERE page_id=" . $page_id);
	}
	sed_redirect(sed_url("admin", "m=comments", "", true), false, ['msg' => '302']);
	exit;
}

$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

$perpage = (isset($cfg['plugin']['comments']['maxcommentsperpage']) ? (int)$cfg['plugin']['comments']['maxcommentsperpage'] : 30) * 2;
$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_com"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=comments"), $d, $totallines, $perpage);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=comments"), $d, $totallines, $perpage, TRUE);

$sql = sed_sql_query("SELECT * FROM $db_com WHERE 1 ORDER BY com_id DESC LIMIT $d," . $perpage);

if (!empty($pagination)) {
	$t->assign(array(
		"COMMENTS_PAGINATION" => $pagination,
		"COMMENTS_PAGEPREV" => $pagination_prev,
		"COMMENTS_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_COMMENTS.COMMENTS_PAGINATION_TP");
	$t->parse("ADMIN_COMMENTS.COMMENTS_PAGINATION_BM");
}

$ii = 0;

while ($row = sed_sql_fetchassoc($sql)) {
	$row['com_text'] = sed_cutstring(strip_tags($row['com_text']), 80);
	$row['com_url'] = sed_comments_item_url($row['com_code'], "#c" . $row['com_id']);

	$t->assign(array(
		"COMMENTS_LIST_DELETE_URL" => sed_url("admin", "m=comments&a=delete&id=" . $row['com_id'] . "&" . sed_xg()),
		"COMMENTS_LIST_ID" => $row['com_id'],
		"COMMENTS_LIST_CODE" => $row['com_code'],
		"COMMENTS_LIST_AUTHOR" => $row['com_author'],
		"COMMENTS_LIST_DATE" => sed_build_date($cfg['dateformat'], $row['com_date']),
		"COMMENTS_LIST_TEXT" => $row['com_text'],
		"COMMENTS_LIST_OPEN_URL" => $row['com_url']
	));

	$t->parse("ADMIN_COMMENTS.COMMENTS_LIST");

	$ii++;
}

$t->assign(array(
	"COMMENTS_TOTAL" => $ii
));

$t->assign("ADMIN_COMMENTS_TITLE", $admintitle);

$t->parse("ADMIN_COMMENTS");

$adminmain .= $t->text("ADMIN_COMMENTS");
