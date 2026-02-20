<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/admin/forums.admin.menu.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Forums admin menu definition (sidebar item and submenu)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'title'   => 'adm_forums',
	'order'   => 20,
	'sections' => array(
		''          => 'adm_forums_main',
		'structure' => 'adm_forum_structure'
	)
);
