<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Configuration
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array ('admin.php?m=config', $L['Configuration']);
$adminmain = "<h2><img src=\"system/img/admin/config.png\" alt=\"\" /> ".$L['Configuration']."</h2>";

$sed_select_charset = sed_loadcharsets();
$sed_select_doctypeid = sed_loaddoctypes();

$o = sed_import('o','G','ALP');
$p = sed_import('p','G','ALP');
$v = sed_import('v','G','TXT');

if (empty($a) && empty($n) && empty($o))
  {
  $n = 'edit';
  $o = 'core';
  $p = 'main';
  }	
  
if ($o == 'plug' && !empty($p))  //New in v173
  {
    $plug_langfile = "plugins/".$p."/lang/".$p.".".$usr['lang'].".lang.php";
    if (@file_exists($plug_langfile)) { require($plug_langfile); }
  } 
	
$adminmain .= "<table class=\"flat\"><tr>";
$adminmain .= "<td style=\"width:20%; vertical-align:top;\">";

$adminmain .= "<table class=\"cells\">";
$adminmain .= "<tr><td>";
	
$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='core' ORDER BY config_cat ASC");
	
while ($row = sed_sql_fetchassoc($sql))
	{
	$code = "core_".$row['config_cat'];
	$adminmain .= ($o=='core' && $row['config_cat']==$p) ? "<strong>" : '';
	$adminmain .= "<a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=".$row['config_cat']."\">";
	$adminmain .= "<img src=\"system/img/admin/".$row['config_cat'].".png\" alt=\"\" /> ".$L[$code]."</a>";
	$adminmain .= ($o=='core' && $row['config_cat']==$p) ? "</strong>" : '';
	$adminmain .= "<br />";
	}

$adminmain .= "</td></tr></table>";
$adminmain .= "<table class=\"cells\"><tr><td class=\"coltop\">".$L['Plugins']."</td></tr>";
$adminmain .= "<tr><td>";

$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='plug' ORDER BY config_cat ASC");

while ($row = sed_sql_fetchassoc($sql))
	{
	$adminmain .= ($o=='plug' && $row['config_cat']==$p) ? "<strong>" : '';	
	$adminmain .= "<a href=\"admin.php?m=config&amp;n=edit&amp;o=plug&amp;p=".$row['config_cat']."\">";
	$adminmain .= sed_plugin_icon($row['config_cat']);
	$adminmain .= " ".$sed_plugins[$row['config_cat']]['pl_title']."</a>";
	$adminmain .= ($o=='plug' && $row['config_cat']==$p) ? "</strong>" : '';
	$adminmain .= "<br />";
	}	
	
$adminmain .= "</td></tr></table>";	
	
$adminmain .= "</td><td style=\"vertical-align:top;\">";	

switch ($n)
	{
	case 'edit':
	$o = (empty($o)) ? 'core' : $o;
	$p = (empty($o)) ? 'main' : $p;

	if ($a=='update' && !empty($n))
		{
		sed_check_xg();
		if ($o=='core')
			{
			reset($cfgmap);
			foreach ($cfgmap as $k => $line)
			 	{
				if ($line[0]==$p)
		 			{
		 			$cfg_name = $line[2];
					$cfg_value = trim(sed_import($cfg_name, 'P', 'NOC'));
					$sql = sed_sql_query("UPDATE $db_config SET config_value='".sed_sql_prep($cfg_value)."' WHERE config_name='".$cfg_name."' AND config_owner='core'");
					}
				}
			}
		else
			{
			$sql = sed_sql_query("SELECT config_owner, config_name FROM $db_config WHERE config_owner='$o' AND config_cat='$p'");
			while ($row = sed_sql_fetchassoc($sql))
				{
				$cfg_value = trim(sed_import($row['config_name'], 'P', 'NOC'));
				$sql1 = sed_sql_query("UPDATE $db_config SET config_value='".sed_sql_prep($cfg_value)."' WHERE config_name='".$row['config_name']."' AND config_owner='$o' AND config_cat='$p'");
				}
			}
		header("Location: admin.php?m=config&n=edit&o=".$o."&p=".$p);
		exit;
		}

	elseif ($a=='reset' && $o=='core' && !empty($v))
		{
		sed_check_xg();
		foreach($cfgmap as $i => $line)
			{
			if ($v==$line[2])
				{ $sql = sed_sql_query("UPDATE $db_config SET config_value='".sed_sql_prep($line[4])."', config_type='".sed_sql_prep($line[3])."' WHERE config_name='$v' AND config_owner='$o'"); }
			}
		}
	elseif ($a=='reset' && $o=='plug' && !empty($v) &&  !empty($p))
		{
		sed_check_xg();
		$extplugin_info = "plugins/".$p."/".$p.".setup.php";
    
		if (file_exists($extplugin_info))
		  { 
      if (empty($info_cfg['Error']))
        { 
        $info_cfg = sed_infoget($extplugin_info, 'SED_EXTPLUGIN_CONFIG');
 
        foreach($info_cfg as $i => $x)
				  {
          $line = explode(":", $x);
          if (is_array($line) && !empty($line[1]) && !empty($i))
            {
            if ($v==$i)
              { $sql = sed_sql_query("UPDATE $db_config SET config_value='".sed_sql_prep($line[3])."' WHERE config_name='$v' AND config_owner='$o'"); }
            }
          }         
        }
      }
    }
  
	$sql = sed_sql_query("SELECT * FROM $db_config WHERE config_owner='$o' AND config_cat='$p' ORDER BY config_cat ASC, config_order ASC, config_name ASC");
	sed_die(sed_sql_numrows($sql)==0);

	foreach ($cfgmap as $k => $line)
		{ $cfg_params[$line[2]] = $line[5]; }

	if ($o=='core')
		{ 
			$adminpath[] = array ('admin.php?m=config&amp;n=edit&amp;o='.$o.'&amp;p='.$p, $L["core_".$p]); 
			$adminhelpconfig = $L["adm_help_config_$p"]; 
		}
	else
		{
		$extplugin_info = "plugins/".$p."/".$p.".setup.php";
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
		$adminpath[] = array ('admin.php?m=config&amp;n=edit&amp;o='.$o.'&amp;p='.$p, $L['Plugin'].' : '.$info['Name'].' ('.$p.')');
		}

	$adminmain .= "<form id=\"saveconfig\" action=\"admin.php?m=config&amp;n=edit&amp;o=".$o."&amp;p=".$p."&amp;a=update&amp;".sed_xg()."\" method=\"post\">";
	$adminmain .= "<table class=\"cells\">";
	$adminmain .= "<tr><td  class=\"coltop\" colspan=\"2\">".$L['Configuration']."</td><td class=\"coltop\">".$L['Reset']."</td></tr>";

	while ($row = sed_sql_fetchassoc($sql))
		{
		$config_owner = $row['config_owner'];
		$config_cat = $row['config_cat'];
		$config_name = $row['config_name'];
		$config_value = sed_cc($row['config_value']);
		$config_default = $row['config_default'];
		$config_type = $row['config_type'];		
    
		$config_title = $L['cfg_'.$row['config_name']][0];
		$check_config_title = empty($config_title);  //fix Sed v173      
		$config_title = (empty($config_title)) ? $row['config_name'] : $config_title;	
		$config_text = sed_cc($row['config_text']);
		$config_more = $L['cfg_'.$row['config_name']][1];	
		$config_more = (!empty($config_more)) ? '&nbsp; &nbsp;('.$config_more.')' : $config_more;	
		$config_title = (!empty($config_text) && $check_config_title) ? $config_text : $config_title; //fix Sed v173 

		if ($config_type == 7) { continue; } //Hidden config New v173
		
		$adminmain .= "<tr><td style=\"width:40%;\">".$config_title." : </td><td style=\"width:60%;\">";

		if ($config_type == 1)
			{ $adminmain .= "<input type=\"text\" class=\"text\" name=\"$config_name\" value=\"$config_value\" size=\"32\" maxlength=\"255\" />"; }
		elseif ($config_type == 2)
			{
			if ($o=='plug' && !empty($row['config_default']))
				{
				$cfg_params[$config_name] = explode(",", $row['config_default']);
				//$config_more = "&nbsp;";
				}

			if (is_array($cfg_params[$config_name]))
				{
				reset($cfg_params[$config_name]);
				$adminmain .= "<select name=\"$config_name\" size=\"1\">";
				while( list($i,$x) = each($cfg_params[$config_name]) )
					{
					$x = trim($x);
					$selected = ($x == $config_value) ? "selected=\"selected\"" : '';
					$adminmain .= "<option value=\"".$x."\" $selected>".$x;
					}
				$adminmain .= "</select>";
				}
			elseif ($cfg_params[$config_name]=="userlevels")
				{
				$adminmain .= sed_selectboxlevels(0, 99, $config_value, $config_name);
				}
			else
				{
				$adminmain .= "<input type=\"text\" class=\"text\" name=\"$config_name\" value=\"$config_value\" size=\"8\" maxlength=\"11\" />";
				}
			}
		elseif ($config_type == 3)
			{
			if ($config_value == 1)
				{ $adminmain .= "<input type=\"radio\" class=\"radio\" name=\"$config_name\" value=\"1\" checked=\"checked\" />".$L['Yes']."&nbsp;&nbsp;<input type=\"radio\" class=\"radio\" name=\"$config_name\" value=\"0\" />".$L['No']; 	}
			else
				{ $adminmain .= "<input type=\"radio\" class=\"radio\" name=\"$config_name\" value=\"1\" />".$L['Yes']."&nbsp;&nbsp;<input type=\"radio\" class=\"radio\" name=\"$config_name\" value=\"0\" checked=\"checked\" />".$L['No']; }
			}
		elseif ($config_type == 4)
			{
			$varname = "sed_select_".$config_name;
			$adminmain .= "<select name=\"".$config_name."\" size=\"1\">";
			reset($$varname);
			while ( list($i,$x) = each($$varname) )
				{
				$selected = ($config_value==$x[0]) ? "selected=\"selected\"" : '';
				$adminmain .= "<option value=\"".$x[0]."\" $selected>".$x[1]."</option>";
				}
			$adminmain .= "</select>";
			}
		else
			{
			$adminmain .= "<textarea name=\"$config_name\" rows=\"5\" cols=\"56\" class=\"noeditor\">".$config_value."</textarea>";
			}
		$adminmain .= " ".$config_more."</td>";
		$adminmain .= "<td style=\"text-align:center; width:7%;\">";
		$adminmain .= ($o=='core') ? "<a href=\"admin.php?m=config&amp;n=edit&amp;o=".$o."&amp;p=".$p."&amp;a=reset&amp;v=".$config_name."&amp;".sed_xg()."\"><img src=\"system/img/admin/reset.png\" alt=\"\" /></a>" : '';
		$adminmain .= ($o=='plug') ? "<a href=\"admin.php?m=config&amp;n=edit&amp;o=".$o."&amp;p=".$p."&amp;a=reset&amp;v=".$config_name."&amp;".sed_xg()."\"><img src=\"system/img/admin/reset.png\" alt=\"\" /></a>" : '&nbsp;';    
		$adminmain .= "</td>";
		$adminmain .= "</tr>";
		}
	$adminmain .= "<tr><td colspan=\"3\"><input type=\"submit\" class=\"submit\" value=\"".$L['Update']."\" /></td></tr>";
	$adminmain .= (empty($adminhelpconfig)) ? '' : "<tr><td colspan=\"3\"><h4>".$L['Help']." :</h4>".$adminhelpconfig."</td></tr>";
	$adminmain .= "</table>";
	$adminmain .= "</form>";
  
  $sys['inc_cfg_options'] = 'system/core/admin/admin.config.'.$p.'.inc.php';
  if (file_exists($sys['inc_cfg_options']))
    { require($sys['inc_cfg_options']); }

	break;

	default:

	//

	break;
	}
	

$adminmain .= "</td></tr></table>";


?>
