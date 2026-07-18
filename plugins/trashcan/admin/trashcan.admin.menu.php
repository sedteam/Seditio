<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/admin/trashcan.admin.menu.php
Version=186
Updated=2026-jul-17
Type=Plugin.admin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'title'     => 'Trashcan', // $L['Trashcan'] in main.lang.php
	'order'     => 60,
	'adminlink' => sed_url('admin', 'm=trashcan'),
	'icon'      => 'ic-trash',
	'auth'      => array('plug', 'trashcan', 'A'),
	'sections'  => array()
);
