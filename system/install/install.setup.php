<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/install/install.setup.php
Version=185
Updated=2026-feb-14
Type=Core.install
Author=Seditio Team
Description=Install setup
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
	die('Wrong URL.');
}

$cfg['config_file'] = "datas/config.php";
$cfg['data_root'] = "datas";
$cfg['default_skin'] = 'sympfy';

$cfg['sqldb'] = "mysqli";

$steps[0] = $L['install_step0'];
$steps[1] = $L['install_step1'];
$steps[2] = $L['install_step2'];
$steps[3] = $L['install_step3'];
$steps[4] = isset($L['install_step4_modules']) ? $L['install_step4_modules'] : 'Select Modules';
$steps[5] = isset($L['install_step5_modinst']) ? $L['install_step5_modinst'] : 'Install Modules';
$steps[6] = $L['install_step4'];
$steps[7] = $L['install_step5'];

$rwfolders[] = "datas/defaultav";
$rwfolders[] = "datas/avatars";
$rwfolders[] = "datas/photos";
$rwfolders[] = "datas/signatures";
$rwfolders[] = "datas/thumbs";
$rwfolders[] = "datas/users";



function sed_selectbox_lang_install($check, $name)
{
	global $sed_languages, $sed_countries;

	$handle = opendir(SED_ROOT . '/system/install/lang/');
	while ($f = readdir($handle)) {
		if ($f[0] != '.') {
			$langlist[] = $f;
		}
	}
	closedir($handle);
	sort($langlist);

	$result = "<select name=\"$name\" size=\"1\">";
	foreach ($langlist as $i => $x) {
		$selected = ($x == $check) ? "selected=\"selected\"" : '';
		$lng = (empty($sed_languages[$x])) ? $sed_countries[$x] : $sed_languages[$x];
		$result .= "<option value=\"$x\" $selected>" . $lng . " (" . $x . ")</option>";
	}
	$result .= "</select>";

	return ($result);
}
