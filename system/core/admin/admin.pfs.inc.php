<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.pfs.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=tools"), $L['adm_manage']);
$adminpath[] = array (sed_url("admin", "m=pfs"), $L['PFS']);
$adminhelp = $L['adm_help_pfs'];
$adminmain = "<h2><img src=\"system/img/admin/pfs.png\" alt=\"\" /> ".$L['PFS']."</h2>";

$adminmain .= "<ul class=\"arrow_list\"><li><a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=pfs")."\">".$L['Configuration']."</a></li>";
$adminmain .= "<li><a href=\"".sed_url("pfs", "userid=0")."\">".$L['SFS']."</a></li></ul>";

// ============= All PFS ==============================

$adminmain .= "<h3>".$L['adm_allpfs']."</h3>";

unset ($disp_list);

$sql = sed_sql_query("SELECT DISTINCT p.pfs_userid, u.user_name, u.user_id, COUNT(*) FROM $db_pfs AS p 
	LEFT JOIN $db_users AS u ON p.pfs_userid=u.user_id
	WHERE pfs_folderid>=0 GROUP BY p.pfs_userid ORDER BY u.user_name ASC");

while ($row = sed_sql_fetchassoc($sql))
	{
	$row['user_name'] = ($row['user_id']==0) ? $L['SFS'] : $row['user_name'];
	$row['user_id'] = ($row['user_id']==0) ? "0" : $row['user_id'];
	
	$disp_list .= "<tr>";
	$disp_list .= "<td style=\"text-align:center;\"><a href=\"".sed_url("pfs", "userid=".$row['user_id'])."\">".$out['img_edit']."</a></td>";
	$disp_list .= "<td>".sed_build_user($row['user_id'], sed_cc($row['user_name']))."</td>";
 	$disp_list .= "<td style=\"text-align:center;\">".$row['COUNT(*)']."</td>";
	$disp_list .= "</tr>";
	}

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr><td class=\"coltop\">".$L['Edit']."</td><td class=\"coltop\">".$L['User']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Files']."</td></tr>".$disp_list."</table>";

?>