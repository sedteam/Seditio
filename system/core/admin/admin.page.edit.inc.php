<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.page.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Amro
Description=Pages
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
    die('Wrong URL.');
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=page")] =  $L['Pages'];
$urlpaths[sed_url("admin", "m=edit&id=" . $id)] =  $L['Edit'];

$admintitle = $L['Edit'];

require(SED_ROOT . '/system/core/page/page.edit.inc.php');
