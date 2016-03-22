<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.dic.inc.php
Version=177
Updated=2015-feb-06
Type=Core.admin
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('dic', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=dic"), $L['core_dic']);

$adminhelp = $L['adm_help_dic'];

$mn = sed_import('mn', 'G', 'TXT');
$did = sed_import('did','G','INT');
$tid = sed_import('tid','G','INT');

$dic_type = array(1 => 'selectbox', 2 => 'optionbox', 3 => 'checkbox');

$sql_dic = sed_sql_query("SELECT * FROM $db_dic WHERE 1 ORDER BY dic_title ASC");
while ($row_dic = sed_sql_fetchassoc($sql_dic))
	{
		$dic_list[$row_dic['dic_id']] = $row_dic['dic_title']; 
	}

$t = new XTemplate(sed_skinfile('admin.dic', true)); 

switch($mn)
	{
	case 'dicitem':
  
	if ($a == 'update' && (!empty($tid)))
		{		
		$ititle = sed_import('ititle','P','TXT');
		$icode = sed_import('icode','P','TXT');
		$idefval = sed_import('idefval','P','INT');
		$ichildren = sed_import('ichildren', 'P', 'INT');	
		
		$sql = sed_sql_query("UPDATE $db_dic_items SET ditem_title = '".sed_sql_prep($ititle)."', 
				ditem_code = '".sed_sql_prep($icode)."', ditem_children = '".(int)$ichildren."', 
				ditem_defval = '".(int)$idefval."' WHERE ditem_id = '".$tid."'");
				
		sed_log("Update term #".$tid,'adm');
		sed_cache_clear('sed_dic');
		sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=".$did."&msg=917", "", true));  			
		}
	elseif ($a == 'delete' && (!empty($tid)))
		{
		$sql = sed_sql_query("DELETE FROM $db_dic_items WHERE ditem_id = '".$tid."'");
		sed_log("Deleted term #".$tid,'adm');
		sed_cache_clear('sed_dic');
		sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=".$did."&msg=917", "", true));			
		}
	elseif ($a == 'add' && (!empty($did)))
		{
		
		$ditemtitle = sed_import('ditemtitle','P','TXT');
		$ditemcode = sed_import('ditemcode','P','TXT');
		$ditemdefval = sed_import('ditemdefval','P','TXT');
		$ditemchildren = sed_import('ditemchildren', 'P', 'INT');
		
		$sql = sed_sql_query("INSERT into $db_dic_items (ditem_dicid, ditem_title, ditem_code, ditem_children, ditem_defval) 
                          VALUES (".(int)$did.", '".sed_sql_prep($ditemtitle)."', '".sed_sql_prep($ditemcode)."', '".(int)$ditemchildren."', '".(int)$ditemdefval."')");	
		sed_log("Added new term in directory #".$did,'adm');
		sed_cache_clear('sed_dic');
		sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=".$did."&msg=917", "", true));
		}
	
	$sql = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id = '".$did."'");
	$drow = sed_sql_fetchassoc($sql);
  
	$t -> assign(array(
	  "DIC_TERMS_DICTIONARY" => sed_cc($drow['dic_title'])
	));

	$sql = sed_sql_query("SELECT d.*, t.* FROM $db_dic_items AS t LEFT JOIN $db_dic AS d ON t.ditem_dicid = d.dic_id WHERE t.ditem_dicid = '".$did."'");
	while($row = sed_sql_fetchassoc($sql))
		{
			
		if  ($row['ditem_defval'] > 0)
		  {
		  $t -> assign(array(
				"TERM_LIST_DEFVAL" => $row['ditem_defval']
		  ));  		
		  
		  $t -> parse("ADMIN_DIC.DIC_TERMS.TERMS_LIST.TERM_DEFAULT");
		  }
		  
		$children_url = (!empty($row['ditem_children'])) ? sed_url('admin', 'm=dic&mn=dicitem&did='.$row['ditem_children']) : '';
		
		$t -> assign(array(
			"TERM_LIST_ID" => $row['ditem_id'],
			"TERM_LIST_TITLE" => sed_cc($row['ditem_title']),
			"TERM_LIST_CODE" => sed_cc($row['ditem_code']),
			"TERM_LIST_CHILDRENDIC" => (!empty($row['ditem_children'])) ? "<a href=\"".$children_url."\">ID#".$row['ditem_children'].' '.$dic_list[$row['ditem_children']]."</a>" : '',
			"TERM_LIST_DELETE_URL" => sed_url('admin', 'm=dic&mn=dicitem&a=delete&tid='.$row['ditem_id']."&did=".$row['dic_id']),
			"TERM_LIST_EDIT_URL" => sed_url('admin', 'm=dic&mn=dicitemedit&tid='.$row['ditem_id'])				
		));		
		
		$t -> parse("ADMIN_DIC.DIC_TERMS.TERMS_LIST");		
		}
  
	$adminpath[] = array (sed_url("admin", "m=dic&mn=dicitem&did=".$did), $drow['dic_title']);
  
	$t -> assign(array(
		"TERM_ADD_SEND" => sed_url('admin', 'm=dic&mn=dicitem&a=add&did='.$did),
		"TERM_ADD_TITLE" => sed_textbox('ditemtitle', $ditemtitle),
		"TERM_ADD_CODE" => sed_textbox('ditemcode', $ditemcode),
		"TERM_ADD_CHILDRENDIC" => sed_selectbox('', 'ditemchildren', $dic_list),	
		"TERM_ADD_DEFVAL" => sed_radiobox("radio", "ditemdefval", 1, FALSE).$L['Yes']." ".sed_radiobox("radio", "ditemdefval", 0, TRUE).$L['No']
	));	    
    
	$t -> parse("ADMIN_DIC.DIC_TERMS");
		
	break;
  
  case 'dicedit':
  
	$dicparent = array();
	$sqlp = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id != '".$did."'");
	while ($rowp = sed_sql_fetchassoc($sqlp))
	{
		$dicparent[$rowp['dic_code']] = $rowp['dic_title'];
	}	
	
	$sql = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id = '".$did."'");
	$row = sed_sql_fetchassoc($sql);
	
	$t -> assign(array(
		"DIC_EDIT_SEND" => sed_url('admin', 'm=dic&a=update&did='.$did),
		"DIC_EDIT_TITLE" => sed_textbox('dtitle', $row['dic_title']),
		"DIC_EDIT_CODE" => sed_textbox('dcode', $row['dic_code']),
		"DIC_EDIT_TYPE" => sed_selectbox($row['dic_type'], 'dtype', $dic_type),	
		"DIC_EDIT_DICPARENT" => sed_selectbox($row['dic_parent'], 'dparent', $dicparent) //sed_textbox('dtype', $row['dic_type'])		
	));			

	$t -> parse("ADMIN_DIC.DIC_EDIT");	  

  break;
  
  case 'dicitemedit':
  
	$sql = sed_sql_query("SELECT d.*, t.* FROM $db_dic_items AS t LEFT JOIN $db_dic AS d ON t.ditem_dicid = d.dic_id WHERE t.ditem_id = '".$tid."' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);

	$defval = ($row['ditem_defval'] > 0) ? sed_radiobox("radio", "idefval", 1, TRUE).$L['Yes']." ".sed_radiobox("radio", "idefval", 0, FALSE).$L['No'] :
												sed_radiobox("radio", "idefval", 1, FALSE).$L['Yes']." ".sed_radiobox("radio", "idefval", 0, TRUE).$L['No'];
	$t -> assign(array(
		"DIC_TITLE" => sed_cc($row['dic_title']),
		"DIC_ITEM_EDIT_SEND" => sed_url('admin', 'm=dic&mn=dicitem&a=update&tid='.$tid."&did=".$row['dic_id']),
		"DIC_ITEM_EDIT_TITLE" => sed_textbox('ititle', $row['ditem_title']),
		"DIC_ITEM_EDIT_CHILDRENDIC" => sed_selectbox($row['ditem_children'], 'ichildren', $dic_list),
		"DIC_ITEM_EDIT_CODE" => sed_textbox('icode', $row['ditem_code']),
		"DIC_ITEM_EDIT_DEFVAL" => $defval		
	));			

	$t -> parse("ADMIN_DIC.DIC_ITEM_EDIT");	  
  
  break;

  default:
	
	$dtitle = sed_import('dtitle','P','TXT');
	$dcode = sed_import('dcode','P','TXT');
	$dparent = sed_import('dparent','P','TXT');
	$dtype = sed_import('dtype','P','INT');
	
	if ($a == 'update' && (!empty($did)))
			{
			$sql = sed_sql_query("UPDATE $db_dic SET dic_title = '".sed_sql_prep($dtitle)."', 
					dic_code = '".sed_sql_prep($dcode)."', dic_type = '".(int)$dtype."', dic_parent = '".sed_sql_prep($dparent)."' WHERE dic_id = '".$did."'");
			sed_log("Update directory #".$did,'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));    				
			}
	elseif ($a == 'delete' && (!empty($did)))
			{
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_dic_items WHERE ditem_dicid = '".$did."'");
			$totalitems = sed_sql_result($sql, 0, "COUNT(*)");
			if ($totalitems == 0)
				{
				$sql = sed_sql_query("DELETE FROM $db_dic WHERE dic_id = '".$did."'");
				sed_log("Deleted directory #".$did,'adm');
				sed_cache_clear('sed_dic');
				sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));
				}				
			}
	elseif ($a == 'add')
			{
			$sql = sed_sql_query("INSERT into $db_dic (dic_title, dic_code, dic_type) VALUES ('".sed_sql_prep($dtitle)."', '".sed_sql_prep($dcode)."', '".(int)$dtype."')");
			sed_log("Addded directory #".$did,'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));
			}
		
    
	$sql = sed_sql_query("SELECT DISTINCT(ditem_dicid), COUNT(*) FROM $db_dic_items WHERE 1 GROUP BY ditem_dicid");

	while ($row = sed_sql_fetchassoc($sql))
		{ $termcount[$row['ditem_dicid']] = $row['COUNT(*)']; }	
    
	$sql = sed_sql_query("SELECT * FROM $db_dic");  
	
	while ($row = sed_sql_fetchassoc($sql))
		{

		if ($termcount[$row['dic_id']] < 1)
		  {
		  $t -> assign(array(
			"DIC_LIST_DELETE_URL" => sed_url("admin", "m=dic&a=delete&did=".$row['dic_id'])
			));    
		  $t -> parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST.ADMIN_DELETE");        
		  }

		$t -> assign(array(
			"DIC_LIST_EDIT_URL" => sed_url("admin", "m=dic&mn=dicedit&did=".$row['dic_id'])	
			));
      		
		$t -> parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST.ADMIN_ACTIONS");  

		$t -> assign(array(
			"DIC_LIST_ID" => $row['dic_id'],
			"DIC_LIST_URL" => sed_url('admin', 'm=dic&mn=dicitem&did='.$row['dic_id']),
			"DIC_LIST_TITLE" => $row['dic_title'],
			"DIC_LIST_CODE" => $row['dic_code'],
			"DIC_LIST_TYPE" =>	$dic_type[$row['dic_type']]	
		));
		$t -> parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST");
		}
		
	$t -> assign(array(
		"DIC_ADD_SEND" => sed_url('admin', 'm=dic&a=add'),
		"DIC_ADD_TITLE" => sed_textbox('dtitle', $dtitle),
		"DIC_ADD_CODE" => sed_textbox('dcode', $dcode),
		"DIC_ADD_TYPE" => sed_selectbox($dtype, 'dtype', $dic_type) //sed_textbox('dtype', $dtype)			
	));			
	
	$t -> parse("ADMIN_DIC.DIC_STRUCTURE");	
	break;
	}	

$t -> parse("ADMIN_DIC");  
$adminmain .= $t -> text("ADMIN_DIC");

?>
