<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.modules.inc.php
Version=185
Updated=2026-feb-14
Type=Core.admin
Author=Seditio Team
Description=Module management
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

global $db_core, $db_plugins, $db_config, $sed_modules;

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=modules")] = $L['adm_modules'];

$admintitle = isset($L['adm_modules']) ? $L['adm_modules'] : 'Modules';

$a = sed_import('a', 'G', 'ALP', 24);
$b = sed_import('b', 'G', 'ALP', 24);
$mod_code = sed_import('mod', 'G', 'ALP', 24);

$mskin = sed_skinfile('admin.modules', false, true);
if (empty($mskin) || !file_exists($mskin)) {
	sed_die();
}
$t = new XTemplate($mskin);

$status_mod = array();
$status_mod[0] = '<span style="color:#5882AC; font-weight:bold;">' . $L['adm_paused'] . '</span>';
$status_mod[1] = '<span style="color:#739E48; font-weight:bold;">' . $L['adm_running'] . '</span>';
$status_mod[3] = '<span style="color:#AC5866; font-weight:bold;">' . (isset($L['adm_notinstalled']) ? $L['adm_notinstalled'] : 'Not installed') . '</span>';

// ---------- Details (a=details & mod=code)
if ($a == 'details' && !empty($mod_code)) {
	$setup_file = SED_ROOT . '/modules/' . $mod_code . '/' . $mod_code . '.setup.php';
	if (!file_exists($setup_file)) {
		sed_redirect(sed_url("message", "msg=909", "", true));
		exit;
	}

	$info = sed_infoget($setup_file, 'SED_MODULE');
	if (!empty($info['Error'])) {
		sed_redirect(sed_url("message", "msg=909", "", true));
		exit;
	}
	$mod_name = isset($info['Name']) ? $info['Name'] : $mod_code;

	$urlpaths[sed_url("admin", "m=modules&a=details&mod=" . $mod_code)] = $mod_name . ' (' . $mod_code . ')';
	$admintitle = $mod_name . ' (' . $mod_code . ')';

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_config WHERE config_owner='module' AND config_cat='" . sed_sql_prep($mod_code) . "'");
	$totalconfig = (int) sed_sql_result($sql, 0, "COUNT(*)");

	if ($totalconfig > 0) {
		$t->assign("MODULE_DETAILS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=module&p=" . $mod_code));
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_CONFIG");
	}

	$auth_guests = isset($info['Auth_guests']) && $info['Auth_guests'] !== '' ? sed_auth_getvalue($info['Auth_guests']) : sed_auth_getvalue('R');
	$lock_guests = isset($info['Lock_guests']) && $info['Lock_guests'] !== '' ? sed_auth_getvalue($info['Lock_guests']) : sed_auth_getvalue('W12345A');
	$auth_members = isset($info['Auth_members']) && $info['Auth_members'] !== '' ? sed_auth_getvalue($info['Auth_members']) : sed_auth_getvalue('RW');
	$lock_members = isset($info['Lock_members']) && $info['Lock_members'] !== '' ? sed_auth_getvalue($info['Lock_members']) : sed_auth_getvalue('12345A');

	$module_help = '';
	$help_key = 'adm_help_config_' . $mod_code;
	if (isset($L[$help_key]) && $L[$help_key] !== '') {
		$module_help = $L[$help_key];
	}

	// Plugins that depend on this module (from pl_dependencies or from setup Requires_modules)
	$dependent_plugins_str = '';
	$dependent_codes = array();
	$sql_dep = sed_sql_query("SELECT pl_code, pl_title, pl_dependencies FROM $db_plugins WHERE pl_module=0");
	while ($dep_row = sed_sql_fetchassoc($sql_dep)) {
		$depends = false;
		$title = !empty($dep_row['pl_title']) ? $dep_row['pl_title'] : $dep_row['pl_code'];
		$json = array_key_exists('pl_dependencies', $dep_row) ? $dep_row['pl_dependencies'] : null;
		if ($json !== null && $json !== '') {
			$deps = json_decode((string) $json, true);
			if (is_array($deps) && isset($deps['requires']) && is_array($deps['requires']) && in_array($mod_code, $deps['requires'])) {
				$depends = true;
			}
		}
		if (!$depends) {
			// Fallback: plugin may have been installed before pl_dependencies was filled — check setup file
			$plug_setup = SED_ROOT . '/plugins/' . $dep_row['pl_code'] . '/' . $dep_row['pl_code'] . '.setup.php';
			if (file_exists($plug_setup)) {
				$plug_info = sed_infoget($plug_setup, 'SED_EXTPLUGIN');
				if (empty($plug_info['Error']) && !empty($plug_info['Requires_modules'])) {
					$req_mods = array_map('trim', array_filter(explode(',', $plug_info['Requires_modules'])));
					if (in_array($mod_code, $req_mods)) {
						$depends = true;
						$title = !empty($plug_info['Name']) ? $plug_info['Name'] : $dep_row['pl_code'];
					}
				}
			}
		}
		if ($depends) {
			$dependent_codes[$dep_row['pl_code']] = $title;
		}
	}
	if (!empty($dependent_codes)) {
		$links = array();
		foreach ($dependent_codes as $pc => $title) {
			$links[] = sed_link(sed_url("admin", "m=plug&a=details&pl=" . $pc), $title);
		}
		$dependent_plugins_str = implode(', ', $links);
	}

	// Requires: build clickable links with titles (L['core_*'] or ct_title or code), like plugins
	$module_requires_str = '';
	$req_codes = isset($info['Requires']) && $info['Requires'] !== ''
		? array_map('trim', array_filter(explode(',', $info['Requires'])))
		: array();
	if (!empty($req_codes)) {
		$req_links = array();
		foreach ($req_codes as $rc) {
			$core_key = 'core_' . $rc;
			if (isset($L[$core_key]) && $L[$core_key] !== '') {
				$title = $L[$core_key];
			} elseif (!empty($sed_modules[$rc]['ct_title'])) {
				$title = $sed_modules[$rc]['ct_title'];
			} else {
				// Module not active: get title from DB (e.g. required module is paused or not yet installed)
				$sql_ct = sed_sql_query("SELECT ct_title FROM $db_core WHERE ct_code='" . sed_sql_prep($rc) . "' LIMIT 1");
				$ct_row = sed_sql_fetchassoc($sql_ct);
				$title = ($ct_row && !empty($ct_row['ct_title'])) ? $ct_row['ct_title'] : $rc;
			}
			$req_links[] = sed_link(sed_url("admin", "m=modules&a=details&mod=" . $rc), $title);
		}
		$module_requires_str = implode(', ', $req_links);
	}

	$t->assign(array(
		"MODULE_DETAILS_NAME" => $mod_name,
		"MODULE_DETAILS_CODE" => isset($info['Code']) ? $info['Code'] : $mod_code,
		"MODULE_DETAILS_DESC" => isset($info['Description']) ? $info['Description'] : '',
		"MODULE_DETAILS_VERSION" => isset($info['Version']) ? $info['Version'] : '',
		"MODULE_DETAILS_DATE" => isset($info['Date']) ? $info['Date'] : '',
		"MODULE_DETAILS_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=" . $mod_code . "&io=a"),
		"MODULE_DETAILS_DEFAUTH_GUESTS" => sed_build_admrights($auth_guests),
		"MODULE_DETAILS_DEFLOCK_GUESTS" => sed_build_admrights($lock_guests),
		"MODULE_DETAILS_DEFAUTH_MEMBERS" => sed_build_admrights($auth_members),
		"MODULE_DETAILS_DEFLOCK_MEMBERS" => sed_build_admrights($lock_members),
		"MODULE_DETAILS_AUTHOR" => isset($info['Author']) ? $info['Author'] : '',
		"MODULE_DETAILS_COPYRIGHT" => isset($info['Copyright']) ? $info['Copyright'] : '',
		"MODULE_DETAILS_NOTES" => isset($info['Notes']) ? $info['Notes'] : '',
		"MODULE_DETAILS_REQUIRES" => $module_requires_str,
		"MODULE_DETAILS_DEPENDENT_PLUGINS" => $dependent_plugins_str,
		"MODULE_DETAILS_HELP" => $module_help
	));

	if (!empty($dependent_plugins_str)) {
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_DEPENDENT_PLUGINS");
	}
	if (!empty($module_help)) {
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_HELP");
	}

	// Options: installed or not
	$sql_core = sed_sql_query("SELECT ct_state, ct_lock FROM $db_core WHERE ct_code='" . sed_sql_prep($mod_code) . "' LIMIT 1");
	$core_row = sed_sql_fetchassoc($sql_core);
	$is_installed = is_array($core_row) && isset($core_row['ct_state']);
	$ct_state = $is_installed ? (int) $core_row['ct_state'] : -1;
	$ct_lock = $is_installed && isset($core_row['ct_lock']) ? (int) $core_row['ct_lock'] : 0;

	if ($ct_lock === 1) {
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_LOCKED");
	}

	// Options block: hide entirely when module is installed and locked
	if (!$is_installed || $ct_lock === 0) {
		$opt_box = "ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_OPT_BOX";
		if (!$is_installed) {
			$t->assign("MODULE_DETAILS_INSTALL_URL", sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=install&" . sed_xg()));
			$t->parse($opt_box . ".MODULE_DETAILS_OPT_INSTALL");
		} else {
			$t->assign("MODULE_DETAILS_UNINSTALL_URL", sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=uninstall&" . sed_xg()));
			$t->parse($opt_box . ".MODULE_DETAILS_OPT_UNINSTALL");
			if ($ct_state == 1) {
				$t->assign("MODULE_DETAILS_PAUSE_URL", sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=pause&" . sed_xg()));
				$t->parse($opt_box . ".MODULE_DETAILS_OPT_PAUSE");
			} else {
				$t->assign("MODULE_DETAILS_UNPAUSE_URL", sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=unpause&" . sed_xg()));
				$t->parse($opt_box . ".MODULE_DETAILS_OPT_UNPAUSE");
			}
		}
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_DETAILS_OPT_BOX");
	}

	// Parts: from filesystem (like plugins) so uninstalled modules still show their parts with "Not installed" status
	$module_dir = SED_ROOT . '/modules/' . $mod_code . '/';
	$parts_from_fs = array();
	if (is_dir($module_dir)) {
		$handle = @opendir($module_dir);
		if ($handle) {
			$skip = array($mod_code . '.setup.php', $mod_code . '.install.php', $mod_code . '.uninstall.php', $mod_code . '.urls.php');
			while (($f = readdir($handle)) !== false) {
				if ($f === '.' || $f === '..') continue;
				if (mb_strtolower(mb_substr($f, -4)) !== '.php') continue;
				if (in_array($f, $skip)) continue;
				$parts_from_fs[] = $f;
			}
			closedir($handle);
		}
	}
	if (empty($parts_from_fs)) {
		$parts_from_fs[] = $mod_code . '.php';
	}
	sort($parts_from_fs);
	$main_file = $mod_code . '.php';
	$parts_ordered = array();
	if (in_array($main_file, $parts_from_fs)) {
		$parts_ordered[] = $main_file;
		$parts_from_fs = array_values(array_diff($parts_from_fs, array($main_file)));
	}
	$parts_ordered = array_merge($parts_ordered, $parts_from_fs);

	foreach ($parts_ordered as $x) {
		$part_name = mb_substr($x, 0, -4);
		$pl_part = ($part_name === $mod_code) ? 'main' : $part_name;
		$part_file = $module_dir . $x;
		$content = file_exists($part_file) ? file_get_contents($part_file) : '';
		$hooks = array();
		if ($content !== '' && preg_match_all("/sed_getextplugins\s*\(\s*([^)]+)\s*\)/", $content, $m) && !empty($m[1])) {
			foreach ($m[1] as $arg) {
				$hook = trim(trim($arg), "'\"");
				if ($hook !== '') {
					$hooks[] = $hook;
				}
			}
			$hooks = array_unique($hooks);
		}
		$hooks_display = !empty($hooks) ? implode('<br />', $hooks) : 'module';
		$sql_pl = sed_sql_query("SELECT pl_id, pl_part, pl_file, pl_hook, pl_active, pl_lock FROM $db_plugins WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_part='" . sed_sql_prep($pl_part) . "' AND pl_module=1 LIMIT 1");
		$row_pl = sed_sql_fetchassoc($sql_pl);
		if ($row_pl) {
			$part_status = $status_mod[(int) $row_pl['pl_active']];
		if ((int)$row_pl['pl_lock'] === 1) {
			$pl_action = isset($L['adm_lockpart']) ? $L['adm_lockpart'] : 'Lock part';
		} elseif ($pl_part === 'main') {
			$pl_action = '-';
		} else {
			$pl_action = ($row_pl['pl_active'] == 1)
				? sed_link(sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=pausepart&part=" . $row_pl['pl_id'] . "&" . sed_xg()), isset($L['adm_opt_pause']) ? $L['adm_opt_pause'] : 'Pause', array('class' => 'btn btn-adm'))
				: sed_link(sed_url("admin", "m=modules&a=edit&mod=" . $mod_code . "&b=unpausepart&part=" . $row_pl['pl_id'] . "&" . sed_xg()), isset($L['adm_opt_unpause']) ? $L['adm_opt_unpause'] : 'Un-pause', array('class' => 'btn btn-adm'));
		}
			$t->assign(array(
				"MODULE_PARTS_NUMBER" => $row_pl['pl_id'],
				"MODULE_PARTS_PART" => $row_pl['pl_part'],
				"MODULE_PARTS_FILE" => $row_pl['pl_file'],
				"MODULE_PARTS_HOOKS" => $hooks_display,
				"MODULE_PARTS_STATUS" => $part_status,
				"MODULE_PARTS_ACTION" => $pl_action
			));
		} else {
			$t->assign(array(
				"MODULE_PARTS_NUMBER" => '-',
				"MODULE_PARTS_PART" => $pl_part,
				"MODULE_PARTS_FILE" => $part_name,
				"MODULE_PARTS_HOOKS" => $hooks_display,
				"MODULE_PARTS_STATUS" => $status_mod[3],
				"MODULE_PARTS_ACTION" => '-'
			));
		}
		$t->parse("ADMIN_MODULES.MODULE_DETAILS.MODULE_PARTS_ROW");
	}
	$t->parse("ADMIN_MODULES.MODULE_DETAILS");

	$t->assign("ADMIN_MODULES_TITLE", $admintitle);
	$t->parse("ADMIN_MODULES");
	$adminmain .= $t->text("ADMIN_MODULES");
} elseif ($a == 'edit' && !empty($mod_code) && !empty($b)) {
	// ---------- Actions (a=edit & mod=code & b=...)
	sed_check_xg();

	$setup_file = SED_ROOT . '/modules/' . $mod_code . '/' . $mod_code . '.setup.php';
	if (!file_exists($setup_file)) {
		sed_redirect(sed_url("message", "msg=909", "", true));
		exit;
	}

	switch ($b) {
		case 'install':
			$res = sed_module_install($mod_code);
			$t->assign(array(
				"MODULE_ACTION_INFO" => $res,
				"MODULE_ACTION_URL" => sed_url("admin", "m=modules&a=details&mod=" . $mod_code)
			));
			$t->parse("ADMIN_MODULES.MODULE_ACTION");
			$t->assign("ADMIN_MODULES_TITLE", $admintitle);
			$t->parse("ADMIN_MODULES");
			$adminmain .= $t->text("ADMIN_MODULES");
			break;

		case 'uninstall':
			$res = sed_module_uninstall($mod_code);
			$t->assign(array(
				"MODULE_ACTION_INFO" => $res,
				"MODULE_ACTION_URL" => sed_url("admin", "m=modules")
			));
			$t->parse("ADMIN_MODULES.MODULE_ACTION");
			$t->assign("ADMIN_MODULES_TITLE", $admintitle);
			$t->parse("ADMIN_MODULES");
			$adminmain .= $t->text("ADMIN_MODULES");
			break;

		case 'pause':
			sed_module_pause($mod_code, 0);
			sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_module=1");
			sed_cache_clearall();
			sed_redirect(sed_url("admin", "m=modules&a=details&mod=" . $mod_code, "", true));
			exit;

		case 'unpause':
			sed_module_pause($mod_code, 1);
			sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_module=1");
			sed_cache_clearall();
			sed_redirect(sed_url("admin", "m=modules&a=details&mod=" . $mod_code, "", true));
			exit;

		case 'pausepart':
			$part = sed_import('part', 'G', 'INT');
			$sql_check = sed_sql_query("SELECT pl_lock FROM $db_plugins WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_id='" . (int)$part . "' AND pl_module=1 LIMIT 1");
			$row_check = sed_sql_fetchassoc($sql_check);
			if (!$row_check || (int)$row_check['pl_lock'] === 1) {
				sed_redirect(sed_url("admin", "m=modules&a=details&mod=" . $mod_code . "&msg=locked", "", true));
				exit;
			}
			sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_id='" . (int)$part . "' AND pl_module=1");
			sed_cache_clearall();
			sed_redirect(sed_url("admin", "m=modules&a=details&mod=" . $mod_code, "", true));
			exit;

		case 'unpausepart':
			$part = sed_import('part', 'G', 'INT');
			sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='" . sed_sql_prep($mod_code) . "' AND pl_id='" . (int)$part . "' AND pl_module=1");
			sed_cache_clearall();
			sed_redirect(sed_url("admin", "m=modules&a=details&mod=" . $mod_code, "", true));
			exit;

		default:
			sed_die();
	}
} else {
	// ---------- List (default)
	$sql_installed = sed_sql_query("SELECT * FROM $db_core WHERE ct_path LIKE 'modules/%' ORDER BY ct_code ASC");
$installed = array();
while ($row = sed_sql_fetchassoc($sql_installed)) {
	$installed[$row['ct_code']] = $row;
}

$modules_dir = SED_ROOT . '/modules';
$available_codes = array();
if (is_dir($modules_dir)) {
	$handle = opendir($modules_dir);
	while ($f = readdir($handle)) {
		if ($f != '.' && $f != '..' && is_dir($modules_dir . '/' . $f)) {
			$setup = $modules_dir . '/' . $f . '/' . $f . '.setup.php';
			if (file_exists($setup)) {
				$available_codes[] = $f;
			}
		}
	}
	closedir($handle);
}
$all_codes = array_unique(array_merge(array_keys($installed), $available_codes));
sort($all_codes);

$sql_cfg = sed_sql_query("SELECT config_cat, COUNT(*) FROM $db_config WHERE config_owner='module' GROUP BY config_cat");
$cfgentries = array();
while ($row = sed_sql_fetchassoc($sql_cfg)) {
	$cfgentries[$row['config_cat']] = (int) $row['COUNT(*)'];
}

$t->assign("MODULES_LISTING_COUNT", count($all_codes));

foreach ($all_codes as $code) {
	$setup_file = SED_ROOT . '/modules/' . $code . '/' . $code . '.setup.php';
	$info = file_exists($setup_file) ? sed_infoget($setup_file, 'SED_MODULE') : array();
	$name = isset($info['Name']) ? $info['Name'] : $code;
	$version = isset($info['Version']) ? $info['Version'] : '?';

	$row = isset($installed[$code]) ? $installed[$code] : null;
	if ($row) {
		$version = $row['ct_version'];
		if (file_exists($setup_file)) {
			$file_info = sed_infoget($setup_file, 'SED_MODULE');
			if (isset($file_info['Version']) && $file_info['Version'] != $row['ct_version']) {
				$version .= ' <span class="no">(file: ' . $file_info['Version'] . ')</span>';
			}
		}
		$status = $status_mod[(int) $row['ct_state']];
	} else {
		$status = '<span style="color:#AC5866; font-weight:bold;">' . $L['adm_notinstalled'] . '</span>';
	}

	$t->assign(array(
		"MODULE_LIST_NAME" => $name,
		"MODULE_LIST_DETAILS_URL" => sed_url("admin", "m=modules&a=details&mod=" . $code),
		"MODULE_LIST_CODE" => $code,
		"MODULE_LIST_VERSION" => $version,
		"MODULE_LIST_STATUS" => $status,
		"MODULE_LIST_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=" . $code . "&io=a")
	));

	if (isset($cfgentries[$code]) && $cfgentries[$code] > 0) {
		$t->assign("MODULE_LIST_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=module&p=" . $code));
		$t->parse("ADMIN_MODULES.MODULES_LISTING.MODULE_LIST.MODULE_LIST_CONFIG");
	}

	if ($row) {
		$open_url = '';
		if ((int)$row['ct_admin']) {
			$menu_file = SED_ROOT . '/modules/' . $code . '/admin/' . $code . '.admin.menu.php';
			if (file_exists($menu_file)) {
				$menu_def = @include($menu_file);
				if (is_array($menu_def) && !empty($menu_def['adminlink'])) {
					$open_url = $menu_def['adminlink'];
				}
			}
			if ($open_url === '') {
				$open_url = sed_url('admin', 'm=' . $code);
			}
		} else {
			$open_url = sed_url($row['ct_code'], '');
		}
		if ($code !== 'view' && $open_url !== '') {
			$t->assign("MODULE_LIST_OPEN_URL", $open_url);
			$t->parse("ADMIN_MODULES.MODULES_LISTING.MODULE_LIST.MODULE_LIST_OPEN");
		}
	}

	$t->parse("ADMIN_MODULES.MODULES_LISTING.MODULE_LIST");
}

	$t->parse("ADMIN_MODULES.MODULES_LISTING");
	$t->assign("ADMIN_MODULES_TITLE", $admintitle);
	$t->parse("ADMIN_MODULES");
	$adminmain .= $t->text("ADMIN_MODULES");
}
