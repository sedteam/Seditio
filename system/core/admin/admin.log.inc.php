<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.statistics.log.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['auth_read']);

$adminpath[] = array (sed_url("admin", "m=log"), $L['Log']);
$adminhelp = $L['adm_help_log'];
$adminmain = "<h2><img src=\"system/img/admin/log.png\" alt=\"\" /> ".$L['Log']."</h2>";

$log_groups = array (
	'all' => $L['All'],
	'def' => $L['Default'],
	'adm' => $L['Administration'],
	'for' => $L['Forums'],
	'sec' => $L['Security'],
	'usr' => $L['Users'],
	'plg' => $L['Plugins']
	);

$d = sed_import('d', 'G', 'INT');
if(empty($d)) { $d = 0; }

if ($a=='purge' && $usr['isadmin'])
	{
	sed_check_xg();
	$sql = sed_sql_query("TRUNCATE $db_logger");
	}

$totaldblog = sed_sql_rowcount($db_logger);

$n = (empty($n)) ? 'all' : $n;

$group_select = "<form>".$L['Group']." : <select name=\"groups\" size=\"1\" onchange=\"redirect(this)\">";

foreach($log_groups as $grp_code => $grp_name)
	{
	$selected = ($grp_code==$n) ? "selected=\"selected\"" : "";
	$group_select .= "<option value=\"".sed_url("admin", "m=log&n=".$grp_code)."\" $selected>".$grp_name."</option>";
	}

$group_select .= "</select></form>";
	
$totallines = ($n == 'all') ? $totaldblog : sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_logger WHERE log_group='$n'"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=log&n=".$n), $d, $totallines, 100);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=log&n=".$n), $d, $totallines, 100, TRUE);

if ($n=='all')
	$sql = sed_sql_query("SELECT * FROM $db_logger WHERE 1 ORDER by log_id DESC LIMIT $d, 100");
else
	$sql = sed_sql_query("SELECT * FROM $db_logger WHERE log_group='$n' ORDER by log_id DESC LIMIT $d,100");

$adminmain .= ($usr['isadmin']) ? $L['adm_purgeall']." (".$totaldblog.") : [<a href=\"".sed_url("admin", "m=log&a=purge&".sed_xg())."\">x</a>]<br />&nbsp;<br />" : '';
$adminmain .= $group_select;

$adminmain .= "<div class=\"paging\">";
$adminmain .= "<ul class=\"pagination\">";
$adminmain .= "<li class=\"prev\">".$pagination_prev."</li>";
$adminmain .= $pagination;
$adminmain .= "<li class=\"next\">".$pagination_next."</li>";
$adminmain .= "</ul>";
$adminmain .= "</div>";

$adminmain .= "<table class=\"cells striped\"><tr><td class=\"coltop\">#</td><td class=\"coltop\" style=\"width:100px;\">".$L['Date']." (GMT)</td>";
$adminmain .= "<td class=\"coltop\">".$L['Ip']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['User']."</td><td class=\"coltop\">".$L['Group']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Log']."</td></tr>";

while ($row = sed_sql_fetchassoc($sql))
	{
	$adminmain .= "<tr><td>".$row['log_id']."</td>";
	$adminmain .= "<td>".sed_build_date($cfg['dateformat'], $row['log_date'])." &nbsp;</td>";
	$adminmain .= "<td><a href=\"".sed_url("admin", "m=tools&p=ipsearch&a=search&id=".$row['log_ip']."&".sed_xg())."\">";
	$adminmain .= $row['log_ip']."</a> &nbsp;</td>";
	$adminmain .= "<td>".$row['log_name']." &nbsp;</td>";
	$adminmain .= "<td><a href=\"".sed_url("admin", "m=log&n=".$row['log_group'])."\">";
	$adminmain .= $log_groups[$row['log_group']]."</a> &nbsp;</td>";
	$adminmain .= "<td class=\"desc\"><div style=\"word-break: break-all;\">".htmlspecialchars($row['log_text'])."</div></td></tr>";
	}
$adminmain .= "</table>";

$adminmain .= "<div class=\"paging\">";
$adminmain .= "<ul class=\"pagination\">";
$adminmain .= "<li class=\"prev\">".$pagination_prev."</li>";
$adminmain .= $pagination;
$adminmain .= "<li class=\"next\">".$pagination_next."</li>";
$adminmain .= "</ul>";
$adminmain .= "</div>";

?>