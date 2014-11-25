<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.ratings.inc.php
Version=175
Updated=2012-dec-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('ratings', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=tools"), $L['adm_manage']);
$adminpath[] = array (sed_url("admin", "m=ratings"), $L['Ratings']);
$adminhelp = $L['adm_help_ratings'];
$adminmain = "<h2><img src=\"system/img/admin/ratings.png\" alt=\"\" /> ".$L['Ratings']."</h2>";

$id = sed_import('id','G','TXT');
$ii=0;
$jj=0;

$adminmain .= "<ul class=\"arrow_list\"><li><a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=ratings")."\">".$L['Configuration']."</a></li></ul>";

if ($a=='delete')
	{
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id' ");
	$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id' ");
	sed_redirect(sed_url("admin", "m=ratings", "", true));
	exit;
	}

// [Limit patch]
$d = sed_import('d', 'G', 'INT');
if(empty($d)) $d = 0;

$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_ratings"), 0, 0);
$pagination = sed_pagination(sed_url("admin", "m=ratings"), $d, $totallines, $cfg['maxrowsperpage']);
list($pagination_prev, $pagination_next) = sed_pagination_pn(sed_url("admin", "m=ratings"), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

$adminmain .= "<div class=\"paging\">";
$adminmain .= "<ul class=\"pagination\">";
$adminmain .= "<li class=\"prev\">".$pagination_prev."</li>";
$adminmain .= $pagination;
$adminmain .= "<li class=\"next\">".$pagination_next."</li>";
$adminmain .= "</ul>";
$adminmain .= "</div>";

$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE 1 ORDER by rating_id DESC LIMIT $d,".$cfg['maxrowsperpage']);
// [/Limit patch]

$adminmain .= "<table class=\"cells striped\"><tr>";
$adminmain .= "<td class=\"coltop\" style=\"width:40px;\">".$L['Delete']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Code']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Date']." (GMT)</td>";
$adminmain .= "<td class=\"coltop\">".$L['Votes']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Rating']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:64px;\">".$L['Open']."</td></tr>";

while ($row = sed_sql_fetchassoc($sql))
	{
	$id2 = $row['rating_code'];
	$sql1 = sed_sql_query("SELECT COUNT(*) FROM $db_rated WHERE rated_code='$id2'");
	$votes = sed_sql_result($sql1,0,"COUNT(*)");

	$rat_type = mb_substr($row['rating_code'], 0, 1);
	$rat_value = mb_substr($row['rating_code'], 1);

	switch($rat_type)
		{
		case 'p':
			$rat_url = sed_url("page", "id=".$rat_value."&ratings=1");
		break;

		default:
			$rat_url = '';
		break;
		}

	$adminmain .= "<tr><td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=ratings&a=delete&id=".$row['rating_code']."&".sed_xg())."\">".$out['img_delete']."</a></td>";
	$adminmain .= "<td style=\"text-align:center;\">".$row['rating_code']."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".sed_build_date($cfg['dateformat'], $row['rating_creationdate'])."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".$votes."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".$row['rating_average']."</td>";
	$adminmain .= "<td style=\"text-align:center;\"><a href=\"".$rat_url."\"><img src=\"system/img/admin/jumpto.png\" alt=\"\"></a></td></tr>";
	$ii++;
	$jj = $jj + $votes;
	}

$adminmain .= "<tr><td colspan=\"8\">".$L['adm_ratings_totalitems']." : ".$ii."<br />";
$adminmain .= $L['adm_ratings_totalvotes']." : ".$jj."</td></tr></table>";

$adminmain .= "<div class=\"paging\">";
$adminmain .= "<ul class=\"pagination\">";
$adminmain .= "<li class=\"prev\">".$pagination_prev."</li>";
$adminmain .= $pagination;
$adminmain .= "<li class=\"next\">".$pagination_next."</li>";
$adminmain .= "</ul>";
$adminmain .= "</div>";

?>
