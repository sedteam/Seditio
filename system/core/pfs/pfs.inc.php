<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=pfs.inc.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
list($usr['auth_read_gal'], $usr['auth_write_gal'], $usr['isadmin_gal']) = sed_auth('gallery', 'a');
sed_block($usr['auth_read']);

$id = sed_import('id','G','TXT');
$o = sed_import('o','G','TXT');
$f = sed_import('f','G','INT');
$v = sed_import('v','G','TXT');
$c1 = sed_import('c1','G','TXT');
$c2 = sed_import('c2','G','TXT');
$userid = sed_import('userid','G','INT');

$L_pff_type[0] = $L['Private'];
$L_pff_type[1] = $L['Public'];
$L_pff_type[2] = $L['Gallery'];

if (!$usr['isadmin'] || $userid=='')
	{
	$userid = $usr['id'];
	}
else
	{
	$more = "userid=".$userid;
	}

if ($userid!=$usr['id'])
	{ sed_block($usr['isadmin']); }

$files_count = 0;
$folders_count = 0;
$standalone = FALSE;
$user_info = sed_userinfo($userid);
$maingroup = ($userid==0) ? 5 : $user_info['user_maingrp'];

$sql = sed_sql_query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
if ($row = sed_sql_fetchassoc($sql))
	{
	$maxfile = $row['grp_pfs_maxfile'];
	$maxtotal = $row['grp_pfs_maxtotal'];
	}
else
	{ sed_die(); }

if (($maxfile==0 || $maxtotal==0) && !$usr['isadmin'])
	{ sed_block(FALSE); }

if (!empty($c1) || !empty($c2))
	{
	$more = "c1=".$c1."&c2=".$c2."&".$more;
	$standalone = TRUE;
	}

reset($sed_extensions);
foreach ($sed_extensions as $k => $line)
	{
 	$icon[$line[0]] = "<img src=\"system/img/pfs/".$line[2].".gif\" alt=\"".$line[1]."\" />";
	$icon[$line[0]] = "<img src=\"system/img/ext/".$line[2].".svg\" alt=\"".$line[1]."\" width=\"16\" />";
 	$filedesc[$line[0]] = $line[1];
 	}


$L['pfs_title'] = ($userid==0) ? $L['SFS'] : $L['pfs_title'];
$title = "<a href=\"".sed_url("pfs", $more)."\">".$L['pfs_title']."</a>";
$shorttitle = $L['pfs_title'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pfs", $more)] = $L['pfs_title'];

if ($userid!=$usr['id'])
	{
	sed_block($usr['isadmin']);
	$title .= ($userid==0) ? '' : " (".sed_build_user($user_info['user_id'], $user_info['user_name']).")";
	$urlpaths[sed_url("users", "m=details&id=".$user_info['user_id'])] = $user_info['user_name']; 
	$shorttitle = $user_info['user_name'];
	}

/* === Hook === */
$extp = sed_getextplugins('pfs.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */


$u_totalsize=0;
$sql = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' ");
$pfs_totalsize = sed_sql_result($sql,0,"SUM(pfs_size)");

if ($a=='upload')
	{
	sed_block($usr['auth_write']);
	$folderid = sed_import('folderid','P','INT');
	$ntitle = sed_import('ntitle','P','ARR');
	$nresize = sed_import('nresize','P','BOL');
	$naddlogo = sed_import('naddlogo','P','BOL');
	$naddlogo = ($naddlogo) ? 1 : 0;
	$nresize = ($nresize) ? 1 : 0;

	/* === Hook === */
	$extp = sed_getextplugins('pfs.upload.first');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	if ($folder_id!=0)
		{
		$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$folderid' ");
		sed_die(sed_sql_numrows($sql) == 0);
		}

	$disp_errors = "<ul class=\"cross_list\">";
	
	$count_userfile = count($_FILES['userfile']['name']);
	
	for ($ii = 0; $ii < $count_userfile; $ii++)
		{
		$u_tmp_name = $_FILES['userfile']['tmp_name'][$ii];
		$u_type = $_FILES['userfile']['type'][$ii];
		$u_name = $_FILES['userfile']['name'][$ii];
		$u_size = $_FILES['userfile']['size'][$ii];
		$u_name  = str_replace("\'",'',$u_name );
		$u_name  = trim(str_replace("\"",'',$u_name ));

		if (!empty($u_name))
			{
			$disp_errors .= "<li>".$u_name." : ";

			$u_title = sed_import($ntitle[$ii],'D','TXT');   // New in Sed 170
			$desc = '';

			$u_name = mb_strtolower($u_name);
			$dotpos = mb_strrpos($u_name,".")+1;
			$f_extension = mb_substr($u_name, $dotpos, 5);
			$f_extension_ok = 0;
      
			if ($cfg['pfs_filemask'])
				{
					$u_name = $userid."-".time()."-".sed_unique(3).".".$f_extension;
				}
				else
				{
					$u_name = sed_newname($userid."-".$u_name, TRUE);
				}

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
					if ($cfg['pfsuserfolder'])
						{
						if (!is_dir($cfg['pfs_dir']))
							{ mkdir($cfg['pfs_dir'], 0666); }
						if (!is_dir($cfg['th_dir']))
							{ mkdir($cfg['th_dir'], 0666); }
						}

					move_uploaded_file($u_tmp_name, $cfg['pfs_dir'].$u_name);
					@chmod($cfg['pfs_dir'].$u_name, 0766);

					/* === Hook === */
					$extp = sed_getextplugins('pfs.upload.moved');
					if (is_array($extp))
						{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
					/* ===== */

					if ($nresize && in_array($f_extension, $cfg['gd_supported']) && $cfg['gallery_imgmaxwidth']>0)
						{
						sed_image_resize($cfg['pfs_dir'].$u_name, $cfg['pfs_dir'].$u_name, $cfg['gallery_imgmaxwidth'], $f_extension, $cfg['gallery_logojpegqual']);
						}

					if ($naddlogo && in_array($f_extension, $cfg['gd_supported']) && !empty($cfg['gallery_logofile']) && @file_exists($cfg['gallery_logofile']))
					{
						$img2_dotpos = mb_strrpos($cfg['gallery_logofile'], ".")+1;          
						$img2_extension = mb_substr($cfg['gallery_logofile'], $img2_dotpos, 5);
						sed_image_merge($cfg['pfs_dir'].$u_name, $f_extension, $cfg['gallery_logofile'], $img2_extension, $img2_x, $img2_y, $cfg['gallery_logopos'], $cfg['gallery_logotrsp'], $cfg['gallery_logojpegqual']);
					}

					$u_size = filesize($cfg['pfs_dir'].$u_name);

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
						(".(int)$userid.",
						".(int)$sys['now_offset'].",
						'".sed_sql_prep($u_sqlname)."',
						'".sed_sql_prep($f_extension)."',
						".(int)$folderid.",
						'".sed_sql_prep($u_title)."',
						'".sed_sql_prep($desc)."',
						".(int)$u_size.",
						0) ");

					$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='".$sys['now']."' WHERE pff_id='$folderid'");
					$disp_errors .= $L['Yes'];
					$pfs_totalsize += $u_size;

					/* === Hook === */
					$extp = sed_getextplugins('pfs.upload.done');
					if (is_array($extp))
						{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
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
					$disp_errors .= $L['pfs_fileexists'];
					}
				}
			else
				{
				$disp_errors .= $L['pfs_filetoobigorext'];
				}
			$disp_errors .= "</li>";
			}
		}
	$disp_errors .= "</ul>";
	}
elseif ($a=='delete')
	{
	sed_block($usr['auth_write']);
	sed_check_xg();
	$sql = sed_sql_query("SELECT pfs_file, pfs_folderid FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$id' LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql))
		{
		$pfs_file = $row['pfs_file'];
		$f = $row['pfs_folderid'];
		$ff = $cfg['pfs_dir'].$pfs_file;

		if (file_exists($ff) && (mb_substr($pfs_file, 0, mb_strpos($pfs_file, "-"))==$userid || $usr['isadmin']))
			{
			@unlink($ff);
			if (file_exists($cfg['th_dir'].$pfs_file))
				{ @unlink($cfg['th_dir'].$pfs_file); }
			}
		$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_id='$id'");
		sed_redirect(sed_url("pfs", "f=".$f."&o=".$o."&".$more, "", true));
		exit;
		}
	else
		{ sed_die(); }
	}
elseif ($a=='newfolder')
	{
	sed_block($usr['auth_write']);
	$ntitle = sed_import('ntitle','P','TXT');
	$ndesc = sed_import('ndesc','P','TXT');
	$ntype = sed_import('ntype','P','INT');
	$ntitle = (empty($ntitle)) ? '???' : $ntitle;

	$sql = sed_sql_query("INSERT INTO $db_pfs_folders
		(pff_userid,
		pff_title,
		pff_date,
		pff_updated,
		pff_desc,
		pff_type,
		pff_count)
		VALUES
		(".(int)$userid.",
		'".sed_sql_prep($ntitle)."',
		".(int)$sys['now'].",
		".(int)$sys['now'].",
		'".sed_sql_prep($ndesc)."', 
		".(int)$ntype.",
		0)");

	sed_redirect(sed_url("pfs", $more, "", true));
	exit;
	}

elseif ($a=='deletefolder')
	{
	sed_block($usr['auth_write']);
	sed_check_xg();

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid='$f'");
	$files_count = sed_sql_result($sql,0,"COUNT(*)");
	if ($files_count == 0)
		{
		$sql = sed_sql_query("DELETE FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f'");
		$sql = sed_sql_query("UPDATE $db_pfs SET pfs_folderid=0 WHERE pfs_userid='$userid' AND pfs_folderid='$f'");
		}

	sed_redirect(sed_url("pfs", $more, "", true));
	exit;
	}

elseif ($a=='setsample')
	{
	sed_block($usr['auth_write']);
	sed_check_xg();
	$id = sed_import('id','G','INT');
	$sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_sample='$id' WHERE pff_id='$f' AND pff_userid='$userid'");
	sed_redirect(sed_url("pfs", "f=".$f."&".$more, "", true));
	exit;
	}

$f = (empty($f)) ? '0' : $f;

if ($f>0)
	{
	$sql1 = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_id='$f' AND pff_userid='$userid'");
	if ($row1 = sed_sql_fetchassoc($sql1))
		{
		$pff_id = $row1['pff_id'];
		$pff_title = $row1['pff_title'];
		$pff_updated = $row1['pff_updated'];
		$pff_desc = $row1['pff_desc'];
		$pff_type = $row1['pff_type'];
		$pff_count = $row1['pff_count'];
		$pff_sample = $row1['pff_sample'];

		$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid='$f' ORDER BY pfs_file ASC");
		$title .= " ".$cfg['separator']." <a href=\"".sed_url("pfs", "f=".$pff_id."&".$more)."\">".$pff_title."</a>";
		$shorttitle = $pff_title;
		$urlpaths[sed_url("pfs", "f=".$pff_id."&".$more)] = $pff_title; 
		}
	else
		{ sed_die(); }
	$movebox = sed_selectbox_folders($userid,"",$f);
	}
else
	{
	$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid=0 ORDER BY pfs_file ASC");
	$sql1 = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_userid='$userid' ORDER BY pff_type DESC, pff_title ASC");
	$sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_pfs WHERE pfs_folderid>0 AND pfs_userid='$userid'");
	$sql3 = sed_sql_query("SELECT pfs_folderid, COUNT(*), SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' GROUP BY pfs_folderid");

	while ($row3 = sed_sql_fetchassoc($sql3))
		{
		$pff_filescount[$row3['pfs_folderid']] = $row3['COUNT(*)'];
		$pff_filessize[$row3['pfs_folderid']] = $row3['SUM(pfs_size)'];
		}

	$folders_count = sed_sql_numrows($sql1);
	$subfiles_count = sed_sql_result($sql2,0,"COUNT(*)");
	$movebox = sed_selectbox_folders($userid,"/","");

	while ($row1 = sed_sql_fetchassoc($sql1))
		{
		$pff_id = $row1['pff_id'];
		$pff_title = $row1['pff_title'];
		$pff_updated = $row1['pff_updated'];
		$pff_desc = $row1['pff_desc'];
		$pff_type = $row1['pff_type'];
		$pff_count = $row1['pff_count'];
		$pff_fcount = $pff_filescount[$pff_id];
		$pff_fsize = floor($pff_filessize[$pff_id]/1024);
		$pff_fcount = (empty($pff_fcount)) ? "0" : $pff_fcount;
		$pff_fssize = (empty($pff_fsize)) ? "0" : $pff_fsize;

		$is_folder_delete = ($pff_fcount > 0) ? "-" : "<a href=\"".sed_url("pfs", "a=deletefolder&".sed_xg()."&f=".$pff_id."&".$more)."\" title=\"".$L['Delete']."\">".$out['img_delete']."</a>";
    
		$list_folders .= "<tr><td style=\"text-align:center;\">".$is_folder_delete."</td>";
		$list_folders .= "<td style=\"text-align:center;\"><a href=\"".sed_url("pfs", "m=editfolder&f=".$pff_id."&".$more)."\" title=\"".$L['Edit']."\">".$out['img_edit']."</a></td>";

		if ($pff_type==2)
			{ $icon_f = "<img src=\"skins/$skin/img/system/icon-gallery.gif\" alt=\"\" />"; }
		else
			{ $icon_f = "<img src=\"skins/$skin/img/system/icon-folder.gif\" alt=\"\" />"; }

		if ($pff_type==2 && !$cfg['disable_gallery'])
			{ $icon_g = "<a href=\"".sed_url("gallery", "f=".$pff_id)."\"><img src=\"system/img/admin/jumpto.gif\" alt=\"\" /></a>"; }
		else
			{ $icon_g = ''; }

		$list_folders .= "<td><a href=\"".sed_url("pfs", "f=".$pff_id."&".$more)."\">".$pff_title."</a></td>";
		$list_folders .= "<td>".$icon_f." ".$L_pff_type[$pff_type]." ".$icon_g."</td>";
		$list_folders .= "<td style=\"text-align:right;\">".$pff_fcount."</td>";
		$list_folders .= "<td style=\"text-align:right;\">".$pff_fsize." ".$L['kb']."</td>";
		$list_folders .= "<td style=\"text-align:center;\">".sed_build_date($cfg['dateformat'], $row1['pff_updated'])."</td>";	
  	$list_folders .= "<td style=\"text-align:right;\">".$pff_count."</td>";
		$list_folders .= "</tr>";
		}
	}

$files_count = sed_sql_numrows($sql);
$movebox = (empty($f)) ? sed_selectbox_folders($userid,"/","") : sed_selectbox_folders($userid,"$f","");
$th_colortext = array(hexdec(mb_substr($cfg['th_colortext'],0,2)), hexdec(mb_substr($cfg['th_colortext'],2,2)), hexdec(mb_substr($cfg['th_colortext'],4,2)));
$th_colorbg = array(hexdec(mb_substr($cfg['th_colorbg'],0,2)), hexdec(mb_substr($cfg['th_colorbg'],2,2)), hexdec(mb_substr($cfg['th_colorbg'],4,2)));

while ($row = sed_sql_fetchassoc($sql))
	{
	$pfs_id = $row['pfs_id'];
	$pfs_file = $row['pfs_file'];
	$pfs_date = $row['pfs_date'];
	$pfs_extension = $row['pfs_extension'];
	$pfs_desc = $row['pfs_desc'];
	$pfs_title = $row['pfs_title'];
	$pfs_fullfile = $cfg['pfs_dir'].$pfs_file;
	$pfs_filesize = floor($row['pfs_size']/1024);
	$pfs_icon = $icon[$pfs_extension];

	$dotpos = mb_strrpos($pfs_file, ".")+1;
	$pfs_realext = mb_strtolower(mb_substr($pfs_file, $dotpos, 5));
	
	unset($add_thumbnail, $add_image, $add_file); 
		
	if ($pfs_extension!=$pfs_realext);
		{
		$sql1 = sed_sql_query("UPDATE $db_pfs SET pfs_extension='$pfs_realext' WHERE pfs_id='$pfs_id' " );
		$pfs_extension = $pfs_realext;
		}

	$setassample = "";
  
  if (in_array($pfs_extension, $cfg['gd_supported']) && $cfg['th_amode']!='Disabled')
		{		
    $setassample = ($pfs_id==$pff_sample) ?  $out['img_checked'] : "<a href=\"".sed_url("pfs", "a=setsample&id=".$pfs_id."&f=".$f."&".sed_xg()."&".$more)."\" title=\"".$L['pfs_setassample']."\">".$out['img_set']."</a>";    
    $pfs_icon = "<a href=\"".$pfs_fullfile."\" rel=\"".$cfg['th_rel']."\"><img src=\"".$cfg['th_dir'].$pfs_file."\" alt=\"".$pfs_file."\"></a>";
		
		if (!file_exists($cfg['th_dir'].$pfs_file) && file_exists($cfg['pfs_dir'].$pfs_file))
			{
			$th_colortext = array(hexdec(mb_substr($cfg['th_colortext'],0,2)), hexdec(mb_substr($cfg['th_colortext'],2,2)), hexdec(mb_substr($cfg['th_colortext'],4,2)));
			$th_colorbg = array(hexdec(mb_substr($cfg['th_colorbg'],0,2)), hexdec(mb_substr($cfg['th_colorbg'],2,2)), hexdec(mb_substr($cfg['th_colorbg'],4,2)));
			sed_createthumb($cfg['pfs_dir'].$pfs_file, $cfg['th_dir'].$pfs_file, $cfg['th_x'],$cfg['th_y'], $cfg['th_keepratio'], $pfs_extension, $pfs_file, $pfs_filesize, $th_colortext, $cfg['th_textsize'], $th_colorbg, $cfg['th_border'], $cfg['th_jpeg_quality'], $cfg['th_dimpriority']);
			}			

  	if ($standalone) 
      { 
      $add_thumbnail .= "<a href=\"javascript:addthumb('".$cfg['th_dir'].$pfs_file."', '".$pfs_file."')\" title=\"".$L['pfs_insertasthumbnail']."\"><img src=\"skins/".$skin."/img/system/icon-pastethumb.gif\" alt=\"".$L['pfs_insertasthumbnail']."\" /></a>"; 
      $add_image = "<a href=\"javascript:addpix('".$pfs_fullfile."')\" title=\"".$L['pfs_insertasimage']."\"><img src=\"skins/".$skin."/img/system/icon-pasteimage.gif\" alt=\"".$L['pfs_insertasimage']."\" /></a>"; 
      } 
	  }
	  
	$add_file = ($standalone) ? "<a href=\"javascript:addfile('".$pfs_file."','".$pfs_fullfile."')\" title=\"".$L['pfs_insertaslink']."\"><img src=\"skins/".$skin."/img/system/icon-pastefile.gif\" alt=\"".$L['pfs_insertaslink']."\" /></a>" : '';
	
	if ((($c2 == "newpageurl") || ($c2 == "rpageurl")) && ($standalone)) 
		{ 
		$add_file = "<a href=\"javascript:addfile_pageurl('".$pfs_fullfile."')\" title=\"".$L['pfs_insertaslink']."\"><img src=\"skins/".$skin."/img/system/icon-pastefile.gif\" alt=\"".$L['pfs_insertaslink']."\" /></a>"; 
		$add_thumbnail = "";
		$add_image = "";
		} 
		
	/* === New Hook Sed 170 by Amro === */
	$stndl_icons_list = "";
	$stndl_icons_disp = "";
	$extp = sed_getextplugins('pfs.stndl.icons');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* =============================== */		

	$list_files .= "<tr><td style=\"text-align:center;\"><a href=\"".sed_url("pfs", "a=delete&".sed_xg()."&id=".$pfs_id."&o=".$o."&".$more)."\" title=\"".$L['Delete']."\">".$out['img_delete']."</a></td>";
	$list_files .= "<td style=\"text-align:center;\"><a href=\"".sed_url("pfs" ,"m=edit&id=".$pfs_id."&".$more)."\" title=\"".$L['Edit']."\">".$out['img_edit']."</a></td>";
	$list_files .= "<td>".$pfs_icon."</td>";
	$list_files .= "<td><a href=\"".$pfs_fullfile."\">".$pfs_file."</a><br />";
	$list_files .= sed_build_date($cfg['dateformat'], $pfs_date)."<br />";
	$list_files .= $pfs_filesize.$L['kb']."</td>";    
	$list_files .= "<td>".$pfs_title."</td>";  
	$list_files .= "<td style=\"text-align:right;\">".$row['pfs_count']."</td>";
	$list_files .= ($f>0) ? "<td style=\"text-align:center;\">".$setassample."</td>" : '';	  

	$list_files .= (empty($stndl_icons_list)) ? "<td style=\"text-align:center;\">".$add_thumbnail." ".$add_image." ".$add_file."</td>" : ""; 

	/*=== for hook stndl.icons ===*/
	$list_files .= $stndl_icons_list;
	/*======*/  	
	 
	$list_files .= "</tr>";
	$pfs_foldersize = $pfs_foldersize + $pfs_filesize;
	}
  
if ($files_count>0 || $folders_count>0)
	{
	if ($folders_count>0)
		{
		$disp_main .= "<h4>".$folders_count." ".$L['Folders']." / ".$subfiles_count." ".$L['Files']." :</h4>";
		$disp_main .= "<table class=\"cells striped\">";
		$disp_main .= "<tr><td class=\"coltop\"><i>".$L['Delete']."</i></td>";
		$disp_main .= "<td class=\"coltop\"><i>".$L['Edit']."</i></td>";
		$disp_main .= "<td class=\"coltop\"  style=\"width:30%;\">".$L['Folder']."</td>";
		$disp_main .= "<td class=\"coltop\">".$L['Type']."</td>";
		$disp_main .= "<td class=\"coltop\"><i>".$L['Files']."</i></td>";
		$disp_main .= "<td class=\"coltop\"><i>".$L['Size']."</i></td>";
		$disp_main .= "<td class=\"coltop\"><i>".$L['Updated']."</i></td>";
		$disp_main .= "<td class=\"coltop\"><i>".$L['Hits']."</i></td></tr>";
		$disp_main .= $list_folders."</table>";
		}

	if ($files_count>0)
		{
		$disp_main .= "<h4>".$files_count." ";

		if ($f>0)
			{ $disp_main .= $L['pfs_filesinthisfolder']; }
		else
			{ $disp_main .= $L['pfs_filesintheroot']; }

		$disp_main .= "</h4><table class=\"cells striped\">";

		$disp_main .= "<tr><td class=\"coltop\">".$L['Delete']."</td>";
		$disp_main .= "<td class=\"coltop\">".$L['Edit']."</td>";
		$disp_main .= "<td colspan=\"2\" class=\"coltop\" style=\"width:30%;\"><i>".$L['File']."</i>";
		$disp_main .= " / ".$L['Date'];
		$disp_main .= " / ".$L['Size']."</td>";
		$disp_main .= "<td class=\"coltop\" style=\"width:40%;\">".$L['Title']."</td>";
		$disp_main .= "<td class=\"coltop\">".$L['Hits']."</td>";
		$disp_main .= ($f>0) ? "<td class=\"coltop\">".$L['pfs_setassample']."</td>" : '';
		
		$disp_main .= (empty($stndl_icons_disp)) ? "<td class=\"coltop\">&nbsp;</td>" : ""; 
		  
		/*=== for hook stndl.icons ===*/
		$disp_main .= $stndl_icons_disp;
		/*======*/  
		
		$disp_main .= "</tr>";
		$disp_main .= $list_files."</table>";
		}
	}
	else
	{
	$disp_main = $L['pfs_folderistempty'];
	}

// ========== Statistics =========

$pfs_precentbar = @floor(100 * $pfs_totalsize / 1024 / $maxtotal);
$disp_stats = $L['pfs_totalsize']." : ".floor($pfs_totalsize/1024).$L['kb']." / ".$maxtotal.$L['kb'];
$disp_stats .= " (".@floor(100*$pfs_totalsize/1024/$maxtotal)."%) ";
$disp_stats .= " &nbsp; ".$L['pfs_maxsize']." : ".$maxfile.$L['kb'];
$disp_stats .= "<div style=\"width:300px; margin:6px 0 0 0;\"><div class=\"bar_back\">";
$disp_stats .= "<div class=\"bar_front\" style=\"width:".$pfs_precentbar."%;\"></div></div></div>";

// ========== Upload =========

$disp_upload = "<h4>".$L['pfs_newfile']."</h4>";
$disp_upload .= "<form enctype=\"multipart/form-data\" action=\"".sed_url("pfs", "a=upload"."&".$more)."\" method=\"post\">";
$disp_upload .= "<table class=\"cells striped\"><tr><td colspan=\"3\" style=\"vertical-align:middle;\">";

$disp_upload .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".($maxfile*1024)."\" />";
$disp_upload .= $L['Folder']." : ".sed_selectbox_folders($userid, "", $f);
$disp_upload .= ($cfg['gallery_imgmaxwidth']>0) ? " &nbsp; &nbsp; ".sprintf($L['pfs_resize'], $cfg['gallery_imgmaxwidth'])." : ".sed_checkbox('nresize') : '';
$disp_upload .= (!empty($cfg['gallery_logofile'])) ? " &nbsp; &nbsp; ".$L['pfs_addlogo']." : ".sed_checkbox('naddlogo')." &nbsp;  <img src=\"".$cfg['gallery_logofile']."\" alt=\"\" />" : '';

$disp_upload .= "</td></tr>";
$disp_upload .= "<tr><td class=\"coltop\">&nbsp;</td><td class=\"coltop\">".$L['Title']."</td>";
$disp_upload .= "<td class=\"coltop\">".$L['File']."</td></tr>";

for ($ii = 0; $ii < $cfg['pfsmaxuploads']; $ii++)
	{
	$disp_upload .= "<tr><td style=\"text-align:center;\">#".($ii+1)."</td>\n";
	$disp_upload .= "<td style=\"width:48%;\"><input type=\"text\" class=\"text\" name=\"ntitle[$ii]\" value=\"\" size=\"38\" maxlength=\"255\" /></td>\n";
	$disp_upload .= "<td style=\"width:48%;\"><input name=\"userfile[$ii]\" type=\"file\" class=\"file\" size=\"32\" />\n";
	$disp_upload .= ($ii+1==$cfg['pfsmaxuploads']) ? " &nbsp; <a href=\"javascript:sedjs.toggleblock('moreuploads')\"><img src=\"skins/".$skin."/img/system/arrow-down.gif\" alt=\"\" /></a>": '';
	$disp_upload .= "</td></tr>";
	}

$disp_upload .= "<tbody id=\"moreuploads\" style=\"display:none;\">";

for ($ii = $cfg['pfsmaxuploads']; $ii < $cfg['pfsmaxuploads']*2; $ii++)
	{
	$disp_upload .= "<tr><td style=\"text-align:center;\">#".($ii+1)."</td>\n";
	$disp_upload .= "<td style=\"width:48%;\"><input type=\"text\" class=\"text\" name=\"ntitle[$ii]\" value=\"\" size=\"38\" maxlength=\"255\" />\n</td>";
	$disp_upload .= "<td style=\"width:48%;\"><input name=\"userfile[$ii]\" type=\"file\" class=\"file\" size=\"32\" /></td>\n</tr>";
	}
$disp_upload .= "</tbody>";

$disp_upload .= "<tr><td style=\"text-align:center;\"></td>\n";
$disp_upload .= "<td style=\"width:48%; text-align:right;\">".$L['pfs_multiuploading']."</td>";
$disp_upload .= "<td style=\"width:48%;\"><input name=\"userfile[]\" type=\"file\" class=\"file\" multiple=\"true\" size=\"32\" /></td>\n</tr>";

$disp_upload .= "<tr><td style=\"text-align:center;\" colspan=\"3\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Upload']."\" /></td></tr></table>";
$disp_upload .= "</form>";

// ========== Icons Help =========

$disp_iconshelp = "<h4>".$L['Help']." :</h4>";
$disp_iconshelp .= "<img src=\"skins/$skin/img/system/icon-pastethumb.gif\" alt=\"\" /> : ".$L['pfs_insertasthumbnail']." &nbsp; &nbsp; 
	<img src=\"skins/$skin/img/system/icon-pasteimage.gif\" alt=\"\" /> : ".$L['pfs_insertasimage']." &nbsp; &nbsp; 
	<img src=\"skins/$skin/img/system/icon-pastefile.gif\" alt=\"\" /> : ".$L['pfs_insertaslink']; 

// ========== Allowed =========

$disp_allowed = "<h4>".$L['pfs_extallowed']." :</h4>";
reset($sed_extensions);
sort($sed_extensions);
$disp_allowedlist = array();
foreach ($sed_extensions as $k => $line)
 	{ $disp_allowedlist[] = $icon[$line[0]]." .".$line[0]." (".$filedesc[$line[0]].")"; }
$disp_allowed .= implode(", ", $disp_allowedlist);

// ========== Create a new folder =========

if ($f==0 && $usr['auth_write'])
	{
	$disp_newfolder = "<h4>".$L['pfs_newfolder']."</h4>";
	$disp_newfolder .= "<form id=\"newfolder\" action=\"".sed_url("pfs", "a=newfolder"."&".$more)."\" method=\"post\">";
	$disp_newfolder .= "<table class=\"cells striped\"><tr><td>".$L['Title']."</td>";
	$disp_newfolder .= "<td><input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"40\" maxlength=\"64\" /></td></tr>";
	$disp_newfolder .= "<tr><td>".$L['Description']."</td>";
	$disp_newfolder .= "<td><input type=\"text\" class=\"text\" name=\"ndesc\" value=\"\" size=\"40\" maxlength=\"255\" /></td></tr>";
	$disp_newfolder .= "<tr><td>".$L['Type']."</td>";
	$disp_newfolder .= "<td>";
		
	$ntype_arr = ($usr['auth_write_gal']) ? array(0 => $L['Private'], 1 => $L['Public'], 2 => $L['Gallery']) : array(0 => $L['Private'], 1 => $L['Public']);	
	$disp_newfolder .= sed_radiobox("ntype", $ntype_arr, 0);
	
	$disp_newfolder .= "</td></tr>";
	$disp_newfolder .= "<tr><td colspan=\"2\" style=\"text-align:center;\">";
	$disp_newfolder .= "<input type=\"submit\" class=\"submit btn\" value=\"".$L['Create']."\" /></td></tr>";
	$disp_newfolder .= "</table></form>";
	}

// ========== Putting all together =========

$subtitle = $disp_stats;
$body = (!empty($disp_errors)) ? "<div>".$disp_errors."</div>" : '<div>&nbsp;</div>';


$body .= "<div class=\"sedtabs\">";
	
$body .= "<ul class=\"tabs\">";
$body .= "<li><a href=\"".$sys['request_uri']."#tab1\" class=\"selected\">".$L['Folders']." & ".$L['Files']."</a></li>";
$body .= ($usr['auth_write']) ? "<li><a href=\"".$sys['request_uri']."#tab2\">".$L['pfs_newfile']."</a></li>" : '';
$body .= ($f==0 && $usr['auth_write']) ? "<li><a href=\"".$sys['request_uri']."#tab3\">".$L['pfs_newfolder']."</a></li>" : '';
$body .= "</ul>";    

$body .= "<div class=\"tab-box\">";

$body .= "<div id=\"tab1\" class=\"tabs\">".$disp_main."</div>";
$body .= ($usr['auth_write']) ? "<div id=\"tab2\" class=\"tabs\">".$disp_upload."</div>" : '';
$body .= ($usr['auth_write']) ? "<div id=\"tab3\" class=\"tabs\">".$disp_newfolder."</div>" : '';

$body .= "</div></div>";

$body .= ($standalone) ? "<div>".$disp_iconshelp."</div>" : '';

$body .= ($usr['auth_write']) ? "<div>".$disp_allowed."</div>" : '';

$out['subtitle'] = $L['Mypfs'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

/* ============= */

if ($standalone)
	{
	sed_sendheaders();
	
	$pfs_header1 = $cfg['doctype']."<html><head>".sed_htmlmetas()."<title>".$out['subtitle']."</title>";
	$pfs_header2 = "</head><body>";
	$pfs_footer = "</body></html>";
	
	/* === Hook === */
	$extp = sed_getextplugins('pfs.stndl');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ====== */		

	$mskin = sed_skinfile(array('pfs', 'standalone'));
	$t = new XTemplate($mskin);
	
	$t->assign(array(
		"PFS_STANDALONE_HEADER1" => $pfs_header1,
		"PFS_STANDALONE_HEADER2" => $pfs_header2,
		"PFS_STANDALONE_FOOTER" => $pfs_footer,
	));

	$t->parse("MAIN.STANDALONE_HEADER");
	$t->parse("MAIN.STANDALONE_FOOTER");

	$t-> assign(array(
		"PFS_TITLE" => $title,
		"PFS_SHORTTITLE" => $shorttitle,
		"PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths, 1, false),
		"PFS_BODY" => $body
		));

	$t->parse("MAIN");
	$t->out("MAIN");

	
	@ob_end_flush();
	@ob_end_flush();
	
	sed_sql_close($connection_id);
	}
else
	{
	if (defined('SED_ADMIN'))
		{
		$t = new XTemplate(sed_skinfile("admin.apfs", true));
		}
	else
		{
		require(SED_ROOT . "/system/header.php");
		$t = new XTemplate(sed_skinfile("pfs"));
		}

	sed_breadcrumbs($urlpaths);
	
	$t-> assign(array(
		"PFS_TITLE" => $title,
		"PFS_SHORTTITLE" => $shorttitle,
		"PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),		
		"PFS_SUBTITLE" => $subtitle,
		"PFS_BODY" => $body
	));

	/* === Hook === */
	$extp = sed_getextplugins('pfs.tags');
	if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	if (defined('SED_ADMIN'))
		{
		$t -> parse("MAIN"); 
		$adminmain = $t -> text("MAIN");
		}
	else 
		{
		$t->parse("MAIN");
		$t->out("MAIN");
		require(SED_ROOT . "/system/footer.php");
		}
	}
?>