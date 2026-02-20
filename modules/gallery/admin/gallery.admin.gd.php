<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/admin/gallery.admin.gd.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Gallery GD info
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['isadmin']);

$t = new XTemplate(sed_skinfile('admin.config.gallery', false, true));

if (!function_exists('gd_info')) {
	$t->parse("ADMIN_CONFIG_GALLERY.NO_GD");
} else {
	$gd_datas = gd_info();
	foreach ($gd_datas as $k => $i) {
		if (mb_strlen($i) < 2) {
			$i = ($i) ? $out['ic_checked'] : '';
		}
		$t->assign(array(
			"GD_SETTING_NAME" => $k,
			"GD_SETTING_VALUE" => $i
		));
		$t->parse("ADMIN_CONFIG_GALLERY.GD_INFO.GD_SETTINGS_LIST");
	}
	$t->parse("ADMIN_CONFIG_GALLERY.GD_INFO");
}

$t->parse("ADMIN_CONFIG_GALLERY");
$adminmain .= $t->text("ADMIN_CONFIG_GALLERY");
