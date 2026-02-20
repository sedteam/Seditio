<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.admin.plug.php
Version=185
Updated=2026-feb-18
Type=Plugin
Description=Ratings administration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=admin.plug
Hooks=admin.plug
File=ratings.admin.plug
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'ratings');
sed_block($usr['isadmin']);

$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=ratings")] = $L['Ratings'];

$admintitle = $L['Ratings'];

$adminhelp = isset($L['adm_help_ratings']) ? $L['adm_help_ratings'] : '';

$admin_ratings_tpl = SED_ROOT . '/plugins/ratings/tpl/admin.ratings.tpl';
if (file_exists($admin_ratings_tpl)) {
	$t = new XTemplate($admin_ratings_tpl);
} else {
	$t = new XTemplate(sed_skinfile('admin.ratings', false, true));
}

$id = sed_import('id', 'G', 'TXT');
$ii = 0;
$jj = 0;

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_RATINGS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=plugin&p=ratings"));
	$t->parse("ADMIN_RATINGS.RATINGS_BUTTONS.RATINGS_BUTTONS_CONFIG");
	$t->parse("ADMIN_RATINGS.RATINGS_BUTTONS");
}

if ($a == 'delete') {
	sed_check_xg();
	$id = sed_sql_prep($id);
	sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='" . $id . "' ");
	sed_sql_query("DELETE FROM $db_rated WHERE rated_code='" . $id . "' ");
	sed_redirect(sed_url("admin", "m=ratings", "", true));
	exit;
}

$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

$perpage = (isset($cfg['plugin']['ratings']['maxratsperpage']) ? (int)$cfg['plugin']['ratings']['maxratsperpage'] : 30) * 2;
$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_ratings"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=ratings"), $d, $totallines, $perpage);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=ratings"), $d, $totallines, $perpage, true);

if (!empty($pagination)) {
	$t->assign(array(
		"RATINGS_PAGINATION" => $pagination,
		"RATINGS_PAGEPREV" => $pagination_prev,
		"RATINGS_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_RATINGS.RATINGS_PAGINATION_TP");
	$t->parse("ADMIN_RATINGS.RATINGS_PAGINATION_BM");
}

$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE 1 ORDER BY rating_id DESC LIMIT $d," . $perpage);

while ($row = sed_sql_fetchassoc($sql)) {
	$id2 = sed_sql_prep($row['rating_code']);
	$sql1 = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='" . $id2 . "'");
	$votes = sed_sql_result($sql1, 0, "COUNT(*)");

	$rat_url = function_exists('sed_ratings_item_url') ? sed_ratings_item_url($row['rating_code']) : '';

	$t->assign(array(
		"RATINGS_LIST_DELETE_URL" => sed_url("admin", "m=ratings&a=delete&id=" . $row['rating_code'] . "&" . sed_xg()),
		"RATINGS_LIST_CODE" => $row['rating_code'],
		"RATINGS_LIST_CREATIONDATE" => sed_build_date($cfg['dateformat'], $row['rating_creationdate']),
		"RATINGS_LIST_VOTES" => $votes,
		"RATINGS_LIST_AVERAGE" => $row['rating_average'],
		"RATINGS_LIST_URL" => $rat_url
	));

	$t->parse("ADMIN_RATINGS.RATINGS_LIST");

	$ii++;
	$jj = $jj + $votes;
}

$t->assign(array(
	"ADMIN_RATINGS_TOTALITEMS" => $ii,
	"ADMIN_RATINGS_TOTALVOTES" => $jj
));

$t->assign("ADMIN_RATINGS_TITLE", $admintitle);

$t->parse("ADMIN_RATINGS");

$adminmain .= $t->text("ADMIN_RATINGS");
