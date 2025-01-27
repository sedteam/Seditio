<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.dic.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Amro
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('dic', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=dic")] =  $L['core_dic'];

$admintitle = $L['core_dic'];

$adminhelp = $L['adm_help_dic'];

$mn = sed_import('mn', 'G', 'TXT');
$did = sed_import('did', 'G', 'INT');
$tid = sed_import('tid', 'G', 'INT');

$sql_dic = sed_sql_query("SELECT * FROM $db_dic WHERE 1 ORDER BY dic_title ASC");

while ($row_dic = sed_sql_fetchassoc($sql_dic)) {
	$dic_list[$row_dic['dic_id']] = $row_dic['dic_title'];
}

$t = new XTemplate(sed_skinfile('admin.dic', false, true));

switch ($mn) {
	case 'dicitem':

		if ($a == 'update' && (!empty($tid))) {
			$ititle = sed_import('ititle', 'P', 'TXT');
			$icode = sed_import('icode', 'P', 'TXT');
			$idefval = sed_import('idefval', 'P', 'INT');
			$ichildren = sed_import('ichildren', 'P', 'INT');

			$sql = sed_sql_query("UPDATE $db_dic_items SET ditem_title = '" . sed_sql_prep($ititle) . "', 
				ditem_code = '" . sed_sql_prep($icode) . "', ditem_children = '" . (int)$ichildren . "', 
				ditem_defval = '" . (int)$idefval . "' WHERE ditem_id = '" . $tid . "'");

			sed_log("Update term #" . $tid, 'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=" . $did . "&msg=917", "", true));
		} elseif ($a == 'delete' && (!empty($tid))) {
			$sql = sed_sql_query("DELETE FROM $db_dic_items WHERE ditem_id = '" . $tid . "'");
			sed_log("Deleted term #" . $tid, 'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=" . $did . "&msg=917", "", true));
		} elseif ($a == 'add' && (!empty($did))) {

			$ditemtitle = sed_import('ditemtitle', 'P', 'TXT');
			$ditemcode = sed_import('ditemcode', 'P', 'TXT');
			$ditemdefval = sed_import('ditemdefval', 'P', 'TXT');
			$ditemchildren = sed_import('ditemchildren', 'P', 'INT');

			$sql = sed_sql_query("INSERT into $db_dic_items (ditem_dicid, ditem_title, ditem_code, ditem_children, ditem_defval) 
                          VALUES (" . (int)$did . ", '" . sed_sql_prep($ditemtitle) . "', '" . sed_sql_prep($ditemcode) . "', '" . (int)$ditemchildren . "', '" . (int)$ditemdefval . "')");
			sed_log("Added new term in directory #" . $did, 'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&mn=dicitem&did=" . $did . "&msg=917", "", true));
		}

		$sql = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id = '" . $did . "'");
		$drow = sed_sql_fetchassoc($sql);

		$t->assign(array(
			"DIC_TERMS_DICTIONARY" => sed_cc($drow['dic_title'])
		));

		$sql = sed_sql_query("SELECT d.*, t.* FROM $db_dic_items AS t LEFT JOIN $db_dic AS d ON t.ditem_dicid = d.dic_id WHERE t.ditem_dicid = '" . $did . "'");
		while ($row = sed_sql_fetchassoc($sql)) {

			if ($row['ditem_defval'] > 0) {
				$t->assign(array(
					"TERM_LIST_DEFVAL" => $row['ditem_defval']
				));

				$t->parse("ADMIN_DIC.DIC_TERMS.TERMS_LIST.TERM_DEFAULT");
			}

			$children_url = (!empty($row['ditem_children'])) ? sed_url('admin', 'm=dic&mn=dicitem&did=' . $row['ditem_children']) : '';

			$t->assign(array(
				"TERM_LIST_ID" => $row['ditem_id'],
				"TERM_LIST_TITLE" => sed_cc($row['ditem_title']),
				"TERM_LIST_CODE" => sed_cc($row['ditem_code']),
				"TERM_LIST_CHILDRENDIC" => (!empty($row['ditem_children'])) ? "<a href=\"" . $children_url . "\">ID#" . $row['ditem_children'] . ' ' . $dic_list[$row['ditem_children']] . "</a>" : '',
				"TERM_LIST_DELETE_URL" => sed_url('admin', 'm=dic&mn=dicitem&a=delete&tid=' . $row['ditem_id'] . "&did=" . $row['dic_id']),
				"TERM_LIST_EDIT_URL" => sed_url('admin', 'm=dic&mn=dicitemedit&tid=' . $row['ditem_id'])
			));

			$t->parse("ADMIN_DIC.DIC_TERMS.TERMS_LIST");
		}

		$urlpaths[sed_url("admin", "m=dic&mn=dicitem&did=" . $did)] = $drow['dic_title'];
		$admintitle = $drow['dic_title'];

		$t->assign(array(
			"TERM_ADD_SEND" => sed_url('admin', 'm=dic&mn=dicitem&a=add&did=' . $did),
			"TERM_ADD_TITLE" => sed_textbox('ditemtitle', isset($ditemtitle) ? $ditemtitle : ''),
			"TERM_ADD_CODE" => sed_textbox('ditemcode', isset($ditemcode) ? $ditemcode : ''),
			"TERM_ADD_CHILDRENDIC" => sed_selectbox('', 'ditemchildren', $dic_list),
			"TERM_ADD_DEFVAL" => sed_radiobox("ditemdefval", $sed_yesno, isset($ditemdefval) ? $ditemdefval : 0)
		));

		$t->parse("ADMIN_DIC.DIC_TERMS");

		break;

	case 'dicedit':

		$dicparent = array();
		$sqlp = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id != '" . $did . "'");
		while ($rowp = sed_sql_fetchassoc($sqlp)) {
			$dicparent[$rowp['dic_id']] = $rowp['dic_title'];
		}

		$sql = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id = '" . $did . "'");
		$row = sed_sql_fetchassoc($sql);

		$t->assign(array(
			"DIC_EDIT_SEND" => sed_url('admin', 'm=dic&a=update&did=' . $did),
			"DIC_EDIT_TITLE" => sed_textbox('dtitle', $row['dic_title']),
			"DIC_EDIT_TYPE" => sed_selectbox($row['dic_type'], 'dtype', $dic_type),
			"DIC_EDIT_VALUES" => sed_textarea('dvalues', $row['dic_values'], 5, 60),
			"DIC_EDIT_MERA" => sed_textbox('dmera', $row['dic_mera']),
			"DIC_EDIT_FORM_TITLE" => sed_textbox('dformtitle', $row['dic_form_title']),
			"DIC_EDIT_FORM_DESC" => sed_textarea('dformdesc', $row['dic_form_desc'], 5, 60),
			"DIC_EDIT_FORM_SIZE" => sed_textbox('dformsize', $row['dic_form_size'], 3, 10),
			"DIC_EDIT_FORM_MAXSIZE" => sed_textbox('dformmaxsize', $row['dic_form_maxsize'], 3, 10),
			"DIC_EDIT_FORM_COLS" => sed_textbox('dformcols', $row['dic_form_cols'], 3, 10),
			"DIC_EDIT_FORM_ROWS" => sed_textbox('dformrows', $row['dic_form_rows'], 3, 10),
			"DIC_EDIT_DICPARENT" => sed_selectbox($row['dic_parent'], 'dparent', $dicparent) //sed_textbox('dtype', $row['dic_type'])		
		));

		$t->parse("ADMIN_DIC.DIC_EDIT");

		break;

	case 'dicitemedit':

		$sql = sed_sql_query("SELECT d.*, t.* FROM $db_dic_items AS t LEFT JOIN $db_dic AS d ON t.ditem_dicid = d.dic_id WHERE t.ditem_id = '" . $tid . "' LIMIT 1");
		$row = sed_sql_fetchassoc($sql);

		$t->assign(array(
			"DIC_TITLE" => sed_cc($row['dic_title']),
			"DIC_ITEM_EDIT_SEND" => sed_url('admin', 'm=dic&mn=dicitem&a=update&tid=' . $tid . "&did=" . $row['dic_id']),
			"DIC_ITEM_EDIT_TITLE" => sed_textbox('ititle', $row['ditem_title']),
			"DIC_ITEM_EDIT_CHILDRENDIC" => sed_selectbox($row['ditem_children'], 'ichildren', $dic_list),
			"DIC_ITEM_EDIT_CODE" => sed_textbox('icode', $row['ditem_code']),
			"DIC_ITEM_EDIT_DEFVAL" => sed_radiobox("idefval", $sed_yesno, $row['ditem_defval'])
		));

		$t->parse("ADMIN_DIC.DIC_ITEM_EDIT");

		break;

	case 'extra':

		$extralocation = sed_import('extralocation', 'P', 'TXT');
		$extratype = sed_import('extratype', 'P', 'TXT');
		$extrasize = sed_import('extrasize', 'P', 'INT');

		$sql = sed_sql_query("SELECT * FROM $db_dic WHERE dic_id = '" . $did . "'");
		if (sed_sql_numrows($sql) > 0) {
			$row = sed_sql_fetchassoc($sql);

			//---
			if ($a == 'add' && (!empty($did))) {
				if (!empty($extralocation) && !empty($row['dic_code'])) {
					sed_extrafield_add($extralocation, $row['dic_code'], $extratype, $extrasize);
					sed_redirect(sed_url("admin", "m=dic&mn=extra&did=" . $did, "", true));
				}
			}
			//---      	
			if ($a == 'update' && (!empty($did))) {
				$extradelete = sed_import('extradelete', 'P', 'BOL');
				if ($extradelete) {
					sed_extrafield_remove($row['dic_extra_location'], $row['dic_code']);
				} else {
					sed_extrafield_update($row['dic_extra_location'], $row['dic_code'], $extratype, $extrasize);
				}
				sed_redirect(sed_url("admin", "m=dic&mn=extra&did=" . $did, "", true));
			}

			$urlpaths[sed_url("admin", "m=dic&mn=dicitem&did=" . $did)] = $row['dic_title'];
			$urlpaths[sed_url("admin", "m=dic&mn=extra&did=" . $did)] = $L['adm_dic_extra'];
			$admintitle = $L['adm_dic_extra'];

			$location_arr = array('pages' => 'Pages', 'users' => 'Users', 'com' => 'Comments', 'forum_topics' => 'Forum topics');
			$type_arr = array('varchar' => 'VARCHAR', 'text' => 'TEXT', 'int' => 'INTEGER', 'tinyint' => 'TINY INTEGER', 'boolean' => 'BOOLEAN');

			for ($i = 1; $i <= 255; $i++) {
				$maxsize_arr[$i] = $i;
			}
			
			$isset_column = "";
			if (!empty($row['dic_extra_location'])) {
				$colname = $row['dic_code'];
				$fieldsres = sed_sql_query("SELECT * FROM " . $cfg['sqldbprefix'] . $row['dic_extra_location'] . " LIMIT 1");
				$i = 1;
				while ($i <= sed_sql_numfields($fieldsres)) {
					$column = sed_sql_fetchfield($fieldsres, $i);
					if (preg_match("#.*?_" . preg_quote($colname) . "$#", $column->name, $match)) {
						$isset_column = !empty($match[0]) ? $match[0] : "";
						break;
					}
					$i++;
				}
			}

			if (!empty($isset_column)) {
				$t->assign(array(
					"DIC_EXTRA_DELETE" => sed_radiobox("extradelete", $yesno_arr, 0)
				));
				$t->parse("ADMIN_DIC.DIC_EXTRA.DIC_EXTRA_DELETE");
			}

			$t->assign(array(
				"DIC_EXTRA_SEND" => sed_url('admin', 'm=dic&mn=extra&did=' . $did . '&a=' . ((!empty($isset_column)) ? "update" : "add")),
				"DIC_EXTRA_SUBMIT_NAME" => (!empty($isset_column)) ? $L['Update'] : $L['Add'],
				"DIC_EXTRA_TITLE" => $row['dic_title'],
				"DIC_EXTRA_DICCODE" => $row['dic_code'],
				"DIC_EXTRA_LOCATION" => (!empty($isset_column)) ?
					$row['dic_extra_location'] . " / <strong>" . $isset_column . "</strong>" :
					sed_selectbox($row['dic_extra_location'], 'extralocation', $location_arr),
				"DIC_EXTRA_TYPE" => sed_selectbox($row['dic_extra_type'], 'extratype', $type_arr, false),
				"DIC_EXTRA_SIZE" => sed_selectbox($row['dic_extra_size'], 'extrasize', $maxsize_arr)
			));

			$t->parse("ADMIN_DIC.DIC_EXTRA");
		}
		break;

	default:

		$dtitle = sed_import('dtitle', 'P', 'TXT');
		$dcode = sed_import('dcode', 'P', 'ALP');
		$dparent = sed_import('dparent', 'P', 'INT');
		$dtype = sed_import('dtype', 'P', 'INT');
		$dvalues = sed_import('dvalues', 'P', 'TXT');
		$dmera = sed_import('dmera', 'P', 'TXT');

		$dformtitle = sed_import('dformtitle', 'P', 'TXT');
		$dformdesc = sed_import('dformdesc', 'P', 'TXT');
		$dformsize = sed_import('dformsize', 'P', 'TXT');
		$dformmaxsize = sed_import('dformmaxsize', 'P', 'TXT');
		$dformcols = sed_import('dformcols', 'P', 'TXT');
		$dformrows = sed_import('dformrows', 'P', 'TXT');

		if ($a == 'update' && (!empty($did))) {
			$sql = sed_sql_query("UPDATE $db_dic SET 
				dic_title = '" . sed_sql_prep($dtitle) . "', 
				dic_type = '" . (int)$dtype . "', 
				dic_values = '" . sed_sql_prep($dvalues) . "', 
				dic_mera = '" . sed_sql_prep($dmera) . "', 
				dic_parent = '" . sed_sql_prep($dparent) . "', 
				dic_form_title = '" . sed_sql_prep($dformtitle) . "', 
				dic_form_desc = '" . sed_sql_prep($dformdesc) . "',
				dic_form_size = '" . sed_sql_prep($dformsize) . "',
				dic_form_maxsize = '" . sed_sql_prep($dformmaxsize) . "',
				dic_form_cols = '" . sed_sql_prep($dformcols) . "',
				dic_form_rows = '" . sed_sql_prep($dformrows) . "'          
				WHERE dic_id = '" . $did . "'");

			sed_log("Update directory #" . $did, 'adm');
			sed_cache_clear('sed_dic');
			sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));
		} elseif ($a == 'delete' && (!empty($did))) {
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_dic_items WHERE ditem_dicid = '" . $did . "'");
			$totalitems = sed_sql_result($sql, 0, "COUNT(*)");
			if ($totalitems == 0) {
				$sql = sed_sql_query("DELETE FROM $db_dic WHERE dic_id = '" . $did . "'");
				sed_log("Deleted directory #" . $did, 'adm');
				sed_cache_clear('sed_dic');
				sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));
			}
		} elseif ($a == 'add') {
			if (empty($dtitle) || empty($dcode) || empty($dtype)) {
				$msg = 303;
			} else {

				$dcode = preg_replace('/[^a-zA-Z0-9]/', '', $dcode);

				$sql = sed_sql_query("INSERT into $db_dic 
					(dic_title, 
					dic_code, 
					dic_type, 
					dic_values, 
					dic_mera,
					dic_form_title, 
					dic_form_desc,
					dic_form_size,
					dic_form_maxsize,
					dic_form_cols,
					dic_form_rows             
					) 
					VALUES 
					('" . sed_sql_prep($dtitle) . "', 
					'" . sed_sql_prep($dcode) . "', 
					'" . (int)$dtype . "', 
					'" . sed_sql_prep($dvalues) . "', 
					'" . sed_sql_prep($dmera) . "',
					'" . sed_sql_prep($dformtitle) . "', 
					'" . sed_sql_prep($dformdesc) . "',
					'" . sed_sql_prep($dformsize) . "',
					'" . sed_sql_prep($dformmaxsize) . "',
					'" . sed_sql_prep($dformcols) . "',
					'" . sed_sql_prep($dformrows) . "')");

				sed_log("Addded directory #" . $did, 'adm');
				sed_cache_clear('sed_dic');
				sed_redirect(sed_url("admin", "m=dic&msg=917", "", true));
			}
		}

		$termcount = array();
		$sql = sed_sql_query("SELECT DISTINCT(ditem_dicid), COUNT(*) FROM $db_dic_items WHERE 1 GROUP BY ditem_dicid");

		while ($row = sed_sql_fetchassoc($sql)) {
			$termcount[$row['ditem_dicid']] = $row['COUNT(*)'];
		}

		$sql = sed_sql_query("SELECT * FROM $db_dic");

		while ($row = sed_sql_fetchassoc($sql)) {

			if (!isset($termcount[$row['dic_id']])) {
				$t->assign(array(
					"DIC_LIST_DELETE_URL" => sed_url("admin", "m=dic&a=delete&did=" . $row['dic_id'])
				));
				$t->parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST.ADMIN_DELETE");
			}

			$t->assign(array(
				"DIC_LIST_EDIT_URL" => sed_url("admin", "m=dic&mn=dicedit&did=" . $row['dic_id'])
			));

			$t->parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST.ADMIN_ACTIONS");

			$dic_code = "<a href=\"" . sed_url('admin', 'm=dic&mn=extra&did=' . $row['dic_id']) . "\">" . $row['dic_code'] . "</a>";
			
			if (!empty($row['dic_extra_location'])) {
				$i = 1;
				$colname = $row['dic_code'];
				$fieldsres = sed_sql_query("SELECT * FROM " . $cfg['sqldbprefix'] . $row['dic_extra_location'] . " LIMIT 1");

				while ($i <= sed_sql_numfields($fieldsres)) {
					$column = sed_sql_fetchfield($fieldsres, $i);
					if (preg_match("#.*?_" . preg_quote($colname) . "$#", $column->name, $match)) {
						$dic_code .= !empty($match[0]) ? " <strong>(" . $column->table . "#" . $match[0] . ")</strong>" : "";
						break;
					}
					$i++;
				}
			}

			$t->assign(array(
				"DIC_LIST_ID" => $row['dic_id'],
				"DIC_LIST_URL" => sed_url('admin', 'm=dic&mn=dicitem&did=' . $row['dic_id']),
				"DIC_LIST_TITLE" => $row['dic_title'],
				"DIC_LIST_CODE" => $dic_code,
				"DIC_LIST_TYPE" => isset($dic_type[$row['dic_type']]) ? $dic_type[$row['dic_type']] : ''
			));
			$t->parse("ADMIN_DIC.DIC_STRUCTURE.DIC_LIST");
		}

		$t->assign(array(
			"DIC_ADD_SEND" => sed_url('admin', 'm=dic&a=add'),
			"DIC_ADD_TITLE" => sed_textbox('dtitle', $dtitle),
			"DIC_ADD_VALUES" => sed_textarea('dvalues', $dvalues, 5, 60),
			"DIC_ADD_MERA" => sed_textbox('dmera', $dmera, 8, 50),
			"DIC_ADD_CODE" => sed_textbox('dcode', $dcode),
			"DIC_ADD_TYPE" => sed_selectbox(isset($dtype) ? $dtype : '', 'dtype', $dic_type),
			"DIC_ADD_FORM_TITLE" => sed_textbox('dformtitle', $dformtitle),
			"DIC_ADD_FORM_DESC" => sed_textarea('dformdesc', $dformdesc, 5, 60),
			"DIC_ADD_FORM_SIZE" => sed_textbox('dformsize', $dformsize, 3, 10),
			"DIC_ADD_FORM_MAXSIZE" => sed_textbox('dformmaxsize', $dformmaxsize, 3, 10),
			"DIC_ADD_FORM_COLS" => sed_textbox('dformcols', $dformcols, 3, 10),
			"DIC_ADD_FORM_ROWS" => sed_textbox('dformrows', $dformrows, 3, 10)
		));

		$t->parse("ADMIN_DIC.DIC_STRUCTURE");
		break;
}

$t->assign("ADMIN_DIC_TITLE", $admintitle);

$t->parse("ADMIN_DIC");

$adminmain .= $t->text("ADMIN_DIC");
