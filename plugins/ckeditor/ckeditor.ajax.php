<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.ajax.php
Version=186
Updated=2026-oct-15
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
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require(SED_ROOT . '/system/config.extensions.php');

$allow_extensions = array();
foreach ($sed_extensions as $k => $line) {
	$allow_extensions[] = $line[0];
}

$result_upload = array();
$disp_errors = "";
$uploaded = 0;
$filename = "";
$imageUrl = "";

// Get user permissions and group information
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');

if (!$usr['auth_write']) {
	sed_sendheaders('application/json', 403);
	echo json_encode(array(
		"uploaded" => 0,
		"error" => array("message" => $L['pfs_filetoobigorext'])
	));
	exit;
}

$sk = sed_sourcekey();
$xg = sed_import('x', 'G', 'ALP');
$xp = sed_import('xp', 'P', 'ALP');

$csrf_valid = sed_check_csrf() || (!empty($xp) && $xp === $sk) || (!empty($xg) && $xg === $sk);

if (!$csrf_valid) {
	sed_sendheaders('application/json', 403);
	echo json_encode(array(
		"uploaded" => 0,
		"error" => array("message" => "Access denied. Invalid CSRF token.")
	));
	exit;
}

// Calculate the total size of files uploaded by the user
$sql_total = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='".(int)$usr['id']."'");
$pfs_totalsize = (int)sed_sql_result($sql_total, 0, "SUM(pfs_size)");

// Get user group information
$user_info = sed_userinfo($usr['id']);
$maingroup = ($usr['id'] == 0) ? 5 : $user_info['user_maingrp'];

// Get group file size limits
$sql = sed_sql_query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='".(int)$maingroup."' LIMIT 1");
if ($row = sed_sql_fetchassoc($sql)) {
    $maxfile = (int)$row['grp_pfs_maxfile'];
    $maxtotal = (int)$row['grp_pfs_maxtotal'];
} else {
    exit;
}

// Check if the user has permission to upload files
if ($maxfile == 0 || $maxtotal == 0) {
    $disp_errors = $L['pfs_filetoobigorext'];
}

// Handle file upload through $_FILES
$u_tmp_name = isset($_FILES['upload']['tmp_name']) ? $_FILES['upload']['tmp_name'] : null;
$u_type = isset($_FILES['upload']['type']) ? $_FILES['upload']['type'] : null;
$u_name = isset($_FILES['upload']['name']) ? $_FILES['upload']['name'] : null;
$u_size = isset($_FILES['upload']['size']) ? $_FILES['upload']['size'] : null;

// Only for admin
if ($usr['isadmin'] && empty($disp_errors)) {
	// Handle file upload through $_POST with image URL
	$imageUrl = isset($_POST['imageUrl']) ? $_POST['imageUrl'] : null;

	// Determine the temporary directory
	$tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();

	if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
		$parsed_url = parse_url($imageUrl);
		$scheme = isset($parsed_url['scheme']) ? strtolower($parsed_url['scheme']) : '';
		$host = isset($parsed_url['host']) ? strtolower($parsed_url['host']) : '';

		if (!in_array($scheme, array('http', 'https'), true)) {
			$disp_errors = "Invalid image URL scheme";
		} else {
			$ip = gethostbyname($host);
			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false || in_array($host, array('localhost', '127.0.0.1', '::1'), true)) {
				$disp_errors = "Fetching from local or private network URLs is disallowed.";
			} else {
				$imageData = @file_get_contents($imageUrl);
				if ($imageData === false) {
					$disp_errors = "Failed to fetch image from URL";
				} else {
					$u_size = strlen($imageData);
					$u_name = basename($imageUrl);
					$extension_arr = explode(".", $u_name);
					$f_extension = end($extension_arr);
					if (in_array($f_extension, $cfg['gd_supported'])) {			
						$u_tmp_name = tempnam($tmp_dir, 'CKE');
						file_put_contents($u_tmp_name, $imageData);
					} else {
						$disp_errors = "Bad file extension. Not image.";
					}
				}
			}
		}
	}
}

if (empty($disp_errors) && !empty($u_name)) {
	// Check file extension
	$filename = sed_newname($usr['id']."-".$u_name, TRUE);
	if (function_exists('sed_pfs_unique_filename')) {
		$filename = sed_pfs_unique_filename($filename, $cfg['pfs_dir'], !empty($cfg['pfs_filemask']));
	}

	$extension_arr = explode(".", $filename);
	$f_extension = end($extension_arr);

	$u_sqlname = $filename;
	$u_title = $filename;

	// Validate file extension and size
	if (!in_array($f_extension, $allow_extensions)) {
		$disp_errors = "Bad file extension";
	} elseif ((($pfs_totalsize + $u_size) > $maxtotal * 1024) || ($u_size > ($maxfile * 1024))) {
		$disp_errors = $L['pfs_filetoobigorext'];
	} elseif (empty($disp_errors)) {
		if ($u_tmp_name && file_exists($u_tmp_name)) {
			if ($imageUrl) {
				// Move the file from the temporary directory to the target directory
				if (copy($u_tmp_name, $cfg['pfs_dir'].$filename)) {
					unlink($u_tmp_name); // Delete the temporary file after successful copy
					@chmod($cfg['pfs_dir'].$filename, 0644);
					$uploaded = 1;
				} else {
					$disp_errors = "Failed to move uploaded file";
				}
			} else {
				if (move_uploaded_file($u_tmp_name, $cfg['pfs_dir'].$filename)) {
					@chmod($cfg['pfs_dir'].$filename, 0644);
					$uploaded = 1;
				} else {
					$disp_errors = "Failed to move uploaded file";
				}
			}
		} else {
			$disp_errors = "Temporary file does not exist";
		}

		if ($uploaded) {
			$folder_title = $L[date('F')]." ".date('Y');

			$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid = '".(int)$usr['id']."' AND pff_title = '".sed_sql_prep($folder_title)."' LIMIT 1");
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
					(".(int)$usr['id'].",
					'".sed_sql_prep($folder_title)."',
					".(int)$sys['now'].",
					".(int)$sys['now'].",
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
				(".(int)$usr['id'].",
				".(int)$sys['now_offset'].",
				'".sed_sql_prep($u_sqlname)."',
				'".sed_sql_prep($f_extension)."',
				".(int)$folderid.",
				'".sed_sql_prep($u_title)."',
				".(int)$u_size.",
				0) ");

			$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='".(int)$folderid."'");

			// Check if the file exists before creating a thumbnail
			if (in_array($f_extension, $cfg['gd_supported']) && $cfg['th_amode'] != 'Disabled' && file_exists($cfg['pfs_dir'].$filename)) {
				@unlink($cfg['th_dir'] . $filename);			
				sed_sm_createthumb($cfg['pfs_dir'].$filename, $cfg['th_dir'].$filename, $cfg['th_x'], $cfg['th_y'], $cfg['th_jpeg_quality'], "resize", TRUE);
			} 
		}
	}
}

$result_upload = array(
    "uploaded" => $uploaded,
    "filename" => $filename,
    "url" => $cfg['pfs_dir'].$filename,
    "error" => array("message" => $disp_errors)
);

sed_sendheaders('application/json');
print json_encode($result_upload);
