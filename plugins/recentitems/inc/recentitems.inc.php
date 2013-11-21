<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/recentitems/inc/recentitems.inc.php
Version=173
Updated=2012-oct-18
Type=Plugin
Author=Neocrome
Description=
[END_SED]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }


/* ================== FUNCTIONS ================== */

function sed_get_latestpages($limit, $mask)
	{
	global $L, $db_pages, $usr, $cfg, $sed_cat, $plu_empty;

	$sql = sed_sql_query("SELECT page_id, page_alias, page_cat, page_title, page_date FROM $db_pages WHERE page_state=0 AND page_cat NOT LIKE 'system' ORDER by page_date DESC LIMIT $limit");

	while ($row = sed_sql_fetchassoc($sql))
		{
		if (sed_auth('page', $row['page_cat'], 'R'))
			{
			$row['page_pageurl'] = (empty($row['page_alias'])) ? "page.php?id=".$row['page_id'] : "page.php?al=".$row['page_alias'];
		$res .= sprintf($mask,
			"<a href=\"list.php?c=".$row['page_cat']."\">".$sed_cat[$row['page_cat']]['title']."</a>",
			"<a href=\"".$row['page_pageurl']."\">".sed_cc(sed_cutstring(stripslashes($row['page_title']), 36))."</a>",
			date($cfg['formatyearmonthday'], $row['page_date'] + $usr['timezone'] * 3600)
				);
			}
		}

	$res = (empty($res)) ? $plu_empty : $res;

	return($res);
	}

/* ------------------ */

function sed_get_latesttopics($limit, $mask)
	{
	global $L, $db_forum_topics, $db_forum_sections, $usr, $cfg, $skin, $plu_empty;

	$sql0 = sed_sql_query("SELECT fs_id, fs_title, fs_parentcat, fs_lt_id, fs_lt_title, fs_lt_date, fs_lt_posterid, fs_lt_postername 
		FROM $db_forum_sections WHERE fs_parentcat = 0 ORDER BY fs_order ASC");

	while ($fsn_sub = sed_sql_fetchassoc($sql0))
		{	
			$forum_parentcat[$fsn_sub['fs_id']] = $fsn_sub;
		}	
	
	$sql = sed_sql_query("SELECT t.ft_id, t.ft_sectionid, t.ft_title, t.ft_updated, t.ft_postcount, s.fs_id, s.fs_title, s.fs_category, s.fs_parentcat
		FROM $db_forum_topics t,$db_forum_sections s
		WHERE t.ft_sectionid=s.fs_id
		AND t.ft_movedto=0 AND t.ft_mode=0
		ORDER by t.ft_updated DESC LIMIT $limit");

	while ($row = sed_sql_fetchassoc($sql))
		{
		if (sed_auth('forums', $row['fs_id'], 'R'))
			{
			$img = ($usr['id']>0 && $row['ft_updated']>$usr['lastvisit']) ? "<a href=\"forums.php?m=posts&amp;q=".$row['ft_id']."&amp;n=unread#unread\"><img src=\"skins/$skin/img/system/arrow-unread.gif\" alt=\"\" /></a>" : "<a href=\"forums.php?m=posts&amp;q=".$row['ft_id']."&amp;n=last#bottom\"><img src=\"skins/$skin/img/system/arrow-follow.gif\" alt=\"\" /></a> ";

			if ($row['fs_parentcat'] > 0) 
			{
				$parentcat['sectionid']  = $forum_parentcat[$row['fs_parentcat']]['fs_id'];
				$parentcat['title']  = $forum_parentcat[$row['fs_parentcat']]['fs_title'];
			} else { $parentcat = FALSE; }
			
			$res .= sprintf($mask,
				$img,
				date($cfg['formatmonthdayhourmin'], $row['ft_updated'] + $usr['timezone'] * 3600),
				sed_build_forums($row['fs_id'], sed_cutstring($row['fs_title'],24), sed_cutstring($row['fs_category'],16), TRUE, $parentcat),
				"<a href=\"forums.php?m=posts&amp;q=".$row['ft_id']."&amp;n=last#bottom\">".sed_cc(sed_cutstring(stripslashes($row['ft_title']),25))."</a>",
				$row['ft_postcount']-1
					);
			}
		}

	$res = (empty($res)) ? $plu_empty : $res;

	return($res);
	}

/* ------------------ */

function sed_get_latestpolls($limit, $mask)
	{
	global $L, $db_polls, $db_polls_voters, $db_polls_options, $usr, $plu_empty;



	$sql_p = sed_sql_query("SELECT poll_id, poll_text FROM $db_polls WHERE 1 AND poll_state=0  AND poll_type=0 ORDER by poll_creationdate DESC LIMIT $limit");

	while ($row_p = sed_sql_fetchassoc($sql_p))
		{
		unset($res);
		$poll_id = $row_p['poll_id'];

		if ($usr['id']>0)
	 		{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$poll_id' AND (pv_userid='".$usr['id']."' OR pv_userip='".$usr['ip']."') LIMIT 1"); }
	       else
	 		{ $sql2 = sed_sql_query("SELECT pv_id FROM $db_polls_voters WHERE pv_pollid='$poll_id' AND pv_userip='".$usr['ip']."' LIMIT 1"); }

		if (sed_sql_numrows($sql2)>0)
			{
			$alreadyvoted =1;
			$sql2 = sed_sql_query("SELECT SUM(po_count) FROM $db_polls_options WHERE po_pollid='$poll_id'");
			$totalvotes = sed_sql_result($sql2,0,"SUM(po_count)");
			}
		else
			{ $alreadyvoted =0; }

		$res .= "<h5>".$row_p['poll_text']."</h5>";

		$sql = sed_sql_query("SELECT po_id, po_text, po_count FROM $db_polls_options WHERE po_pollid='$poll_id' ORDER by po_id ASC");

		while ($row = sed_sql_fetchassoc($sql))
			{
			if ($alreadyvoted)
				{
				$percentbar = floor(($row['po_count'] / $totalvotes) * 100);
				$res .= $row['po_text']." : $percentbar%<div style=\"width:95%;\"><div class=\"bar_back\"><div class=\"bar_front\" style=\"width:".$percentbar."%;\"></div></div></div>";
				}
			else
				{
				$res .= "<a href=\"javascript:pollvote('".$poll_id."','".$row['po_id']."')\">";
				$res .= $row['po_text']."</a><br />";
				}
			}
		$res .= "<p style=\"text-align:center;\"><a href=\"javascript:polls('".$poll_id."')\">".$L['polls_viewresults']."</a> &nbsp; ";
		$res .= "<a href=\"javascript:polls('viewall')\">".$L['polls_viewarchives']."</a></p>";
		$res_all .= sprintf($mask, $res);
		}

//		{ $res = $plu_empty; }

	return($res_all);
	}


?>