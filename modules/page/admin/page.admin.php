<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Page admin router
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$s = sed_import('s', 'G', 'ALP', 24);

if ($s == 'add') {
	require(SED_ROOT . '/modules/page/admin/page.admin.add.php');
} elseif ($s == 'edit') {
	require(SED_ROOT . '/modules/page/admin/page.admin.edit.php');
} elseif ($s == 'manager') {
	require(SED_ROOT . '/modules/page/admin/page.admin.manager.php');
} else {
	require(SED_ROOT . '/modules/page/admin/page.admin.structure.php');
}
