<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.gallery.inc.php
Version=177
Updated=2015-feb-06
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

$t = new XTemplate(sed_skinfile('admin.config.gallery', true)); 

if (!function_exists('gd_info'))
	{
	$t -> parse("ADMIN_CONFIG_GALLERY.NO_GD");
	}
   else
	{
	$gd_datas = gd_info();
	foreach ($gd_datas as $k => $i)
		{
		if (mb_strlen($i) < 2) { $i = ($i) ? $out['img_checked'] : ''; }
		
		$t -> assign(array( 
			"GD_SETTING_NAME" => $k,
			"GD_SETTING_VALUE" => $i
		));
		
		$t -> parse("ADMIN_CONFIG_GALLERY.GD_INFO.GD_SETTINGS_LIST");		
		}	
	$t -> parse("ADMIN_CONFIG_GALLERY.GD_INFO");
	}

$t -> parse("ADMIN_CONFIG_GALLERY");

$adminmain .= $t -> text("ADMIN_CONFIG_GALLERY");  

?>
