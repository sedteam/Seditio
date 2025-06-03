<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.rights.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Rights
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$g = sed_import('g', 'G', 'INT');
$advanced = sed_import('advanced', 'G', 'BOL');

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=users")] =  $L['Users'];
$urlpaths[sed_url("admin", "m=rights&g=" . $g)] =  $L['Rights'] . " : " . $sed_groups[$g]['title'];

$admintitle = $L['Rights'] . " : " . $sed_groups[$g]['title'];

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

$L['adm_code']['admin'] = $L['Administration'];
$L['adm_code']['comments'] = $L['Comments'];
$L['adm_code']['forums'] = $L['Forums'];
$L['adm_code']['index'] = $L['Home'];
$L['adm_code']['message'] = $L['Messages'];
$L['adm_code']['page'] = $L['Pages'];
$L['adm_code']['pfs'] = $L['PFS'];
$L['adm_code']['gallery'] = $L['Gallery'];
$L['adm_code']['plug'] = $L['Plugins'];
$L['adm_code']['pm'] = $L['Private_Messages'];
$L['adm_code']['polls'] = $L['Polls'];
$L['adm_code']['ratings'] = $L['Ratings'];
$L['adm_code']['users'] = $L['Users'];
$L['adm_code']['dic'] = $L['core_dic'];
$L['adm_code']['menu'] = $L['core_menu'];
$L['adm_code']['log'] = $L['Log'];
$L['adm_code']['trash'] = $L['Trashcan'];
$L['adm_code']['manage'] = $L['adm_manage'];

$t = new XTemplate(sed_skinfile('admin.rights', false, true));

if ($a == 'update') {
	$ncopyrightsconf =  sed_import('ncopyrightsconf', 'P', 'BOL');
	$ncopyrightsfrom =  sed_import('ncopyrightsfrom', 'P', 'INT');

	if ($ncopyrightsconf && !empty($sed_groups[$ncopyrightsfrom]['title']) && $g > 5) {
		$sql = sed_sql_query("SELECT * FROM $db_auth WHERE auth_groupid='" . $ncopyrightsfrom . "' order by auth_code ASC, auth_option ASC");
		if (sed_sql_numrows($sql) > 0) {
			$sql1 = sed_sql_query("DELETE FROM $db_auth WHERE auth_groupid='" . $g . "'");

			while ($row = sed_sql_fetchassoc($sql)) {
				$sql1 = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$g . ", '" . $row['auth_code'] . "', '" . $row['auth_option'] . "', " . (int)$row['auth_rights'] . ", 0, " . (int)$usr['id'] . ")");
			}
		}
		sed_auth_reorder();
		sed_auth_clear('all');
		sed_redirect(sed_url("admin", "m=rights&g=" . $g, "", true));
		exit;
	} elseif (is_array($_POST['auth'])) {
		$mask = array();
		$auth = sed_import('auth', 'P', 'ARR');

		$sql = sed_sql_query("UPDATE $db_auth SET auth_rights=0 WHERE auth_groupid='$g'");

		foreach ($auth as $k => $v) {
			foreach ($v as $i => $j) {
				if (is_array($j)) {
					$mask = 0;
					foreach ($j as $l => $m) {
						$mask +=  sed_auth_getvalue($l);
					}
					$sql = sed_sql_query("UPDATE $db_auth SET auth_rights='$mask' WHERE auth_groupid='$g' AND auth_code='$k' AND auth_option='$i'");
				}
			}
		}
		sed_auth_reorder();
		sed_auth_clear('all');
		sed_redirect(sed_url("admin", "m=rights&g=" . $g, "", true));
		exit;
	}
}

$jj = 1;

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.rights.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$sql1 = sed_sql_query("SELECT a.*, u.user_name FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	WHERE auth_groupid='$g' AND auth_code IN ('admin', 'comments', 'index', 'message', 'pfs', 'gallery', 'polls', 'pm', 'ratings', 'users', 'dic', 'menu', 'trash', 'log', 'manage')
	ORDER BY auth_code ASC");

sed_die(sed_sql_numrows($sql1) == 0);

$sql2 = sed_sql_query("SELECT a.*, u.user_name, f.fs_id, f.fs_title, f.fs_category FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_forum_sections AS f ON f.fs_id=a.auth_option
	LEFT JOIN $db_forum_structure AS n ON n.fn_code=f.fs_category
	WHERE auth_groupid='$g' AND auth_code='forums'
	ORDER BY fn_path ASC, fs_order ASC, fs_title ASC");
$sql3 = sed_sql_query("SELECT a.*, u.user_name, s.structure_path FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_structure AS s ON s.structure_code=a.auth_option
	WHERE auth_groupid='$g' AND auth_code='page'
	ORDER BY structure_path ASC");
$sql4 = sed_sql_query("SELECT a.*, u.user_name FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	WHERE auth_groupid='$g' AND auth_code='plug'
	ORDER BY auth_option ASC");

$adv_columns = ($advanced) ? 5 : 0;

$legend = "<img src=\"system/img/admin/auth_r.gif\" alt=\"\" /> : " . $L['Read'] . "<br />";
$legend .= "<img src=\"system/img/admin/auth_w.gif\" alt=\"\" /> : " . $L['Write'] . "<br />";
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_1.gif\" alt=\"\" /> : " . $L['Custom'] . " #1<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_2.gif\" alt=\"\" /> : " . $L['Custom'] . " #2<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_3.gif\" alt=\"\" /> : " . $L['Custom'] . " #3<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_4.gif\" alt=\"\" /> : " . $L['Custom'] . " #4<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_5.gif\" alt=\"\" /> : " . $L['Custom'] . " #5<br />" : '';
$legend .= "<img src=\"system/img/admin/auth_a.gif\" alt=\"\" /> : " . $L['Administration'];

$t->assign(array(
	"RIGHTS_TITLE" => $L['Rights'] . " : " . $sed_groups[$g]['title'],
	"RIGHTS_COLUMN_COUNT" => ($advanced) ? 8 : 3,
	"RIGHTS_UPDATECOLUMN_COUNT" => ($advanced) ? 11 : 6,
	"RIGHTS_GROUP_COLUMN_COUNT" => ($advanced) ? 13 : 8,
	"RIGHTS_UPDATE_SEND" => sed_url("admin", "m=rights&a=update&g=" . $g)
));

if ($g > 5) {
	$t->assign(array(
		"RIGHTS_COPYCOLUMN_COUNT" => ($advanced) ? 11 : 6,
		"RIGHTS_COPYRIGHTSCONF" => sed_checkbox("ncopyrightsconf"),
		"RIGHTS_COPYRIGHTSFROM" => sed_selectbox_groups(4, 'ncopyrightsfrom', array('5', $g))
	));
	$t->parse("ADMIN_RIGHTS.RIGHTS_COPY");
}

function sed_rights_parseline($row, $title, $link)
{
	global $L, $allow_img, $advanced, $t;

	$mn['R'] = 1;
	$mn['W'] = 2;

	if ($advanced) {
		$mn['1'] = 4;
		$mn['2'] = 8;
		$mn['3'] = 16;
		$mn['4'] = 32;
		$mn['5'] = 64;
	}
	$mn['A'] = 128;

	foreach ($mn as $code => $value) {
		$state[$code] = (($row['auth_rights'] & $value) == $value) ? TRUE : FALSE;
		$locked[$code] = (($row['auth_rights_lock'] & $value) == $value) ? TRUE : FALSE;
		$checked[$code] = ($state[$code]) ? 1 : 0;

		if ($locked[$code]) {
			$box[$code] = ($checked[$code]) ? "<input type=\"hidden\" name=\"auth[" . $row['auth_code'] . "][" . $row['auth_option'] . "][" . $code . "]\" value=\"" . $checked[$code] . "\" />" : '';
			$box[$code] .= sed_checkbox("", $checked[$code], $state[$code], $locked[$code]);
		} else {
			$box[$code] = sed_checkbox("auth[" . $row['auth_code'] . "][" . $row['auth_option'] . "][" . $code . "]", $checked[$code], $state[$code], $locked[$code]);
		}

		$t->assign(array(
			"RIGHTS_OPTIONS" => $box[$code],
			"RIGHTS_OPTIONS_CODE" => $code
		));

		$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.RIGHTS_LIST.RIGHTS_LIST_OPTIONS");
	}

	$t->assign(array(
		"RIGHTS_LIST_CODE" => $row['auth_code'],
		"RIGHTS_LIST_URL" => $link,
		"RIGHTS_LIST_TITLE" => $title,
		"RIGHTS_LIST_RIGHTBYITEM_URL" => sed_url("admin", "m=rightsbyitem&ic=" . $row['auth_code'] . "&io=" . $row['auth_option']),
		"RIGHTS_LIST_SETBYUSER" => sed_build_user($row['auth_setbyuserid'], sed_cc($row['user_name']))
	));

	$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.RIGHTS_LIST");
}

if ($advanced) {
	$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.ADVANCED_RIGHTS");
}

while ($row = sed_sql_fetchassoc($sql1)) {
	$link = sed_url("admin", "m=" . $row['auth_code']);
	$title = $L['adm_code'][$row['auth_code']];
	sed_rights_parseline($row, $title, $link);
}

$t->assign(array(
	"RIGHTS_GROUP_TITLE" => $L['Core'],
	"RIGHTS_GROUP_CODE" => 'admin'
));

$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP");

if ($advanced) {
	$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.ADVANCED_RIGHTS");
}

while ($row = sed_sql_fetchassoc($sql2)) {
	$link = sed_url("admin", "m=forums&n=edit&id=" . $row['auth_option']);
	$title = sed_build_forums($row['fs_id'], sed_cutstring($row['fs_title'], 24), sed_cutstring($row['fs_category'], 32), FALSE);
	sed_rights_parseline($row, $title, $link);
}

$t->assign(array(
	"RIGHTS_GROUP_TITLE" => $L['Forums'],
	"RIGHTS_GROUP_CODE" => 'forums'
));

$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP");

if ($advanced) {
	$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.ADVANCED_RIGHTS");
}

while ($row = sed_sql_fetcharray($sql3)) {
	$link = sed_url("admin", "m=page");
	$title = $sed_cat[$row['auth_option']]['tpath'];
	sed_rights_parseline($row, $title, $link);
}

$t->assign(array(
	"RIGHTS_GROUP_TITLE" => $L['Pages'],
	"RIGHTS_GROUP_CODE" => 'page'
));

$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP");

if ($advanced) {
	$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.ADVANCED_RIGHTS");
}

$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP.RIGHTS_UPDATE");

while ($row = sed_sql_fetcharray($sql4)) {
	$link = sed_url("admin", "m=plug&a=details&pl=" . $row['auth_option']);
	$title = $L['Plugin'] . " : " . $row['auth_option'];
	sed_rights_parseline($row, $title, $link);
}

$t->assign(array(
	"RIGHTS_GROUP_TITLE" => $L['Plugins'],
	"RIGHTS_GROUP_CODE" => 'plugins'
));

$t->parse("ADMIN_RIGHTS.RIGHTS_GROUP");

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.rights.end');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$adminhelp = $legend;

$t->assign("ADMIN_RIGHTS_TITLE", $admintitle);

$t->parse("ADMIN_RIGHTS");
$adminmain .= $t->text("ADMIN_RIGHTS");
