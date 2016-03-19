<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.ajax.php
Version=177
Updated=2013-oct-09
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

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require('system/config.extensions.php');

$result_upload = array();
$errors = array();

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');

$files_count = 0;
$folders_count = 0;
$standalone = FALSE;
$user_info = sed_userinfo($usr['id']);
$maingroup = ($usr['id'] == 0) ? 5 : $user_info['user_maingrp'];

$sql = sed_sql_query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
if ($row = sed_sql_fetchassoc($sql))
	{
	$maxfile = $row['grp_pfs_maxfile'];
	$maxtotal = $row['grp_pfs_maxtotal'];
	}
else
	{ sed_die(); }

if (($maxfile == 0 || $maxtotal == 0) && !$usr['isadmin'])
	{ sed_block(FALSE); }


$u_totalsize=0;
$sql = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='".$usr['id']."' ");
$pfs_totalsize = sed_sql_result($sql,0,"SUM(pfs_size)");


$u_tmp_name = $_FILES['upload']['tmp_name'];
$u_type = $_FILES['upload']['type'];
$u_name = $_FILES['upload']['name'];
$u_size = $_FILES['upload']['size'];

$u_name  = str_replace("\'",'',$u_name );
$u_name  = trim(str_replace("\"",'',$u_name ));


			$result_upload = array(
				"uploaded" => 1, 
				"fileName" => $u_name,
				"url" => $cfg['pfs_dir'].$u_name
				);


if (!empty($u_name))
	{

	$u_name = mb_strtolower($u_name);
	$dotpos = mb_strrpos($u_name,".")+1;
	$f_extension = mb_substr($u_name, $dotpos, 5);
	$f_extension_ok = 0;

	$u_name = sed_newname($usr['id']."-".$u_name, TRUE);
	
	if ($cfg['pfs_filemask'] || file_exists($cfg['pfs_dir'].$u_name))
		{
			$u_name = $usr['id']."-".time()."-".sed_unique(3).".".$f_extension;
		}

	
	$u_title = $u_name;   // New in Sed 177
	$desc = '';	

	$u_sqlname = sed_sql_prep($u_name);

	if ($f_extension!='php' && $f_extension!='php3' && $f_extension!='php4' && $f_extension!='php5')
		{
		foreach ($sed_extensions as $k => $line)
			{
			if (mb_strtolower($f_extension) == $line[0])
				{ $f_extension_ok = 1; }
			}
		}

	if (is_uploaded_file($u_tmp_name) && $u_size>0 && $u_size<($maxfile*1024) && $f_extension_ok && ($pfs_totalsize+$u_size)<$maxtotal*1024)
		{
		if (!file_exists($cfg['pfs_dir'].$u_name))
			{

			move_uploaded_file($u_tmp_name, $cfg['pfs_dir'].$u_name);
			@chmod($cfg['pfs_dir'].$u_name, 0766);
			
			$uploaded = 1;
			
			/* ============= */
			$u_size = filesize($cfg['pfs_dir'].$u_name);
			
			// Generate Folder Name
			$folder_title = $L[date('F')]." ".date('Y'); 

			// Check Folder name exist
			$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid = '".$usr['id']."' AND pff_title = '".$folder_title."' LIMIT 1");

			if (sed_sql_numrows($sql) > 0) 
				{ 
				$folderid = sed_sql_result($sql, 0, "pff_id"); 
				}
			else 
				{
				$sql = sed_sql_query("INSERT INTO $db_pfs_folders
					(pff_userid,
					pff_title,
					pff_date,
					pff_updated,
					pff_desc,
					pff_desc_ishtml,
					pff_type,
					pff_count)
					VALUES
					(".(int)$usr['id'].",
					'".sed_sql_prep($folder_title)."',
					".(int)$sys['now'].",
					".(int)$sys['now'].",
					'',
					".(int)$ishtml.",    
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
				pfs_desc_ishtml,
				pfs_size,
				pfs_count)
				VALUES
				(".(int)$usr['id'].",
				".(int)$sys['now_offset'].",
				'".sed_sql_prep($u_sqlname)."',
				'".sed_sql_prep($f_extension)."',
				".(int)$folderid.",
				'".sed_sql_prep($u_title)."',
				'".sed_sql_prep($desc)."',
				".(int)$ishtml.",
				".(int)$u_size.",
				0) ");

			$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$folderid'");
			
			/* === Hook === */
			$extp = sed_getextplugins('pfs.upload.done');
			if (is_array($extp))
				{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
			/* ===== */			

			if (in_array($f_extension, $cfg['gd_supported']) && $cfg['th_amode']!='Disabled' && file_exists($cfg['pfs_dir'].$u_name))
				{
				@unlink($cfg['th_dir'].$u_name);
				$th_colortext = array(hexdec(mb_substr($cfg['th_colortext'],0,2)), hexdec(mb_substr($cfg['th_colortext'],2,2)), hexdec(mb_substr($cfg['th_colortext'],4,2)));
				$th_colorbg = array(hexdec(mb_substr($cfg['th_colorbg'],0,2)), hexdec(mb_substr($cfg['th_colorbg'],2,2)), hexdec(mb_substr($cfg['th_colorbg'],4,2)));
				sed_createthumb($cfg['pfs_dir'].$u_name, $cfg['th_dir'].$u_name, $cfg['th_x'],$cfg['th_y'], $cfg['th_keepratio'], $f_extension, $u_name, floor($u_size/1024), $th_colortext, $cfg['th_textsize'], $th_colorbg, $cfg['th_border'], $cfg['th_jpeg_quality'], $cfg['th_dimpriority']);
				}
			}
		else
			{
			$uploaded = 0;
			$error = $L['pfs_fileexists'];
			}
		}
	else
		{
		$error = $L['pfs_filetoobigorext'];
		}
		
			
	$result_upload = array(
		"uploaded" => $uploaded, 
		"fileName" => $u_name,
		"url" => $cfg['pfs_dir'].$u_name,
		"error" => array("message" => $error)
		);

	}
//}

if ($_GET['fl'] == 'filebrowser') {
	$funcNum = $_GET['CKEditorFuncNum'] ;
	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '".$result_upload['url']."', '".$result_upload['error']['message']."');</script>";
	}
else {   
	echo json_encode($result_upload);    
}

?>
