<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.config.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Configuration
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=config")] = $L['Configuration'];

$admintitle = $L['Configuration'];

$sed_select_charset = sed_loadcharsets();
$sed_select_doctypeid = sed_loaddoctypes();

$o = sed_import('o', 'G', 'ALP');
$p = sed_import('p', 'G', 'ALP');
$v = sed_import('v', 'G', 'TXT');

if (empty($a) && empty($n) && empty($o)) {
	$n = 'edit';
	$o = 'core';
	$p = 'main';
}

if ($o == 'plug' && !empty($p))  //New in v173
{
	$plug_langfile = SED_ROOT . "/plugins/" . $p . "/lang/" . $p . "." . $usr['lang'] . ".lang.php";
	if (@file_exists($plug_langfile)) {
		require($plug_langfile);
	}
}

switch ($n) {
	case 'edit':
		$o = (empty($o)) ? 'core' : $o;
		$p = (empty($o)) ? 'main' : $p;

		if ($a == 'update' && !empty($n)) {
			sed_check_xg();
			if ($o == 'core') {
				reset($cfgmap);
				foreach ($cfgmap as $k => $line) {
					if ($line[0] == $p && $line[3] != 7) {
						$cfg_name = $line[2];
						$cfg_value = trim(sed_import($cfg_name, 'P', 'NOC'));
						$sql = sed_sql_query("UPDATE $db_config SET config_value='" . sed_sql_prep($cfg_value) . "' WHERE config_name='" . $cfg_name . "' AND config_owner='core'");
					}
				}
			} else {
				$sql = sed_sql_query("SELECT config_owner, config_name FROM $db_config WHERE config_owner='$o' AND config_cat='$p'");
				while ($row = sed_sql_fetchassoc($sql)) {
					$cfg_value = trim(sed_import($row['config_name'], 'P', 'NOC'));
					$sql1 = sed_sql_query("UPDATE $db_config SET config_value='" . sed_sql_prep($cfg_value) . "' WHERE config_name='" . $row['config_name'] . "' AND config_owner='$o' AND config_cat='$p'");
				}
			}
			sed_redirect(sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p . "&msg=917", "", true));
			exit;
		} elseif ($a == 'reset' && $o == 'core' && !empty($v)) {
			sed_check_xg();
			foreach ($cfgmap as $i => $line) {
				if ($v == $line[2]) {
					$sql = sed_sql_query("UPDATE $db_config SET config_value='" . sed_sql_prep($line[4]) . "', config_type='" . sed_sql_prep($line[3]) . "' WHERE config_name='$v' AND config_owner='$o'");
				}
			}
		} elseif ($a == 'reset' && $o == 'plug' && !empty($v) &&  !empty($p)) {
			sed_check_xg();
			$extplugin_info = SED_ROOT . "/plugins/" . $p . "/" . $p . ".setup.php";

			if (file_exists($extplugin_info)) {
				if (empty($info_cfg['Error'])) {
					$info_cfg = sed_infoget($extplugin_info, 'SED_EXTPLUGIN_CONFIG');
					foreach ($info_cfg as $i => $x) {
						$line = explode(":", $x);
						if (is_array($line) && !empty($line[1]) && !empty($i)) {
							if ($v == $i) {
								$sql = sed_sql_query("UPDATE $db_config SET config_value='" . sed_sql_prep($line[3]) . "' WHERE config_name='$v' AND config_owner='$o'");
							}
						}
					}
				}
			}
		}

		$sql = sed_sql_query("SELECT * FROM $db_config WHERE config_owner='$o' AND config_cat='$p' ORDER BY config_cat ASC, config_order ASC, config_name ASC");
		sed_die(sed_sql_numrows($sql) == 0);

		foreach ($cfgmap as $k => $line) {
			$cfg_params[$line[2]] = $line[5];
		}

		if ($o == 'core') {
			$urlpaths[sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p)] = $L["core_" . $p];
			$admintitle = $L["core_" . $p];

			$adminhelpconfig = isset($L["adm_help_config_$p"]) ? $L["adm_help_config_$p"] : '';
			$adminlegend = $L["core_" . $p];
		} else {
			$extplugin_info = SED_ROOT . "/plugins/" . $p . "/" . $p . ".setup.php";
			$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

			$urlpaths[sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p)] = $info['Name'] . ' (' . $p . ')';
			$admintitle = $info['Name'] . ' (' . $p . ')';

			$adminlegend = $L['Plugin'] . ' : ' . $info['Name'] . ' (' . $p . ')';
		}

		$t = new XTemplate(sed_skinfile('admin.config', false, true));

		while ($row = sed_sql_fetchassoc($sql)) {
			$config_owner = $row['config_owner'];
			$config_cat = $row['config_cat'];
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$config_default = $row['config_default'];
			$config_type = $row['config_type'];
			$config_title = isset($L['cfg_' . $row['config_name']][0]) ? $L['cfg_' . $row['config_name']][0] : '';
			$check_config_title = empty($config_title);  //fix Sed v173      
			$config_title = (empty($config_title)) ? $row['config_name'] : $config_title;
			$config_text = sed_cc($row['config_text']);
			$config_more = isset($L['cfg_' . $row['config_name']][1]) ? $L['cfg_' . $row['config_name']][1] : '';
			$config_more = (!empty($config_more)) ? '(' . $config_more . ')' : $config_more;
			$config_title = (!empty($config_text) && $check_config_title) ? $config_text : $config_title; //fix Sed v173 

			if ($config_type == 7) {
				continue;
			} //Hidden config New v173

			if ($config_type == 1) {
				$config_field = sed_textbox($config_name, $config_value, 32, 255);
			} elseif ($config_type == 2) {
				if ($o == 'plug' && !empty($row['config_default'])) {
					$cfg_params[$config_name] = explode(",", $row['config_default']);
				}
				if (is_array($cfg_params[$config_name])) {
					reset($cfg_params[$config_name]);
					$config_field = sed_selectbox($config_value, $config_name, $cfg_params[$config_name], false, false);
				} elseif ($cfg_params[$config_name] == "userlevels") {
					$config_field = sed_selectboxlevels(0, 99, $config_value, $config_name);
				} else {
					$config_field = sed_textbox($config_name, $config_value, 8, 11);
				}
			} elseif ($config_type == 3) {
				$config_field  = sed_radiobox($config_name, $yesno_arr, $config_value); // sed177			
			} elseif ($config_type == 4) {
				$varname = "sed_select_" . $config_name;
				$config_field = "<select name=\"" . $config_name . "\" size=\"1\">";
				reset($$varname);
				foreach ($$varname as $i => $x) {
					$selected = ($config_value == $x[0]) ? "selected=\"selected\"" : '';
					$config_field .= "<option value=\"" . $x[0] . "\" $selected>" . $x[1] . "</option>";
				}
				$config_field .= "</select>";
			} else {
				$config_field = "<textarea name=\"$config_name\" rows=\"5\" cols=\"76\" class=\"noeditor\">" . $config_value . "</textarea>";
			}

			$config_reset_url = ($o == 'core') ? sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p . "&a=reset&v=" . $config_name . "&" . sed_xg()) : '';
			$config_reset_url .= ($o == 'plug') ? sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p . "&a=reset&v=" . $config_name . "&" . sed_xg()) : '&nbsp;';

			$t->assign(array(
				"CONFIG_LIST_TITLE" => $config_title,
				"CONFIG_LIST_FIELD" => $config_field,
				"CONFIG_LIST_DESC" => $config_more,
				"CONFIG_LIST_RESET_URL" => $config_reset_url
			));

			$t->parse("ADMIN_CONFIG.CONFIG_LIST");
		}

		if (!empty($adminhelpconfig)) {
			$t->assign(array(
				"HELP_CONFIG" => $adminhelpconfig
			));

			$t->parse("ADMIN_CONFIG.HELP");
		}

		$t->assign(array(
			"ADMIN_CONFIG_FORM_SEND" => sed_url("admin", "m=config&n=edit&o=" . $o . "&p=" . $p . "&a=update&" . sed_xg()),
			"ADMIN_CONFIG_ADMINLEGEND" => $adminlegend
		));

		$t->assign("ADMIN_CONFIG_TITLE", $admintitle);

		$t->parse("ADMIN_CONFIG");

		$adminmain = $t->text("ADMIN_CONFIG");

		$sys['inc_cfg_options'] = SED_ROOT . '/system/core/admin/admin.config.' . $p . '.inc.php';

		if (file_exists($sys['inc_cfg_options'])) {
			require($sys['inc_cfg_options']);
		}

		break;

	default:

		break;
}
