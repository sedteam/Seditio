<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.gallery.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=tools"), $L['adm_manage']);
$adminpath[] = array (sed_url("admin", "m=gallery"), $L['Gallery']);
$adminhelp = $L['adm_help_gallery'];
$adminmain = "<h2><img src=\"system/img/admin/gallery.png\" alt=\"\" /> ".$L['Gallery']."</h2>";

$adminmain .= "<ul class=\"arrow_list\"><li><a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=gallery")."\">".$L['Configuration']."</a></li>";
$adminmain .= "</ul>";


$adminmain .= "PLACEHOLDER";


?>