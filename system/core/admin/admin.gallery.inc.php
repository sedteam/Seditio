<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.pfs.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array ("admin.php?m=tools", $L['adm_manage']);
$adminpath[] = array ("admin.php?m=gallery", $L['Gallery']);
$adminhelp = $L['adm_help_gallery'];
$adminmain = "<h2><img src=\"system/img/admin/gallery.png\" alt=\"\" /> ".$L['Gallery']."</h2>";

$adminmain .= "<ul><li><a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=gallery\">".$L['Configuration']."</a></li>";
$adminmain .= "</ul>";


$adminmain .= "PLACEHOLDER";


?>