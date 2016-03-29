<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=page.inc.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Neocrome
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','INT');
$r = sed_import('r','G','ALP');
$c = sed_import('c','G','TXT');

$newpageallowcomments = 1;
$newpageallowratings = 1;


list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', 'any');
sed_block($usr['auth_write']);

/* === Hook === */
$extp = sed_getextplugins('page.add.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if ($a=='add')
	{
	sed_shield_protect();

	/* === Hook === */
	$extp = sed_getextplugins('page.add.add.first');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$newpagecat = sed_import('newpagecat','P','TXT');
	$newpagekey = sed_import('newpagekey','P','TXT');
	$newpagealias = sed_replacespace(sed_import('newpagealias','P','ALS'));  //New in175
	$newpageextra1 = sed_import('newpageextra1','P','TXT');
 	$newpageextra2 = sed_import('newpageextra2','P','TXT');
	$newpageextra3 = sed_import('newpageextra3','P','TXT');
 	$newpageextra4 = sed_import('newpageextra4','P','TXT');
	$newpageextra5 = sed_import('newpageextra5','P','HTM');
  
	$newpageextra6 = sed_import('newpageextra6','P','HTM'); 
	$newpageextra7 = sed_import('newpageextra7','P','HTM');  
	$newpageextra8 = sed_import('newpageextra8','P','HTM'); 
	$newpageextra9 = sed_import('newpageextra9','P','HTM');
	$newpageextra10 = sed_import('newpageextra10','P','HTM');

	$newpageprice = sed_import('newpageprice','P','TXT');
	$newpagethumb = sed_import('newpagethumb','P','TXT');  
      
	$newpagetitle = sed_import('newpagetitle','P','TXT');
	$newpagedesc = sed_import('newpagedesc','P','TXT');
	$newpagetext = sed_import('newpagetext','P','HTM');
	$newpagetext2 = sed_import('newpagetext2','P','HTM');
	$newpageauthor = sed_import('newpageauthor','P','TXT');
	$newpagefile = sed_import('newpagefile','P','TXT');
	$newpageurl = sed_import('newpageurl','P','TXT');
	$newpagesize = sed_import('newpagesize','P','TXT');
	$newpageyear_beg = sed_import('ryear_beg','P','INT');
	$newpagemonth_beg = sed_import('rmonth_beg','P','INT');
	$newpageday_beg = sed_import('rday_beg','P','INT');
	$newpagehour_beg = sed_import('rhour_beg','P','INT');
	$newpageminute_beg = sed_import('rminute_beg','P','INT');
	$newpageyear_exp = sed_import('ryear_exp','P','INT');
	$newpagemonth_exp = sed_import('rmonth_exp','P','INT');
	$newpageday_exp = sed_import('rday_exp','P','INT');
	$newpagehour_exp = sed_import('rhour_exp','P','INT');
	$newpageminute_exp = sed_import('rminute_exp','P','INT');
	
	$newpageseotitle = sed_import('newpageseotitle','P','TXT');
	$newpageseodesc = sed_import('newpageseodesc','P','TXT');
	$newpageseokeywords = sed_import('newpageseokeywords','P','TXT');
		
	$newpageallowcomments = sed_import('newpageallowcomments','P','BOL');
	$newpageallowratings = sed_import('newpageallowratings','P','BOL');
  
	$newpageallowcomments  = (empty($newpageallowcomments) && $newpageallowcomments != 0) ? 1 : $newpageallowcomments ;  //Fix 175
	$newpageallowratings = (empty($newpageallowratings) && $newpageallowratings != 0) ? 1 : $newpageallowratings; //Fix 175
		
	$newpagebegin = sed_mktime($newpagehour_beg, $newpageminute_beg, 0, $newpagemonth_beg, $newpageday_beg, $newpageyear_beg) - $usr['timezone'] * 3600;
	$newpageexpire = sed_mktime($newpagehour_exp, $newpageminute_exp, 0, $newpagemonth_exp, $newpageday_exp, $newpageyear_exp) - $usr['timezone'] * 3600;
	$newpageexpire = ($newpageexpire <= $newpagebegin) ? 1861916400 : $newpageexpire;
	$newpagebegin = ($newpagebegin < 0) ? 0 : $newpagebegin;
  
	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $newpagecat);
	sed_block($usr['auth_write']);

	//Parsing for BBCode Mode New v173
	$newpagetype = ($usr['isadmin'] && ($cfg['textmode']=='bbcode')) ? sed_import('newpagetype','P','INT') : 0;  // New v173
	if ($cfg['textmode']=='html') { $newpagetype = 1; }  // New v173

  //Autovalidation New v173
	$newpagepublish = sed_import('newpagepublish', 'P', 'ALP');
	$newpagestate = (($newpagepublish == "OK") && $usr['isadmin']) ? 0 : 1;

	$error_string .= (empty($newpagecat)) ? $L['pag_catmissing']."<br />" : '';
	$error_string .= (mb_strlen($newpagetitle)<2) ? $L['pag_titletooshort']."<br />" : '';

	if (empty($error_string))
		{
		if (!empty($newpagealias))
			{     
			$sql = sed_sql_query("SELECT page_id FROM $db_pages WHERE page_alias='".sed_sql_prep($newpagealias)."'");
			$newpagealias = (sed_sql_numrows($sql)>0) ? "alias".rand(1000,9999) : $newpagealias;
			}

		$sql = sed_sql_query("INSERT into $db_pages
			(page_state,
			page_type,
			page_cat,
			page_key,
			page_extra1,
			page_extra2,
			page_extra3,
			page_extra4,
			page_extra5,
			page_extra6,
			page_extra7,
			page_extra8,
			page_extra9,
			page_extra10,      
			page_title,
			page_desc,
			page_text,
			page_text_ishtml,
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
			page_price,
			page_thumb
			)
			VALUES
			(".(int)$newpagestate.",
			".(int)$newpagetype.",
			'".sed_sql_prep($newpagecat)."',
			'".sed_sql_prep($newpagekey)."',
			'".sed_sql_prep($newpageextra1)."',
			'".sed_sql_prep($newpageextra2)."',
			'".sed_sql_prep($newpageextra3)."',
			'".sed_sql_prep($newpageextra4)."',
			'".sed_sql_prep($newpageextra5)."',
			'".sed_sql_prep($newpageextra6)."',
			'".sed_sql_prep($newpageextra7)."',
			'".sed_sql_prep($newpageextra8)."',
			'".sed_sql_prep($newpageextra9)."',
			'".sed_sql_prep($newpageextra10)."',      
			'".sed_sql_prep($newpagetitle)."',
			'".sed_sql_prep($newpagedesc)."',
			'".sed_sql_prep(sed_checkmore($newpagetext, true))."',
			".(int)$ishtml.",
			'".sed_sql_prep(sed_checkmore($newpagetext2, true))."',
			'".sed_sql_prep($newpageauthor)."',
			".(int)$usr['id'].",
			".(int)$sys['now_offset'].",
			".(int)$newpagebegin.",
			".(int)$newpageexpire.",
			".(int)$newpagefile.",
			'".sed_sql_prep($newpageurl)."',
			'".sed_sql_prep($newpagesize)."',
			'".sed_sql_prep($newpagealias)."',
			".(int)$newpageallowcomments.",      
			".(int)$newpageallowratings.",
			'".sed_sql_prep($newpageseotitle)."',
			'".sed_sql_prep($newpageseodesc)."',			
			'".sed_sql_prep($newpageseokeywords)."',
			'".sed_sql_prep($newpageprice)."',      
			'".sed_sql_prep($newpagethumb)."')");

		/* === Hook === */
		$extp = sed_getextplugins('page.add.add.done');
		if (is_array($extp))
			{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */

		sed_shield_update(30, "New page");
		
		if (defined('SED_ADMIN')) { sed_redirect(sed_url("admin", "m=page&s=manager&c=".$newpagecat."&msg=300", "", true)); }
		else { sed_redirect(sed_url("message", "msg=300", "", true)); }
		
		exit;
		}
	}

if (($a=='clone') && ($id > 0))
	{		
	$sql1 = sed_sql_query("SELECT * FROM $db_pages WHERE page_id='$id' LIMIT 1");
	sed_die(sed_sql_numrows($sql1)==0);
	$row1 = sed_sql_fetchassoc($sql1);

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row1['page_cat']);
	sed_block($usr['isadmin']);

	/* === Hook === */
	$extp = sed_getextplugins('page.add.clone');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */
	
	$newpagecat = $row1['page_cat'];
	$newpagekey = $row1['page_key'];
	$newpagealias = $row1['page_alias'];
	$newpageextra1 = $row1['page_extra1'];
 	$newpageextra2 = $row1['page_extra2'];
	$newpageextra3 = $row1['page_extra3'];
 	$newpageextra4 = $row1['page_extra4'];
	$newpageextra5 = $row1['page_extra5'];
  
	$newpageextra6 = $row1['page_extra6'];
 	$newpageextra7 = $row1['page_extra7'];
	$newpageextra8 = $row1['page_extra8'];
 	$newpageextra9 = $row1['page_extra9'];
	$newpageextra10 = $row1['page_extra10'];
  
	$newpageprice = $row1['page_price']; 
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
	}		

if (empty($newpagecat) && !empty($c))
{
	$newpagecat = $c;
	$usr['isadmin'] = sed_auth('page', $newpagecat, 'A');
}

$pageadd_form_file = sed_radiobox("newpagefile", $yesno_arr, $newpagefile);
$pageadd_form_allowcomments = sed_radiobox("newpageallowcomments", $yesno_arr, $newpageallowcomments); 
$pageadd_form_allowratings = sed_radiobox("newpageallowratings", $yesno_arr, $newpageallowratings);

if ($usr['isadmin'] && ($cfg['textmode']=='bbcode'))
{
  $page_form_type = "<select name=\"newpagetype\" size=\"1\">";
  $selected0 = ($newpagetype==0) ? "selected=\"selected\"" : '';
  $selected1 = ($newpagetype==1) ? "selected=\"selected\"" : '';
  $page_form_type .= "<option value=\"0\" $selected0>".$L['Default']."</option>";
  $page_form_type .= "<option value=\"1\" $selected1>HTML</option>";
  $page_form_type .= "</select>"; 
}

$pageadd_form_categories = sed_selectbox_categories($newpagecat, 'newpagecat');
$newpage_form_begin = sed_selectbox_date($sys['now_offset']+$usr['timezone']*3600, 'long', '_beg');
$newpage_form_expire = sed_selectbox_date(1861916400, 'long', '_exp');

if ($cfg['textmode']=='bbcode')
    {
    $bbcodes = ($cfg['parsebbcodepages']) ? sed_build_bbcodes('newpage', 'newpagetext',$L['BBcodes']) : '';
    $smilies = ($cfg['parsesmiliespages']) ? " &nbsp; ".sed_build_smilies('newpage', 'newpagetext',$L['Smilies'])." &nbsp; " : '';
    }
else { $bbcodes = ''; $smilies = ''; }

$pfs = sed_build_pfs($usr['id'], 'newpage', 'newpagetext',$L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; ".sed_build_pfs(0, 'newpage', 'newpagetext', $L['SFS']) : '';

$pfs_form_url_myfiles = (!$cfg['disable_pfs']) ? sed_build_pfs($usr['id'], "newpage", "newpageurl", $L['Mypfs']) : '';
$pfs_form_url_myfiles .= (sed_auth('pfs', 'a', 'A')) ? ' '.sed_build_pfs(0, 'newpage', 'newpageurl', $L['SFS']) : '';

$sys['sublocation'] = $sed_cat[$c]['title'];

$out['subtitle'] = $L['pagadd_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('pagetitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('page.add.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if (defined('SED_ADMIN'))
	{
	$mskin = sed_skinfile(array('admin', 'page', 'add', $sed_cat[$newpagecat]['tpl']), true);	
	}
else 
	{
	require("system/header.php");
	$mskin = sed_skinfile(array('page', 'add', $sed_cat[$newpagecat]['tpl']));	
	}

$t = new XTemplate($mskin);

if (!empty($error_string))
	{
	$t->assign("PAGEADD_ERROR_BODY",$error_string);
	$t->parse("MAIN.PAGEADD_ERROR");
	}

if ($usr['isadmin'] && ($cfg['textmode']=='bbcode'))
  {
	$t->assign("PAGEADD_FORM_TYPE", $page_form_type);
	$t->parse("MAIN.PAGEADD_PARSING");
  }

if ($usr['isadmin'])  { $t->parse("MAIN.PAGEADD_PUBLISH"); }

$form_send_url = (defined('SED_ADMIN')) ? sed_url("admin", "m=page&s=add&a=add") : sed_url("page", "m=add&a=add");

$t->assign(array(
	"PAGEADD_PAGETITLE" => $L['pagadd_title'],
	"PAGEADD_SUBTITLE" => $L['pagadd_subtitle'],
	"PAGEADD_ADMINEMAIL" => "mailto:".$cfg['adminemail'],
	"PAGEADD_FORM_SEND" => $form_send_url,
	"PAGEADD_FORM_CAT" => $pageadd_form_categories,
	"PAGEADD_FORM_KEY" => sed_textbox('newpagekey', $newpagekey, 16, 16),
	"PAGEADD_FORM_ALIAS" => sed_textbox('newpagealias', $newpagealias),
	"PAGEADD_FORM_EXTRA1" => sed_textbox('newpageextra1', $newpageextra1),
	"PAGEADD_FORM_EXTRA2" => sed_textbox('newpageextra2', $newpageextra2),
	"PAGEADD_FORM_EXTRA3" => sed_textbox('newpageextra3', $newpageextra3),
	"PAGEADD_FORM_EXTRA4" => sed_textbox('newpageextra4', $newpageextra4),
	"PAGEADD_FORM_EXTRA5" => sed_textbox('newpageextra5', $newpageextra5),
	"PAGEADD_FORM_EXTRA6" => sed_textbox('newpageextra6', $newpageextra6),
	"PAGEADD_FORM_EXTRA7" => sed_textbox('newpageextra7', $newpageextra7),
	"PAGEADD_FORM_EXTRA8" => sed_textbox('newpageextra8', $newpageextra8),
	"PAGEADD_FORM_EXTRA9" => sed_textbox('newpageextra9', $newpageextra9),
	"PAGEADD_FORM_EXTRA10" => sed_textbox('newpageextra10', $newpageextra10),  
	"PAGEADD_FORM_TITLE" => sed_textbox('newpagetitle', $newpagetitle),
	"PAGEADD_FORM_DESC" => sed_textbox('newpagedesc', $newpagedesc),
	"PAGEADD_FORM_SEOTITLE" => sed_textbox('newpageseotitle', $newpageseotitle),
	"PAGEADD_FORM_SEODESC" => sed_textbox('newpageseodesc', $newpageseodesc),
	"PAGEADD_FORM_SEOKEYWORDS" => sed_textbox('newpageseokeywords', $newpageseokeywords),
 	"PAGEADD_FORM_PRICE" => sed_textbox('newpageprice', $newpageprice, 16, 16),
	"PAGEADD_FORM_THUMB" => sed_textbox('newpagethumb', $newpagethumb),
	"PAGEADD_FORM_AUTHOR" => sed_textbox('newpageauthor', $newpageauthor, 16, 24),
	"PAGEADD_FORM_OWNER" => sed_build_user($usr['id'], sed_cc($usr['name'])),
	"PAGEADD_FORM_OWNERID" => $usr['id'],
	"PAGEADD_FORM_BEGIN" => $newpage_form_begin,
	"PAGEADD_FORM_EXPIRE" => $newpage_form_expire,
	"PAGEADD_FORM_FILE" => $pageadd_form_file,
	"PAGEADD_FORM_ALLOWRATINGS" => $pageadd_form_allowratings,
	"PAGEADD_FORM_ALLOWCOMMENTS" => $pageadd_form_allowcomments,
	"PAGEADD_FORM_URL" => sed_textbox('newpageurl', $newpageurl)." ".$pfs_form_url_myfiles,
	"PAGEADD_FORM_SIZE" => sed_textbox('newpagesize', $newpagesize),
	"PAGEADD_FORM_TEXT" => sed_textarea('newpagetext', $newpagetext, $cfg['textarea_default_height'], $cfg['textarea_default_width']).$bbcodes." ".$smilies." ".$pfs,
	"PAGEADD_FORM_TEXT2" => sed_textarea('newpagetext2', $newpagetext2, $cfg['textarea_default_height'], $cfg['textarea_default_width']),
	"PAGEADD_FORM_TEXTBOXER" => sed_textarea('newpagetext', $newpagetext, $cfg['textarea_default_height'], $cfg['textarea_default_width']).$bbcodes." ".$smilies." ".$pfs,
	"PAGEADD_FORM_BBCODES" => $bbcodes,
	"PAGEADD_FORM_SMILIES" => $smilies,
	"PAGEADD_FORM_MYPFS" => $pfs
		));

/* === Hook === */
$extp = sed_getextplugins('page.add.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if (defined('SED_ADMIN'))
	{
	$t -> parse("MAIN"); 
	$adminmain = $t -> text("MAIN");
	}
else 
	{
	$t->parse("MAIN");
	$t->out("MAIN");
	require("system/footer.php");
	}
?>
