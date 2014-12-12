<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/ckeditor/ckeditor.pfs.stndl.php
Version=175
Updated=2012-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Part=pfs
File=ckeditor.pfs.stndl
Hooks=pfs.stndl
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

if ( $cfg['plugin']['ckeditor']['auto_popup_close'] == "Yes" )
	{
	$auto_popup_close = "window.close();";
	}
	
$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';	

$pfs_header1 = $cfg['doctype']."<html>
<head>
<title>".$cfg['maintitle']."</title>".sed_htmlmetas().sed_javascript($morejavascript).$moremetas."
<script type=\"text/javascript\">
<!--
function addthumb(thmb, image)
	{ 
	var html = '<a href=\"".$cfg['pfs_dir']."'+image+'\" rel=\"".$cfg['th_rel']."\"><img src=\"'+thmb+'\" alt=\"\" /></a>'; 
	window.".$openerparent.".CKEDITOR.instances['".$c2."'].insertHtml(html); ".$auto_popup_close."
	}
function addpix(gfile)
	{ 
	var html = '<img src=\"'+gfile+'\" alt=\"\" />';
	window.".$openerparent.".CKEDITOR.instances['".$c2."'].insertHtml(html); ".$auto_popup_close."
	}
function addfile(gfile, gpath)
	{ 
	var html = '<a href=\"'+gpath+'\" title=\"\">'+gfile+'</a>';
	window.".$openerparent.".CKEDITOR.instances['".$c2."'].insertHtml(html); ".$auto_popup_close."
	}
function addfile_pageurl(gfile)
	{ 
	".$openerparent.".document.".$c1.".".$c2.".value += gfile; ".$auto_popup_close." 
	}

//-->
</script>
";

?>