<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.home.inc.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$adminpath[] = array (sed_url("admin", "m=home"), $L['Home']);

$pagesqueued = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='1'");
$pagesqueued = sed_sql_result($pagesqueued, 0, "COUNT(*)");

$sys['user_istopadmin'] = sed_auth('admin', 'a', 'A');

// --------------------------

if (!function_exists('gd_info') && $cfg['th_amode']!='Disabled')
	{ $adminwarnings .= "<p>".$L['adm_nogd']."</p>"; }

if (!empty($adminwarnings))
	{ $adminmain .= "<div class=\"error\">".$L['adm_warnings']." :".$adminwarnings."</div>"; }


$adminmain .= "<div class=\"sedtabs\">	
<ul class=\"tabs\">
  <li><a href=\"".$sys['request_uri']."#tab1\">".$L['Pages']."</a></li>
  <li><a href=\"".$sys['request_uri']."#tab2\">".$L['upg_upgrade']."</a></li>
  <li><a href=\"".$sys['request_uri']."#tab3\">".$L['adm_infos']."</a></li>
</ul>    
<div class=\"tab-box\">";

$adminmain .= "<div id=\"tab1\" class=\"tabs\">";	
	
$adminmain .= "<h4>".$L['adm_valqueue']." :</h4><ul class=\"arrow_list\">";
$adminmain .= "<li><a href=\"".sed_url("admin", "m=page")."\">".$L['Pages']." : ".$pagesqueued."</a></li>";
$adminmain .= "<li>".sed_linkif(sed_url("page", "m=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'))."</li>";
$adminmain .= "</ul>";

$adminmain .= "</div>";

// --------------------------

if ($sys['user_istopadmin'])
	{
	if ($a=='force')
		{
		sed_check_xg();
		$forcesql = sed_import('forcesql','P',INT);
		sed_stat_set('version', $forcesql);
		sed_redirect(sed_url("admin", "", "", true));
		exit;
		}

	if (!($cfg['sqlversion'] = sed_stat_get('version')))
    {
    sed_stat_create('version', $cfg['version']);
    $cfg['sqlversion'] = $cfg['version'];
    }

	$adminmain .= "<div id=\"tab2\" class=\"tabs\">";

	$adminmain .= "<h4>".$L['upg_upgrade']." :</h4>";

	$adminmain .= "<form id=\"forcesqlversion\" action=\"".sed_url("admin", "a=force&".sed_xg())."\" method=\"post\">";
	$adminmain .= "<table class=\"cells striped\" >";
	$adminmain .= "<tr><td>".$L['upg_codeversion']." :</td><td style=\"text-align:center;\">".$cfg['version']."</td></tr>";
	$adminmain .= "<tr><td>".$L['upg_sqlversion']." :</td><td style=\"text-align:center;\">".$cfg['sqlversion']."</td></tr>";
	$adminmain .= "<tr><td>";

	if ($cfg['version'] > $cfg['sqlversion'])
		{
  	$adminmain .=  $L['upg_codeisnewer'];
	  $upg_file = "system/upgrade/upgrade_".$cfg['sqlversion']."_".$cfg['version'].".php";
    $status_ok = FALSE;
    
		if (file_exists($upg_file))
		   {
	     	$adminmain .= "<br /><strong><a href=\"".sed_url("admin", "m=upgrade&".sed_xg())."\">".$L['upg_upgradenow']."</a></strong>";
	     	$adminmain .= "<br />".$L['upg_manual'];
	    	}
  		else
    		{
	    	$adminmain .= "<br /><strong>".$L['upg_upgradenotavail']."</strong>";
	    	}
		}
	elseif ($cfg['version'] == $cfg['sqlversion'])
		{
    $status_ok = TRUE;
  		$adminmain .= $L['upg_codeissame'];
		}
	elseif ($cfg['version'] < $cfg['sqlversion'])
		{
	 	$adminmain .= $L['upg_codeisolder'];
		}

	$adminmain .= "</td><td style=\"text-align:center; width:10%; vertical-align:middle;\" rowspan=\"2\">";
	$adminmain .= ($status_ok) ? $out['img_checked'] : "<img src=\"system/img/admin/warning.png\" alt=\"\" />"; 
	$adminmain .= "</td></tr>";
	$adminmain .= "<tr><td>".$L['upg_force'];
	$adminmain .= "<select name=\"forcesql\" size=\"1\">";

	while( list($i,$x) = each($cfg['versions_list']) )
		{
		$selected = ($x==$cfg['sqlversion']) ? "selected=\"selected\"" : '';
		$adminmain .= "<option value=\"$x\" $selected>".$x."</option>";
		}

	$adminmain .= "</select>";
	$adminmain .= " <input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" />";
	$adminmain .= "</td></tr></table></form>";

	$adminmain .= "</div>";
	}

$adminmain .= "<div id=\"tab3\" class=\"tabs\">";

$adminmain .= "<h4>".$L['adm_infos']." :</h4>";
$adminmain .= "<div name=\"log\" id=\"infos\">";
$adminmain .= "<table class=\"cells striped\" >";	
$adminmain .= (function_exists('phpversion')) ? "<tr><td>".$L['adm_phpver']." :</td><td style=\"text-align:center;\">".@phpversion()."</td></tr>" : '' ;
$adminmain .= (function_exists('zend_version')) ? "<tr><td>".$L['adm_zendver']." :</td><td style=\"text-align:center;\">".@zend_version()."</td></tr>" : '';
$adminmain .= (function_exists('php_sapi_name')) ? "<tr><td>".$L['adm_interface']." :</td><td style=\"text-align:center;\">".@php_sapi_name()."</td></tr>" : '';
$adminmain .= (function_exists('php_uname')) ? "<tr><td>".$L['adm_os']." :</td><td style=\"text-align:center;\">".@php_uname()."</td></tr>" : '';
$mysql_ver = sed_sql_query("SELECT VERSION() as mysql_version");
$adminmain .= "<tr><td>SQL :</td><td style=\"text-align:center;\">".sed_sql_result($mysql_ver, 0, "mysql_version")."</td></tr>";
$adminmain .= "</table>";
$adminmain .= "</div>";

$adminmain .= "</div></div></div>";

// --------------------------

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.home', 'R');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

if ($cfg['trash_prunedelay']>0)
	{
	$timeago = $sys['now_offset'] - ($cfg['trash_prunedelay'] * 86400);
	$sqltmp = sed_sql_query("DELETE FROM $db_trash WHERE tr_date<$timeago");
	$deleted = sed_sql_affectedrows();
	if ($deleted>0)
		{ sed_log($deleted.' old item(s) removed from the trashcan, older than '.$cfg['trash_prunedelay'].' days', 'adm'); }
	}

?>