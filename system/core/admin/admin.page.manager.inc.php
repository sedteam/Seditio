<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.page.manager.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$sm = sed_import('sm', 'G', 'ALP', 13);
$d = sed_import('d', 'G', 'INT');
$c = sed_import('c', 'G', 'TXT');
$wm = sed_import('wm', 'G', 'ALP', 4);

$id = sed_import('id', 'G', 'INT');
$a = sed_import('a', 'G', 'TXT');

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=page")] =  $L['Pages'];
$urlpaths[sed_url("admin", "m=page&s=manager")] =  $L['adm_pagemanager'];

$admintitle = $L['adm_pagemanager'];

if ($a == 'delete') {
	$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {

		list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row['page_cat']);
		if (!$usr['isadmin']) {
			sed_redirect(sed_url("admin", "m=page&s=manager&c=" . $row['page_cat'] . "&msg=403", "", true));
			exit;
		}

		if ($cfg['trash_page']) {
			sed_trash_put('page', $L['Page'] . " #" . $id . " " . $row['page_title'], $id, $row);
		}

		$id2 = "p" . $id;
		$sql = sed_sql_query("DELETE FROM $db_pages WHERE page_id='$id'");
		$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id2'");
		$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id2'");
		$sql = sed_sql_query("DELETE FROM $db_com WHERE com_code='$id2'");
		sed_log("Deleted page #" . $id, 'adm');
		sed_redirect(sed_url("admin", "m=page&s=manager&c=" . $row['page_cat'] . "&msg=302", "", true));
		exit;
	}
}

if (empty($s) && !empty($c)) {
	$sm = $sed_cat[$c]['order'];
	$wm = $sed_cat[$c]['way'];
}

if (empty($sm)) {
	$sm = 'title';
}
if (empty($wm)) {
	$wm = 'asc';
}
if (empty($d)) {
	$d = '0';
}

$t = new XTemplate(sed_skinfile('admin.page.manager', false, true));

$redirecturl = sed_url('admin', 'm=page&s=manager') . "&c=";
$additional = "<option value=\"" . $redirecturl . "\">" . $L['All'] . "</option>";

$sqlcat = (!empty($c)) ? " WHERE page_cat='" . $c . "' " : " WHERE 1 ";

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages" . rtrim($sqlcat));
$totallines = sed_sql_result($sql, 0, "COUNT(*)");

$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp 
					  FROM $db_pages as p 
					  LEFT JOIN $db_users AS u 
					  ON u.user_id=p.page_ownerid" . $sqlcat . "
					  ORDER BY page_$sm $wm 
					  LIMIT $d," . $cfg['maxrowsperpage']);

$totalpages = ceil($totallines / $cfg['maxrowsperpage']);
$currentpage = ceil($d / $cfg['maxrowsperpage']) + 1;
$pagination = sed_pagination(sed_url("admin", "m=page&s=manager" . "&c=" . $c . "&sm=" . $sm . "&wm=" . $wm), $d, $totallines, $cfg['maxrowsperpage']);
list($pageprev, $pagenext) = sed_pagination_pn(sed_url("admin", "m=page&s=manager" . "&c=" . $c . "&sm=" . $sm . "&wm=" . $wm), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

while ($pag = sed_sql_fetchassoc($sql)) {
	if (sed_auth('page', $pag['page_cat'], 'W')) {
		$sys['catcode'] = $pag['page_cat'];
		$pag['page_date'] = sed_build_date($cfg['formatyearmonthday'], $pag['page_date']);
		$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id']) : sed_url("page", "al=" . $pag['page_alias']);

		if ($pag['page_state'] > 0) {
			$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST.PAGE_VALIDATE");
		} else {
			$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST.PAGE_UNVALIDATE");
		}

		if (sed_auth('page', $pag['page_cat'], 'A')) {
			if ($pag['page_state'] > 0) {
				$t->assign(array(
					"PAGE_VALIDATE_URL" => sed_url("admin", "m=page&a=validate&id=" . $pag['page_id'] . "&" . $sys['url_redirect'] . "&" . sed_xg()),
				));

				$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST.ADMIN_ACTIONS.PAGE_VALIDATE");
			}

			if ($pag['page_state'] <= 0) {
				$t->assign(array(
					"PAGE_UNVALIDATE_URL" => sed_url("admin", "m=page&a=unvalidate&id=" . $pag['page_id'] . "&" . $sys['url_redirect'] . "&" . sed_xg()),
				));

				$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST.ADMIN_ACTIONS.PAGE_UNVALIDATE");
			}

			$t->assign(array(
				"PAGE_DELETE_URL" => sed_url("admin", "m=page&s=manager&a=delete&id=" . $pag['page_id']),
				"PAGE_EDIT_URL" => sed_url("admin", "m=page&s=edit&id=" . $pag['page_id']),
				"PAGE_CLONE_URL" => sed_url("admin", "m=page&s=add&id=" . $pag['page_id'] . "&a=clone")
			));
			$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST.ADMIN_ACTIONS");
		}

		$t->assign(array(
			"PAGE_ID" => $pag['page_id'],
			"PAGE_STATE" => $pag['page_state'],
			"PAGE_URL" => $pag['page_pageurl'],
			"PAGE_TITLE" => $pag['page_title'],
			"PAGE_CAT" => $pag['page_cat'],
			"PAGE_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
			"PAGE_DATE" => $pag['page_date'],
			"PAGE_COUNT" => $pag['page_count']
		));

		$t->parse("ADMIN_PAGE_MANAGER.PAGE_LIST");
	}
}

if (!empty($pagination)) {

	$t->assign(array(
		"PAGE_PAGINATION" => $pagination,
		"PAGE_PAGEPREV" => $pageprev,
		"PAGE_PAGENEXT" => $pagenext
	));

	$t->parse("ADMIN_PAGE_MANAGER.PAGE_PAGINATION_TP");
	$t->parse("ADMIN_PAGE_MANAGER.PAGE_PAGINATION_BM");
}

$t->assign(array(
	"PAGE_MANAGER_CATEGORY" => sed_selectbox_categories($c, "rpagecat", TRUE, $redirecturl, $additional),
	"PAGE_MANAGER_COUNT" => $totallines
));

$t->assign("ADMIN_PAGE_MANAGER_TITLE", $admintitle);

$t->parse("ADMIN_PAGE_MANAGER");
$adminmain .= $t->text("ADMIN_PAGE_MANAGER");
