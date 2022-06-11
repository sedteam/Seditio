<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.config.skin.inc.php
Version=178
Updated=2022-jun-12
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
	sed_redirect(sed_url("admin", "m=config&n=edit&o=core&p=skin", "", true));
}

$handle = opendir("skins/");

while ($f = readdir($handle))
	{
	if (mb_strpos($f, '.')  === FALSE)
		{ $skinlist[] = $f; }
	}

closedir($handle);
sort($skinlist);

$t = new XTemplate(sed_skinfile('admin.config.skin', true)); 

foreach ($skinlist as $i => $x)
	{
	$skininfo = SED_ROOT."/skins/".$x."/".$x.".php";
	$info = sed_infoget($skininfo);	 	
	$skin_name = (!empty($info['Error'])) ? $x." (".$info['Error'].")" : $info['Name'];	   	
	$skin_desc = $L['Version']." : ".$info['Version']."<br />";
	$skin_desc .= $L['Updated']." : ".$info['Updated']."<br />";
	$skin_desc .= $L['Author']." : ".$info['Author']."<br />";
	$skin_desc .= $L['URL']." : ".$info['Url']."<br />";
	$skin_desc .= $L['Description']." : ".$info['Description']."";   	
	$skin_default = ($x == $cfg['defaultskin']) ? $out['img_checked'] : $out['img_unchecked'];	
	$skin_set = ($x == $cfg['defaultskin']) ? $out['img_checked'] : "<a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=skin&setskin=update&skin_name=".$x."&".sed_xg())."\">".$out['img_set']."</a>";
		
	$t -> assign(array( 
		"SKIN_LIST_NAME" => $skin_name,
		"SKIN_LIST_PREVIEW" => "<img src=\"skins/$x/$x.png\" alt=\"".$info['Name']."\" />",
		"SKIN_LIST_DESC" => $skin_desc,
		"SKIN_LIST_SET" => $skin_set,
		"SKIN_LIST_DEFAULT" => $skin_default
	));
		
	$t -> parse("ADMIN_CONFIG_SKIN.SKIN_LIST");
  }
  
$t -> parse("ADMIN_CONFIG_SKIN");
$adminmain .= $t -> text("ADMIN_CONFIG_SKIN");  
  
?>
