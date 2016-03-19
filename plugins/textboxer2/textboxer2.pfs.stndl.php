<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer2/textboxer2.pfs.stndl.php
Version=177
Updated=2012-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer2
Part=pfs
File=textboxer2.pfs.stndl
Hooks=pfs.stndl
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';	

$pfs_header1 = $cfg['doctype']."<html><head>
<title>".$cfg['maintitle']."</title>".sed_htmlmetas().sed_javascript($morejavascript)."
<script type=\"text/javascript\">
<!--
function addthumb(thmb, image)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += '[thumb='+thmb+']'+image+'[/thumb]'; }
function addpix(gfile)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += '[img]'+gfile+'[/img]'; }
function addfile(gfile, gpath)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += '[pfs]".$cfg['rel_dir']."'+gfile+'[/pfs]'; }
function addglink(id)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += '[gallery='+id+']".$L["pfs_gallery"]." #'+id+'[/gallery]'; }	
function addfile_pageurl(gfile)
	{ ".$openerparent.".document.".$c1.".".$c2.".value += gfile; }	
//-->
</script>
";



?>