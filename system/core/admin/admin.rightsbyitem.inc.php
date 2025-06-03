<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.rightsbyitem.inc.php
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

$ic = sed_import('ic', 'G', 'ALP');
$io = sed_import('io', 'G', 'TXT');
$advanced = sed_import('advanced', 'G', 'BOL');

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

$t = new XTemplate(sed_skinfile('admin.rightsbyitem', false, true));

if ($a == 'update') {
	$mask = array();
	$auth = sed_import('auth', 'P', 'ARR');

	$sql = sed_sql_query("UPDATE $db_auth SET auth_rights=0 WHERE auth_code='$ic' AND auth_option='$io'");

	foreach ($auth as $i => $j) {
		if (is_array($j)) {
			$mask = 0;
			foreach ($j as $l => $m) {
				$mask +=  sed_auth_getvalue($l);
			}
			$sql = sed_sql_query("UPDATE $db_auth SET auth_rights='$mask' WHERE auth_groupid='$i' AND auth_code='$ic' AND auth_option='$io'");
		}
	}

	sed_auth_reorder();
	sed_auth_clear('all');
	sed_redirect(sed_url("admin", "m=rightsbyitem&ic=" . $ic . "&io=" . $io, "", true));
	exit;
}

$sql = sed_sql_query("SELECT a.*, u.user_name, g.grp_title, g.grp_level FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_groups AS g ON g.grp_id=a.auth_groupid
	WHERE auth_code='$ic' AND auth_option='$io' ORDER BY grp_level DESC");

sed_die(sed_sql_numrows($sql) == 0);

switch ($ic) {
	case 'page':
		$title = " : " . $sed_cat[$io]['title'];
		$rurl = sed_url('admin', 'm=page&mn=structure');
		break;

	case 'forums':
		$forum = sed_forum_info($io);
		$title = " : " . sed_cc($forum['fs_title']) . " (#" . $io . ")";
		$rurl = sed_url('admin', 'm=forums');
		break;

	case 'plug':
		$extplugin_info = SED_ROOT . "/plugins/" . $io . "/" . $io . ".setup.php";
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
		$title = " : " . $info['Name'];
		$rurl = sed_url('admin', 'm=plug');
		break;

	default:
		$title = ($io == 'a') ? '' : $io;
		$rurl = sed_url('admin', 'm=manage');
		break;
}

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.rightsbyitem.case');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[$rurl] =  $L['adm_code'][$ic];
$urlpaths[sed_url("admin", "m=rightsbyitem&ic=" . $ic . "&io=" . $io)] =  $L['Rights'] . " / " . $L['adm_code'][$ic] . $title;

$admintitle = $L['Rights'] . " / " . $L['adm_code'][$ic] . $title;

$legend = "<img src=\"system/img/admin/auth_r.gif\" alt=\"\" /> : " . $L['Read'] . "<br />";
$legend .= "<img src=\"system/img/admin/auth_w.gif\" alt=\"\" /> : " . $L['Write'] . "<br />";
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_1.gif\" alt=\"\" /> : " . $L['Custom'] . " #1<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_2.gif\" alt=\"\" /> : " . $L['Custom'] . " #2<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_3.gif\" alt=\"\" /> : " . $L['Custom'] . " #3<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_4.gif\" alt=\"\" /> : " . $L['Custom'] . " #4<br />" : '';
$legend .= ($advanced) ? "<img src=\"system/img/admin/auth_5.gif\" alt=\"\" /> : " . $L['Custom'] . " #5<br />" : '';
$legend .= "<img src=\"system/img/admin/auth_a.gif\" alt=\"\" /> : " . $L['Administration'];

$t->assign(array(
	"RIGHTS_TITLE" => $L['Rights'] . " / " . $L['adm_code'][$ic] . $title,
	"RIGHTS_COLUMN_COUNT" => ($advanced) ? 8 : 3,
	"RIGHTS_UPDATECOLUMN_COUNT" => ($advanced) ? 12 : 7,
	"RIGHTS_UPDATE_SEND" => sed_url("admin", "m=rightsbyitem&a=update&ic=" . $ic . "&io=" . $io)
));

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
			$box[$code] = ($checked[$code]) ? "<input type=\"hidden\" name=\"auth[" . $row['auth_groupid'] . "][" . $code . "]\" value=\"" . $checked[$code] . "\" />" : '';
			$box[$code] .= sed_checkbox("", $checked[$code], $state[$code], $locked[$code]);
		} else {
			$box[$code] = sed_checkbox("auth[" . $row['auth_groupid'] . "][" . $code . "]", $checked[$code], $state[$code], $locked[$code]);
		}

		$t->assign(array(
			"RIGHTS_OPTIONS" => $box[$code],
			"RIGHTS_OPTIONS_CODE" => $code
		));

		$t->parse("ADMIN_RIGHTS.RIGHTSBYITEM.RIGHTS_LIST.RIGHTS_LIST_OPTIONS");
	}

	$t->assign(array(
		"RIGHTS_LIST_CODE" => $row['auth_code'],
		"RIGHTS_LIST_URL" => $link,
		"RIGHTS_LIST_TITLE" => $title,
		"RIGHTS_LIST_RIGHTBYITEM_URL" => sed_url("admin", "m=rightsbyitem&ic=" . $row['auth_code'] . "&io=" . $row['auth_option']),
		"RIGHTS_LIST_SETBYUSER" => sed_build_user($row['auth_setbyuserid'], sed_cc($row['user_name'])),
		"RIGHTS_LIST_OPEN_URL" => sed_url("users", "g=" . $row['auth_groupid'])
	));

	$t->parse("ADMIN_RIGHTS.RIGHTSBYITEM.RIGHTS_LIST");
}

if ($advanced) {
	$t->parse("ADMIN_RIGHTS.RIGHTSBYITEM.ADVANCED_RIGHTS");
}

while ($row = sed_sql_fetcharray($sql)) {
	$link = sed_url("admin", "m=rights&g=" . $row['auth_groupid']);
	$title = sed_cc($row['grp_title']);
	sed_rights_parseline($row, $title, $link);
}

$t->parse("ADMIN_RIGHTS.RIGHTSBYITEM");

$adminhelp = $legend;

$t->assign("ADMIN_RIGHTS_TITLE", $admintitle);

$t->parse("ADMIN_RIGHTS");
$adminmain .= $t->text("ADMIN_RIGHTS");
