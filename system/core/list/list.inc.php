<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=system/core/list/list.inc.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','INT');
$s = sed_import('s','G','ALP',13);  //v173
$d = sed_import('d','G','INT');
$c = sed_import('c','G','TXT');
$w = sed_import('w','G','ALP',4);
$o = sed_import('o','G','ALP',16);
$p = sed_import('p','G','ALP',16);

if ($c=='all' || $c=='system')
	{
	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
	sed_block($usr['isadmin']);
	}
else
	{
	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $c);
	sed_block($usr['auth_read']);
	}

/* === Hook === */
$extp = sed_getextplugins('list.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if (empty($s))
	{
	$s = $sed_cat[$c]['order'];
	$w = $sed_cat[$c]['way'];
	}

if (empty($s)) { $s = 'title'; }
if (empty($w)) { $w = 'asc'; }
if (empty($d)) { $d = '0'; }

$cfg['maxrowsperpage'] = ($c=='all' || $c=='system') ? $cfg['maxrowsperpage']*2 : $cfg['maxrowsperpage'];

$item_code = 'list_'.$c;
$join_ratings_columns = ($cfg['disable_ratings']) ? '' : ", r.rating_average";
$join_ratings_condition = ($cfg['disable_ratings']) ? '' : "LEFT JOIN $db_ratings as r ON r.rating_code=CONCAT('p',p.page_id)";

if ($c=='all')
	{
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='0'");
	$totallines = sed_sql_result($sql, 0, "COUNT(*)");
	
	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp ".$join_ratings_columns."
		FROM $db_pages as p ".$join_ratings_condition."
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_state='0'
		ORDER BY page_$s $w LIMIT $d,".$cfg['maxrowsperpage']);
	}
elseif (!empty($o) && !empty($p) && $p!='password')
	{
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat='$c' AND (page_state='0' OR page_state='2') AND page_$o='$p'");
	$totallines = sed_sql_result($sql, 0, "COUNT(*)");
	
	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp ".$join_ratings_columns."
		FROM $db_pages as p ".$join_ratings_condition."
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_cat='$c' AND (page_state='0' OR page_state='2') AND page_$o='$p'
		ORDER BY page_$s $w LIMIT $d,".$cfg['maxrowsperpage']);
	}
else
	{
	sed_die(empty($sed_cat[$c]['title']));
	if (($sed_cat[$c]['group']) && ($cfg['showpagesubcatgroup'] == 1)) 
		{
		$mtch = $sed_cat[$c]['path'].".";
		$mtchlen = mb_strlen($mtch);
		$catsub = array();
		$catsub[] = $c;
		foreach($sed_cat as $i => $x)
		  {
		  if (mb_substr($x['path'], 0, $mtchlen)==$mtch && sed_auth('page', $i, 'R'))
			{ $catsub[] = $i; }
		  }
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat IN ('".implode("','", $catsub)."') AND (page_state='0' OR page_state='2') ");
		$totallines = sed_sql_result($sql, 0, "COUNT(*)");
		
		$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp ".$join_ratings_columns."
		  FROM $db_pages as p ".$join_ratings_condition."
		  LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		  WHERE page_cat IN ('".implode("','", $catsub)."') AND (page_state='0' OR page_state='2')
		  ORDER BY page_$s $w LIMIT $d,".$cfg['maxrowsperpage']);
		}
	else 
		{
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat='$c' AND (page_state='0' OR page_state='2') ");
		$totallines = sed_sql_result($sql, 0, "COUNT(*)");
		
		$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp ".$join_ratings_columns."
		  FROM $db_pages as p ".$join_ratings_condition."
		  LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		  WHERE page_cat='$c' AND (page_state='0' OR page_state='2')
		  ORDER BY page_$s $w LIMIT $d,".$cfg['maxrowsperpage']);
		}
	}

	
if ($c != 'all')
	{
	$sql2 = sed_sql_query("SELECT structure_text, structure_code, structure_text_ishtml FROM $db_structure WHERE structure_code = '$c' LIMIT 1");
	$row2 = sed_sql_fetchassoc($sql2);
  			
	$list_text = sed_parse($row2['structure_text'], $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1, $row2['structure_text_ishtml']);

	if (!$row2['structure_text_ishtml'] && $cfg['textmode']=='html')
		{
		 $sql2 = sed_sql_query("UPDATE $db_structure SET structure_text_ishtml = 1, structure_text = '".sed_sql_prep($list_text)."' WHERE structure_code = '".$row2['structure_code']."'");
		}	
	}	
	
$incl="datas/content/list.$c.txt";

if (@file_exists($incl))
	{
	$fd = @fopen ($incl, "r");
	$extratext = fread ($fd, filesize ($incl));
	fclose ($fd);
	}

if ($c=='all' || $c=='system')
	{ $catpath = $sed_cat[$c]['title']; }
else
	{ $catpath = sed_build_catpath($c, "<a href=\"%1\$s\">%2\$s</a>"); }

$totalpages = ceil($totallines / $cfg['maxrowsperpage']);
$currentpage= ceil ($d / $cfg['maxrowsperpage'])+1;
$submitnewpage = ($usr['auth_write'] && $c!='all') ? "<a href=\"".sed_url("page", "m=add&c=".$c)."\">".$L['lis_submitnew']."</a>" : '';

$pagination = sed_pagination(sed_url("list", "c=".$c."&s=".$s."&w=".$w."&o=".$o."&p=".$p), $d, $totallines, $cfg['maxrowsperpage']);
list($pageprev, $pagenext) = sed_pagination_pn(sed_url("list", "c=".$c."&s=".$s."&w=".$w."&o=".$o."&p=".$p), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

//fix for sed_url()
$url_list = array('part' => 'list', 'params' => "c=".$c);

list($list_comments, $list_comments_display) = sed_build_comments($item_code, $url_list, $comments);
list($list_ratings, $list_ratings_display) = sed_build_ratings($item_code, $url_list, $ratings);

$sys['sublocation'] = $sed_cat[$c]['title'];
$out['subtitle'] = $sed_cat[$c]['title'];
$out['subdesc'] = $sed_cat[$c]['desc'];


/**/
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('listtitle', $title_tags, $title_data);
/**/


/* === Hook === */
$extp = sed_getextplugins('list.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

require("system/header.php");

if ($sed_cat[$c]['group'])
	{ $mskin = sed_skinfile(array('list', 'group', $sed_cat[$c]['tpl'])); }
else
	{ $mskin = sed_skinfile(array('list', $sed_cat[$c]['tpl'])); }

$t = new XTemplate($mskin);

if (!empty($pagination))
	{
	$t->assign(array(
		"LIST_TOP_PAGINATION" => $pagination,
		"LIST_TOP_PAGEPREV" => $pageprev,
		"LIST_TOP_PAGENEXT" => $pagenext
		));	
	$t->parse("MAIN.LIST_PAGINATION_TP");
	$t->parse("MAIN.LIST_PAGINATION_BM");		
	}

$t->assign(array(
	"LIST_PAGETITLE" => $catpath,
	"LIST_CATEGORY" => "<a href=\"".sed_url("list", "c=".$c)."\">".$sed_cat[$c]['title']."</a>",
	"LIST_CAT" => $c,
	"LIST_CATTITLE" => $sed_cat[$c]['title'],
	"LIST_CATPATH" => $catpath,
	"LIST_CATDESC" => $sed_cat[$c]['desc'],
	"LIST_CATTEXT" => $list_text,
	"LIST_CATICON" => $sed_cat[$c]['icon'],
	"LIST_COMMENTS" => $list_comments,
	"LIST_COMMENTS_DISPLAY" => $list_comments_display,
	"LIST_RATINGS" => $list_ratings,
	"LIST_RATINGS_DISPLAY" => $list_ratings_display,
  "LIST_RSS" => sed_url("rss", "m=pages&c=".$c),
	"LIST_EXTRATEXT" => $extratext,
	"LIST_SUBMITNEWPAGE" => $submitnewpage
	));

if (!$sed_cat[$c]['group'])
	{
	$t->assign(array(
	"LIST_TOP_CURRENTPAGE" => $currentpage,
	"LIST_TOP_TOTALLINES" => $totallines,
	"LIST_TOP_MAXPERPAGE" => $cfg['maxrowsperpage'],
	"LIST_TOP_TOTALPAGES" => $totalpages,
	"LIST_TOP_TITLE" => "<a href=\"".sed_url("list", "c=".$c."&s=title&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
  <a href=\"".sed_url("list", "c=".$c."&s=title&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Title'],
	"LIST_TOP_KEY" => "<a href=\"".sed_url("list", "c=".$c."&s=key&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=key&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Key'],
	"LIST_TOP_EXTRA1" => "<a href=\"".sed_url("list", "c=".$c."&s=extra1&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=extra1&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a>",
	"LIST_TOP_EXTRA2" => "<a href=\"".sed_url("list", "c=".$c."&s=extra2&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=extra2&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a>",
	"LIST_TOP_EXTRA3" => "<a href=\"".sed_url("list", "c=".$c."&s=extra3&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=extra3&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a>",
	"LIST_TOP_EXTRA4" => "<a href=\"".sed_url("list", "c=".$c."&s=extra4&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=extra4&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a>",
	"LIST_TOP_EXTRA5" => "<a href=\"".sed_url("list", "c=".$c."&s=extra5&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=extra5&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a>",
	"LIST_TOP_DATE" => "<a href=\"".sed_url("list", "c=".$c."&s=date&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
  <a href=\"".sed_url("list", "c=".$c."&s=date&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Date'],
	"LIST_TOP_AUTHOR" => "<a href=\"".sed_url("list", "c=".$c."&s=author&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=author&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Author'],
	"LIST_TOP_OWNER" => "<a href=\"".sed_url("list", "c=".$c."&s=ownerid&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=ownerid&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Owner'],
	"LIST_TOP_COUNT" => "<a href=\"".sed_url("list", "c=".$c."&s=count&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=count&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Hits'],
	"LIST_TOP_FILECOUNT" => "<a href=\"".sed_url("list", "c=".$c."&s=filecount&w=asc&o=".$o."&p=".$p)."\">$sed_img_down</a>
	<a href=\"".sed_url("list", "c=".$c."&s=filecount&w=desc&o=".$o."&p=".$p)."\">$sed_img_up</a> ".$L['Hits']
		));
	}

$ii=0;
$jj=1;
$mtch = $sed_cat[$c]['path'].".";
$mtchlen = mb_strlen($mtch);
$mtchlvl = mb_substr_count($mtch,".");

while (list($i,$x) = each($sed_cat) )
		{
		if (mb_substr($x['path'],0,$mtchlen)==$mtch && mb_substr_count($x['path'],".")==$mtchlvl)
			{
			$sql4 = sed_sql_query("SELECT COUNT(*) FROM $db_pages p, $db_structure s
				WHERE p.page_cat=s.structure_code
				AND s.structure_path LIKE '".$sed_cat[$i]['rpath']."%'
				AND page_state=0 ");
			
			$sub_count = sed_sql_result($sql4,0,"COUNT(*)");

			$t-> assign(array(
				"LIST_ROWCAT_URL" => sed_url("list", "c=".$i),
				"LIST_ROWCAT_TITLE" => $x['title'],
				"LIST_ROWCAT_DESC" => $x['desc'],
				"LIST_ROWCAT_ICON" => $x['icon'],
				"LIST_ROWCAT_COUNT" => $sub_count,
				"LIST_ROWCAT_ODDEVEN" => sed_build_oddeven($ii)
					));
			$t->parse("MAIN.LIST_ROWCAT");
			$ii++;
			}
		}

/* === Hook - Part1 : Set === */
$extpf = sed_getextplugins('list.loopfirst');
$extp = sed_getextplugins('list.loop');
/* ===== */

while ($pag = sed_sql_fetchassoc($sql) and ($jj<=$cfg['maxrowsperpage']))
	{
	/* === Hook - Part2 : Include === */
	if (is_array($extpf))
		{ foreach($extpf as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$jj++;
	
  $sys['catcode'] = $pag['page_cat'];
  
  $pag['page_desc'] = sed_cc($pag['page_desc']);
	$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']) : sed_url("page", "al=".$pag['page_alias']);

	if (!empty($pag['page_url']) && $pag['page_file'])
		{
		$dotpos = mb_strrpos($pag['page_url'],".")+1;
		$pag['page_fileicon'] = (mb_strlen($pag['page_url'])-$dotpos>4) ? "system/img/admin/page.png" : "system/img/pfs/".mb_strtolower(mb_substr($pag['page_url'], $dotpos, 5)).".gif";
		$pag['page_fileicon'] = "<img src=\"".$pag['page_fileicon']."\" alt=\"\" />";
		}
	else
		{ $pag['page_fileicon'] = ''; }

  $pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";
  
  $pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id'].$pcomments) : sed_url("page", "al=".$pag['page_alias'].$pcomments);
  $pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']."&ratings=1") : sed_url("page", "al=".$pag['page_alias']."&ratings=1");

	$item_code = 'p'.$pag['page_id'];
	$pag['page_comcount'] = (!$pag['page_comcount']) ? "0" : $pag['page_comcount'];
	$pag['page_comments'] = "<a href=\"".$pag['page_pageurlcom']."\"><img src=\"skins/".$usr['skin']."/img/system/icon-comment.gif\" alt=\"\" /> (".$pag['page_comcount'].")</a>";
  
	$pag['admin'] = $usr['isadmin'] ? "<a href=\"".sed_url("admin", "m=page&a=unvalidate&id=".$pag['page_id']."&".sed_xg())."\">".$L['Putinvalidationqueue']."</a> &nbsp;<a href=\"".sed_url("page", "m=edit&id=".$pag['page_id']."&r=list")."\">".$L['Edit']."</a> &nbsp;<a href=\"".sed_url("page", "m=add&id=".$pag['page_id']."&r=list&a=clone")."\">".$L['Clone']."</a> " : '';

	$t-> assign(array(
		"LIST_ROW_URL" => $pag['page_pageurl'],
		"LIST_ROW_ID" => $pag['page_id'],
		"LIST_ROW_CAT" => $pag['page_cat'],
		"LIST_ROW_KEY" => sed_cc($pag['page_key']),
		"LIST_ROW_EXTRA1" => sed_cc($pag['page_extra1']),
		"LIST_ROW_EXTRA2" => sed_cc($pag['page_extra2']),
		"LIST_ROW_EXTRA3" => sed_cc($pag['page_extra3']),
		"LIST_ROW_EXTRA4" => sed_cc($pag['page_extra4']),
		"LIST_ROW_EXTRA5" => sed_cc($pag['page_extra5']),
		"LIST_ROW_TITLE" => sed_cc($pag['page_title']),
		"LIST_ROW_DESC" => $pag['page_desc'],
		"LIST_ROW_AUTHOR" => sed_cc($pag['page_author']),
		"LIST_ROW_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
		"LIST_ROW_DATE" => @date($cfg['formatyearmonthday'], $pag['page_date'] + $usr['timezone'] * 3600),
		"LIST_ROW_FILEURL" => $pag['page_url'],
		"LIST_ROW_SIZE" => $pag['page_size'],
		"LIST_ROW_COUNT" => $pag['page_count'],
		"LIST_ROW_FILEICON" => $pag['page_fileicon'],
		"LIST_ROW_FILECOUNT" => $pag['page_filecount'],
		"LIST_ROW_JUMP" => $pag['page_pageurl']."&a=dl",
		"LIST_ROW_COMMENTS" => $pag['page_comments'],
		"LIST_ROW_RATINGS" => "<a href=\"".$pag['page_pageurlrat']."\"><img src=\"skins/".$usr['skin']."/img/system/vote".round($pag['rating_average'],0).".gif\" alt=\"\" /></a>",
		"LIST_ROW_ADMIN" => $pag['admin'],
		"LIST_ROW_ODDEVEN" => sed_build_oddeven($jj)
			));

	/* === Hook - Part2 : Include === */
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$t->parse("MAIN.LIST_ROW");
	}


/* === Hook === */
$extp = sed_getextplugins('list.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require("system/footer.php");

?>