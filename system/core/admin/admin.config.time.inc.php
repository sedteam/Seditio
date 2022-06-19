<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.config.time.inc.php
Version=178
Updated=2022-jun-12
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$adminhelp = $L['adm_help_versions'];

$t = new XTemplate(sed_skinfile('admin.config.time', true)); 

	$t -> assign(array( 
		"CONFIG_TIME_1" => date("Y-m-d H:i"),
		"CONFIG_TIME_2" => gmdate("Y-m-d H:i"),
		"CONFIG_TIME_3" => $usr['gmttime'],
		"CONFIG_TIME_4" => sed_build_date($cfg['dateformat'], $sys['now_offset'])." ".$usr['timetext']
	));
		 
$t -> parse("ADMIN_CONFIG_TIME");

$adminmain .= $t -> text("ADMIN_CONFIG_TIME");  

?>
