<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.tools.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array ("admin.php?m=tools", $L['adm_manage']);
$adminhelp = $L['adm_help_tools'];

$p = sed_import('p','G','ALP');

if (!empty($p))
	{
	$path_lang_def	= "plugins/$p/lang/$p.en.lang.php";
	$path_lang_alt	= "plugins/$p/lang/$p.$lang.lang.php";

	if (@file_exists($path_lang_alt))
		{ require($path_lang_alt); }
	elseif (@file_exists($path_lang_def))
		{ require($path_lang_def); }

	$extp = array();

	if (is_array($sed_plugins))
		{
		foreach($sed_plugins as $i => $k)
			{
			if ($k['pl_hook']=='tools' && $k['pl_code']==$p)
				{ $extp[$i] = $k; }
			}
		}

	if (count($extp)==0)
		{
		header("Location: message.php?msg=907");
		exit;
		}

	$extplugin_info = "plugins/".$p."/".$p.".setup.php";

	if (file_exists($extplugin_info))
		{
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
		}
	else
		{
		header("Location: message.php?msg=907");
		exit;
		}

	$adminpath[] = array ("admin.php?m=tools&amp;p=$p", $info['Name']);
  $adminmain .= "<h2>".sed_plugin_icon($p)." ".$info['Name']."</h2>";

	if (is_array($extp))
		{
		foreach($extp as $k => $pl)
			 {
			 include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php');
			 $adminmain .= $plugin_body;
			 }
		}

	$adminhelp = $L['Description']." : ".$info['Description']."<br />".$L['Version']." : ".$info['Version']."<br />".$L['Date']." : ".$info['Date']."<br />".$L['Author']." : ".$info['Author']."<br />".$L['Copyright']." : ".$info['Copyright']."<br />".$L['Notes']." : ".$info['Notes'];

	}
else
	{

  $adminmain .= "<table class=\"flat\">";
  $adminmain .= "<tr><td style=\"width:50%; padding-right:4px;\">";




$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner!='plug' GROUP BY config_cat");
while ($row = sed_sql_fetchassoc($sql))
	{ $cfgentries[$row['config_cat']] = $row['COUNT(*)']; }

$sql = sed_sql_query("SELECT DISTINCT(auth_code), COUNT(*) FROM $db_auth WHERE 1 GROUP BY auth_code");
while ($row = sed_sql_fetchassoc($sql))
	{ $authentries[$row['auth_code']] = $row['COUNT(*)']; }

$sql = sed_sql_query("SELECT * FROM $db_core WHERE ct_code NOT IN ('admin', 'message', 'index', 'forums', 'users', 'plug', 'page', 'trash') ORDER BY ct_title ASC");
$lines = array();

$adminmain .= "<table class=\"cells\">";
$adminmain .= "<tr>";
$adminmain .= "<td class=\"coltop\">".$L['Modules']." ".$L['adm_clicktoedit']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:80px;\">".$L['Rights']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:128px;\">".$L['Configuration']."</td>";

$adminmain .= "</tr>";

while ($row = sed_sql_fetchassoc($sql))
	{
	$adminmain .= "<tr>";
	$adminmain .= "<td>";
	
	$row['ct_title_loc'] = (empty($L["core_".$row['ct_code']])) ? $row['ct_title'] : $L["core_".$row['ct_code']];
	$adminmain .= sed_linkif("admin.php?m=".$row['ct_code'], "<img src=\"system/img/admin/".$row['ct_code'].".png\" alt=\"\" /> ".$row['ct_title_loc'], sed_auth($row['ct_code'], 'a', 'A') && $row['ct_code']!='admin' && $row['ct_code']!='index' && $row['ct_code']!='message');
	$adminmain .= "</td>";

	$adminmain .= "<td style=\"text-align:center;\">";
	$adminmain .= ($authentries[$row['ct_code']]>0) ? "<a href=\"admin.php?m=rightsbyitem&amp;ic=".$row['ct_code']."&amp;io=a\"><img src=\"system/img/admin/rights2.png\" alt=\"\" /></a>" : '&nbsp;';
	$adminmain .= 	"</td>";

	$cfgcode = "disable_".$row['ct_code'];
	$adminmain .= "<td style=\"text-align:center;\">";
	$adminmain .= ($cfgentries[$row['ct_code']]>0) ? "<a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=".$row['ct_code']."\"><img src=\"system/img/admin/config.png\" alt=\"\" /></a>" : '&nbsp;';
	$adminmain .= "</td></tr>";
	}

$adminmain .= "<tr>";
$adminmain .= "<td colspan=\"3\">".sed_linkif("admin.php?m=cache", "<img src=\"system/img/admin/cache.png\" alt=\"\" /> ".$L['adm_internalcache'], sed_auth('admin', 'a', 'A'))."</td>";
$adminmain .= "</tr>";

$adminmain .= "<tr>";
$adminmain .= "<td colspan=\"3\">".sed_linkif("admin.php?m=smilies", "<img src=\"system/img/admin/smilies.png\" alt=\"\" /> ".$L['Smilies'], sed_auth('admin', 'a', 'A'))."</td>";
$adminmain .= "</tr>";

$adminmain .= "<tr>";
$adminmain .= "<td colspan=\"3\"><a href=\"admin.php?m=hits\"><img src=\"system/img/admin/statistics.png\" alt=\"\" /> ".$L['Hits']."</a></td>";
$adminmain .= "</tr>";

$adminmain .= "<tr>";
$adminmain .= "<td colspan=\"3\">".sed_linkif("admin.php?m=referers", "<img src=\"system/img/admin/info.png\" alt=\"\" /> ".$L['Referers'], sed_auth('admin', 'a', 'A'))."</td>";
$adminmain .= "</tr>";

$adminmain .= "</table>";




















  
  $adminmain .= "</td><td style=\"width:50%; padding-left:4px;\">";
  
  












	$plugins = array();

	function cmp ($a, $b, $k=1)
		{
		if ($a[$k] == $b[$k]) return 0;
		return ($a[$k] < $b[$k]) ? -1 : 1;
		}

	/* === Hook === */
	$extp = sed_getextplugins('tools');

	if (is_array($extp))
		{
		$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner='plug' GROUP BY config_cat");
		while ($row = sed_sql_fetchassoc($sql))
			{ $cfgentries[$row['config_cat']] = $row['COUNT(*)']; }
		
		
		foreach($extp as $k => $pl)
			{ $plugins[]= array ($pl['pl_code'], $pl['pl_title']); }

		usort($plugins, "cmp");



		$adminmain .= "<table class=\"cells\">";
		$adminmain .= "<tr><td style=\"text-align:center;\" class=\"coltop\">".$L['Tools']." (".$L['Plugins'].")</td>";
		$adminmain .= "<td style=\"text-align:center;\" class=\"coltop\">".$L['Configuration']."</td></tr>";

		while (list($i,$x) = each($plugins))
			{
			$extplugin_info = "plugins/".$x[0]."/".$x[0].".setup.php";

			if (file_exists($extplugin_info))
				{
				$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
				}
			else
				{
				include ("system/lang/".$usr['lang']."/message.lang.php");
				$info['Name'] = $x[0]." : ".$L['msg907_1'];
				}

			$plugin_icon = (empty($x[1])) ? 'plugins' : $x[1];
			$adminmain  .= "<tr><td><a href=\"admin.php?m=tools&amp;p=".$x[0]."\">";
      $adminmain .= sed_plugin_icon($x[0])." ".$info['Name']."</a></td>";
			$adminmain .= "<td style=\"width:96px; text-align:center;\">";
			$adminmain .= ($cfgentries[$info['Code']]>0) ? "<a href=\"admin.php?m=config&amp;n=edit&amp;o=plug&amp;p=".$info['Code']."\"><img src=\"system/img/admin/config.png\" alt=\"\" /></a>" : '&nbsp;';
			$adminmain  .= "</td></tr>";
			}
		$adminmain .= "</table>";
		}
	else
		{
		$adminmain = $L['adm_listisempty'];
		}
  
    $adminmain .= "</td></tr></table>";		
		
		
	}
?>