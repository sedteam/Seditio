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
$c = sed_import('c', 'G', 'TXT');

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('pages');
$number_of_extrafields = count($extrafields);
// ----------------------

if ($a == 'update') {
	sed_check_xg();

	$sql1 = sed_sql_query("SELECT page_cat, page_state, page_ownerid FROM $db_pages WHERE page_id='$id' LIMIT 1");
	sed_die(sed_sql_numrows($sql1) == 0);
	$row1 = sed_sql_fetchassoc($sql1);

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row1['page_cat']);
	sed_block($usr['isadmin']);

	/* === Hook === */
	$extp = sed_getextplugins('page.edit.update.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$rpagekey = sed_import('rpagekey', 'P', 'TXT');
	$rpagealias = sed_replacespace(sed_import('rpagealias', 'P', 'ALS')); //New in175  
	$rpagethumb = sed_import('rpagethumb', 'P', 'TXT');
	$rpagetitle = sed_import('rpagetitle', 'P', 'TXT');
	$rpagedesc = sed_import('rpagedesc', 'P', 'TXT');
	$rpagetext = sed_import('rpagetext', 'P', 'HTM');
	$rpagetext2 = sed_import('rpagetext2', 'P', 'HTM');
	$rpageauthor = sed_import('rpageauthor', 'P', 'TXT');
	$rpageownerid = sed_import('rpageownerid', 'P', 'INT');
	$rpagefile = sed_import('rpagefile', 'P', 'TXT');
	$rpageurl = sed_import('rpageurl', 'P', 'TXT');
	$rpagesize = sed_import('rpagesize', 'P', 'TXT');
	$rpagecount = sed_import('rpagecount', 'P', 'INT');
	$rpagefilecount = sed_import('rpagefilecount', 'P', 'INT');
	$rpagecat = sed_import('rpagecat', 'P', 'TXT');
	$rpagedatenow = sed_import('rpagedatenow', 'P', 'BOL');

	$rpageseotitle = sed_import('rpageseotitle', 'P', 'TXT');
	$rpageseodesc = sed_import('rpageseodesc', 'P', 'TXT');
	$rpageseokeywords = sed_import('rpageseokeywords', 'P', 'TXT');
	$rpageseoh1 = sed_import('rpageseoh1', 'P', 'TXT');

	$rpageallowcomments = sed_import('rpageallowcomments', 'P', 'BOL');
	$rpageallowratings = sed_import('rpageallowratings', 'P', 'BOL');

	$rpageallowcomments = (empty($rpageallowcomments) && $rpageallowcomments != 0) ? 1 : $rpageallowcomments;
	$rpageallowratings = (empty($rpageallowratings) && $rpageallowratings != 0) ? 1 : $rpageallowratings;

	$ryear = sed_import('ryear', 'P', 'INT');
	$rmonth = sed_import('rmonth', 'P', 'INT');
	$rday = sed_import('rday', 'P', 'INT');
	$rhour = sed_import('rhour', 'P', 'INT');
	$rminute = sed_import('rminute', 'P', 'INT');

	$ryear_beg = sed_import('ryear_beg', 'P', 'INT');
	$rmonth_beg = sed_import('rmonth_beg', 'P', 'INT');
	$rday_beg = sed_import('rday_beg', 'P', 'INT');
	$rhour_beg = sed_import('rhour_beg', 'P', 'INT');
	$rminute_beg = sed_import('rminute_beg', 'P', 'INT');

	$ryear_exp = sed_import('ryear_exp', 'P', 'INT');
	$rmonth_exp = sed_import('rmonth_exp', 'P', 'INT');
	$rday_exp = sed_import('rday_exp', 'P', 'INT');
	$rhour_exp = sed_import('rhour_exp', 'P', 'INT');
	$rminute_exp = sed_import('rminute_exp', 'P', 'INT');

	$rpagedelete = sed_import('rpagedelete', 'P', 'BOL');

	// --------- Extra fields     
	if ($number_of_extrafields > 0) $rpageextrafields = sed_extrafield_buildvar($extrafields, 'rpage', 'page');

	// ----------------------	

	$error_string .= (empty($rpagecat)) ? $L['pag_catmissing'] . "<br />" : '';
	$error_string .= (mb_strlen($rpagetitle) < 2) ? $L['pag_titletooshort'] . "<br />" : '';

	if (empty($error_string) || $rpagedelete) {
		if ($rpagedelete) {
			$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");

			if ($row = sed_sql_fetchassoc($sql)) {
				if ($cfg['trash_page']) {
					sed_trash_put('page', $L['Page'] . " #" . $id . " " . $row['page_title'], $id, $row);
				}

				$id2 = "p" . $id;
				$sql = sed_sql_query("DELETE FROM $db_pages WHERE page_id='$id'");
				$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id2'");
				$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id2'");
				$sql = sed_sql_query("DELETE FROM $db_com WHERE com_code='$id2'");
				sed_log("Deleted page #" . $id, 'adm');

				if (defined('SED_ADMIN')) {
					sed_redirect(sed_url("admin", "m=page&s=manager&c=" . $row1['page_cat'] . "&msg=917", "", true));
				} else {
					sed_redirect(sed_url("list", "c=" . $row1['page_cat'], "", true));
				}

				exit;
			}
		} else {
			$rpagedate = ($rpagedatenow) ? $sys['now_offset'] : sed_mktime($rhour, $rminute, 0, $rmonth, $rday, $ryear) - $usr['timezone'] * 3600;
			$rpagebegin = sed_mktime($rhour_beg, $rminute_beg, 0, $rmonth_beg, $rday_beg, $ryear_beg) - $usr['timezone'] * 3600;
			$rpageexpire = sed_mktime($rhour_exp, $rminute_exp, 0, $rmonth_exp, $rday_exp, $ryear_exp) - $usr['timezone'] * 3600;
			$rpageexpire = ($rpageexpire <= $rpagebegin) ? $rpagebegin + 31536000 : $rpageexpire;

			//Autovalidation New v175
			$rpagestate = $row1['page_state'];

			$rpagepublish = sed_import('rpagepublish', 'P', 'ALP');
			$rpagestate = (($rpagepublish == "OK") && $usr['isadmin']) ? 0 : $rpagestate; //Unvalidation
			$rpagestate = (($rpagepublish == "NO") && $usr['isadmin']) ? 1 : $rpagestate; //Validation

			$rpagealias = (empty($rpagealias) && $cfg['genseourls']) ? sed_translit_seourl($rpagetitle) : $rpagealias;

			if (!empty($rpagealias)) {
				$sql = sed_sql_query("SELECT page_id FROM $db_pages WHERE page_alias='" . sed_sql_prep($rpagealias) . "' AND page_id!='" . $id . "'");
				$rpagealias = (sed_sql_numrows($sql) > 0) ? "alias" . rand(1000, 9999) : $rpagealias;
			}

			// ------ Extra fields 
			$ssql_extra = '';
			if (count($extrafields) > 0) {
				foreach ($extrafields as $i => $row) {
					$ssql_extra .= ", page_" . $row['code'] . " = " . "'" . sed_sql_prep($rpageextrafields['page_' . $row['code']]) . "'";
				}
			}
			// ----------------------				


			$sql = sed_sql_query("UPDATE $db_pages SET
				page_state = '$rpagestate',
				page_cat = '" . sed_sql_prep($rpagecat) . "',
				page_key = '" . sed_sql_prep($rpagekey) . "',       
				page_title = '" . sed_sql_prep($rpagetitle) . "',
				page_desc = '" . sed_sql_prep($rpagedesc) . "',
				page_text='" . sed_sql_prep(sed_checkmore($rpagetext, true)) . "',
				page_text2='" . sed_sql_prep(sed_checkmore($rpagetext2, true)) . "',
				page_author = '" . sed_sql_prep($rpageauthor) . "',
				page_ownerid = '$rpageownerid',
				page_date = '$rpagedate',
				page_begin = '$rpagebegin',
				page_expire = '$rpageexpire',
				page_file = '" . sed_sql_prep($rpagefile) . "',
				page_url = '" . sed_sql_prep($rpageurl) . "',
				page_size = '" . sed_sql_prep($rpagesize) . "',
				page_count = '$rpagecount',
				page_allowcomments = '$rpageallowcomments',
				page_allowratings = '$rpageallowratings',
				page_filecount = '$rpagefilecount',
				page_alias = '" . sed_sql_prep($rpagealias) . "',
				page_seo_title = '" . sed_sql_prep($rpageseotitle) . "',
				page_seo_desc = '" . sed_sql_prep($rpageseodesc) . "',				
				page_seo_keywords = '" . sed_sql_prep($rpageseokeywords) . "',
				page_seo_h1 = '" . sed_sql_prep($rpageseoh1) . "',
				page_thumb = '" . sed_sql_prep($rpagethumb) . "'" . $ssql_extra . "          				
				WHERE page_id='$id'");

			/* === Hook === */
			$extp = sed_getextplugins('page.edit.update.done');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			$sys['catcode'] = $rpagecat; //new in v175

			sed_log("Edited page #" . $id, 'adm');

			$url_redir = (empty($rpagealias)) ? sed_url("page", "id=" . $id, "", true) : sed_url("page", "al=" . $rpagealias, "", true);

			if (defined('SED_ADMIN')) {
				sed_redirect(sed_url("admin", "m=page&s=manager&c=" . $rpagecat . "&msg=917", "", true));
			} else {
				sed_redirect($url_redir);
			}

			exit;
		}
	}
}

$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");
sed_die(sed_sql_numrows($sql) == 0);
$pag = sed_sql_fetchassoc($sql);

$pag['page_date'] = sed_selectbox_date($pag['page_date'] + $usr['timezone'] * 3600, 'long');
$pag['page_begin'] = sed_selectbox_date($pag['page_begin'] + $usr['timezone'] * 3600, 'long', '_beg');
$pag['page_expire'] = sed_selectbox_date($pag['page_expire'] + $usr['timezone'] * 3600, 'long', '_exp');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $pag['page_cat']);
sed_block($usr['isadmin']);

/* === Hook === */
$extp = sed_getextplugins('page.edit.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$page_form_delete =  sed_radiobox("rpagedelete", $yesno_arr, 0);

$page_form_categories = sed_selectbox_categories($pag['page_cat'], 'rpagecat');

$page_form_file = sed_radiobox("rpagefile", $yesno_arr, $pag['page_file']);
$page_form_allowcomments = sed_radiobox("rpageallowcomments", $yesno_arr, $pag['page_allowcomments']);
$page_form_allowratings = sed_radiobox("rpageallowratings", $yesno_arr, $pag['page_allowratings']);

$pfs = sed_build_pfs($usr['id'], 'update', 'rpagetext', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, 'update', 'rpagetext', $L['SFS']) : '';

$pfs_form_url_myfiles = (!$cfg['disable_pfs']) ? sed_build_pfs($usr['id'], "update", "rpageurl", $L['Mypfs']) : '';
$pfs_form_url_myfiles .= (sed_auth('pfs', 'a', 'A')) ? ' ' . sed_build_pfs(0, 'update', 'rpageurl', $L['SFS']) : '';

$sys['sublocation'] = $sed_cat[$pag['page_cat']]['title'];

$out['subtitle'] = $L['paged_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('pagetitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("page", "m=edit&id=" . $pag['page_id'] . "&r=list")] = $L['Edit'];

/* === Hook === */
$extp = sed_getextplugins('page.edit.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

if (defined('SED_ADMIN')) {
	$mskin = sed_skinfile(array('admin', 'page', 'edit', $sed_cat[$pag['page_cat']]['tpl']), false, true);
} else {
	require(SED_ROOT . "/system/header.php");
	$mskin = sed_skinfile(array('page', 'edit', $sed_cat[$pag['page_cat']]['tpl']));
}

$t = new XTemplate($mskin);

if (!empty($error_string)) {
	$t->assign("PAGEEDIT_ERROR_BODY", sed_alert($error_string, 'e'));
	$t->parse("MAIN.PAGEEDIT_ERROR");
}

if ($usr['isadmin']) {
	$publish_title = ($pag['page_state'] == 0) ? $L['Putinvalidationqueue'] : $L['Validate'];
	$publish_state = ($pag['page_state'] == 0) ? "NO" : "OK";
	$t->assign(array(
		"PAGEEDIT_FORM_PUBLISH_STATE" => $publish_state,
		"PAGEEDIT_FORM_PUBLISH_TITLE" => $publish_title
	));
	$t->parse("MAIN.PAGEEDIT_PUBLISH");
}

$form_send_url = (defined('SED_ADMIN')) ? sed_url("admin", "m=page&s=edit&a=update&id=" . $pag['page_id'] . "&" . sed_xg()) : sed_url("page", "m=edit&a=update&id=" . $pag['page_id'] . "&" . sed_xg());

$t->assign(array(
	"PAGEEDIT_PAGETITLE" => $L['paged_title'],
	"PAGEEDIT_SUBTITLE" => $L['paged_subtitle'],
	"PAGEEDIT_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PAGEEDIT_FORM_SEND" => $form_send_url,
	"PAGEEDIT_FORM_ID" => $pag['page_id'],
	"PAGEEDIT_FORM_STATE" => $pag['page_state'],
	"PAGEEDIT_FORM_CAT" => $page_form_categories,
	"PAGEEDIT_FORM_KEY" => sed_textbox('rpagekey', $pag['page_key'], 16, 16),
	"PAGEEDIT_FORM_ALIAS" => sed_textbox('rpagealias', $pag['page_alias']),
	"PAGEEDIT_FORM_THUMB" => sed_textbox('rpagethumb', $pag['page_thumb']),
	"PAGEEDIT_FORM_TITLE" => sed_textbox('rpagetitle', $pag['page_title']),
	"PAGEEDIT_FORM_DESC" => sed_textarea('rpagedesc', $pag['page_desc'], 3, 75),
	"PAGEEDIT_FORM_SEOTITLE" => sed_textbox('rpageseotitle', $pag['page_seo_title']),
	"PAGEEDIT_FORM_SEODESC" => sed_textbox('rpageseodesc', $pag['page_seo_desc']),
	"PAGEEDIT_FORM_SEOKEYWORDS" => sed_textbox('rpageseokeywords', $pag['page_seo_keywords']),
	"PAGEEDIT_FORM_SEOH1" => sed_textbox('rpageseoh1', $pag['page_seo_h1']),
	"PAGEEDIT_FORM_AUTHOR" => sed_textbox('rpageauthor', $pag['page_author'], 24, 32),
	"PAGEEDIT_FORM_OWNERID" => sed_textbox('rpageownerid', $pag['page_ownerid'], 24, 32),
	"PAGEEDIT_FORM_DATE" => $pag['page_date'] . " " . $usr['timetext'],
	"PAGEEDIT_FORM_DATENOW" => sed_checkbox('rpagedatenow'),
	"PAGEEDIT_FORM_BEGIN" => $pag['page_begin'] . " " . $usr['timetext'],
	"PAGEEDIT_FORM_EXPIRE" => $pag['page_expire'] . " " . $usr['timetext'],
	"PAGEEDIT_FORM_FILE" => $page_form_file,
	"PAGEEDIT_FORM_ALLOWRATINGS" => $page_form_allowratings,
	"PAGEEDIT_FORM_ALLOWCOMMENTS" => $page_form_allowcomments,
	"PAGEEDIT_FORM_URL" => sed_textbox('rpageurl', $pag['page_url']) . $pfs_form_url_myfiles,
	"PAGEEDIT_FORM_SIZE" => sed_textbox('rpagesize', $pag['page_size']),
	"PAGEEDIT_FORM_PAGECOUNT" => sed_textbox('rpagecount', $pag['page_count'], 8, 8),
	"PAGEEDIT_FORM_FILECOUNT" => sed_textbox('rpagefilecount', $pag['page_filecount'], 8, 8),
	"PAGEEDIT_FORM_TEXT" => sed_textarea('rpagetext', $pag['page_text'], $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended') . " " . $pfs,
	"PAGEEDIT_FORM_TEXT2" => sed_textarea('rpagetext2', $pag['page_text2'], $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended'),
	"PAGEEDIT_FORM_MYPFS" => $pfs,
	"PAGEEDIT_FORM_DELETE" => $page_form_delete
));

// Extra fields 
if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields('page', 'PAGEEDIT_FORM', $extrafields, $pag, 'rpage');
}

$t->assign($extra_array);

/* === Hook === */
$extp = sed_getextplugins('page.edit.tags');

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
