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

/*
	sections cheat sheet (admin sidebar menu for modules/plugins):

	- key => value:
		- key (e.g. 'manager', 'queue'): used to auto-build the URL as {param}={key} when 'url' is not set
		- value can be either a string (label) or an array with options

	- label:
		- language key or plain text

	- param (default is 's'):
		- which GET parameter to use for the auto-generated link: m={module}&{param}={key}
		- examples: 's', 'mn'

	- auth (optional):
		- arguments for sed_auth(), e.g. array('page', 'any', 'A')
		- if auth fails, the menu item is hidden

	- url (optional):
		- if set, it is used as-is (can point to any admin URL)

	- match (optional):
		- expected params to mark the item as active/current
		- IMPORTANT: current admin.header.php implementation compares match only against m/s/mn.
		  So for links like admin/config?... you can reliably match only by m=config
		  (or extend the matching logic in admin.header.php).
*/

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

		/*
		// Example: "Page settings" item (links to admin/config?...)
		,
		'config' => array(
			'label' => 'Configuration',
			'auth'  => array('admin', 'a', 'A'),
			'url'   => sed_url('admin', 'm=config&n=edit&o=module&p=page'),
			'match' => array('m' => 'config')
		)
		*/
	)
);
