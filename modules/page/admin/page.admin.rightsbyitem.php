<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.rightsbyitem.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Page rights by item title and back URL
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$rurl = sed_url('admin', 'm=page&mn=structure');
$title = ($io == 'a' || !isset($sed_cat[$io]['title'])) ? '' : " : " . $sed_cat[$io]['title'];
