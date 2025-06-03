<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.manage.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('manage', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];

$admintitle = $L['adm_manage'];

$p = sed_import('p', 'G', 'ALP');

$t = new XTemplate(sed_skinfile('admin.manage', false, true));

if (!empty($p)) {
	$path_lang_def	= SED_ROOT . "/plugins/$p/lang/$p.en.lang.php";
	$path_lang_alt	= SED_ROOT . "/plugins/$p/lang/$p.$lang.lang.php";

	if (@file_exists($path_lang_alt)) {
		require($path_lang_alt);
	} elseif (@file_exists($path_lang_def)) {
		require($path_lang_def);
	}

	$extp = array();

	if (is_array($sed_plugins)) {
		foreach ($sed_plugins['tools'] as $i => $k) {
			if ($k['pl_code'] == $p) {
				$extp[$i] = $k;
			}
		}
	}

	if (count($extp) == 0) {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	$extplugin_info = SED_ROOT . "/plugins/" . $p . "/" . $p . ".setup.php";

	if (file_exists($extplugin_info)) {
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
	} else {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	$adminhelp = $L['Description'] . " : " . $info['Description'] . "<br />" . $L['Version'] . " : " . $info['Version'] . "<br />" . $L['Date'] . " : " . $info['Date'] . "<br />" . $L['Author'] . " : " . $info['Author'] . "<br />" . $L['Copyright'] . " : " . $info['Copyright'] . "<br />" . $L['Notes'] . " : " . $info['Notes'];

	$urlpaths[sed_url("admin", "m=manage&p=" . $p)] = $info['Name'];

	$t->assign(array(
		"TOOL_TITLE" => $info['Name'],
		"TOOL_ICON" => sed_plugin_icon($p)
	));

	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');

			$t->assign(array(
				"TOOL_BODY" => $plugin_body
			));

			$t->parse("ADMIN_TOOL.TOOL_BODY_LIST");
		}
	}

	$t->parse("ADMIN_TOOL");
	$adminmain .= $t->text("ADMIN_TOOL");
} else {

	$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner!='plug' GROUP BY config_cat");
	while ($row = sed_sql_fetchassoc($sql)) {
		$cfgentries[$row['config_cat']] = $row['COUNT(*)'];
	}

	$sql = sed_sql_query("SELECT DISTINCT(auth_code), COUNT(*) FROM $db_auth WHERE 1 GROUP BY auth_code");
	while ($row = sed_sql_fetchassoc($sql)) {
		$authentries[$row['auth_code']] = $row['COUNT(*)'];
	}

	$sql = sed_sql_query("SELECT * FROM $db_core WHERE ct_code NOT IN ('admin', 'message', 'index', 'forums', 'users', 'plug', 'page', 'trash') ORDER BY ct_title ASC");
	$lines = array();

	while ($row = sed_sql_fetchassoc($sql)) {
		$row['ct_title_loc'] = (empty($L["core_" . $row['ct_code']])) ? $row['ct_title'] : $L["core_" . $row['ct_code']];

		if ($authentries[$row['ct_code']] > 0) {
			$t->assign(array(
				"MODULES_LIST_RIGHTS_URL" => sed_url("admin", "m=rightsbyitem&ic=" . $row['ct_code'] . "&io=a")
			));
			$t->parse("ADMIN_MANAGE.MODULES_LIST.MODULES_LIST_RIGHTS");
		}

		if (isset($cfgentries[$row['ct_code']]) && $cfgentries[$row['ct_code']] > 0) {
			$t->assign(array(
				"MODULES_LIST_CONFIG_URL" => sed_url("admin", "m=config&n=edit&o=core&p=" . $row['ct_code'])
			));
			$t->parse("ADMIN_MANAGE.MODULES_LIST.MODULES_LIST_CONFIG");
		}

		$t->assign(array(
			"MODULES_LIST_URL" => sed_url("admin", "m=" . $row['ct_code']),
			"MODULES_LIST_CODE" => $row['ct_code'],
			"MODULES_LIST_TITLE" => $row['ct_title_loc']
		));

		$t->parse("ADMIN_MANAGE.MODULES_LIST");
	}

	$t->assign(array(
		"MODULES_LIST_BANLIST_URL" => sed_url("admin", "m=banlist")
	));

	$t->parse("ADMIN_MANAGE.MODULES_LIST_BANLIST");

	$t->assign(array(
		"MODULES_LIST_CACHE_URL" => sed_url("admin", "m=cache")
	));

	$t->parse("ADMIN_MANAGE.MODULES_LIST_CACHE");

	$t->assign(array(
		"MODULES_LIST_SMILIES_URL" => sed_url("admin", "m=smilies")
	));

	$t->parse("ADMIN_MANAGE.MODULES_LIST_SMILIES");

	$t->assign(array(
		"MODULES_LIST_HITS_URL" => sed_url("admin", "m=hits")
	));

	$t->parse("ADMIN_MANAGE.MODULES_LIST_HITS");

	$t->assign(array(
		"MODULES_LIST_REFERERS_URL" => sed_url("admin", "m=referers")
	));

	$t->parse("ADMIN_MANAGE.MODULES_LIST_REFERERS");

	$plugins = array();

	function cmp($a, $b, $k = 1)
	{
		if ($a[$k] == $b[$k]) return 0;
		return ($a[$k] < $b[$k]) ? -1 : 1;
	}

	/* === Hook === */
	$extp = sed_getextplugins('tools');

	if (is_array($extp)) {
		$sql = sed_sql_query("SELECT DISTINCT(config_cat), COUNT(*) FROM $db_config WHERE config_owner='plug' GROUP BY config_cat");
		while ($row = sed_sql_fetchassoc($sql)) {
			$cfgentries[$row['config_cat']] = $row['COUNT(*)'];
		}


		foreach ($extp as $k => $pl) {
			$plugins[] = array($pl['pl_code'], $pl['pl_title']);
		}

		usort($plugins, "cmp");

		foreach ($plugins as $i => $x) {

			if (sed_auth('plug', $x[0], 'R')) {

				$extplugin_info = SED_ROOT . "/plugins/" . $x[0] . "/" . $x[0] . ".setup.php";

				if (file_exists($extplugin_info)) {
					$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');
				} else {
					include(SED_ROOT . "/system/lang/" . $usr['lang'] . "/message.lang.php");
					$info['Name'] = $x[0] . " : " . $L['msg907_1'];
				}

				if (isset($cfgentries[$info['Code']]) && $cfgentries[$info['Code']] > 0) {
					$t->assign(array(
						"TOOLS_LIST_CONFIG_URL" => sed_url("admin", "m=config&n=edit&o=plug&p=" . $info['Code'])
					));
					$t->parse("ADMIN_MANAGE.TOOLS_LIST.TOOLS_LIST_CONFIG");
				}

				$t->assign(array(
					"TOOLS_LIST_URL" => sed_url("admin", "m=manage&p=" . $x[0]),
					"TOOLS_LIST_TITLE" => $info['Name'],
					"TOOLS_LIST_ICON" => sed_plugin_icon($x[0])
				));

				$t->parse("ADMIN_MANAGE.TOOLS_LIST");
			}
		}
	} else {
		$adminmain = $L['adm_listisempty'];
	}

	$t->assign("ADMIN_MANAGE_TITLE", $admintitle);

	$t->parse("ADMIN_MANAGE");

	$adminmain .= $t->text("ADMIN_MANAGE");
}
