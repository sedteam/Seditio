<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.menu.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Page admin menu definition (sidebar item and submenu with auth)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

return array(
	'title'     => 'core_page',
	'order'     => 10,
	'adminlink' => sed_url('admin', 'm=page&s=manager'),
	'sections'  => array(
		'queue' => array(
			'label' => 'adm_valqueue',
			'auth'  => array('admin', 'any', 'A'),
			'param' => 'mn'
		),
		'add' => array(
			'label' => 'addnewentry',
			'auth'  => array('page', 'any', 'A'),
			'param' => 's'
		),
		'manager' => array(
			'label' => 'adm_pagemanager',
			'auth'  => array('page', 'any', 'A'),
			'param' => 's'
		),
		'catorder' => array(
			'label' => 'adm_sortingorder',
			'auth'  => array('admin', 'a', 'A'),
			'param' => 'mn'
		),
		'structure' => array(
			'label' => 'adm_structure',
			'auth'  => array('admin', 'a', 'A'),
			'param' => 'mn'
		)
	)
);
