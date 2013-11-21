<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.lang.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$adminmain .= "<h3>".$L['core_lang']." :</h3>";

$handle = opendir("system/lang/");

while ($f = readdir($handle))
	{
	if ($f[0] != '.')
		{ $langlist[] = $f; }
	}
closedir($handle);
sort($langlist);

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr><td class=\"coltop\">".$L['core_lang']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Code']."</td>";
$adminmain .= "<td class=\"coltop\">&nbsp;</td>";
$adminmain .= "<td class=\"coltop\">".$L['Default']."</td></tr>";


while(list($i,$x) = each($langlist))
	{
	$info = sed_infoget("system/lang/$x/main.lang.php");
	
	$adminmain .= "</tr>";
  $adminmain .= "<td style=\"width:25%;\"><strong>"; 	
	$adminmain .= (empty($sed_languages[$x])) ? $sed_countries[$x] : $sed_languages[$x];
  $adminmain .= "</strong>"; 	
  $adminmain .= "</td><td style=\"width:10%; text-align:center;\">"; 
  $adminmain .= $x;
  $adminmain .= "<td>";
  $adminmain .= $L['Version']." : ".$info['Version']."<br />";
  $adminmain .= $L['Author']." : ".$info['Author']."<br />";
  $adminmain .= $L['Updated']." : ".$info['Updated'];
  $adminmain .= "</td><td style=\"text-align:center; vertical-align:middle; width:15%;\">";  
	$adminmain .= ($x == $cfg['defaultlang']) ? $out['img_checked'] : '';
  $adminmain .= "</td></tr>";  	
	}
	
$adminmain .= "</table>";

?>