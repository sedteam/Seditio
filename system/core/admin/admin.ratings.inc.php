<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.ratings.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('ratings', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=ratings")] =  $L['Ratings'];

$admintitle = $L['Ratings'];

$adminhelp = $L['adm_help_ratings'];

$t = new XTemplate(sed_skinfile('admin.ratings', false, true));

$id = sed_import('id', 'G', 'TXT');
$ii = 0;
$jj = 0;

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_RATINGS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=core&p=ratings"));
	$t->parse("ADMIN_RATINGS.RATINGS_BUTTONS.RATINGS_BUTTONS_CONFIG");
	$t->parse("ADMIN_RATINGS.RATINGS_BUTTONS");
}

if ($a == 'delete') {
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id' ");
	$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id' ");
	sed_redirect(sed_url("admin", "m=ratings", "", true));
	exit;
}

// [Limit patch]
$d = sed_import('d', 'G', 'INT');
if (empty($d)) $d = 0;

$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_ratings"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=ratings"), $d, $totallines, $cfg['maxrowsperpage']);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=ratings"), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

if (!empty($pagination)) {
	$t->assign(array(
		"RATINGS_PAGINATION" => $pagination,
		"RATINGS_PAGEPREV" => $pagination_prev,
		"RATINGS_PAGENEXT" => $pagination_next
	));
	$t->parse("ADMIN_RATINGS.RATINGS_PAGINATION_TP");
	$t->parse("ADMIN_RATINGS.RATINGS_PAGINATION_BM");
}

$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE 1 ORDER by rating_id DESC LIMIT $d," . $cfg['maxrowsperpage']);
// [/Limit patch]

while ($row = sed_sql_fetchassoc($sql)) {
	$id2 = $row['rating_code'];
	$sql1 = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='$id2'");
	$votes = sed_sql_result($sql1, 0, "COUNT(*)");

	$rat_type = mb_substr($row['rating_code'], 0, 1);
	$rat_value = mb_substr($row['rating_code'], 1);

	switch ($rat_type) {
		case 'p':
			$rat_url = sed_url("page", "id=" . $rat_value . "&ratings=1");
			break;

		default:
			$rat_url = '';
			break;
	}

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
