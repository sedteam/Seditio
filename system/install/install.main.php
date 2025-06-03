<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=install.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Seditio auto-installation
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
	die('Wrong URL.');
}

/* === Select Installation lang === */

session_start();
$langinstall = sed_import('langinstall', 'P', 'TXT', 10);
if (!empty($langinstall)) {
	$_SESSION['ilang'] = $langinstall;
} elseif (isset($_SESSION['ilang'])) {
	$langinstall = $_SESSION['ilang'];
} else {
	$langinstall = "en";
}

require(SED_ROOT . '/system/install/lang/' . $langinstall . '/install.' . $langinstall . '.lang.php');

/* === === === */

require(SED_ROOT . '/system/install/install.setup.php');

mb_internal_encoding('UTF-8');

unset($res, $cfg_data, $step);
$m = sed_import('m', 'G', 'ALP', 8);

$sys['secure'] = sed_is_ssl();
$sys['scheme'] = $sys['secure'] ? 'https' : 'http';
$sys['host'] = $_SERVER['HTTP_HOST'];
$sys['dir_uri'] = (mb_strlen(dirname($_SERVER['PHP_SELF'])) > 1) ? dirname($_SERVER['PHP_SELF']) : "/";
if ($sys['dir_uri'][mb_strlen($sys['dir_uri']) - 1] != '/') {
	$sys['dir_uri'] .= '/';
}
$sys['abs_url'] = $sys['scheme'] . '://' . $sys['host'] . $sys['dir_uri'];
$sys['request_uri'] = $_SERVER['REQUEST_URI'];

$res = "";

// ------------------------------------

$disp_header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
$disp_header .= '<html xmlns="http://www.w3.org/1999/xhtml">';
$disp_header .= '<head>';
$disp_header .= '<base href="' . $sys['abs_url'] . '" />';
$disp_header .= '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';
$disp_header .= '<meta name="description" content="' . $L['install_title'] . '" />';
$disp_header .= "<script type=\"text/javascript\">  <!--
 function toggleblock(id)
	{
	var bl = document.getElementById(id);
	if(bl.style.display == 'none')
		{ bl.style.display = ''; }
	else
		{ bl.style.display = 'none'; }
	} 
  //-->
</script>";
$disp_header .= '<style type="text/css">';
$disp_header .= '<!-- ';
$disp_header .= 'body 		{ background-color: #FFFFFF; padding:24px; text-align:center; margin:0; ';
$disp_header .= 'font-family: Segoe UI, Verdana, Arial, Helvetica; color: #101010; font-size: 13px; }';
$disp_header .= '#conte	{ width:800px; margin:20px auto 0 auto; padding:8px 20px 12px 20px; text-align:left; background-color: #F0F0F0; border-radius:20px;} ';
$disp_header .= 'a 			{ text-decoration: none; color: #306797; }';
$disp_header .= 'a:hover 	{ text-decoration: none; color: #000000; background-color: #AFCCE5; }';
$disp_header .= '.no 	 	{ color: #c31d1d; }';
$disp_header .= '.yes 	 	{ color: #2d992d; }';
$disp_header .= 'table.cells 	{ width:100%; margin:0; padding:0; }';
$disp_header .= 'table.cells td	{ padding:5px; background-color:#E6E6E6; margin:0; }';
$disp_header .= '.coltop		{ text-align:center; font-size:95%;  background-color:#C9C9C9!important; color:#707070; }';
$disp_header .= '.desc 		{ font-size:90%; padding:3px; color:#646464; }';
$disp_header .= '-->';
$disp_header .= '</style>';
$disp_header .= '<link rel="stylesheet" type="text/css" href="system/install/install.css" />';
$disp_header .= '<title>' . $L['install_title'] . '</title>';
$disp_header .= '</head>';
$disp_header .= '<body>';
$disp_header .= '<div id="conte">';
$disp_footer = '</div><br /></div><br /></body><br /></html>';

// -----------------------------------------------

switch ($m) {
	case 'config':

		$step = 3;

		// ---------------------------------------

		$res .= "<h2>" . $L['install_build_config'] . " <strong>" . $cfg['config_file'] . "</strong>...</h2>";

		$m = sed_import('m', 'G', 'ALP', 24);
		$mysqlhost = sed_import('mysqlhost', 'P', 'TXT', 128);
		$mysqluser = sed_import('mysqluser', 'P', 'TXT', 128);
		$mysqlpassword = sed_import('mysqlpassword', 'P', 'TXT', 128);
		$mysqldb = sed_import('mysqldb', 'P', 'TXT', 128);
		$sqldb = sed_import('sqldb', 'P', 'TXT', 10);

		$md_site_secret = md5(sed_unique(16)); // New sed171
		$cfg['site_secret'] = $md_site_secret;

		$sqldbprefix = sed_import('sqldbprefix', 'P', 'TXT', 16);
		$defaultskin = sed_import('defaultskin', 'P', 'TXT', 32);
		$defaultlang = sed_import('defaultlang', 'P', 'ALP', 2);

		require(SED_ROOT . '/system/install/install.config.php');

		if (is_writable($cfg['data_root']) && !file_exists($cfg['config_file'])) {

			// ---------------------------------------

			$res .= "<h2>" . $L['install_setting_mysql'] . "</h2>";

			$res .= $L['install_creating_mysql'] . "<br />";

			require(SED_ROOT . '/system/database.' . $cfg['sqldb'] . '.php');
			$connection_id = sed_sql_connect($mysqlhost, $mysqluser, $mysqlpassword, $mysqldb);
			$cfg['mysqldb'] = $sqldbprefix;
			sed_sql_set_charset($connection_id, 'utf8');

			$fp = @fopen($cfg['config_file'], 'w');
			@fwrite($fp, $cfg_data);
			@fclose($fp);

			$cfg_size = filesize($cfg['config_file']);
			$res .= "Size of the file : " . $cfg_size . " bytes.<br />";
			$res .= "<span class=\"yes\">" . $L['install_looks_chmod'] . "</span>";
			@chmod($cfg['config_file'], 0444);

			require(SED_ROOT . '/system/install/install.database.php');

			@define('SED_ADMIN', TRUE);
			unset($query);

			$res .= $L['install_presettings'] . "<br />";

			require_once('system/functions.admin.php');
			foreach ($cfgmap as $i => $line) {
				$query[] = "('core','" . $line[0] . "','" . $line[1] . "','" . $line[2] . "'," . (int)$line[3] . ",'" . $line[4] . "')";
			}
			$query = implode(",", $query);

			$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "config (config_owner, config_cat, config_order, config_name, config_type, config_value) VALUES " . $query);

			$res .= $L['install_adding_administrator'] . "<br />";

			$rusername = sed_import('admin_name', 'P', 'TXT', 24, TRUE);
			$rpassword = sed_import('admin_pass', 'P', 'TXT', 16);
			$ruseremail = sed_import('admin_email', 'P', 'TXT', 64, TRUE);
			$rcountry = sed_import('admin_country', 'P', 'TXT');
			$ip = $_SERVER['REMOTE_ADDR'];

			$rusername = (empty($rusername)) ? "Admin" : $rusername;
			$rpassword = (empty($rpassword)) ? "123456" : $rpassword;

			$defgroup = 5;

			$mdsalt = sed_unique(16); // New sed172    

			$mdpass = sed_hash($rpassword, 1, $mdsalt);  // New sed172

			$mdpass_secret = md5(sed_unique(16)); // New sed171

			$ruserbirthdate = 0;
			$rtimezone = 0;
			$validationkey = md5(microtime());

			$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "users
			(user_name,
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
			user_lastip)
			VALUES
			('" . sed_sql_prep($rusername) . "',
			'$mdpass',
			'$mdsalt',
			'$mdpass_secret',
			1,			
			" . (int)$defgroup . ",
			'" . sed_sql_prep($rcountry) . "',
			'',
			'" . sed_sql_prep($rtimezone) . "',
			'',
			'',
			'" . sed_sql_prep($ruseremail) . "',
			1,
			0,
			'" . $defaultskin . "',
			'" . $defaultlang . "',
			" . (int)time() . ",
			0,
			'$validationkey',
			'',
			0,
			'', '', '" . $ip . "')");

			$userid = sed_sql_insertid();

			$usr['id'] = $userid;
			$usr['name'] = $userid;
			$usr['ip'] = $userid;

			$_SESSION['usr'] = $usr;

			$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$defgroup . ")");

			unset($mysqlhost, $mysqluser, $mysqlpassword, $mysqldb);

			$res .= "<span class=\"yes\">" . $L['install_done'] . "</span>";

			$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=plugins") . "\" method=\"post\">";
			$res .= "<input type=\"submit\" class=\"submit btn\" style=\"margin-top:32px;\" value=\"" . $L['install_contine_toplugins'] . "\">";
			$res .= "</form>";
		} else {
			$res .= "<span class=\"no\">" . $L['install_error_notwrite'] . "</span>";
		}

		break;

		// -----------------------------------------------

	case 'plugins':

		$step = 4;

		$res .= "<h3>" . $L['install_plugins'] . " :</h3>";
		$res .= $L['install_optional_plugins'];
		$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=plinst") . "\" method=\"post\">";
		$res .= "<table class=\"cells striped\">";
		$res .= "<tr><td colspan=\"2\" style=\"width:80%;\" class=\"coltop\">" . $L['install_plugins'] . "</td>";
		$res .= "<td style=\"width:10%;\" class=\"coltop\">" . $L['install_install'] . "</td>";
		$res .= "</tr>";

		$handle = opendir("plugins");
		while ($f = readdir($handle)) {
			if (!is_file($f) && $f != '.' && $f != '..' && $f != 'code') {
				$plugins[] = $f;
			}
		}
		closedir($handle);
		sort($plugins);

		foreach ($plugins as $k => $v) {
			$extplugin_info = "plugins/" . $v . "/" . $v . ".setup.php";
			$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

			$checked = (isset($info['Installer_skip']) && $info['Installer_skip'] == 1) ? '' : "checked=\"checked\"";

			$res .= "<tr><td style=\"width:6%; text-align:center;\">";
			$res .= sed_plugin_icon($v);
			$res .= "</td><td><strong>" . $info['Name'] . "</strong><br /><spab class=\"desc\">" . $info['Description'] . "</span></td>";
			$res .= "<td style=\"width:6%; text-align:center;\">";
			$res .= "<input type=\"checkbox\" class=\"checkbox\" name=\"pl[]\" value=\"" . $v . "\" " . $checked . " />";
			$res .= "</td></tr>";
		}

		$res .= "</table>";
		$res .= "<input type=\"submit\" class=\"submit btn\" style=\"margin-top:32px;\" value=\"" . $L['install_now'] . "\">";
		$res .= "</form>";

		break;

		// -----------------------------------------------

	case 'plinst':

		$step = 5;

		$pl = sed_import('pl', 'P', 'ARR');
		$res .= "<h3>" . $L['install_installing_plugins'] . "</h3>";

		$usr = $_SESSION['usr'];
		$sys['now_offset'] = time();

		$j = 0;
		$log = '';

		if (!isset($sed_groups)) {
			$sql = sed_sql_query("SELECT * FROM $db_groups WHERE grp_disabled = 0 ORDER BY grp_level DESC");
			if (sed_sql_numrows($sql) > 0) {
				while ($row = sed_sql_fetchassoc($sql)) {
					$sed_groups[$row['grp_id']] = array(
						'id' => $row['grp_id'],
						'alias' => $row['grp_alias'],
						'level' => $row['grp_level'],
						'disabled' => $row['grp_disabled'],
						'hidden' => $row['grp_hidden'],
						'title' => sed_cc($row['grp_title']),
						'desc' => sed_cc($row['grp_desc']),
						'icon' => $row['grp_icon'],
						'pfs_maxfile' => $row['grp_pfs_maxfile'],
						'pfs_maxtotal' => $row['grp_pfs_maxtotal'],
						'ownerid' => $row['grp_ownerid']
					);
				}
			}
		}

		foreach ($pl as $k => $v) {
			$j++;
			$extplugin_info = "plugins/" . $v . "/" . $v . ".setup.php";
			$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
			$res .= "- Installing : " . $info['Name'] . "<br />";
			$log .= sed_plugin_install($v);
		}

		$res .= "<br />" . $j . " " . $L['install_installed_plugins'];
		$res .= "<a onclick=\"return toggleblock('logf')\" href=\"#\">" . $L['install_display_log'] . "</a>).<br />";
		$res .= "<div name=\"log\" id=\"logf\" style=\"display:none;\" >";
		$res .= $log . "</div>";

		sed_stat_create('installed', 1);

		$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=home") . "\" method=\"post\">";
		$res .= "<input type=\"submit\" class=\"submit btn\" style=\"margin-top:32px;\" value=\"" . $L['install_contine_homepage'] . "\">";
		$res .= "</form>";

		break;

		// -----------------------------------------------

	case 'home':

		sed_redirect(sed_url("index", "", "", true));
		exit;

		break;

		// -----------------------------------------------

	case 'error':

		$res .= "<h2>" . $L['install_error'] . "</h2>";
		$res .= "<div class=\"no\" style=\"text-align:center; padding:32px;\">";
		$res .= $L['install_wrong_manual'];
		$res .= "</div>";

		break;

		// -----------------------------------------------

	case 'param':

		$step = 2;

		$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=config") . "\" method=\"post\">";

		$res .= "<h3>" . $L['install_database_setup'] . "</h3>";

		$res .= "<table class=\"cells\">";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_database_hosturl'] . "</td><td colspan=\"2\">";
		$res .= "<input type=\"text\" name=\"mysqlhost\" size=\"32\" value=\"localhost\" maxlength=\"128\" />";
		$res .= " (" . $L['install_always_localhost'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_database_user'] . "</td><td colspan=\"2\">";
		$res .= "<input type=\"text\" name=\"mysqluser\" size=\"32\" value=\"root\" maxlength=\"128\" />";
		$res .= " (" . $L['install_see_yourhosting'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_database_password'] . "</td><td colspan=\"2\"><input type=\"text\" name=\"mysqlpassword\" size=\"32\" value=\"\" maxlength=\"128\" />";
		$res .= " (" . $L['install_see_yourhosting'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_database_name'] . "</td><td colspan=\"2\">";
		$res .= "<input type=\"text\" name=\"mysqldb\" size=\"32\" value=\"\" maxlength=\"128\" />";
		$res .= " (" . $L['install_see_yourhosting'] . ")</td></tr>";

		$res .= "<tr><td style=\"width:172px;\">" . $L['install_database_tableprefix'] . "</td><td colspan=\"2\">";
		$res .= "<input type=\"text\" name=\"sqldbprefix\" size=\"32\" value=\"sed_\" maxlength=\"16\" />";
		$res .= " (" . $L['install_seditio_already'] . ")</td></tr>";

		$res .= "<tr><td style=\"width:172px;\">" . $L['install_mysql_connector'] . "</td><td colspan=\"2\">";

		if ((extension_loaded('mysql')) && (extension_loaded('mysqli'))) {
			$param_value = "mysql,mysqli";
			$cheked = 'mysqli';
		} elseif (extension_loaded('mysqli')) {
			$param_value = "mysqli";
			$cheked = 'mysqli';
		} else {
			$param_value = "mysql";
			$cheked = 'mysql';
		}

		$res .= sed_selectbox($cheked, 'sqldb', $param_value, false);
		$res .= " (" . $L['install_mysql_preffered'] . ")</td></tr>";

		$res .= "</table>";

		$res .= "<h3>" . $L['install_skinandlang'] . "</h3>";

		$res .= "<table style=\"width:100%;\" class=\"cells\">";

		$res .= "<tr><td style=\"width:172px; vertical-align:top;\">" . $L['install_default_skin'] . "</td><td  style=\"vertical-align:top;\">";
		$res .= sed_radiobox_skin($cfg['default_skin'], 'defaultskin') . "</td></tr>";
		$res .= "<tr><td style=\"width:96px;\">" . $L['install_default_lang'] . "</td><td>";
		$res .= sed_selectbox_lang('', 'defaultlang') . "</td></tr>";

		$res .= "</table>";

		$res .= "<h3>" . $L['install_admin_account'] . "</h3>";

		$res .= "<table style=\"width:100%;\" class=\"cells\">";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_account_name'] . "</td>";
		$res .= "<td><input type=\"text\" name=\"admin_name\" size=\"32\" value=\"\" maxlength=\"128\" /> (" . $L['install_ownaccount_name'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_password'] . "</td>";
		$res .= "<td><input type=\"text\" name=\"admin_pass\" size=\"32\" value=\"\" maxlength=\"128\" /> (" . $L['install_least8chars'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_email'] . "</td>";
		$res .= "<td><input type=\"text\" name=\"admin_email\" size=\"32\" value=\"\" maxlength=\"128\" /> (" . $L['install_doublecheck'] . ")</td></tr>";
		$res .= "<tr><td style=\"width:172px;\">" . $L['install_country'] . "</td>";
		$res .= "<td>" . sed_selectbox_countries(isset($cfg['defaultcountry']) ? $cfg['defaultcountry'] : '', 'admin_country') . "</td></tr>";
		$res .= "<tr><td colspan=\"2\" style=\"padding-top:32px; text-align:center;\"><input type=\"submit\" class=\"submit btn\" value=\"" . $L['install_validate'] . "\"></td></tr>";
		$res .= "</table>";

		$res .= "</form>";

		break;

		// -----------------------------------------------

	case 'onestep':

		$step = 1;

		$res .= "<h2>" . $L['install_auto_installer'] . "</h2>";

		$res .= $L['install_create_configfile'];

		$res .= "<table style=\"width:100%; margin-top:8px;\">";
		$res .= "<tr><td style=\"width:50%px; vertical-align:top;\">";

		$res .= "<table style=\"width:100%;\" class=\"cells\">";
		foreach ($rwfolders as $i => $j) {
			$res .= "<tr><td style=\"width:172px;\">" . $L['install_folder'] . " <strong>$j</strong> :</td><td style=\"padding-right:16px;\">";
			if (file_exists($j)) {
				$res .= (is_writable($j)) ? '<span class="yes">' . $L['install_writable'] . '</span>' : '<span class="no">' . $L['install_not_writable'] . '</span>';
			} else {
				$res .= '<span class="no">' . $L['install_not_found'] . '</span>';
			}
			$res .= "</td></tr>";
		}
		$res .= "</table width:100%; >";

		$res .= "</td><td style=\"width:50%px; vertical-align:top;\">";

		$res .= "<table style=\"width:100%;\" class=\"cells\">";
		$res .= "<tr><td style=\"width:150px;\">" . $L['install_file'] . " <strong>datas/config.php</strong> :</td><td>";

		if (file_exists($cfg['config_file'])) {
			$res .= (is_writable($cfg['config_file'])) ? '<span class="yes">' . $L['install_found_writable'] . '</span>' : '<span class="no">' . $L['install_found_notwritable'] . '</span>';
		} else {
			$res .= (is_writable($cfg['data_root'])) ? '<span class="yes">' . $L['install_notfound_folderwritable'] . '</span>' : '<span class="no">' . $L['install_notfound_foldernotwritable'] . '</span>';
		}
		$res .= "</td></tr>";

		$res .= "<tr><td style=\"width:150px;\">" . $L['install_phpversion'] . "</td><td>";
		$res .= (version_compare(PHP_VERSION, '5.3') > 0) ? '<span class="yes">' . PHP_VERSION . ' : ' . $L['install_ok'] . '</span>' : '<span class="no">' . PHP_VERSION . ' : ' . $L['install_too_old'] . '</span>';
		$res .= "</td></tr>";

		$res .= "<tr><td style=\"width:150px;\">MB String</td><td>";
		$res .= (extension_loaded('mbstring')) ? '<span class="yes">' . $L['install_available'] . '</span>' : '<span class="no">' . $L['install_missing'] . '</span>';
		$res .= "</td></tr>";

		$res .= "<tr><td style=\"width:150px;\">" . $L['install_gd_extension'] . "</td><td>";
		$res .= (extension_loaded('gd')) ? '<span class="yes">' . $L['install_available'] . '</span>' : '<span class="no">' . $L['install_missing'] . '</span>';
		$res .= "</td></tr>";

		/*	$res .= "<tr><td style=\"width:150px;\">".$L['install_mysql_extension']."</td><td>";
	$res .= (extension_loaded('mysql')) ? '<span class="yes">'.$L['install_available'].'</span>' : '<span class="no">'.$L['install_missing'].'</span>';
	$res .= "</td></tr>";
*/
		$res .= "<tr><td style=\"width:150px;\">" . $L['install_mysqli_extension'] . "</td><td>";
		$res .= (extension_loaded('mysqli')) ? '<span class="yes">' . $L['install_available'] . '</span>' : '<span class="no">' . $L['install_missing'] . '</span>';
		$res .= "</td></tr>";

		$res .= "<tr><td colspan=\"2\" style=\"padding-top:16px; text-align:center;\">[ <a href=\"" . sed_url("install") . "\">" . $L['install_refresh'] . "</a> ]</td></tr>";
		$res .= "</table>";

		$res .= "</td></tr>";
		$res .= "</table>";

		$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=param") . "\" method=\"post\">";
		$res .= "<input type=\"submit\" class=\"submit btn\" style=\"margin-top:32px;\" value=\"" . $L['install_nextstep'] . "\">";
		$res .= "</form>";

		break;

	default:

		$step = 0;

		$res .= "<h3>" . $L['install_language installation'] . "</h3>";

		$res .= "<form name=\"install\" action=\"" . sed_url("install", "m=onestep") . "\" method=\"post\">";
		$res .= "<table style=\"width:100%;\" class=\"cells\">";

		$res .= "<tr><td style=\"width:196px;\">" . $L['install_select_language installation'] . "</td><td>";
		$res .= sed_selectbox_lang_install('', 'langinstall') . "</td></tr>";

		$res .= "</table>";


		$res .= "<input type=\"submit\" class=\"submit btn\" style=\"margin-top:32px;\" value=\"" . $L['install_nextstep'] . "\">";
		$res .= "</form>";

		break;
}

$disp_shared = "<table style=\"width:100%; border-bottom:#CCCCCC dashed 1px;\">";
$disp_shared .= "<tr><td style=\"width:50%; vertical-align:middle;\"><img src=\"system/install/seditio.png\" alt=\"\" /></td>";
$disp_shared .= "<td style=\"width:50%; vertical-align:middle;\">";
foreach ($steps as $i => $j) {
	$disp_shared .= ($step == $i) ? "<strong>" : '';
	$disp_shared .= "Step $i : $j";
	$disp_shared .= ($step == $i) ? " &lt;--</strong>" : '';
	$disp_shared .= "<br />";
}
$disp_shared .= "</td></tr></table>";

echo ($disp_header);
echo ($disp_shared);
echo ($res);
echo ($disp_footer);
