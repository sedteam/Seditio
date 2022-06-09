<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=admin.statistics.referers.inc.php
Version=178
Updated=2021-jun-17
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['auth_read']);

$adminpath[] = array (sed_url("admin", "m=tools"), $L['adm_manage']);
$adminpath[] = array (sed_url("admin", "m=referers"), $L['Referers']);

$adminhelp = $L['adm_help_referers'];

$d = sed_import('d', 'G', 'INT');
if(empty($d)) { $d = 0; }

if ($a=='prune' && $usr['isadmin'])
	{ 
	sed_check_xg();
	$sql = sed_sql_query("TRUNCATE $db_referers"); 
	}
elseif ($a=='prunelowhits' && $usr['isadmin'])
	{ 
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_referers WHERE ref_count<6"); 
	}

$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_referers"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=referers"), $d, $totallines, 100);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=referers"), $d, $totallines, 100, TRUE);

$sql = sed_sql_query("SELECT * FROM $db_referers ORDER BY ref_count DESC LIMIT $d, 100");

$t = new XTemplate(sed_skinfile('admin.referers', true)); 

if ($usr['isadmin'])
{
	$t->assign(array(
		"REFERERS_PURGEALL_URL" => sed_url("admin", "m=referers&a=prune&".sed_xg()),
		"REFERERS_PURGE_LOWHITS_URL" => sed_url("admin", "m=referers&a=prunelowhits&".sed_xg())
	));
	$t->parse("ADMIN_REFERERS.REFERERS_PURGE");	
}

if (!empty($pagination))
	{
	$t->assign(array(
		"REFERERS_PAGINATION" => $pagination,
		"REFERERS_PAGEPREV" => $pagination_prev,
		"REFERERS_PAGENEXT" => $pagination_next
	));	
	$t->parse("ADMIN_REFERERS.REFERERS_PAGINATION_TP");
	$t->parse("ADMIN_REFERERS.REFERERS_PAGINATION_BM");		
	}

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
		$t->assign("REFERER_GROUP_URL", $referer);		
		foreach ($url as $uri=>$count)
			{
			$uri1 = sed_cutstring($uri, 48);			
			$t->assign(array(
				"REFERER_URL" => htmlspecialchars($uri),
				"REFERER_TITLE" => htmlspecialchars($uri1),
				"REFERER_COUNT" => $count	
			));
			$t->parse("ADMIN_REFERERS.REFERERS_LIST.REFERERS_LIST_ITEM");
			}
		$t->parse("ADMIN_REFERERS.REFERERS_LIST");
		}
	}
else
	{ $t->parse("ADMIN_REFERERS.REFERERS_NONE"); }

$t -> parse("ADMIN_REFERERS");

$adminmain .= $t -> text("ADMIN_REFERERS"); 

?>