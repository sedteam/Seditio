<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer3/textboxer3.pfs.stndl.php
Version=175
Updated=2014-nov-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer3
Part=pfs
File=textboxer3.pfs.stndl
Hooks=pfs.stndl
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';	

$pfs_header1 = $cfg['doctype']."<html><head>
<title>".$cfg['maintitle']."</title>".sed_htmlmetas().$moremetas.sed_javascript($morejavascript)."
<script type=\"text/javascript\">
<!--
function addthumb(thmb, image)
	{ 	
	var textarea = window.".$openerparent.".Tb3.getInstanceByName('".$c2."');
	var selection = textarea.selection(); console.log(selection);
	textarea.paste('[thumb='+thmb+']'+image+'[/thumb]');
 }
function addpix(gfile)
	{ 
	var textarea = window.".$openerparent.".Tb3.getInstanceByName('".$c2."');
	var selection = textarea.selection(); console.log(selection);
	textarea.paste('[img]'+gfile+'[/img]');	
 }
function addfile(gfile, gpath)
	{ 
	var textarea = window.".$openerparent.".Tb3.getInstanceByName('".$c2."');
	var selection = textarea.selection(); console.log(selection);
	textarea.paste('[pfs]".$cfg['rel_dir']."'+gfile+'[/pfs]');		
 }
function addglink(id)
	{ 
	var textarea = window.".$openerparent.".Tb3.getInstanceByName('".$c2."');
	var selection = textarea.selection(); console.log(selection);
	textarea.paste('[gallery='+id+']".$L["pfs_gallery"]." #'+id+'[/gallery]');	
 }	
function addfile_pageurl(gfile)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += gfile; }	
//-->
</script>
";



?>