<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/search/search.php
Version=179
Date=2022-jul-25
Type=Plugin
Author=Olivier C. & Spartan
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=search
Part=main
File=search
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) { die('Wrong URL.'); }

$cfg_maxwords = 5;
$cfg_maxitems = 50;

$sq = sed_import('sq','P','TXT');
$a = sed_import('a','G','TXT');
$checked_catarr = array();
      
$plugin_title = '';
$plugin_body = '';

if ($a=='search' && $sq=='')
  {
  unset($a);
  }

if ($a=='search')
  {
	if (mb_strlen($sq)<3)
		{
		$plugin_body .= "<p>".$L['plu_querytooshort']."</p>";
		unset($a);
		}
	else
		{
		$sq = sed_sql_prep($sq);
		$words = explode(" ", $sq);
		$words_count = count($words);
		if ($words_count>$cfg_maxwords)
			{
			$plugin_body .= "<p>".$L['plu_toomanywords']." ".$cfg_maxwords."</p>";
			unset($a);
			}
		}
  }

if ($a=='search')
	{
	$sqlsearch = implode("%", $words);
	$sqlsearch = "%".$sqlsearch."%";

	if (!$cfg['disable_page'])
		{
			
		$pag_sub = sed_import('pag_sub', 'P', 'ARR');
		if (!is_array($pag_sub) || $pag_sub[0] == 'all')
			{ $sqlsections = ''; }
		else
			{
			$sub = array();
			foreach($pag_sub as $i => $k)
				{ 
				$k = sed_import($k,'D','TXT');
				$checked_catarr[] = $k;
				$sub[] = "page_cat='".sed_sql_prep($k)."'"; 
				}
			 $sqlsections = "AND (".implode(' OR ', $sub).")";
			}

		$pagsql = "(p.page_title LIKE '".$sqlsearch."' OR p.page_text LIKE '".sed_sql_prep($sqlsearch)."') AND ";

		$sql  = sed_sql_query("SELECT page_id, page_ownerid, page_title, page_cat, page_date from $db_pages p, $db_structure s
				WHERE $pagsql (p.page_title LIKE '".$sqlsearch."' OR p.page_text LIKE '".sed_sql_prep($sqlsearch)."') AND 
				 p.page_cat=s.structure_code
				AND p.page_cat NOT LIKE 'system' $sqlsections ORDER by page_date DESC 
				LIMIT $cfg_maxitems");
          
		$items = sed_sql_numrows($sql);

		$plugin_body .= "<h4>".$L['Pages'].", ".$L['plu_found']." ".$items." ".$L['plu_match'].": </h4>";

		if ($items>0)
			{
			$plugin_body .= "<table class=\"cells striped\" width=\"100%\">";      
			$plugin_body .= "<tr>";
			$plugin_body .= "<td style=\"width:35%;\" class=\"coltop\">".$L['Category']."</td>";
			$plugin_body .= "<td style=\"width:35%;\" class=\"coltop\">".$L['Page']."</td>";
			$plugin_body .= "<td style=\"width:15%;\" class=\"coltop\">".$L['Date']."</td>";      
			$plugin_body .= "<td style=\"width:15%;\" width=\"15%\"class=\"coltop\">".$L['Owner']."</td>";
			$plugin_body .= "</tr>";

			while ($row = sed_sql_fetchassoc($sql))
				{
				if (sed_auth('page', $row['page_cat'], 'R'))
					{
					$ownername = sed_sql_fetchassoc(sed_sql_query("SELECT user_name FROM $db_users WHERE user_id='".$row['page_ownerid']."'"));
					$plugin_body .= "<tr><td><a href=\"".sed_url("list", "c=".$row['page_cat'])."\">".$sed_cat[$row['page_cat']]['tpath']."</a></td>";
					$plugin_body .= "<td><a href=\"".sed_url("page", "id=".$row['page_id'])."\">".sed_cc($row['page_title'])."</a></td>";
					$plugin_body .= "<td style=\"text-align:center;\">".@date($cfg['dateformat'], $row['page_date'] + $usr['timezone'] * 3600)."</td>";          
					$plugin_body .= "<td style=\"text-align:center;\">".sed_build_user($row['page_ownerid'], $ownername['user_name'])."</td>";
					$plugin_body .= "</tr>";
					}
				}
			$plugin_body .= "</table>";
			}
		}
  
	if (!$cfg['disable_forums'])
		{
		$frm_sub = sed_import('frm_sub','P','ARR');

		if (!is_array($frm_sub) || $frm_sub[0] == 9999)
			{ $sqlsections = ''; }
		else
			{
			foreach($frm_sub as $i => $k)
				{ $sections1[] = "s.fs_id='".sed_sql_prep($k)."'"; }
			$sqlsections = "AND (".implode(' OR ', $sections1).")";
			}

		$sql = sed_sql_query("SELECT p.fp_id, t.ft_firstposterid, t.ft_firstpostername, t.ft_title, t.ft_id, t.ft_updated, s.fs_id, s.fs_title, s.fs_category
		FROM $db_forum_posts p, $db_forum_topics t, $db_forum_sections s
		WHERE 1 AND (p.fp_text LIKE '".sed_sql_prep($sqlsearch)."' OR t.ft_title LIKE '".sed_sql_prep($sqlsearch)."')
		AND p.fp_topicid=t.ft_id AND p.fp_sectionid=s.fs_id $sqlsections 
		GROUP BY t.ft_id ORDER BY ft_updated DESC
		LIMIT $cfg_maxitems");

		$items = sed_sql_numrows($sql);
		$plugin_body .= "<h4>".$L['Forums'].", ".$L['plu_found']." ".$items." ".$L['plu_match']." :</h4>";
       
		if ($items > 0)
			{
			$plugin_body .= "<table class=\"cells striped\" width=\"100%\">";
			$plugin_body .= "<tr>";
			$plugin_body .= "<td style=\"width:35%;\" class=\"coltop\">".$L['Section']."</td>";
			$plugin_body .= "<td style=\"width:35%;\" class=\"coltop\">".$L['Topic']."</td>";
			$plugin_body .= "<td style=\"width:15%;\" class=\"coltop\">".$L['Date']."</td>";
			$plugin_body .= "<td style=\"width:15%;\" class=\"coltop\">".$L['Poster']."</td>";
			$plugin_body .= "</tr>";

			while ($row = sed_sql_fetchassoc($sql))
				{
				if (sed_auth('forums', $row['fs_id'], 'R'))
					{
					$plugin_body .= "<tr>";
					$plugin_body .= "<td>".sed_build_forums($row['fs_id'], $row['fs_title'], $row['fs_category'], TRUE)."</td>";
					$plugin_body .= "<td><a href=\"".sed_url("forums", "m=posts&p=".$row['fp_id'], "#".$row['fp_id'])."\">".sed_cc($row['ft_title'])."</a></td>";
					$plugin_body .= "<td style=\"text-align:center;\">".@date($cfg['dateformat'], $row['ft_updated'] + $usr['timezone'] * 3600)."</td>";
					$plugin_body .= "<td style=\"text-align:center;\">".sed_build_user($row['ft_firstposterid'],$row['ft_firstpostername'])."</td>";

					$plugin_body .= "</tr>";
					}
				}    
				$plugin_body .= "</table>";
			}    
		}
	}

$plugin_body .= "<h4>&nbsp;</h4>";
$plugin_body .= "<form id=\"search\" action=\"".sed_url("plug", "e=search&a=search")."\" method=\"post\">";
$plugin_body .= "<table class=\"cells striped\">";
$plugin_body .= "<tr><td width=\"20%\">".$L['plu_searchin']." :</td>";
$plugin_body .= "<td width=\"80%\"><input type=\"text\" class=\"text\" name=\"sq\" value=\"".sed_cc($sq)."\" size=\"40\" maxlength=\"64\" /></td></tr>";

if (!$cfg['disable_page'])
	{
	$plugin_body .= "<tr><td>".$L['Pages']."</td>";
	$plugin_body .= "<td><select multiple name=\"pag_sub[]\" size=\"5\">";
	$plugin_body .= "<option value=\"all\" selected=\"selected\">".$L['plu_allcategories']."</option>";

	foreach ($sed_cat as $i =>$x)
		{
		if ($i != 'all' && $i != 'system' && sed_auth('page', $i, 'R'))
			{
			$selected = (count($checked_catarr) > 0 && in_array($i, $checked_catarr)) ? "selected=\"selected\"" : '';
			$plugin_body .= "<option value=\"".$i."\" $selected> ".$x['tpath']."</option>";
			}
		}  
  }

if (!$cfg['disable_forums'])
	{
	$sql1 = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category FROM $db_forum_sections AS s
		LEFT JOIN $db_forum_structure AS n ON n.fn_code=s.fs_category
   	ORDER by fn_path ASC, fs_order ASC");

	$plugin_body .= "<tr><td>".$L['Forums']."</td>";
	$plugin_body .= "<td><select multiple name=\"frm_sub[]\" size=\"5\">";
	$plugin_body .= "<option value=\"9999\" selected=\"selected\">".$L['plu_allsections']."</option>";

	while ($row1 = sed_sql_fetchassoc($sql1))
		{
		if (sed_auth('forums', $row1['fs_id'], 'R'))
			{
			$plugin_body .= "<option value=\"".$row1['fs_id']."\">".sed_build_forums($row1['fs_id'], $row1['fs_title'], $row1['fs_category'], FALSE)."</option>";
			}
		}

	$plugin_body .= "</select></td></tr>";
	}
   
$plugin_body .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"submit btn btn-big\" value=\"".$L['Search']."\" /></td></tr>";
$plugin_body .= "</table></form>";

?>
