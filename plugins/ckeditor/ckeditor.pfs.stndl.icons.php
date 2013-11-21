<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/ckeditor/ckeditor.pfs.stndl.icons.php
Version=173
Updated=2012-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Part=pfs
File=ckeditor.pfs.stndl.icons
Hooks=pfs.stndl.icons
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

// Create icons: add thumbnail, add image, add file 

$icon_addimg_path = $cfg['pfs_dir'].$pfs_file;

$add_thumbnail = "<a href=\"javascript:addthumb('".$cfg['th_dir'].$pfs_file."', '".$icon_addimg_path."')\"><img src=\"skins/".$skin."/img/system/icon-pastethumb.gif\" alt=\"\" /></a>";
$add_image = "<a href=\"javascript:addpix('".$pfs_fullfile."')\"><img src=\"skins/".$skin."/img/system/icon-pasteimage.gif\" alt=\"\" /></a>";
$add_file = "<a href=\"javascript:addfile('".$pfs_file."','".$cfg['pfs_dir'].$pfs_file."')\"><img src=\"skins/".$skin."/img/system/icon-pastefile.gif\" alt=\"\" /></a>";

if (($c2 == "newpageurl") || ($c2 == "rpageurl")) { 
$add_file = "<a href=\"javascript:addfile_pageurl('".$cfg['pfs_dir'].$pfs_file."', '".$c1."', '".$c2."')\"><img src=\"skins/".$skin."/img/system/icon-pastefile.gif\" alt=\"\" /></a>"; 
$add_thumbnail = "";
$add_image = "";
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