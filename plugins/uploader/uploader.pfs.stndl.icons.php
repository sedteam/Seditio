<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.pfs.stndl.icons.php
Version=179
Updated=2021-jun-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.pfs.stndl.icons
Hooks=pfs.stndl.icons
Tags=
Minlevel=0
Order=12
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$extraslot = $cfg['plugin']['uploader']['thumb_extra'];

if (($c2 == "newpage".$extraslot) || ($c2 == "rpage".$extraslot) || ($c2 == "rthumb")) 
	{ 
	$add_file = "<a href=\"javascript:upl_addimg('".$cfg['th_dir'].$pfs_file."','".$pfs_id."','".$pfs_file."');\" class=\"btn-icon\">".$out['ic_pastefile']."</a>"; 
	$add_thumbnail = "";
	$add_image = "";	
	$pfs_icon = "<a href=\"javascript:upl_addimg('".$cfg['th_dir'].$pfs_file."','".$pfs_id."','".$pfs_file."');\"><img src=\"".$cfg['th_dir'].$pfs_file."\" alt=\"".$pfs_file."\"></a>";	
	} 


?>
