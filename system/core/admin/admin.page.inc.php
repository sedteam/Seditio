<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.page.inc.php
Version=175
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

$adminmain = "<h2><img src=\"system/img/admin/pages.png\" alt=\"\" /> ".$L['Pages']."</h2>";

$totaldbpages = sed_sql_rowcount($db_pages);
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
$sys['pagesqueued'] = sed_sql_result($sql,0,'COUNT(*)');
                     
$adminmain .= "<ul class=\"arrow_list\">";
$adminmain .= "<li><a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=page")."\">".$L['Configuration']."</a></li>";
$adminmain .= "<li>".sed_linkif(sed_url("admin", "m=page&mn=queue"), $L['adm_valqueue']." : ".$sys['pagesqueued'], sed_auth('admin', 'any', 'A'))."</li>";
$adminmain .= "<li>".sed_linkif(sed_url("page", "m=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'))."</li>";
$adminmain .= "<li>".sed_linkif(sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder'], sed_auth('admin', 'a', 'A'))."</li>"; 
$adminmain .= "<li>".sed_linkif(sed_url("admin", "m=page&mn=structure"), $L['Structure'], sed_auth('admin', 'a', 'A'))."</li>"; 
$adminmain .= "<li>".$L['Pages']." : ".$totaldbpages." (<a href=\"".sed_url("list", "c=all")."\">".$L['adm_showall']."</a>)</li>";
$adminmain .= "</ul>";

$structure_mode[0] = $L['Default'];
$structure_mode[1] = $L['Group'];
$structure_mode[2] = $L['Sub'];

switch($mn)
	{
  case 'structure':
  
    // ============= Structure ==============================
    
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
    sed_block($usr['isadmin']);
    
    $adminpath[] = array (sed_url("admin", "m=page&mn=structure"), $L['Structure']);

    $adminmain .= "<h4>".$L['Structure']."</h4>";
    
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
    		  sed_redirect(sed_url("admin", "m=page&mn=structure", "", true));
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
    
    	$adminmain .= "<h4>".$L['editdeleteentries']."</h4>";    	
    	$adminmain .= "<form id=\"savestructure\" action=\"".sed_url("admin", "m=page&mn=structure&n=options&a=update&id=".$structure_id)."\" method=\"post\">";
     
    	$adminmain .= "<table class=\"cells striped\">";
    	$adminmain .= "<tr><td>".$L['Code']." :</td>";
    	$adminmain .= "<td>".$structure_code."</td></tr>";
    	$adminmain .= "<tr><td>".$L['Path']." :</td>";
    	$adminmain .= "<td><input type=\"text\" class=\"text\" name=\"rpath\" value=\"".$structure_path."\" size=\"16\" maxlength=\"16\" /></td></tr>";
    	$adminmain .= "<tr><td>".$L['Title']." :</td>";
    	$adminmain .= "<td><input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".$structure_title."\" size=\"64\" maxlength=\"32\" /></td></tr>";
    	$adminmain .= "<tr><td>".$L['Description']." :</td>";
    	$adminmain .= "<td><input type=\"text\" class=\"text\" name=\"rdesc\" value=\"".$structure_desc."\" size=\"64\" maxlength=\"255\" /></td></tr>";
    	$adminmain .= "<tr><td>".$L['Icon']." :</td>";
    	$adminmain .= "<td><input type=\"text\" class=\"text\" name=\"ricon\" value=\"".$structure_icon."\" size=\"64\" maxlength=\"128\" /></td></tr>";
    	
    	$adminmain .= "<tr><td colspan=\"2\">".$L['Text']." :<br />";
    	$adminmain .= "<textarea name=\"rstext\" rows=\"".$cfg['textarea_default_height']."\" cols=\"".$cfg['textarea_default_width']."\">".sed_cc($structure_text, ENT_QUOTES)."</textarea></td></tr>";

    	$checked = $structure_pages ? "checked=\"checked\"" : '';
    	$checked = $structure_group ? "checked=\"checked\"" : '';
    	$adminmain .= "<tr><td>".$L['Group']." :</td>";
    	$adminmain .= "<td><input type=\"checkbox\" class=\"checkbox\" name=\"rgroup\" $checked /></td></tr>";
    	$adminmain .= "<tr><td>".$L['adm_tpl_mode']." :</td><td>";
    	$adminmain .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"1\" $check1 /> ".$L['adm_tpl_empty']."<br/>";
    	$adminmain .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"2\" $check2 /> ".$L['adm_tpl_forced'];
    	$adminmain .=  " <select name=\"rtplforced\" size=\"1\">";
    
    	foreach($sed_cat as $i => $x)
    		{
    		if ($i!='all')
    			{
    			$selected = ($i==$row['structure_tpl']) ? "selected=\"selected\"" : '';
    			$adminmain .= "<option value=\"".$i."\" $selected> ".$x['tpath']."</option>";
    			}
    		}
    	$adminmain .= "</select><br/>";
    	$adminmain .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"3\" $check3 /> ".$L['adm_tpl_parent'];
    	$adminmain .= "</td></tr>";
    	$adminmain .= "<tr><td>".$L['adm_enablecomments']." :</td><td>".$form_allowcomments."</td></tr>";
    	$adminmain .= "<tr><td>".$L['adm_enableratings']." :</td><td>".$form_allowratings."</td></tr>";
    	$adminmain .= "<tr><td colspan=\"2\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
    	$adminmain .= "</table>";
    	$adminmain .= "</form>";
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
    		sed_redirect(sed_url("admin", "m=page", "", true));
    		exit;
    		}
    	elseif ($a=='add')
    		{
    		$g = array ('ncode','npath', 'ntitle', 'ndesc', 'nicon', 'ngroup');
    		foreach($g as $k => $x) $$x = $_POST[$x];
    		$group = (isset($group)) ? 1 : 0;
    		sed_structure_newcat($ncode, $npath, $ntitle, $ndesc, $nicon, $ngroup);
    		sed_redirect(sed_url("admin", "m=page&mn=structure", "", true));
    		exit;
    		}
    	elseif ($a=='delete')
    		{
    		sed_check_xg();
    		sed_structure_delcat($id, $c);
    		sed_redirect(sed_url("admin", "m=page&mn=structure", "", true));
    		exit;
    		}
    
    	$sql = sed_sql_query("SELECT DISTINCT(page_cat), COUNT(*) FROM $db_pages WHERE 1 GROUP BY page_cat");
    
    	while ($row = sed_sql_fetchassoc($sql))
    		{ $pagecount[$row['page_cat']] = $row['COUNT(*)']; }
    
    	$sql = sed_sql_query("SELECT * FROM $db_structure ORDER by structure_path ASC, structure_code ASC");
    
    	$skinpath = "skins/".$skin."/";
    
		$adminmain .= "<div class=\"sedtabs\">	
			<ul class=\"tabs\">
		  <li><a href=\"".$sys['request_uri']."#tab1\" class=\"selected\">".$L['Structure']."</a></li>
		  <li><a href=\"".$sys['request_uri']."#tab2\">".$L['addnewentry']."</a></li>
		</ul>    
		<div class=\"tab-box\">";

		$adminmain .= "<div id=\"tab1\" class=\"tabs\">";

		$adminmain .= "<form id=\"savestructure\" action=\"".sed_url("admin", "m=page&mn=structure&a=update")."\" method=\"post\">";  
      
    	$adminmain .= "<table class=\"cells striped\">";
    	$adminmain .= "<tr><td class=\"coltop\">".$L['Delete']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Code']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Title']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Path']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['TPL']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Group']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Pages']."</td>";
    	$adminmain .= "<td class=\"coltop\">".$L['Rights']."</td>";
    	$adminmain .= "</tr>";
    
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
    
    		$adminmain .= "<tr><td style=\"text-align:center;\">";
    		$adminmain .= ($pagecount[$structure_code]>0) ? '' : "<a href=\"".sed_url("admin", "m=page&mn=structure&a=delete&id=".$structure_id."&c=".$row['structure_code']."&".sed_xg())."\">".$out['img_delete']."</a>";
    		$adminmain .= "</td>";
    		$adminmain .= "<td>".$structure_code."</td>";
    		$adminmain .= "<td><a href=\"".sed_url("admin", "m=page&mn=structure&n=options&id=".$structure_id."&".sed_xg())."\">".sed_cc($structure_title)."</a></td>";
    		$adminmain .= "<td>".$pathfieldimg."<input type=\"text\" class=\"text\" name=\"s[$structure_id][rpath]\" value=\"".$structure_path."\" size=\"$pathfieldlen\" maxlength=\"24\" /></td>";
    		$adminmain .= "<td>".$structure_tpl_sym." / ";
    		$adminmain .= "<span class=\"desc\">".str_replace($skinpath, '', sed_skinfile(array('page', $sed_cat[$row['structure_code']]['tpl'])));
    
    		$adminmain .= "+";    
    		if ($sed_cat[$row['structure_code']]['group'])
    		{  $adminmain .= str_replace($skinpath, '', sed_skinfile(array('list', 'group', $sed_cat[$row['structure_code']]['tpl']))); }
    		else
    		{  $adminmain .= str_replace($skinpath, '', sed_skinfile(array('list', $sed_cat[$row['structure_code']]['tpl']))); }
               
    		$adminmain .= "</span></td>";    
        
    //		$checked = $structure_group ? "checked=\"checked\"" : '';
    //		$adminmain .= "<td style=\"text-align:center;\"><input type=\"checkbox\" class=\"checkbox\" name=\"s[$structure_id][rgroup]\" $checked /></td>";
     
    		$adminmain .= "<td style=\"text-align:center;\">"; 
    		$adminmain .= "<select name=\"s[$structure_id][rgroup]\" size=\"1\">";
    
    		for ($i=0; $i<3; $i++)
    			{
    			$selected = ($i==$structure_group) ? "selected=\"selected\"" : '';
    			$adminmain .= "<option value=\"$i\" $selected>".$structure_mode[$i]."</option>";	   
    			}
    		$adminmain .=  "</select>";    
    		$adminmain .= "</td>";       
          
    		$adminmain .= "<td style=\"text-align:right;\">".$pagecount[$structure_code]." ";
    		$adminmain .= "<a href=\"".sed_url("list", "c=".$structure_code)."\"><img src=\"system/img/admin/jumpto.png\" alt=\"\" /></a></td>";
    		$adminmain .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin" ,"m=rightsbyitem&ic=page&io=".$structure_code)."\"><img src=\"system/img/admin/rights2.png\" alt=\"\" /></a></td>";
    		$adminmain .= "</tr>";
    		}

    	$adminmain .= "<tr><td colspan=\"9\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
    	$adminmain .= "</table>";
    	$adminmain .= "</form>";
		
		$adminmain .= "</div>";
		
		$adminmain .= "<div id=\"tab2\" class=\"tabs\">";

    	$adminmain .= "<h4>".$L['addnewentry']."</h4>"; 
    	$adminmain .= "<form id=\"addstructure\" action=\"".sed_url("admin", "m=page&mn=structure&a=add")."\" method=\"post\">";
      
    	$adminmain .= "<table class=\"cells striped\">";
    	$adminmain .= "<tr><td style=\"width:160px;\">".$L['Code']." :</td><td><input type=\"text\" class=\"text\" name=\"ncode\" value=\"\" size=\"48\" maxlength=\"255\" /> ".$L['adm_required']."</td></tr>";
    	$adminmain .= "<tr><td>".$L['Path']." :</td><td><input type=\"text\" class=\"text\" name=\"npath\" value=\"\" size=\"16\" maxlength=\"255\" /> ".$L['adm_required']."</td></tr>";
    	$adminmain .= "<tr><td>".$L['Title']." :</td><td><input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"48\" maxlength=\"64\" /> ".$L['adm_required']."</td></tr>";
    	$adminmain .= "<tr><td>".$L['Description']." :</td><td><input type=\"text\" class=\"text\" name=\"ndesc\" value=\"\" size=\"48\" maxlength=\"255\" /></td></tr>";
    	$adminmain .= "<tr><td>".$L['Icon']." :</td><td><input type=\"text\" class=\"text\" name=\"nicon\" value=\"\" size=\"48\" maxlength=\"128\" /></td></tr>";
    	$adminmain .= "<tr><td>".$L['Group']." :</td><td><input type=\"checkbox\" class=\"checkbox\" name=\"ngroup\" /></td></tr>";
    	$adminmain .= "<tr><td colspan=\"2\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Add']."\" /></td></tr>";
    	$adminmain .= "</table>";
    	$adminmain .= "</form>"; 

		$adminmain .= "</div></div></div>"; 
		
    	}

  break;
  
  case 'catorder':
  
    // ============= Categories order ==============================
    
    list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
    sed_block($usr['isadmin']);
    
    $adminpath[] = array (sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder']);
    
    $adminmain .= "<h4 id=\"catorder\">".$L['adm_sortingorder']."</h4>";
    
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
    
    $adminmain .= "<form id=\"chgorder\" action=\"".sed_url("admin", "m=page&mn=catorder&a=reorder")."\" method=\"post\">";
    
    $adminmain .= "<table class=\"cells striped\"><tr><td class=\"coltop\">".$L['Code']."</td><td class=\"coltop\">".$L['Path']."</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Title']."</td><td class=\"coltop\">".$L['Order']."</td></tr>";
    
    while ($row = sed_sql_fetchassoc($sql))
    	{
    	$structure_id = $row['structure_id'];
    	$structure_code = $row['structure_code'];
    	$structure_path = $row['structure_path'];
    	$structure_title = $row['structure_title'];
    	$structure_desc = $row['structure_desc'];
    	$structure_order = $row['structure_order'];
    
    	$adminmain .= "<tr><td>".$structure_code."</td><td>".$structure_path."</td><td>".sed_cc($structure_title)."</td>";
    
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
    
    	$adminmain  .= "<td>".$form_sort.' '.$form_way."</td></tr>";
    	}
    
    $adminmain  .= "<tr><td colspan=\"4\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
    $adminmain  .= "</table>";
    $adminmain  .= "</form>";
  
  break;
  
  default:

    // ============= Pages queued ==============================
    
    $adminmain .= "<h4>".$L['adm_valqueue']."</h4>";
    
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
    		sed_redirect(sed_url("admin", "m=page&mn=queue", "", true));
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
    		sed_redirect(sed_url("list", "c=".$row['page_cat'], "", true));
    		exit;
    		}
    	else
    		{ sed_die(); }
    	}
    
    $sql = sed_sql_query("SELECT p.*, u.user_name 
    	FROM $db_pages as p 
    	LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid 
    	WHERE page_state=1 ORDER by page_id DESC");
    
    $adminmain .= "<table class=\"cells striped\">";
    $adminmain .= "<tr>";
    $adminmain .= "<td class=\"coltop\">#</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Title']." ".$L['adm_clicktoedit']."</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Category']."</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Date']."</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Owner']."</td>";
    $adminmain .= "<td class=\"coltop\">".$L['Validate']."</td>";
    $adminmain .= "</tr>";
    
    while ($row = sed_sql_fetchassoc($sql))
    	{
    	$row['page_title'] = empty($row['page_title']) ? '(empty title)' : $row['page_title'];
    	$sys['catcode'] = $row['page_cat'];
    	$adminmain .= "<tr>";
    	$adminmain .= "<td>".$row['page_id']."</td>";
    	$adminmain .= "<td><a href=\"".sed_url("page", "id=".$row['page_id'])."\">".sed_cc($row['page_title'])."</a></td>";
    	$adminmain .= "<td>".sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>")."</td>";
    	$adminmain .= "<td style=\"text-align:center;\">".sed_build_date($cfg['dateformat'], $row['page_date'])."</td>";
    	$adminmain .= "<td style=\"text-align:center;\">".sed_build_user($row['page_ownerid'], sed_cc($row['user_name']))."</td>";  	
    	$adminmain .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=page&mn=queue&a=validate&id=".$row['page_id']."&".sed_xg())."\" class=\"btn btn-adm\">".$L['Validate']."</a></td>";
    	$adminmain .= "</tr>";
      }
    $adminmain .= "</table>";
    $adminmain .= (sed_sql_numrows($sql)==0) ? "<p>".$L['None']."</p>" : '';
  
  break;
  }
  
$adminhelp = $L['adm_help_page'];

?>