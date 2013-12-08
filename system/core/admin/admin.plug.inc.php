<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.plugins.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=plug"), $L['Plugins']);

$pl = sed_import('pl','G','ALP');
$part = sed_import('part','G','ALP');

$status[0] = '<span style="color:#5882AC; font-weight:bold;">'.$L['adm_paused'].'</span>';
$status[1] = '<span style="color:#739E48; font-weight:bold;">'.$L['adm_running'].'</span>';
$status[2] = '<span style="color:#A78731; font-weight:bold;">'.$L['adm_partrunning'].'</span>';
$status[3] = '<span style="color:#AC5866; font-weight:bold;">'.$L['adm_notinstalled'].'</span>';
$found_txt[0] = '<span style="color:#AC5866; font-weight:bold;">'.$L['adm_missing'].'</span>';
$found_txt[1] = '<span style="color:#739E48; font-weight:bold;">'.$L['adm_present'].'</span>';
unset($disp_errors);

switch ($a)
	{
	/* =============== */
	case 'details' :
	/* =============== */

	$extplugin_info = "plugins/".$pl."/".$pl.".setup.php";

	if (file_exists($extplugin_info))
		{
		$extplugin_info = "plugins/".$pl."/".$pl.".setup.php";
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
		$adminpath[] = array (sed_url("admin", "m=plug&a=details&pl=".$pl), $info['Name']." ($pl)");

		$handle=opendir("plugins/".$pl);
		$setupfile = $pl.".setup.php";
		while ($f = readdir($handle))
			{
			if ($f != "." && $f != ".." && $f!=$setupfile && mb_strtolower(mb_substr($f, mb_strrpos($f, '.')+1, 4))=='php')
				{ $parts[] = $f; }
			}
		closedir($handle);
		if (is_array($parts))
			{ sort($parts); }

		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_config WHERE config_owner='plug' AND config_cat='$pl'");
		$totalconfig = sed_sql_result($sql, 0, "COUNT(*)");

		$info['Config'] = ($totalconfig>0) ? "<a href=\"".sed_url("admin", "m=config&n=edit&o=plug&p=".$pl)."\"><img src=\"system/img/admin/config.png\" alt=\"\" /> (".$totalconfig.")</a>": $L['None'];

		$info['Auth_members'] = sed_auth_getvalue($info['Auth_members']);
		$info['Lock_members'] = sed_auth_getvalue($info['Lock_members']);
		$info['Auth_guests'] = sed_auth_getvalue($info['Auth_guests']);
		$info['Lock_guests'] = sed_auth_getvalue($info['Lock_guests']);
    
		$adminmain .= "<h2>".sed_plugin_icon($pl)." ".$info['Name']."</h2>";

		$adminmain .= "<table class=\"cells striped\">";
		$adminmain .= "<tr><td style=\"width:33%;\">".$L['Code'].":</td><td>".$info['Code']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Description'].":</td><td>".$info['Description']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Version'].":</td><td>".$info['Version']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Date'].":</td><td>".$info['Date']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Configuration'].":</td><td>".$info['Config']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Rights'].":</td><td><a href=\"".sed_url("admin", "m=rightsbyitem&ic=plug&io=".$info['Code'])."\"><img src=\"system/img/admin/rights2.png\" alt=\"\" /></a></td></tr>";
		$adminmain .= "<tr><td>".$L['adm_defauth_guests'].":</td><td>".sed_build_admrights($info['Auth_guests']);
		$adminmain .= " (".$info['Auth_guests'].")</td></tr>";
		$adminmain .= "<tr><td>".$L['adm_deflock_guests'].":</td><td>".sed_build_admrights($info['Lock_guests']);
		$adminmain .= " (".$info['Lock_guests'].")</td></tr>";
		$adminmain .= "<tr><td>".$L['adm_defauth_members'].":</td><td>".sed_build_admrights($info['Auth_members']);
		$adminmain .= " (".$info['Auth_members'].")</td></tr>";
		$adminmain .= "<tr><td>".$L['adm_deflock_members'].":</td><td>".sed_build_admrights($info['Lock_members']);
		$adminmain .= " (".$info['Lock_members'].")</td></tr>";
		$adminmain .= "<tr><td>".$L['Author'].":</td><td>".$info['Author']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Copyright'].":</td><td>".$info['Copyright']."</td></tr>";
		$adminmain .= "<tr><td>".$L['Notes'].":</td><td>".$info['Notes']."</td></tr>";
		$adminmain .= "</table>";

		$adminmain .= "<h4>".$L['Options']." :</h4>";
		$adminmain .= "<table class=\"cells striped\">";
		$adminmain .= "<tr><td style=\"width:33%;\"><a href=\"".sed_url("admin", "m=plug&a=edit&pl=".$info['Code']."&b=install&".sed_xg())."\" class=\"btn\" title=\"".$L['adm_opt_installall']."\"><img src=\"system/img/admin/play.png\" alt=\"\" /> ".$L['adm_opt_installall']."</a></td>";
		$adminmain .= "<td>".$L['adm_opt_installall_explain']."</td></tr>";
		$adminmain .= "<tr><td><a href=\"".sed_url("admin", "m=plug&a=edit&pl=".$info['Code']."&b=uninstall&".sed_xg())."\" class=\"btn\" title=\"".$L['adm_opt_uninstallall']."\"><img src=\"system/img/admin/stop.png\" alt=\"\" /> ".$L['adm_opt_uninstallall']."</a></td>";
		$adminmain .= "<td>".$L['adm_opt_uninstallall_explain']."</td></tr>";
		$adminmain .= "<tr><td><a href=\"".sed_url("admin", "m=plug&a=edit&pl=".$info['Code']."&b=pause&".sed_xg())."\" class=\"btn\" title=\"".$L['adm_opt_pauseall']."\"><img src=\"system/img/admin/pause.png\" alt=\"\" /> ".$L['adm_opt_pauseall']."</a></td>";
		$adminmain .= "<td>".$L['adm_opt_pauseall_explain']."</td></tr>";
		$adminmain .= "<tr><td><a href=\"".sed_url("admin", "m=plug&a=edit&pl=".$info['Code']."&b=unpause&".sed_xg())."\" class=\"btn\" title=\"".$L['adm_opt_unpauseall']."\"><img src=\"system/img/admin/forward.png\" alt=\"\" /> ".$L['adm_opt_unpauseall']."</a></td>";
		$adminmain .= "<td>".$L['adm_opt_unpauseall_explain']."</td></tr>";
		$adminmain .= "</table>";

		$adminmain .= "<h4>".$L['Parts']." :</h4>";
		$adminmain .= "<table class=\"cells striped\"><tr>";
		$adminmain .= "<td class=\"coltop\" colspan=\"2\">".$L['adm_part']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['File']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['Hooks']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['Order']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['Status']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['Action']."</td>";
		$adminmain .= "</tr>";

		while( list($i,$x) = each($parts) )
			{
			$extplugin_file = "plugins/".$pl."/".$x;
			$info_file = sed_infoget($extplugin_file, 'SED_EXTPLUGIN');

			if (!empty($info_file['Error']))
				{
				$adminmain .= "<tr>";
				$adminmain .= "<td style=\"width:32px;\">#".($i+1)."</td>";
				$adminmain .= "<td>-</td>";
				$adminmain .= "<td>".$x."</td>";				
				$adminmain .= "<td colspan=\"4\">".$info_file['Error']."</td>";
				$adminmain .= "</tr>";
				}
			else
				{
				$sql = sed_sql_query("SELECT pl_active, pl_id FROM $db_plugins WHERE pl_code='$pl' AND pl_part='".$info_file['Part']."' LIMIT 1");

				if ($row = sed_sql_fetchassoc($sql))
					{ $info_file['Status'] = $row['pl_active']; }
				else
					{ $info_file['Status'] = 3; }

				$adminmain .= "<tr>";
				$adminmain .= "<td style=\"width:32px;\">#".($i+1)."</td>";
				$adminmain .= "<td>".$info_file['Part']."</td>";
				$adminmain .= "<td>".$info_file['File'].".php</td>";
				
				$adminmain .= "<td>";
				
				//Multihooks New v 173
				$mhooks = explode(",", $info_file['Hooks']);
				foreach ($mhooks as $kh => $vh)
				{
					$adminmain .= $vh."<br />";
				}
				
				$adminmain .= "</td>";				
				$adminmain .= "<td style=\"text-align:center;\">";
				
				//Multihooks New v 173
				$morder = explode(",", $info_file['Order']);
				foreach ($morder as $ko => $vo)
				{
					$adminmain .= $vo."<br />";
				}								
				$adminmain .= "</td>";
				
				$adminmain .= "<td style=\"text-align:center;\">".$status[$info_file['Status']]."</td>";
				$adminmain .= "<td style=\"text-align:center;\">";

				if ($info_file['Status']==3)
					{ $adminmain .= "-"; }
				elseif ($row['pl_active']==1)
					{ $adminmain .= "<a href=\"".sed_url("admin", "m=plug&a=edit&pl=".$pl."&b=pausepart&part=".$row['pl_id']."&".sed_xg())."\" class=\"btn\">Pause</a>"; }
				elseif ($row['pl_active']==0)
					{ $adminmain .= "<a href=\"".sed_url("admin" ,"m=plug&a=edit&pl=".$pl."&b=unpausepart&part=".$row['pl_id']."&".sed_xg())."\" class=\"btn\">Un-pause</a>"; }

				$adminmain .= "</td></tr>";
				$listtags .= "<tr><td style=\"width:32px;\">#".($i+1)."</td><td>".$info_file['Part']."</td><td>";

				if (empty($info_file['Tags']))
					{
					$listtags .= $L['None'];
					}
				else
					{
					$line = explode (":",$info_file['Tags']);					
					$line[0] = trim($line[0]);					
					$tpls = explode (",", $line[0]);
					
					foreach ($tpls as $kt => $vt)
						{								
							$tags = explode (",",$line[1]);
							$listtags .= $vt." :<br />";
							foreach ($tags as $k => $v)
								{
								if (mb_substr(trim($v),0,1)=='{')
									{
									$listtags .= $v." : ";
									$found = sed_stringinfile('skins/'.$cfg['defaultskin'].'/'.$vt, trim($v));
									$listtags .= $found_txt[$found];
									$listtags .= "<br />";
									}
								else
									{
									$listtags .= $v."<br />";
									}
								}
							$listtags .= "<br />";
						}
					}

				$listtags .= "</td></tr>";
				$adminmain .= "</td></tr>";
				}

			}
		$adminmain .= "</table>";

		$adminmain .= "<h4>".$L['Tags']." :</h4>";
		$adminmain .= "<table class=\"cells striped\">";
		$adminmain .= "<tr><td class=\"coltop\" colspan=\"2\">".$L['Part']."</td>";
		$adminmain .= "<td class=\"coltop\">".$L['Files']." / ".$L['Tags']."</td>".$listtags."</table>";
		}
	else
		{
		sed_die();
		}

	break;

	/* =============== */
	case 'edit' :
	/* =============== */

	switch ($b)
		{
		case 'install' :
		sed_check_xg();
		$pl =(mb_strtolower($pl)=='core') ? 'error' : $pl;
		$adminmain .= sed_plugin_install($pl);
		$adminmain .= "<a href=\"".sed_url("admin", "m=plug&a=details&pl=".$pl)."\">Continue...</a>";

		break;

		case 'uninstall' :
		sed_check_xg();
		$adminmain .= sed_plugin_uninstall($pl);
		$adminmain .= "<a href=\"".sed_url("admin", "m=plug")."\">Continue...</a>";
		break;

		case 'pause' :
		sed_check_xg();
		$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='$pl'");
		sed_cache_clearall();
		sed_redirect(sed_url("admin", "m=plug&a=details&pl=".$pl, "", true));
		exit;
		break;

		case 'unpause' :
		sed_check_xg();
		$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='$pl'");
		sed_cache_clearall();
		sed_redirect(sed_url("admin", "m=plug&a=details&pl=".$pl, "", true));
		exit;
		break;

		case 'pausepart' :
		sed_check_xg();
		$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='$pl' AND pl_id='$part'");
		sed_cache_clearall();
		sed_redirect(sed_url("admin", "m=plug&a=details&pl=".$pl, "", true));
		exit;
		break;

		case 'unpausepart' :
		sed_check_xg();
		$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='$pl' AND pl_id='$part'");
		sed_cache_clearall();
		sed_redirect(sed_url("admin", "m=plug&a=details&pl=".$pl, "", true));
		exit;
		break;

		default:
		sed_die();
		break;
	}

	break;

	default:

  if ($a=='delhook')
    {
    $id = sed_import('id', 'G', 'INT');
    sed_check_xg();
    $sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_id='$id'");
    sed_cache_clearall();
    }

	$disp_plugins = "<table class=\"cells striped\">";
	$disp_plugins .= "<tr>";
	$disp_plugins .= "<td class=\"coltop\">".$L['Plugins']."</td>";
	$disp_plugins .= "<td class=\"coltop\">".$L['Status']."</td>";
	$disp_plugins .= "</tr>";

	$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner='plug' GROUP BY config_cat");
	while ($row = sed_sql_fetchassoc($sql))
		{ $cfgentries[$row['config_cat']] = $row['COUNT(*)']; }

	$handle=opendir("plugins");
	while ($f = readdir($handle))
		{
		if (!is_file($f) && $f!='.' && $f!='..' && $f!='code')
			{ $extplugins[] = $f; }
		}
	closedir($handle);
	sort($extplugins);
	$cnt_extp = count($extplugins);
	$cnt_parts = 0;

	$plg_standalone = array();
	$sql3 = sed_sql_query("SELECT pl_code FROM $db_plugins WHERE pl_hook='standalone'");
	while ($row3 = sed_sql_fetchassoc($sql3))
		{ $plg_standalone[$row3['pl_code']] = TRUE; }

	$plg_tools = array();
	$sql3 = sed_sql_query("SELECT pl_code FROM $db_plugins WHERE pl_hook='tools'");
	while ($row3 = sed_sql_fetchassoc($sql3))
		{ $plg_tools[$row3['pl_code']] = TRUE; }

	$adminmain = "<h2><img src=\"system/img/admin/plugins.png\" alt=\"\" /> ".$L['Plugins']." (".$cnt_extp.")</h2>";

	$adminmain .= "<table class=\"cells striped\">";
	$adminmain .= "<tr>";
	$adminmain .= "<td class=\"coltop\">".$L['Plugins']." ".$L['adm_clicktoedit']."</td>";
	$adminmain .= "<td class=\"coltop\">".$L['Code']."</td>";
	$adminmain .= "<td class=\"coltop\">".$L['Version']."</td>";
	$adminmain .= "<td class=\"coltop\">".$L['Status']." (".$L['Parts'].")</td>";
	$adminmain .= "<td class=\"coltop\">".$L['Configuration']."</td>";
	$adminmain .= "<td class=\"coltop\" style=\"width:50px;\">".$L['Rights']."</td>";
	$adminmain .= "<td class=\"coltop\" style=\"width:50px;\">".$L['Open']."</td>";
	$adminmain .= "</tr>";

	while( list($i,$x) = each($extplugins) )
		{
		$extplugin_info = "plugins/".$x."/".$x.".setup.php";
		if (file_exists($extplugin_info))
			{
			$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

			if (!empty($info['Error']))
				{
				$adminmain .= "<tr><td>".$x."</td><td colspan=\"7\">".$info['Error']."</td></tr>";
				}
			else
				{
				$sql1 = sed_sql_query("SELECT SUM(pl_active) FROM $db_plugins WHERE pl_code='$x'");
				$sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_plugins WHERE pl_code='$x'");
				$totalactive = sed_sql_result($sql1, 0, "SUM(pl_active)");
				$totalinstalled = sed_sql_result($sql2, 0, "COUNT(*)");
				$cnt_parts += $totalinstalled;

				if ($totalinstalled ==0)
					{
					$part_status = 3;
					$info['Partscount'] = '';
					}
				else
					{
					$info['Partscount'] = '('.$totalinstalled.')';
					if ($totalinstalled>$totalactive && $totalactive>0)
						{ $part_status = 2; }
					elseif ($totalactive==0)
						{ $part_status = 0; }
					else
						{ $part_status = 1; }
					}

				$adminmain .= "<tr><td><a href=\"".sed_url("admin", "m=plug&a=details&pl=".$info['Code'])."\">";				
				$adminmain .= sed_plugin_icon($info['Code']);
				$adminmain .= " ".$info['Name']."</a></td><td>".$x."</td>";
				$adminmain .= "<td style=\"text-align:center;\">".$info['Version']."</td>";
				$adminmain .= "<td style=\"text-align:center;\">".$status[$part_status]." ".$info['Partscount']."</td>";
				$adminmain .= "<td style=\"text-align:center;\">";
				$adminmain .= ($cfgentries[$info['Code']]>0) ? "<a href=\"".sed_url("admin", "m=config&n=edit&o=plug&p=".$info['Code'])."\"><img src=\"system/img/admin/config.png\" alt=\"\" /></a>" : '&nbsp;';
				$adminmain .= "</td>";
				$adminmain .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=rightsbyitem&ic=plug&io=".$info['Code'])."\"><img src=\"system/img/admin/rights2.png\" alt=\"\" /></a></td>";
				$adminmain .= "<td style=\"text-align:center;\">";

				if ($plg_tools[$info['Code']])
					{
					$adminmain .= "<a href=\"".sed_url("admin", "m=tools&p=".$info['Code'])."\"><img src=\"system/img/admin/jumpto.png\" alt=\"\" /></a>";
					}
				else
					{
					$adminmain .= ($plg_standalone[$info['Code']]) ? "<a href=\"".sed_url("plug", "e=".$info['Code'])."\"><img src=\"system/img/admin/jumpto.png\" alt=\"\" /></a>" : '&nbsp;';
					}
				$adminmain .= "</td></tr>";
				}
			}
		else
			{
			$disp_errors .= "<tr><td>plugins/".$x."</td><td colspan=\"7\">Error: Setup file is missing !</td></tr>";
			}
		}
	$adminmain .= $disp_errors;
	$adminmain .= "</table>";

	if ($o=='code')
		{ $sql = sed_sql_query("SELECT * FROM $db_plugins ORDER BY pl_code ASC, pl_hook ASC, pl_order ASC"); }
	else
		{ $sql = sed_sql_query("SELECT * FROM $db_plugins ORDER BY pl_hook ASC, pl_code ASC, pl_order ASC"); }

	$adminmain .= "<h4 id=\"hooks\">".$L['Hooks']." (".sed_sql_numrows($sql).") :</h4>";
	$adminmain .= "<table class=\"cells striped\">";
	$adminmain .= "<tr><td class=\"coltop\">".$L['Hooks']."</td><td class=\"coltop\">".$L['Plugin']."</td>";
	$adminmain .= "<td class=\"coltop\" style=\"text-align:center;\">".$L['File']."</td>";
	$adminmain .= "<td class=\"coltop\" style=\"text-align:center;\">".$L['Order']."</td>";
	$adminmain .= "<td class=\"coltop\" style=\"text-align:center;\">".$L['Active']."</td></tr>";

	while ($row = sed_sql_fetchassoc($sql))
		{
		$extplugin_file = "plugins/".$row['pl_code']."/".$row['pl_file'].".php";
		$info_file = sed_infoget($extplugin_file, 'SED_EXTPLUGIN');
		
		$adminmain .= "<tr>";
		$adminmain .= "<td>".$row['pl_hook']."</td>";
		$adminmain .= "<td>".$row['pl_title']." (".$row['pl_code'].")</td>";
		$adminmain .= "<td>";
		
		$adminmain .= (file_exists($extplugin_file)) ? "<span style=\"color:#739E48; font-weight:bold;\">".$extplugin_file."</span>" : "<a href=\"".sed_url("admin", "m=plug&a=delhook&id=".$row['pl_id']."&".sed_xg(), "#hooks")."\">".$out['img_delete']."</a> <span style=\"color:#AC5866; font-weight:bold;\">".$L['adm_missing']." : ".$extplugin_file."</span>";
	 
		$adminmain .= "</td>";
		$adminmain .= "<td style=\"text-align:center;\">".$row['pl_order']."</td>";
		$adminmain .= "<td style=\"text-align:center;\">".$sed_yesno[$row['pl_active']]."</td>";
		$adminmain .= "</tr>";
		}

	$adminmain .= "</table>";

	break;
	}

?>