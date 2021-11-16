<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=admin.gallery.inc.php
Version=178
Updated=2021-jun-17
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

$t = new XTemplate(sed_skinfile('admin.gallery', true)); 
		
$t -> parse("ADMIN_GALLERY");
$adminmain .= $t -> text("ADMIN_GALLERY");

?>
