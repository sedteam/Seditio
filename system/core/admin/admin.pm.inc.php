<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.pm.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pm', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=tools"), $L['adm_manage']);
$adminpath[] = array (sed_url("admin", "m=pm"), $L['Private_Messages']);
$adminhelp = $L['adm_help_pm'];
$adminmain = "<h2><img src=\"system/img/admin/pm.png\" alt=\"\" /> ".$L['Private_Messages']."</h2>";

$adminmain .= "<ul class=\"arrow_list\"><li><a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=pm")."\">".$L['Configuration']."</a></li></ul>";

$totalpmdb = sed_sql_rowcount($db_pm);
$totalpmsent = sed_stat_get('totalpms');

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr><td colspan=\"2\" class=\"coltop\">".$L['Statistics']."</td></tr>";
$adminmain .= "<tr><td>".$L['adm_pm_totaldb']."</td><td style=\"text-align:center;\">".$totalpmdb."</td></tr>";
$adminmain .= "<tr><td>".$L['adm_pm_totalsent']."</td><td style=\"text-align:center;\">".$totalpmsent."</td></tr>";

$adminmain .= "</table>";

?>