<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/admin/rss.admin.menu.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=RSS admin menu (no sidebar button)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'adminlink' => sed_url('rss')
);

