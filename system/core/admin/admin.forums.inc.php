<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.forums.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Forums & categories
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$id = sed_import('id', 'G', 'INT');

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=forums")] =  $L['Forums'];

$admintitle = $L['Forums'];

$t = new XTemplate(sed_skinfile('admin.forums', false, true));

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_FORUMS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=core&p=forums"));
	$t->parse("ADMIN_FORUMS.FORUMS_BUTTONS.FORUMS_BUTTONS_CONFIG");
	$t->assign("BUTTON_FORUMS_STRUCTURE_URL", sed_url("admin", "m=forums&s=structure"));
	$t->parse("ADMIN_FORUMS.FORUMS_BUTTONS.FORUMS_BUTTONS_STRUCTURE");
}

if (!isset($sed_forums_str) && $cfg['disable_forums']) {
	$sed_forums_str = sed_load_forum_structure();
	sed_cache_store('sed_forums_str', $sed_forums_str, 3600);
}

$t->parse("ADMIN_FORUMS.FORUMS_BUTTONS");

if ($n == 'edit') {
	if ($a == 'update') {
		sed_check_xg();
		$rstate = sed_import('rstate', 'P', 'BOL');
		$rtitle = sed_import('rtitle', 'P', 'TXT');
		$rdesc = sed_import('rdesc', 'P', 'TXT');
		$ricon = sed_import('ricon', 'P', 'TXT');
		$rautoprune = sed_import('rautoprune', 'P', 'INT');
		$rcat = sed_import('rcat', 'P', 'TXT');
		$rallowusertext = sed_import('rallowusertext', 'P', 'BOL');
		$rallowbbcodes = sed_import('rallowbbcodes', 'P', 'BOL');
		$rallowsmilies = sed_import('rallowsmilies', 'P', 'BOL');
		$rallowprvtopics = sed_import('rallowprvtopics', 'P', 'BOL');
		$rcountposts = sed_import('rcountposts', 'P', 'BOL');
		$rtitle = sed_sql_prep($rtitle);
		$rdesc = sed_sql_prep($rdesc);
		$rcat = sed_sql_prep($rcat);
		$rparentcat = sed_import('rparentcat', 'P', 'INT'); // New in sed 172

		$sql = sed_sql_query("SELECT fs_id, fs_order, fs_category FROM $db_forum_sections WHERE fs_id='" . $id . "'");
		sed_die(sed_sql_numrows($sql) == 0);
		$row_cur = sed_sql_fetchassoc($sql);

		if ($row_cur['fs_category'] != $rcat) {
			$sql = sed_sql_query("SELECT fs_order FROM $db_forum_sections WHERE fs_category='" . $rcat . "' ORDER BY fs_order DESC LIMIT 1");

			if (sed_sql_numrows($sql) > 0) {
				$row_oth = sed_sql_fetchassoc($sql);
				$rorder = $row_oth['fs_order'] + 1;
			} else {
				$rorder = 100;
			}

			$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_order=fs_order-1 WHERE fs_category='" . $row_cur['fs_category'] . "' AND fs_order>" . $row_cur['fs_order']);
			$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_order='$rorder' WHERE fs_id='$id'");
		}

		$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_state='$rstate', fs_title='$rtitle', fs_desc='$rdesc', fs_category='$rcat', fs_parentcat='$rparentcat', fs_icon='$ricon', fs_autoprune='$rautoprune', fs_allowusertext='$rallowusertext', fs_allowbbcodes='$rallowbbcodes', fs_allowsmilies='$rallowsmilies', fs_allowprvtopics='$rallowprvtopics', fs_countposts='$rcountposts' WHERE fs_id='$id'");

		sed_redirect(sed_url("admin", "m=forums", "", true));
		exit;
	} elseif ($a == 'delete') {
		sed_check_xg();
		sed_auth_clear('all');
		$num = sed_forum_deletesection($id);
		sed_redirect(sed_url("message", "msg=916&rc=103&num=" . $num, "", true));
		exit;
	} elseif ($a == 'resync') {
		sed_check_xg();
		sed_forum_resync($id);
	}

	$sql = sed_sql_query("SELECT * FROM $db_forum_sections WHERE fs_id='$id'");
	sed_die(sed_sql_numrows($sql) == 0);
	$row = sed_sql_fetchassoc($sql);

	$fs_id = $row['fs_id'];
	$fs_parentcat = $row['fs_parentcat'];  //New Sed 172
	$fs_state = $row['fs_state'];
	$fs_order = $row['fs_order'];
	$fs_title = $row['fs_title'];
	$fs_desc = $row['fs_desc'];
	$fs_category = $row['fs_category'];
	$fs_icon = $row['fs_icon'];
	$fs_autoprune = $row['fs_autoprune'];
	$fs_allowusertext = $row['fs_allowusertext'];
	$fs_allowbbcodes = $row['fs_allowbbcodes'];
	$fs_allowsmilies = $row['fs_allowsmilies'];
	$fs_allowprvtopics = $row['fs_allowprvtopics'];
	$fs_countposts = $row['fs_countposts'];

	$form_state = sed_radiobox("rstate", $yesno_arr, $fs_state);
	$form_allowusertext = sed_radiobox("rallowusertext", $yesno_arr, $fs_allowusertext);
	$form_allowbbcodes = sed_radiobox("rallowbbcodes", $yesno_arr, $fs_allowbbcodes);
	$form_allowsmilies = sed_radiobox("rallowsmilies", $yesno_arr, $fs_allowsmilies);
	$form_allowprvtopics = sed_radiobox("rallowprvtopics", $yesno_arr, $fs_allowprvtopics);
	$form_countposts = sed_radiobox("rcountposts", $yesno_arr, $fs_countposts);

	$urlpaths[sed_url("admin", "m=forums&n=edit&id=" . $id)] = sed_cc($fs_title);
	$admintitle = sed_cc($fs_title);

	$form_parent_cat = "<select name=\"rparentcat\"><option value=\"0\">--</option>";

	$sql = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category FROM $db_forum_sections 
                        AS s LEFT JOIN $db_forum_structure AS n ON n.fn_code = s.fs_category 
                        WHERE fs_id <> '$id' AND fs_parentcat < 1 AND fs_category = '" . $fs_category . "' 
                        ORDER by fn_path ASC, fs_order ASC");

	while ($row = sed_sql_fetchassoc($sql)) {
		$parent_name = sed_build_forums($row['fs_id'], $row['fs_title'], $row['fs_category'], FALSE);
		$selected = ($fs_parentcat == $row['fs_id']) ? " selected=\"selected\"" : "";
		$form_parent_cat .= "<option value=\"" . $row['fs_id'] . "\"" . $selected . ">" . $parent_name . "</option>";
	}

	$form_parent_cat .= "</select>";

	if (file_exists($fs_icon)) {
		$fs_icon_img = " <img src=\"" . $fs_icon . "\" alt=\"\" />";
	}

	if ($usr['isadmin']) {
		$t->assign(array(
			"FS_UPDATE_RESYNC" => "<a href=\"" . sed_url("admin", "m=forums&n=edit&a=resync&id=" . $fs_id . "&" . sed_xg()) . "\">" . $L['Resync'] . "</a>",
			"FS_UPDATE_DELETE" => "<a href=\"" . sed_url("admin", "m=forums&n=edit&a=delete&id=" . $fs_id . "&" . sed_xg()) . "\">" . $out['ic_delete'] . "</a>"
		));

		$t->parse("ADMIN_FORUMS.FS_UPDATE.FS_ADMIN");
	}

	$t->assign(array(
		"FS_UPDATE_FORM_TITLE" => $L['editdeleteentries'] . " : " . sed_cc($fs_title),
		"FS_UPDATE_SEND" => sed_url("admin", "m=forums&n=edit&a=update&id=" . $fs_id . "&" . sed_xg()),
		"FS_UPDATE_ID" => $fs_id,
		"FS_UPDATE_PARENTCAT" => $form_parent_cat,
		"FS_UPDATE_CATEGORY" => sed_selectbox_forumcat($fs_category, 'rcat'),
		"FS_UPDATE_TITLE" => "<input type=\"text\" class=\"text\" name=\"rtitle\" value=\"" . sed_cc($fs_title) . "\" size=\"56\" maxlength=\"128\" />",
		"FS_UPDATE_DESC" => "<input type=\"text\" class=\"text\" name=\"rdesc\" value=\"" . sed_cc($fs_desc) . "\" size=\"56\" maxlength=\"255\" />",
		"FS_UPDATE_ICON" => "<input type=\"text\" class=\"text\" name=\"ricon\" value=\"" . sed_cc($fs_icon) . "\" size=\"40\" maxlength=\"255\" />" . $fs_icon_img,
		"FS_UPDATE_ALLOWUSERTEXT" => $form_allowusertext,
		"FS_UPDATE_ALLOWBBCODES" => $form_allowbbcodes,
		"FS_UPDATE_ALLOWSMILIES" => $form_allowsmilies,
		"FS_UPDATE_ALLOWPRIVATETOPICS" => $form_allowprvtopics,
		"FS_UPDATE_COUNTPOST" => $form_countposts,
		"FS_UPDATE_STATE" => $form_state,
		"FS_UPDATE_AUTOPRUNE" => "<input type=\"text\" class=\"text\" name=\"rautoprune\" value=\"" . $fs_autoprune . "\" size=\"3\" maxlength=\"7\" />"
	));

	$t->parse("ADMIN_FORUMS.FS_UPDATE");
} else {
	if ($a == 'order') {
		$w = sed_import('w', 'G', 'ALP', 4);

		$sql = sed_sql_query("SELECT fs_order, fs_category FROM $db_forum_sections WHERE fs_id='" . $id . "'");
		sed_die(sed_sql_numrows($sql) == 0);
		$row_cur = sed_sql_fetchassoc($sql);

		if ($w == 'up') {
			$sql = sed_sql_query("SELECT fs_id, fs_order FROM $db_forum_sections WHERE fs_category='" . $row_cur['fs_category'] . "' AND fs_order<'" . $row_cur['fs_order'] . "' ORDER BY fs_order DESC LIMIT 1");
		} else {
			$sql = sed_sql_query("SELECT fs_id, fs_order FROM $db_forum_sections WHERE fs_category='" . $row_cur['fs_category'] . "' AND fs_order>'" . $row_cur['fs_order'] . "' ORDER BY fs_order ASC LIMIT 1");
		}

		if (sed_sql_numrows($sql) > 0) {
			$row_oth = sed_sql_fetchassoc($sql);
			$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_order='" . $row_oth['fs_order'] . "' WHERE fs_id='" . $id . "'");
			$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_order='" . $row_cur['fs_order'] . "' WHERE fs_id='" . $row_oth['fs_id'] . "'");
		}

		sed_redirect(sed_url("admin", "m=forums", "", true));
		exit;
	} elseif ($a == 'add') {
		$g = array('ntitle', 'ndesc', 'ncat');
		foreach ($g as $k => $x) $$x = $_POST[$x];

		if (!empty($ntitle)) {
			$sql1 = sed_sql_query("SELECT fs_order FROM $db_forum_sections WHERE fs_category='" . sed_sql_prep($ncat) . "' ORDER BY fs_order DESC LIMIT 1");
			if ($row1 = sed_sql_fetchassoc($sql1)) {
				$nextorder = $row1['fs_order'] + 1;
			} else {
				$nextorder = 100;
			}

			$sql = sed_sql_query("INSERT INTO $db_forum_sections (fs_order, fs_title, fs_desc, fs_category, fs_icon, fs_autoprune, fs_allowusertext, fs_allowbbcodes, fs_allowsmilies, fs_allowprvtopics, fs_countposts) VALUES ('" . (int)$nextorder . "', '" . sed_sql_prep($ntitle) . "', '" . sed_sql_prep($ndesc) . "', '" . sed_sql_prep($ncat) . "', 'system/img/admin/forums.png', 0, 1, 1, 1, 0, 1)");

			$forumid = sed_sql_insertid();

			foreach ($sed_groups as $k => $v) {
				if ($k == 1 || $k == 2) {
					$ins_auth = 1;
					$ins_lock = 254;
				} elseif ($k == 3) {
					$ins_auth = 0;
					$ins_lock = 255;
				} elseif ($k == 5) {
					$ins_auth = 255;
					$ins_lock = 255;
				} else {
					$ins_auth = 3;
					$ins_lock = ($k == 4) ? 128 : 0;
				}

				$sql = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$v['id'] . ", 'forums', " . (int)$forumid . ", " . (int)$ins_auth . ", " . (int)$ins_lock . ", " . (int)$usr['id'] . ")");
			}
			sed_auth_reorder();
			sed_auth_clear('all');
			sed_redirect(sed_url("admin", "m=forums", "", true));
		}
	}

	$sql = sed_sql_query("SELECT s.*, n.* FROM $db_forum_sections AS s LEFT JOIN
    $db_forum_structure AS n ON n.fn_code=s.fs_category
    ORDER by fn_path ASC, fs_order ASC, fs_title ASC");

	$prev_cat = '';
	$line = 1;

	while ($row = sed_sql_fetchassoc($sql)) {
		$fs_id = $row['fs_id'];
		$fs_state = $row['fs_state'];
		$fs_order = $row['fs_order'];
		$fs_title = sed_cc($row['fs_title']);
		$fs_desc = sed_cc($row['fs_desc']);
		$fs_category = $row['fs_category'];

		if ($fs_category != $prev_cat) {

			$t->assign(array(
				"FN_CAT_URL" => sed_url("admin", "m=forums&s=structure&n=options&id=" . $row['fn_id']),
				"FN_CAT_TITLE" => sed_cc($row['fn_title']),
				"FN_CAT_PATH" => $row['fn_path']
			));

			$t->parse("ADMIN_FORUMS.FS_CAT.FS_LIST.FN_CAT");

			$prev_cat = $fs_category;
			$line = 1;
		}

		$t->assign(array(
			"FS_LIST_TITLE" => "<a href=\"" . sed_url("admin", "m=forums&n=edit&id=" . $fs_id) . "\">" . $fs_title . "</a>",
			"FS_LIST_ORDER_UP_URL" => sed_url("admin", "m=forums&id=" . $fs_id . "&a=order&w=up"),
			"FS_LIST_ORDER_DOWN_URL" => sed_url("admin", "m=forums&id=" . $fs_id . "&a=order&w=down"),
			"FS_LIST_ALLOWPRIWATETOPICS" => $sed_yesno[$row['fs_allowprvtopics']],
			"FS_LIST_TOPICCOUNT" => $row['fs_topiccount'],
			"FS_LIST_POSTCONT" => $row['fs_postcount'],
			"FS_LIST_VIEWCOUNT" => $row['fs_viewcount'],
			"FS_LIST_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=forums&io=" . $row['fs_id']),
			"FS_LIST_OPEN_URL" => sed_url("forums", "m=topics&s=" . $fs_id)
		));

		$t->parse("ADMIN_FORUMS.FS_CAT.FS_LIST");

		$line++;
	}

	$t->assign(array(
		"FS_ADD_SEND" => sed_url("admin", "m=forums&a=add"),
		"FS_ADD_CATEGORY" => sed_selectbox_forumcat('', 'ncat'),
		"FS_ADD_TITLE" => "<input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"64\" maxlength=\"128\" />",
		"FS_ADD_DESC" => "<input type=\"text\" class=\"text\" name=\"ndesc\" value=\"\" size=\"64\" maxlength=\"255\" />"
	));

	$t->parse("ADMIN_FORUMS.FS_CAT");
}

$t->assign("ADMIN_FORUMS_TITLE", $admintitle);

$t->parse("ADMIN_FORUMS");

$adminmain .= $t->text("ADMIN_FORUMS");
