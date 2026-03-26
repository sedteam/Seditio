<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/admin/trashcan.admin.menu.php
Version=185
Updated=2026-mar-26
Type=Plugin.admin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'title'     => 'Trashcan', // $L['Trashcan'] in main.lang.php
	'order'     => 15,
	'adminlink' => sed_url('admin', 'm=trashcan'),
	'auth'      => array('plug', 'trashcan', 'A'),
	'sections'  => array()
);
