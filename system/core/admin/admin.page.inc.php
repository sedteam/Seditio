<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=admin.page.inc.php
Version=178
Updated=2012-may-31
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'any');
sed_block($usr['isadmin']);

$mn = sed_import('mn', 'G', 'TXT');

$adminpath[] = array (sed_url("admin", "m=page"), $L['Pages']);

//$adminmain = "<h2><img src=\"system/img/admin/pages.png\" alt=\"\" /> ".$L['Pages']."</h2>";

$totaldbpages = sed_sql_rowcount($db_pages);
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
$sys['pagesqueued'] = sed_sql_result($sql,0,'COUNT(*)');
                     
$structure_mode[0] = $L['Default'];
$structure_mode[1] = $L['Group'];
$structure_mode[2] = $L['Sub'];

$t = new XTemplate(sed_skinfile('admin.page', true));

switch($mn)
	{
  case 'structure':
  
    // ============= Structure ==============================
    
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
    sed_block($usr['isadmin']);
    
    $adminpath[] = array (sed_url("admin", "m=page&mn=structure"), $L['Structure']);

   // $adminmain .= "<h4>".$L['Structure']."</h4>";
    
    if ($n=='options')
    	{
    	if ($a=='update')
    		{
    		$rpath = sed_import('rpath','P','TXT');
    		$rtitle = sed_import('rtitle','P','TXT');
    		$rtplmode = sed_import('rtplmode','P','INT');
    		$rdesc = sed_import('rdesc','P','TXT');
			$rstext = sed_import('rstext','P','HTM'); //New v175
    		$ricon = sed_import('ricon','P','TXT');
    		$rgroup = sed_import('rgroup','P','BOL');
    		
    		$rallowcomments = sed_import('rallowcomments', 'P', 'BOL');  //New v173
    		$rallowratings = sed_import('rallowratings', 'P', 'BOL');  //New v173
    		
        if ($rtplmode==1)
    		  { $rtpl = ''; }
        elseif ($rtplmode==3)
          { $rtpl = 'same_as_parent'; }
        else
          { $rtpl = sed_import('rtplforced','P','ALS'); }
    
    		$sql = sed_sql_query("UPDATE $db_structure SET
    			structure_path='".sed_sql_prep($rpath)."',
    			structure_tpl='".sed_sql_prep($rtpl)."',
    			structure_title='".sed_sql_prep($rtitle)."',
    			structure_desc='".sed_sql_prep($rdesc)."',
    			structure_text='".sed_sql_prep($rstext)."',
    			structure_text_ishtml=".(int)$ishtml.",
    			structure_icon='".sed_sql_prep($ricon)."',
    			structure_allowcomments='".sed_sql_prep($rallowcomments)."',
    			structure_allowratings='".sed_sql_prep($rallowratings)."',
    			structure_group='".$rgroup."'
    			WHERE structure_id='".$id."'");
    
    		  sed_cache_clear('sed_cat');
    		  sed_redirect(sed_url("admin", "m=page&mn=structure&msg=917", "", true));
    		  exit;
    		  }
    
        $sql = sed_sql_query("SELECT * FROM $db_structure WHERE structure_id='$id' LIMIT 1");
        sed_die(sed_sql_numrows($sql)==0);
    
        $handle=opendir("skins/".$cfg['defaultskin']."/");
        $allskinfiles = array();
    
        while ($f = readdir($handle))
          {
          if (($f != ".") && ($f != "..") && mb_strtolower(mb_substr($f, mb_strrpos($f, '.')+1, 4))=='tpl')
            { $allskinfiles[] = $f; }
          }
        closedir($handle);
    
        $allskinfiles = implode (',', $allskinfiles);
    
        $row = sed_sql_fetchassoc($sql);
    
        $structure_id = $row['structure_id'];
        $structure_code = $row['structure_code'];
        $structure_path = $row['structure_path'];
        $structure_title = $row['structure_title'];
        $structure_desc = $row['structure_desc'];
        $structure_text = $row['structure_text'];  //New v175
        $structure_icon = $row['structure_icon'];
        $structure_group = $row['structure_group'];
        
        $structure_allowcomments = $row['structure_allowcomments'];
        $structure_allowratings = $row['structure_allowratings'];
        
        $form_allowcomments = ($structure_allowcomments) ? "<input type=\"radio\" class=\"radio\" name=\"rallowcomments\" value=\"1\" checked=\"checked\" />".$L['Yes']." <input type=\"radio\" class=\"radio\" name=\"rallowcomments\" value=\"0\" />".$L['No'] : "<input type=\"radio\" class=\"radio\" name=\"rallowcomments\" value=\"1\" />".$L['Yes']." <input type=\"radio\" class=\"radio\" name=\"rallowcomments\" value=\"0\" checked=\"checked\" />".$L['No'];
        $form_allowratings = ($structure_allowratings) ? "<input type=\"radio\" class=\"radio\" name=\"rallowratings\" value=\"1\" checked=\"checked\" />".$L['Yes']." <input type=\"radio\" class=\"radio\" name=\"rallowratings\" value=\"0\" />".$L['No'] : "<input type=\"radio\" class=\"radio\" name=\"rallowratings\" value=\"1\" />".$L['Yes']." <input type=\"radio\" class=\"radio\" name=\"rallowratings\" value=\"0\" checked=\"checked\" />".$L['No'];
    
    
        if (empty($row['structure_tpl']))
          {
          $check1 = " checked=\"checked\"";
          }
        elseif ($row['structure_tpl']=='same_as_parent')
          {
          $structure_tpl_sym = "*";
          $check3 = " checked=\"checked\"";
          }
        else
          {
          $structure_tpl_sym = "+";
          $check2 = " checked=\"checked\"";
          }
    
    	$adminpath[] = array(sed_url("admin", "m=page&mn=structure&n=options&id=".$id), sed_cc($structure_title));
    
		$st_tpl = "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"1\" $check1 /> ".$L['adm_tpl_empty']."<br/>";
    	$st_tpl .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"2\" $check2 /> ".$L['adm_tpl_forced'];
    	$st_tpl .=  " <select name=\"rtplforced\" size=\"1\">";    
    	foreach($sed_cat as $i => $x)
    		{
    		if ($i!='all')
    			{
    			$selected = ($i==$row['structure_tpl']) ? "selected=\"selected\"" : '';
    			$st_tpl .= "<option value=\"".$i."\" $selected> ".$x['tpath']."</option>";
    			}
    		}
    	$st_tpl .= "</select><br/>";
    	$st_tpl .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"3\" $check3 /> ".$L['adm_tpl_parent'];

    	$checked = $structure_pages ? "checked=\"checked\"" : '';
    	$checked = $structure_group ? "checked=\"checked\"" : '';
    	$st_group = "<input type=\"checkbox\" class=\"checkbox\" name=\"rgroup\" $checked />";
    	
		$t->assign(array(   	
			"STRUCTURE_UPDATE_SEND" => sed_url("admin", "m=page&mn=structure&n=options&a=update&id=".$structure_id),
			"STRUCTURE_UPDATE_CODE" => $structure_code,
			"STRUCTURE_UPDATE_PATH" => "<input type=\"text\" class=\"text\" name=\"rpath\" value=\"".$structure_path."\" size=\"16\" maxlength=\"16\" />",
			"STRUCTURE_UPDATE_TITLE" => "<input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".$structure_title."\" size=\"64\" maxlength=\"32\" />",
			"STRUCTURE_UPDATE_DESC" => "<input type=\"text\" class=\"text\" name=\"rdesc\" value=\"".$structure_desc."\" size=\"64\" maxlength=\"255\" />",
			"STRUCTURE_UPDATE_ICON" => "<input type=\"text\" class=\"text\" name=\"ricon\" value=\"".$structure_icon."\" size=\"64\" maxlength=\"128\" />",
			"STRUCTURE_UPDATE_TEXT" => "<textarea name=\"rstext\" rows=\"".$cfg['textarea_default_height']."\" cols=\"".$cfg['textarea_default_width']."\">".sed_cc($structure_text, ENT_QUOTES)."</textarea>",
			"STRUCTURE_UPDATE_GROUP" => $st_group,
			"STRUCTURE_UPDATE_TPL" => $st_tpl,
			"STRUCTURE_UPDATE_ALLOWCOMMENTS" => $form_allowcomments,
			"STRUCTURE_UPDATE_ALLOWRATINGS" => $form_allowratings
		));
    	
    	$t -> parse("ADMIN_PAGE.STRUCTURE_UPDATE");
    	
    	}
    else
    	{
    	if ($a=='update')
    		{
    		$s = sed_import('s', 'P', 'ARR');
    
    		foreach($s as $i => $k)
    			{
    			$sql1 = sed_sql_query("UPDATE $db_structure SET
    				structure_path='".sed_sql_prep($s[$i]['rpath'])."',
    				structure_group='".$s[$i]['rgroup']."'
    				WHERE structure_id='".$i."'");
    			}
    		sed_auth_clear('all');
    		sed_cache_clear('sed_cat');
    		sed_redirect(sed_url("admin", "m=page&mn=structure&msg=917", "", true));
    		exit;
    		}
    	elseif ($a=='add')
    		{
    		$g = array ('ncode','npath', 'ntitle', 'ndesc', 'nicon', 'ngroup');
    		foreach($g as $k => $x) $$x = $_POST[$x];
    		$group = (isset($group)) ? 1 : 0;
    		sed_structure_newcat($ncode, $npath, $ntitle, $ndesc, $nicon, $ngroup);
    		sed_redirect(sed_url("admin", "m=page&mn=structure&msg=301", "", true));
    		exit;
    		}
    	elseif ($a=='delete')
    		{
    		sed_check_xg();
    		sed_structure_delcat($id, $c);
    		sed_redirect(sed_url("admin", "m=page&mn=structure&msg=302", "", true));
    		exit;
    		}
    
    	$sql = sed_sql_query("SELECT DISTINCT(page_cat), COUNT(*) FROM $db_pages WHERE 1 GROUP BY page_cat");
    
    	while ($row = sed_sql_fetchassoc($sql))
    		{ $pagecount[$row['page_cat']] = $row['COUNT(*)']; }
    
    	$sql = sed_sql_query("SELECT * FROM $db_structure ORDER by structure_path ASC, structure_code ASC");
    
    	$skinpath = "skins/".$skin."/";
    
    	while ($row = sed_sql_fetchassoc($sql))
    		{
    		$jj++;
    		$structure_id = $row['structure_id'];
    		$structure_code = $row['structure_code'];
    		$structure_path = $row['structure_path'];
    		$structure_title = $row['structure_title'];
    		$structure_desc = $row['structure_desc'];
    		$structure_icon = $row['structure_icon'];
    		$structure_group = $row['structure_group'];
    		$pathfieldlen = (mb_strpos($structure_path, ".")==0) ? 3 : 9;
    		$pathfieldimg = (mb_strpos($structure_path, ".")==0) ? '' : "<img src=\"system/img/admin/join2.gif\" alt=\"\" /> ";
    		$pagecount[$structure_code] = (!$pagecount[$structure_code]) ? "0" : $pagecount[$structure_code];
    
    		if (empty($row['structure_tpl']))
    			{ $structure_tpl_sym = $L['adm_tpl_empty']; }
    		elseif ($row['structure_tpl']=='same_as_parent')
    			{ $structure_tpl_sym = $L['adm_tpl_parent']; }
    		else
    			{ $structure_tpl_sym = $L['adm_tpl_forced']." : ".$sed_cat[$row['structure_tpl']]['tpath']; }
    
    		$st_tpl = $structure_tpl_sym." / ";
    		$st_tpl .= "<span class=\"desc\">".str_replace($skinpath, '', sed_skinfile(array('page', $sed_cat[$row['structure_code']]['tpl'])));   
    		$st_tpl .= "+";    
    		if ($sed_cat[$row['structure_code']]['group'])
				{  $st_tpl .= str_replace($skinpath, '', sed_skinfile(array('list', 'group', $sed_cat[$row['structure_code']]['tpl']))); }
    		else
				{  $st_tpl .= str_replace($skinpath, '', sed_skinfile(array('list', $sed_cat[$row['structure_code']]['tpl']))); }              
    		$st_tpl .= "</span>";    
     
    		$st_group = "<select name=\"s[$structure_id][rgroup]\" size=\"1\">";   
    		for ($i=0; $i<3; $i++)
    			{
    			$selected = ($i==$structure_group) ? "selected=\"selected\"" : '';
    			$st_group .= "<option value=\"$i\" $selected>".$structure_mode[$i]."</option>";	   
    			}
    		$st_group .=  "</select>";    
        		
			$t->assign(array(     		
				"STRUCTURE_LIST_DELETE" => ($pagecount[$structure_code] > 0) ? '' : "<a href=\"".sed_url("admin", "m=page&mn=structure&a=delete&id=".$structure_id."&c=".$row['structure_code']."&".sed_xg())."\">".$out['img_delete']."</a>",
				"STRUCTURE_LIST_CODE" => $structure_code,
				"STRUCTURE_LIST_TITLE" => "<a href=\"".sed_url("admin", "m=page&mn=structure&n=options&id=".$structure_id."&".sed_xg())."\">".sed_cc($structure_title)."</a>",
				"STRUCTURE_LIST_PATH" => $pathfieldimg."<input type=\"text\" class=\"text\" name=\"s[$structure_id][rpath]\" value=\"".$structure_path."\" size=\"$pathfieldlen\" maxlength=\"24\" />",
				"STRUCTURE_LIST_TPL" => $st_tpl,
				"STRUCTURE_LIST_GROUP" => $st_group,
				"STRUCTURE_LIST_PAGECOUNT" => $pagecount[$structure_code],
				"STRUCTURE_LIST_OPEN_URL" => sed_url("list", "c=".$structure_code),
				"STRUCTURE_LIST_RIGHTS_URL" => sed_url("admin" ,"m=rightsbyitem&ic=page&io=".$structure_code)
    		));
    		
			$t -> parse("ADMIN_PAGE.PAGE_STRUCTURE.STRUCTURE_LIST");   		
    		   		
    		}

			$t->assign(array( 
				"PAGE_STRUCTURE_SEND" => sed_url("admin", "m=page&mn=structure&a=update"),
				"PAGE_STRUCTURE_ADD_SEND" => sed_url("admin", "m=page&mn=structure&a=add"),
				"PAGE_STRUCTURE_ADD_CODE" => "<input type=\"text\" class=\"text\" name=\"ncode\" value=\"\" size=\"48\" maxlength=\"255\" />", 
				"PAGE_STRUCTURE_ADD_PATH" => "<input type=\"text\" class=\"text\" name=\"npath\" value=\"\" size=\"16\" maxlength=\"255\" />",
				"PAGE_STRUCTURE_ADD_TITLE" => "<input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"48\" maxlength=\"64\" />",
				"PAGE_STRUCTURE_ADD_DESC" => "<input type=\"text\" class=\"text\" name=\"ndesc\" value=\"\" size=\"48\" maxlength=\"255\" />",
				"PAGE_STRUCTURE_ADD_ICON" => "<input type=\"text\" class=\"text\" name=\"nicon\" value=\"\" size=\"48\" maxlength=\"128\" />",
				"PAGE_STRUCTURE_ADD_GROUP" => "<input type=\"checkbox\" class=\"checkbox\" name=\"ngroup\" />"
			));

			$t -> parse("ADMIN_PAGE.PAGE_STRUCTURE"); 		
    	}

  break;
  
  case 'catorder':
  
    // ============= Categories order ==============================
    
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
    sed_block($usr['isadmin']);
    
    $adminpath[] = array (sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder']);
    
    $options_sort = array(
    	'id' => $L['Id'],
    	'type'	=> $L['Type'],
    	'key' => $L['Key'],
    	'title' => $L['Title'],
    	'desc'	=> $L['Description'],
    	'text'	=> $L['Body'],
    	'author' => $L['Author'],
    	'owner' => $L['Owner'],
    	'date'	=> $L['Date'],
    	'begin'	=> $L['Begin'],
    	'expire'	=> $L['Expire'],
    	'count' => $L['Count'],
    	'file' => $L['adm_fileyesno'],
    	'url' => $L['adm_fileurl'],
    	'size' => $L['adm_filesize'],
    	'filecount' => $L['adm_filecount']
    	);
    
    $options_way = array (
    	'asc' => $L['Ascending'],
    	'desc' => $L['Descending']
    	);
    
    if ($a=='reorder')
    	{
    	$s = sed_import('s', 'P', 'ARR');
    
    	foreach($s as $i => $k)
    		{
    		$order = $s[$i]['order'].'.'.$s[$i]['way'];
    		$sql = sed_sql_query("UPDATE $db_structure SET structure_order='$order' WHERE structure_id='$i'");
    		}
    	sed_cache_clear('sed_cat');
       	sed_redirect(sed_url("admin", "m=page&mn=catorder", "#catorder", true));
    	}
    
    $sql = sed_sql_query("SELECT * FROM $db_structure ORDER by structure_path, structure_code");
       
    while ($row = sed_sql_fetchassoc($sql))
    	{
    	$structure_id = $row['structure_id'];
    	$structure_code = $row['structure_code'];
    	$structure_path = $row['structure_path'];
    	$structure_title = $row['structure_title'];
    	$structure_desc = $row['structure_desc'];
    	$structure_order = $row['structure_order'];
       
    	$raw = explode('.',$structure_order);
    	$sort = $raw[0];
    	$way = $raw[1];
    
    	reset($options_sort);
    	reset($options_way);
    
    	$form_sort = "<select name=\"s[".$structure_id."][order]\" size=\"1\">";
    	while( list($i,$x) = each($options_sort) )
    		{
    		$selected = ($i==$sort) ? 'selected="selected"' : '';
    		$form_sort .= "<option value=\"$i\" $selected>".$x."</option>";
    		}
    	$form_sort .= "</select> ";
    
    	$form_way = "<select name=\"s[".$structure_id."][way]\" size=\"1\">";
    	while( list($i,$x) = each($options_way) )
    		{
    		$selected = ($i==$way) ? 'selected="selected"' : '';
    		$form_way .= "<option value=\"$i\" $selected>".$x."</option>";
    		}
    	$form_way .= "</select> ";
    	
		$t->assign(array( 
			"STRUCTURE_LIST_CODE" => $structure_code,
			"STRUCTURE_LIST_PATH" => $structure_path,
			"STRUCTURE_LIST_TITLE" => sed_cc($structure_title),
			"STRUCTURE_LIST_ORDER" => $form_sort.' '.$form_way
		));

		$t -> parse("ADMIN_PAGE.PAGE_SORTING.SORTING_STRUCTURE_LIST"); 		   	
    	
    	}
    
    $t->assign(array( 
		"PAGE_SORTING_SEND" => sed_url("admin", "m=page&mn=catorder&a=reorder")
	));
    
    $t -> parse("ADMIN_PAGE.PAGE_SORTING");
  
  break;
  
  default:

    // ============= Pages queued ==============================
    
    $id = sed_import('id','G','INT');
    
    if ($a=='validate')
    	{
    	sed_check_xg();
    
    	$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='$id'");
    	if ($row = sed_sql_fetchassoc($sql))
    		{
    		$usr['isadmin_local'] = sed_auth('page', $row['page_cat'], 'A');
    		sed_block($usr['isadmin_local']);
    		$sql = sed_sql_query("UPDATE $db_pages SET page_state=0 WHERE page_id='$id'");
    		sed_cache_clear('latestpages');
    		sed_redirect((!empty($redirect)) ? base64_decode($redirect) : sed_url("admin", "m=page&mn=queue", "", true));
    		exit;
    		}
    	else
    		{ sed_die(); }
    	}
    
    if ($a=='unvalidate')
    	{
    	sed_check_xg();
    
    	$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='$id'");
    	if ($row = sed_sql_fetchassoc($sql))
    		{
    		$usr['isadmin_local'] = sed_auth('page', $row['page_cat'], 'A');
    		sed_block($usr['isadmin_local']);
    		$sql = sed_sql_query("UPDATE $db_pages SET page_state=1 WHERE page_id='$id'");
    		sed_cache_clear('latestpages');
    		//sed_redirect(sed_url("list", "c=".$row['page_cat'], "", true));
    		sed_redirect((!empty($redirect)) ? base64_decode($redirect) : sed_url("admin", "m=page&mn=queue", "", true));
    		exit;
    		}
    	else
    		{ sed_die(); }
    	}
    
    $sql = sed_sql_query("SELECT p.*, u.user_name 
    	FROM $db_pages as p 
    	LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid 
    	WHERE page_state=1 ORDER by page_id DESC");
    
    while ($row = sed_sql_fetchassoc($sql))
    	{
    	$row['page_title'] = empty($row['page_title']) ? '(empty title)' : $row['page_title'];
    	$sys['catcode'] = $row['page_cat'];
         
		$t->assign(array( 
			"PAGE_LIST_ID" => $row['page_id'],
			"PAGE_LIST_TITLE" => "<a href=\"".sed_url("page", "id=".$row['page_id'])."\">".sed_cc($row['page_title'])."</a>",
			"PAGE_LIST_CATPATH" => sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"),
			"PAGE_LIST_DATE" => sed_build_date($cfg['dateformat'], $row['page_date']),
			"PAGE_LIST_OWNER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name'])), 	
			"PAGE_LIST_VALIDATE" => "<a href=\"".sed_url("admin", "m=page&mn=queue&a=validate&id=".$row['page_id']."&".sed_xg())."\" class=\"btn btn-adm\">".$L['Validate']."</a>"		
		));
		
		$t -> parse("ADMIN_PAGE.PAGE_QUEUE.PAGE_QUEUE_LIST");      
      }

    if (sed_sql_numrows($sql) == 0) { $t -> parse("ADMIN_PAGE.PAGE_QUEUE.WARNING"); }
    
    $t -> parse("ADMIN_PAGE.PAGE_QUEUE");
  
  break;
  }

$t -> parse("ADMIN_PAGE");  

$adminmain .= $t -> text("ADMIN_PAGE");
  
$adminhelp = $L['adm_help_page'];

?>
