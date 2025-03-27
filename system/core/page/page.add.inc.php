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

$id = sed_import('id', 'G', 'INT');
$r = sed_import('r', 'G', 'ALP');
$c = sed_import('c', 'G', 'TXT');

$newpageallowcomments = 1;
$newpageallowratings = 1;

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', 'any');
sed_block($usr['auth_write']);

/* === Hook === */
$extp = sed_getextplugins('page.add.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('pages');
$number_of_extrafields = count($extrafields);
// ----------------------

if ($a == 'add') {
	sed_shield_protect();

	/* === Hook === */
	$extp = sed_getextplugins('page.add.add.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$newpagecat = sed_import('newpagecat', 'P', 'TXT');
	$newpagekey = sed_import('newpagekey', 'P', 'TXT');
	$newpagealias = sed_replacespace(sed_import('newpagealias', 'P', 'ALS'));  //New in175
	$newpagethumb = sed_import('newpagethumb', 'P', 'TXT');
	$newpagetitle = sed_import('newpagetitle', 'P', 'TXT');
	$newpagedesc = sed_import('newpagedesc', 'P', 'TXT');
	$newpagetext = sed_import('newpagetext', 'P', 'HTM');
	$newpagetext2 = sed_import('newpagetext2', 'P', 'HTM');
	$newpageauthor = sed_import('newpageauthor', 'P', 'TXT');
	$newpagefile = sed_import('newpagefile', 'P', 'TXT');
	$newpageurl = sed_import('newpageurl', 'P', 'TXT');
	$newpagesize = sed_import('newpagesize', 'P', 'TXT');
	$newpageyear_beg = sed_import('ryear_beg', 'P', 'INT');
	$newpagemonth_beg = sed_import('rmonth_beg', 'P', 'INT');
	$newpageday_beg = sed_import('rday_beg', 'P', 'INT');
	$newpagehour_beg = sed_import('rhour_beg', 'P', 'INT');
	$newpageminute_beg = sed_import('rminute_beg', 'P', 'INT');
	$newpageyear_exp = sed_import('ryear_exp', 'P', 'INT');
	$newpagemonth_exp = sed_import('rmonth_exp', 'P', 'INT');
	$newpageday_exp = sed_import('rday_exp', 'P', 'INT');
	$newpagehour_exp = sed_import('rhour_exp', 'P', 'INT');
	$newpageminute_exp = sed_import('rminute_exp', 'P', 'INT');

	$newpageseotitle = sed_import('newpageseotitle', 'P', 'TXT');
	$newpageseodesc = sed_import('newpageseodesc', 'P', 'TXT');
	$newpageseokeywords = sed_import('newpageseokeywords', 'P', 'TXT');
	$newpageseoh1 = sed_import('newpageseoh1', 'P', 'TXT');

	$newpageseoindex = sed_import('newpageseoindex', 'P', 'BOL');
	$newpageseofollow = sed_import('newpageseofollow', 'P', 'BOL');

	$newpageallowcomments = sed_import('newpageallowcomments', 'P', 'BOL');
	$newpageallowratings = sed_import('newpageallowratings', 'P', 'BOL');

	$newpageallowcomments  = (empty($newpageallowcomments) && $newpageallowcomments != 0) ? 1 : $newpageallowcomments;  //Fix 175
	$newpageallowratings = (empty($newpageallowratings) && $newpageallowratings != 0) ? 1 : $newpageallowratings; //Fix 175

	$newpagebegin = sed_mktime($newpagehour_beg, $newpageminute_beg, 0, $newpagemonth_beg, $newpageday_beg, $newpageyear_beg) - $usr['timezone'] * 3600;
	$newpageexpire = sed_mktime($newpagehour_exp, $newpageminute_exp, 0, $newpagemonth_exp, $newpageday_exp, $newpageyear_exp) - $usr['timezone'] * 3600;
	$newpageexpire = ($newpageexpire <= $newpagebegin) ? 1861916400 : $newpageexpire;
	$newpagebegin = ($newpagebegin < 0) ? 0 : $newpagebegin;

	// --------- Extra fields     
	if ($number_of_extrafields > 0) $newpageextrafields = sed_extrafield_buildvar($extrafields, 'newpage', 'page');
	// ----------------------

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $newpagecat);
	sed_block($usr['auth_write']);

	//Autovalidation New v173
	$newpagepublish = sed_import('newpagepublish', 'P', 'ALP');
	$newpagestate = (($newpagepublish == "OK") && $usr['isadmin']) ? 0 : 1;

	$error_string .= (empty($newpagecat)) ? $L['pag_catmissing'] . "<br />" : '';
	$error_string .= (mb_strlen($newpagetitle) < 2) ? $L['pag_titletooshort'] . "<br />" : '';

	$newpagealias = (empty($newpagealias) && $cfg['genseourls']) ? sed_translit_seourl($newpagetitle) : $newpagealias;

	if (empty($error_string)) {
		if (!empty($newpagealias)) {
			$sql = sed_sql_query("SELECT page_id FROM $db_pages WHERE page_alias='" . sed_sql_prep($newpagealias) . "'");
			$newpagealias = (sed_sql_numrows($sql) > 0) ? "alias" . rand(1000, 9999) : $newpagealias;
		}

		// ------ Extra fields 
		$ssql_extra_columns = '';
		$ssql_extra_values = '';
		if (count($extrafields) > 0) {
			foreach ($extrafields as $i => $row) {
				$ssql_extra_columns .= ', page_' . $row['code'];
				$ssql_extra_values .= ", '" . sed_sql_prep($newpageextrafields['page_' . $row['code']]) . "'";
			}
		}
		// ----------------------

		$sql = sed_sql_query("INSERT into $db_pages
			(page_state,
			page_cat,
			page_key,     
			page_title,
			page_desc,
			page_text,
			page_text2,
			page_author,
			page_ownerid,
			page_date,
			page_begin,
			page_expire,
			page_file,
			page_url,
			page_size,
			page_alias,
			page_allowcomments,
			page_allowratings,
			page_seo_title,
			page_seo_desc,
			page_seo_keywords,
			page_seo_h1,
			page_seo_index,
			page_seo_follow,
			page_thumb" . $ssql_extra_columns . "
			)
			VALUES
			(" . (int)$newpagestate . ",
			'" . sed_sql_prep($newpagecat) . "',
			'" . sed_sql_prep($newpagekey) . "',    
			'" . sed_sql_prep($newpagetitle) . "',
			'" . sed_sql_prep($newpagedesc) . "',
			'" . sed_sql_prep(sed_checkmore($newpagetext, true)) . "',
			'" . sed_sql_prep(sed_checkmore($newpagetext2, true)) . "',
			'" . sed_sql_prep($newpageauthor) . "',
			" . (int)$usr['id'] . ",
			" . (int)$sys['now_offset'] . ",
			" . (int)$newpagebegin . ",
			" . (int)$newpageexpire . ",
			" . (int)$newpagefile . ",
			'" . sed_sql_prep($newpageurl) . "',
			'" . sed_sql_prep($newpagesize) . "',
			'" . sed_sql_prep($newpagealias) . "',
			" . (int)$newpageallowcomments . ",      
			" . (int)$newpageallowratings . ",
			'" . sed_sql_prep($newpageseotitle) . "',
			'" . sed_sql_prep($newpageseodesc) . "',			
			'" . sed_sql_prep($newpageseokeywords) . "',  
			'" . sed_sql_prep($newpageseoh1) . "', 	
			" . (int)$newpageseoindex . ", 	
			" . (int)$newpageseofollow . ", 			
			'" . sed_sql_prep($newpagethumb) . "'" . $ssql_extra_values . ")");

		/* === Hook === */
		$extp = sed_getextplugins('page.add.add.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		sed_shield_update(30, "New page");

		if (defined('SED_ADMIN')) {
			sed_redirect(sed_url("admin", "m=page&s=manager&c=" . $newpagecat . "&msg=300", "", true));
		} else {
			sed_redirect(sed_url("message", "msg=300", "", true));
		}

		exit;
	}
}

if (($a == 'clone') && ($id > 0)) {
	$sql1 = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");
	sed_die(sed_sql_numrows($sql1) == 0);
	$row1 = sed_sql_fetchassoc($sql1);

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row1['page_cat']);
	sed_block($usr['isadmin']);

	/* === Hook === */
	$extp = sed_getextplugins('page.add.clone');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$newpagecat = $row1['page_cat'];
	$newpagekey = $row1['page_key'];
	$newpagealias = $row1['page_alias'];
	$newpagethumb = $row1['page_thumb'];
	$newpagetitle = $row1['page_title'];
	$newpagedesc = $row1['page_desc'];
	$newpagetext = $row1['page_text'];
	$newpagetext2 = $row1['page_text2'];
	$newpageauthor = $row1['page_author'];
	$newpagefile = $row1['page_file'];
	$newpageurl = $row1['page_url'];
	$newpagesize = $row1['page_size'];
	$newpageallowcomments = $row1['page_allowcomments'];
	$newpageallowratings = $row1['page_allowratings'];
	$newpageseotitle = $row1['page_seo_title'];
	$newpageseodesc = $row1['page_seo_desc'];
	$newpageseokeywords = $row1['page_seo_keywords'];
	$newpageseoh1 = $row1['page_seo_h1'];
	$newpageseoindex = $row1['page_seo_index'];
	$newpageseofollow = $row1['page_seo_follow'];

	if (count($extrafields) > 0) {
		foreach ($extrafields as $row) {
			$a = 'newpage' . $row['code'];
			$$a = $row1['page_' . $row['code']];
		}
		$newpageextrafields = $row1;
	}
}

if (empty($newpagecat) && !empty($c)) {
	$newpagecat = $c;
	$usr['isadmin'] = sed_auth('page', $newpagecat, 'A');
}

$pageadd_form_file = sed_radiobox("newpagefile", $yesno_arr, isset($newpagefile) ? $newpagefile : 0);
$pageadd_form_allowcomments = sed_radiobox("newpageallowcomments", $yesno_arr, $newpageallowcomments);
$pageadd_form_allowratings = sed_radiobox("newpageallowratings", $yesno_arr, $newpageallowratings);

$pageadd_form_categories = sed_selectbox_categories(isset($newpagecat) ? $newpagecat : '', 'newpagecat');
$newpage_form_begin = sed_selectbox_date($sys['now_offset'] + $usr['timezone'] * 3600, 'long', '_beg');
$newpage_form_expire = sed_selectbox_date(1861916400, 'long', '_exp');

$pfs = sed_build_pfs($usr['id'], 'newpage', 'newpagetext', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, 'newpage', 'newpagetext', $L['SFS']) : '';

$pfs_form_url_myfiles = (!$cfg['disable_pfs']) ? sed_build_pfs($usr['id'], "newpage", "newpageurl", $L['Mypfs']) : '';
$pfs_form_url_myfiles .= (sed_auth('pfs', 'a', 'A')) ? ' ' . sed_build_pfs(0, 'newpage', 'newpageurl', $L['SFS']) : '';

$sys['sublocation'] = (isset($newpagecat) && isset($sed_cat[$newpagecat])) ? $sed_cat[$newpagecat]['title'] : '';

$out['subtitle'] = $L['pagadd_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('pagetitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("page", "m=add&c=" . $c)] = $L['Add'];

/* === Hook === */
$extp = sed_getextplugins('page.add.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$newpage_tpl = (isset($newpagecat) && isset($sed_cat[$newpagecat]['tpl'])) ? $sed_cat[$newpagecat]['tpl'] : '';

if (defined('SED_ADMIN')) {
	$mskin = sed_skinfile(array('admin', 'page', 'add', $newpage_tpl), false, true);
} else {

	require(SED_ROOT . "/system/header.php");
	$mskin = sed_skinfile(array('page', 'add', $newpage_tpl));
}

$t = new XTemplate($mskin);

if (!empty($error_string)) {
	$t->assign("PAGEADD_ERROR_BODY", sed_alert($error_string, 'e'));
	$t->parse("MAIN.PAGEADD_ERROR");
}

if ($usr['isadmin']) {
	$t->parse("MAIN.PAGEADD_PUBLISH");
}

$form_send_url = (defined('SED_ADMIN')) ? sed_url("admin", "m=page&s=add&a=add") : sed_url("page", "m=add&a=add");

$t->assign(array(
	"PAGEADD_PAGETITLE" => $L['pagadd_title'],
	"PAGEADD_SUBTITLE" => $L['pagadd_subtitle'],
	"PAGEADD_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PAGEADD_ADMINEMAIL" => "mailto:" . $cfg['adminemail'],
	"PAGEADD_FORM_SEND" => $form_send_url,
	"PAGEADD_FORM_CAT" => $pageadd_form_categories,
	"PAGEADD_FORM_KEY" => sed_textbox('newpagekey', isset($newpagekey) ? $newpagekey : '', 16, 16),
	"PAGEADD_FORM_ALIAS" => sed_textbox('newpagealias', isset($newpagealias) ? $newpagealias : ''),
	"PAGEADD_FORM_TITLE" => sed_textbox('newpagetitle', isset($newpagetitle) ? $newpagetitle : ''),
	"PAGEADD_FORM_DESC" => sed_textarea('newpagedesc', isset($newpagedesc) ? $newpagedesc : '', 3, 75),
	"PAGEADD_FORM_SEOTITLE" => sed_textbox('newpageseotitle', isset($newpageseotitle) ? $newpageseotitle : ''),
	"PAGEADD_FORM_SEODESC" => sed_textbox('newpageseodesc', isset($newpageseodesc) ? $newpageseodesc : ''),
	"PAGEADD_FORM_SEOKEYWORDS" => sed_textbox('newpageseokeywords', isset($newpageseokeywords) ? $newpageseokeywords : ''),
	"PAGEADD_FORM_SEOH1" => sed_textbox('newpageseoh1', isset($newpageseoh1) ? $newpageseoh1 : ''),
	"PAGEADD_FORM_SEOINDEX" => sed_checkbox("newpageseoindex", "", isset($newpageseoindex) ? $newpageseoindex : 1),
	"PAGEADD_FORM_SEOFOLLOW" => sed_checkbox("newpageseofollow", "", isset($newpageseofollow) ? $newpageseofollow : 1),
	"PAGEADD_FORM_THUMB" => sed_textbox('newpagethumb', isset($newpagethumb) ? $newpagethumb : ''),
	"PAGEADD_FORM_AUTHOR" => sed_textbox('newpageauthor', isset($newpageauthor) ? $newpageauthor : '', 16, 24),
	"PAGEADD_FORM_OWNER" => sed_build_user($usr['id'], sed_cc($usr['name'])),
	"PAGEADD_FORM_OWNERID" => $usr['id'],
	"PAGEADD_FORM_BEGIN" => $newpage_form_begin,
	"PAGEADD_FORM_EXPIRE" => $newpage_form_expire,
	"PAGEADD_FORM_FILE" => $pageadd_form_file,
	"PAGEADD_FORM_ALLOWRATINGS" => $pageadd_form_allowratings,
	"PAGEADD_FORM_ALLOWCOMMENTS" => $pageadd_form_allowcomments,
	"PAGEADD_FORM_URL" => sed_textbox('newpageurl', isset($newpageurl) ? $newpageurl : '') . " " . $pfs_form_url_myfiles,
	"PAGEADD_FORM_SIZE" => sed_textbox('newpagesize', isset($newpagesize) ? $newpagesize : ''),
	"PAGEADD_FORM_TEXT" => sed_textarea('newpagetext', isset($newpagetext) ? $newpagetext : '', $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended') . " " . $pfs,
	"PAGEADD_FORM_TEXT2" => sed_textarea('newpagetext2', isset($newpagetext2) ? $newpagetext2 : '', $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended'),
	"PAGEADD_FORM_MYPFS" => $pfs
));

// Extra fields 
if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields('page', 'PAGEADD_FORM', $extrafields, isset($newpageextrafields) ? $newpageextrafields : array(), 'newpage');
}

$t->assign($extra_array);

/* === Hook === */
$extp = sed_getextplugins('page.add.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if (defined('SED_ADMIN')) {
	$t->parse("MAIN");
	$adminmain = $t->text("MAIN");
} else {
	$t->parse("MAIN");
	$t->out("MAIN");
	require(SED_ROOT . "/system/footer.php");
}
