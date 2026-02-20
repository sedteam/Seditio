<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/pm.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PM URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 310;

/* Pm rewriting */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/pm/mess/([0-9]+)(/?)$#',
		'rule' => 'modules/pm/pm.php?id=$1'
	),
	array(
		'cond' => '#^/pm/action/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/pm/pm.php?m=$1'
	),
	array(
		'cond' => '#^/pm/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/pm/pm.php?f=$1'
	),
	array(
		'cond' => '#^/pm(/?)$#',
		'rule' => 'modules/pm/pm.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['pm'] = array(
	array(
		'params' => 'id=*',
		'rewrite' => 'pm/mess/{id}'
	),
	array(
		'params' => 'm=*',
		'rewrite' => 'pm/action/{m}'
	),
	array(
		'params' => 'f=*',
		'rewrite' => 'pm/{f}'
	),
	array(
		'params' => '',
		'rewrite' => 'pm'
	)
);
