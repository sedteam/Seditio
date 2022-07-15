<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/otherpages/otherpages.page.tags.php
Version=179
Updated=2013-jul-08
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=otherpages
Part=main
File=otherpages.page.tags
Hooks=page.tags
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$maxotherpages = $cfg['plugin']['otherpages']['maxpages'];

function sed_get_otherpages($pid, $cat, $limit)
	{
	global $t, $L, $db_pages, $db_users, $sys, $usr, $cfg, $sed_cat, $plu_empty;

	$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";
	
	$sql = sed_sql_query("(SELECT p.page_id, p.page_alias, p.page_cat, p.page_title, p.page_date, p.page_ownerid, p.page_comcount, 
						p.page_thumb, u.user_id, u.user_name, u.user_maingrp, u.user_avatar 
						FROM $db_pages AS p LEFT JOIN $db_users AS u ON u.user_id = p.page_ownerid 
						WHERE p.page_id < ".(int)$pid." AND p.page_state = 0 AND p.page_cat = '".$cat."' 
						ORDER BY p.page_date DESC LIMIT $limit) 
						UNION 
						(SELECT p.page_id, p.page_alias, p.page_cat, p.page_title, p.page_date, p.page_ownerid, p.page_comcount, 
						p.page_thumb, u.user_id, u.user_name, u.user_maingrp, u.user_avatar 
						FROM $db_pages AS p LEFT JOIN $db_users AS u ON u.user_id = p.page_ownerid 
						WHERE p.page_id > ".(int)$pid." AND p.page_state = 0 AND p.page_cat = '".$cat."' 
						ORDER BY p.page_date DESC LIMIT $limit)");

	if (sed_sql_numrows($sql) > 0)
		{		
		while ($row = sed_sql_fetchassoc($sql))
			{
			if (sed_auth('page', $row['page_cat'], 'R'))
				{			
				$sys['catcode'] = $row['page_cat']; //new in v175
				$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=".$row['page_id']) : sed_url("page", "al=".$row['page_alias']);
				$row['page_pageurlcom'] = (empty($row['page_alias'])) ? sed_url("page", "id=".$row['page_id'].$pcomments) : sed_url("page", "al=".$row['page_alias'].$pcomments);
				
				$t-> assign(array(
					"OTHER_PAGES_ROW_URL" => $row['page_pageurl'],
					"OTHER_PAGES_ROW_ID" => $row['page_id'],
					"OTHER_PAGES_ROW_CAT" => $row['page_cat'],
					"OTHER_PAGES_ROW_CATTITLE" => $sed_cat[$row['page_cat']]['title'],
					"OTHER_PAGES_ROW_CATPATH" => sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"),
					"OTHER_PAGES_ROW_SHORTTITLE" => sed_cutstring($row['page_title'], 50),
					"OTHER_PAGES_ROW_TITLE" => $row['page_title'],			
					"OTHER_PAGES_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['page_date'], $cfg['plu_mask_pages_date']),
					"OTHER_PAGES_ROW_AUTHOR" => sed_cc($row['user_name']),
					"OTHER_PAGES_ROW_USERURL" => sed_url("users", "m=details&id=".$row['page_ownerid']),
					"OTHER_PAGES_ROW_USER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name']), $row['user_maingrp']),
					"OTHER_PAGES_ROW_COMMENTS_URL" => $row['page_pageurlcom'],
					"OTHER_PAGES_ROW_COMMENTS_COUNT" => $row['page_comcount'],				
					"OTHER_PAGES_ROW_AVATAR" => sed_build_userimage($row['user_avatar'])
				));
				
				// ------- thumb
				
				if (!empty($row['page_thumb']))
					{	
					$first_thumb_array = rtrim($row['page_thumb']); 
					if ($first_thumb_array[mb_strlen($first_thumb_array) - 1] == ';') 
						{
						$first_thumb_array = mb_substr($first_thumb_array, 0, -1);		
						}		
					$first_thumb_array = explode(";", $first_thumb_array);
					if (count($first_thumb_array) > 0)
						{
						$t->assign("OTHER_PAGES_ROW_THUMB", $first_thumb_array[0]);  
						$t->parse("MAIN.OTHER_PAGES.OTHER_PAGES_ROW.OTHER_PAGES_ROW_THUMB");	
						}
					else 
						{
						$t->assign("OTHER_PAGES_ROW_THUMB", sed_cc($row['page_thumb']));
						}
					}

				// -------		
				
				$t->parse("MAIN.OTHER_PAGES.OTHER_PAGES_ROW");
				}			
			}
			
		$t->parse("MAIN.OTHER_PAGES");	
		}
	}
	
sed_get_otherpages($pag['page_id'], $pag['page_cat'], $maxotherpages);

?>
