<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.ajax.php
Version=179
Updated=2022-jul-18
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Part=AjaxCk
File=ckeditor.ajax
Hooks=ajax
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require(SED_ROOT . '/system/config.extensions.php');

$result_upload = array();
$disp_errors = "";

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');

$sql_total = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='" . $usr['id'] . "'");
$pfs_totalsize = sed_sql_result($sql_total, 0, "SUM(pfs_size)");

$user_info = sed_userinfo($usr['id']);
$maingroup = ($usr['id'] == 0) ? 5 : $user_info['user_maingrp'];

$sql = sed_sql_query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
if ($row = sed_sql_fetchassoc($sql)) {
	$maxfile = $row['grp_pfs_maxfile'];
	$maxtotal = $row['grp_pfs_maxtotal'];
} else {
	exit;
}

if ($maxfile == 0 || $maxtotal == 0 || !$usr['auth_write']) {
	$disp_errors = $L['pfs_filetoobigorext'];
}

$u_tmp_name = $_FILES['upload']['tmp_name'];
$u_type = $_FILES['upload']['type'];
$u_name = $_FILES['upload']['name'];
$u_size = $_FILES['upload']['size'];

$filename = sed_newname($usr['id'] . "-" . $u_name, TRUE);

if ($cfg['pfs_filemask'] || file_exists($cfg['pfs_dir'] . $filename)) {
	$filename = sed_newname($usr['id'] . "-" . time() . sed_unique(3) . "-" . $u_name, TRUE);
}

$allow_extension = array('gif', 'png', 'jpg', 'jpeg', 'bmp');
$extension_arr = explode(".", $filename);
$f_extension = end($extension_arr);

$uploaded = 0;
$u_sqlname = $filename;
$u_title = $filename;

if (in_array($f_extension, $allow_extension) == FALSE) {
	$disp_errors = "Bad file extension";
} elseif ((($pfs_totalsize + $u_size) > $maxtotal * 1024) || ($u_size > ($maxfile * 1024))) {
	$disp_errors = $L['pfs_filetoobigorext'];
} elseif (file_exists($cfg['pfs_dir'] . $filename)) {
	$disp_errors = $L['pfs_fileexists'];
} elseif (empty($disp_errors)) {
	move_uploaded_file($u_tmp_name, $cfg['pfs_dir'] . $filename);

	@chmod($cfg['pfs_dir'] . $filename, 0644);

	$uploaded = 1;

	$folder_title = $L[date('F')] . " " . date('Y');

	$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid = '" . $usr['id'] . "' AND pff_title = '" . $folder_title . "' LIMIT 1");
	if (sed_sql_numrows($sql) > 0) {
		$folderid = sed_sql_result($sql, 0, "pff_id");
	} else {
		$sql = sed_sql_query("INSERT INTO $db_pfs_folders
			(pff_userid,
			pff_title,
			pff_date,
			pff_updated,
			pff_desc,
			pff_type,
			pff_count)
		VALUES
			(" . (int)$usr['id'] . ",
			'" . sed_sql_prep($folder_title) . "',
			" . (int)$sys['now'] . ",
			" . (int)$sys['now'] . ",
			'',  
			0,
			0)");
		$folderid = sed_sql_insertid();
	}

	$sql = sed_sql_query("INSERT INTO $db_pfs
		(pfs_userid,
		pfs_date,
		pfs_file,
		pfs_extension,
		pfs_folderid,
		pfs_title,
		pfs_size,
		pfs_count)
	VALUES
		(" . (int)$usr['id'] . ",
		" . (int)$sys['now_offset'] . ",
		'" . sed_sql_prep($u_sqlname) . "',
		'" . sed_sql_prep($f_extension) . "',
		" . (int)$folderid . ",
		'" . sed_sql_prep($u_title) . "',
		" . (int)$u_size . ",
		0) ");

	$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='" . $sys['now'] . "' WHERE pff_id='$folderid'");
	sed_sm_createthumb($cfg['pfs_dir'] . $filename, $cfg['th_dir'] . $filename, $cfg['th_x'], $cfg['th_y'], $cfg['th_jpeg_quality'], "resize", TRUE);
}

$result_upload = array(
	"uploaded" => $uploaded,
	"filename" => $filename,
	"url" => $cfg['pfs_dir'] . $filename,
	"error" => array("message" => $disp_errors)
);

header("Content-type: application/json; charset=UTF-8");
header("Cache-Control: must-revalidate");
header("Pragma: no-cache");
header("Expires: -1");
print json_encode($result_upload);
