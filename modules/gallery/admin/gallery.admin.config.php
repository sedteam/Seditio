<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/admin/gallery.admin.config.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Gallery extra config on module config page (GD block)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

// Append GD info block when this module's config is edited (admin/config?n=edit&o=module&p=gallery)
require(SED_ROOT . '/modules/gallery/admin/gallery.admin.gd.php');
