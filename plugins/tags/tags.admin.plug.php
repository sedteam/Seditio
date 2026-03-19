<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.admin.plug.php
Version=185
Type=Plugin
Description=Tags administration
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=admin.plug
File=tags.admin.plug
Hooks=admin.plug
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $db_tags, $db_tag_references, $db_pages, $db_forum_topics, $cfg, $L;

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'tags');
sed_block($usr['isadmin']);

$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=tags")] = $L['tags_admin_title'];

$admintitle = $L['tags_admin_title'];
$adminhelp = '';

$a = sed_import('a', 'G', 'ALP');
$msg_html = '';

$t = new XTemplate(sed_skinfile('admin.tags', false, true));

/* --- Actions --- */

if ($a == 'delete') {
	sed_check_xg();
	$del_tag = sed_import('tag', 'G', 'TXT');
	if (!empty($del_tag)) {
		sed_tag_unregister($del_tag);
		$msg_html = sed_alert($L['tags_admin_deleted'], 's');
	}
}

if ($a == 'rename') {
	sed_check_xg();
	$old_tag = sed_import('oldtag', 'P', 'TXT');
	$new_tag = sed_import('newtag', 'P', 'TXT');

	if (!empty($old_tag) && !empty($new_tag)) {
		$old_tag = sed_tag_normalize($old_tag);
		$new_tag = sed_tag_normalize($new_tag);

		if (sed_tag_rename($old_tag, $new_tag)) {
			$msg_html = sed_alert($L['tags_admin_renamed'], 's');
		} else {
			$msg_html = sed_alert($L['tags_admin_rename_exists'], 'e');
		}
	}
}

if ($a == 'cleanup') {
	sed_check_xg();
	$cleaned = sed_tag_cleanup();
	$msg_html = sed_alert(sprintf($L['tags_admin_cleanup_done'], $cleaned), 's');
}

/* --- Filter --- */
$filter = sed_import('f', 'G', 'TXT');
$search = sed_import('search', 'G', 'TXT');

$where = '';
if (!empty($filter) && $filter !== 'all') {
	$where = " WHERE t.tag LIKE '" . sed_sql_prep($filter) . "%'";
} elseif (!empty($search)) {
	$where = " WHERE t.tag LIKE '%" . sed_sql_prep($search) . "%'";
}

/* --- Sort --- */
$sort = sed_import('sort', 'G', 'ALP');
$sort_sql = 't.tag ASC';
if ($sort === 'count') {
	$sort_sql = 'cnt DESC, t.tag ASC';
}

/* --- Pagination --- */
$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;
$perpage = isset($cfg['plugin']['tags']['maxrowsperpage']) ? (int)$cfg['plugin']['tags']['maxrowsperpage'] : 30;
if ($perpage <= 0) $perpage = 30;

$count_sql = sed_sql_query("SELECT COUNT(DISTINCT t.tag) FROM $db_tags AS t
	LEFT JOIN $db_tag_references AS r ON r.tag = t.tag
	$where");
$totallines = (int)sed_sql_result($count_sql, 0, 'COUNT(DISTINCT t.tag)');

$base_url_params = "m=tags";
if (!empty($filter) && $filter !== 'all') $base_url_params .= "&f=" . sed_tag_qs_value($filter);
if (!empty($search)) $base_url_params .= "&search=" . sed_tag_qs_value($search);
if (!empty($sort)) $base_url_params .= "&sort=" . sed_tag_qs_value($sort);

$pagination = sed_pagination(sed_url("admin", $base_url_params), $d, $totallines, $perpage);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", $base_url_params), $d, $totallines, $perpage, true);

/* --- List --- */
$sql = sed_sql_query("SELECT t.tag, COUNT(r.tag_item) AS cnt,
	GROUP_CONCAT(DISTINCT r.tag_area SEPARATOR ', ') AS areas
	FROM $db_tags AS t
	LEFT JOIN $db_tag_references AS r ON r.tag = t.tag
	$where
	GROUP BY t.tag
	ORDER BY $sort_sql
	LIMIT $d, $perpage");

while ($row = sed_sql_fetchassoc($sql)) {
	$del_url = sed_url("admin", "m=tags&a=delete&tag=" . sed_tag_qs_value($row['tag']) . "&" . sed_xg());
	$rename_action = sed_url("admin", "m=tags&a=rename&" . sed_xg());

	$t->assign(array(
		"TAGS_LIST_TAG" => sed_cc($row['tag']),
		"TAGS_LIST_TAG_DISPLAY" => sed_tag_title(sed_cc($row['tag'])),
		"TAGS_LIST_TAG_URL" => sed_url('plug', 'e=tags&t=' . sed_tag_qs_value($row['tag'])),
		"TAGS_LIST_COUNT" => (int)$row['cnt'],
		"TAGS_LIST_AREAS" => sed_cc($row['areas']),
		"TAGS_LIST_DELETE_URL" => $del_url,
		"TAGS_LIST_RENAME_ACTION" => $rename_action
	));
	$t->parse("ADMIN_TAGS.TAGS_LIST");
}

/* --- Filter letters --- */
$filter_letters = isset($L['tags_filter_letters']) ? $L['tags_filter_letters'] : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$letters_html = '<a href="' . sed_url("admin", "m=tags") . '" class="tags-admin-letter' . (empty($filter) ? ' active' : '') . '">' . $L['tags_filter_all'] . '</a> ';
$letters_arr = preg_split('//u', $filter_letters, -1, PREG_SPLIT_NO_EMPTY);
foreach ($letters_arr as $ch) {
	$lc = mb_strtolower($ch, 'UTF-8');
	$letters_html .= '<a href="' . sed_url("admin", "m=tags&f=" . sed_tag_qs_value($lc)) . '" class="tags-admin-letter' . ($filter === $lc ? ' active' : '') . '">' . $ch . '</a> ';
}

/* --- Assign global --- */
$cleanup_url = sed_url("admin", "m=tags&a=cleanup&" . sed_xg());
$search_action = sed_url("admin", "m=tags");

if ($totallines > $perpage) {
	$t->assign(array(
		"TAGS_PAGINATION" => $pagination,
		"TAGS_PAGEPREV" => $pagination_prev,
		"TAGS_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_TAGS.TAGS_PAGINATION_TP");
}

$t->assign(array(
	"ADMIN_TAGS_TITLE" => $admintitle,
	"ADMIN_TAGS_MSG" => $msg_html,
	"ADMIN_TAGS_TOTAL" => $L['tags_total'],
	"ADMIN_TAGS_TOTALITEMS" => $totallines,
	"ADMIN_TAGS_LETTERS" => $letters_html,
	"ADMIN_TAGS_SEARCH_ACTION" => $search_action,
	"ADMIN_TAGS_SEARCH_VALUE" => sed_cc($search),
	"ADMIN_TAGS_CLEANUP_URL" => $cleanup_url,
	"ADMIN_TAGS_SORT_TAG_URL" => sed_url("admin", "m=tags&sort=tag" . (!empty($filter) ? "&f=" . sed_tag_qs_value($filter) : '') . (!empty($search) ? "&search=" . sed_tag_qs_value($search) : '')),
	"ADMIN_TAGS_SORT_COUNT_URL" => sed_url("admin", "m=tags&sort=count" . (!empty($filter) ? "&f=" . sed_tag_qs_value($filter) : '') . (!empty($search) ? "&search=" . sed_tag_qs_value($search) : ''))
));

$t->parse("ADMIN_TAGS");
$adminmain .= $t->text("ADMIN_TAGS");
