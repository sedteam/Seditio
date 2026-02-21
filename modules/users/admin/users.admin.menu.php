<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/admin/users.admin.menu.php
Version=185
Updated=2026-feb-21
Type=Module
Author=Seditio Team
Description=Users admin menu
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'title'   => 'Users',
	'order'   => 5,
	'adminlink' => sed_url('admin', 'm=users'),
	'sections' => array(
		'' => 'Users'
	)
);
