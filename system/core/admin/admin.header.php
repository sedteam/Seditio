<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/admin/admin.header.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Admin header
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* === Hook === */
$extp = sed_getextplugins('header.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$out['logstatus'] = ($usr['id'] > 0) ? $L['hea_youareloggedas'] . ' ' . $usr['name'] : $L['hea_youarenotlogged'];
$out['userlist'] = (sed_auth('users', 'a', 'R')) ? "<a href=\"" . sed_url("users") . "\">" . $L['hea_users'] . "</a>" : '';
$out['metas'] = sed_htmlmetas($out['subdesc'], $out['subkeywords']) . $moremetas;
$out['compopup'] = sed_javascript($morejavascript);

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle']);
$out['subtitle'] = (empty($out['subtitle'])) ? sed_title('defaulttitle', $title_tags, $title_data) : $out['subtitle'];
/**/

$out['currenturl'] = sed_getcurrenturl();
$out['canonical_url'] = empty($out['canonical_url']) ? str_replace('&', '&amp;', $sys['canonical_url']) : $out['canonical_url'];  // New in 175
$out['register_link'] = sed_url("users", "m=register");  // New in 175
$out['auth_link'] = sed_url("users", "m=auth");  // New in 175
$out['whosonline_link'] = sed_url("plug", "e=whosonline");  // New in 175

if (sed_auth('page', 'any', 'A')) {
	$sqltmp2 = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
	$sys['pagesqueued'] = sed_sql_result($sqltmp2, 0, 'COUNT(*)');

	if ($sys['pagesqueued'] > 0) {
		$out['notices'] .= $L['hea_valqueues'];

		if ($sys['pagesqueued'] == 1) {
			$out['notices'] .= "<a href=\"" . sed_url("admin", "m=page") . "\">" . "1 " . $L['Page'] . "</a> ";
		} elseif ($sys['pagesqueued'] > 1) {
			$out['notices'] .= "<a href=\"" . sed_url("admin", "m=page") . "\">" . $sys['pagesqueued'] . " " . $L['Pages'] . "</a> ";
		}
	}
}

sed_sendheaders();

/* === Hook === */
$extp = sed_getextplugins('header.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$mskin = sed_skinfile('admin.header', false, true);

$t = new XTemplate($mskin);

$t->assign(array(
	"HEADER_TITLE" => $out['subtitle'],
	"HEADER_METAS" => $out['metas'],
	"HEADER_DOCTYPE" => $cfg['doctype'],
	"HEADER_CSS" => $cfg['css'],
	"HEADER_CANONICAL_URL" => $out['canonical_url'], // New in 175
	"HEADER_COMPOPUP" => $out['compopup'],
	"HEADER_LOGSTATUS" => $out['logstatus'],
	"HEADER_WHOSONLINE" => $out['whosonline'],
	"HEADER_TOPLINE" => $cfg['topline'],
	"HEADER_ADMIN_URL" => sed_url('admin'),
	"HEADER_BANNER" => $cfg['banner'],
	"HEADER_GMTTIME" => $usr['gmttime'],
	"HEADER_USERLIST" => $out['userlist'],
	"HEADER_NOTICES" => $out['notices']
));

if ($usr['id'] > 0) {
	$out['adminpanel'] = (sed_auth('admin', 'any', 'R')) ? "<a href=\"" . sed_url("admin") . "\">" . $L['hea_administration'] . "</a>" : '';
	$out['loginout_url'] = sed_url("users", "m=logout&" . sed_xg());
	$out['loginout'] = "<a href=\"" . $out['loginout_url'] . "\">" . $L['hea_logout'] . "</a>";
	$out['profile'] = "<a href=\"" . sed_url("users", "m=profile") . "\">" . $L['hea_profile'] . "</a>";

	$out['pms'] = ($cfg['disable_pm']) ? '' : "<a href=\"" . sed_url("pm") . "\">" . $L['hea_private_messages'] . "</a>";
	$out['pfs'] = ($cfg['disable_pfs'] || !sed_auth('pfs', 'a', 'R') || $sed_groups[$usr['maingrp']]['pfs_maxtotal'] == 0 || 	$sed_groups[$usr['maingrp']]['pfs_maxfile'] == 0) ? '' : "<a href=\"" . sed_url("pfs") . "\">" . $L['hea_mypfs'] . "</a>";
	$out['pageadd'] = sed_auth('page', 'any', 'W') ? "<a href=\"" . sed_url("page", "m=add") . "\">" . $L['hea_pageadd'] . "</a>" : "";

	if (!$cfg['disable_pm']) {
		if ($usr['newpm']) {
			$sqlpm = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=0");
			$usr['messages'] = sed_sql_result($sqlpm, 0, 'COUNT(*)');
		}
		$out['pmreminder'] = "<a href=\"" . sed_url("pm") . "\">";
		$out['pmreminder'] .= ($usr['messages'] > 0) ? $usr['messages'] . ' ' . $L['hea_privatemessages'] : $L['hea_noprivatemessages'];
		$out['pmreminder'] .= "</a>";
	}

	if (!empty($out['notices'])) $t->parse("HEADER.USER.HEADER_NOTICES");

	$t->assign(array(
		"HEADER_USER_NAME" => $usr['name'],
		"HEADER_USER_ADMINPANEL" => $out['adminpanel'],
		"HEADER_USER_LOGINOUT" => $out['loginout'],
		"HEADER_USER_PROFILE" => $out['profile'],
		"HEADER_USER_PMS" => $out['pms'],
		"HEADER_USER_PFS" => $out['pfs'],
		"HEADER_USER_AVATAR" => sed_build_userimage($usr['profile']['user_avatar']),
		"HEADER_USER_PMREMINDER" => isset($out['pmreminder']) ? $out['pmreminder'] : '',
		"HEADER_USER_PAGEADD" => $out['pageadd'],
		"HEADER_USER_MESSAGES" => $usr['messages']
	));

	$t->parse("HEADER.HEADER_USER_MENU");
	$t->parse("HEADER.HEADER_ADMIN_USER");


	// Options menu

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');

	if (sed_auth('admin', 'a', 'A')) {
		$order_field = (sed_stat_get("version") >= 180) ? " ORDER BY config_id ASC" : "";
		$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='core'".$order_field);
		$config_menu = "<ul>";

		while ($row = sed_sql_fetchassoc($sql)) {
			$config_menu .= "<li>";
			$code = "core_" . $row['config_cat'];
			$config_menu_class = ($row['config_cat'] == $p) ? "current" : '';
			$config_menu .= "<a href=\"" . sed_url("admin", "m=config&n=edit&o=core&p=" . $row['config_cat']) . "\" class=\"" . $config_menu_class . "\">" . $L[$code] . "</a>";
			$config_menu .= "</li>";
		}

		$config_menu .= "</ul>";

		$t->assign(array(
			"ADMIN_MENU_CONFIG_URL" => sed_url('admin', "m=config"),
			"ADMIN_MENU_CONFIG_URL_CLASS" => ($m == "config") ? "current" : "",
			"ADMIN_MENU_CONFIG" => $config_menu
		));
		$t->parse("HEADER.ADMIN_MENU.CONFIG_MENU");
	}

	// Pages menu 

	$page_menu = "<ul>";
	$page_menu .= ($mn == 'queue') ? "<li>" . sed_linkif(sed_url("admin", "m=page&mn=queue"), $L['adm_valqueue'], sed_auth('admin', 'any', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=page&mn=queue"), $L['adm_valqueue'], sed_auth('admin', 'any', 'A')) . "</li>";
	$page_menu .= ($m == 'page' && $s == 'add') ? "<li>" . sed_linkif(sed_url("admin", "m=page&s=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=page&s=add"), $L['addnewentry'], sed_auth('page', 'any', 'A')) . "</li>";
	$page_menu .= ($m == 'page' && $s == 'manager') ? "<li>" . sed_linkif(sed_url("admin", "m=page&s=manager"), $L['adm_pagemanager'], sed_auth('page', 'any', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=page&s=manager"), $L['adm_pagemanager'], sed_auth('page', 'any', 'A')) . "</li>";

	if (sed_auth('admin', 'a', 'A')) {
		$page_menu .= ($mn == 'catorder') ? "<li>" . sed_linkif(sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder'], sed_auth('admin', 'a', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder'], sed_auth('admin', 'a', 'A')) . "</li>";
		$page_menu .= ($mn == 'structure') ? "<li>" . sed_linkif(sed_url("admin", "m=page&mn=structure"), $L['adm_structure'], sed_auth('admin', 'a', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=page&mn=structure"), $L['adm_structure'], sed_auth('admin', 'a', 'A')) . "</li>";
	}

	$page_menu .= "</ul>";

	$t->assign(array(
		"ADMIN_MENU_PAGE_URL" => sed_url('admin', "m=page"),
		"ADMIN_MENU_PAGE_URL_CLASS" => ($m == "page" || $m == "pageadd") ? "current" : "",
		"ADMIN_MENU_PAGE" => $page_menu
	));
	$t->parse("HEADER.ADMIN_MENU.PAGE_MENU");

	if (sed_auth('log', 'a', 'A')) {
		$t->assign(array(
			"ADMIN_MENU_LOG_URL" => sed_url('admin', "m=log"),
			"ADMIN_MENU_LOG_URL_CLASS" => ($m == 'log') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.LOG_MENU");
	}

	if (sed_auth('trash', 'a', 'A')) {
		$t->assign(array(
			"ADMIN_MENU_TRASHCAN_URL" => sed_url('admin', "m=trashcan"),
			"ADMIN_MENU_TRASHCAN_URL_CLASS" => ($m == 'trashcan') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.TRASHCAN_MENU");
	}

	if (sed_auth('manage', 'a', 'A')) {
		$t->assign(array(
			"ADMIN_MENU_MANAGE_URL" => sed_url('admin', "m=manage"),
			"ADMIN_MENU_MANAGE_URL_CLASS" => ($m == 'manage') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.MANAGE_MENU");
	}

	// Forums menu & other

	if (sed_auth('admin', 'a', 'A')) {
		$forums_menu = "<ul class=\"arrow_list\">";
		$forums_menu .= ($m == "forums" && empty($s)) ? "<li>" . sed_linkif(sed_url("admin", "m=forums"), $L['adm_forum_structure_cat'], sed_auth('admin', 'a', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=forums"), $L['adm_forum_structure_cat'], sed_auth('admin', 'a', 'A')) . "</li>";
		$forums_menu .= ($s == "structure") ? "<li>" . sed_linkif(sed_url("admin", "m=forums&s=structure"), $L['adm_forum_structure'], sed_auth('admin', 'a', 'A'), 'current') . "</li>" : "<li>" . sed_linkif(sed_url("admin", "m=forums&s=structure"), $L['adm_forum_structure'], sed_auth('admin', 'a', 'A')) . "</li>";
		$forums_menu .= "</ul>";

		$t->assign(array(
			"ADMIN_MENU_FORUMS_URL" => sed_url('admin', "m=forums"),
			"ADMIN_MENU_FORUMS_URL_CLASS" => ($m == "forums") ? "current" : "",
			"ADMIN_MENU_FORUMS" => $forums_menu
		));
		$t->parse("HEADER.ADMIN_MENU.FORUMS_MENU");

		$t->assign(array(
			"ADMIN_MENU_USERS_URL" => sed_url('admin', "m=users"),
			"ADMIN_MENU_USERS_URL_CLASS" => ($m == 'users') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.USERS_MENU");

		$t->assign(array(
			"ADMIN_MENU_PLUGINS_URL" => sed_url('admin', "m=plug"),
			"ADMIN_MENU_PLUGINS_URL_CLASS" => ($m == 'plug') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.PLUGINS_MENU");
	}

	$t->assign(array(
		"ADMIN_MENU_URL" => sed_url('admin'),
		"ADMIN_MENU_URL_CLASS" => (empty($m)) ? 'current' : ''
	));

	$t->parse("HEADER.ADMIN_MENU");
}

/* === Hook === */
$extp = sed_getextplugins('header.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("HEADER");
$t->out("HEADER");
