<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.menu.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Amro
Description=Menu build module
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('menu', 'a');
sed_block($usr['isadmin']);

$mn = sed_import('mn', 'G', 'TXT');
$mid = sed_import('mid', 'G', 'INT');

$target_arr = array(
	'_blank' => 'New Window (_blank)',
	'_top' => 'Topmost Window (_top)',
	'_self' => 'Same Window (_self)',
	'_parent' => 'Parent Window (_parent)'
);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=menu")] =  $L['core_menu'];

$admintitle = $L['core_menu'];

$t = new XTemplate(sed_skinfile('admin.menu', false, true));

switch ($a) {
	case "add":

		$mparent = sed_import('mparent', 'P', 'INT');
		$mtitle = sed_import('mtitle', 'P', 'TXT');
		$murl = sed_import('murl', 'P', 'TXT');
		$mposition = sed_import('mposition', 'P', 'INT');
		$mvisible = sed_import('mvisible', 'P', 'BOL');
		$mtarget = sed_import('mtarget', 'P', 'TXT');

		if (empty($mtitle)) {
			$msg = 303;
		} else {

			$sql = sed_sql_query("INSERT INTO $db_menu
						(
						menu_pid,
						menu_title,
						menu_url,
						menu_position,
						menu_visible,
						menu_target
						)
						VALUES 
						(
						" . $mparent . ",
						'" . sed_sql_prep($mtitle) . "',
						'" . sed_sql_prep($murl) . "',
						" . (int)$mposition . ",
						" . (int)$mvisible . ",
						'" . sed_sql_prep($mtarget) . "'
						)");
						
			if (empty($mposition)) {
				$mposition = sed_sql_insertid();
				$sql = sed_sql_query("UPDATE $db_menu SET menu_position = " . (int)$mposition . " WHERE menu_id = " . $mposition);
			}

			sed_log("Add new menu item", 'adm');
			sed_cache_clear('sed_menu');
			sed_redirect(sed_url("admin", "m=menu&msg=917", "", true));
		}

		break;

	case "updatepos":

		$mposition = sed_import('mposition', 'P', 'ARR');
		if (count($mposition) > 0) {
			foreach ($mposition as $key => $position) {
				//$mposition_arr[$key] = sed_import($position,'D','INT');
				sed_sql_query("UPDATE $db_menu SET menu_position = " . (int)$position . " WHERE menu_id = " . (int)$key);
			}
		}

		sed_log("Update menu positions for all items", 'adm');
		sed_cache_clear('sed_menu');
		sed_redirect(sed_url("admin", "m=menu&msg=917", "", true));

		break;

	case "update":

		$mparent = sed_import('mparent', 'P', 'INT');
		$mtitle = sed_import('mtitle', 'P', 'TXT');
		$murl = sed_import('murl', 'P', 'TXT');
		$mposition = sed_import('mposition', 'P', 'INT');
		$mvisible = sed_import('mvisible', 'P', 'BOL');
		$mtarget = sed_import('mtarget', 'P', 'TXT');

		if (empty($mtitle)) {
			$msg = 303;
		} else {
			$sql = sed_sql_query("UPDATE $db_menu SET 
							menu_pid = " . (int)$mparent . ",
							menu_title = '" . sed_sql_prep($mtitle) . "',
							menu_url = '" . sed_sql_prep($murl) . "',
							menu_position = " . (int)$mposition . ",
							menu_visible = " . (int)$mvisible . ",
							menu_target = '" . sed_sql_prep($mtarget) . "' 						
							WHERE menu_id = " . $mid);

			sed_log("Update menu item #" . $mid, 'adm');
			sed_cache_clear('sed_menu');
			sed_redirect(sed_url("admin", "m=menu&msg=917", "", true));
		}

		break;

	case "delete":

		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_menu WHERE menu_pid = '" . $mid . "'");
		$childrens = sed_sql_result($sql, 0, "COUNT(*)");
		if ($childrens > 0) {
			sed_redirect(sed_url("admin", "m=menu&msg=918", "", true));
		} else {
			$sql = sed_sql_query("DELETE FROM $db_menu WHERE menu_id = '" . $mid . "'");
			sed_log("Delete menu item #" . $mid, 'adm');
			sed_cache_clear('sed_menu');
			sed_redirect(sed_url("admin", "m=menu&msg=917", "", true));
		}

		break;
}

switch ($mn) {
	case "editmenu":

		$sql2 = sed_sql_query("SELECT * FROM $db_menu WHERE 1 ORDER BY menu_position ASC");
		while ($row2 = sed_sql_fetchassoc($sql2)) {
			$menu_row[$row2['menu_id']] = $row2;
		}

		$sql = sed_sql_query("SELECT * FROM $db_menu WHERE menu_id = " . (int)$mid);
		$row = sed_sql_fetchassoc($sql);

		$options = sed_menu_options($menu_row, 0, "");

		$parent_select = "<select name=\"mparent\">\n";
		$parent_select .= "<option value=\"0\">---</option>\n";
		foreach ($options as $key => $val) {
			$k = substr($key, 1);
			$selected = ($k == $row['menu_pid']) ? " selected" : "";
			$parent_select .= "<option value=\"" . substr($key, 1) . "\"" . $selected . ">" . $val . "</option>\n";
		}
		$parent_select .= "</select>\n";


		$t->assign(array(
			"MENU_UPDATE_SEND" => sed_url('admin', 'm=menu&a=update&mid=' . $mid),
			"MENU_UPDATE_PARENT" => $parent_select,
			"MENU_UPDATE_TITLE" => sed_textbox('mtitle', $row['menu_title']),
			"MENU_UPDATE_URL" => sed_textbox('murl', $row['menu_url']),
			"MENU_UPDATE_POSITION" => sed_textbox('mposition', $row['menu_position'], 3, 5),
			"MENU_UPDATE_VISIBLE" => sed_checkbox('mvisible', "", $row['menu_visible']),
			"MENU_UPDATE_TARGET" => sed_selectbox($row['menu_target'], 'mtarget', $target_arr)
		));

		$t->parse("ADMIN_MENU.MENU_DEFAULT.MENU_ADD");

		$t->parse("ADMIN_MENU.MENU_EDIT");

		break;

	default:

		$sql = sed_sql_query("SELECT * FROM $db_menu WHERE 1 ORDER BY menu_position ASC");
		while ($row = sed_sql_fetchassoc($sql)) {
			$menu_tree[$row['menu_pid']][$row['menu_id']] = $row;
			$menu_row[$row['menu_id']] = $row;
		}

		$options = sed_menu_options($menu_row, 0, "");
		$parent_select = "<select name=\"mparent\">\n";
		$parent_select .= "<option value=\"0\">---</option>\n";
		foreach ($options as $key => $val) {
			$k = substr($key, 1);
			$parent_select .= "<option value=\"" . substr($key, 1) . "\">" . $val . "</option>\n";
		}
		$parent_select .= "</select>\n";

		$t->assign(array(
			"MENU_ADD_SEND" => sed_url('admin', 'm=menu&a=add'),
			"MENU_ADD_PARENT" => $parent_select,
			"MENU_ADD_TITLE" => sed_textbox('mtitle', isset($mtitle) ? $mtitle : ''),
			"MENU_ADD_URL" => sed_textbox('murl', isset($murl) ? $murl : ''),
			"MENU_ADD_POSITION" => sed_textbox('mposition', isset($mposition) ? $mposition : '', 3, 5),
			"MENU_ADD_VISIBLE" => sed_checkbox('mvisible', isset($mvisible) ? $mvisible : '', 1),
			"MENU_ADD_TARGET" => sed_selectbox(isset($mtarget) ? $mtarget : '', 'mtarget', $target_arr)
		));

		$t->parse("ADMIN_MENU.MENU_DEFAULT.MENU_ADD");

		foreach ($options as $key => $val) {
			$k = substr($key, 1);
			$frow = $menu_row[$k];

			$t->assign(array(
				"MENU_ID" => $k,
				"MENU_TITLE" => $frow['menu_pid'] ? $val : "<strong>" . $val . "</strong>",
				"MENU_URL" => $frow['menu_url'],
				"MENU_POSITION" => sed_textbox('mposition[' . $k . ']', $frow['menu_position'], 2, 3),
				"MENU_DELETE_URL" => sed_url("admin", "m=menu&a=delete&mid=" . $k),
				"MENU_EDIT_URL" => sed_url("admin", "m=menu&mn=editmenu&mid=" . $k)
			));

			$t->parse("ADMIN_MENU.MENU_DEFAULT.MENU_LIST.MENU_LIST_ITEM");
		}

		$t->assign(array(
			"MENU_UPDATE_POSITION_SEND" => sed_url('admin', 'm=menu&a=updatepos'),
		));


		$t->parse("ADMIN_MENU.MENU_DEFAULT.MENU_LIST");

		$t->parse("ADMIN_MENU.MENU_DEFAULT");

		break;
}

$t->assign("ADMIN_MENU_TITLE", $admintitle);

$t->parse("ADMIN_MENU");

$adminmain .= $t->text("ADMIN_MENU");
