<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/core/list/list.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$available_sort = array('id', 'type', 'key', 'title', 'desc', 'text', 'author', 'owner', 'date', 'begin', 'expire', 'count', 'file', 'url', 'size', 'filecount');
$available_way = array('asc', 'desc');

$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'ALP', 13);  //v173
$d = sed_import('d', 'G', 'INT');
$c = sed_import('c', 'G', 'TXT');
$w = sed_import('w', 'G', 'ALP', 4);
$o = sed_import('o', 'G', 'ALP', 16);
$p = sed_import('p', 'G', 'ALP', 16);

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('pages');
$number_of_extrafields = count($extrafields);

$filter_vars = array();
$filter_sql = array();
$filter_urlspar = array();
$filter_urlparams_arr = array();
$filter_urlparams = "";

$sql_where = "";

if (count($extrafields) > 0) {
	foreach ($extrafields as $key => $val) {
		array_push($available_sort, $key);
		if (in_array($val['vartype'], array('INT', 'BOL', 'TXT'))) {
			$filter_vars['filter_' . $key] = sed_import('filter_' . $key, 'G', $val['vartype']);
			if (!empty($filter_vars['filter_' . $key])) {
				$filter_sql[] = "page_" . $key . " = '" . sed_sql_prep($filter_vars['filter_' . $key]) . "'";
				$filter_urlspar['filter_' . $key] = $filter_vars['filter_' . $key];
				$filter_urlparams_arr[] = $key . " = '" . $filter_vars['filter_' . $key] . "'";
			}
		}
	}
}

$filter_urlparams = (count($filter_urlparams_arr) > 0) ? "&" . implode('&', $filter_urlparams_arr) : "";
$sql_where = (count($filter_sql) > 0) ? " AND " . implode(' AND ', $filter_sql) : " ";

if (!array_key_exists($c, $sed_cat) && !($c == 'all')) {
	sed_die(true, 404);
}

if ($c == 'all' || $c == 'system') {
	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
	sed_block($usr['isadmin']);
} else {
	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $c);
	sed_block($usr['auth_read']);
}

/* === Hook === */
$extp = sed_getextplugins('list.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if (empty($s) || !in_array($s, $available_sort) || !in_array($w, $available_way)) {
	$s = $sed_cat[$c]['order'];
	$w = $sed_cat[$c]['way'];
}

if (empty($s)) {
	$s = 'title';
}
if (empty($w)) {
	$w = 'asc';
}
if (empty($d)) {
	$d = '0';
}

$pn_s = ($s == $sed_cat[$c]['order']) ? "" : $s;
$pn_w = ($w == $sed_cat[$c]['way'] && $s == $sed_cat[$c]['order']) ? "" : $w;

$cfg['maxrowsperpage'] = ($c == 'all' || $c == 'system') ? $cfg['maxrowsperpage'] * 2 : $cfg['maxrowsperpage'];

$item_code = 'list_' . $c;
$join_ratings_columns = ($cfg['disable_ratings']) ? '' : ", r.rating_average";
$join_ratings_condition = ($cfg['disable_ratings']) ? '' : "LEFT JOIN $db_ratings as r ON r.rating_code=CONCAT('p',p.page_id)";

if ($c == 'all') {
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='0' $sql_where");
	$totallines = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp, u.user_avatar " . $join_ratings_columns . "
		FROM $db_pages as p " . $join_ratings_condition . "
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_state='0'
		$sql_where ORDER BY page_$s $w LIMIT $d," . $cfg['maxrowsperpage']);
} elseif (!empty($o) && !empty($p) && $p != 'password') {
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat='$c' AND (page_state='0' OR page_state='2') AND page_$o='$p' $sql_where");
	$totallines = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp, u.user_avatar " . $join_ratings_columns . "
		FROM $db_pages as p " . $join_ratings_condition . "
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_cat='$c' AND (page_state='0' OR page_state='2') AND page_$o='$p'
		$sql_where ORDER BY page_$s $w LIMIT $d," . $cfg['maxrowsperpage']);
} else {
	sed_die(empty($sed_cat[$c]['title']), 950);
	if (($sed_cat[$c]['group']) && ($cfg['showpagesubcatgroup'] == 1)) {
		$mtch = $sed_cat[$c]['path'] . ".";
		$mtchlen = mb_strlen($mtch);
		$catsub = array();
		$catsub[] = $c;
		foreach ($sed_cat as $i => $x) {
			if (mb_substr($x['path'], 0, $mtchlen) == $mtch && sed_auth('page', $i, 'R')) {
				$catsub[] = $i;
			}
		}
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat IN ('" . implode("','", $catsub) . "') AND (page_state='0' OR page_state='2') $sql_where");
		$totallines = sed_sql_result($sql, 0, "COUNT(*)");

		$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp, u.user_avatar " . $join_ratings_columns . "
		  FROM $db_pages as p " . $join_ratings_condition . "
		  LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		  WHERE page_cat IN ('" . implode("','", $catsub) . "') AND (page_state='0' OR page_state='2')
		  $sql_where ORDER BY page_$s $w LIMIT $d," . $cfg['maxrowsperpage']);
	} else {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_cat='$c' AND (page_state='0' OR page_state='2') $sql_where");
		$totallines = sed_sql_result($sql, 0, "COUNT(*)");

		$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_maingrp, u.user_avatar " . $join_ratings_columns . "
		  FROM $db_pages as p " . $join_ratings_condition . "
		  LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		  WHERE page_cat='$c' AND (page_state='0' OR page_state='2')
		  $sql_where ORDER BY page_$s $w LIMIT $d," . $cfg['maxrowsperpage']);
	}
}


if ($c != 'all') {
	$sql2 = sed_sql_query("SELECT structure_title, structure_desc, structure_text, structure_code FROM $db_structure WHERE structure_code = '$c' LIMIT 1");
	$row2 = sed_sql_fetchassoc($sql2);

	/* === Hook === */
	$extp = sed_getextplugins('list.fetch');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$list_text = sed_parse($row2['structure_text']);
}

$incl = "datas/content/list.$c.txt";
$extratext = '';

if (@file_exists($incl)) {
	$fd = @fopen($incl, "r");
	$extratext = fread($fd, filesize($incl));
	fclose($fd);
}

if ($c == 'all' || $c == 'system') {
	$catpath = $sed_cat[$c]['title'];
} else {
	$catpath = sed_build_catpath($c, "<a href=\"%1\$s\">%2\$s</a>");
}

if (count($filter_urlspar) > 0) {
	foreach ($filter_urlspar as $fkey => $fval) {
		$filter_urlspar_arr[] = $fkey . "=" . $fval;
	}
	$filter_urlparams = "&" . implode('&', $filter_urlspar_arr);
}

$totalpages = ceil($totallines / $cfg['maxrowsperpage']);
$currentpage = ceil($d / $cfg['maxrowsperpage']) + 1;

$pagination = sed_pagination(sed_url("list", "c=" . $c . "&s=" . $pn_s . "&w=" . $pn_w . "&o=" . $o . "&p=" . $p . $filter_urlparams), $d, $totallines, $cfg['maxrowsperpage']);
list($pageprev, $pagenext) = sed_pagination_pn(sed_url("list", "c=" . $c . "&s=" . $pn_s . "&w=" . $pn_w . "&o=" . $o . "&p=" . $p . $filter_urlparams), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

//fix for sed_url()
$url_list = array('part' => 'list', 'params' => "c=" . $c);

// Options for category
$allowcommentscat = $sed_cat[$c]['allowcomments'];
$allowratingscat = $sed_cat[$c]['allowratings'];

list($list_comments, $list_comments_display) = sed_build_comments($item_code, $url_list, $allowcommentscat);
list($list_ratings, $list_ratings_display) = sed_build_ratings($item_code, $url_list, $allowratingscat);

$sys['sublocation'] = $sed_cat[$c]['title'];
$out['subtitle'] = $sed_cat[$c]['title'];
$out['subdesc'] = $sed_cat[$c]['desc'];

$out['robots_index'] = $sed_cat[$c]['seo_index'];
$out['robots_follow'] = $sed_cat[$c]['seo_follow'];

/* ===== */
$sys['catcode'] = $c;
$out['canonical_url'] = ($cfg['absurls']) ? sed_url("list", "c=" . $c . "&d=" . $d) : $sys['abs_url'] . sed_url("list", "c=" . $c . "&d=" . $d);
/* ===== */

/**/
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('listtitle', $title_tags, $title_data);
/**/

// ---------- Breadcrumbs
$urlpaths = array();
sed_build_list_bc($c);

// ---------- List thumb
$list_thumbs_array = array();
if (!empty($sed_cat[$c]['thumb'])) {
	$list_thumbs_array = rtrim($sed_cat[$c]['thumb']);
	if ($list_thumbs_array[mb_strlen($list_thumbs_array) - 1] == ';') {
		$list_thumbs_array = mb_substr($list_thumbs_array, 0, -1);
	}
	$list_thumbs_array = explode(";", $list_thumbs_array);
	if (count($list_thumbs_array) > 0) {
		$out['image'] = $list_thumbs_array[0];
	}
}

/* === Hook === */
$extp = sed_getextplugins('list.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

if ($sed_cat[$c]['group']) {
	$mskin = sed_skinfile(array('list', 'group', $sed_cat[$c]['tpl']));
} else {
	$mskin = sed_skinfile(array('list', $sed_cat[$c]['tpl']));
}

$t = new XTemplate($mskin);

if (!empty($pagination)) {
	$t->assign(array(
		"LIST_TOP_PAGINATION" => $pagination,
		"LIST_TOP_PAGEPREV" => $pageprev,
		"LIST_TOP_PAGENEXT" => $pagenext
	));
	$t->parse("MAIN.LIST_PAGINATION_TP");
	$t->parse("MAIN.LIST_PAGINATION_BM");
}

if ($usr['auth_write'] && $c != 'all') {
	$t->assign(array(
		"LIST_SUBMITNEWPAGE" => sed_link(sed_url("page", "m=add&c=" . $c), $L['lis_submitnew'])
	));
	$t->parse("MAIN.LIST_AUTHUSER");
}

$t->assign(array(
	"LIST_ID" => $sed_cat[$c]['id'],
	"LIST_PAGETITLE" => $catpath,
	"LIST_SHORTTITLE" => $sed_cat[$c]['title'],
	"LIST_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"LIST_CATEGORY" => sed_link(sed_url("list", "c=" . $c), $sed_cat[$c]['title']),
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
	"LIST_RSS" => sed_url("rss", "m=pages&c=" . $c),
	"LIST_EXTRATEXT" => $extratext
));

if (!empty($list_text)) {
	$t->parse("MAIN.LIST_CATTEXT");
}

if (count($list_thumbs_array) > 0) {
	$t->assign("LIST_THUMB", $list_thumbs_array[0]);
	$t->parse("MAIN.LIST_THUMB");
} else {
	$t->assign("LIST_THUMB", sed_cc($sed_cat[$c]['thumb']));
}

if (!$sed_cat[$c]['group']) {
	$t->assign(array(
		"LIST_TOP_CURRENTPAGE" => $currentpage,
		"LIST_TOP_TOTALLINES" => $totallines,
		"LIST_TOP_MAXPERPAGE" => $cfg['maxrowsperpage'],
		"LIST_TOP_TOTALPAGES" => $totalpages,
		"LIST_TOP_TITLE" => sed_link(sed_url("list", "c=" . $c . "&s=title&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=title&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Title'],
		"LIST_TOP_KEY" => sed_link(sed_url("list", "c=" . $c . "&s=key&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=key&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Key'],
		"LIST_TOP_DATE" => sed_link(sed_url("list", "c=" . $c . "&s=date&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=date&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Date'],
		"LIST_TOP_AUTHOR" => sed_link(sed_url("list", "c=" . $c . "&s=author&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=author&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Author'],
		"LIST_TOP_OWNER" => sed_link(sed_url("list", "c=" . $c . "&s=ownerid&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=ownerid&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Owner'],
		"LIST_TOP_COUNT" => sed_link(sed_url("list", "c=" . $c . "&s=count&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=count&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Hits'],
		"LIST_TOP_FILECOUNT" => sed_link(sed_url("list", "c=" . $c . "&s=filecount&w=asc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_down']) .
			sed_link(sed_url("list", "c=" . $c . "&s=filecount&w=desc&o=" . $o . "&p=" . $p . $filter_urlparams), $out['ic_arrow_up']) . $L['Hits']
	));

	// ----- Extra fields 
	if ($number_of_extrafields > 0) {
		foreach ($extrafields as $row) {
			$extratitle = isset($L['page_' . $row['code'] . '_title']) ? $L['page_' . $row['code'] . '_title'] : $row['title'];
			$t->assign('LIST_TOP_' . strtoupper($row['code']), sed_link(sed_url('list', "c=$c&s=" . $row['code'] . "&w=asc&o=$o&p=$p" . $filter_urlparams), $out['ic_arrow_down']) .
				sed_link(sed_url('list', "c=$c&s=" . $row['code'] . "&w=desc&o=$o&p=$p" . $filter_urlparams), $out['ic_arrow_up']) . " " . $extratitle);
		}
	}
	//--------------- 
}

$ii = 0;
$jj = 1;
$mtch = $sed_cat[$c]['path'] . ".";
$mtchlen = mb_strlen($mtch);
$mtchlvl = mb_substr_count($mtch, ".");

foreach ($sed_cat as $i => $x) {
	if (mb_substr($x['path'], 0, $mtchlen) == $mtch && mb_substr_count($x['path'], ".") == $mtchlvl) {
		$sql4 = sed_sql_query("SELECT COUNT(*) FROM $db_pages p, $db_structure s
		WHERE p.page_cat=s.structure_code
		AND (s.structure_path LIKE '" . $sed_cat[$i]['rpath'] . "' 
		OR s.structure_path LIKE '" . $sed_cat[$i]['rpath'] . ".%') 
		AND page_state=0");

		$sub_count = sed_sql_result($sql4, 0, "COUNT(*)");

		$t->assign(array(
			"LIST_ROWCAT_ID" => $x['id'],
			"LIST_ROWCAT_URL" => sed_url("list", "c=" . $i),
			"LIST_ROWCAT_TITLE" => $x['title'],
			"LIST_ROWCAT_DESC" => $x['desc'],
			"LIST_ROWCAT_ICON" => $x['icon'],
			"LIST_ROWCAT_ICONSRC" => $x['iconsrc'],
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

while ($pag = sed_sql_fetchassoc($sql) and ($jj <= $cfg['maxrowsperpage'])) {

	/* === Hook - Part2 : Include === */
	if (is_array($extpf)) {
		foreach ($extpf as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$jj++;

	$sys['catcode'] = $pag['page_cat'];

	$pag['page_desc'] = sed_cc($pag['page_desc']);
	$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id']) : sed_url("page", "al=" . $pag['page_alias']);

	if (!empty($pag['page_url']) && $pag['page_file']) {
		$dotpos = mb_strrpos($pag['page_url'], ".") + 1;
		$pag['page_fileicon'] = (mb_strlen($pag['page_url']) - $dotpos > 4) ? "doc" : mb_strtolower(mb_substr($pag['page_url'], $dotpos, 5));

		$t->assign(array(
			"LIST_ROW_FILEICON" => $pag['page_fileicon'],
			"LIST_ROW_FILECOUNT" => $pag['page_filecount']
		));

		$t->parse("MAIN.LIST_ROW.LIST_ROW_FILE");
	} else {
		$pag['page_fileicon'] = '';
	}

	$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";

	$pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pcomments) : sed_url("page", "al=" . $pag['page_alias'] . $pcomments);
	$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . "&ratings=1") : sed_url("page", "al=" . $pag['page_alias'] . "&ratings=1");

	$item_code = 'p' . $pag['page_id'];
	$pag['page_comcount'] = (!$pag['page_comcount']) ? "0" : $pag['page_comcount'];
	$pag['page_comments'] = sed_link($pag['page_pageurlcom'], $out['ic_comment'] . " (" . $pag['page_comcount'] . ")");

	$pag['admin'] = $usr['isadmin'] ?
		sed_link(sed_url("admin", "m=page&a=unvalidate&id=" . $pag['page_id'] . "&" . sed_xg()), $L['Putinvalidationqueue']) . " &nbsp;" .
		sed_link(sed_url("page", "m=edit&id=" . $pag['page_id'] . "&r=list"), $L['Edit']) . " &nbsp;" .
		sed_link(sed_url("page", "m=add&id=" . $pag['page_id'] . "&r=list&a=clone"), $L['Clone']) :
		'';

	$t->assign(array(
		"LIST_ROW_URL" => $pag['page_pageurl'],
		"LIST_ROW_ID" => $pag['page_id'],
		"LIST_ROW_CAT" => $pag['page_cat'],
		"LIST_ROW_CATURL" => sed_url("list", "c=" . $pag['page_cat']),
		"LIST_ROW_CATTITLE" => $sed_cat[$pag['page_cat']]['title'],
		"LIST_ROW_KEY" => sed_cc($pag['page_key']),
		"LIST_ROW_TITLE" => sed_cc($pag['page_title']),
		"LIST_ROW_THUMB" => sed_cc($pag['page_thumb']),
		"LIST_ROW_DESC" => $pag['page_desc'],
		"LIST_ROW_AUTHOR" => sed_cc($pag['page_author']),
		"LIST_ROW_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
		"LIST_ROW_OWNER_AVATAR" => sed_build_userimage($pag['user_avatar']),
		"LIST_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $pag['page_date']),
		"LIST_ROW_FILEURL" => $pag['page_url'],
		"LIST_ROW_SIZE" => $pag['page_size'],
		"LIST_ROW_COUNT" => $pag['page_count'],
		"LIST_ROW_JUMP" => $pag['page_pageurl'] . "&a=dl",
		"LIST_ROW_COMMENTS" => $pag['page_comments'],
		"LIST_ROW_COMCOUNT" => $pag['page_comcount'],
		"LIST_ROW_COMURL" => $pag['page_pageurlcom'],
		"LIST_ROW_RATINGS" => sed_link($pag['page_pageurlrat'], "<img src=\"skins/" . $usr['skin'] . "/img/system/vote" . round($pag['page_rating'], 0) . ".gif\" alt=\"\" />"),
		"LIST_ROW_ADMIN" => $pag['admin'],
		"LIST_ROW_ODDEVEN" => sed_build_oddeven($jj)
	));

	if (!empty($pag['page_thumb'])) {
		$page_thumbs_array = rtrim($pag['page_thumb']);
		if ($page_thumbs_array[mb_strlen($page_thumbs_array) - 1] == ';') {
			$page_thumbs_array = mb_substr($page_thumbs_array, 0, -1);
		}
		$page_thumbs_array = explode(";", $page_thumbs_array);
		if (count($page_thumbs_array) > 0) {
			$t->assign("LIST_ROW_THUMB", $page_thumbs_array[0]);
			$t->parse("MAIN.LIST_ROW.LIST_ROW_THUMB");
		}
	} else {
		$t->assign("LIST_ROW_THUMB", "noimg.jpg");
	}

	// ---------- Extra fields - getting
	if (count($extrafields) > 0) {
		$extra_array = sed_build_extrafields_data('page', 'LIST_ROW', $extrafields, $pag);
		$t->assign($extra_array);
	}
	// ----------------------			

	/* === Hook - Part2 : Include === */
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$t->parse("MAIN.LIST_ROW");
}


/* === Hook === */
$extp = sed_getextplugins('list.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
