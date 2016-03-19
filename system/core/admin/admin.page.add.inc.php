<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.page.add.inc.php
Version=177
Updated=2015-feb-06
Type=Core.admin
Author=Amro
Description=Pages
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

$adminpath[] = array (sed_url("admin", "m=page"), $L['Pages']);
$adminpath[] = array (sed_url("admin", "m=page&s=add"), $L['addnewentry']);

require('system/core/page/page.add.inc.php');

?>
