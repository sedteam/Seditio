<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.trashcan.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Trash can
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('trash', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=trashcan")] =  $L['Trashcan'];

$admintitle = $L['Trashcan'];

$adminhelp = $L['adm_help_trashcan'];

$id = sed_import('id', 'G', 'INT');

if ($a == 'wipe') {
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_trash WHERE tr_id='$id'");
} elseif ($a == 'wipeall') {
	sed_check_xg();
	$sql = sed_sql_query("TRUNCATE $db_trash");
} elseif ($a == 'restore') {
	sed_check_xg();
	if (sed_trash_restore($id)) {
		sed_trash_delete($id);
	}
}

$sql = sed_sql_query("SELECT t.*, u.user_name FROM $db_trash AS t
	LEFT JOIN $db_users AS u ON t.tr_trashedby=u.user_id
	WHERE 1 ORDER by tr_id DESC");

$t = new XTemplate(sed_skinfile('admin.trashcan', false, true));

$ii = 0;

while ($row = sed_sql_fetchassoc($sql)) {
	switch ($row['tr_type']) {
		case 'comment':
			$icon = "comments.png";
			$typestr = $L['Comment'];
			break;

		case 'forumpost':
			$icon = "forums.png";
			$typestr = $L['Post'];
			break;

		case 'forumtopic':
			$icon = "forums.png";
			$typestr = $L['Topic'];
			break;

		case 'page':
			$icon = "page.png";
			$typestr = $L['Page'];
			break;

		case 'pm':
			$icon = "pm.png";
			$typestr = $L['Private_Messages'];
			break;

		case 'user':
			$icon = "user.png";
			$typestr = $L['User'];
			break;

		default:
			$icon = "tools.png";
			$typestr = $row['tr_type'];
			break;
	}

	$t->assign(array(
		"TRASHCAN_LIST_DATE" => sed_build_date($cfg['dateformat'], $row['tr_date']),
		"TRASHCAN_LIST_TYPE" => "<img src=\"system/img/admin/" . $icon . "\" alt=\"" . $typestr . "\" /> " . $typestr,
		"TRASHCAN_LIST_TITLE" => sed_cc($row['tr_title']),
		"TRASHCAN_LIST_TRASHEDBY" => ($row['tr_trashedby'] == 0) ? $L['System'] : sed_build_user($row['tr_trashedby'], sed_cc($row['user_name'])),
		"TRASHCAN_LIST_WIPE_URL" => sed_url("admin", "m=trashcan&a=wipe&id=" . $row['tr_id'] . "&" . sed_xg()),
		"TRASHCAN_LIST_RESTORE_URL" => sed_url("admin", "m=trashcan&a=restore&id=" . $row['tr_id'] . "&" . sed_xg())
	));

	$t->parse("ADMIN_TRASHCAN.TRASHCAN_LIST");

	$ii++;
}

$t->assign(array(
	"TRASHCAN_CONFIGURATION_URL" => sed_url("admin", "m=config&n=edit&o=core&p=trash"),
	"TRASHCAN_WIPEALL_URL" => sed_url("admin", "m=trashcan&a=wipeall&" . sed_xg()),
	"TRASHCAN_TOTAL" => $ii
));

$t->assign("ADMIN_TRASHCAN_TITLE", $admintitle);

$t->parse("ADMIN_TRASHCAN");

$adminmain .= $t->text("ADMIN_TRASHCAN");
