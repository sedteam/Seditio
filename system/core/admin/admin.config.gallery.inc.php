<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.gallery.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }


$adminmain .= "<h3>".$L['adm_gd']." :</h3>";

if (!function_exists('gd_info'))
	{
	$adminmain .= "<p>".$L['adm_nogd']."</p>";
	}
   else
	{
  $adminmain .= "<table class=\"cells striped\">";
	$gd_datas = gd_info();
	foreach ($gd_datas as $k => $i)
		{
		if (mb_strlen($i)<2)
      { $i = ($i) ? $out['img_checked'] : '';
      }
		$adminmain .= "<tr><td>".$k."</td><td>".$i."</td></tr>";
		}
	$adminmain .= "</table>";
	}

?>