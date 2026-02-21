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

if ($a == 'delete') {
	sed_check_xg();

	$sql = sed_sql_query("SELECT * FROM $db_com WHERE com_id='$id' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);

	$sql = sed_sql_query("DELETE FROM $db_com WHERE com_id='$id'");

	if (mb_substr($row['com_code'], 0, 1) == 'p') {
		$page_id = mb_substr($row['com_code'], 1, 10);
		$sql = sed_sql_query("UPDATE $db_pages SET page_comcount=" . sed_get_comcount($row['com_code']) . " WHERE page_id=" . $page_id);
	}
}

$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

$perpage = (isset($cfg['plugin']['comments']['maxcommentsperpage']) ? (int)$cfg['plugin']['comments']['maxcommentsperpage'] : 30) * 2;
$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_com"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=comments"), $d, $totallines, $perpage);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=comments"), $d, $totallines, $perpage, TRUE);

$sql = sed_sql_query("SELECT * FROM $db_com WHERE 1 ORDER BY com_id DESC LIMIT $d," . $perpage);

$t = new XTemplate(sed_skinfile('admin.comments', false, true));

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
