<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/recentitems/recentitems.ajax.php
Version=175
Updated=2013-jul-08
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=recentitems
Part=main
File=recentitems.ajax
Hooks=ajax
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','ALP',8);
$vote = sed_import('vote','G','INT');
$a = sed_import('a','G','ALP');

$modal = ($cfg['enablemodal']) ? ',1' : '';

/* =================================== */

if ($a=='send' && $id > 0 && $vote > 0)
	{
	if ($usr['id']>0)
	 	{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND (pv_userid='".$usr['id']."' OR pv_userip='".$usr['ip']."') LIMIT 1"); }
			else
	 	{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND pv_userip='".$usr['ip']."' LIMIT 1"); }
	
	$alreadyvoted = (sed_sql_numrows($sql2)>0) ? 1 : 0;
	
	if (!$alreadyvoted)
		{
		$sql2 = sed_sql_query("UPDATE $db_polls_options SET po_count=po_count+1 WHERE po_pollid='$id' AND po_id='$vote'");
		if (sed_sql_affectedrows()==1)
			{
			$sql2 = sed_sql_query("INSERT INTO $db_polls_voters (pv_pollid, pv_userid, pv_userip) VALUES (".(int)$id.", ".(int)$usr['id'].", '".$usr['ip']."')");
			}

		$sql3 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$id'");
		$totalvotes = sed_sql_result($sql3,0,"SUM(po_count)");

		$sql = sed_sql_query("SELECT po_id, po_text, po_count FROM $db_polls_options WHERE po_pollid='$id' ORDER by po_id ASC");
			
		$res = '';
		
		while ($row = sed_sql_fetchassoc($sql))
			{
				$percentbar = floor(($row['po_count'] / $totalvotes) * 100);
				$res .= $row['po_text']." : $percentbar%<div style=\"width:95%;\"><div class=\"bar_back\"><div class=\"bar_front\" style=\"width:".$percentbar."%;\"></div></div></div>";
			}
		
		$res .= "<div style=\"text-align:center;\"><a href=\"javascript:sedjs.polls('".$id."'".$modal.")\">".$L['polls_viewresults']."</a> &nbsp; ";
		$res .= "<a href=\"javascript:sedjs.polls('viewall'".$modal.")\">".$L['polls_viewarchives']."</a></div>";
		
		sed_sendheaders();		
		echo $res;
		}
	}
/* =================================== */




?>