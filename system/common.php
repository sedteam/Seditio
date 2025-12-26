<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/common.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Common
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

error_reporting(E_ALL ^ E_NOTICE);

/* ======== Connect to the SQL DB======== */

require(SED_ROOT . '/system/database.' . $cfg['sqldb'] . '.php');
$connection_id = sed_sql_connect($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword'], $cfg['mysqldb']);
unset($cfg['mysqlhost'], $cfg['mysqluser'], $cfg['mysqlpassword']);
sed_sql_set_charset($connection_id, 'utf8mb4');

mb_internal_encoding('UTF-8'); // New v171

/* ======== Configuration settings (from the DB) ======== */

$sql_config = sed_sql_query("SELECT config_owner, config_cat, config_name, config_value FROM $db_config");

while ($row = sed_sql_fetchassoc($sql_config)) {
	if ($row['config_owner'] == 'core') {
		$cfg[$row['config_name']] = $row['config_value'];
	} else {
		$cfg['plugin'][$row['config_cat']][$row['config_name']] = $row['config_value'];
	}
}

/* ======== Extra settings (the other presets are in functions.php) ======== */

$sys['day'] = @date('Y-m-d');
$sys['now'] = time();
$sys['now_offset'] = $sys['now'] - $cfg['servertimezone'] * 3600;
$sys['site_id'] = 'sed' . substr(md5(empty($cfg['site_secret']) ? $cfg['mainurl'] : $cfg['site_secret']), 0, 16);
$online_timedout = $sys['now'] - $cfg['timedout'];
$cfg['doctype'] = sed_setdoctype($cfg['doctypeid']);
$cfg['css'] = $cfg['defaultskin'];
$usr['ip'] = sed_get_userip();
$sys['unique'] = sed_unique(16);

/* ================================== */

$sys['request_uri'] = $_SERVER['REQUEST_URI'];

$sys['url'] = base64_encode($sys['request_uri']);
$sys['url_redirect'] = 'redirect=' . $sys['url'];
$redirect = sed_import('redirect', 'G', 'SLU');

$url_default = parse_url($cfg['mainurl']);
$sys['secure'] = sed_is_ssl();
$sys['scheme'] = $sys['secure'] ? 'https' : 'http';
$sys['domain'] = preg_replace('#^www\.#', '', $url_default['host']);

$sys['host'] = sed_set_host($sys['domain']);

if (
	$sys['host'] == $url_default['host']
	|| $cfg['multihost']
	|| $sys['host'] != 'www.' . $sys['domain']
	&& preg_match('`^.+\.' . preg_quote($sys['domain']) . '$`i', $sys['host'])
) {
	$sys['host'] = preg_match('#^[\w\p{L}\.\-]+$#u', $sys['host']) ? $sys['host'] : $url_default['host'];
	$sys['domain'] = preg_replace('#^www\.#', '', $sys['host']);
} else {
	$sys['host'] = $url_default['host'];
}

$sys['port'] = empty($url_default['port']) ? '' : ':' . $url_default['port'];

$sys['dir_uri'] = (mb_strlen(dirname($_SERVER['PHP_SELF'])) > 1) ? dirname($_SERVER['PHP_SELF']) : "/";
if ($sys['dir_uri'][mb_strlen($sys['dir_uri']) - 1] != '/') {
	$sys['dir_uri'] .= '/';
}

$sys['abs_url'] = $sys['scheme'] . '://' . $sys['host'] . $sys['port'] . $sys['dir_uri'];

$sys['canonical_url'] = $sys['scheme'] . '://' . $sys['host'] . $sys['port'] . $sys['request_uri'];

if (empty($cfg['cookiedomain'])) $cfg['cookiedomain'] = $sys['domain'];
if (empty($cfg['cookiepath'])) $cfg['cookiepath'] = $sys['dir_uri'];

sed_setcookie_params(0, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);

if ($cfg['multihost']) {
	$cfg['mainurl'] = mb_substr($sys['abs_url'], 0, -1);
}
/* ================================== */

$usr['user_agent'] = $_SERVER['HTTP_USER_AGENT']; //New v173
$check_defskin = "skins/" . $cfg['defskin'] . "/header.tpl"; //New v173
$cfg['defaultskin'] = (!empty($cfg['defskin']) && @file_exists($check_defskin)) ? $cfg['defskin'] : $cfg['defaultskin']; //New v173

/* ======== Internal cache ======== */

if ($cfg['cache']) {
	$sql = sed_cache_getall();
	if ($sql) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$newvar = $row['c_name']; //fix 178 php7.1
			$$newvar = unserialize($row['c_value']);
		}
	}
}

/* ======== Check the banlist ======== */

sed_check_banlist($usr['ip']);

/* ======== Groups ======== */

if (!isset($sed_groups)) {
	$sql = sed_sql_query("SELECT * FROM $db_groups WHERE grp_disabled=0 ORDER BY grp_level DESC");

	if (sed_sql_numrows($sql) > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$sed_groups[$row['grp_id']] = array(
				'id' => $row['grp_id'],
				'alias' => $row['grp_alias'],
				'level' => $row['grp_level'],
				'disabled' => $row['grp_disabled'],
				'hidden' => $row['grp_hidden'],
				//'state' => $row['grp_state'],
				'title' => sed_cc($row['grp_title']),
				'desc' => sed_cc($row['grp_desc']),
				'icon' => $row['grp_icon'],
				'color' => $row['grp_color'],
				'pfs_maxfile' => $row['grp_pfs_maxfile'],
				'pfs_maxtotal' => $row['grp_pfs_maxtotal'],
				'ownerid' => $row['grp_ownerid']
			);
		}
	} else {
		sed_diefatal('No groups found.');
	}

	sed_cache_store('sed_groups', $sed_groups, 3600);
}

/* ======== User/Guest ======== */

$usr['id'] = 0;
$usr['sessionid'] = '';
$usr['name'] = '';
$usr['level'] = 0;
$usr['maingrp'] = 0;
$usr['lastvisit'] = 30000000000;
$usr['lastlog'] = 0;
$usr['timezone'] = empty($cfg['defaulttimezone']) ? 0 : $cfg['defaulttimezone'];
$usr['newpm'] = 0;
$usr['messages'] = 0;

session_start();

if (empty($_SESSION['guest_sourcekey'])) {
	$_SESSION['guest_sourcekey'] = md5(sed_unique(16));
}
$usr['sourcekey'] = $_SESSION['guest_sourcekey'];

if (isset($_SESSION[$sys['site_id'] . '_n']) && ($cfg['authmode'] == 2 || $cfg['authmode'] == 3)) {
	$rsedition = $_SESSION[$sys['site_id'] . '_n'];
	$rseditiop = $_SESSION[$sys['site_id'] . '_p'];
	$rseditios = $_SESSION[$sys['site_id'] . '_s'];
} elseif (isset($_COOKIE[$sys['site_id']]) && ($cfg['authmode'] == 1 || $cfg['authmode'] == 3)) {
	$u = base64_decode($_COOKIE[$sys['site_id']]);
	$u = explode(':_:', $u);
	$rsedition = sed_import($u[0], 'D', 'INT');
	$rseditiop = sed_import($u[1], 'D', 'H32');
	$rseditios = sed_import($u[2], 'D', 'ALP');
}

if (isset($rsedition) && $rsedition > 0 && $cfg['authmode'] > 0) {
	if (mb_strlen($rseditiop) != 32) {
		sed_diefatal('Wrong value for the password.');
	}

	if ($cfg['ipcheck']) {
		$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='$rsedition' AND user_secret='$rseditiop' AND user_lastip='" . $usr['ip'] . "'");
	} else {
		$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='$rsedition' AND user_secret='$rseditiop'");
	}

	if ($row = sed_sql_fetcharray($sql)) {
		if ($row['user_maingrp'] > 3) {
			$usr['id'] = $row['user_id'];
			$usr['sessionid'] = ($cfg['authmode'] == 1) ? sed_hash($row['user_lastvisit'], 2) : sed_hash($row['user_secret'], 2);
			$usr['sourcekey'] = md5($row['user_secret'] . $row['user_lastvisit']);
			$usr['name'] = $row['user_name'];
			$usr['maingrp'] = $row['user_maingrp'];
			$usr['lastvisit'] = $row['user_lastvisit'];
			$usr['lastlog'] = $row['user_lastlog'];
			$usr['timezone'] = $row['user_timezone'];
			$usr['skin'] = ($cfg['forcedefaultskin']) ? $cfg['defaultskin'] : $row['user_skin'];
			$usr['lang'] = ($cfg['forcedefaultlang']) ? $cfg['defaultlang'] : $row['user_lang'];
			$usr['newpm'] = $row['user_newpm'];
			$usr['auth'] = unserialize($row['user_auth']);
			$usr['level'] = $sed_groups[$usr['maingrp']]['level'];
			$usr['profile'] = $row;
			$sys['sql_update_lastvisit'] = '';
			$sys['sql_update_auth'] = '';

			if ($usr['lastlog'] + $cfg['timedout'] < $sys['now_offset']) {
				$sys['comingback'] = TRUE;
				$usr['lastvisit'] = $usr['lastlog'];
				$sys['sql_update_lastvisit'] = ", user_lastvisit='" . $usr['lastvisit'] . "'";
			}

			if (empty($row['user_auth'])) {
				$usr['auth'] = sed_auth_build($usr['id'], $usr['maingrp']);
				$sys['sql_update_auth'] = ", user_auth='" . serialize($usr['auth']) . "'";
			}

			$sql = sed_sql_query("UPDATE $db_users SET user_lastlog='" . $sys['now_offset'] . "', user_lastip='" . $usr['ip'] . "', user_sid='" . $usr['sessionid'] . "', user_logcount=user_logcount+1 " . $sys['sql_update_lastvisit'] . " " . $sys['sql_update_auth'] . " WHERE user_id='" . $usr['id'] . "'");
		}
	}
} else {
	if (empty($rseditios) && ($cfg['authmode'] == 1 || $cfg['authmode'] == 3)) {
		$u = base64_encode('0:_:0:_:' . $cfg['defaultskin']);
		sed_setcookie($sys['site_id'], $u, time() + $cfg['cookielifetime'], $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
	} else {
		$skin = ($cfg['forcedefaultskin']) ? $cfg['defaultskin'] : $rseditios;
	}
}

if ($usr['id'] == 0) {
	$usr['auth'] = sed_auth_build(0);
	$usr['skin'] = (empty($usr['skin'])) ? $cfg['defaultskin'] : $usr['skin'];
	$usr['lang'] = $cfg['defaultlang'];
}

if ($cfg['devmode'] && sed_auth('admin', 'a', 'A')) {
	XTemplate::configure(['debug' => true]);
}

/* ======== GET imports ======== */

$z = sed_import('z', 'G', 'ALP', 32);
$z = (!empty($z)) ? mb_strtolower($z) : $z;

$m = sed_import('m', 'G', 'ALP', 24);
$n = sed_import('n', 'G', 'ALP', 24);
$a = sed_import('a', 'G', 'ALP', 24);
$b = sed_import('b', 'G', 'ALP', 24);

/* ======== Plugins ======== */

if (!isset($sed_plugins)) {
	$sql = sed_sql_query("SELECT * FROM $db_plugins WHERE pl_active=1 ORDER BY pl_hook ASC, pl_order ASC");
	if (sed_sql_numrows($sql) > 0) {
		while ($row = sed_sql_fetcharray($sql)) {
			$sed_plugins[$row['pl_hook']][] = $row;
			//$sed_plugins[$row['pl_code']]['pl_title'] = $row['pl_title'];
		}
	}
	sed_cache_store('sed_plugins', $sed_plugins, 3300);
}

/* ======== Hooks for plugins (standalone) ======== */

if (defined('SED_PLUG') && !empty($_GET['e'])) {
	$extp = sed_getextplugins('common.plug.' . $_GET['e']);
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
}

/* ======== Hooks for plugins (admin tools) ======== */

if (defined('SED_ADMIN') && $m == 'manage' && !empty($_GET['p'])) {
	$extp = sed_getextplugins('common.tool.' . $_GET['p']);
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
}

/* ======== Anti-XSS protection ======== */

$xg = sed_import('x', 'G', 'ALP');
$xp = sed_import('x', 'P', 'ALP');

$xk = sed_check_xp();

$extp = sed_getextplugins('common');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}


/* ======== Gzip and output filtering ======== */

if ($cfg['gzip']) {
	@ob_start('ob_gzhandler');
} else {
	ob_start();
}

ob_start('sed_outputfilters'); //fix v173

/* ======== Who's online (part 1) and shield protection ======== */

if (!$cfg['disablewhosonline']) {
	$sql = sed_sql_query("DELETE FROM $db_online WHERE online_lastseen<'$online_timedout'");
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_online WHERE online_name='v'");
	$sys['whosonline_vis_count'] = sed_sql_result($sql, 0, 'COUNT(*)');
	$sql = sed_sql_query("SELECT online_name, online_userid FROM $db_online WHERE online_name NOT LIKE 'v' ORDER BY online_name ASC");
	$sys['whosonline_reg_count'] = sed_sql_numrows($sql);
	$sys['whosonline_all_count'] = $sys['whosonline_reg_count'] + $sys['whosonline_vis_count'];
	$out['whosonline_reg_list'] = '';
	$ii = 0;
	while ($row = sed_sql_fetchassoc($sql)) {
		$out['whosonline_reg_list'] .= ($ii > 0) ? ', ' : '';
		$out['whosonline_reg_list'] .= sed_build_user($row['online_userid'], sed_cc($row['online_name']));
		$sed_usersonline[] = $row['online_userid'];
		$ii++;
	}
}

/* =========== Shield Protection ================= */


if (!$cfg['disablewhosonline'] || $cfg['shieldenabled']) {
	if ($usr['id'] > 0) {
		$sql = sed_sql_query("SELECT online_id FROM $db_online WHERE online_userid='" . $usr['id'] . "'");

		if ($row = sed_sql_fetchassoc($sql)) {
			$online_count = 1;

			if ($cfg['shieldenabled']) {
				$sql2 = sed_sql_query("SELECT online_shield, online_action, online_hammer, online_lastseen FROM $db_online WHERE online_userid='" . $usr['id'] . "'");
				if ($row = sed_sql_fetchassoc($sql2)) {
					$shield_limit = $row['online_shield'];
					$shield_action = $row['online_action'];
					$shield_hammer = sed_shield_hammer($row['online_hammer'], $shield_action, $row['online_lastseen']);
				}
			}
		}
	} else {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_online WHERE online_ip='" . $usr['ip'] . "'");
		$online_count = sed_sql_result($sql, 0, 'COUNT(*)');

		if ($online_count > 0) {

			if ($cfg['shieldenabled']) {
				$sql2 = sed_sql_query("SELECT online_shield, online_action, online_hammer, online_lastseen FROM $db_online WHERE online_ip='" . $usr['ip'] . "'");
				if ($row = sed_sql_fetchassoc($sql2)) {
					$shield_limit = $row['online_shield'];
					$shield_action = $row['online_action'];
					$shield_hammer = sed_shield_hammer($row['online_hammer'], $shield_action, $row['online_lastseen']);
				}
			}
		}
	}
}

/* ======== Max users ======== */

if (!$cfg['disablehitstats']) {
	$maxusers = 0;

	$sql = sed_sql_query("SELECT stat_value FROM $db_stats where stat_name='maxusers' LIMIT 1");
	if ($row = sed_sql_fetcharray($sql)) {
		$maxusers = $row[0];
	} else {
		$sql = sed_sql_query("INSERT INTO $db_stats (stat_name, stat_value) VALUES ('maxusers', 1)");
	}

	if ($maxusers < $sys['whosonline_all_count']) {
		$sql = sed_sql_query("UPDATE $db_stats SET stat_value='" . $sys['whosonline_all_count'] . "' WHERE stat_name='maxusers'");
	}
}

/* ======== Language ======== */

$mlang = SED_ROOT . '/system/lang/' . $usr['lang'] . '/main.lang.php';

if (!file_exists($mlang)) {
	$usr['lang'] = $cfg['defaultlang'];
	$mlang = SED_ROOT . '/system/lang/' . $usr['lang'] . '/main.lang.php';

	if (!file_exists($mlang)) {
		sed_diefatal('Main language file not found.');
	}
}

$lang = $usr['lang'];
require($mlang);

$yesno_arr = array(1 => $L['Yes'], 0 => $L['No']);
$yesno_revers_arr = array(0 => $L['Yes'], 1 => $L['No']);

/* ======== Who's online part 2 ======== */

$out['whosonline'] = ($cfg['disablewhosonline']) ? '' : $sys['whosonline_reg_count'] . ' ' . $L['com_members'] . ', ' . $sys['whosonline_vis_count'] . ' ' . $L['com_guests'];
$out['copyright'] = "<a href=\"https://seditio.org\">" . $L['foo_poweredby'] . " Seditio</a>";

/* ======== Various ======== */

$out['ic_jumpto'] = "<img src=\"system/img/admin/jumpto.gif\" alt=\"\" />";
$out['ic_delete'] = "<img src=\"system/img/admin/delete.png\" alt=\"\" />";
$out['ic_edit'] = "<img src=\"system/img/admin/edit.png\" alt=\"\" />";
$out['ic_checked'] = "<img src=\"system/img/admin/checked.png\" alt=\"\" />";
$out['ic_unchecked'] = "<img src=\"system/img/admin/unchecked.png\" alt=\"\" />";
$out['ic_set'] = "<img src=\"system/img/admin/set.png\" alt=\"\" />";
$out['ic_reset'] = "<img src=\"system/img/admin/reset.png\" alt=\"\" />";
$out['ic_warning'] = "<img src=\"system/img/admin/warning.png\" alt=\"\" />";

$out['ic_arrow_up'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-up.gif\" alt=\"\" />";
$out['ic_arrow_down'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-down.gif\" alt=\"\" />";
$out['ic_arrow_left'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-left.gif\" alt=\"\" />";
$out['ic_arrow_right'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-right.gif\" alt=\"\" />";
$out['ic_arrow_unread'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-unread.gif\" alt=\"\" />";
$out['ic_arrow_follow'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/arrow-follow.gif\" alt=\"\" />";

$out['ic_gallery'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-gallery.gif\" alt=\"\" />";
$out['ic_folder'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-folder.gif\" alt=\"\" />";

$out['ic_pastethumb'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pastethumb.gif\" alt=\"" . $L['pfs_insertasthumbnail'] . "\" />";
$out['ic_pastefile'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pastefile.gif\" alt=\"" . $L['pfs_insertaslink'] . "\" />";
$out['ic_pasteimage'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pasteimage.gif\" alt=\"" . $L['pfs_insertasimage'] . "\" />";

$out['ic_pastevideo'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pastevideo.gif\" alt=\"" . $L['pfs_insertasvideo'] . "\" />";

$out['ic_comment'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-comment.gif\" alt=\"\" />";

$out['ic_posts_moved'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/posts_moved.gif\" alt=\"\" />";

$out['ic_gallery_prev'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/gallery_prev.png\" alt=\"\" />";
$out['ic_gallery_next'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/gallery_next.png\" alt=\"\" />";
$out['ic_gallery_back'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/gallery_back.png\" alt=\"\" />";
$out['ic_gallery_zoom'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/gallery_zoom.png\" alt=\"Zoom\" />";

$out['ic_pm_new'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pm-new.gif\" alt=\"\" />";
$out['ic_pm'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pm.gif\" alt=\"\" />";
$out['ic_pm_trashcan'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pm-trashcan.gif\" alt=\"" . $L['Delete'] . "\" />";
$out['ic_pm_reply'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pm-reply.gif\" alt=\"" . $L['pm_replyto'] . "\" />";
$out['ic_pm_archive'] = "<img src=\"skins/" . $usr['skin'] . "/img/system/icon-pm-archive.gif\" alt=\"" . $L['pm_putinarchives'] . "\" />";

$out['ic_alert_error'] = "<img src=\"system/img/alerts/error.png\" alt=\"\" />";
$out['ic_alert_info'] = "<img src=\"system/img/alerts/info.png\" alt=\"\" />";
$out['ic_alert_warning'] = "<img src=\"system/img/alerts/warning.png\" alt=\"\" />";
$out['ic_alert_success'] = "<img src=\"system/img/alerts/success.png\" alt=\"\" />";
$out['ic_close'] = "<img src=\"system/img/alerts/close.png\" alt=\"\" />";

$sed_yesno[1] = $L['Yes'];
$sed_yesno[0] = $L['No'];

$sed_img_up = $out['ic_arrow_up'];
$sed_img_down = $out['ic_arrow_down'];
$sed_img_left = $out['ic_arrow_left'];
$sed_img_right = $out['ic_arrow_right'];

/* ======== Skin ======== */

$usr['skin_raw'] = $usr['skin'];

if (@file_exists(SED_ROOT . '/skins/' . $usr['skin'] . '.' . $usr['lang'] . '/header.tpl')) {
	$usr['skin'] = $usr['skin'] . '.' . $usr['lang'];
}

$mskin = SED_ROOT . '/skins/' . $usr['skin'] . '/header.tpl';

if (!file_exists($mskin)) {
	$out['notices'] .= $L['com_skinfail'] . '<br />';
	$usr['skin'] = $cfg['defaultskin'];
	$mskin = SED_ROOT . '/skins/' . $usr['skin'] . '/header.tpl';

	if (!file_exists($mskin)) {
		sed_diefatal('Default skin not found.');
	}
}

$skin = $usr['skin'];

$usr['skin_lang'] = defined('SED_ADMIN') && !empty($cfg['adminskin'])
	? SED_ROOT . '/system/adminskin/' . $cfg['adminskin'] . '/' . $cfg['adminskin'] . '.' . $usr['lang'] . '.lang.php'
	: SED_ROOT . '/skins/' . $usr['skin'] . '/' . $usr['skin_raw'] . '.' . $usr['lang'] . '.lang.php';

if (@file_exists($usr['skin_lang'])) {
	require($usr['skin_lang']);
}

$usr['skin_file'] = defined('SED_ADMIN') && !empty($cfg['adminskin'])
	? SED_ROOT . '/system/adminskin/' . $cfg['adminskin'] . '/' . $cfg['adminskin'] . '.php'
	: SED_ROOT . '/skins/' . $usr['skin'] . '/' . $usr['skin'] . '.php';

require($usr['skin_file']);

/* ======== Basic statistics ======== */

if (!$cfg['disablehitstats']) {
	sed_stat_inc('totalpages');
	$hits_today = sed_stat_get($sys['day']);

	if ($hits_today > 0) {
		sed_stat_inc($sys['day']);
	} else {
		sed_stat_create($sys['day']);
	}

	$sys['referer'] = isset($_SERVER['HTTP_REFERER']) ? mb_substr(mb_strtolower($_SERVER['HTTP_REFERER']), 0, 255) : '';
	$sys['httphost'] = mb_strtolower($_SERVER['HTTP_HOST']); // New Sed175

	if (
		!empty($sys['referer'])
		&& !(
			(!empty($cfg['mainurl']) && mb_stripos($sys['referer'], $cfg['mainurl']) !== FALSE)
			|| (!empty($cfg['hostip']) && mb_stripos($sys['referer'], $cfg['hostip']) !== FALSE)
			|| (!empty($sys['httphost']) && mb_stripos($sys['referer'], $sys['httphost']) !== FALSE)
			|| (!empty($cfg['mainurl']) && mb_stripos($sys['referer'], str_ireplace('//www.', '//', $cfg['mainurl'])) !== FALSE)
			|| (!empty($cfg['mainurl']) && mb_stripos(str_ireplace('//www.', '//', $sys['referer']), $cfg['mainurl']) !== FALSE)
		)
	) {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_referers WHERE ref_url = '" . sed_sql_prep($sys['referer']) . "'");
		$count = sed_sql_result($sql, 0, "COUNT(*)");

		if ($count > 0) {
			$sql = sed_sql_query("UPDATE $db_referers SET ref_count=ref_count+1,
				ref_date='" . $sys['now_offset'] . "'
				WHERE ref_url='" . sed_sql_prep($sys['referer']) . "'");
		} else {
			$sql = sed_sql_query("INSERT INTO $db_referers
				(ref_url,
				ref_count,
				ref_date)
				VALUES
				('" . sed_sql_prep($sys['referer']) . "',
				'1',
				" . (int)$sys['now_offset'] . ")");
		}
	}
}

/* ======== Categories ======== */

if ((!isset($sed_cat) || !$sed_cat) && !$cfg['disable_page']) {
	$sed_cat = sed_load_structure();
	sed_cache_store('sed_cat', $sed_cat, 3600);
}

/* ======== Forums ======== */

if ((!isset($sed_forums_str) || !$sed_forums_str) && !$cfg['disable_forums']) {
	$sed_forums_str = sed_load_forum_structure();
	sed_cache_store('sed_forums_str', $sed_forums_str, 3600);
}

/* ======== Directories ======== */

$dic_type = array(1 => 'select', 2 => 'radio', 3 => 'checkbox',  4 => 'textinput', 5 => 'textarea', 6 => 'multipleselect');
$dic_var_type = array('varchar' => 'TXT', 'text' => 'HTM', 'int' => 'INT', 'tinyint' => 'INT', 'boolean' => 'BOL');

if (!isset($sed_dic) && (sed_stat_get("version") >= 177)) {
	// Load directories
	$sql = sed_sql_query("SELECT * FROM $db_dic");
	if (sed_sql_numrows($sql) > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			if ($row['dic_type'] == 3 && $row['dic_extra_type'] != 'boolean' || $row['dic_type'] == 6) {
				$vartype = 'ARR';
			} else {
				$vartype = (!empty($row['dic_extra_type'])) ? $dic_var_type[$row['dic_extra_type']] : 'TXT';
			}
			$dictype = !empty($row['dic_type']) ? $dic_type[$row['dic_type']] : $dic_type[4];
			$sed_dic[$row['dic_code']] = array(
				'id' => $row['dic_id'],
				'title' => $row['dic_title'],
				'code' => $row['dic_code'],
				'type' => $dictype,
				'vartype' => $vartype,
				'values' => $row['dic_values'],
				'parent' => $row['dic_parent'],
				'mera' => $row['dic_mera'],
				'form_title' => $row['dic_form_title'],
				'form_desc' => $row['dic_form_desc'],
				'form_size' => $row['dic_form_size'],
				'form_maxsize' => $row['dic_form_maxsize'],
				'form_cols' => $row['dic_form_cols'],
				'form_rows' => $row['dic_form_rows'],
				'form_wysiwyg' => $row['dic_form_wysiwyg'],
				'extra_location' => $row['dic_extra_location'],
				'extra_type' => $row['dic_extra_type'],
				'extra_size' => $row['dic_extra_size'],
				'extra_default'	=> $row['dic_extra_default'],
				'extra_allownull' => $row['dic_extra_allownull'],
				'terms' => array(),
				'term_default' => ''
			);
			$sed_dicid_arr[$row['dic_id']] = $row['dic_code'];
		}
		// Load terms
		$sql2 = sed_sql_query("SELECT * FROM $db_dic_items");
		if (sed_sql_numrows($sql2) > 0) {
			while ($row2 = sed_sql_fetchassoc($sql2)) {
				$term_code = ($row2['ditem_code'] != "") ? $row2['ditem_code'] : $row2['ditem_id'];
				$sed_dic[$sed_dicid_arr[$row2['ditem_dicid']]]['terms'][$term_code] = $row2['ditem_title'];
				if (!empty($row2['ditem_defval'])) $sed_dic[$sed_dicid_arr[$row2['ditem_dicid']]]['term_default'] = $term_code;
			}
		}
	}
	sed_cache_store('sed_dic', $sed_dic, 3600);
}

/* ======== Menus ======== */

if (!isset($sed_menu) && (sed_stat_get("version") > 177)) {
	$sql = sed_sql_query("SELECT * FROM $db_menu WHERE 1 ORDER BY menu_position ASC");
	while ($row = sed_sql_fetchassoc($sql)) {
		$menu_tree[$row['menu_pid']][$row['menu_id']] = $row;
		$menu_row[$row['menu_id']] = $row;
	}

	foreach ($menu_row as $k => $v) {
		$sed_menu[$k]['childrens'] = sed_menu_tree($menu_tree, $k);
		$sed_menu[$k]['childrensonlevel'] = sed_menu_tree($menu_tree, $k, 0, false, true);
		$sed_menu[$k]['parent'] = sed_menu_tree($menu_row, $k, 0, true);
	}

	sed_cache_store('sed_menu', $sed_menu, 3600);
}

/* ======== Smilies ======== */

if (!isset($sed_smilies)) {
	$sql = sed_sql_query("SELECT * FROM $db_smilies ORDER by smilie_order ASC, smilie_id ASC");
	if (sed_sql_numrows($sql) > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$sed_smilies[] = $row;
		}
	}
	sed_cache_store('sed_smilies', $sed_smilies, 3550);
}

/* ======== Local/GMT time ======== */

$usr['timetext'] = sed_build_timezone($usr['timezone']);
$usr['gmttime'] = @date($cfg['dateformat'], $sys['now_offset']) . ' GMT';


/* ======== Maintenance Mode ======== */  // New in 175

if ($cfg['maintenance'] && $usr['level'] < $cfg['maintenancelevel'] && !defined('SED_USERS')) {
	sed_diemaintenance();
}

/* ======== Global hook ======== */

$extp = sed_getextplugins('global');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}

/* ======== 301 Redirect to SEF URL's ======== */

if ($cfg['sefurls'] && $cfg['sefurls301']) {
	sed_sefurlredirect();
}
