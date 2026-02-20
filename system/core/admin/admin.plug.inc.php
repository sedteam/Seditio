<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.plug.inc.php
Version=185
Updated=2026-feb-14
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=plug")] = $L['Plugins'];
$admintitle = $L['Plugins'];

$pl = sed_import('pl', 'G', 'ALP');
$part = sed_import('part', 'G', 'ALP');

$status[0] = '<span style="color:#5882AC; font-weight:bold;">' . $L['adm_paused'] . '</span>';
$status[1] = '<span style="color:#739E48; font-weight:bold;">' . $L['adm_running'] . '</span>';
$status[2] = '<span style="color:#A78731; font-weight:bold;">' . $L['adm_partrunning'] . '</span>';
$status[3] = '<span style="color:#AC5866; font-weight:bold;">' . $L['adm_notinstalled'] . '</span>';
$found_txt[0] = '<span style="color:#AC5866; font-weight:bold;">' . $L['adm_missing'] . '</span>';
$found_txt[1] = '<span style="color:#739E48; font-weight:bold;">' . $L['adm_present'] . '</span>';

unset($disp_errors);

$t = new XTemplate(sed_skinfile('admin.plug', false, true));

switch ($a) {
	/* =============== */
	case 'details':
		/* =============== */

		$extplugin_info = SED_ROOT . "/plugins/" . $pl . "/" . $pl . ".setup.php";

		if (file_exists($extplugin_info)) {
			$extplugin_info = SED_ROOT . "/plugins/" . $pl . "/" . $pl . ".setup.php";
			$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

			$urlpaths[sed_url("admin", "m=plug&a=details&pl=" . $pl)] = $info['Name'] . " ($pl)";
			$admintitle = $info['Name'] . " ($pl)";

			$handle = opendir(SED_ROOT . "/plugins/" . $pl);
			$setupfile = $pl . ".setup.php";
			while ($f = readdir($handle)) {
				if ($f != "." && $f != ".." && $f != $setupfile && mb_strtolower(mb_substr($f, mb_strrpos($f, '.') + 1, 4)) == 'php') {
					$parts[] = $f;
				}
			}
			closedir($handle);
			if (is_array($parts)) {
				sort($parts);
			}

			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_config WHERE config_owner='plug' AND config_cat='$pl'");
			$totalconfig = sed_sql_result($sql, 0, "COUNT(*)");

			$info['Auth_members'] = sed_auth_getvalue($info['Auth_members']);
			$info['Lock_members'] = sed_auth_getvalue($info['Lock_members']);
			$info['Auth_guests'] = sed_auth_getvalue($info['Auth_guests']);
			$info['Lock_guests'] = sed_auth_getvalue($info['Lock_guests']);

			if ($totalconfig > 0) {
				$t->assign("PLUG_DETAILS_CONFIG_URL", sed_url("admin", "m=config&n=edit&o=plug&p=" . $pl));
				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_CONFIG");
			}

			// Dependencies: from DB if installed, else from setup; build clickable links with titles
			$req_modules_str = '';
			$req_plugins_str = '';
			$req_module_codes = array();
			$req_plugin_codes = array();
			$sql_dep = sed_sql_query("SELECT pl_dependencies FROM $db_plugins WHERE pl_code='" . sed_sql_prep($pl) . "' AND pl_module=0 AND pl_dependencies IS NOT NULL AND pl_dependencies != '' LIMIT 1");
			if ($dep_row = sed_sql_fetchassoc($sql_dep)) {
				$deps = json_decode($dep_row['pl_dependencies'], true);
				if (isset($deps['requires']) && is_array($deps['requires'])) {
					$req_module_codes = $deps['requires'];
				}
				if (isset($deps['requires_plugins']) && is_array($deps['requires_plugins'])) {
					$req_plugin_codes = $deps['requires_plugins'];
				}
			} else {
				if (!empty($info['Requires_modules'])) {
					$req_module_codes = array_map('trim', array_filter(explode(',', $info['Requires_modules'])));
				}
				if (!empty($info['Requires_plugins'])) {
					$req_plugin_codes = array_map('trim', array_filter(explode(',', $info['Requires_plugins'])));
				}
			}
			if (!empty($req_module_codes)) {
				$mod_titles = array();
				foreach ($req_module_codes as $mc) {
					$sql_m = sed_sql_query("SELECT ct_title FROM $db_core WHERE ct_code='" . sed_sql_prep($mc) . "' LIMIT 1");
					$mr = sed_sql_fetchassoc($sql_m);
					$title = $mr ? $mr['ct_title'] : $mc;
					$mod_titles[] = sed_link(sed_url("admin", "m=modules&a=details&mod=" . $mc), $title);
				}
				$req_modules_str = implode(', ', $mod_titles);
			}
			if (!empty($req_plugin_codes)) {
				$plug_titles = array();
				foreach ($req_plugin_codes as $pc) {
					$sql_p = sed_sql_query("SELECT pl_title FROM $db_plugins WHERE pl_code='" . sed_sql_prep($pc) . "' AND pl_module=0 LIMIT 1");
					$pr = sed_sql_fetchassoc($sql_p);
					$title = ($pr && !empty($pr['pl_title'])) ? $pr['pl_title'] : $pc;
					$plug_titles[] = sed_link(sed_url("admin", "m=plug&a=details&pl=" . $pc), $title);
				}
				$req_plugins_str = implode(', ', $plug_titles);
			}

			$t->assign(array(
				"PLUG_DETAILS_NAME" => $info['Name'],
				"PLUG_DETAILS_CODE" => $info['Code'],
				"PLUG_DETAILS_DESC" => isset($info['Description']) ? $info['Description'] : '',
				"PLUG_DETAILS_VERSION" => isset($info['Version']) ? $info['Version'] : '',
				"PLUG_DETAILS_DATE" => isset($info['Date']) ? $info['Date'] : '',
				"PLUG_DETAILS_REQUIRES_MODULES" => $req_modules_str,
				"PLUG_DETAILS_REQUIRES_PLUGINS" => $req_plugins_str,
				"PLUG_DETAILS_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=plug&io=" . $info['Code']),
				"PLUG_DETAILS_DEFAUTH_GUESTS" => sed_build_admrights($info['Auth_guests']),
				"PLUG_DETAILS_DEFLOCK_GUESTS" => sed_build_admrights($info['Lock_guests']),
				"PLUG_DETAILS_DEFAUTH_MEMBERS" => sed_build_admrights($info['Auth_members']),
				"PLUG_DETAILS_DEFLOCK_MEMBERS" => sed_build_admrights($info['Lock_members']),
				"PLUG_DETAILS_AUTHOR" => isset($info['Author']) ? $info['Author'] : '',
				"PLUG_DETAILS_COPYRIGHT" => isset($info['Copyright']) ? $info['Copyright'] : '',
				"PLUG_DETAILS_NOTES" => isset($info['Notes']) ? $info['Notes'] : '',
				"PLUG_DETAILS_INSTALL_URL" => sed_url("admin", "m=plug&a=edit&pl=" . $info['Code'] . "&b=install&" . sed_xg()),
				"PLUG_DETAILS_UNINSTALL_URL" => sed_url("admin", "m=plug&a=edit&pl=" . $info['Code'] . "&b=uninstall&" . sed_xg()),
				"PLUG_DETAILS_PAUSE_URL" => sed_url("admin", "m=plug&a=edit&pl=" . $info['Code'] . "&b=pause&" . sed_xg()),
				"PLUG_DETAILS_UNPAUSE_URL" => sed_url("admin", "m=plug&a=edit&pl=" . $info['Code'] . "&b=unpause&" . sed_xg())
			));

			if (!empty($req_modules_str)) {
				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_REQUIRES_MODULES");
			}
			if (!empty($req_plugins_str)) {
				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_REQUIRES_PLUGINS");
			}

			// Options: show Install only when not installed; Uninstall + Pause/Unpause when installed
			$sql_inst = sed_sql_query("SELECT COUNT(*) FROM $db_plugins WHERE pl_code='" . sed_sql_prep($pl) . "' AND pl_module=0");
			$plug_installed = (int) sed_sql_result($sql_inst, 0, "COUNT(*)") > 0;
			$sql_active = sed_sql_query("SELECT SUM(pl_active) FROM $db_plugins WHERE pl_code='" . sed_sql_prep($pl) . "' AND pl_module=0");
			$plug_has_active = (int) sed_sql_result($sql_active, 0, "SUM(pl_active)") > 0;

			if (!$plug_installed) {
				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_OPT_INSTALL");
			} else {
				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_OPT_UNINSTALL");
				if ($plug_has_active) {
					$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_OPT_PAUSE");
				} else {
					$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_DETAILS_OPT_UNPAUSE");
				}
			}

			foreach ($parts as $i => $x) {
				$extplugin_file = SED_ROOT . "/plugins/" . $pl . "/" . $x;
				$info_file = sed_infoget($extplugin_file, 'SED_EXTPLUGIN');

				$info_file['Error'] = isset($info_file['Error']) ? str_replace(SED_ROOT, "", $info_file['Error']) : '';

				if (!empty($info_file['Error'])) {

					$t->assign(array(
						"PARTS_LIST_NUMBER" => ($i + 1),
						"PARTS_LIST_FILE" => $x,
						"PARTS_LIST_ERROR" => $info_file['Error']
					));

					$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_PARTS_LIST.PLUG_PARTS_ERROR");
				} else {
					$sql = sed_sql_query("SELECT pl_active, pl_id FROM $db_plugins WHERE pl_code='$pl' AND pl_part='" . $info_file['Part'] . "' LIMIT 1");

					if ($row = sed_sql_fetchassoc($sql)) {
						$info_file['Status'] = $row['pl_active'];
					} else {
						$info_file['Status'] = 3;
					}

					$multi_hooks = "";
					$mhooks = explode(",", $info_file['Hooks']);
					foreach ($mhooks as $kh => $vh) {
						$multi_hooks .= $vh . "<br />";
					}

					$multi_order = '';
					$morder = explode(",", $info_file['Order']);
					foreach ($morder as $ko => $vo) {
						$multi_order .= $vo . "<br />";
					}

					if ($info_file['Status'] == 3) {
						$pl_action = "-";
					} elseif ($row['pl_active'] == 1) {
						$pl_action = sed_link(sed_url("admin", "m=plug&a=edit&pl=" . $pl . "&b=pausepart&part=" . $row['pl_id'] . "&" . sed_xg()), 'Pause', array('class' => 'btn btn-adm'));
					} elseif ($row['pl_active'] == 0) {
						$pl_action = sed_link(sed_url("admin", "m=plug&a=edit&pl=" . $pl . "&b=unpausepart&part=" . $row['pl_id'] . "&" . sed_xg()), 'Un-pause', array('class' => 'btn btn-adm'));
					}

					$t->assign(array(
						"PARTS_LIST_NUMBER" => ($i + 1),
						"PARTS_LIST_PART" => $info_file['Part'],
						"PARTS_LIST_FILE" => $info_file['File'],
						"PARTS_LIST_HOOKS" => $multi_hooks,
						"PARTS_LIST_ORDER" => $multi_order,
						"PARTS_LIST_STATUS" => $status[$info_file['Status']],
						"PARTS_LIST_ACTION" => $pl_action
					));

					$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_PARTS_LIST.PLUG_PARTS");

					$listtags = '';
					if (empty($info_file['Tags'])) {
						$listtags = $L['None'] . "<br />";
					} else {
						$line = explode(":", $info_file['Tags']);
						$line[0] = trim($line[0]);
						$tpls = explode(",", $line[0]);

						foreach ($tpls as $kt => $vt) {
							if (isset($line[1]) && mb_strpos($line[1], ',')) {
								$tags = explode(",", $line[1]);
								$listtags .= $vt . " :<br />";
								foreach ($tags as $k => $v) {
									if (mb_substr(trim($v), 0, 1) == '{') {
										$listtags .= $v . " : ";
										$found = sed_stringinfile(SED_ROOT . '/skins/' . $cfg['defaultskin'] . '/' . $vt, trim($v));
										$listtags .= $found_txt[$found];
										$listtags .= "<br />";
									} else {
										$listtags .= $v . "<br />";
									}
								}
								$listtags .= "<br />";
							} else {
								$listtags = $L['None'] . "<br />";
							}
						}
					}

					$t->assign(array(
						"TAGS_LIST_NUMBER" => ($i + 1),
						"TAGS_LIST_PART" => $info_file['Part'],
						"TAGS_LIST_BODY" => $listtags
					));

					$t->parse("ADMIN_PLUG.PLUG_DETAILS.TAGS_LIST");
				}

				$t->parse("ADMIN_PLUG.PLUG_DETAILS.PLUG_PARTS_LIST");
			}

			$t->parse("ADMIN_PLUG.PLUG_DETAILS");
		} else {
			sed_die();
		}

		break;

	/* =============== */
	case 'edit':
		/* =============== */

		switch ($b) {
			case 'install':
				sed_check_xg();
				$pl = (mb_strtolower($pl) == 'core') ? 'error' : $pl;

				$t->assign(array(
					"PLUG_UN_INSTALL_INFO" => sed_plugin_install($pl),
					"PLUG_UN_INSTALL_URL" => sed_url("admin", "m=plug&a=details&pl=" . $pl)
				));

				$t->parse("ADMIN_PLUG.PLUG_UN_INSTALL");

				break;

			case 'uninstall':
				sed_check_xg();

				$t->assign(array(
					"PLUG_UN_INSTALL_INFO" => sed_plugin_uninstall($pl),
					"PLUG_UN_INSTALL_URL" => sed_url("admin", "m=plug")
				));

				$t->parse("ADMIN_PLUG.PLUG_UN_INSTALL");

				break;

			case 'pause':
				sed_check_xg();
				$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='$pl'");
				sed_cache_clearall();
				sed_redirect(sed_url("admin", "m=plug&a=details&pl=" . $pl, "", true));
				exit;
				break;

			case 'unpause':
				sed_check_xg();
				// Check dependencies before activating
				$dep_ok = true;
				$sql_dep = sed_sql_query("SELECT pl_dependencies FROM $db_plugins WHERE pl_code='" . sed_sql_prep($pl) . "' AND pl_dependencies IS NOT NULL AND pl_dependencies != '' LIMIT 1");
				if ($dep_row = sed_sql_fetchassoc($sql_dep)) {
					$deps = json_decode($dep_row['pl_dependencies'], true);
					if (isset($deps['requires']) && is_array($deps['requires'])) {
						foreach ($deps['requires'] as $req_code) {
							$sql_req = sed_sql_query("SELECT ct_state FROM $db_core WHERE ct_code='" . sed_sql_prep($req_code) . "' AND ct_state=1 LIMIT 1");
							if (!sed_sql_fetchassoc($sql_req)) {
								$dep_ok = false;
								$adminwarnings .= "Cannot activate: required module '" . $req_code . "' is not installed or not active.<br />";
							}
						}
					}
					if ($dep_ok && isset($deps['requires_plugins']) && is_array($deps['requires_plugins'])) {
						foreach ($deps['requires_plugins'] as $req_code) {
							$sql_req = sed_sql_query("SELECT SUM(pl_active) AS active_count FROM $db_plugins WHERE pl_code='" . sed_sql_prep($req_code) . "' AND pl_module=0");
							$req_row = sed_sql_fetchassoc($sql_req);
							if (!$req_row || (int)$req_row['active_count'] < 1) {
								$dep_ok = false;
								$adminwarnings .= "Cannot activate: required plugin '" . $req_code . "' is not installed or not active.<br />";
							}
						}
					}
				}
				if ($dep_ok) {
					$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='$pl'");
					sed_cache_clearall();
					sed_redirect(sed_url("admin", "m=plug&a=details&pl=" . $pl, "", true));
					exit;
				}
				break;

			case 'pausepart':
				sed_check_xg();
				$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=0 WHERE pl_code='$pl' AND pl_id='$part'");
				sed_cache_clearall();
				sed_redirect(sed_url("admin", "m=plug&a=details&pl=" . $pl, "", true));
				exit;
				break;

			case 'unpausepart':
				sed_check_xg();
				$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='$pl' AND pl_id='$part'");
				sed_cache_clearall();
				sed_redirect(sed_url("admin", "m=plug&a=details&pl=" . $pl, "", true));
				exit;
				break;

			default:
				sed_die();
				break;
		}

		break;

	default:

		if ($a == 'delhook') {
			$id = sed_import('id', 'G', 'INT');
			sed_check_xg();
			$sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_id='$id'");
			sed_cache_clearall();
		}

		$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner='plug' GROUP BY config_cat");
		while ($row = sed_sql_fetchassoc($sql)) {
			$cfgentries[$row['config_cat']] = $row['COUNT(*)'];
		}

		$handle = opendir(SED_ROOT . "/plugins");
		while ($f = readdir($handle)) {
			if (!is_file($f) && $f != '.' && $f != '..' && $f != 'code') {
				$extplugins[] = $f;
			}
		}
		closedir($handle);
		sort($extplugins);
		$cnt_extp = count($extplugins);
		$cnt_parts = 0;

		$plg_standalone = array();
		$sql3 = sed_sql_query("SELECT pl_code FROM $db_plugins WHERE pl_hook='standalone'");
		while ($row3 = sed_sql_fetchassoc($sql3)) {
			$plg_standalone[$row3['pl_code']] = TRUE;
		}

		$plg_tools = array();
		$sql3 = sed_sql_query("SELECT pl_code FROM $db_plugins WHERE pl_hook='tools'");
		while ($row3 = sed_sql_fetchassoc($sql3)) {
			$plg_tools[$row3['pl_code']] = TRUE;
		}

		$t->assign(array(
			"PLUG_LISTING_COUNT" => $cnt_extp
		));

		foreach ($extplugins as $i => $x) {
			$extplugin_info = SED_ROOT . "/plugins/" . $x . "/" . $x . ".setup.php";
			if (file_exists($extplugin_info)) {
				$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

				if (!empty($info['Error'])) {

					$t->assign(array(
						"PLUG_LIST_CODE" => $x,
						"PLUG_LIST_ERROR" => $info['Error']
					));

					$t->parse("ADMIN_PLUG.PLUG_LISTING.PLUG_LIST.PLUG_LIST_ERROR");
				} else {
					$sql1 = sed_sql_query("SELECT SUM(pl_active) FROM $db_plugins WHERE pl_code='$x'");
					$sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_plugins WHERE pl_code='$x'");
					$totalactive = sed_sql_result($sql1, 0, "SUM(pl_active)");
					$totalinstalled = sed_sql_result($sql2, 0, "COUNT(*)");
					$cnt_parts += $totalinstalled;

					if ($totalinstalled == 0) {
						$part_status = 3;
						$info['Partscount'] = '';
					} else {
						$info['Partscount'] = '(' . $totalinstalled . ')';
						if ($totalinstalled > $totalactive && $totalactive > 0) {
							$part_status = 2;
						} elseif ($totalactive == 0) {
							$part_status = 0;
						} else {
							$part_status = 1;
						}
					}

					if (isset($cfgentries[$info['Code']]) && $cfgentries[$info['Code']] > 0) {
						$t->assign(array(
							"PLUG_LIST_CONFIG_URL" => sed_url("admin", "m=config&n=edit&o=plug&p=" . $info['Code'])
						));

						$t->parse("ADMIN_PLUG.PLUG_LISTING.PLUG_LIST.PLUG_LIST_CONFIG");
					}

					if (isset($plg_tools[$info['Code']]) && $plg_tools[$info['Code']]) {
						$pl_url = sed_url("admin", "m=manage&p=" . $info['Code']);
					} else {
						$pl_url = (isset($plg_standalone[$info['Code']]) && $plg_standalone[$info['Code']]) ? sed_url("plug", "e=" . $info['Code']) : '';
					}

					if (!empty($pl_url)) {
						$t->assign(array(
							"PLUG_LIST_OPEN_URL" => $pl_url
						));

						$t->parse("ADMIN_PLUG.PLUG_LISTING.PLUG_LIST.PLUG_LIST_OPEN");
					}

					$t->assign(array(
						"PLUG_LIST_ICON" => sed_plugin_icon($info['Code']),
						"PLUG_LIST_NAME" => $info['Name'],
						"PLUG_LIST_DETAILS_URL" => sed_url("admin", "m=plug&a=details&pl=" . $info['Code']),
						"PLUG_LIST_CODE" => $x,
						"PLUG_LIST_VERSION" => $info['Version'],
						"PLUG_LIST_STATUS" => $status[$part_status],
						"PLUG_LIST_PARTS_COUNT" => $info['Partscount'],
						"PLUG_LIST_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=plug&io=" . $info['Code'])
					));

					$t->parse("ADMIN_PLUG.PLUG_LISTING.PLUG_LIST");
				}
			} else {
				$t->assign(array(
					"PLUG_LIST_CODE" => $x,
					"PLUG_LIST_ERROR" => "Error: Setup file is missing !"
				));

				$t->parse("ADMIN_PLUG.PLUG_LISTING.PLUG_LIST.PLUG_LIST_ERROR");
			}
		}

		if ($o == 'code') {
			$sql = sed_sql_query("SELECT * FROM $db_plugins WHERE (pl_module=0 OR pl_module IS NULL) ORDER BY pl_code ASC, pl_hook ASC, pl_order ASC");
		} else {
			$sql = sed_sql_query("SELECT * FROM $db_plugins WHERE (pl_module=0 OR pl_module IS NULL) ORDER BY pl_hook ASC, pl_code ASC, pl_order ASC");
		}

		$t->assign(array(
			"HOOKS_COUNT" => sed_sql_numrows($sql)
		));

		while ($row = sed_sql_fetchassoc($sql)) {
			$extplugin_file = SED_ROOT . "/plugins/" . $row['pl_code'] . "/" . $row['pl_file'] . ".php";
			$info_file = sed_infoget($extplugin_file, 'SED_EXTPLUGIN');

			$extplugin_file_path = str_replace(SED_ROOT, "", $extplugin_file);

			$t->assign(array(
				"HOOK_LIST_HOOK" => $row['pl_hook'],
				"HOOK_LIST_PLUG_TITLE" => $row['pl_title'],
				"HOOK_LIST_PLUG_CODE" => $row['pl_code'],
				"HOOK_LIST_PLUG_FILE" => (file_exists($extplugin_file)) ? "<span style=\"color:#739E48; font-weight:bold;\">" . $extplugin_file_path . "</span>" : sed_link(sed_url("admin", "m=plug&a=delhook&id=" . $row['pl_id'] . "&" . sed_xg(), "#hooks"), $out['ic_delete']) . " <span style=\"color:#AC5866; font-weight:bold;\">" . $L['adm_missing'] . " : " . $extplugin_file_path . "</span>",
				"HOOK_LIST_ORDER" => $row['pl_order'],
				"HOOK_LIST_STATUS" => $sed_yesno[$row['pl_active']]
			));

			$t->parse("ADMIN_PLUG.PLUG_LISTING.HOOK_LIST");
		}

		$t->parse("ADMIN_PLUG.PLUG_LISTING");

		break;
}

$t->assign("ADMIN_PLUG_TITLE", $admintitle);

$t->parse("ADMIN_PLUG");

$adminmain .= $t->text("ADMIN_PLUG");
