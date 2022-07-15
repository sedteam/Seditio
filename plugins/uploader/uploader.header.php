<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.header.php
Version=179
Updated=2021-jun-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=loaderUp
File=uploader.header
Hooks=header.tags
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

global $usr;

if ($usr['id'] > 0)
	{      
	$out['uploader_header'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/uploader/css/uploader.css\"/>";    
	//$out['uploader_footer'] = "<script src=\"plugins/uploader/js/jquery-ui.min.js\" type=\"text/javascript\"></script>";
	$out['uploader_footer'] = "<script src=\"plugins/uploader/js/uploader.js\" type=\"text/javascript\"></script>";
	$t->assign("HEADER_UPLOADER", $out['uploader_header']);
  }




?>
