<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.plugins.inc.php
Version=180
Updated=2025-jan-25
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

			$t->assign(array(
				"PLUG_DETAILS_NAME" => $info['Name'],
				"PLUG_DETAILS_CODE" => $info['Code'],
				"PLUG_DETAILS_DESC" => isset($info['Description']) ? $info['Description'] : '',
				"PLUG_DETAILS_VERSION" => isset($info['Version']) ? $info['Version'] : '',
				"PLUG_DETAILS_DATE" => isset($info['Date']) ? $info['Date'] : '',
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
						$pl_action = "<a href=\"" . sed_url("admin", "m=plug&a=edit&pl=" . $pl . "&b=pausepart&part=" . $row['pl_id'] . "&" . sed_xg()) . "\" class=\"btn btn-adm\">Pause</a>";
					} elseif ($row['pl_active'] == 0) {
						$pl_action = "<a href=\"" . sed_url("admin", "m=plug&a=edit&pl=" . $pl . "&b=unpausepart&part=" . $row['pl_id'] . "&" . sed_xg()) . "\" class=\"btn btn-adm\">Un-pause</a>";
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
				$sql = sed_sql_query("UPDATE $db_plugins SET pl_active=1 WHERE pl_code='$pl'");
				sed_cache_clearall();
				sed_redirect(sed_url("admin", "m=plug&a=details&pl=" . $pl, "", true));
				exit;
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
			$sql = sed_sql_query("SELECT * FROM $db_plugins ORDER BY pl_code ASC, pl_hook ASC, pl_order ASC");
		} else {
			$sql = sed_sql_query("SELECT * FROM $db_plugins ORDER BY pl_hook ASC, pl_code ASC, pl_order ASC");
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
				"HOOK_LIST_PLUG_FILE" => (file_exists($extplugin_file)) ? "<span style=\"color:#739E48; font-weight:bold;\">" . $extplugin_file_path . "</span>" : "<a href=\"" . sed_url("admin", "m=plug&a=delhook&id=" . $row['pl_id'] . "&" . sed_xg(), "#hooks") . "\">" . $out['ic_delete'] . "</a> <span style=\"color:#AC5866; font-weight:bold;\">" . $L['adm_missing'] . " : " . $extplugin_file_path . "</span>",
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
