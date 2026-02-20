<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/admin/pm.admin.config.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=PM statistics block on module config page
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

require(SED_ROOT . '/modules/pm/admin/pm.admin.stat.php');
