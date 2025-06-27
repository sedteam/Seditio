<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.page.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'any');
sed_block($usr['isadmin']);

$mn = sed_import('mn', 'G', 'TXT');

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=page")] = $L['Pages'];
$admintitle = $L['Pages'];

$totaldbpages = sed_sql_rowcount($db_pages);
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
$sys['pagesqueued'] = sed_sql_result($sql, 0, 'COUNT(*)');

$structure_mode[0] = $L['Default'];
$structure_mode[1] = $L['Group'];
$structure_mode[2] = $L['Sub'];

$t = new XTemplate(sed_skinfile('admin.page', false, true));

switch ($mn) {
	case 'structure':

		// ============= Structure ==============================

		list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
		sed_block($usr['isadmin']);

		$urlpaths[sed_url("admin", "m=page&mn=structure")] = $L['Structure'];
		$admintitle = $L['Structure'];

		if ($n == 'options') {
			if ($a == 'update') {

				/* === Hook === */
				$extp = sed_getextplugins('admin.page.structure.edit.first');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}
				/* ===== */

				$rpath = sed_import('rpath', 'P', 'TXT');
				$rtitle = sed_import('rtitle', 'P', 'TXT');
				$rtplmode = sed_import('rtplmode', 'P', 'INT');
				$rdesc = sed_import('rdesc', 'P', 'TXT');
				$rstext = sed_import('rstext', 'P', 'HTM'); //New v175
				$ricon = sed_import('ricon', 'P', 'TXT');
				$rgroup = sed_import('rgroup', 'P', 'BOL');

				$rthumb = sed_import('rthumb', 'P', 'TXT'); // New v178		
				$rseotitle = sed_import('rseotitle', 'P', 'TXT');
				$rseodesc = sed_import('rseodesc', 'P', 'TXT');
				$rseokeywords = sed_import('rseokeywords', 'P', 'TXT');
				$rseoh1 =  sed_import('rseoh1', 'P', 'TXT');

				$rseoindex = sed_import('rseoindex', 'P', 'BOL');
				$rseofollow = sed_import('rseofollow', 'P', 'BOL');

				$rallowcomments = sed_import('rallowcomments', 'P', 'BOL');  //New v173
				$rallowratings = sed_import('rallowratings', 'P', 'BOL');  //New v173

				if ($rtplmode == 1) {
					$rtpl = '';
				} elseif ($rtplmode == 3) {
					$rtpl = 'same_as_parent';
				} else {
					$rtpl = sed_import('rtplforced', 'P', 'ALS');
				}

				$sql = sed_sql_query("UPDATE $db_structure SET
    			structure_path = '" . sed_sql_prep($rpath) . "',
    			structure_tpl = '" . sed_sql_prep($rtpl) . "',
    			structure_title = '" . sed_sql_prep($rtitle) . "',
    			structure_desc = '" . sed_sql_prep($rdesc) . "',
    			structure_text = '" . sed_sql_prep($rstext) . "',
    			structure_icon = '" . sed_sql_prep($ricon) . "',
				structure_thumb = '" . sed_sql_prep($rthumb) . "',
				structure_seo_title = '" . sed_sql_prep($rseotitle) . "',
				structure_seo_desc = '" . sed_sql_prep($rseodesc) . "',
				structure_seo_keywords = '" . sed_sql_prep($rseokeywords) . "',
				structure_seo_h1 = '" . sed_sql_prep($rseoh1) . "',
				structure_seo_index = '" . sed_sql_prep($rseoindex) . "',
				structure_seo_follow = '" . sed_sql_prep($rseofollow) . "',
    			structure_allowcomments = '" . sed_sql_prep($rallowcomments) . "',
    			structure_allowratings = '" . sed_sql_prep($rallowratings) . "',
    			structure_group = '" . $rgroup . "'
    			WHERE structure_id = '" . $id . "'");

				sed_cache_clear('sed_cat');
				sed_redirect(sed_url("admin", "m=page&mn=structure&msg=917", "", true));
				exit;
			}

			$sql = sed_sql_query("SELECT * FROM $db_structure WHERE structure_id='$id' LIMIT 1");
			sed_die(sed_sql_numrows($sql) == 0);

			$handle = opendir("skins/" . $cfg['defaultskin'] . "/");
			$allskinfiles = array();

			while ($f = readdir($handle)) {
				if (($f != ".") && ($f != "..") && mb_strtolower(mb_substr($f, mb_strrpos($f, '.') + 1, 4)) == 'tpl') {
					$allskinfiles[] = $f;
				}
			}
			closedir($handle);

			$allskinfiles = implode(',', $allskinfiles);

			$row = sed_sql_fetchassoc($sql);

			$structure_id = $row['structure_id'];
			$structure_code = $row['structure_code'];
			$structure_path = $row['structure_path'];
			$structure_title = $row['structure_title'];
			$structure_desc = $row['structure_desc'];
			$structure_text = $row['structure_text'];  //New v175
			$structure_icon = $row['structure_icon'];
			$structure_group = $row['structure_group'];

			$structure_thumb = $row['structure_thumb']; //New v 178	
			$structure_seo_title = $row['structure_seo_title'];
			$structure_seo_desc = $row['structure_seo_desc'];
			$structure_seo_keywords = $row['structure_seo_keywords'];
			$structure_seo_h1 = $row['structure_seo_h1'];

			$structure_seo_index = $row['structure_seo_index'];
			$structure_seo_follow = $row['structure_seo_follow'];

			$structure_allowcomments = $row['structure_allowcomments'];
			$structure_allowratings = $row['structure_allowratings'];

			$form_allowcomments = sed_radiobox("rallowcomments", $yesno_arr, $structure_allowcomments);
			$form_allowratings = sed_radiobox("rallowratings", $yesno_arr, $structure_allowratings);

			if (empty($row['structure_tpl'])) {
				$check_rtplmode = 1;
			} elseif ($row['structure_tpl'] == 'same_as_parent') {
				$structure_tpl_sym = "*";
				$check_rtplmode = 3;
			} else {
				$structure_tpl_sym = "+";
				$check_rtplmode = 2;
			}

			$adminpath[] = array(sed_url("admin", "m=page&mn=structure&n=options&id=" . $id), sed_cc($structure_title));

			$rtplmode_arr = array(1 => $L['adm_tpl_empty'], 3 => $L['adm_tpl_parent'], 2 => $L['adm_tpl_forced']);

			$st_tpl = sed_radiobox("rtplmode", $rtplmode_arr, $check_rtplmode);

			$st_tpl .=  " <select name=\"rtplforced\" size=\"1\">";
			foreach ($sed_cat as $i => $x) {
				if ($i != 'all') {
					$selected = ($i == $row['structure_tpl']) ? "selected=\"selected\"" : '';
					$st_tpl .= "<option value=\"" . $i . "\" $selected> " . $x['tpath'] . "</option>";
				}
			}
			$st_tpl .= "</select><br/>";

			$pfs = sed_build_pfs($usr['id'], 'savestructure', 'rstext', $L['Mypfs']);
			$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, 'savestructure', 'rstext', $L['SFS']) : '';

			$t->assign(array(
				"STRUCTURE_UPDATE_SEND" => sed_url("admin", "m=page&mn=structure&n=options&a=update&id=" . $structure_id),
				"STRUCTURE_UPDATE_CODE" => $structure_code,
				"STRUCTURE_UPDATE_PATH" => sed_textbox('rpath', $structure_path, 16, 16),
				"STRUCTURE_UPDATE_TITLE" => sed_textbox('rtitle', $structure_title, 48, 64),
				"STRUCTURE_UPDATE_DESC" => sed_textbox('rdesc', $structure_desc, 64, 255),
				"STRUCTURE_UPDATE_ICON" => sed_textbox('ricon', $structure_icon, 64, 128),
				"STRUCTURE_UPDATE_THUMB" => sed_textbox('rthumb', $structure_thumb, 64, 255),
				"STRUCTURE_UPDATE_SEOTITLE" => sed_textbox('rseotitle', $structure_seo_title, 64, 255),
				"STRUCTURE_UPDATE_SEODESC" => sed_textbox('rseodesc', $structure_seo_desc, 64, 255),
				"STRUCTURE_UPDATE_SEOKEYWORDS" => sed_textbox('rseokeywords', $structure_seo_keywords, 64, 255),
				"STRUCTURE_UPDATE_SEOH1" => sed_textbox('rseoh1', $structure_seo_h1, 64, 255),
				"STRUCTURE_UPDATE_SEOINDEX" => sed_checkbox("rseoindex", "", $structure_seo_index),
				"STRUCTURE_UPDATE_SEOFOLLOW" => sed_checkbox("rseofollow", "", $structure_seo_follow),
				"STRUCTURE_UPDATE_TEXT" => sed_textarea('rstext', $structure_text, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Extended') . " " . $pfs,
				"STRUCTURE_UPDATE_GROUP" => sed_checkbox("rgroup", "", $structure_group),
				"STRUCTURE_UPDATE_TPL" => $st_tpl,
				"STRUCTURE_UPDATE_ALLOWCOMMENTS" => $form_allowcomments,
				"STRUCTURE_UPDATE_ALLOWRATINGS" => $form_allowratings,
				"STRUCTURE_UPDATE_MYPFS" => $pfs
			));

			/* === Hook === */
			$extp = sed_getextplugins('admin.page.structure.edit.tags');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			$t->parse("ADMIN_PAGE.STRUCTURE_UPDATE");
		} else {
			if ($a == 'update') {
				$s = sed_import('s', 'P', 'ARR');

				foreach ($s as $i => $k) {
					$sql1 = sed_sql_query("UPDATE $db_structure SET
    				structure_path='" . sed_sql_prep($s[$i]['rpath']) . "',
    				structure_group='" . $s[$i]['rgroup'] . "'
    				WHERE structure_id='" . $i . "'");
				}
				sed_auth_clear('all');
				sed_cache_clear('sed_cat');
				sed_redirect(sed_url("admin", "m=page&mn=structure&msg=917", "", true));
				exit;
			} elseif ($a == 'add') {
				$g = array('ncode', 'npath', 'ntitle', 'ndesc', 'nicon', 'nthumb', 'ngroup');
				foreach ($g as $k => $x) $$x = $_POST[$x];
				$group = (isset($group)) ? 1 : 0;
				sed_structure_newcat($ncode, $npath, $ntitle, $ndesc, $nicon, $ngroup);
				sed_redirect(sed_url("admin", "m=page&mn=structure&msg=301", "", true));
				exit;
			} elseif ($a == 'delete') {
				sed_check_xg();
				sed_structure_delcat($id, $c);
				sed_redirect(sed_url("admin", "m=page&mn=structure&msg=302", "", true));
				exit;
			}

			$sql = sed_sql_query("SELECT DISTINCT(page_cat), COUNT(*) FROM $db_pages WHERE 1 GROUP BY page_cat");

			while ($row = sed_sql_fetchassoc($sql)) {
				$pagecount[$row['page_cat']] = $row['COUNT(*)'];
			}

			$skinpath = SED_ROOT . "/skins/" . $skin . "/";

			$sql = sed_sql_query("SELECT * FROM $db_structure ORDER by structure_path ASC, structure_code ASC");
			$rows = array();
			while ($data_rows = sed_sql_fetchassoc($sql)) {
				$rows[] = $data_rows;
			}

			if ($cfg['structuresort']) {
				usort($rows, 'sed_structure_sort');
			}

			$jj = 0;
			foreach ($rows as $row) {
				$jj++;
				$structure_id = $row['structure_id'];
				$structure_code = $row['structure_code'];
				$structure_path = $row['structure_path'];
				$structure_title = $row['structure_title'];
				$structure_desc = $row['structure_desc'];
				$structure_icon = $row['structure_icon'];
				$structure_thumb = $row['structure_thumb'];
				$structure_seo_title = $row['structure_seo_title'];
				$structure_seo_desc = $row['structure_seo_desc'];
				$structure_seo_keywords = $row['structure_seo_keywords'];
				$structure_seo_h1 = $row['structure_seo_h1'];
				$structure_group = $row['structure_group'];
				$pathfieldlen = (mb_strpos($structure_path, ".") == 0) ? 3 : 9;
				$pathfieldimg = (mb_strpos($structure_path, ".") == 0) ? '' : "<img src=\"system/img/admin/join2.gif\" alt=\"\" /> ";
				$pagecount[$structure_code] = (isset($pagecount[$structure_code]) && $pagecount[$structure_code]) ? $pagecount[$structure_code] : "0";

				if (empty($row['structure_tpl'])) {
					$structure_tpl_sym = $L['adm_tpl_empty'];
				} elseif ($row['structure_tpl'] == 'same_as_parent') {
					$structure_tpl_sym = $L['adm_tpl_parent'];
				} else {
					$structure_tpl_sym = $L['adm_tpl_forced'] . ": <strong>" . $sed_cat[$row['structure_tpl']]['tpath'] . "</strong>";
				}

				$st_tpl = $structure_tpl_sym . ": ";
				$st_tpl .= "<span class=\"desc\">" . str_replace($skinpath, '', sed_skinfile(array('page', $sed_cat[$row['structure_code']]['tpl'])));
				$st_tpl .= "+";
				if ($sed_cat[$row['structure_code']]['group']) {
					$st_tpl .= str_replace($skinpath, '', sed_skinfile(array('list', 'group', $sed_cat[$row['structure_code']]['tpl'])));
				} else {
					$st_tpl .= str_replace($skinpath, '', sed_skinfile(array('list', $sed_cat[$row['structure_code']]['tpl'])));
				}
				$st_tpl .= "</span>";

				$st_group = "<select name=\"s[$structure_id][rgroup]\" size=\"1\">";
				for ($i = 0; $i < 3; $i++) {
					$selected = ($i == $structure_group) ? "selected=\"selected\"" : '';
					$st_group .= "<option value=\"$i\" $selected>" . $structure_mode[$i] . "</option>";
				}
				$st_group .=  "</select>";

				if (!$pagecount[$structure_code] > 0) {
					$t->assign("STRUCTURE_LIST_DELETE_URL", sed_url("admin", "m=page&mn=structure&a=delete&id=" . $structure_id . "&c=" . $row['structure_code'] . "&" . sed_xg()));
					$t->parse("ADMIN_PAGE.PAGE_STRUCTURE.STRUCTURE_LIST.STRUCTURE_LIST_DELETE");
				}

				$t->assign(array(
					"STRUCTURE_LIST_CODE" => $structure_code,
					"STRUCTURE_LIST_TITLE" => sed_link(sed_url("admin", "m=page&mn=structure&n=options&id=" . $structure_id . "&" . sed_xg()), sed_cc($structure_title)),
					"STRUCTURE_LIST_PATH" => $pathfieldimg . sed_textbox("s[" . $structure_id . "][rpath]", $structure_path, $pathfieldlen, 24),
					"STRUCTURE_LIST_TPL" => $st_tpl,
					"STRUCTURE_LIST_GROUP" => $st_group,
					"STRUCTURE_LIST_PAGECOUNT" => $pagecount[$structure_code],
					"STRUCTURE_LIST_OPEN_URL" => sed_url("list", "c=" . $structure_code),
					"STRUCTURE_LIST_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=page&io=" . $structure_code)
				));

				$t->parse("ADMIN_PAGE.PAGE_STRUCTURE.STRUCTURE_LIST");
			}

			$t->assign(array(
				"PAGE_STRUCTURE_SEND" => sed_url("admin", "m=page&mn=structure&a=update"),
				"PAGE_STRUCTURE_ADD_SEND" => sed_url("admin", "m=page&mn=structure&a=add"),
				"PAGE_STRUCTURE_ADD_CODE" => sed_textbox('ncode', isset($ncode) ? $ncode : '', 48, 255),
				"PAGE_STRUCTURE_ADD_PATH" => sed_textbox('npath', isset($npath) ? $npath : '', 16, 16),
				"PAGE_STRUCTURE_ADD_TITLE" => sed_textbox('ntitle', isset($ntitle) ? $ntitle : '', 48, 64),
				"PAGE_STRUCTURE_ADD_DESC" => sed_textbox('ndesc', isset($ndesc) ? $ndesc : '', 64, 255),
				"PAGE_STRUCTURE_ADD_ICON" => sed_textbox('nicon', isset($nicon) ? $nicon : '', 64, 128),
				"PAGE_STRUCTURE_ADD_THUMB" => sed_textbox('nthumb', isset($nicon) ? $nicon : '', 64, 255),
				"PAGE_STRUCTURE_ADD_GROUP" => sed_checkbox('ngroup')
			));

			$t->parse("ADMIN_PAGE.PAGE_STRUCTURE");
		}

		break;

	case 'catorder':

		// ============= Categories order ==============================

		list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
		sed_block($usr['isadmin']);

		$urlpaths[sed_url("admin", "m=page&mn=catorder")] = $L['adm_sortingorder'];
		$admintitle = $L['adm_sortingorder'];

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

		$options_way = array(
			'asc' => $L['Ascending'],
			'desc' => $L['Descending']
		);

		if ($a == 'reorder') {
			$s = sed_import('s', 'P', 'ARR');

			foreach ($s as $i => $k) {
				$order = $s[$i]['order'] . '.' . $s[$i]['way'];
				$sql = sed_sql_query("UPDATE $db_structure SET structure_order='$order' WHERE structure_id='$i'");
			}
			sed_cache_clear('sed_cat');
			sed_redirect(sed_url("admin", "m=page&mn=catorder", "#catorder", true));
		}

		$sql = sed_sql_query("SELECT * FROM $db_structure ORDER by structure_path, structure_code");

		$rows = array();
		while ($data_rows = sed_sql_fetchassoc($sql)) {
			$rows[] = $data_rows;
		}

		if ($cfg['structuresort']) {
			usort($rows, 'sed_structure_sort');
		}

		foreach ($rows as $row) {
			$structure_id = $row['structure_id'];
			$structure_code = $row['structure_code'];
			$structure_path = $row['structure_path'];
			$structure_title = $row['structure_title'];
			$structure_desc = $row['structure_desc'];
			$structure_order = $row['structure_order'];

			$raw = explode('.', $structure_order);
			$sort = $raw[0];
			$way = $raw[1];

			reset($options_sort);
			reset($options_way);

			$form_sort = "<select name=\"s[" . $structure_id . "][order]\" size=\"1\">";
			foreach ($options_sort as $i => $x) {
				$selected = ($i == $sort) ? 'selected="selected"' : '';
				$form_sort .= "<option value=\"$i\" $selected>" . $x . "</option>";
			}
			$form_sort .= "</select> ";

			$form_way = "<select name=\"s[" . $structure_id . "][way]\" size=\"1\">";
			foreach ($options_way as $i => $x) {
				$selected = ($i == $way) ? 'selected="selected"' : '';
				$form_way .= "<option value=\"$i\" $selected>" . $x . "</option>";
			}
			$form_way .= "</select> ";

			$t->assign(array(
				"STRUCTURE_LIST_CODE" => $structure_code,
				"STRUCTURE_LIST_PATH" => $structure_path,
				"STRUCTURE_LIST_TITLE" => sed_cc($structure_title),
				"STRUCTURE_LIST_ORDER" => $form_sort . ' ' . $form_way
			));

			$t->parse("ADMIN_PAGE.PAGE_SORTING.SORTING_STRUCTURE_LIST");
		}

		$t->assign(array(
			"PAGE_SORTING_SEND" => sed_url("admin", "m=page&mn=catorder&a=reorder")
		));

		$t->parse("ADMIN_PAGE.PAGE_SORTING");

		break;

	default:

		// ============= Pages queued ==============================

		$id = sed_import('id', 'G', 'INT');

		if ($a == 'validate') {
			sed_check_xg();

			$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='$id'");
			if ($row = sed_sql_fetchassoc($sql)) {
				$usr['isadmin_local'] = sed_auth('page', $row['page_cat'], 'A');
				sed_block($usr['isadmin_local']);
				$sql = sed_sql_query("UPDATE $db_pages SET page_state=0 WHERE page_id='$id'");
				sed_cache_clear('latestpages');
				sed_redirect((!empty($redirect)) ? base64_decode($redirect) : sed_url("admin", "m=page&mn=queue", "", true));
				exit;
			} else {
				sed_die();
			}
		}

		if ($a == 'unvalidate') {
			sed_check_xg();

			$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='$id'");
			if ($row = sed_sql_fetchassoc($sql)) {
				$usr['isadmin_local'] = sed_auth('page', $row['page_cat'], 'A');
				sed_block($usr['isadmin_local']);
				$sql = sed_sql_query("UPDATE $db_pages SET page_state=1 WHERE page_id='$id'");
				sed_cache_clear('latestpages');
				//sed_redirect(sed_url("list", "c=".$row['page_cat'], "", true));
				sed_redirect((!empty($redirect)) ? base64_decode($redirect) : sed_url("admin", "m=page&mn=queue", "", true));
				exit;
			} else {
				sed_die();
			}
		}

		$sql = sed_sql_query("SELECT p.*, u.user_name 
    	FROM $db_pages as p 
    	LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid 
    	WHERE page_state=1 ORDER by page_id DESC");

		while ($row = sed_sql_fetchassoc($sql)) {
			$row['page_title'] = empty($row['page_title']) ? '(empty title)' : $row['page_title'];
			$sys['catcode'] = $row['page_cat'];

			$t->assign(array(
				"PAGE_LIST_ID" => $row['page_id'],
				"PAGE_LIST_TITLE" => sed_link(sed_url("page", "id=" . $row['page_id']), sed_cc($row['page_title'])),
				"PAGE_LIST_CATPATH" => sed_build_catpath($row['page_cat'], "<a href=\"%1\$s\">%2\$s</a>"),
				"PAGE_LIST_DATE" => sed_build_date($cfg['dateformat'], $row['page_date']),
				"PAGE_LIST_OWNER" => sed_build_user($row['page_ownerid'], sed_cc($row['user_name'])),
				"PAGE_LIST_VALIDATE" => sed_link(sed_url("admin", "m=page&mn=queue&a=validate&id=" . $row['page_id'] . "&" . sed_xg()), $L['Validate'], array('class' => 'btn btn-adm'))
			));

			$t->parse("ADMIN_PAGE.PAGE_QUEUE.PAGE_QUEUE_LIST");
		}

		if (sed_sql_numrows($sql) == 0) {
			$t->parse("ADMIN_PAGE.PAGE_QUEUE.WARNING");
		}

		$t->parse("ADMIN_PAGE.PAGE_QUEUE");

		break;
}

$t->assign("ADMIN_PAGE_TITLE", $admintitle);

$t->parse("ADMIN_PAGE");

$adminmain .= $t->text("ADMIN_PAGE");

$adminhelp = $L['adm_help_page'];
