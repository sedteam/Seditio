<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/news/news.php
Version=175
Updated=2012-may-23
Type=Plugin
Author=Neocrome
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
			{ foreach($extpf as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */
					
		$jj++;
		
    $sys['catcode'] = $pag['page_cat']; //new in v175
    
    $catpath = sed_build_catpath($pag['page_cat'], "<a href=\"%1\$s\">%2\$s</a>");
		$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']) : sed_url("page", "al=".$pag['page_alias']);
		$pag['page_fulltitle'] = $catpath." ".$cfg['separator']." <a href=\"".$pag['page_pageurl']."\">".$pag['page_title']."</a>";

		//$item_code = 'p'.$pag['page_id'];
		//list($pag['page_comments'], $pag['page_comments_display']) = sed_build_comments($item_code, $pag['page_pageurl'], FALSE);

		$pag['page_comcount'] = (!$pag['page_comcount']) ? "0" : $pag['page_comcount'];
    
    $pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";
    
    $pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id'].$pcomments) : sed_url("page", "al=".$pag['page_alias'].$pcomments);
    $pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']."&ratings=1") : sed_url("page", "al=".$pag['page_alias']."&ratings=1");
    
		$pag['page_comments'] = "<a href=\"".$pag['page_pageurlcom']."\"><img src=\"skins/".$usr['skin']."/img/system/icon-comment.gif\" alt=\"\" /> (".$pag['page_comcount'].")</a>";
    $pag['page_comments_url'] = "<a href=\"".$pag['page_pageurlcom']."\">(".$pag['page_comcount'].")</a>";
		
		$news-> assign(array(
			"PAGE_ROW_URL" => $pag['page_pageurl'],
			"PAGE_ROW_ID" => $pag['page_id'],
			"PAGE_ROW_TITLE" => $pag['page_fulltitle'],
			"PAGE_ROW_SHORTTITLE" => $pag['page_title'],
			"PAGE_ROW_CAT" => $pag['page_cat'],
			"PAGE_ROW_CATTITLE" => $sed_cat[$pag['page_cat']]['title'],
			"PAGE_ROW_CATPATH" => $catpath,
			"PAGE_ROW_CATDESC" => $sed_cat[$pag['page_cat']]['desc'],
			"PAGE_ROW_CATICON" => $sed_cat[$pag['page_cat']]['icon'],
			"PAGE_ROW_KEY" => sed_cc($pag['page_key']),
			"PAGE_ROW_EXTRA1" => sed_cc($pag['page_extra1']),
			"PAGE_ROW_EXTRA2" => sed_cc($pag['page_extra2']),
			"PAGE_ROW_EXTRA3" => sed_cc($pag['page_extra3']),
			"PAGE_ROW_EXTRA4" => sed_cc($pag['page_extra4']),
			"PAGE_ROW_EXTRA5" => sed_cc($pag['page_extra5']),
			"PAGE_ROW_DESC" => sed_cc($pag['page_desc']),
			"PAGE_ROW_AUTHOR" => sed_cc($pag['page_author']),
			"PAGE_ROW_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
			"PAGE_ROW_AVATAR" => sed_build_userimage($pag['user_avatar']),
			"PAGE_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $pag['page_date']),
			"PAGE_ROW_FILEURL" => $pag['page_url'],
			"PAGE_ROW_SIZE" => $pag['page_size'],
			"PAGE_ROW_COUNT" => $pag['page_count'],
			"PAGE_ROW_FILECOUNT" => $pag['page_filecount'],
			"PAGE_ROW_COMMENTS" => $pag['page_comments'],
      "PAGE_ROW_COMMENTS_URL" => $pag['page_comments_url'],
			"PAGE_ROW_RATINGS" => "<a href=\"".$pag['page_pageurlrat']."\"><img src=\"skins/".$usr['skin']."/img/system/vote".round($pag['rating_average'],0).".gif\" alt=\"\" /></a>",
			"PAGE_ROW_ODDEVEN" => sed_build_oddeven($jj)
				));
				
		$ishtml_page = ($pag['page_type']==1 || $pag['page_text_ishtml']);				
		$pag['page_text'] = sed_parse($pag['page_text'], $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1, $ishtml_page);
		if (!$pag['page_text_ishtml'] && $cfg['textmode']=='html')
			{
			 $sql2 = sed_sql_query("UPDATE $db_pages SET page_text_ishtml=1, page_type=1, page_text='".sed_sql_prep($pag['page_text'])."', 
									page_text2='".sed_sql_prep($pag['page_text2'])."' WHERE page_id=".$pag['page_id']);
			}										
		$pag['page_text'] = sed_cutreadmore($pag['page_text'], $pag['page_pageurl']);

		$news->assign("PAGE_ROW_TEXT", $pag['page_text']);
		
		/* === Hook - Part2 : Include === */
		if (is_array($extp))
			{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */		
			
		$news->parse("NEWS.PAGE_ROW");	
	}	
		    
	$news->parse("NEWS");
	$t->assign("INDEX_NEWS", $news->text("NEWS"));
	}

?>