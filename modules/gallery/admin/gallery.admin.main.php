<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/admin/gallery.admin.main.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Gallery admin panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['isadmin']);

$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=gallery")] = $L['Gallery'];

$admintitle = $L['Gallery'];

$t = new XTemplate(sed_skinfile('admin.gallery', false, true));
$t->parse("ADMIN_GALLERY");
$t->assign("ADMIN_GALLERY_TITLE", $admintitle);

$adminmain .= $t->text("ADMIN_GALLERY");
