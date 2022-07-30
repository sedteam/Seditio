<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.pfs.stndl.php
Version=179
Updated=2021-jun-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=pfs
File=uploader.pfs.stndl
Hooks=pfs.stndl
Tags=
Minlevel=0
Order=12
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';
$openerparent_close = ($cfg['enablemodal']) ? 'window.parent.modal.close();' : 'window.close();';

$pfs_header1 .= "
<script type=\"text/javascript\">
<!--

function upl_addimg(gfilepath, gid, gfile)
	{ 
	window.".$openerparent.".upl_insertimg(gfilepath, gfile);
	".$openerparent_close."
	}

//-->
</script>
";

?>