<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.header.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=Admin header
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

sed_add_javascript('system/javascript/core.js', true);

/* === Hook === */
$extp = sed_getextplugins('header.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$out['logstatus'] = ($usr['id'] > 0) ? $L['hea_youareloggedas'] . ' ' . $usr['name'] : $L['hea_youarenotlogged'];
$out['userlist'] = (sed_auth('users', 'a', 'R')) ? sed_link(sed_url("users"), $L['hea_users']) : '';
$out['metas'] = sed_htmlmetas($out['subdesc'], $out['subkeywords']) . $moremetas;

sed_add_javascript($morejavascript);
$out['javascript'] = sed_javascript();

sed_add_css($morecss);
$out['css'] = sed_css();

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

if (sed_module_active('page') && sed_auth('page', 'any', 'A')) {
	$sqltmp2 = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state=1");
	$sys['pagesqueued'] = sed_sql_result($sqltmp2, 0, 'COUNT(*)');

	if ($sys['pagesqueued'] > 0) {
		$out['notices'] .= $L['hea_valqueues'];

		if ($sys['pagesqueued'] == 1) {
			$out['notices'] .= sed_link(sed_url("admin", "m=page"), "1 " . $L['Page']) . " ";
		} elseif ($sys['pagesqueued'] > 1) {
			$out['notices'] .= sed_link(sed_url("admin", "m=page"), $sys['pagesqueued'] . " " . $L['Pages']) . " ";
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
	"HEADER_CSS" => $out['css'],
	"HEADER_CANONICAL_URL" => $out['canonical_url'], // New in 175
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
	$out['adminpanel'] = (sed_auth('admin', 'any', 'R')) ? sed_link(sed_url("admin"), $L['hea_administration']) : '';
	$out['loginout_url'] = sed_url("users", "m=logout&" . sed_xg());
	$out['loginout'] = sed_link($out['loginout_url'], $L['hea_logout']);
	$out['profile'] = sed_link(sed_url("users", "m=profile"), $L['hea_profile']);

	$out['pms'] = (!sed_module_active('pm')) ? '' : sed_link(sed_url("pm"), $L['hea_private_messages']);
	$out['pfs'] = (!sed_module_active('pfs') || !sed_auth('pfs', 'a', 'R') || $sed_groups[$usr['maingrp']]['pfs_maxtotal'] == 0 || $sed_groups[$usr['maingrp']]['pfs_maxfile'] == 0) ? '' : sed_link(sed_url("pfs"), $L['hea_mypfs']);
	$out['pageadd'] = (sed_module_active('page') && sed_auth('page', 'any', 'W')) ? sed_link(sed_url("page", "m=add"), $L['hea_pageadd']) : "";

	if (sed_module_active('pm')) {
		if ($usr['newpm']) {
			$sqlpm = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=0");
			$usr['messages'] = sed_sql_result($sqlpm, 0, 'COUNT(*)');
		}
		$out['pmreminder'] = sed_link(sed_url("pm"), ($usr['messages'] > 0) ? $usr['messages'] . ' ' . $L['hea_privatemessages'] : $L['hea_noprivatemessages']);
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
		$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='core'" . $order_field);
		$config_menu = "<ul>";

		while ($row = sed_sql_fetchassoc($sql)) {
			$config_cat = $row['config_cat'];
			$code = "core_" . $config_cat;
			$config_menu .= "<li>";
			$config_menu_class = ($config_cat == $p) ? "current" : '';
			$config_menu .= sed_link(sed_url("admin", "m=config&n=edit&o=core&p=" . $config_cat), isset($L[$code]) ? $L[$code] : $config_cat, array('class' => $config_menu_class));
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

	// Other admin menus

	if (sed_auth('admin', 'a', 'A')) {
		$t->assign(array(
			"ADMIN_MENU_PLUGINS_URL" => sed_url('admin', "m=plug"),
			"ADMIN_MENU_PLUGINS_URL_CLASS" => ($m == 'plug') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.PLUGINS_MENU");

		// Modules manager menu item
		$t->assign(array(
			"ADMIN_MENU_MODULES_URL" => sed_url('admin', "m=modules"),
			"ADMIN_MENU_MODULES_URL_CLASS" => ($m == 'modules') ? 'current' : ''
		));
		$t->parse("HEADER.ADMIN_MENU.MODULES_MENU");

		// Module menu items: collect from modules, sort by order, then output (title, order, sections with optional auth/param)
		$mod_menu_items = array();
		$sql_mod_menu = sed_sql_query("SELECT ct_code, ct_title FROM $db_core WHERE ct_admin=1 AND ct_state=1 AND ct_path LIKE 'modules/%' ORDER BY ct_title ASC");
		while ($mod_menu_row = sed_sql_fetchassoc($sql_mod_menu)) {
			$mod_code = $mod_menu_row['ct_code'];
			$menu_file = SED_ROOT . '/modules/' . $mod_code . '/admin/' . $mod_code . '.admin.menu.php';
			if (!is_file($menu_file)) {
				continue;
			}
			$menu_def = include($menu_file);
			if ($menu_def === false || !is_array($menu_def) || empty($menu_def['title'])) {
				continue;
			}
			$mod_menu_items[] = array(
				'code'     => $mod_code,
				'title'   => $menu_def['title'],
				'order'   => isset($menu_def['order']) ? (int)$menu_def['order'] : 50,
				'ct_title'=> $mod_menu_row['ct_title'],
				'sections'=> isset($menu_def['sections']) && is_array($menu_def['sections']) ? $menu_def['sections'] : array()
			);
		}
		usort($mod_menu_items, function ($a, $b) {
			if ($a['order'] !== $b['order']) {
				return $a['order'] - $b['order'];
			}
			return strcmp($a['ct_title'], $b['ct_title']);
		});
		foreach ($mod_menu_items as $mod_item) {
			$mod_code = $mod_item['code'];
			$mod_menu_title = isset($L[$mod_item['title']]) ? $L[$mod_item['title']] : $mod_item['title'];
			$mod_menu_url = sed_url('admin', "m=" . $mod_code);
			$is_current = ($m == $mod_code);
			$sections = $mod_item['sections'];
			$has_submenu = (count($sections) > 0);
			$t->assign(array(
				"ADMIN_MODULE_MENU_URL" => $mod_menu_url,
				"ADMIN_MODULE_MENU_TITLE" => sed_cc($mod_menu_title),
				"ADMIN_MODULE_MENU_MOD_CODE" => $mod_code,
				"ADMIN_MODULE_MENU_URL_CLASS" => $is_current ? 'current' : '',
				"ADMIN_MODULE_MENU_SUBMENU_CLASS" => $has_submenu ? 'yes-submenu' : 'no-submenu'
			));
			if ($has_submenu) {
				$t->assign("ADMIN_MODULE_MENU_SUB_STYLE", $is_current ? 'style="display: block;"' : '');
				foreach ($sections as $sk => $sval) {
					$slabel = is_array($sval) ? (isset($sval['label']) ? $sval['label'] : '') : $sval;
					$sparam = (is_array($sval) && isset($sval['param'])) ? $sval['param'] : 's';
					$sauth = is_array($sval) && isset($sval['auth']) && is_array($sval['auth']) ? $sval['auth'] : null;
					if ($sauth !== null && !call_user_func_array('sed_auth', $sauth)) {
						continue;
					}
					// Custom URL: use $sval['url'] when set; otherwise auto-build from param+key
					if (is_array($sval) && isset($sval['url']) && $sval['url'] !== '') {
						$surl = $sval['url'];
					} else {
						$surl = $sk === '' ? $mod_menu_url : sed_url('admin', "m=" . $mod_code . "&" . $sparam . "=" . $sk);
					}
					// Highlight current: use $sval['match'] when set; otherwise legacy param+key logic
					if (is_array($sval) && isset($sval['match']) && is_array($sval['match'])) {
						$sclass = 'current';
						foreach ($sval['match'] as $mk => $mv) {
							$gv = (isset($GLOBALS[$mk])) ? (string)$GLOBALS[$mk] : '';
							if ($gv !== (string)$mv) {
								$sclass = '';
								break;
							}
						}
					} else {
						$sclass = ($m == $mod_code && $sparam === 'mn' && (string)$mn === (string)$sk) ? 'current' : (($m == $mod_code && $sparam === 's' && (string)$s === (string)$sk) ? 'current' : '');
					}
					$stext = isset($L[$slabel]) ? $L[$slabel] : $slabel;
					$t->assign(array(
						"ADMIN_MODULE_SUB_URL" => $surl,
						"ADMIN_MODULE_SUB_TITLE" => sed_cc($stext),
						"ADMIN_MODULE_SUB_CLASS" => $sclass
					));
					$t->parse("HEADER.ADMIN_MENU.MODULE_MENU_ITEM.MODULE_MENU_SUB.MODULE_MENU_SUBITEM");
				}
				$t->parse("HEADER.ADMIN_MENU.MODULE_MENU_ITEM.MODULE_MENU_SUB");
			}
			$t->parse("HEADER.ADMIN_MENU.MODULE_MENU_ITEM");
		}
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
