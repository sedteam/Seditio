<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer2/textboxer2.pfs.stndl.icons.php
Version=175
Updated=2012-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer2
Part=pfs
File=textboxer2.pfs.stndl.icons
Hooks=pfs.stndl.icons
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

// Create icons: add thumbnail, add image, add file 



$icon_addimg_path = $cfg['pfs_dir'].$pfs_file;

$add_file = ($standalone) ? "<a href=\"javascript:addfile('".$pfs_file."','".$c1."','".$c2."')\"><img src=\"skins/".$skin."/img/system/icon-pastefile.gif\" alt=\"\" /></a>" : '';

if ($standalone)
{			
	$icon_addimg_path = ($cfg['pfsuserfolder']) ? $userid."/".$pfs_file :  $pfs_file;
  $add_thumbnail = "<a href=\"javascript:addthumb('".$cfg['th_dir'].$pfs_file."', '".$icon_addimg_path."','".$c1."','".$c2."')\"><img src=\"skins/".$skin."/img/system/icon-pastethumb.gif\" alt=\"\" /></a>";
  $add_image = "<a href=\"javascript:addpix('".$pfs_fullfile."','".$c1."','".$c2."')\"><img src=\"skins/".$skin."/img/system/icon-pasteimage.gif\" alt=\"\" /></a>";		
}

if (in_array($pfs_extension, $cfg['gd_supported']) && $cfg['th_amode']!='Disabled') 
{
    $stndl_icons_list = "<td>".$add_thumbnail." ".$add_image." ".$add_file."</td>";    
}
elseif (in_array($pfs_extension, $cfg['gd_supported']) && $cfg['th_amode']=='Disabled') {
    $stndl_icons_list = "<td>".$add_image." ".$add_file."</td>";
}
else {
    $stndl_icons_list = "<td>".$add_file."</td>";
}  

$stndl_icons_disp = "<td class=\"coltop\"></td>";

?>