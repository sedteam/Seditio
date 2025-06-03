<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.smilies.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.smilies.first');

if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=smilies")] =  $L['Smilies'];

$admintitle = $L['Smilies'];

if ($a == 'update') {
	$s = sed_import('s', 'P', 'ARR');
	foreach ($s as $i => $k) {
		$sql = sed_sql_query("UPDATE $db_smilies SET
			smilie_code='" . sed_sql_prep($s[$i]['code']) . "',
			smilie_image='" . sed_sql_prep($s[$i]['image']) . "',
			smilie_text='" . sed_sql_prep($s[$i]['text']) . "',
			smilie_order='" . sed_sql_prep($s[$i]['order']) . "'
			WHERE smilie_id='$i'");
	}
	sed_cache_clear('sed_smilies');
	sed_redirect(sed_url("admin", "m=smilies", "", true));
	exit;
} elseif ($a == 'add') {
	$nsmiliecode = sed_sql_prep(sed_import('nsmiliecode', 'P', 'TXT'));
	$nsmilieimage = sed_sql_prep(sed_import('nsmilieimage', 'P', 'TXT'));
	$nsmilietext = sed_sql_prep(sed_import('nsmilietext', 'P', 'TXT'));
	$nsmilieorder = sed_sql_prep(sed_import('nsmilieorder', 'P', 'TXT'));
	$sql = sed_sql_query("INSERT INTO $db_smilies (smilie_code, smilie_image, smilie_text, smilie_order) VALUES ('$nsmiliecode', '$nsmilieimage', '$nsmilietext', " . (int)$nsmilieorder . ")");

	/* === Hook for the plugins === */
	$extp = sed_getextplugins('admin.smilies.added');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	sed_cache_clear('sed_smilies');
	sed_redirect(sed_url("admin", "m=smilies", "", true));
	exit;
} elseif ($a == 'delete') {
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');
	$sql = sed_sql_query("DELETE FROM $db_smilies WHERE smilie_id='$id'");

	/* === Hook for the plugins === */
	$extp = sed_getextplugins('admin.smilies.deleted');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	sed_cache_clear('sed_smilies');
	sed_redirect(sed_url("admin", "m=smilies", "", true));
	exit;
}

$sql = sed_sql_query("SELECT * FROM $db_smilies ORDER by smilie_order ASC, smilie_id ASC");

$t = new XTemplate(sed_skinfile('admin.smilies', false, true));

while ($row = sed_sql_fetchassoc($sql)) {
	if (file_exists($row['smilie_image'])) {
		$row['smilie_preview'] = "<img src=\"" . $row['smilie_image'] . "\" alt=\"" . $row['smilie_text'] . "\" />";
		$row['smilie_img'] = @getimagesize($row['smilie_image']);
		if ($row['smilie_img']) {
			$row['smilie_size'] = $row['smilie_img'][0] . "x" . $row['smilie_img'][1];
		} else {
			$row['smilie_size'] = "?";
		}
	} else {
		$row['smilie_preview'] = "?";
		$row['smilie_size'] = "?";
	}

	$t->assign(array(
		"SMILIE_LIST_DELETE_URL" => sed_url("admin", "m=smilies&a=delete&id=" . $row['smilie_id'] . "&" . sed_xg()),
		"SMILIE_LIST_ID" => $row['smilie_id'],
		"SMILIE_LIST_PREVIEW" => $row['smilie_preview'],
		"SMILIE_LIST_SIZE" => $row['smilie_size'],
		"SMILIE_LIST_CODE" => sed_textbox("s[" . $row['smilie_id'] . "][code]", $row['smilie_code'], 10, 16),
		"SMILIE_LIST_IMAGE" => sed_textbox("s[" . $row['smilie_id'] . "][image]", $row['smilie_image'], 32, 128),
		"SMILIE_LIST_TEXT" => sed_textbox("s[" . $row['smilie_id'] . "][text]", $row['smilie_text'], 12, 64),
		"SMILIE_LIST_ORDER" => sed_textbox("s[" . $row['smilie_id'] . "][order]", $row['smilie_order'], 5, 5)
	));

	$t->parse("ADMIN_SMILIES.SMILIES_LIST.SMILIES_LIST_ITEM");
}

$t->assign(array(
	"SMILIES_UPDATE_SEND" => sed_url("admin", "m=smilies&a=update")
));

$t->parse("ADMIN_SMILIES.SMILIES_LIST");

$t->assign(array(
	"SMILIE_ADD_SEND" => sed_url("admin", "m=smilies&a=add"),
	"SMILIE_ADD_CODE" => sed_textbox("nsmiliecode", isset($nsmiliecode) ? $nsmiliecode : '', 10, 16),
	"SMILIE_ADD_IMAGEURL" => sed_textbox("nsmilieimage", isset($nsmilieimage) ? $nsmilieimage : '', 32, 128),
	"SMILIE_ADD_TEXT" => sed_textbox("nsmilietext", isset($nsmilietext) ? $nsmilietext : '', 12, 64),
	"SMILIE_ADD_ORDER" => sed_textbox("nsmilieorder", isset($nsmilieorder) ? $nsmilieorder : '', 5, 5)
));

$t->parse("ADMIN_SMILIES.ADD_SMILIE");

$t->assign("ADMIN_SMILIES_TITLE", $admintitle);

$t->parse("ADMIN_SMILIES");

$adminmain .= $t->text("ADMIN_SMILIES");

$adminhelp = $L['adm_help_page'];
