<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.ratings.inc.php
Version=173
Updated=2012-sep-23
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('ratings', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array ("admin.php?m=tools", $L['adm_manage']);
$adminpath[] = array ("admin.php?m=ratings", $L['Ratings']);
$adminhelp = $L['adm_help_ratings'];
$adminmain = "<h2><img src=\"system/img/admin/ratings.png\" alt=\"\" /> ".$L['Ratings']."</h2>";

$id = sed_import('id','G','TXT');
$ii=0;
$jj=0;

$adminmain .= "<ul><li><a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=ratings\">".$L['Configuration']."</a></li></ul>";

if ($a=='delete')
	{
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id' ");
	$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id' ");
	header("Location: admin.php?m=ratings");
	exit;
	}

// [Limit patch]
$d = sed_import('d', 'G', 'INT');
if(empty($d)) $d = 0;
$totallines = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_ratings"), 0, 0);
$totalpages = ceil($totallines / $cfg['maxrowsperpage']);
$currentpage= ceil ($d / $cfg['maxrowsperpage'])+1;
$pagination = '';
for($i = 1; $i <= $totalpages; $i++)
{
	$pagination .= ($i == $currentpage) ? ' <span class="pagenav_current">' : ' ';
	$pagination .= '<a href="admin.php?m=ratings&d='.(($i-1)*$cfg['maxrowsperpage']).'">'.$i.'</a>';
	$pagination .= ($i == $currentpage) ? '</span> ' : ' ';
	if($i != $totalpages) $pagination .= '|';
}
$adminmain .= '<div class="paging">'.$pagination.'</div>';
$sql = sed_sql_query("SELECT * FROM $db_ratings WHERE 1 ORDER by rating_id DESC LIMIT $d,".$cfg['maxrowsperpage']);
// [/Limit patch]

$adminmain .= "<table class=\"cells\"><tr>";
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
			$rat_url = "page.php?id=".$rat_value."&amp;ratings=1";
		break;

		default:
			$rat_url = '';
		break;
		}

	$adminmain .= "<tr><td style=\"text-align:center;\"><a href=\"admin.php?m=ratings&amp;a=delete&amp;id=".$row['rating_code']."&amp;".sed_xg()."\">".$out['img_delete']."</a></td>";
	$adminmain .= "<td style=\"text-align:center;\">".$row['rating_code']."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".date($cfg['dateformat'], $row['rating_creationdate'])."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".$votes."</td>";
	$adminmain .= "<td style=\"text-align:center;\">".$row['rating_average']."</td>";
	$adminmain .= "<td style=\"text-align:center;\"><a href=\"".$rat_url."\"><img src=\"system/img/admin/jumpto.png\" alt=\"\"></a></td></tr>";
	$ii++;
	$jj = $jj + $votes;
	}

$adminmain .= "<tr><td colspan=\"8\">".$L['adm_ratings_totalitems']." : ".$ii."<br />";
$adminmain .= $L['adm_ratings_totalvotes']." : ".$jj."</td></tr></table>";

?>
