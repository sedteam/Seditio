<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=polls.php
Version=178
Updated=2022-jun-12
Type=Core
Author=Seditio Team
Description=Polls
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/* === Hook === */
$extp = sed_getextplugins('polls.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('polls', 'a');
sed_block($usr['auth_read']);

$polls_header1 = $cfg['doctype']."\n<html>\n<head>
<title>".$cfg['maintitle']."</title>".sed_htmlmetas().sed_javascript($morejavascript);

$polls_header2 = "</head>\n<body>";
$polls_footer = "</body>\n</html>";

$id = sed_import('id','G','ALP',8);
$vote = sed_import('vote','G','INT');
$comments = sed_import('comments','G','BOL');
$ratings = sed_import('ratings','G','BOL');

if ($id=='viewall')
	{
	$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_state=0 AND poll_type=0 ORDER BY poll_id DESC");
	}
else
	{
	$id = sed_import($id,'D','INT');
	
	if ($id>0)
		{
		$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id='$id' AND poll_state=0");

		if ($row = sed_sql_fetchassoc($sql))
			{
			$poll_state = $row['poll_state'];
			$poll_minlevel = $row['poll_minlevel'];
      $poll_title = $row['poll_text'];
      $poll_creationdate = $row['poll_creationdate'];

			if ($usr['id']>0)
			 	{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND (pv_userid='".$usr['id']."' OR pv_userip='".$usr['ip']."') LIMIT 1"); }
					else
			 	{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$id' AND pv_userip='".$usr['ip']."' LIMIT 1"); }

			$alreadyvoted = (sed_sql_numrows($sql2)>0) ? 1 : 0;

			if ($a=='send' && empty($error_string) && !$alreadyvoted)
				{
				$sql2 = sed_sql_query("UPDATE $db_polls_options SET po_count=po_count+1 WHERE po_pollid='$id' AND po_id='$vote'");
				if (sed_sql_affectedrows()==1)
					{
					$sql2 = sed_sql_query("INSERT INTO $db_polls_voters (pv_pollid, pv_userid, pv_userip) VALUES (".(int)$id.", ".(int)$usr['id'].", '".$usr['ip']."')");
					$votecasted = TRUE;
					$alreadyvoted = TRUE;
					}
				}

			$sql2 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$id'");
			$totalvotes = sed_sql_result($sql2,0,"SUM(po_count)");

			$sql1 = sed_sql_query("SELECT po_id, po_text, po_count FROM $db_polls_options WHERE po_pollid='$id' ORDER by po_id ASC");
			$error_string = (sed_sql_numrows($sql1)<1) ? $L['wrongURL'] : '';
			}
       	else
			{ $error_string = $L['wrongURL']; }
		}
	else
		{ sed_die(); }
	}

$out['subtitle'] = $L['Polls'];

sed_sendheaders();

/* === Hook === */
$extp = sed_getextplugins('polls.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t = new XTemplate("skins/".$skin."/polls.tpl");

$t->assign(array(
	"POLLS_HEADER1" => $polls_header1,
	"POLLS_HEADER2" => $polls_header2,
	"POLLS_FOOTER" => $polls_footer,
	));

if (!empty($error_string))
	{
	$t->assign("POLLS_EXTRATEXT",$error_string);
	$t->parse("MAIN.POLLS_EXTRA");
	}
elseif ($id=='viewall')
	{
	$result = "<table class=\"cells striped\">";

	if (sed_sql_numrows($sql)==0)
		{ $result .= "<tr><td>".$L['None']."</td></tr>"; }
       else
		{
		while ($row = sed_sql_fetchassoc($sql))
			{
			$result .= "<tr>";
			$result .= "<td style=\"width:128px;\">".sed_build_date($cfg['formatyearmonthday'], $row['poll_creationdate'])."</td>";
			$result .= "<td><a href=\"".sed_url("polls", "id=".$row['poll_id'])."\"><img src=\"system/img/admin/polls.png\" alt=\"\" /></a></td>";
			$result .= "<td>".$row['poll_text']."</td>";
			$result .= "</tr>";
			}
		}
	$result .= "</table>";

	$t->assign(array(
		"POLLS_LIST" => $result,
		));

	$t->parse("MAIN.POLLS_VIEWALL");
	}
else
	{
	$result = "<table class=\"cells striped\">";

	while ($row1 = sed_sql_fetchassoc($sql1))
		{
		$po_id = $row1['po_id'];
		$po_count = $row1['po_count'];
		$percent = @round(100 * ($po_count / $totalvotes),1);
		$percentbar = floor($percent * 2.24);

		$result .= "<tr><td>";
		$result .= ($alreadyvoted) ? $row1['po_text'] : "<a href=\"".sed_url("polls", "a=send&".sed_xg()."&id=".$id."&vote=".$po_id)."\">".sed_cc($row1['po_text'])."</a>";
		$result .= "</td><td><div style=\"width:256px;\"><div class=\"bar_back\"><div class=\"bar_front\" style=\"width:".$percent."%;\"></div></div></div></td><td>$percent%</td><td>(".$po_count.")</td></tr>";

		}

	$result .= "</table>";

	$item_code = 'v'.$id;
  
  $url_poll = array('part' => 'polls', 'params' => "id=".$id."&comments=1");
  
	$cfg['enablemodal'] = false;
	list($comments_link, $comments_display) = sed_build_comments($item_code, $url_poll, $comments);

	$t->assign(array(
		"POLLS_VOTERS" => $totalvotes,
		"POLLS_SINCE" => sed_build_date($cfg['dateformat'], $poll_creationdate),
		"POLLS_TITLE" => $poll_title,
		"POLLS_RESULTS" => $result,
		"POLLS_COMMENTS" => $comments_link,
		"POLLS_COMMENTS_DISPLAY" => $comments_display,
		"POLLS_VIEWALL" => "<a href=\"".sed_url("polls", "id=viewall")."\">".$L['polls_viewarchives']."</a>",
		));

	$t->parse("MAIN.POLLS_VIEW");

	if ($alreadyvoted)
		{ $extra = ($votecasted) ? $L['polls_votecasted'] : $L['polls_alreadyvoted']; }
	else
		{ $extra = $L['polls_notyetvoted']; }

	$t->assign(array(
		"POLLS_EXTRATEXT" => $extra,
		));

	$t->parse("MAIN.POLLS_EXTRA");

	}

/* === Hook === */
$extp = sed_getextplugins('polls.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

@ob_end_flush();
@ob_end_flush();

sed_sql_close($connection_id);
?>