<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.forums.inc.php
Version=177
Updated=2015-feb-06
Type=Core.admin
Author=Neocrome
Description=Forums & categories
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$id = sed_import('id','G','INT');

$adminpath[] = array (sed_url("admin", "m=forums"), $L['Forums']);
$adminpath[] = array (sed_url("admin", "m=forums&s=structure"), $L['adm_forum_structure']);

$adminhelp = $L['adm_help_forum_structure'];

$t = new XTemplate(sed_skinfile('admin.forums.structure', true));

if ($n=='options')
	{
	if ($a=='update')
		{
		$rpath = sed_import('rpath','P','TXT');
		$rtitle = sed_import('rtitle','P','TXT');
		$rtplmode = sed_import('rtplmode','P','INT');
		$rdesc = sed_import('rdesc','P','TXT');
		$ricon = sed_import('ricon','P','TXT');
		$rdefstate = sed_import('rdefstate','P','BOL');										

	if ($rtplmode==1)
		{ $rtpl = ''; }
	elseif ($rtplmode==3)
		{ $rtpl = 'same_as_parent'; }

		$sql = sed_sql_query("UPDATE $db_forum_structure SET
			fn_path='".sed_sql_prep($rpath)."',
			fn_tpl='".sed_sql_prep($rtpl)."',
			fn_title='".sed_sql_prep($rtitle)."',
			fn_desc='".sed_sql_prep($rdesc)."',
			fn_icon='".sed_sql_prep($ricon)."',
			fn_defstate='".$rdefstate."'
			WHERE fn_id='".$id."'");

		sed_cache_clear('sed_forums_str');
		sed_redirect(sed_url("admin", "m=forums&s=structure", "", true));
		exit;
		}

	$sql = sed_sql_query("SELECT * FROM $db_forum_structure WHERE fn_id='$id' LIMIT 1");
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

	$fn_id = $row['fn_id'];
	$fn_code = $row['fn_code'];
	$fn_path = $row['fn_path'];
	$fn_title = $row['fn_title'];
	$fn_desc = $row['fn_desc'];
	$fn_icon = $row['fn_icon'];
	$fn_defstate = $row['fn_defstate'];

	if ($row['fn_tpl']=='same_as_parent')
		{
		$fn_tpl_sym = "*";
		$check3 = " checked=\"checked\"";
		}
	else
		{
		$fn_tpl_sym = "-";	
		$check1 = " checked=\"checked\"";
		}	

	$adminpath[] = array (sed_url("admin", "m=forums&s=structure&n=options&id=".$id), sed_cc($fn_title));
    
	$selected0 = (!$row['fn_defstate']) ? "selected=\"selected\"" : '';
	$selected1 = ($row['fn_defstate']) ? "selected=\"selected\"" : '';
	$fn_defstate = "<select name=\"rdefstate\" size=\"1\">";	
	$fn_defstate .= "<option value=\"1\" $selected1>".$L['adm_defstate_1'];
	$fn_defstate .= "<option value=\"0\" $selected0>".$L['adm_defstate_0'];
	$fn_defstate .= "</select>";
	
	$fn_tplmode = "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"1\" $check1 /> ".$L['adm_tpl_empty']."<br/>";
	$fn_tplmode .= "<input type=\"radio\" class=\"radio\" name=\"rtplmode\" value=\"3\" $check3 /> ".$L['adm_tpl_parent'];	  
  
	$t->assign(array(  
		"FN_UPDATE_FORM_TITLE" => sed_cc($fn_title),
		"FN_UPDATE_SEND" => sed_url("admin", "m=forums&s=structure&n=options&a=update&id=".$fn_id),
		"FN_UPDATE_CODE" => $fn_code,
		"FN_UPDATE_PATH" => "<input type=\"text\" class=\"text\" name=\"rpath\" value=\"".$fn_path."\" size=\"16\" maxlength=\"16\" />",
		"FN_UPDATE_TITLE" => "<input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".$fn_title."\" size=\"64\" maxlength=\"32\" />",
		"FN_UPDATE_DESC" => "<input type=\"text\" class=\"text\" name=\"rdesc\" value=\"".$fn_desc."\" size=\"64\" maxlength=\"255\" />",
		"FN_UPDATE_ICON" => "<input type=\"text\" class=\"text\" name=\"ricon\" value=\"".$fn_icon."\" size=\"64\" maxlength=\"128\" />",
		"FN_UPDATE_DEFSTATE" => $fn_defstate,
		"FN_UPDATE_TPLMODE" => $fn_tplmode
	));
	
	$t -> parse("ADMIN_FORUMS.FORUMS_STRUCTURE_UPDATE");  
   
	}
else
	{

	if ($a=='update')
		{
		$s = sed_import('s', 'P', 'ARR');

		foreach($s as $i => $k)
			{
			$sql1 = sed_sql_query("UPDATE $db_forum_structure SET
				fn_path='".$s[$i]['rpath']."',
				fn_title='".$s[$i]['rtitle']."',
				fn_defstate='".$s[$i]['rdefstate']."'
				WHERE fn_id='".$i."'");
			}
		sed_cache_clear('sed_forums_str');
		sed_redirect(sed_url("admin", "m=forums&s=structure", "", true));
		exit;
		}
	elseif ($a=='add')
		{
		$g = array ('ncode','npath', 'ntitle', 'ndesc', 'nicon', 'ndefstate');
		foreach($g as $k => $x) $$x = $_POST[$x];

		if (!empty($ntitle) && !empty($ncode) && !empty($npath) && $ncode!='all')
			{
			$sql = sed_sql_query("SELECT fn_code FROM $db_forum_structure WHERE fn_code='".sed_sql_prep($ncode)."' LIMIT 1");
			$ncode .= (sed_sql_numrows($sql)>0) ? "_".rand(100,999) : '';

			$sql = sed_sql_query("INSERT INTO $db_forum_structure (fn_code, fn_path, fn_title, fn_desc, fn_icon, fn_defstate) VALUES ('$ncode', '$npath', '$ntitle', '$ndesc', '$nicon', ".(int)$ndefstate.")");
			}

		sed_cache_clear('sed_forums_str');
		sed_redirect(sed_url("admin", "m=forums&s=structure", "", true));
		exit;
		}
	elseif ($a=='delete')
		{
		sed_check_xg();
		$sql = sed_sql_query("DELETE FROM $db_forum_structure WHERE fn_id='$id'");
		sed_cache_clear('sed_forums_str');
		sed_redirect(sed_url("admin", "m=forums&s=structure", "", true));
		exit;
		}

	$sql = sed_sql_query("SELECT DISTINCT(fs_category), COUNT(*) FROM $db_forum_sections WHERE 1 GROUP BY fs_category");

	while ($row = sed_sql_fetchassoc($sql))
		{ $sectioncount[$row['fs_category']] = $row['COUNT(*)']; }

	$sql = sed_sql_query("SELECT * FROM $db_forum_structure ORDER by fn_path ASC, fn_code ASC");   

	while ($row = sed_sql_fetchassoc($sql))
		{
		$jj++;
		$fn_id = $row['fn_id'];
		$fn_code = $row['fn_code'];
		$fn_path = $row['fn_path'];
		$fn_title = $row['fn_title'];
		$fn_desc = $row['fn_desc'];
		$fn_icon = $row['fn_icon'];
		$pathfieldlen = (mb_strpos($fn_path, ".")==0) ? 3 : 9;
		$pathfieldimg = (mb_strpos($fn_path, ".")==0) ? '' : "<img src=\"system/img/admin/join2.gif\" alt=\"\" /> ";
		$sectioncount[$fn_code] = (!$sectioncount[$fn_code]) ? "0" : $sectioncount[$fn_code];
	
		if (empty($row['fn_tpl']))
			{ $fn_tpl_sym = "-"; }
		elseif ($row['fn_tpl']=='same_as_parent')
			{ $fn_tpl_sym = "*"; }
		else
			{ $fn_tpl_sym = "+"; }
		
		$selected0 = (!$row['fn_defstate']) ? "selected=\"selected\"" : '';
		$selected1 = ($row['fn_defstate']) ? "selected=\"selected\"" : '';
		$fn_defstate = "<select name=\"s[$fn_id][rdefstate]\" size=\"1\">";	
		$fn_defstate .= "<option value=\"1\" $selected1>".$L['adm_defstate_1'];
		$fn_defstate .= "<option value=\"0\" $selected0>".$L['adm_defstate_0'];
		$fn_defstate .= "</select>";

		$t->assign(array( 
			"STRUCTURE_LIST_DELETE" => ($sectioncount[$fn_code] > 0) ? '' : "<a href=\"".sed_url("admin", "m=forums&s=structure&a=delete&id=".$fn_id."&c=".$row['fn_code']."&".sed_xg())."\">".$out['img_delete']."</a>",
			"STRUCTURE_LIST_CODE" => $fn_code,
			"STRUCTURE_LIST_PATH" => $pathfieldimg."<input type=\"text\" class=\"text\" name=\"s[$fn_id][rpath]\" value=\"".$fn_path."\" size=\"$pathfieldlen\" maxlength=\"24\" />",
			"STRUCTURE_LIST_DEFSTATE" => $fn_defstate,
			"STRUCTURE_LIST_TPL" => $fn_tpl_sym,
			"STRUCTURE_LIST_TITLE" => "<input type=\"text\" class=\"text\" name=\"s[$fn_id][rtitle]\" value=\"".$fn_title."\" size=\"24\" maxlength=\"32\" />",
			"STRUCTURE_LIST_SECTIONCOUNT" => $sectioncount[$fn_code],
			"STRUCTURE_LIST_OPEN_URL" => sed_url("forums", "c=".$fn_code),
			"STRUCTURE_LIST_OPTIONS" => "<a href=\"".sed_url("admin", "m=forums&s=structure&n=options&id=".$fn_id."&".sed_xg())."\">".$L['Options']."</a>"
		));
		
		$t -> parse("ADMIN_FORUMS.FORUMS_STRUCTURE.STRUCTURE_LIST"); 		
		}

	$t->assign(array(
		"FORUMS_STRUCTURE_UPDATE_SEND" => sed_url("admin", "m=forums&s=structure&a=update"),
		"FN_ADD_SEND" => sed_url("admin", "m=forums&s=structure&a=add"),
		"FN_ADD_CODE" => "<input type=\"text\" class=\"text\" name=\"ncode\" value=\"\" size=\"16\" maxlength=\"16\" />",
		"FN_ADD_PATH" => "<input type=\"text\" class=\"text\" name=\"npath\" value=\"\" size=\"16\" maxlength=\"16\" />",
		"FN_ADD_DEFSTATE" => "<input type=\"radio\" class=\"radio\" name=\"ndefstate\" value=\"1\" checked=\"checked\" />".$L['adm_defstate_1']." <input type=\"radio\" class=\"radio\" name=\"ndefstate\" value=\"0\" />".$L['adm_defstate_0'],
		"FN_ADD_TITLE" => "<input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"48\" maxlength=\"32\" />",
		"FN_ADD_DESC" => "<input type=\"text\" class=\"text\" name=\"ndesc\" value=\"\" size=\"48\" maxlength=\"255\" />",
		"FN_ADD_ICON" => "<input type=\"text\" class=\"text\" name=\"nicon\" value=\"\" size=\"48\" maxlength=\"128\" />"
	));
	
	
	$t -> parse("ADMIN_FORUMS.FORUMS_STRUCTURE"); 
	
	}
	
$t -> parse("ADMIN_FORUMS");  
$adminmain .= $t -> text("ADMIN_FORUMS");

?>
