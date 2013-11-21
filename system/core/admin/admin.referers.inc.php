<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.statistics.referers.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['auth_read']);

$adminpath[] = array ("admin.php?m=tools", $L['adm_manage']);
$adminpath[] = array ("admin.php?m=referers", $L['Referers']);
$adminhelp = $L['adm_help_referers'];
$adminmain = "<h2><img src=\"system/img/admin/info.png\" alt=\"\" /> ".$L['Referers']."</h2>";

$d = sed_import('d', 'G', 'INT');
if(empty($d)) { $d = 0; }

if ($a=='prune' && $usr['isadmin'])
	{ 
	sed_check_xg();
	$sql = sed_sql_query("TRUNCATE $db_referers"); }
elseif ($a=='prunelowhits' && $usr['isadmin'])
	{ 
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_referers WHERE ref_count<6"); }

$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_referers"), 0, 0);
$pagination = sed_pagination("admin.php?m=referers", $d, $totallines, 100);
list($pagination_prev, $pagination_next) = sed_pagination_pn("admin.php?m=referers", $d, $totallines, 100, TRUE);

$sql = sed_sql_query("SELECT * FROM $db_referers ORDER BY ref_count DESC LIMIT $d, 100");
$adminmain .= ($usr['isadmin']) ? "<ul><li>".$L['adm_purgeall']." : [<a href=\"admin.php?m=referers&amp;a=prune&amp;".sed_xg()."\">x</a>]</li><li>".$L['adm_ref_lowhits']." : [<a href=\"admin.php?m=referers&amp;a=prunelowhits&amp;".sed_xg()."\">x</a>]</li></ul>" : '';
$adminmain .= "<table class=\"paging\"><tr><td class=\"paging_left\">".$pagination_prev."</td>";
$adminmain .= "<td class=\"paging_center\">".$pagination."</td>";
$adminmain .= "<td class=\"paging_right\">".$pagination_next."</td></tr></table>";
$adminmain .= "<table class=\"cells\"><tr><td class=\"coltop\">".$L['Referer']."</td><td class=\"coltop\">".$L['Hits']."</td></tr>";

if (sed_sql_numrows($sql)>0)
	{
	while ($row = sed_sql_fetchassoc($sql))
		{
		preg_match_all("|//([^/]+)/|", $row['ref_url'], $a);
		$referers[$a[1][0]][$row['ref_url']] = $row['ref_count'];
		}

	foreach($referers as $referer => $url)
		{
		$referer = htmlspecialchars($referer);
		$adminmain .= "<tr><td colspan=\"2\"><a href=http://".$referer.">".$referer."</a></td></tr>";
		foreach ($url as $uri=>$count)
			{
			$uri1 = sed_cutstring($uri, 48);
			$adminmain .= "<tr><td>&nbsp; &nbsp; <a href=\"".htmlspecialchars($uri)."\">".htmlspecialchars($uri1)."</a></td>";
			$adminmain .= "<td style=\"text-align:right;\">".$count."</td></tr>";
			}
		}
	}
else
	{ $adminmain .= "<tr><td colspan=\"2\">".$L['None']."</td></tr>"; }

$adminmain .= "</table>";

?>
