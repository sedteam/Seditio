<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/admin/comments.admin.menu.php
Version=185
Updated=2026-mar-12
Type=Plugin.admin
Author=Seditio Team
Description=Comments admin menu definition (sidebar item and submenu with auth)
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
		- which GET parameter to use for the auto-generated link: m={code}&{param}={key}
		- examples: 's', 'mn'

	- auth (optional):
		- arguments for sed_auth(), e.g. array('plug', 'comments', 'R')
		- if auth fails, the menu item is hidden

	- url (optional):
		- if set, it is used as-is (can point to any admin URL)

	- match (optional):
		- expected params to mark the item as active/current
		- IMPORTANT: current admin.header.php implementation compares match only against m/s/mn.
*/

return array(
	'title'     => 'Comments',
	'order'     => 50,
	'adminlink' => sed_url('admin', 'm=comments'),
	// One button without submenu:
	'sections'  => array()

	/*
	// Submenu example (uncomment to enable):
	,
	'sections' => array(
		'' => array(
			'label' => 'Comments',
			'auth'  => array('plug', 'comments', 'R'),
			'param' => 's'
		),
		// 'someSectionKey' => array(
		// 	'label' => 'SomeLabelKeyOrText',
		// 	'auth'  => array('admin', 'any', 'A'),
		// 	'param' => 's',
		// 	// 'url' => sed_url('admin', 'm=comments&s=someSectionKey'), // optional
		// 	// 'match' => array('m' => 'comments', 's' => 'someSectionKey') // optional
		// )
	)
	*/
);

