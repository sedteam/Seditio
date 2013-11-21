<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.skin.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$setskin = sed_import('setskin','G','ALP');

if ($setskin == "update")
{
	sed_check_xg();
	$skin_name = mb_strtolower(sed_import('skin_name','G','ALP'));
	$sql = sed_sql_query("UPDATE $db_config SET config_value='".sed_sql_prep($skin_name)."' WHERE config_name='defskin' AND config_owner='core'");	
	header("Location: admin.php?m=config&n=edit&o=core&p=skin");
}

$adminmain .= "<h3>".$L['core_skin']." :</h3>";

$handle = opendir("skins/");

while ($f = readdir($handle))
	{
	if (mb_strpos($f, '.')  === FALSE)
		{ $skinlist[] = $f; }
	}

closedir($handle);
sort($skinlist);

$adminmain .= "<table class=\"cells\">";
$adminmain .= "<tr><td class=\"coltop\">".$L['core_skin']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Preview']."</td>";
$adminmain .= "<td class=\"coltop\">&nbsp;</td>";
$adminmain .= "<td class=\"coltop\">".$L['Default']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Set']."</td></tr>";

while(list($i,$x) = each($skinlist))
	{
	$skininfo = "skins/".$x."/".$x.".php";
	$info = sed_infoget($skininfo);
	$adminmain .= "</tr>";
	$adminmain .= "<td style=\"width:20%;\"><strong>"; 	
	$adminmain .= (!empty($info['Error'])) ? $x." (".$info['Error'].")" : $info['Name'];
	$adminmain .= "</strong>";	
	$adminmain .= "</td><td><img src=\"skins/$x/$x.png\" alt=\"".$info['Name']."\" />";
	$adminmain .= "<td style=\"width:60%;\">"; 	   
	$adminmain .= $L['Version']." : ".$info['Version']."<br />";
	$adminmain .= $L['Updated']." : ".$info['Updated']."<br />";
	$adminmain .= $L['Author']." : ".$info['Author']."<br />";
	$adminmain .= $L['URL']." : ".$info['Url']."<br />";
	$adminmain .= $L['Description']." : ".$info['Description']."";  
	$adminmain .= "</td><td style=\"text-align:center; vertical-align:middle; width:10%;\">";  
	$adminmain .= ($x == $cfg['defaultskin']) ? $out['img_checked'] : $out['img_unchecked'];
	$adminmain .= "</td><td style=\"text-align:center; vertical-align:middle; width:10%;\">";
	$adminmain .= ($x == $cfg['defaultskin']) ? $out['img_checked'] : "<a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=skin&amp;setskin=update&amp;skin_name=".$x."&amp;".sed_xg()."\">".$out['img_set']."</a>";
	$adminmain .= "</td></tr>"; 
  }
$adminmain .= "</table>";


?>
