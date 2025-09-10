<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.pfs.stndl.php
Version=180
Updated=2025-jan-23
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

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';
$openerparent_close = ($cfg['enablemodal']) ? 'window.parent.modal.close();' : 'window.close();';

$auto_popup_close = ($cfg['plugin']['ckeditor']['auto_popup_close'] == "Yes") ? $openerparent_close : "";

$init_ck = "
function addthumb(thmb, image)
	{ 
	var html = '<a href=\"" . $cfg['pfs_dir'] . "'+image+'\" rel=\"" . $cfg['th_rel'] . "\"><img src=\"'+thmb+'\" alt=\"\" /></a>'; 
	window." . $openerparent . ".CKEDITOR.instances['" . $c2 . "'].insertHtml(html); " . $auto_popup_close . "
	}
function addpix(gfile)
	{ 
	var html = '<img src=\"'+gfile+'\" alt=\"\" />';
	window." . $openerparent . ".CKEDITOR.instances['" . $c2 . "'].insertHtml(html); " . $auto_popup_close . "
	}
function addfile(gfile, gpath, gtitle, ext)
	{ 
	gtitle = (gtitle) ? gtitle : gfile;
	var html = '<i class=\"ext ext-'+ext+'\"></i> <a href=\"'+gpath+'\">'+gtitle+'</a>';
	window." . $openerparent . ".CKEDITOR.instances['" . $c2 . "'].insertHtml(html); " . $auto_popup_close . "
	}
function addfile_pageurl(gfile)
	{ 
	" . $openerparent . ".document." . $c1 . "." . $c2 . ".value += gfile; " . $auto_popup_close . " 
	}
function addvideo(gfile)
	{
	var html = '<div class=\"ckeditor-html5-video\" style=\"text-align: center;\"><video controls=\"controls\" src=\"'+gfile+'\"></video></div>';
	window." . $openerparent . ".CKEDITOR.instances['" . $c2 . "'].insertHtml(html); " . $auto_popup_close . "
	}
";

sed_add_javascript('plugins/ckeditor/lib/ckeditor.js?v=4', true);
sed_add_javascript($init_ck);
