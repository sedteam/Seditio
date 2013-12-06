<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=page.inc.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', 'any');
sed_block($usr['auth_read']);

$id = sed_import('id','G','INT');
$c = sed_import('c','G','TXT');

if ($a=='update')
	{
	sed_check_xg();
		
	$sql1 = sed_sql_query("SELECT page_cat, page_state, page_ownerid FROM $db_pages WHERE page_id='$id' LIMIT 1");
	sed_die(sed_sql_numrows($sql1)==0);
	$row1 = sed_sql_fetchassoc($sql1);

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row1['page_cat']);
	sed_block($usr['isadmin']);

	/* === Hook === */
	$extp = sed_getextplugins('page.edit.update.first');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$rpagekey = sed_import('rpagekey','P','TXT');
	$rpagealias = sed_replacespace(sed_import('rpagealias','P','TXT')); //New in175
	$rpageextra1 = sed_import('rpageextra1','P','TXT');
	$rpageextra2 = sed_import('rpageextra2','P','TXT');
	$rpageextra3 = sed_import('rpageextra3','P','TXT');
	$rpageextra4 = sed_import('rpageextra4','P','TXT');
	$rpageextra5 = sed_import('rpageextra5','P','HTM');
	$rpagetype = sed_import('rpagetype','P','INT');
	$rpagetitle = sed_import('rpagetitle','P','TXT');
	$rpagedesc = sed_import('rpagedesc','P','TXT');
	$rpagetext = sed_import('rpagetext','P','HTM');
	$rpagetext2 = sed_import('rpagetext2','P','HTM');
	$rpageauthor = sed_import('rpageauthor','P','TXT');
	$rpageownerid = sed_import('rpageownerid','P','INT');
	$rpagefile = sed_import('rpagefile','P','TXT');
	$rpageurl = sed_import('rpageurl','P','TXT');
	$rpagesize = sed_import('rpagesize','P','TXT');
	$rpagecount = sed_import('rpagecount','P','INT');
	$rpagefilecount = sed_import('rpagefilecount','P','INT');
	$rpagecat = sed_import('rpagecat','P','TXT');
	$rpagedatenow = sed_import('rpagedatenow','P','BOL');
	
	$rpageallowcomments = sed_import('rpageallowcomments','P','BOL');
	$rpageallowratings = sed_import('rpageallowratings','P','BOL');
  
  $rpageallowcomment = (empty($rpageallowcomments)) ? 1 : $rpageallowcomment;
  $rpageallowratings = (empty($rpageallowratings)) ? 1 : $rpageallowratings;

	$ryear = sed_import('ryear','P','INT');
	$rmonth = sed_import('rmonth','P','INT');
	$rday = sed_import('rday','P','INT');
	$rhour = sed_import('rhour','P','INT');
	$rminute = sed_import('rminute','P','INT');

	$ryear_beg = sed_import('ryear_beg','P','INT');
	$rmonth_beg = sed_import('rmonth_beg','P','INT');
	$rday_beg = sed_import('rday_beg','P','INT');
	$rhour_beg = sed_import('rhour_beg','P','INT');
	$rminute_beg = sed_import('rminute_beg','P','INT');

	$ryear_exp = sed_import('ryear_exp','P','INT');
	$rmonth_exp = sed_import('rmonth_exp','P','INT');
	$rday_exp = sed_import('rday_exp','P','INT');
	$rhour_exp = sed_import('rhour_exp','P','INT');
	$rminute_exp = sed_import('rminute_exp','P','INT');

	$rpagedelete = sed_import('rpagedelete','P','BOL');

	$error_string .= (empty($rpagecat)) ? $L['pag_catmissing']."<br />" : '';
	$error_string .= (mb_strlen($rpagetitle)<2) ? $L['pag_titletooshort']."<br />" : '';

	if (empty($error_string) || $rpagedelete)
		{
		if ($rpagedelete)
			{
			$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");

			if ($row = sed_sql_fetchassoc($sql))
				{
				if ($cfg['trash_page'])
					{ sed_trash_put('page', $L['Page']." #".$id." ".$row['page_title'], $id, $row); }

				$id2 = "p".$id;
				$sql = sed_sql_query("DELETE FROM $db_pages WHERE page_id='$id'");
				$sql = sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$id2'");
				$sql = sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$id2'");
				$sql = sed_sql_query("DELETE FROM $db_com WHERE com_code='$id2'");
				sed_log("Deleted page #".$id,'adm');
				sed_redirect(sed_url("list", "c=".$row1['page_cat'], "", true));
				exit;
				}
			}
		else
			{
			$rpagebegin = ($rpagebegin<0) ? 0 : $rpagebegin;
			$rpagedate = ($rpagedatenow) ? $sys['now_offset'] : sed_mktime($rhour, $rminute, 0, $rmonth, $rday, $ryear) - $usr['timezone'] * 3600;
			$rpagebegin = sed_mktime($rhour_beg, $rminute_beg, 0, $rmonth_beg, $rday_beg, $ryear_beg) - $usr['timezone'] * 3600;
			$rpageexpire = sed_mktime($rhour_exp, $rminute_exp, 0, $rmonth_exp, $rday_exp, $ryear_exp) - $usr['timezone'] * 3600;
			$rpageexpire = ($rpageexpire<=$rpagebegin) ? $rpagebegin+31536000 : $rpageexpire;

			$rpagetype = ($usr['maingrp']!=5 && $rpagetype >1) ? 0 : $rpagetype;
			$rpagetype = ($cfg['textmode']=='html') ? 1 : $rpagetype;
			
			//Autovalidation New v175
			$rpagestate = $row1['page_state'];
			
			$rpagepublish = sed_import('rpagepublish', 'P', 'ALP');
			$rpagestate = (($rpagepublish == "OK") && $usr['isadmin']) ? 0 : $rpagestate; //Unvalidation
			$rpagestate = (($rpagepublish == "NO") && $usr['isadmin']) ? 1 : $rpagestate; //Validation

			if (!empty($rpagealias))
				{
				$sql = sed_sql_query("SELECT page_id FROM $db_pages WHERE page_alias='".sed_sql_prep($rpagealias)."' AND page_id!='".$id."'");
				$rpagealias = (sed_sql_numrows($sql)>0) ? "alias".rand(1000,9999) : $rpagealias;
				}
	
			$sql = sed_sql_query("UPDATE $db_pages SET
				page_state = '$rpagestate',
				page_cat = '".sed_sql_prep($rpagecat)."',
				page_type = '".sed_sql_prep($rpagetype)."',
				page_key = '".sed_sql_prep($rpagekey)."',
				page_extra1 = '".sed_sql_prep($rpageextra1)."',
				page_extra2 = '".sed_sql_prep($rpageextra2)."',
				page_extra3 = '".sed_sql_prep($rpageextra3)."',
				page_extra4 = '".sed_sql_prep($rpageextra4)."',
				page_extra5 = '".sed_sql_prep($rpageextra5)."',
				page_title = '".sed_sql_prep($rpagetitle)."',
				page_desc = '".sed_sql_prep($rpagedesc)."',
				page_text='".sed_sql_prep(sed_checkmore($rpagetext, true))."',
				page_text_ishtml='".$ishtml."',
				page_text2='".sed_sql_prep(sed_checkmore($rpagetext2, true))."',
				page_author = '".sed_sql_prep($rpageauthor)."',
				page_ownerid = '$rpageownerid',
				page_date = '$rpagedate',
				page_begin = '$rpagebegin',
				page_expire = '$rpageexpire',
				page_file = '".sed_sql_prep($rpagefile)."',
				page_url = '".sed_sql_prep($rpageurl)."',
				page_size = '".sed_sql_prep($rpagesize)."',
				page_count = '$rpagecount',
				page_allowcomments = '$rpageallowcomments',
				page_allowratings = '$rpageallowratings',
				page_filecount = '$rpagefilecount',
				page_alias = '".sed_sql_prep($rpagealias)."'
				WHERE page_id='$id'");

			/* === Hook === */
			$extp = sed_getextplugins('page.edit.update.done');
			if (is_array($extp))
				{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
			/* ===== */

			$sys['catcode'] = $rpagecat; //new in v175
      
      sed_log("Edited page #".$id,'adm');
			sed_redirect(sed_url("page", "id=".$id, "", true));
			exit;
			}
		}
	}

$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");
sed_die(sed_sql_numrows($sql)==0);
$pag = sed_sql_fetchassoc($sql);

$pag['page_date'] = sed_selectbox_date($pag['page_date'] + $usr['timezone'] * 3600,'long');
$pag['page_begin'] = sed_selectbox_date($pag['page_begin'] + $usr['timezone'] * 3600, 'long', '_beg');
$pag['page_expire'] = sed_selectbox_date($pag['page_expire'] + $usr['timezone'] * 3600, 'long', '_exp');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $pag['page_cat']);
sed_block($usr['isadmin']);

/* === Hook === */
$extp = sed_getextplugins('page.edit.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$page_form_delete = sed_inputbox("radio", "rpagedelete", 1, FALSE).$L['Yes']." ".sed_inputbox("radio", "rpagedelete", 0, TRUE).$L['No'];

$page_form_categories = sed_selectbox_categories($pag['page_cat'], 'rpagecat');

$page_form_type = "<select name=\"rpagetype\" size=\"1\">";
$selected0 = ($pag['page_type']==0) ? "selected=\"selected\"" : '';
$selected1 = ($pag['page_type']==1) ? "selected=\"selected\"" : '';
$page_form_type .= "<option value=\"0\" $selected0>".$L['Default']."</option>";
$page_form_type .= "<option value=\"1\" $selected1>HTML</option>";
$page_form_type .= "</select>"; 

if ($pag['page_file'])
	{ $page_form_file = sed_inputbox("radio", "rpagefile", 1, TRUE).$L['Yes']." ".sed_inputbox("radio", "rpagefile", 0, FALSE).$L['No']; }
	else
	{ $page_form_file = sed_inputbox("radio", "rpagefile", 1, FALSE).$L['Yes']." ".sed_inputbox("radio", "rpagefile", 0, TRUE).$L['No']; }

if ($pag['page_allowcomments'])
	{ $page_form_allowcomments = sed_inputbox("radio", "rpageallowcomments", 1, TRUE).$L['Yes']." ".sed_inputbox("radio", "rpageallowcomments", 0, FALSE).$L['No']; }
	else
	{ $page_form_allowcomments = sed_inputbox("radio", "rpageallowcomments", 1, FALSE).$L['Yes']." ".sed_inputbox("radio", "rpageallowcomments", 0, TRUE).$L['No']; }

if ($pag['page_allowratings'])
	{ $page_form_allowratings = sed_inputbox("radio", "rpageallowratings", 1, TRUE).$L['Yes']." ".sed_inputbox("radio", "rpageallowratings", 0, FALSE).$L['No']; }
	else
	{ $page_form_allowratings = sed_inputbox("radio", "rpageallowratings", 1, FALSE).$L['Yes']." ".sed_inputbox("radio", "rpageallowratings", 0, TRUE).$L['No']; }


if ($cfg['textmode']=='bbcode')
    {
      $bbcodes = ($cfg['parsebbcodepages']) ? sed_build_bbcodes('update', 'rpagetext', $L['BBcodes']) : '';
      $smilies = ($cfg['parsesmiliespages']) ? " &nbsp; ".sed_build_smilies('update', 'rpagetext', $L['Smilies'])." &nbsp; " : '';
    }
else { $bbcodes = ''; $smilies = ''; }

$pfs = sed_build_pfs($usr['id'], 'update', 'rpagetext', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; ".sed_build_pfs(0, 'update', 'rpagetext', $L['SFS']) : '';

$pfs_form_url_myfiles = (!$cfg['disable_pfs']) ? sed_build_pfs($usr['id'], "update", "rpageurl", $L['Mypfs']) : '';
$pfs_form_url_myfiles .= (sed_auth('pfs', 'a', 'A')) ? ' '.sed_build_pfs(0, 'update', 'rpageurl', $L['SFS']) : '';

$sys['sublocation'] = $sed_cat[$c]['title'];

/* === Hook === */
$extp = sed_getextplugins('page.edit.main');
if (is_array($extp))
	{ 
  foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

require("system/header.php");

$mskin = sed_skinfile(array('page', 'edit', $sed_cat[$pag['page_cat']]['tpl']));
$t = new XTemplate($mskin);

if (!empty($error_string))
	{
	$t->assign("PAGEEDIT_ERROR_BODY",$error_string);
	$t->parse("MAIN.PAGEEDIT_ERROR");
	}

if ($cfg['textmode']=='bbcode') 
  {
	$t->assign("PAGEEDIT_FORM_TYPE", $page_form_type);
	$t->parse("MAIN.PAGEEDIT_PARSING");
  }

if ($usr['isadmin'])  
	{ 
	$publish_title = ($pag['page_state'] == 0) ? $L['Putinvalidationqueue'] : $L['Validate'];
	$publish_state = ($pag['page_state'] == 0) ? "NO" : "OK";
	$t->assign(array(
		"PAGEEDIT_FORM_PUBLISH_STATE" => $publish_state,
		"PAGEEDIT_FORM_PUBLISH_TITLE" => $publish_title
	));
	$t->parse("MAIN.PAGEEDIT_PUBLISH"); 
	}

$t->assign(array(
	"PAGEEDIT_PAGETITLE" => $L['paged_title'],
	"PAGEEDIT_SUBTITLE" => $L['paged_subtitle'],
	"PAGEEDIT_FORM_SEND" => sed_url("page", "m=edit&a=update&id=".$pag['page_id']."&r=".$r."&".sed_xg()),
	"PAGEEDIT_FORM_ID" => $pag['page_id'],
	"PAGEEDIT_FORM_STATE" => $pag['page_state'],
	"PAGEEDIT_FORM_CAT" => $page_form_categories,
	"PAGEEDIT_FORM_KEY" => "<input type=\"text\" class=\"text\" name=\"rpagekey\" value=\"".sed_cc($pag['page_key'])."\" size=\"16\" maxlength=\"16\" />",
	"PAGEEDIT_FORM_ALIAS" => "<input type=\"text\" class=\"text\" name=\"rpagealias\" value=\"".sed_cc($pag['page_alias'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_EXTRA1" => "<input type=\"text\" class=\"text\" name=\"rpageextra1\" value=\"".sed_cc($pag['page_extra1'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_EXTRA2" => "<input type=\"text\" class=\"text\" name=\"rpageextra2\" value=\"".sed_cc($pag['page_extra2'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_EXTRA3" => "<input type=\"text\" class=\"text\" name=\"rpageextra3\" value=\"".sed_cc($pag['page_extra3'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_EXTRA4" => "<input type=\"text\" class=\"text\" name=\"rpageextra4\" value=\"".sed_cc($pag['page_extra4'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_EXTRA5" => "<input type=\"text\" class=\"text\" name=\"rpageextra5\" value=\"".sed_cc($pag['page_extra5'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_TITLE" => "<input type=\"text\" class=\"text\" name=\"rpagetitle\" value=\"".sed_cc($pag['page_title'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_DESC" => "<input type=\"text\" class=\"text\" name=\"rpagedesc\" value=\"".sed_cc($pag['page_desc'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_AUTHOR" => "<input type=\"text\" class=\"text\" name=\"rpageauthor\" value=\"".sed_cc($pag['page_author'])."\" size=\"32\" maxlength=\"24\" />",
	"PAGEEDIT_FORM_OWNERID" => "<input type=\"text\" class=\"text\" name=\"rpageownerid\" value=\"".sed_cc($pag['page_ownerid'])."\" size=\"32\" maxlength=\"24\" />",
	"PAGEEDIT_FORM_DATE" => $pag['page_date']." ".$usr['timetext'],
	"PAGEEDIT_FORM_DATENOW" => "<input type=\"checkbox\" class=\"checkbox\" name=\"rpagedatenow\" value=\"1\" />",
	"PAGEEDIT_FORM_BEGIN" => $pag['page_begin']." ".$usr['timetext'],
	"PAGEEDIT_FORM_EXPIRE" => $pag['page_expire']." ".$usr['timetext'],
	"PAGEEDIT_FORM_FILE" => $page_form_file,
	"PAGEEDIT_FORM_ALLOWRATINGS" => $page_form_allowratings,
	"PAGEEDIT_FORM_ALLOWCOMMENTS" => $page_form_allowcomments,
	"PAGEEDIT_FORM_URL" => "<input type=\"text\" class=\"text\" name=\"rpageurl\" value=\"".sed_cc($pag['page_url'])."\" size=\"56\" maxlength=\"255\" /> ".$pfs_form_url_myfiles,
	"PAGEEDIT_FORM_SIZE" => "<input type=\"text\" class=\"text\" name=\"rpagesize\" value=\"".sed_cc($pag['page_size'])."\" size=\"56\" maxlength=\"255\" />",
	"PAGEEDIT_FORM_PAGECOUNT" => "<input type=\"text\" class=\"text\" name=\"rpagecount\" value=\"".$pag['page_count']."\" size=\"8\" maxlength=\"8\" />",
	"PAGEEDIT_FORM_FILECOUNT" => "<input type=\"text\" class=\"text\" name=\"rpagefilecount\" value=\"".$pag['page_filecount']."\" size=\"8\" maxlength=\"8\" />",
	"PAGEEDIT_FORM_TEXT" => "<div><textarea name=\"rpagetext\" rows=\"".$cfg['textarea_default_height']."\" cols=\"".$cfg['textarea_default_width']."\">".sed_cc(sed_checkmore($pag['page_text'], false), ENT_QUOTES)."</textarea></div>".$bbcodes." ".$smilies." ".$pfs,
	"PAGEEDIT_FORM_TEXT2" => "<div><textarea name=\"rpagetext2\" rows=\"".$cfg['textarea_default_height']."\" cols=\"".$cfg['textarea_default_width']."\">".sed_cc(sed_checkmore($pag['page_text2'], false), ENT_QUOTES)."</textarea>",
	"PAGEEDIT_FORM_TEXTBOXER" => "<div><textarea name=\"rpagetext\" rows=\"".$cfg['textarea_default_height']."\" cols=\"".$cfg['textarea_default_width']."\">".sed_cc(sed_checkmore($pag['page_text'], false), ENT_QUOTES)."</textarea></div>".$bbcodes." ".$smilies." ".$pfs,
	"PAGEEDIT_FORM_BBCODES" => $bbcodes,
	"PAGEEDIT_FORM_SMILIES" => $smilies,	
  "PAGEEDIT_FORM_MYPFS" => $pfs,
	"PAGEEDIT_FORM_DELETE" => $page_form_delete
		));

/* === Hook === */
$extp = sed_getextplugins('page.edit.tags');

if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require("system/footer.php");

?>
