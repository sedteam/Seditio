<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=users.register.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=User auth
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$v = sed_import('v', 'G', 'ALP');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');

if ($cfg['maintenance'] && $usr['level'] < $cfg['maintenancelevel']) {
	sed_diemaintenance();
	exit;
}

if ($cfg['disablereg']) {
	sed_redirect(sed_url("message", "msg=117", "", true));
	exit;
}

if ($usr['id'] > 0 && !$usr['isadmin']) {
	sed_redirect(sed_url("index"));
	exit;
}

/* === Hook === */
$extp = sed_getextplugins('users.register.first');
if (is_array($extp)) {
	foreach ($extp as $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('users');
$number_of_extrafields = count($extrafields);
// ----------------------	

if ($a == 'add') {
	$bannedreason = FALSE;
	sed_shield_protect();

	/* === Hook for the plugins === */
	$extp = sed_getextplugins('users.register.add.first');
	if (is_array($extp)) {
		foreach ($extp as $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$rusername = sed_import('rusername', 'P', 'TXT', 100, TRUE);

	$ruserfirstname = sed_import('ruserfirstname', 'P', 'TXT', 100, TRUE);   // sed 177
	$ruserlastname = sed_import('ruserlastname', 'P', 'TXT', 100, TRUE);     // sed 177

	$ruseremail = sed_import('ruseremail', 'P', 'TXT', 64, TRUE);
	$rpassword1 = sed_import('rpassword1', 'P', 'TXT', 32);
	$rpassword2 = sed_import('rpassword2', 'P', 'TXT', 32);
	$rcountry = sed_import('rcountry', 'P', 'TXT');
	$rlocation = sed_import('rlocation', 'P', 'TXT');
	$rtimezone = sed_import('rtimezone', 'P', 'TXT', 5);
	$rtimezone_p = sed_import('rtimezone_p', 'P', 'BOL');
	$roccupation = sed_import('roccupation', 'P', 'TXT');
	$rusergender = sed_import('rusergender', 'P', 'TXT');
	$ryear = sed_import('ryear', 'P', 'INT');
	$rmonth = sed_import('rmonth', 'P', 'INT');
	$rday = sed_import('rday', 'P', 'INT');
	$ruserskype = sed_import('ruserskype', 'P', 'TXT');
	$ruserwebsite = sed_import('ruserwebsite', 'P', 'TXT');
	$ruseremail = mb_strtolower($ruseremail);

	// --------- Extra fields     
	if ($number_of_extrafields > 0) $ruserextrafields = sed_extrafield_buildvar($extrafields, 'ruser', 'user');
	// ----------------------		

	$sql = sed_sql_query("SELECT banlist_reason, banlist_email FROM $db_banlist WHERE banlist_email!=''");

	while ($row = sed_sql_fetchassoc($sql)) {
		if (mb_strpos($ruseremail, $row['banlist_email']) !== FALSE) {
			$bannedreason = $row['banlist_reason'];
		}
	}

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name='" . sed_sql_prep($rusername) . "'");
	$res1 = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_email='" . sed_sql_prep($ruseremail) . "'");
	$res2 = sed_sql_result($sql, 0, "COUNT(*)");

	$rusername = str_replace('&#160;', '', $rusername);
	$error_string .= (!empty($bannedreason)) ? $L['aut_emailbanned'] . $bannedreason . "<br />" : '';
	$error_string .= (mb_strlen($rusername) < 2) ? $L['aut_usernametooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($rpassword1) < 4) ? $L['aut_passwordtooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($ruseremail) < 4) ? $L['aut_emailtooshort'] . "<br />" : '';
	$error_string .= ($res1 > 0) ? $L['aut_usernamealreadyindb'] . "<br />" : '';
	$error_string .= ($res2 > 0) ? $L['aut_emailalreadyindb'] . "<br />" : '';
	$error_string .= ($rpassword1 != $rpassword2) ? $L['aut_passwordmismatch'] . "<br />" : '';

	if (empty($error_string)) {
		if (sed_sql_rowcount($db_users) == 0) {
			$defgroup = 5;
		} else {
			$defgroup = ($cfg['regnoactivation']) ? 4 : 2;
		}

		$mdsalt = sed_unique(16); // New sed172    
		$mdpass = sed_hash($rpassword1, 1, $mdsalt);  // New sed172

		$mdpass_secret = md5(sed_unique(16)); // New sed172 for generate cookies

		$ruserbirthdate = ($rmonth == 'x' || $rday == 'x' || $ryear == 'x' || $rmonth == 0 || $rday == 0 || $ryear == 0) ? 0 : sed_mktime(1, 0, 0, $rmonth, $rday, $ryear);

		$rtimezone = ($rtimezone_p) ? $rtimezone : $cfg['defaulttimezone'];

		$validationkey = md5(microtime());
		sed_shield_update(20, "Registration");

		// ------ Extra fields 
		if (count($extrafields) > 0) {
			foreach ($extrafields as $i => $row) {
				$ssql_extra_columns .= ', user_' . $row['code'];
				$ssql_extra_values .= ", '" . sed_sql_prep($ruserextrafields['user_' . $row['code']]) . "'";
			}
		}
		// ----------------------		

		$sql = sed_sql_query("INSERT into $db_users
			(user_name,
			user_firstname,
			user_lastname,
			user_password,
			user_salt,
			user_secret,
			user_passtype,
			user_maingrp,
			user_country,
			user_location,
			user_timezone,
			user_occupation,
			user_text,
			user_email,
			user_hideemail,
			user_pmnotify,
			user_skin,
			user_lang,
			user_regdate,
			user_logcount,
			user_lostpass,
			user_gender,
			user_birthdate,
			user_skype,
			user_website,
			user_lastip" . $ssql_extra_columns . "
			)
			VALUES
			('" . sed_sql_prep($rusername) . "',
			'" . sed_sql_prep($ruserfirstname) . "',
			'" . sed_sql_prep($ruserlastname) . "',            
			'$mdpass',
			'$mdsalt',
			'$mdpass_secret',
			1,
			" . (int)$defgroup . ",
			'" . sed_sql_prep($rcountry) . "',
			'" . sed_sql_prep($rlocation) . "',
			'" . sed_sql_prep($rtimezone) . "',
			'" . sed_sql_prep($roccupation) . "',
			'',
			'" . sed_sql_prep($ruseremail) . "',
			1,
			1,
			'" . $cfg['defaultskin'] . "',
			'" . $cfg['defaultlang'] . "',
			" . (int)$sys['now_offset'] . ",
			0,
			'$validationkey',
			'" . sed_sql_prep($rusergender) . "',
			" . (int)$ruserbirthdate . ",
			'" . sed_sql_prep($ruserskype) . "',
			'" . sed_sql_prep($ruserwebsite) . "',
			'" . $usr['ip'] . "'" . $ssql_extra_values . ")");

		$userid = sed_sql_insertid();
		$sql = sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$defgroup . ")");

		/* === Hook for the plugins === */
		$extp = sed_getextplugins('users.register.add.done');
		if (is_array($extp)) {
			foreach ($extp as $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		if ($cfg['regnoactivation'] || $defgroup == 5) {
			sed_redirect(sed_url("message", "msg=106", "", true));
			exit;
		}

		if ($cfg['regrequireadmin']) {
			$rsubject = $cfg['maintitle'] . " - " . $L['aut_regrequesttitle'];
			$rbody = sprintf($L['aut_regrequest'], $rusername, $rpassword1);
			$rbody .= "\n\n" . $L['aut_contactadmin'];
			sed_mail($ruseremail, $rsubject, $rbody);

			$rsubject = $cfg['maintitle'] . " - " . $L['aut_regreqnoticetitle'];
			$rinactive = $cfg['mainurl'] . "/" . sed_url("users", "gm=2&s=regdate&w=desc", "", false, false);
			$rbody = sprintf($L['aut_regreqnotice'], $rusername, $rinactive);
			sed_mail($cfg['adminemail'], $rsubject, $rbody);
			sed_redirect(sed_url("message", "msg=118", "", true));
			exit;
		} else {
			$rsubject = $cfg['maintitle'] . " - " . $L['Registration'];
			$ractivate = $cfg['mainurl'] . "/" . sed_url("users", "m=register&a=validate&v=" . $validationkey, "", false, false);
			$rbody = sprintf($L['aut_emailreg'], $rusername, $rpassword1, $ractivate);
			$rbody .= "\n\n" . $L['aut_contactadmin'];
			sed_mail($ruseremail, $rsubject, $rbody);
			sed_redirect(sed_url("message", "msg=105", "", true));
			exit;
		}
	}
} elseif ($a == 'validate' && mb_strlen($v) == 32) {
	sed_shield_protect();
	$sql = sed_sql_query("SELECT user_id FROM $db_users WHERE user_lostpass='$v' AND user_maingrp=2");

	if ($row = sed_sql_fetchassoc($sql)) {
		$sql = sed_sql_query("UPDATE $db_users SET user_maingrp=4 WHERE user_id='" . $row['user_id'] . "' AND user_lostpass='$v'");
		$sql = sed_sql_query("UPDATE $db_groups_users SET gru_groupid=4 WHERE gru_groupid=2 AND gru_userid='" . $row['user_id'] . "'");
		sed_auth_clear($row['user_id']);
		sed_redirect(sed_url("message", "msg=106", "", true));
		exit;
	} else {
		sed_shield_update(7, "Account validation");
		sed_log("Wrong validation URL", 'sec');
		sed_redirect(sed_url("message", "msg=157", "", true));
		exit;
	}
}

$form_usergender = sed_selectbox_gender((isset($rusergender) ? $rusergender : ''), 'rusergender');

$form_birthdate = sed_selectbox_date(sed_mktime(1, 0, 0, (isset($rmonth) ? $rmonth : 0), (isset($rday) ? $rday : 0), (isset($ryear) ? $ryear : 0)), 'short');

$rtimezone = (empty($rtimezone)) ? $cfg['defaulttimezone'] : $rtimezone;
$rcountry = (empty($rcountry)) ? $cfg['defaultcountry'] : $rcountry;
$timezonelist = array('-12', '-11', '-10', '-09', '-08', '-07', '-06', '-05', '-04', '-03',  '-03.5', '-02', '-01', '+00', '+01', '+02', '+03', '+03.5', '+04', '+04.5', '+05', '+05.5', '+06', '+07', '+08', '+09', '+09.5', '+10', '+11', '+12');

$form_timezone = "<input type=\"hidden\" name=\"rtimezone_p\" value=\"1\" /><select name=\"rtimezone\" size=\"1\">";
foreach ($timezonelist as $i => $x) {
	$selected = ($x == $rtimezone) ? "selected=\"selected\"" : '';
	$form_timezone .= "<option value=\"$x\" $selected>GMT" . $x . "</option>";
}
$form_timezone .= "</select> " . $usr['gmttime'] . " / " . sed_build_date($cfg['dateformat'], $sys['now_offset']) . " " . $usr['timetext'];

$out['subtitle'] = $L['aut_registertitle'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('userstitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('users.register.main');
if (is_array($extp)) {
	foreach ($extp as $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/users.register.tpl");

// ---------- Breadcrumbs
$urlpaths[sed_url("users", "m=register")] = $L['Registration'];

if (!empty($error_string)) {
	$t->assign("USERS_REGISTER_ERROR_BODY", $error_string);
	$t->parse("MAIN.USERS_REGISTER_ERROR");
}

$t->assign(array(
	"USERS_REGISTER_TITLE" => $L['aut_registertitle'],
	"USERS_REGISTER_SUBTITLE" => $L['aut_registersubtitle'],
	"USERS_REGISTER_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"USERS_REGISTER_SEND" => sed_url("users", "m=register&a=add"),
	"USERS_REGISTER_USER" => sed_textbox("rusername", isset($rusername) ? $rusername : '', 24, 100),
	"USERS_REGISTER_FIRSTNAME" => sed_textbox("ruserfirstname", isset($ruserfirstname) ? $ruserfirstname : '', 24, 100),
	"USERS_REGISTER_LASTNAME" => sed_textbox("ruserlastname", isset($ruserlastname) ? $ruserlastname : '', 24, 100),
	"USERS_REGISTER_EMAIL" => sed_textbox("ruseremail", isset($ruseremail) ? $ruseremail : '', 24, 64),
	"USERS_REGISTER_PASSWORD" => sed_textbox("rpassword1", "", 16, 32, "password", false, "password"),
	"USERS_REGISTER_PASSWORDREPEAT" => sed_textbox("rpassword2", "", 16, 32, "password", false, "password"),
	"USERS_REGISTER_COUNTRY" => sed_selectbox_countries($rcountry, 'rcountry'),
	"USERS_REGISTER_LOCATION" => sed_textbox("rlocation", isset($rlocation) ? $rlocation : '', 24, 64),
	"USERS_REGISTER_TIMEZONE" => $form_timezone,
	"USERS_REGISTER_OCCUPATION" => sed_textbox("roccupation", isset($roccupation) ? $roccupation : '', 24, 64),
	"USERS_REGISTER_GENDER" => $form_usergender,
	"USERS_REGISTER_BIRTHDATE" => $form_birthdate,
	"USERS_REGISTER_WEBSITE" => sed_textbox("ruserwebsite", isset($ruserwebsite) ? $ruserwebsite : '', 56, 128),
	"USERS_REGISTER_SKYPE" => sed_textbox("ruserskype", isset($ruserskype) ? $ruserskype : '', 32, 64)
));

// Extra fields 
if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields('user', 'USERS_REGISTER', $extrafields, $ruserextrafields, 'ruser');
	$t->assign($extra_array);
}

/* === Hook === */
$extp = sed_getextplugins('users.register.tags');
if (is_array($extp)) {
	foreach ($extp as $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
