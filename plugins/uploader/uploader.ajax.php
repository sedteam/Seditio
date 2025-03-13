<?PHP
/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.ajax.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=AjaxUp
File=uploader.ajax
Hooks=ajax
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');

//error_reporting(0);

$pfs_delete = $cfg['plugin']['uploader']['pfs_delete'];

$upl_delete = sed_import('upl_delete', 'G', 'TXT');
$upl_rotate = sed_import('upl_rotate', 'G', 'TXT');
$upl_degree_lvl = sed_import('upl_degree_lvl', 'G', 'INT');
$upl_pid = sed_import('upl_pid', 'G', 'INT');
$disp_errors = '';

$buildfilename = $cfg['plugin']['uploader']['buildfilename'];

$upl_filename = sed_import('upl_filename', 'G', 'TXT');

if ($upl_delete) {
	if ($pfs_delete == "yes") {
		sed_block($usr['auth_write']);
		//sed_check_xg();

		$sql = sed_sql_query("SELECT pfs_id, pfs_file, pfs_folderid FROM $db_pfs WHERE pfs_userid='" . $usr['id'] . "' AND pfs_file='$upl_delete' LIMIT 1");

		if ($row = sed_sql_fetchassoc($sql)) {
			$pfs_file = $row['pfs_file'];
			$f = $row['pfs_folderid'];
			$ff = $cfg['pfs_dir'] . $pfs_file;
			if (file_exists($ff) && (mb_substr($pfs_file, 0, mb_strpos($pfs_file, "-")) == $usr['id'] || $usr['isadmin'])) {
				@unlink($ff);
				if (file_exists($cfg['th_dir'] . $pfs_file)) {
					@unlink($cfg['th_dir'] . $pfs_file);
				}
			}
			$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_id='" . $row['pfs_id'] . "'");
			exit;
		}
	}
	exit;
} elseif ($upl_rotate) {
	sed_block($usr['auth_write']);
	//sed_check_xg();

	$sql = sed_sql_query("SELECT pfs_id, pfs_file, pfs_folderid FROM $db_pfs WHERE pfs_userid='" . $usr['id'] . "' AND pfs_file='$upl_rotate' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$pfs_file = $row['pfs_file'];
		$f = $row['pfs_folderid'];
		$ff = $cfg['pfs_dir'] . $pfs_file;
		if (file_exists($ff) && (mb_substr($pfs_file, 0, mb_strpos($pfs_file, "-")) == $usr['id'] || $usr['isadmin'])) {
			sed_rotateimage($cfg['pfs_dir'] . $upl_rotate, $upl_degree_lvl);
			sed_rotateimage($cfg['th_dir'] . $upl_rotate, $upl_degree_lvl);
			echo $upl_rotate;
		}
	}
	exit;
}

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

$filename = sed_newname($usr['id'] . "-" . $upl_filename, TRUE);

if ($cfg['pfs_filemask'] || file_exists($cfg['pfs_dir'] . $filename)) {
	$filename = sed_newname($usr['id'] . "-" . time() . sed_unique(3) . "-" . $upl_filename, TRUE);
}

$allow_extension = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'webp');
$extension_arr = explode(".", $filename);
$f_extension = end($extension_arr);

if (in_array($f_extension, $allow_extension) == FALSE) {
	$disp_errors = "Bad file extension";
} elseif (file_exists($cfg['pfs_dir'] . $filename)) {
	$disp_errors = $L['pfs_fileexists'];
} elseif (empty($disp_errors)) {
	$u_size = file_put_contents($cfg['pfs_dir'] . $filename, file_get_contents('php://input'));
	$imgsize = @getimagesize($cfg['pfs_dir'] . $filename);

	if (!isset($imgsize) || !isset($imgsize['mime']) || !in_array($imgsize['mime'], array('image/jpeg', 'image/png', 'image/gif', 'image/webp'))) {
		$disp_errors = "File is not image!";
		unlink($cfg['pfs_dir'] . $filename);
	} elseif ((($pfs_totalsize + $u_size) > $maxtotal * 1024) || ($u_size > ($maxfile * 1024))) {
		$disp_errors = $L['pfs_filetoobigorext'];
		unlink($cfg['pfs_dir'] . $filename);
	} else {
						
		/* TODO Add to config plugin option Add watermark or insert checkbox in uploader form
		* Combined resize and watermark processing
		if (!empty($cfg['gallery_logofile']) && @file_exists($cfg['gallery_logofile'])) {
			$do_watermark = true;
			sed_image_process(
				$cfg['pfs_dir'] . $filename,      // $source
				$cfg['pfs_dir'] . $filename,      // $dest (overwrite source)
				0, 			 					// $width
				0,                              // $height (auto)
				true,                           // $keepratio (only if resizing)
				'resize',                       // $type
				'Width',                        // $dim_priority
				$cfg['gallery_logojpegqual'],   // $quality
				$do_watermark,                  // $set_watermark
				true                            // $preserve_source
			);
		}
		*/
		
		$u_size = filesize($cfg['pfs_dir'] . $filename);

		$u_sqlname = $filename;
		$u_title = $filename;

		@chmod($cfg['pfs_dir'] . $filename, 0644);

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
			pfs_desc,
			pfs_size,
			pfs_count)
		VALUES
			(" . (int)$usr['id'] . ",
			" . (int)$sys['now_offset'] . ",
			'" . sed_sql_prep($u_sqlname) . "',
			'" . sed_sql_prep($f_extension) . "',
			" . (int)$folderid . ",
			'" . sed_sql_prep($u_title) . "',
			'',
			" . (int)$u_size . ",
			0) ");

		$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='" . $sys['now'] . "' WHERE pff_id='$folderid'");

		sed_image_process(
			$cfg['pfs_dir'] . $filename,  // $source
			$cfg['th_dir'] . $filename,   // $dest
			$cfg['th_x'],                 // $width
			$cfg['th_y'],                 // $height
			$cfg['th_keepratio'],         // $keepratio
			'resize',                     // $type
			$cfg['th_dimpriority'],       // $dim_priority
			$cfg['th_jpeg_quality'],      // $quality
			false,                        // $set_watermark
			false                         // $preserve_source
		);
	}
}

$res = new stdClass;
$res->filename = $filename;
$res->error = $disp_errors;
header("Content-type: application/json; charset=UTF-8");
header("Cache-Control: must-revalidate");
header("Pragma: no-cache");
header("Expires: -1");
print json_encode($res);
