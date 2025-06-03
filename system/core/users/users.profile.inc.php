<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=users.profile.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=User profile
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

@clearstatcache();

if ($usr['id'] < 1) {
	sed_redirect(sed_url("message", "msg=100&" . $sys['url_redirect'], "", true));
	exit;
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['auth_write']);

/* === Hook === */
$extp = sed_getextplugins('profile.first');
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

$id = sed_import('id', 'G', 'TXT');
$a = sed_import('a', 'G', 'ALP');

$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='" . $usr['id'] . "' LIMIT 1");
sed_die(sed_sql_numrows($sql) == 0);
$urr = sed_sql_fetchassoc($sql);

$profile_form_avatar = sed_link('', '', array('name' => 'avatar', 'id' => 'avatar'));
$profile_form_photo = sed_link('', '', array('name' => 'photo', 'id' => 'photo'));
$profile_form_signature = sed_link('', '', array('name' => 'signature', 'id' => 'signature'));

switch ($a) {
	/* ============= */
	case 'avatardelete':
		/* ============= */

		sed_check_xg();

		$avatar = str_replace($cfg['av_dir'], '', $usr['profile']['user_avatar']);
		$avatarpath = $usr['profile']['user_avatar'];

		if (file_exists($avatarpath) && mb_strpos($avatarpath, $cfg['defav_dir']) === false) {
			unlink($avatarpath);
		}

		$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$avatar'");
		$sql = sed_sql_query("UPDATE $db_users SET user_avatar='' WHERE user_id='" . $usr['id'] . "'");
		sed_redirect(sed_url("users", "m=profile", "#avatar", true));
		exit;

		break;

	/* ============= */
	case 'phdelete':
		/* ============= */

		sed_check_xg();

		$photo = str_replace($cfg['photos_dir'], '', $usr['profile']['user_photo']);
		$photopath = $usr['profile']['user_photo'];

		if (file_exists($photopath)) {
			unlink($photopath);
		}

		$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$photo'");
		$sql = sed_sql_query("UPDATE $db_users SET user_photo='' WHERE user_id='" . $usr['id'] . "'");
		sed_redirect(sed_url("users", "m=profile", "#photo", true));
		exit;

		break;

	/* ============= */
	case 'sigdelete':
		/* ============= */

		sed_check_xg();

		$signature = str_replace($cfg['sig_dir'], '', $usr['profile']['user_signature']);
		$signaturepath = $usr['profile']['user_signature'];

		if (file_exists($signaturepath)) {
			unlink($signaturepath);
		}

		$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$signature'");
		$sql = sed_sql_query("UPDATE $db_users SET user_signature='' WHERE user_id='" . $usr['id'] . "'");
		sed_redirect(sed_url("users", "m=profile", "#signature", true));
		exit;

		break;

	/* ============= */
	case 'avatarselect':
		/* ============= */

		sed_check_xg();
		$avatar = $cfg['defav_dir'] . urldecode($id);
		$avatar = str_replace(array("'", ",", chr(0x00)), "", $avatar);
		if (file_exists($avatar)) {
			$sql = sed_sql_query("UPDATE $db_users SET user_avatar='" . sed_sql_prep($avatar) . "' WHERE user_id='" . $usr['id'] . "'");
		}
		sed_redirect(sed_url("users", "m=profile", "#avatar", true));
		exit;

		break;

	/* ============= */
	case 'update':
		/* ============= */

		sed_check_xg();

		/* === Hook === */
		$extp = sed_getextplugins('profile.update.first');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		if (isset($_FILES['userfile']['tmp_name'])) {
			$uav_tmp_name = $_FILES['userfile']['tmp_name'];
			$uav_type = $_FILES['userfile']['type'];
			$uav_name = $_FILES['userfile']['name'];
			$uav_size = $_FILES['userfile']['size'];
		}

		if (isset($_FILES['userphoto']['tmp_name'])) {
			$uph_tmp_name = $_FILES['userphoto']['tmp_name'];
			$uph_type = $_FILES['userphoto']['type'];
			$uph_name = $_FILES['userphoto']['name'];
			$uph_size = $_FILES['userphoto']['size'];
		}

		if (isset($_FILES['usersig']['tmp_name'])) {
			$usig_tmp_name = $_FILES['usersig']['tmp_name'];
			$usig_type = $_FILES['usersig']['type'];
			$usig_name = $_FILES['usersig']['name'];
			$usig_size = $_FILES['usersig']['size'];
		}

		if (!empty($uav_tmp_name) || !empty($uph_tmp_name) || !empty($usig_tmp_name)) {
			@clearstatcache();
		}

		if (!empty($uav_tmp_name) && $uav_size > 0) {
			$dotpos = mb_strrpos($uav_name, ".") + 1;
			$f_extension = mb_strtolower(mb_substr($uav_name, $dotpos, 5));

			if (is_uploaded_file($uav_tmp_name) && $uav_size > 0 && $uav_size <= $cfg['av_maxsize'] && ($f_extension == 'jpeg' || $f_extension == 'jpg' || $f_extension == 'gif' || $f_extension == 'png')) {
				list($w, $h) = @getimagesize($uav_tmp_name);
				if ($w <= $cfg['av_maxx'] && $h <= $cfg['av_maxy']) {
					$avatar = $usr['id'] . "-avatar." . $f_extension;
					$avatarpath = $cfg['av_dir'] . $avatar;

					if (file_exists($avatarpath)) {
						unlink($avatarpath);
					}

					move_uploaded_file($uav_tmp_name, $avatarpath);

					/* === Hook === */
					$extp = sed_getextplugins('profile.update.avatar');
					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}
					/* ===== */

					$uav_size = filesize($avatarpath);
					$sql = sed_sql_query("UPDATE $db_users SET user_avatar='$avatarpath' WHERE user_id='" . $usr['id'] . "'");
					$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$avatar'");
					$sql = sed_sql_query("INSERT INTO $db_pfs (pfs_userid, pfs_file, pfs_extension, pfs_folderid, pfs_desc, pfs_size, pfs_count) VALUES (" . (int)$usr['id'] . ", '$avatar', '$f_extension', -1, '', " . (int)$uav_size . ", 0)");
					@chmod($avatarpath, 0666);
				}
			}
		} else {
			// autogeneration avatar from letter sed v178
			if (empty($usr['profile']['user_avatar'])) {
				sed_autogen_avatar($usr['id']);
			}
		}

		if (!empty($uph_tmp_name) && $uph_size > 0) {
			$dotpos = mb_strrpos($uph_name, ".") + 1;
			$f_extension = mb_strtolower(mb_substr($uph_name, $dotpos, 5));

			if (is_uploaded_file($uph_tmp_name) && $uph_size > 0 && $uph_size <= $cfg['ph_maxsize'] && ($f_extension == 'jpeg' || $f_extension == 'jpg' || $f_extension == 'gif' || $f_extension == 'png')) {
				list($w, $h) = @getimagesize($uph_tmp_name);
				if ($w <= $cfg['ph_maxx'] && $h <= $cfg['ph_maxy']) {
					$photo = $usr['id'] . "-photo." . $f_extension;
					$photopath = $cfg['photos_dir'] . $photo;

					if (file_exists($photopath)) {
						unlink($photopath);
					}

					move_uploaded_file($uph_tmp_name, $photopath);

					/* === Hook === */
					$extp = sed_getextplugins('profile.update.photo');
					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}
					/* ===== */

					$uph_size = filesize($photopath);
					$sql = sed_sql_query("UPDATE $db_users SET user_photo='$photopath' WHERE user_id='" . $usr['id'] . "'");
					$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$photo'");
					$sql = sed_sql_query("INSERT INTO $db_pfs (pfs_userid, pfs_file, pfs_extension, pfs_folderid, pfs_desc, pfs_size, pfs_count) VALUES (" . (int)$usr['id'] . ", '$photo', '$f_extension', -1, '', " . (int)$uph_size . ", 0)");
					@chmod($photopath, 0666);
				}
			}
		}

		if (!empty($usig_tmp_name) && $usig_size > 0) {
			$dotpos = mb_strrpos($usig_name, ".") + 1;
			$f_extension = mb_strtolower(mb_substr($usig_name, $dotpos, 5));

			if (is_uploaded_file($usig_tmp_name) && $usig_size > 0 && $usig_size <= $cfg['sig_maxsize'] && ($f_extension == 'jpeg' || $f_extension == 'jpg' || $f_extension == 'gif' || $f_extension == 'png')) {
				list($w, $h) = @getimagesize($usig_tmp_name);
				if ($w <= $cfg['sig_maxx'] && $h <= $cfg['sig_maxy']) {
					$signature = $usr['id'] . "-signature." . $f_extension;
					$signaturepath = $cfg['sig_dir'] . $signature;

					if (file_exists($signaturepath)) {
						unlink($signaturepath);
					}

					move_uploaded_file($usig_tmp_name, $signaturepath);

					/* === Hook === */
					$extp = sed_getextplugins('profile.update.signature');
					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}
					/* ===== */

					$usig_size = filesize($signaturepath);
					$sql = sed_sql_query("UPDATE $db_users SET user_signature='$signaturepath' WHERE user_id='" . $usr['id'] . "'");
					$sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$signature'");
					$sql = sed_sql_query("INSERT INTO $db_pfs (pfs_userid, pfs_file, pfs_extension, pfs_folderid, pfs_desc, pfs_size, pfs_count) VALUES (" . (int)$usr['id'] . ", '$signature', '$f_extension', -1, '', " . (int)$usig_size . ", 0)");
					@chmod($signaturepath, 0666);
				}
			}
		}

		$ruserfirstname = sed_import('ruserfirstname', 'P', 'TXT', 100, TRUE);   // sed 177
		$ruserlastname = sed_import('ruserlastname', 'P', 'TXT', 100, TRUE);     // sed 177  

		$rusertext = sed_import('rusertext', 'P', 'HTM');
		$rusercountry = sed_import('rusercountry', 'P', 'ALP');
		$ruserskin = sed_import('ruserskin', 'P', 'TXT');
		$ruserlang = sed_import('ruserlang', 'P', 'ALP');
		$ruserwebsite = sed_import('ruserwebsite', 'P', 'TXT');
		$ruserskype = sed_import('ruserskype', 'P', 'TXT');
		$rusergender = sed_import('rusergender', 'P', 'ALP');
		$ryear = sed_import('ryear', 'P', 'INT');
		$rmonth = sed_import('rmonth', 'P', 'INT');
		$rday = sed_import('rday', 'P', 'INT');
		$rhour = sed_import('rhour', 'P', 'INT');
		$rminute = sed_import('rminute', 'P', 'INT');
		$rusertimezone = sed_import('rusertimezone', 'P', 'TXT', 5);
		$ruserlocation = sed_import('ruserlocation', 'P', 'TXT');
		$ruseroccupation = sed_import('ruseroccupation', 'P', 'TXT');
		$ruseremail = sed_import('ruseremail', 'P', 'TXT');
		$ruserhideemail = sed_import('ruserhideemail', 'P', 'BOL');
		$ruserpmnotify = sed_import('ruserpmnotify', 'P', 'BOL');
		$rnewpass1 = sed_import('rnewpass1', 'P', 'TXT');
		$rnewpass2 = sed_import('rnewpass2', 'P', 'TXT');
		$rusertext = mb_substr($rusertext, 0, $cfg['usertextmax']);

		// --------- Extra fields     
		if ($number_of_extrafields > 0) $ruserextrafields = sed_extrafield_buildvar($extrafields, 'ruser', 'user');
		// ----------------------	

		if (!empty($rnewpass1) && !empty($rnewpass2)) {
			$rnewpass1 = sed_import('rnewpass1', 'P', 'TXT');
			$rnewpass2 = sed_import('rnewpass2', 'P', 'TXT');

			$error_string .= ($rnewpass1 != $rnewpass2) ? $L['pro_passdiffer'] . "<br />" : '';
			$error_string .= (mb_strlen($rnewpass1) < 4 || sed_alphaonly($rnewpass1) != $rnewpass2) ? $L['pro_passtoshort'] . "<br />" : '';

			if (empty($error_string)) {

				$rmdsalt = sed_unique(16); // New sed172          
				$rnewpass = sed_hash($rnewpass1, 1, $rmdsalt); // New sed172						
				$rnewpass_secret = md5(sed_unique(16)); // New sed172

				$sql = sed_sql_query("UPDATE $db_users SET 
				user_password='" . sed_sql_prep($rnewpass) . "',
				user_salt='" . sed_sql_prep($rmdsalt) . "',
				user_secret='" . sed_sql_prep($rnewpass_secret) . "',        
				user_passtype=1  
				WHERE user_id='" . $usr['id'] . "'");

				if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
					$u = base64_encode($usr['id'] . ":_:$rnewpass_secret:_:" . $ruserskin);
					sed_setcookie($sys['site_id'], $u, time() + 63072000, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
				}

				if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
					$_SESSION[$sys['site_id'] . '_p'] = $rnewpass_secret;
				}
			}
		}

		if (empty($error_string)) {
			$ruserbirthdate = ($rmonth == 0 || $rday == 0 || $ryear == 0) ? 0 : sed_mktime(1, 0, 0, $rmonth, $rday, $ryear);

			if (!$cfg['useremailchange']) {
				$ruseremail = $urr['user_email'];
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
			user_firstname='" . sed_sql_prep($ruserfirstname) . "', 
			user_lastname='" . sed_sql_prep($ruserlastname) . "',       
			user_text='" . sed_sql_prep($rusertext) . "',
			user_country='" . sed_sql_prep($rusercountry) . "',
			user_skin='" . sed_sql_prep($ruserskin) . "',
			user_lang='" . sed_sql_prep($ruserlang) . "',
			user_website='" . sed_sql_prep($ruserwebsite) . "',
			user_skype='" . sed_sql_prep($ruserskype) . "',
			user_gender='" . sed_sql_prep($rusergender) . "',
			user_birthdate='" . sed_sql_prep($ruserbirthdate) . "',
			user_timezone='" . sed_sql_prep($rusertimezone) . "',
			user_location='" . sed_sql_prep($ruserlocation) . "',
			user_occupation='" . sed_sql_prep($ruseroccupation) . "',
			user_email='" . sed_sql_prep($ruseremail) . "',
			user_hideemail='$ruserhideemail',
			user_pmnotify='$ruserpmnotify',
			user_auth=''" . $ssql_extra . "
			WHERE user_id='" . $usr['id'] . "'");

			/* === Hook === */
			$extp = sed_getextplugins('profile.update.done');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}
			/* ===== */

			sed_redirect(sed_url("message", "msg=113", "", true));
			exit;
		}
		break;

	/* ============= */
	default:
		/* ============= */

		break;
}

$profile_form_skins = sed_selectbox_skin($urr['user_skin'], 'ruserskin');
$profile_form_langs = sed_selectbox_lang($urr['user_lang'], 'ruserlang');

$timezonelist = array('-12', '-11', '-10', '-09', '-08', '-07', '-06', '-05', '-04', '-03',  '-03.5', '-02', '-01', '+00', '+01', '+02', '+03', '+03.5', '+04', '+04.5', '+05', '+05.5', '+06', '+07', '+08', '+09', '+09.5', '+10', '+11', '+12');

$profile_form_timezone = "<select name=\"rusertimezone\" size=\"1\">";

foreach ($timezonelist as $i => $x) {
	$selected = ($x == $urr['user_timezone']) ? "selected=\"selected\"" : '';
	$profile_form_timezone .= "<option value=\"$x\" $selected>GMT" . $x . "</option>";
}
$profile_form_timezone .= "</select> " . $usr['gmttime'] . " / " . sed_build_date($cfg['dateformat'], $sys['now_offset']) . " " . $usr['timetext'];

$profile_form_countries = sed_selectbox_countries($urr['user_country'], 'rusercountry');
$profile_form_gender = sed_selectbox_gender($urr['user_gender'], 'rusergender');
$profile_form_birthdate = sed_selectbox_date($urr['user_birthdate'], 'short');
$profile_form_email = ($cfg['useremailchange']) ? sed_textbox('ruseremail', sed_cc($urr['user_email']), 32, 64, 'text') : sed_cc($urr['user_email']);

$profile_form_avatar = (!empty($urr['user_avatar'])) ? "<img src=\"" . $urr['user_avatar'] . "\" alt=\"\" /><br />" . $L['Delete'] . " [<a href=\"" . sed_url("users", "m=profile&a=avatardelete&" . sed_xg()) . "\">x</a>]<br />&nbsp;<br />" : '';
$profile_form_avatar .= $L['pro_avatarsupload'] . " (" . $cfg['av_maxx'] . "x" . $cfg['av_maxy'] . "x" . $cfg['av_maxsize'] . $L['bytes'] . ")<br />";
$profile_form_avatar .= sed_textbox_hidden('MAX_FILE_SIZE', ($cfg['av_maxsize'] * 1024));
$profile_form_avatar .= sed_filebox('userfile', 'file', false, '', false, array('size' => '24')) . "<br />";

$profile_form_photo = (!empty($urr['user_photo'])) ? "<img src=\"" . $urr['user_photo'] . "\" alt=\"\" /> " . $L['Delete'] . " [<a href=\"" . sed_url("users", "m=profile&a=phdelete&" . sed_xg()) . "\">x</a>]" : '';
$profile_form_photo .= $L['pro_photoupload'] . " (" . $cfg['ph_maxx'] . "x" . $cfg['ph_maxy'] . "x" . $cfg['ph_maxsize'] . $L['bytes'] . ")<br />";
$profile_form_photo .= sed_textbox_hidden('MAX_FILE_SIZE', ($cfg['ph_maxsize'] * 1024));
$profile_form_photo .= sed_filebox('userphoto', 'file', false, '', false, array('size' => '24')) . "<br />";

$profile_form_signature = (!empty($urr['user_signature'])) ? "<img src=\"" . $urr['user_signature'] . "\" alt=\"\" /> " . $L['Delete'] . " [<a href=\"" . sed_url("users", "m=profile&a=sigdelete&" . sed_xg()) . "\">x</a>]" : '';
$profile_form_signature .= $L['pro_sigupload'] . " (" . $cfg['sig_maxx'] . "x" . $cfg['sig_maxy'] . "x" . $cfg['sig_maxsize'] . $L['bytes'] . ")<br />";
$profile_form_signature .= sed_textbox_hidden('MAX_FILE_SIZE', ($cfg['sig_maxsize'] * 1024));
$profile_form_signature .=  sed_filebox('usersig', 'file', false, '', false, array('size' => '24')) . "<br />";

if ($a == 'avatarchoose') {
	sed_check_xg();
	$profile_form_avatar .=  sed_link('', '', array('name' => 'list', 'id' => 'list')) . "<h4>" . $L['pro_avatarschoose'] . " :</h4>";
	$handle = opendir($cfg['defav_dir']);
	while ($f = readdir($handle)) {
		$extens = pathinfo($f, PATHINFO_EXTENSION);
		if ($f != "." && $f != ".." && in_array($extens, $cfg['gd_supported'])) {
			$profile_form_avatar .= sed_link(sed_url("users", "m=profile&a=avatarselect&" . sed_xg() . "&id=" . urlencode($f), "#avatar"), "<img src=\"" . $cfg['defav_dir'] . $f . "\" alt=\"\" />") . " ";
		}
	}
	closedir($handle);
} else {
	$profile_form_avatar .= sed_link(sed_url("users", "m=profile&a=avatarchoose&" . sed_xg(), "#list"), $L['pro_avatarspreset']);
}

$profile_form_pmnotify = sed_radiobox("ruserpmnotify", $yesno_arr, $urr['user_pmnotify']);
$profile_form_hideemail = sed_radiobox("ruserhideemail", $yesno_arr, $urr['user_hideemail']);

$out['subtitle'] = $L['Profile'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('userstitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('profile.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile(array('users', 'profile'));
$t = new XTemplate($mskin);

if (!empty($error_string)) {
	$t->assign("USERS_PROFILE_ERROR_BODY", $error_string);
	$t->parse("MAIN.USERS_PROFILE_ERROR");
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("users")] = $L['Users'];
$urlpaths[sed_url("users", "m=profile")] = $L['pro_title'];

$t->assign(array(
	"USERS_PROFILE_TITLE" => sed_link(sed_url("users", "m=profile"), $L['pro_title']),
	"USERS_PROFILE_SHORTTITLE" => $L['pro_title'],
	"USERS_PROFILE_URL" => sed_url("users", "m=profile"),
	"USERS_PROFILE_SUBTITLE" => $L['pro_subtitle'],
	"USERS_PROFILE_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"USERS_PROFILE_FORM_SEND" => sed_url("users", "m=profile&a=update&" . sed_xg()),
	"USERS_PROFILE_ID" => $urr['user_id'],
	"USERS_PROFILE_NAME" => sed_cc($urr['user_name']),
	"USERS_PROFILE_FIRSTNAME" => sed_textbox('ruserfirstname', $urr['user_firstname'], 32, 100),
	"USERS_PROFILE_LASTNAME" => sed_textbox('ruserlastname', $urr['user_lastname'], 32, 100),
	"USERS_PROFILE_MAINGRP" => sed_build_group($urr['user_maingrp']),
	"USERS_PROFILE_GROUPS" => sed_build_groupsms($urr['user_id'], FALSE, $urr['user_maingrp']),
	"USERS_PROFILE_COUNTRY" => $profile_form_countries,
	"USERS_PROFILE_AVATAR" => $profile_form_avatar,
	"USERS_PROFILE_PHOTO" => $profile_form_photo,
	"USERS_PROFILE_SIGNATURE" => $profile_form_signature,
	"USERS_PROFILE_TEXT" => sed_textarea("rusertext", $urr['user_text'], 8, $cfg['textarea_default_width'], 'Micro'),
	"USERS_PROFILE_EMAIL" => $profile_form_email,
	"USERS_PROFILE_HIDEEMAIL" => $profile_form_hideemail,
	"USERS_PROFILE_PMNOTIFY" => $profile_form_pmnotify,
	"USERS_PROFILE_WEBSITE" => sed_textbox('ruserwebsite', $urr['user_website'], 56, 128),
	"USERS_PROFILE_SKIN" => $profile_form_skins,
	"USERS_PROFILE_LANG" => $profile_form_langs,
	"USERS_PROFILE_SKYPE" => sed_textbox('ruserskype', $urr['user_skype'], 32, 64),
	"USERS_PROFILE_GENDER" => $profile_form_gender,
	"USERS_PROFILE_BIRTHDATE" => $profile_form_birthdate,
	"USERS_PROFILE_TIMEZONE" => $profile_form_timezone,
	"USERS_PROFILE_LOCATION" => sed_textbox('ruserlocation', $urr['user_location'], 32, 64),
	"USERS_PROFILE_OCCUPATION" => sed_textbox('ruseroccupation', $urr['user_occupation'], 32, 64),
	"USERS_PROFILE_REGDATE" => sed_build_date($cfg['dateformat'], $urr['user_regdate']) . " " . $usr['timetext'],
	"USERS_PROFILE_LASTLOG" => sed_build_date($cfg['dateformat'], $urr['user_lastlog']) . " " . $usr['timetext'],
	"USERS_PROFILE_LOGCOUNT" => $urr['user_logcount'],
	"USERS_PROFILE_ADMINRIGHTS" => '',
	"USERS_PROFILE_NEWPASS1" => sed_textbox("rnewpass1", "", 16, 32, "password", false, "password"),
	"USERS_PROFILE_NEWPASS2" => sed_textbox("rnewpass2", "", 16, 32, "password", false, "password")
));

// Extra fields 
if (count($extrafields) > 0) {
	$extra_array = sed_build_extrafields('user', 'USERS_PROFILE', $extrafields, $urr, 'ruser');
	$t->assign($extra_array);
}

/* === Hook === */
$extp = sed_getextplugins('profile.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
