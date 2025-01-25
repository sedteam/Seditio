<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.comments.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('comments', 'a');
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

$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_com"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=comments"), $d, $totallines, $cfg['maxrowsperpage'] * 2);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=comments"), $d, $totallines, $cfg['maxrowsperpage'] * 2, TRUE);

$sql = sed_sql_query("SELECT * FROM $db_com WHERE 1 ORDER BY com_id DESC LIMIT $d," . $cfg['maxrowsperpage'] * 2);

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
	$row['com_type'] = mb_substr($row['com_code'], 0, 1);
	$row['com_value'] = mb_substr($row['com_code'], 1);

	switch ($row['com_type']) {
		case 'p':
			$row['com_url'] = sed_url("page", "id=" . $row['com_value'] . "&comments=1", "#c" . $row['com_id']);
			break;

		case 'g':
			$row['com_url'] = sed_url("gallery", "id=" . $row['com_value'] . "&comments=1" . "#c" . $row['com_id']);
			break;

		case 'u':
			$row['com_url'] = sed_url("users", "m=details&id=" . $row['com_value'] . "&comments=1", "#c" . $row['com_id']);
			break;

		case 'v':
			$row['com_url'] = sed_url("polls", "id=" . $row['com_value'] . "&comments=1", "#c" . $row['com_id']);
			break;

		default:
			$row['com_url'] = '';
			break;
	}

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
