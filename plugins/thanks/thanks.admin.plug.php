<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.admin.plug.php
Version=185
Type=Plugin
Description=Thanks administration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=admin.plug
File=thanks.admin.plug
Hooks=admin.plug
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $db_thanks, $db_users, $db_pages, $db_forum_posts, $db_forum_topics, $db_forum_sections, $db_com, $db_structure, $cfg;

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'thanks');
sed_block($usr['isadmin']);

$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=thanks")] = $L['thanks_title_short'];

$admintitle = $L['thanks_title_short'];
$adminhelp = '';

$a = sed_import('a', 'G', 'ALP');

$t = new XTemplate(sed_skinfile('admin.thanks', false, true));

if ($a == 'delete') {
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');
	if ($id > 0) {
		thanks_remove($id);
		sed_redirect(sed_url("admin", "m=thanks", "", true), false, array('msg' => '302'));
		exit;
	}
}

$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

$perpage = 30; //TODO: Make this configurable
$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_thanks"), 0, 'COUNT(*)');
$pagination = sed_pagination(sed_url("admin", "m=thanks"), $d, $totallines, $perpage);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=thanks"), $d, $totallines, $perpage, true);

if ($totallines > $perpage) {
	$t->assign(array(
		"THANKS_PAGINATION" => $pagination,
		"THANKS_PAGEPREV" => $pagination_prev,
		"THANKS_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_THANKS.THANKS_PAGINATION_TP");
}

$sql = sed_sql_query("SELECT t.*, u1.user_name AS from_name, u1.user_maingrp AS from_maingrp, u2.user_name AS to_name, u2.user_maingrp AS to_maingrp FROM $db_thanks AS t LEFT JOIN $db_users AS u1 ON t.th_fromuser = u1.user_id LEFT JOIN $db_users AS u2 ON t.th_touser = u2.user_id ORDER BY t.th_date DESC LIMIT " . $d . "," . $perpage);

while ($row = sed_sql_fetchassoc($sql)) {
	$from_link = sed_build_user($row['th_fromuser'], sed_cc($row['from_name']), $row['from_maingrp']);
	$to_link = sed_build_user($row['th_touser'], sed_cc($row['to_name']), $row['to_maingrp']);
	$item_info = thanks_resolve_item_info($row['th_ext'], $row['th_item'], $L);

	$t->assign(array(
		"THANKS_LIST_ID" => $row['th_id'],
		"THANKS_LIST_DATE" => sed_build_date(!empty($cfg['plugin']['thanks']['format']) ? $cfg['plugin']['thanks']['format'] : 'd.m.Y', $row['th_date']),
		"THANKS_LIST_FROM" => $from_link,
		"THANKS_LIST_TO" => $to_link,
		"THANKS_LIST_TYPE" => $item_info['type'],
		"THANKS_LIST_CATEGORY" => $item_info['category'],
		"THANKS_LIST_ITEM" => $item_info['item'],
		"THANKS_LIST_DELETE_URL" => sed_url("admin", "m=thanks&a=delete&id=" . $row['th_id'] . "&" . sed_xg())
	));
	$t->parse("ADMIN_THANKS.THANKS_LIST");
}

$t->assign(array(
	"ADMIN_THANKS_TITLE" => $admintitle,
	"ADMIN_THANKS_TOTAL" => $L['thanks_total'],
	"ADMIN_THANKS_TOTALITEMS" => $totallines
));

$t->parse("ADMIN_THANKS");
$adminmain .= $t->text("ADMIN_THANKS");
