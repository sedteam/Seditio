<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=users.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Users
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$y = sed_import('y', 'P', 'TXT');
$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'ALP', 13);
$w = sed_import('w', 'G', 'ALP', 4);
$d = sed_import('d', 'G', 'INT');
$f = sed_import('f', 'G', 'TXT');
$g = sed_import('g', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

/* === Hook === */
$extp = sed_getextplugins('users.edit.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('users');
$number_of_extrafields = count($extrafields);
// ----------------------	

$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='$id' LIMIT 1");
sed_die(sed_sql_numrows($sql) == 0);
$urr = sed_sql_fetchassoc($sql);

$sql1 = sed_sql_query("SELECT gru_groupid FROM $db_groups_users WHERE gru_userid = '$id' and gru_groupid = '5'");
$sys['edited_istopadmin'] = (sed_sql_numrows($sql1) > 0) ? TRUE : FALSE;
$sys['user_istopadmin'] = sed_auth('admin', 'a', 'A');
$sys['protecttopadmin'] = $sys['edited_istopadmin'] && !$sys['user_istopadmin'];

if ($sys['protecttopadmin']) {
	sed_redirect(sed_url("message", "msg=930", "", true));
	exit;
}

if ($a == 'update') {
	sed_check_xg();

	/* === Hook === */
	$extp = sed_getextplugins('users.edit.update.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$rusername = sed_import('rusername', 'P', 'TXT');

	$ruserfirstname = sed_import('ruserfirstname', 'P', 'TXT', 100, TRUE);   // sed 177
	$ruserlastname = sed_import('ruserlastname', 'P', 'TXT', 100, TRUE);     // sed 177  

	$rusermaingrp = sed_import('rusermaingrp', 'P', 'INT');
	$ruserbanexpire = sed_import('ruserbanexpire', 'P', 'INT');
	$rusercountry = sed_import('rusercountry', 'P', 'ALP');
	$ruseravatar = sed_import('ruseravatar', 'P', 'TXT');
	$ruserphoto = sed_import('ruserphoto', 'P', 'TXT');
	$rusersignature = sed_import('rusersignature', 'P', 'TXT');
	$rusertext = sed_import('rusertext', 'P', 'HTM');
	$ruseremail = sed_import('ruseremail', 'P', 'TXT');
	$ruserhideemail = sed_import('ruserhideemail', 'P', 'INT');
	$ruserpmnotify = sed_import('ruserpmnotify', 'P', 'INT');
	$ruserskin = sed_import('ruserskin', 'P', 'TXT');
	$ruserlang = sed_import('ruserlang', 'P', 'ALP');
	$ruserwebsite = sed_import('ruserwebsite', 'P', 'TXT');
	$ruserskype = sed_import('ruserskype', 'P', 'TXT');
	$rusergender = sed_import('rusergender', 'P', 'TXT');
	$ryear = sed_import('ryear', 'P', 'INT');
	$rmonth = sed_import('rmonth', 'P', 'INT');
	$rday = sed_import('rday', 'P', 'INT');
	$rhour = sed_import('rhour', 'P', 'INT');
	$rminute = sed_import('rminute', 'P', 'INT');
	$rusertimezone = sed_import('rusertimezone', 'P', 'TXT');
	$ruserlocation = sed_import('ruserlocation', 'P', 'TXT');
	$ruseroccupation = sed_import('ruseroccupation', 'P', 'TXT');
	$ruserdelete = sed_import('ruserdelete', 'P', 'BOL');
	$ruserdelpfs = sed_import('ruserdelpfs', 'P', 'BOL');
	$rusernewpass = sed_import('rusernewpass', 'P', 'TXT', 16);
	$rusergroupsms = sed_import('rusergroupsms', 'P', 'ARR');

	// --------- Extra fields     
	if ($number_of_extrafields > 0) $ruserextrafields = sed_extrafield_buildvar($extrafields, 'ruser', 'user');
	// ----------------------	

	$error_string .= (mb_strlen($rusername) < 2 || mb_strpos($rusername, ",") !== FALSE || mb_strpos($rusername, "'") !== FALSE) ? $L['aut_usernametooshort'] . "<br />" : '';
	$error_string .= (!empty($rusernewpass) && (mb_strlen($rusernewpass) < 4 || sed_alphaonly($rusernewpass) != $rusernewpass)) ? $L['aut_passwordtooshort'] . "<br />" : '';

	if ($ruserdelete) {
		if ($sys['user_istopadmin'] && !$sys['edited_istopadmin']) {
			$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='$id'");

			if ($row = sed_sql_fetchassoc($sql)) {
				if ($cfg['trash_user']) {
					sed_trash_put('user', $L['User'] . " #" . $id . " " . $row['user_name'], $id, $row);
				}
				$sql = sed_sql_query("DELETE FROM $db_users WHERE user_id='$id'");
				$sql = sed_sql_query("DELETE FROM $db_groups_users WHERE gru_userid='$id'");
				if ($ruserdelpfs) {
					sed_pfs_deleteall($id);
				}
				sed_log("Deleted user #" . $id, 'adm');
				sed_redirect(sed_url("message", "msg=109&rc=200&id=" . $id, "", true));
				exit;
			}
		} else {
			sed_redirect(sed_url("message", "msg=930", "", true));
			exit;
		}
	}

	if (empty($error_string)) {
		if (mb_strlen($rusernewpass) > 0) {
			$rusermdsalt = sed_unique(16); // New sed172          		        
			$ruserpassword = sed_hash($rusernewpass, 1, $rusermdsalt);
			$ruserpasstype = 1;
		} else {
			$ruserpassword = $urr['user_password'];
			$rusermdsalt = $urr['user_salt'];
			$ruserpasstype = $urr['user_passtype'];
		}

		if ($rusername == '') {
			$rusername = $urr['user_name'];
		}
		if ($ruserhideemail == '') {
			$ruserhideemail = $urr['user_hideemail'];
		}
		if ($ruserpmnotify == '') {
			$ruserpmnotify = $urr['user_pmnotify'];
		}

		$ruserbirthdate = ($rmonth == 0 || $rday == 0 || $ryear == 0) ? 0 : sed_mktime(1, 0, 0, $rmonth, $rday, $ryear);

		if (!isset($ruserbanned)) {
			$rbanexpire = 0;
		} elseif ($ruserbanned && $rbanexpire > 0) {
			$rbanexpire += $sys['now'];
		}

		if ($rusername != $urr['user_name']) {
			$oldname = sed_sql_prep($urr['user_name']);
			$newname = sed_sql_prep($rusername);
			$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_lastpostername='$newname' WHERE ft_lastpostername='$oldname'");
			$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_firstpostername='$newname' WHERE ft_firstpostername='$oldname'");
			$sql = sed_sql_query("UPDATE $db_forum_posts SET fp_postername='$newname' WHERE fp_postername='$oldname'");
			$sql = sed_sql_query("UPDATE $db_pages SET page_author='$newname' WHERE page_author='$oldname'");
			$sql = sed_sql_query("UPDATE $db_com SET com_author='$newname' WHERE com_author='$oldname'");
			$sql = sed_sql_query("UPDATE $db_online SET online_name='$newname' WHERE online_name='$oldname'");
			$sql = sed_sql_query("UPDATE $db_pm SET pm_fromuser='$newname' WHERE pm_fromuser='$oldname'");
		}

		// autogeneration avatar from letter sed v178		
		if (empty($urr['user_avatar'])) {
			$gen_avatar = sed_autogen_avatar($urr['user_id']);
			$ruseravatar = ($gen_avatar['status']) ? $gen_avatar['imagepath'] : $ruseravatar;
		}

		// ------ Extra fields 
		$ssql_extra = '';
		if (count($extrafields) > 0) {
			foreach ($extrafields as $i => $row) {
				$ssql_extra .= ", user_" . $row['code'] . " = " . "'" . sed_sql_prep($ruserextrafields['user_' . $row['code']]) . "'";
			}
		}
		// ----------------------			

		$sql = sed_sql_query("UPDATE $db_users SET
			user_banexpire='$rbanexpire',
			user_name='" . sed_sql_prep($rusername) . "',
			user_firstname='" . sed_sql_prep($ruserfirstname) . "', 
			user_lastname='" . sed_sql_prep($ruserlastname) . "',            
			user_password='" . sed_sql_prep($ruserpassword) . "',
			user_salt='" . sed_sql_prep($rusermdsalt) . "',
			user_passtype =" . (int)$ruserpasstype . ",
			user_country='" . sed_sql_prep($rusercountry) . "',
			user_text='" . sed_sql_prep($rusertext) . "',
			user_avatar='" . sed_sql_prep($ruseravatar) . "',
			user_signature='" . sed_sql_prep($rusersignature) . "',
			user_photo='" . sed_sql_prep($ruserphoto) . "',
			user_email='" . sed_sql_prep($ruseremail) . "',
			user_hideemail='$ruserhideemail',
			user_pmnotify='$ruserpmnotify',
			user_skin='" . sed_sql_prep($ruserskin) . "',
			user_lang='" . sed_sql_prep($ruserlang) . "',
			user_website='" . sed_sql_prep($ruserwebsite) . "',
			user_skype='" . sed_sql_prep($ruserskype) . "',
			user_gender='" . sed_sql_prep($rusergender) . "',
			user_birthdate='" . sed_sql_prep($ruserbirthdate) . "',
			user_timezone='" . sed_sql_prep($rusertimezone) . "',
			user_location='" . sed_sql_prep($ruserlocation) . "',
			user_occupation='" . sed_sql_prep($ruseroccupation) . "',
			user_auth=''" . $ssql_extra . " 
			WHERE user_id='$id'");

		if ($sys['user_istopadmin']) {
			$rusermaingrp = ($rusermaingrp < 4 && $id == 1) ? 5 : $rusermaingrp;

			if (!isset($rusergroupsms[$rusermaingrp])) {
				$rusergroupsms[$rusermaingrp] = 1;
			}

			$sql = sed_sql_query("UPDATE $db_users SET user_maingrp='$rusermaingrp' WHERE user_id='$id'");

			foreach ($sed_groups as $k => $i) {
				if (isset($rusergroupsms[$k])) {
					$sql = sed_sql_query("SELECT gru_userid FROM $db_groups_users WHERE gru_userid='$id' AND gru_groupid='$k'");
					if (sed_sql_numrows($sql) == 0 && !(($id == 1 && $k == 3) || ($id == 1 && $k == 2))) {
						$sql = sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$id . ", " . (int)$k . ")");
					}
				} elseif (!($id == 1 && $k == 5)) {
					$sql = sed_sql_query("DELETE FROM $db_groups_users WHERE gru_userid='$id' AND gru_groupid='$k'");
				}
			}

			if ($rusermaingrp == 4 && $urr['user_maingrp'] == 2) {
				$rsubject = $cfg['maintitle'] . " - " . $L['useed_accountactivated'];
				$rbody = $L['Hi'] . " " . $urr['user_name'] . ",\n\n";
				$rbody .= $L['useed_email'];
				$rbody .= $L['auth_contactadmin'];
				sed_mail($urr['user_email'], $rsubject, $rbody);
			}
		}

		// autogeneration avatar from letter sed v178
		if (empty($ruseravatar)) {
			sed_autogen_avatar($id);
		}

		/* === Hook === */
		$extp = sed_getextplugins('users.edit.update.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		sed_auth_clear($id);
		sed_log("Edited user #" . $id, 'adm');
		sed_redirect(sed_url("users", "m=edit&id=" . $id, "", true));
		exit;
	}
}

$user_form_pmnotify =  sed_radiobox("ruserpmnotify", $yesno_arr, $urr['user_pmnotify']);
$user_form_hideemail =  sed_radiobox("ruserhideemail", $yesno_arr, $urr['user_hideemail']);
$user_form_delete =  ($sys['user_istopadmin']) ? sed_radiobox("ruserdelete", $yesno_arr, 0) . "<br />+ " . $L['PFS'] . ":" . sed_checkbox('ruserdelpfs') : $L['na'];

$user_form_pass = $sys['protecttopadmin'] ? sed_textbox("rusernewpass", "", 16, 32, "password", true, "password") : sed_textbox("rusernewpass", "", 16, 32, "password", false, "password");
$user_form_username = $sys['protecttopadmin'] ? sed_textbox("rusername", $urr['user_name'], 24, 100, "text", true) : sed_textbox("rusername", $urr['user_name'], 24, 100);

$user_form_countries = sed_selectbox_countries($urr['user_country'], 'rusercountry');
$user_form_gender = sed_selectbox_gender($urr['user_gender'], 'rusergender');
$user_form_birthdate = sed_selectbox_date($urr['user_birthdate'], 'short');
$urr['user_lastip'] = sed_build_ipsearch($urr['user_lastip']);

$out['subtitle'] = sed_cc($urr['user_name']);
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('userstitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths[sed_url("users")] = $L['Users'];
$urlpaths[sed_url("users", "m=details&id=" . $urr['user_id'])] = sed_cc($urr['user_name']);
$urlpaths[sed_url("users", "m=edit&id=" . $urr['user_id'])] = $L['Edit'];

/* === Hook === */
$extp = sed_getextplugins('users.edit.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile(array('users', 'edit', $usr['maingrp']));
$t = new XTemplate($mskin);

if (!empty($error_string)) {
	$t->assign("USERS_EDIT_ERROR_BODY", $error_string);
	$t->parse("MAIN.USERS_EDIT_ERROR");
}

$t->assign(array(
	"USERS_EDIT_SHORTTITLE" => $urr['user_name'] . " " . $L['Edit'],
	"USERS_EDIT_TITLE" => "<a href=\"" . sed_url("users") . "\">" . $L['Users'] . "</a> " . $cfg['separator'] . " " . sed_build_user($urr['user_id'], sed_cc($urr['user_name'])) . " " . $cfg['separator'] . " <a href=\"" . sed_url("users", "m=edit&id=" . $urr['user_id']) . "\">" . $L['Edit'] . "</a>",
	"USERS_EDIT_SUBTITLE" => $L['useed_subtitle'],
	"USERS_EDIT_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"USERS_EDIT_SEND" => sed_url("users", "m=edit&a=update&" . sed_xg() . "&id=" . $urr['user_id']),
	"USERS_EDIT_ID" => $urr['user_id'],
	"USERS_EDIT_NAME" => $user_form_username,
	"USERS_EDIT_FIRSTNAME" => sed_textbox('ruserfirstname', $urr['user_firstname'], 32, 100),
	"USERS_EDIT_LASTNAME" => sed_textbox('ruserlastname', $urr['user_lastname'], 32, 100),
	"USERS_EDIT_SKIN" => sed_textbox('ruserskin', $urr['user_skin'], 16, 32),
	"USERS_EDIT_LANG" => sed_textbox('ruserlang', $urr['user_lang'], 16, 32),
	"USERS_EDIT_NEWPASS" => $user_form_pass,
	"USERS_EDIT_MAINGRP" => sed_build_group($urr['user_maingrp']),
	"USERS_EDIT_GROUPS" => sed_build_groupsms($urr['user_id'], $sys['user_istopadmin'], $urr['user_maingrp']),
	"USERS_EDIT_COUNTRY" => $user_form_countries,
	"USERS_EDIT_EMAIL" => sed_textbox('ruseremail', $urr['user_email'], 32, 64),
	"USERS_EDIT_HIDEEMAIL" => $user_form_hideemail,
	"USERS_EDIT_PMNOTIFY" => $user_form_pmnotify,
	"USERS_EDIT_TEXT" => sed_textarea("rusertext", $urr['user_text'], 8, $cfg['textarea_default_width']),
	"USERS_EDIT_AVATAR" => sed_textbox('ruseravatar', $urr['user_avatar'], 32, 255),
	"USERS_EDIT_PHOTO" => sed_textbox('ruserphoto', $urr['user_photo'], 32, 255),
	"USERS_EDIT_SIGNATURE" => sed_textbox('rusersignature', $urr['user_signature'], 32, 255),
	"USERS_EDIT_WEBSITE" => sed_textbox('ruserwebsite', $urr['user_website'], 56, 128),
	"USERS_EDIT_SKYPE" => sed_textbox('ruserskype', $urr['user_skype'], 32, 64),
	"USERS_EDIT_GENDER" => $user_form_gender,
	"USERS_EDIT_BIRTHDATE" => $user_form_birthdate,
	"USERS_EDIT_TIMEZONE" => sed_textbox('rusertimezone', $urr['user_timezone'], 16, 16),
	"USERS_EDIT_LOCATION" => sed_textbox('ruserlocation', $urr['user_location'], 32, 64),
	"USERS_EDIT_OCCUPATION" => sed_textbox('ruseroccupation', $urr['user_occupation'], 32, 64),
	"USERS_EDIT_REGDATE" => sed_build_date($cfg['dateformat'], $urr['user_regdate']) . " " . $usr['timetext'],
	"USERS_EDIT_LASTLOG" => sed_build_date($cfg['dateformat'], $urr['user_lastlog']) . " " . $usr['timetext'],
	"USERS_EDIT_LOGCOUNT" => $urr['user_logcount'],
	"USERS_EDIT_LASTIP" => $urr['user_lastip'],
	"USERS_EDIT_DELETE" => $user_form_delete,
));

// Extra fields 
if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields('user', 'USERS_EDIT', $extrafields, $urr, 'ruser');
	$t->assign($extra_array);
}

/* === Hook === */
$extp = sed_getextplugins('users.edit.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */


$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
