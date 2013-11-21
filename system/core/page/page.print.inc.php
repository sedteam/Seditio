<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=page.print.inc.php
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
$al = sed_import('al','G','ALP');
$r = sed_import('r','G','ALP');
$c = sed_import('c','G','TXT');
$pg = sed_import('pg','G','INT');

$comments = sed_import('comments','G','BOL');
$ratings = sed_import('ratings','G','BOL');

/* === Hook === */
$extp = sed_getextplugins('page.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if (!empty($al))
	{ $sql = sed_sql_query("SELECT p.*, u.user_name, u.user_avatar, u.user_maingrp FROM $db_pages AS p
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_alias='$al' LIMIT 1"); }
else
	{ $sql = sed_sql_query("SELECT p.*, u.user_name, u.user_avatar, u.user_maingrp FROM $db_pages AS p
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
		WHERE page_id='$id'"); }

sed_die(sed_sql_numrows($sql)==0);
$pag = sed_sql_fetchassoc($sql);

$pag['page_date'] = @date($cfg['dateformat'], $pag['page_date'] + $usr['timezone'] * 3600);
$pag['page_begin'] = @date($cfg['dateformat'], $pag['page_begin'] + $usr['timezone'] * 3600);
$pag['page_expire'] = @date($cfg['dateformat'], $pag['page_expire'] + $usr['timezone'] * 3600);
$pag['page_tab'] = (empty($pg)) ? 0 : $pg;
$sys['catcode'] = $pag['page_cat'];
$pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url("page", "id=".$pag['page_id']) : sed_url("page", "al=".$pag['page_alias']);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $pag['page_cat']);
sed_block($usr['auth_read']);

if ($pag['page_state']==1 && !$usr['isadmin'])
	{
	sed_log("Attempt to directly access an un-validated page", 'sec');
	sed_redirect(sed_url("message", "msg=930", "", true));
	exit;
	}

if (mb_substr($pag['page_text'], 0, 6) == 'redir:')
	{
	$redir = str_replace('redir:', '', trim($pag['page_text']));
	$sql = sed_sql_query("UPDATE $db_pages SET page_filecount=page_filecount+1 WHERE page_id='".$pag['page_id']."'");
	sed_redirect($redir);
	exit;
	}
elseif (mb_substr($pag['page_text'], 0, 8)=='include:')
	{
	$pag['page_text'] = sed_readraw('datas/html/'.trim(mb_substr($pag['page_text'], 8, 255)));
	}

if($pag['page_file'] && $a=='dl')
	{
	$file_size = @filesize($row['page_url']);
	$pag['page_filecount']++;
	$sql = sed_sql_query("UPDATE $db_pages SET page_filecount=page_filecount+1 WHERE page_id='".$pag['page_id']."'");
	sed_redirect($pag['page_url']);
	echo("<script type='text/javascript'>location.href='".$pag['page_url']."';</script>Redirecting...");
	exit;
	}

if ($n!='modup' && $n!='moddown' && $n!='delete') 
  {
  $pag['page_count']++;
  $sql = sed_sql_query("UPDATE $db_pages SET page_count='".$pag['page_count']."' WHERE page_id='".$pag['page_id']."'");
  }

// Multitabs, modify in Sed v175

$pag['page_tabs'] = explode('[newpage]', $pag['page_text'], 99);
$pag['page_totaltabs'] = count($pag['page_tabs']);

if ($pag['page_totaltabs']>1)
	{
	if (empty($pag['page_tabs'][0]))
		{
		$remove = array_shift($pag['page_tabs']);
		$pag['page_totaltabs']--;
		}
	$pag['page_tab'] = ($pag['page_tab']>$pag['page_totaltabs']) ? 1 : $pag['page_tab'];
	$pag['page_tabtitles'] = array();
	$pag['page_tabselect'].= "<select name=\"tabjump\" size=\"1\" onchange=\"redirect(this)\">";

	for ($i = 0; $i < $pag['page_totaltabs']; $i++)
		{
		$p1 = mb_strpos($pag['page_tabs'][$i], '[title]');
		$p2 = mb_strpos($pag['page_tabs'][$i], '[/title]');

		if ($p2 > $p1 && $p1 <4)
			{
			$pag['page_tabtitle'][$i] = mb_substr($pag['page_tabs'][$i], $p1+7, ($p2-$p1)-7);
			if ($i == $pag['page_tab'])
				{
				$pag['page_tabs'][$i] = trim(str_replace('[title]'.$pag['page_tabtitle'][$i].'[/title]', '', $pag['page_tabs'][$i]));
				}
			}
		else
			{ $pag['page_tabtitle'][$i] = $i == 1 ? $pag['page_title'] : $L['Page'] . ' ' . ($i + 1); }
		
    $tab_url = empty($pag['page_alias']) ? sed_url("page", "id=".$pag['page_id']."&pg=".$i) : sed_url("page", "al=".$pag['page_alias']."&pg=".($i+1));     
    
    $pag['page_tabtitles'][] .= "<a href=\"".$tab_url."\">".($i+1).". ".$pag['page_tabtitle'][$i]."</a>";		
		$pag['page_tabs'][$i] = trim(str_replace('[newpage]', '', $pag['page_tabs'][$i]));
		$selected = ($i==$pag['page_tab']) ? "selected=\"selected\"" : '';
		$pag['page_tabselect'] .= "<option $selected value=\"".$tab_url."\">".($i+1)." - ".$pag['page_tabtitle'][$i]."</option>";	
  	}

	$pag['page_tabtitles'] = implode('<br />', $pag['page_tabtitles']);
	$pag['page_text'] = $pag['page_tabs'][$pag['page_tab']];
	$pag['page_tabselect'] .= "</select>";
  
  $pag['page_tabnav'] = sed_pagination($pag['page_pageurl'], $pag['page_tab'], $pag['page_totaltabs'], 1, 'pg');  
  list($pag['page_tabprev'], $pag['page_tabnext']) = sed_pagination_pn($pag['page_pageurl'], $pag['page_tab'], $pag['page_totaltabs'], 1, true, 'pg');	
  }

$catpath = sed_build_catpath($pag['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"); 

$pag['page_fulltitle'] = $catpath." ".$cfg['separator']." <a href=\"".$pag['page_pageurl']."\">".$pag['page_title']."</a>";
$pag['page_fulltitle'] .= ($pag['page_totaltabs']>1 && !empty($pag['page_tabtitle'][$pag['page_tab']])) ? " (".$pag['page_tabtitle'][$pag['page_tab']].")" : '';

$item_code = 'p'.$pag['page_id'];

// Options for category New v173
$allowcommentscat = $sed_cat[$pag['page_cat']]['allowcomments'];
$allowratingscat = $sed_cat[$pag['page_cat']]['allowratings'];

// Options for page New v173
$allowcommentspage = $pag['page_allowcomments'];
$allowratingspage = $pag['page_allowratings'];

$comments = $cfg['showcommentsonpage'] ? $cfg['showcommentsonpage'] : $comments;

//fix for sed_url()
$url_param = (empty($pag['page_alias'])) ? "id=".$pag['page_id'] : "al=".$pag['page_alias'];
$url_page = array('part' => 'page', 'params' => $url_param);

if ($allowcommentscat) { list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_page, $comments, $allowcommentspage); }
if ($allowratingscat) { list($ratings_link, $ratings_display) = sed_build_ratings($item_code, $url_page, $ratings, $allowratingspage); }

$sys['sublocation'] = $sed_cat[$c]['title'];
$out['subtitle'] = $pag['page_title'];
$out['canonical_url'] = $pag['page_pageurl'];

/* === Hook === */
$extp = sed_getextplugins('page.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$out['metas'] = sed_htmlmetas().$moremetas;
$out['compopup'] = sed_javascript($morejavascript);
$out['fulltitle'] = $cfg['maintitle'];
$out['subtitle'] = (empty($out['subtitle'])) ? $cfg['subtitle'] : $out['subtitle'];
$out['fulltitle'] .= (empty($out['subtitle'])) ? '' : ' - '.$out['subtitle'];
$out['canonical_url'] = empty($out['canonical_url']) ? str_replace('&', '&amp;', $sys['canonical_url']) : $sys['abs_url'].$out['canonical_url'];  // New in 175

sed_sendheaders();

$mskin = "skins/".$usr['skin']."/print.page.tpl";  
$t = new XTemplate($mskin);

$t->assign(array(
	"PRINT_HEADER_TITLE" => $out['fulltitle'],
	"PRINT_HEADER_MAINTITLE" => $cfg['maintitle'],
	"PRINT_HEADER_SUBTITLE" => $out['subtitle'],
	"PRINT_HEADER_METAS" => $out['metas'],
	"PRINT_HEADER_DOCTYPE" => $cfg['doctype'],
  "PRINT_HEADER_CANONICAL_URL" => $out['canonical_url']
  ));
  
$t->parse("PRINT.HEADER");  


$t->assign(array( 
	"PRINT_PAGE_ID" => $pag['page_id'],
	"PRINT_PAGE_STATE" => $pag['page_state'],
	"PRINT_PAGE_EXECUTE" => $pag['page_execute'],
	"PRINT_PAGE_TITLE" => $pag['page_fulltitle'],
	"PRINT_PAGE_SHORTTITLE" => $pag['page_title'],
	"PRINT_PAGE_CAT" => $pag['page_cat'],
	"PRINT_PAGE_CATTITLE" => $sed_cat[$pag['page_cat']]['title'],
	"PRINT_PAGE_CATPATH" => $catpath,
	"PRINT_PAGE_CATDESC" => $sed_cat[$pag['page_cat']]['desc'],
	"PRINT_PAGE_CATICON" => $sed_cat[$pag['page_cat']]['icon'],
	"PRINT_PAGE_KEY" => $pag['page_key'],
	"PRINT_PAGE_EXTRA1" => $pag['page_extra1'],
	"PRINT_PAGE_EXTRA2" => $pag['page_extra2'],
	"PRINT_PAGE_EXTRA3" => $pag['page_extra3'],
	"PRINT_PAGE_EXTRA4" => $pag['page_extra4'],
	"PRINT_PAGE_EXTRA5" => $pag['page_extra5'],
	"PRINT_PAGE_DESC" => $pag['page_desc'],
	"PRINT_PAGE_AUTHOR" => $pag['page_author'],
	"PRINT_PAGE_OWNER" => sed_build_user($pag['page_ownerid'], sed_cc($pag['user_name']), $pag['user_maingrp']),
	"PRINT_PAGE_AVATAR" => sed_build_userimage($pag['user_avatar']),
	"PRINT_PAGE_DATE" => $pag['page_date'],
	"PRINT_PAGE_BEGIN" => $pag['page_begin'],
	"PRINT_PAGE_EXPIRE" => $pag['page_expire'],
	"PRINT_PAGE_COMMENTS" => $comments_link,
	"PRINT_PAGE_COMMENTS_DISPLAY" => $comments_display,
	"PRINT_PAGE_COMMENTS_COUNT" => $comments_count,
	"PRINT_PAGE_RATINGS" => $ratings_link,
	"PRINT_PAGE_RATINGS_DISPLAY" => $ratings_display
		));

	if($pag['page_totaltabs']>1)
		{
		$t->assign(array(
			"PRINT_PAGE_MULTI_TABNAV" => $pag['page_tabnav'],
			"PRINT_PAGE_MULTI_TABTITLES" => $pag['page_tabtitles'],
			"PRINT_PAGE_MULTI_MAXTAB" => $pag['page_totaltabs'],
			"PRINT_PAGE_MULTI_SELECT" => $pag['page_tabselect'],
			"PRINT_PAGE_MULTI_PREV" => $pag['page_tabprev'],
			"PRINT_PAGE_MULTI_NEXT" => $pag['page_tabnext']

				));
		$t->parse("PRINT.PAGE_MULTI");
		}

	if ($usr['isadmin'])
		{
		$t-> assign(array(
			"PRINT_PAGE_ADMIN_COUNT" => $pag['page_count'],
			"PRINT_PAGE_ADMIN_UNVALIDATE" => "<a href=\"".sed_url("admin", "m=page&a=unvalidate&id=".$pag['page_id']."&".sed_xg())."\">".$L['Putinvalidationqueue']."</a>",
			"PRINT_PAGE_ADMIN_EDIT" => "<a href=\"".sed_url("page", "m=edit&id=".$pag['page_id']."&r=list")."\">".$L['Edit']."</a>"
			));

		$t->parse("PRINT.PAGE_ADMIN");
		}

	$ishtml_page = ($pag['page_type']==1 || $pag['page_text_ishtml']);	
	$pag['page_text'] = sed_parse($pag['page_text'], $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1, $ishtml_page);
	$pag['page_text2'] = sed_parse($pag['page_text2'], $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1, $ishtml_page);

	if (!$pag['page_text_ishtml'] && $cfg['textmode']=='html')
		{
		 $sql2 = sed_sql_query("UPDATE $db_pages SET page_text_ishtml=1, page_type=1, page_text='".sed_sql_prep($pag['page_text'])."', 
								page_text2='".sed_sql_prep($pag['page_text2'])."' WHERE page_id=".$pag['page_id']);
		}				
	$t->assign("PRINT_PAGE_TEXT", $pag['page_text']);
	$t->assign("PRINT_PAGE_TEXT2", $pag['page_text2']);		

	if($pag['page_file'])
		{
		if (!empty($pag['page_url']))
			{
			$dotpos = mb_strrpos($pag['page_url'],".")+1;
			$pag['page_fileicon'] = "system/img/pfs/".mb_strtolower(mb_substr($pag['page_url'], $dotpos, 5)).".gif";
			if (!file_exists($pag['page_fileicon']))
				{ $pag['page_fileicon'] = "system/img/admin/page.png"; }
			$pag['page_fileicon'] = "<img src=\"".$pag['page_fileicon']."\" alt=\"\">";
			}
		else
			{ $pag['page_fileicon'] = ''; }

		$t->assign(array(
			"PRINT_PAGE_FILE_URL" => sed_url("page", "id=".$pag['page_id']."&a=dl"),
			"PRINT_PAGE_FILE_SIZE" => $pag['page_size'],
			"PRINT_PAGE_FILE_COUNT" => $pag['page_filecount'],
			"PRINT_PAGE_FILE_ICON" => $pag['page_fileicon'],
			"PRINT_PAGE_FILE_NAME" => basename($pag['page_url'])
				));
		$t->parse("PRINT.PAGE_FILE");
		}

/* === Hook === */
$extp = sed_getextplugins('page.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */


$t->assign(array (
	"PRINT_FOOTER_BOTTOMLINE" => $out['bottomline'],
	"PRINT_FOOTER_COPYRIGHT" => $out['copyright']
  ));
  
$t->parse("PRINT.FOOTER");  


$t->parse("PRINT");
$t->out("PRINT");

@ob_end_flush();
@ob_end_flush();

sed_sql_close($connection_id);

?>