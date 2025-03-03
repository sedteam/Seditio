<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=page.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', 'any');
sed_block($usr['auth_read']);

$id = sed_import('id', 'G', 'INT');
$al = sed_sql_prep(sed_import('al', 'G', 'TXT'));
$r = sed_import('r', 'G', 'ALP');
$c = sed_import('c', 'G', 'TXT');

$comments = sed_import('comments', 'G', 'BOL');
$ratings = sed_import('ratings', 'G', 'BOL');

/* === Hook === */
$extp = sed_getextplugins('page.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if (!empty($al)) {
	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_avatar, u.user_maingrp FROM $db_pages AS p
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_alias='$al' LIMIT 1");
} else {
	$sql = sed_sql_query("SELECT p.*, u.user_name, u.user_avatar, u.user_maingrp FROM $db_pages AS p
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_id='$id'");
}

if (sed_sql_numrows($sql) == 0) {
	if (!empty($al) && array_key_exists($al, $sed_cat)) {
		sed_redirect(sed_url("list", "c=" . $al, "", true));
	} else {
		sed_die((sed_sql_numrows($sql) == 0), 404);
	}
}

$pag = sed_sql_fetchassoc($sql);

/* === Hook === */
$extp = sed_getextplugins('page.fetch');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$sys['catcode'] = $pag['page_cat']; //new in v175

$pag['page_date'] = sed_build_date($cfg['dateformat'], $pag['page_date']);
$pag['page_begin'] = sed_build_date($cfg['dateformat'], $pag['page_begin']);
$pag['page_expire'] = sed_build_date($cfg['dateformat'], $pag['page_expire']);
$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id']) : sed_url("page", "al=" . $pag['page_alias']);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $pag['page_cat']);
sed_block($usr['auth_read']);

if ($pag['page_state'] == 1 && !$usr['isadmin']) {
	sed_log("Attempt to directly access an un-validated page", 'sec');
	sed_redirect(sed_url("message", "msg=930", "", true));
	exit;
}

if (preg_match("#{redir:(.*?)}#", $pag['page_text'], $find_out)) {
	$redir = $find_out[1];
	$sql = sed_sql_query("UPDATE $db_pages SET page_filecount=page_filecount+1 WHERE page_id='" . $pag['page_id'] . "'");
	sed_redirect($redir);
	exit;
} elseif (preg_match("#{include:([a-zA-Z0-9_.\-]+)}#", $pag['page_text'], $find_out)) {
	$pag['page_text'] = sed_readraw('datas/html/' . trim(mb_substr($find_out[1], 0, 255)));
} elseif (preg_match("#{plugin:([a-z0-9]+)}#", $pag['page_text'], $find_out)) {
	define('SED_PLUG', TRUE);
	$plug = $find_out[1];
	$path_plug = SED_ROOT . 'plugins/' . $plug . '/' . $plug . '.php';
	$path_lang_def = SED_ROOT . "plugins/$plug/lang/$plug.en.lang.php";
	$path_lang_alt = SED_ROOT . "plugins/$plug/lang/$plug.$lang.lang.php";
	if (file_exists($path_lang_alt)) {
		require($path_lang_alt);
	} elseif (file_exists($path_lang_def)) {
		require($path_lang_def);
	}
	if (file_exists($path_plug)) {
		include($path_plug);
	}
	$pag['page_text'] = str_replace($find_out[0], $plugin_body, $pag['page_text']);
}

if ($pag['page_file'] && $a == 'dl') {
	$file_size = @filesize($row['page_url']);
	$pag['page_filecount']++;
	$sql = sed_sql_query("UPDATE $db_pages SET page_filecount=page_filecount+1 WHERE page_id='" . $pag['page_id'] . "'");

	if (preg_match('#^(http|ftp)s?://#', $pag['page_url'])) {
		sed_redirect($pag['page_url']);
	} else {
		sed_redirect($sys['abs_url'] . $pag['page_url']);
	}

	//	echo("<script type='text/javascript'>location.href='".$pag['page_url']."';</script>Redirecting...");
	exit;
}

if (!$usr['isadmin'] || $cfg['disablehitstats']) {
	$pag['page_count']++;
	$sql = sed_sql_query("UPDATE $db_pages SET page_count='" . $pag['page_count'] . "' WHERE page_id='" . $pag['page_id'] . "'");
}

$catpath = sed_build_catpath($pag['page_cat'], "<a href=\"%1\$s\">%2\$s</a>");

$pag['page_fulltitle'] = empty($catpath) ? "" : $catpath . " " . $cfg['separator'] . " ";
$pag['page_fulltitle'] .= "<a href=\"" . $pag['page_pageurl'] . "\">" . $pag['page_title'] . "</a>";

$item_code = 'p' . $pag['page_id'];

// Options for category New v173
$allowcommentscat = $sed_cat[$pag['page_cat']]['allowcomments'];
$allowratingscat = $sed_cat[$pag['page_cat']]['allowratings'];

// Options for page New v173
$allowcommentspage = $pag['page_allowcomments'];
$allowratingspage = $pag['page_allowratings'];

$comments = $cfg['showcommentsonpage'] ? $cfg['showcommentsonpage'] : $comments;

//fix for sed_url()
$url_param = (empty($pag['page_alias'])) ? "id=" . $pag['page_id'] : "al=" . $pag['page_alias'];
$url_page = array('part' => 'page', 'params' => $url_param);

if ($allowcommentscat) {
	list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_page, $comments, $allowcommentspage);
}
if ($allowratingscat) {
	list($ratings_link, $ratings_display) = sed_build_ratings($item_code, $url_page, $ratings, $allowratingspage);
}

$pcomments = ($cfg['showcommentsonpage']) ? "" : "&comments=1";
$pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pcomments) : sed_url("page", "al=" . $pag['page_alias'] . $pcomments);

$pratings = ($ratings) ? "" : "&ratings=1";
$pag['page_pageurlrat'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pratings) : sed_url("page", "al=" . $pag['page_alias'] . $pratings);

$sys['sublocation'] = $sed_cat[$pag['page_cat']]['title'];

$out['subtitle'] = (empty($pag['page_seo_title'])) ? $pag['page_title'] : $pag['page_seo_title'];
$out['subdesc'] = (empty($pag['page_seo_desc'])) ? $pag['page_desc'] : $pag['page_seo_desc'];

/**/
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}', '{CATEGORY}');
$title_tags[] = array('%1$s', '%2$s', '%3$s', '%4$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle'], $sed_cat[$pag['page_cat']]['title']);
$out['subtitle'] = sed_title('pagetitle', $title_tags, $title_data);
/**/

$out['subkeywords'] = $pag['page_seo_keywords'];
$out['canonical_url'] = ($cfg['absurls']) ? $pag['page_pageurl'] : $sys['abs_url'] . $pag['page_pageurl'];

// ---------- Breadcrumbs
$urlpaths = array();
sed_build_list_bc($pag['page_cat']);
$urlpaths[$pag['page_pageurl']] = $pag['page_title'];

// ---------- Page thumb
$page_thumbs_array = array();
if (!empty($pag['page_thumb'])) {
	$page_thumbs_array = rtrim($pag['page_thumb']);
	if ($page_thumbs_array[mb_strlen($page_thumbs_array) - 1] == ';') {
		$page_thumbs_array = mb_substr($page_thumbs_array, 0, -1);
	}
	$page_thumbs_array = explode(";", $page_thumbs_array);
	if (count($page_thumbs_array) > 0) {
		$out['image'] = $page_thumbs_array[0];
	}
}

/* === Hook === */
$extp = sed_getextplugins('page.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if ($m == 'print') {
	sed_sendheaders();
	$mskin = sed_skinfile(array('print.page', $sed_cat[$pag['page_cat']]['tpl']));
} elseif (!empty($pag['page_alias']) && file_exists(sed_skinfile(array('page.alias', $pag['page_alias'])))) {
	require(SED_ROOT . "/system/header.php");
	$mskin = sed_skinfile(array('page.alias', $pag['page_alias']));
} else {
	require(SED_ROOT . "/system/header.php");
	$mskin = sed_skinfile(array('page', $sed_cat[$pag['page_cat']]['tpl']));
}

$t = new XTemplate($mskin);

$t->assign(array(
	"PAGE_ID" => $pag['page_id'],
	"PAGE_STATE" => $pag['page_state'],
	"PAGE_TITLE" => $pag['page_fulltitle'],
	"PAGE_SHORTTITLE" => $pag['page_title'],
	"PAGE_SEOH1" => (empty($pag['page_seo_h1'])) ? $pag['page_title'] : $pag['page_seo_title'],
	"PAGE_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PAGE_CAT" => $pag['page_cat'],
	"PAGE_CATTITLE" => $sed_cat[$pag['page_cat']]['title'],
	"PAGE_CATPATH" => $catpath,
	"PAGE_CATDESC" => $sed_cat[$pag['page_cat']]['desc'],
	"PAGE_CATICON" => $sed_cat[$pag['page_cat']]['icon'],
	"PAGE_KEY" => $pag['page_key'],
	"PAGE_THUMB" => $pag['page_thumb'],
	"PAGE_DESC" => $pag['page_desc'],
	"PAGE_AUTHOR" => $pag['page_author'],
	"PAGE_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
	"PAGE_OWNER_AVATAR" => sed_build_userimage($pag['user_avatar']),
	"PAGE_DATE" => $pag['page_date'],
	"PAGE_BEGIN" => $pag['page_begin'],
	"PAGE_EXPIRE" => $pag['page_expire']
));

if (!empty($comments_link)) {
	$t->assign(array(
		"PAGE_COMMENTS" => $comments_link,
		"PAGE_COMMENTS_DISPLAY" => $comments_display,
		"PAGE_COMMENTS_ISSHOW" => ($cfg['showcommentsonpage'] || $comments) ? " active" : "",
		"PAGE_COMMENTS_JUMP" => ($cfg['showcommentsonpage'] || $comments) ? "<span class=\"spoiler-jump\"></span>" : "",
		"PAGE_COMMENTS_COUNT" => $pag['page_comcount'],
		"PAGE_COMMENTS_RSS" => sed_url("rss", "m=comments&id=" . $pag['page_id']),
		"PAGE_COMMENTS_URL" => $pag['page_pageurlcom']
	));
	$t->parse("MAIN.PAGE_COMMENTS");
}

if (!empty($ratings_link)) {
	$t->assign(array(
		"PAGE_RATINGS_COUNT" => $pag['page_rating'],
		"PAGE_RATINGS_URL" => $pag['page_pageurlrat'],
		"PAGE_RATINGS" => $ratings_link,
		"PAGE_RATINGS_DISPLAY" => $ratings_display
	));
	$t->parse("MAIN.PAGE_RATINGS");
}

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('pages');
$number_of_extrafields = count($extrafields);

if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields_data('page', 'PAGE', $extrafields, $pag);
	$t->assign($extra_array);
}

// ----------------------

if (count($page_thumbs_array) > 0) {
	$t->assign("PAGE_THUMB", $page_thumbs_array[0]);
	$t->parse("MAIN.PAGE_THUMB");
} else {
	$t->assign("PAGE_THUMB", sed_cc($pag['page_thumb']));
}

if ($usr['isadmin']) {
	$t->assign(array(
		"PAGE_ADMIN_COUNT" => $pag['page_count'],
		"PAGE_ADMIN_UNVALIDATE" => "<a href=\"" . sed_url("admin", "m=page&a=unvalidate&id=" . $pag['page_id'] . "&" . sed_xg()) . "\">" . $L['Putinvalidationqueue'] . "</a>",
		"PAGE_ADMIN_EDIT" => "<a href=\"" . sed_url("page", "m=edit&id=" . $pag['page_id'] . "&r=list") . "\">" . $L['Edit'] . "</a>",
		"PAGE_ADMIN_CLONE" => "<a href=\"" . sed_url("page", "m=add&id=" . $pag['page_id'] . "&r=list&a=clone") . "\">" . $L['Clone'] . "</a>"
	));

	$t->parse("MAIN.PAGE_ADMIN");
}

$pag['page_text'] = sed_parse($pag['page_text']);
$pag['page_text2'] = sed_parse($pag['page_text2']);

$t->assign("PAGE_TEXT", $pag['page_text']);
$t->assign("PAGE_TEXT2", $pag['page_text2']);

if ($pag['page_file']) {
	if (!empty($pag['page_url'])) {
		$dotpos = mb_strrpos($pag['page_url'], ".") + 1;
		$pag['page_fileicon'] = "system/img/pfs/" . mb_strtolower(mb_substr($pag['page_url'], $dotpos, 5)) . ".gif";
		if (!file_exists($pag['page_fileicon'])) {
			$pag['page_fileicon'] = "system/img/admin/page.png";
		}
		$pag['page_fileicon'] = "<img src=\"" . $pag['page_fileicon'] . "\" alt=\"\">";
	} else {
		$pag['page_fileicon'] = '';
	}

	$t->assign(array(
		"PAGE_FILE_URL" => sed_url("page", $url_param . "&a=dl"),
		"PAGE_FILE_SIZE" => $pag['page_size'],
		"PAGE_FILE_COUNT" => $pag['page_filecount'],
		"PAGE_FILE_ICON" => $pag['page_fileicon'],
		"PAGE_FILE_NAME" => basename($pag['page_url'])
	));
	$t->parse("MAIN.PAGE_FILE");
}

/* === Hook === */
$extp = sed_getextplugins('page.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

if ($m == 'print') {
	@ob_end_flush();
	@ob_end_flush();
	sed_sql_close($connection_id);
} else {
	require(SED_ROOT . "/system/footer.php");
}
