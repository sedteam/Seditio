<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.users.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Users
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$g = sed_import('g', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=users")] =  $L['Users'];

$admintitle = $L['Users'];


$t = new XTemplate(sed_skinfile('admin.users', false, true));

if (sed_auth('admin', 'a', 'A')) {
	$t->assign("BUTTON_USERS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=core&p=users"));
	$t->parse("ADMIN_USERS.USERS_BUTTONS.USERS_BUTTONS_CONFIG");
}

if (sed_auth('users', 'a', 'A')) {
	$t->assign("BUTTON_USERS_BANLIST_URL", sed_url("admin", "m=banlist"));
	$t->parse("ADMIN_USERS.USERS_BUTTONS.USERS_BUTTONS_BANLIST");
}

$t->parse("ADMIN_USERS.USERS_BUTTONS");

switch ($n) {
	case 'add':

		$ntitle = sed_import('ntitle', 'P', 'TXT');
		$ndesc = sed_import('ndesc', 'P', 'TXT');
		$ncolor = sed_import('ncolor', 'P', 'TXT');
		$nicon = sed_import('nicon', 'P', 'TXT');
		$nalias = sed_import('nalias', 'P', 'TXT');
		$nlevel = sed_import('nlevel', 'P', 'LVL');
		$nmaxsingle = sed_import('nmaxsingle', 'P', 'INT');
		$nmaxtotal = sed_import('nmaxtotal', 'P', 'INT');
		$ncopyrightsfrom = sed_import('ncopyrightsfrom', 'P', 'INT');
		$ndisabled = sed_import('ndisabled', 'P', 'BOL');
		$nhidden = sed_import('nhidden', 'P', 'BOL');
		$ntitle = (empty($ntitle)) ? '???' : $ntitle;

		$sql = sed_sql_query("INSERT INTO $db_groups (grp_alias, grp_level, grp_disabled, grp_hidden, grp_title, grp_desc, grp_icon, grp_color, grp_pfs_maxfile, grp_pfs_maxtotal, grp_ownerid) VALUES ('" . sed_sql_prep($nalias) . "', " . (int)$nlevel . ", " . (int)$ndisabled . ", " . (int)$nhidden . ", '" . sed_sql_prep($ntitle) . "', '" . sed_sql_prep($ndesc) . "', '" . sed_sql_prep($nicon) . "', '" . sed_sql_prep($ncolor) . "', " . (int)$nmaxsingle . ", " . (int)$nmaxtotal . ", " . (int)$usr['id'] . ")");

		$grp_id = sed_sql_insertid();

		$sql = sed_sql_query("SELECT * FROM $db_auth WHERE auth_groupid='" . $ncopyrightsfrom . "' order by auth_code ASC, auth_option ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			$sql1 = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$grp_id . ", '" . $row['auth_code'] . "', '" . $row['auth_option'] . "', " . (int)$row['auth_rights'] . ", 0, " . (int)$usr['id'] . ")");
		}

		sed_auth_reorder();
		sed_cache_clear('sed_groups');
		sed_redirect(sed_url("admin", "m=users", "", true));
		exit;
		break;

	case 'edit':

		if ($a == 'update') {
			$rtitle = sed_import('rtitle', 'P', 'TXT');
			$rdesc = sed_import('rdesc', 'P', 'TXT');
			$ricon = sed_import('ricon', 'P', 'TXT');
			$rcolor = sed_import('rcolor', 'P', 'TXT');
			$ralias = sed_import('ralias', 'P', 'TXT');
			$rlevel = sed_import('rlevel', 'P', 'LVL');
			$rmaxfile = sed_import('rmaxfile', 'P', 'INT');
			$rmaxtotal = sed_import('rmaxtotal', 'P', 'INT');
			$rdisabled = ($g < 6) ? 0 : sed_import('rdisabled', 'P', 'BOL');
			$rhidden = ($g == 4) ? 0 : sed_import('rhidden', 'P', 'BOL');
			$rtitle = sed_sql_prep($rtitle);
			$rdesc = sed_sql_prep($rdesc);
			$ricon = sed_sql_prep($ricon);
			$ralias = sed_sql_prep($ralias);

			$sql = sed_sql_query("UPDATE $db_groups SET grp_title='$rtitle', grp_desc='$rdesc', grp_icon='$ricon', grp_color='$rcolor', grp_alias='$ralias', grp_level='$rlevel', grp_pfs_maxfile='$rmaxfile', grp_pfs_maxtotal='$rmaxtotal', grp_disabled='$rdisabled', grp_hidden='$rhidden' WHERE grp_id='$g'");

			sed_cache_clear('sed_groups');
			sed_redirect(sed_url("admin", "m=users", "", true));
			exit;
		} elseif ($a == 'delete' && $g > 5) {
			$sql = sed_sql_query("DELETE FROM $db_groups WHERE grp_id='$g'");
			$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_groupid='$g'");
			$sql = sed_sql_query("DELETE FROM $db_groups_users WHERE gru_groupid='$g'");
			sed_auth_clear('all');
			sed_cache_clear('sed_groups');
			sed_redirect(sed_url("admin", "m=users", "", true));
			exit;
		}

		$sql = sed_sql_query("SELECT * FROM $db_groups WHERE grp_id='$g'");
		sed_die(sed_sql_numrows($sql) == 0);
		$row = sed_sql_fetchassoc($sql);

		$sql1 = sed_sql_query("SELECT COUNT(*) FROM $db_groups_users WHERE gru_groupid='$g'");
		$row['grp_memberscount'] = sed_sql_result($sql1, 0, "COUNT(*)");

		$row['grp_title'] = sed_cc($row['grp_title']);
		$row['grp_desc'] = sed_cc($row['grp_desc']);
		$row['grp_icon'] = sed_cc($row['grp_icon']);
		$row['grp_alias'] = sed_cc($row['grp_alias']);

		$urlpaths[sed_url("admin", "m=users&n=edit&g=" . $g)] =  $row['grp_title'];
		$admintitle = $row['grp_title'];

		if ($g > 5 && $row['grp_memberscount'] == 0) {
			$t->assign(array(
				"USERS_EDIT_DELETE_URL" => sed_url("admin", "m=users&n=edit&a=delete&g=" . $g . "&" . sed_xg())
			));
			$t->parse("ADMIN_USERS.USERS_EDIT.USERS_EDIT_ADMIN");
		}

		$grpcolor = "<select name=\"rcolor\" size=\"1\" style=\"color:#202020; background-color:" . $row['grp_color'] . ";\">";
		foreach ($cfg['group_colors'] as $color) {
			$selected = ($color == $row['grp_color']) ? "selected=\"selected\"" : '';
			$grpcolor .= "<option value=\"" . $color . "\" " . $selected . " style=\"color:#202020; background-color:" . $color . ";\">" . $color . "</option>";
		}
		$grpcolor .= "</select>";

		if ($g > 5) {
			$grpdisable = sed_radiobox("rdisabled", $yesno_revers_arr, $row['grp_disabled']);
		} else {
			$grpdisable = $L['Yes'];
		}

		if ($g != 4) {
			$grphidden = sed_radiobox("rhidden", $yesno_arr, $row['grp_hidden']);
		} else {
			$grphidden = $L['No'];
		}

		$grplevel = "<select name=\"rlevel\" size=\"1\">";
		for ($i = 1; $i < 100; $i++) {
			$selected = ($i == $row['grp_level']) ? "selected=\"selected\"" : '';
			$grplevel .= "<option value=\"$i\" $selected>" . $i . "</option>";
		}
		$grplevel .= "</select>";

		$t->assign(array(
			"USERS_EDIT_SEND" => sed_url("admin", "m=users&n=edit&a=update&g=" . $g),
			"USERS_EDIT_TITLE" => sed_textbox('rtitle', $row['grp_title'], 40, 64),
			"USERS_EDIT_DESC" => sed_textbox('rdesc', $row['grp_desc'], 40, 64),
			"USERS_EDIT_ICON" => sed_textbox('ricon', $row['grp_icon'], 40, 128),
			"USERS_EDIT_ALIAS" => sed_textbox('ralias', $row['grp_alias'], 16, 24),
			"USERS_EDIT_COLOR" => $grpcolor,
			"USERS_EDIT_MAXFILESIZE" => sed_textbox('rmaxfile', $row['grp_pfs_maxfile'], 16, 24),
			"USERS_EDIT_MAXTOTALSIZE" => sed_textbox('rmaxtotal', $row['grp_pfs_maxtotal'], 16, 24),
			"USERS_EDIT_GRPDISABLE" => $grpdisable,
			"USERS_EDIT_GRPHIDDEN" => $grphidden,
			"USERS_EDIT_GRPLEVEL" => $grplevel,
			"USERS_EDIT_RIGHT_URL" => sed_url("admin", "m=rights&g=" . $g)
		));

		$t->parse("ADMIN_USERS.USERS_EDIT");

		break;

	default:

		$sql = sed_sql_query("SELECT DISTINCT(gru_groupid), COUNT(*) FROM $db_groups_users WHERE 1 GROUP BY gru_groupid");
		while ($row = sed_sql_fetchassoc($sql)) {
			$members[$row['gru_groupid']] = $row['COUNT(*)'];
		}

		$sql = sed_sql_query("SELECT DISTINCT(user_maingrp), COUNT(*) FROM $db_users WHERE 1 GROUP BY user_maingrp");
		while ($row = sed_sql_fetchassoc($sql)) {
			$members_main[$row['user_maingrp']] = $row['COUNT(*)'];
		}

		$sql = sed_sql_query("SELECT grp_id, grp_title, grp_disabled, grp_hidden FROM $db_groups WHERE 1 order by grp_level DESC, grp_id DESC");

		if (sed_sql_numrows($sql) > 0) {
			while ($row = sed_sql_fetchassoc($sql)) {
				$row['grp_hidden'] = ($row['grp_hidden']) ? '1' : '0';

				$members[$row['grp_id']] = (!isset($members[$row['grp_id']])) ? '0' : $members[$row['grp_id']];
				$members_main[$row['grp_id']] = (!isset($members_main[$row['grp_id']])) ? '0' : $members_main[$row['grp_id']];

				$t->assign(array(
					"GROUP_LIST_ID" => $row['grp_id'],
					"GROUP_LIST_URL" => sed_url("admin", "m=users&n=edit&g=" . $row['grp_id']),
					"GROUP_LIST_TITLE" => sed_cc($row['grp_title']),
					"GROUP_LIST_GRP_COUNT" => "<a href=\"" . sed_url("users", "gm=" . $row['grp_id']) . "\">" . $members[$row['grp_id']] . "</a>",
					"GROUP_LIST_MAINGRP_COUNT" => "<a href=\"" . sed_url("users", "g=" . $row['grp_id']) . "\">" . $members_main[$row['grp_id']] . "</a>",
					"GROUP_LIST_DISABLE" => $sed_yesno[!$row['grp_disabled']],
					"GROUP_LIST_COUNT" => $sed_yesno[$row['grp_hidden']],
					"GROUP_LIST_RIGHT_URL" => sed_url("admin", "m=rights&g=" . $row['grp_id'])
				));

				$t->parse("ADMIN_USERS.USERS_GROUPS.GROUP_LIST");
			}
		}

		$grpcolor = "<select name=\"ncolor\" size=\"1\">";
		foreach ($cfg['group_colors'] as $color) {
			$grpcolor .= "<option value=\"" . $color . "\" style=\"color:#202020; background-color:" . $color . ";\">" . $color . "</option>";
		}
		$grpcolor .= "</select>";

		$grplevel = "<select name=\"nlevel\" size=\"1\">";
		for ($i = 1; $i < 100; $i++) {
			$grplevel .= "<option value=\"$i\">" . $i . "</option>";
		}
		$grplevel .= "</select>";

		$grpdisable = sed_radiobox("ndisabled", $yesno_revers_arr, 0);
		$grphidden = sed_radiobox("nhidden", $yesno_arr, 0);

		$t->assign(array(
			"GROUP_ADD_SEND" => sed_url("admin", "m=users&n=add"),
			"GROUP_ADD_TITLE" => sed_textbox('ntitle', '', 40, 64),
			"GROUP_ADD_DESC" => sed_textbox('ndesc', '', 40, 64),
			"GROUP_ADD_ICON" => sed_textbox('nicon', '', 40, 128),
			"GROUP_ADD_ALIAS" => sed_textbox('nalias', '', 16, 24),
			"GROUP_ADD_COLOR" => $grpcolor,
			"GROUP_ADD_MAXFILESIZE" => sed_textbox('nmaxsingle', '0', 16, 16),
			"GROUP_ADD_MAXTOTALSIZE" => sed_textbox('nmaxtotal', '0', 16, 16),
			"GROUP_ADD_COPYRIGHTSFROM" => sed_selectbox_groups(4, 'ncopyrightsfrom', array('5')),
			"GROUP_ADD_GRPLEVEL" => $grplevel,
			"GROUP_ADD_GRPDISABLE" => $grpdisable,
			"GROUP_ADD_GRPHIDDEN" => $grphidden
		));

		$t->parse("ADMIN_USERS.USERS_GROUPS");

		break;
}

$t->assign("ADMIN_USERS_TITLE", $admintitle);

$t->parse("ADMIN_USERS");
$adminmain .= $t->text("ADMIN_USERS");
