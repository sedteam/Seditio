<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/news/news.php
Version=179
Updated=2012-may-23
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=news
Part=homepage
File=news
Hooks=index.tags
Tags=index.tpl:{INDEX_NEWS}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$d = sed_import('d','G','INT');
$c = sed_import('c','G','TXT',16);

// ---------- Extra fields - getting
$extrafields = array(); 
$extrafields = sed_extrafield_get('pages');  
$number_of_extrafields = count($extrafields);

if (empty($d)) { $d = '0'; }
if (empty($c)) { $c = $cfg['plugin']['news']['category']; }

if ($cfg['plugin']['news']['maxpages']>0 && !empty($cfg['plugin']['news']['category']) && !empty($sed_cat[$cfg['plugin']['news']['category']]['order']))
	{
	$jj = 0;
	
	/* --- Modified in v173 --- */

	$mtch = $sed_cat[$cfg['plugin']['news']['category']]['path'].".";
	$mtchlen = mb_strlen($mtch);

	if ($c != $cfg['plugin']['news']['category'])
		{
		$c_mtch = $sed_cat[$c]['path'];
		$c = (mb_substr($c_mtch, 0, $mtchlen) == $mtch && sed_auth('page', $c, 'R')) ? $c : $cfg['plugin']['news']['category'];			
		$mtch = $sed_cat[$c]['path'].".";
		$mtchlen = mb_strlen($mtch);	
		}
		
	$catsub = array();
	$catsub[] = $c;

	foreach($sed_cat as $i => $x)
		{
		if (mb_substr($x['path'], 0, $mtchlen)==$mtch && sed_auth('page', $i, 'R'))
			{ $catsub[] = $i; }
		}
	/* ------- */
	
  /* ======= Pagination Sed 173 ======== */
	
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages 
		WHERE page_state=0 AND page_cat NOT LIKE 'system'
		AND	page_begin<'".$sys['now_offset']."' AND page_expire>'".$sys['now_offset']."' 
		AND page_cat IN ('".implode("','", $catsub)."')");
	
	$totallines = sed_sql_result($sql, 0, "COUNT(*)");
	$totalpages = ceil($totallines / $cfg['plugin']['news']['maxpages']);
	$currentpage= ceil($d / $cfg['plugin']['news']['maxpages'])+1;
  
	$pagination = sed_pagination(sed_url("index", "c=".$c), $d, $totallines, $cfg['plugin']['news']['maxpages']);
	list($pageprev, $pagenext) = sed_pagination_pn(sed_url("index", "c=".$c), $d, $totallines, $cfg['plugin']['news']['maxpages'], TRUE);

	$news = new XTemplate(sed_skinfile('news'));  

	if (!empty($pagination))
		{
	  $news-> assign(array(		
			"NEWS_PAGINATION" => $pagination,
			"NEWS_PAGEPREV" => $pageprev,
			"NEWS_PAGENEXT" => $pagenext
		));
		  
		$news->parse("NEWS.NEWS_PAGINATION_TP");
		$news->parse("NEWS.NEWS_PAGINATION_BM");		
		}	

	$sql = sed_sql_query("SELECT p.*, u.user_name, user_avatar, u.user_maingrp FROM $db_pages AS p
	LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
	WHERE page_state=0 AND page_cat NOT LIKE 'system'
	AND	page_begin<'".$sys['now_offset']."' AND page_expire>'".$sys['now_offset']."' 
	AND page_cat IN ('".implode("','", $catsub)."') ORDER BY page_".$sed_cat[$cfg['plugin']['news']['category']]['order']." ".$sed_cat[$cfg['plugin']['news']['category']]['way']." LIMIT $d,".$cfg['plugin']['news']['maxpages']);

	/* === Hook - Part1 : Set === */
	$extpf = sed_getextplugins('news.loopfirst');
	$extp = sed_getextplugins('news.loop');
	/* ===== */

	while ($pag = sed_sql_fetchassoc($sql))
		{	
		
		/* === Hook - Part2 : Include === */
		if (is_array($extpf))
			{ foreach($extpf as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */
					
		$jj++;
		
		$sys['catcode'] = $pag['page_cat']; //new in v175
    
		$catpath = sed_build_catpath($pag['page_cat'], "<a href=\"%1\$s\">%2\$s</a>");
		$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']) : sed_url("page", "al=".$pag['page_alias']);
		$pag['page_fulltitle'] = $catpath." ".$cfg['separator']." <a href=\"".$pag['page_pageurl']."\">".$pag['page_title']."</a>";;

		$pag['page_comcount'] = (!$pag['page_comcount']) ? "0" : $pag['page_comcount'];
    
		$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";

		$pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id'].$pcomments) : sed_url("page", "al=".$pag['page_alias'].$pcomments);
		$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']."&ratings=1") : sed_url("page", "al=".$pag['page_alias']."&ratings=1");

		$pag['page_comments'] = "<a href=\"".$pag['page_pageurlcom']."\">".$out['ic_comment']." (".$pag['page_comcount'].")</a>";
		
		$news-> assign(array(
			"PAGE_ROW_URL" => $pag['page_pageurl'],
			"PAGE_ROW_ID" => $pag['page_id'],
			"PAGE_ROW_TITLE" => $pag['page_fulltitle'],
			"PAGE_ROW_SHORTTITLE" => $pag['page_title'],
			"PAGE_ROW_CAT" => $pag['page_cat'],
			"PAGE_ROW_CATTITLE" => $sed_cat[$pag['page_cat']]['title'],
			"PAGE_ROW_CATPATH" => $catpath,
			"PAGE_ROW_CATURL" => sed_url("list", "c=".$pag['page_cat']),
			"PAGE_ROW_CATDESC" => $sed_cat[$pag['page_cat']]['desc'],
			"PAGE_ROW_CATICON" => $sed_cat[$pag['page_cat']]['icon'],
			"PAGE_ROW_KEY" => sed_cc($pag['page_key']),
			"PAGE_ROW_DESC" => sed_cc($pag['page_desc']),
			"PAGE_ROW_AUTHOR" => (!empty($pag['page_author'])) ? sed_cc($pag['page_author']) : sed_cc($pag['user_name']),
			"PAGE_ROW_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
			"PAGE_ROW_AVATAR" => sed_build_userimage($pag['user_avatar']),
			"PAGE_ROW_USERURL" => sed_url("users", "m=details&id=".$pag['page_ownerid']),
			"PAGE_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $pag['page_date']),
			"PAGE_ROW_FILEURL" => $pag['page_url'],
			"PAGE_ROW_SIZE" => $pag['page_size'],
			"PAGE_ROW_COUNT" => $pag['page_count'],
			"PAGE_ROW_FILECOUNT" => $pag['page_filecount'],
			"PAGE_ROW_COMMENTS" => $pag['page_comments'],
			"PAGE_ROW_COMMENTS_URL" => $pag['page_pageurlcom'],
			"PAGE_ROW_COMMENTS_COUNT" => $pag['page_comcount'],				
			"PAGE_ROW_RATINGS" => "<a href=\"".$pag['page_pageurlrat']."\"><img src=\"skins/".$usr['skin']."/img/system/vote".round($pag['page_rating'],0).".gif\" alt=\"\" /></a>",
			"PAGE_ROW_ODDEVEN" => sed_build_oddeven($jj)
		));
				
		if (!empty($pag['page_thumb']))
			{	
			$first_thumb_array = rtrim($pag['page_thumb']); 
			if ($first_thumb_array[mb_strlen($first_thumb_array) - 1] == ';') 
				{
				$first_thumb_array = mb_substr($first_thumb_array, 0, -1);		
				}		
			$first_thumb_array = explode(";", $first_thumb_array);
			if (count($first_thumb_array) > 0)
				{
				$news->assign("PAGE_ROW_THUMB", $first_thumb_array[0]);  
				$news->parse("NEWS.PAGE_ROW.PAGE_ROW_THUMB");	
				}		
			}
		else 
			{
			$news->assign("PAGE_ROW_THUMB", "noimg.jpg");
			}			
				
		// ---------- Extra fields - getting
		if(count($extrafields) > 0) 
			{ 
			$extra_array = sed_build_extrafields_data('page', 'PAGE_ROW', $extrafields, $pag);
			} 
		
		$news->assign($extra_array); 
		// ----------------------									
								
		$pag['page_text'] = sed_parse($pag['page_text']);										
		$pag['page_text'] = sed_cutreadmore($pag['page_text'], $pag['page_pageurl']);

		$news->assign("PAGE_ROW_TEXT", $pag['page_text']);
		
		/* === Hook - Part2 : Include === */
		if (is_array($extp))
			{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */		
			
		$news->parse("NEWS.PAGE_ROW");	
	}	
		    
	$news->parse("NEWS");
	$t->assign("INDEX_NEWS", $news->text("NEWS"));
	}

?>