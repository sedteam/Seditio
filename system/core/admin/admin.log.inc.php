<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.statistics.log.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('log', 'a');
sed_block($usr['auth_read']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=log")] =  $L['Log'];

$admintitle = $L['Log'];

$log_groups = array(
	'all' => $L['All'],
	'def' => $L['Default'],
	'adm' => $L['Administration'],
	'for' => $L['Forums'],
	'sec' => $L['Security'],
	'usr' => $L['Users'],
	'plg' => $L['Plugins']
);

$d = sed_import('d', 'G', 'INT');
if (empty($d)) {
	$d = 0;
}

if ($a == 'purge' && $usr['isadmin']) {
	sed_check_xg();
	$sql = sed_sql_query("TRUNCATE $db_logger");
}

$totaldblog = sed_sql_rowcount($db_logger);

$n = (empty($n)) ? 'all' : $n;

$clear_all = ($usr['isadmin']) ? "&nbsp;<a href=\"" . sed_url("admin", "m=log&a=purge&" . sed_xg()) . "\" class=\"btn\">" . $L['adm_purgeall'] . " (" . $totaldblog . ")</a>" : '';

$group_select = $L['Group'] . " : <select name=\"groups\" size=\"1\" onchange=\"sedjs.redirect(this)\">";

foreach ($log_groups as $grp_code => $grp_name) {
	$selected = ($grp_code == $n) ? "selected=\"selected\"" : "";
	$group_select .= "<option value=\"" . sed_url("admin", "m=log&n=" . $grp_code) . "\" $selected>" . $grp_name . "</option>";
}

$group_select .= "</select>";

$totallines = ($n == 'all') ? $totaldblog : sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_logger WHERE log_group='$n'"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=log&n=" . $n), $d, $totallines, 100);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=log&n=" . $n), $d, $totallines, 100, TRUE);

if ($n == 'all')
	$sql = sed_sql_query("SELECT * FROM $db_logger WHERE 1 ORDER by log_id DESC LIMIT $d, 100");
else
	$sql = sed_sql_query("SELECT * FROM $db_logger WHERE log_group='$n' ORDER by log_id DESC LIMIT $d,100");

$t = new XTemplate(sed_skinfile('admin.log', false, true));

if (!empty($pagination)) {
	$t->assign(array(
		"LOG_PAGINATION" => $pagination,
		"LOG_PAGEPREV" => $pagination_prev,
		"LOG_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_LOG.LOG_PAGINATION_TP");
	$t->parse("ADMIN_LOG.LOG_PAGINATION_BM");
}

while ($row = sed_sql_fetchassoc($sql)) {
	$t->assign(array(
		"LOG_LIST_ID" => $row['log_id'],
		"LOG_LIST_DATE" => sed_build_date($cfg['dateformat'], $row['log_date']),
		"LOG_LIST_IP" => "<a href=\"" . sed_url("admin", "m=manage&p=ipsearch&a=search&id=" . $row['log_ip'] . "&" . sed_xg()) . "\">" . $row['log_ip'] . "</a>",
		"LOG_LIST_USER" => $row['log_name'],
		"LOG_LIST_GROUP" => "<a href=\"" . sed_url("admin", "m=log&n=" . $row['log_group']) . "\">" . $log_groups[$row['log_group']] . "</a>",
		"LOG_LIST_DESC" => htmlspecialchars($row['log_text'])
	));

	$t->parse("ADMIN_LOG.LOG_LIST");
}

$t->assign(array(
	"ADMIN_LOG_FILTER" => $group_select,
	"ADMIN_LOG_CLEAR" => $clear_all
));

$t->assign("ADMIN_LOG_TITLE", $admintitle);

$t->parse("ADMIN_LOG");

$adminmain .= $t->text("ADMIN_LOG");
