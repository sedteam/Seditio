<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.config.time.inc.php
Version=177
Updated=2015-feb-06
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }


$adminhelp = $L['adm_help_versions'];

$adminmain .= "<div class=\"content-box\">";
$adminmain .= "<div class=\"content-box-header\">";					
$adminmain .= "				<h3>".$L['adm_clocks']."</h3>";					
$adminmain .= "</div>";

$adminmain .= "<div class=\"content-box-content\">";

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr><td>".$L['adm_time1']."</td><td> ".date("Y-m-d H:i")." </td></tr>";
$adminmain .= "<tr><td>".$L['adm_time2']."</td><td> ".gmdate("Y-m-d H:i")." GMT </td></tr>";
$adminmain .= "<tr><td>".$L['adm_time3']."</td>";
$adminmain .= "<td>".$usr['gmttime']." </td></tr>";
$adminmain .= "<tr><td>".$L['adm_time4']." : </td>";
$adminmain .= "<td>".sed_build_date($cfg['dateformat'], $sys['now_offset'])." ".$usr['timetext']." </td></tr>";
$adminmain .= "</table>";

$adminmain .= "</div></div>";

?>