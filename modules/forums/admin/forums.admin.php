<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/admin/forums.admin.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Forums admin panel router
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$s = sed_import('s', 'G', 'ALP', 24);

if ($s == 'structure') {
	require(SED_ROOT . '/modules/forums/admin/forums.admin.structure.php');
} else {
	require(SED_ROOT . '/modules/forums/admin/forums.admin.main.php');
}
