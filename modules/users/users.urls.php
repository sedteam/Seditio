<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.urls.php
Version=185
Updated=2026-feb-21
Type=Module
Author=Seditio Team
Description=Users URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 535;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
		'rule' => 'modules/users/users.php?f=$1&s=$2&w=$3'
	),
	array(
		'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)(/?)$#',
		'rule' => 'modules/users/users.php?f=$1'
	),
	array(
		'cond' => '#^/users/group/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
		'rule' => 'modules/users/users.php?f=all&gm=$1&s=$2&w=$3'
	),
	array(
		'cond' => '#^/users/group/([0-9]+)(/?)$#',
		'rule' => 'modules/users/users.php?gm=$1'
	),
	array(
		'cond' => '#^/users/maingroup/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
		'rule' => 'modules/users/users.php?f=all&g=$1&s=$2&w=$3'
	),
	array(
		'cond' => '#^/users/maingroup/([0-9]+)(/?)$#',
		'rule' => 'modules/users/users.php?g=$1'
	),
	array(
		'cond' => '#^/users/([a-zA-Z]+)/([a-zA-Z]+)(/?)$#',
		'rule' => 'modules/users/users.php?m=$1&a=$2'
	),
	array(
		'cond' => '#^/users/([a-zA-Z]+)/([0-9]+)(/?)$#',
		'rule' => 'modules/users/users.php?m=$1&id=$2'
	),
	array(
		'cond' => '#^/users/([a-zA-Z]+)(/?)$#',
		'rule' => 'modules/users/users.php?m=$1'
	),
	array(
		'cond' => '#^/users(/?)$#',
		'rule' => 'modules/users/users.php'
	),
	array(
		'cond' => '#^/register(/?)$#',
		'rule' => 'modules/users/users.php?m=register'
	),
	array(
		'cond' => '#^/login(/?)$#',
		'rule' => 'modules/users/users.php?m=auth'
	),
);

$mod_urltrans = array();
$mod_urltrans['users'] = array(
	array(
		'params' => 'm=details&id=*',
		'rewrite' => 'users/details/{id}'
	),
	array(
		'params' => 'm=edit&id=*',
		'rewrite' => 'users/edit/{id}'
	),
	array(
		'params' => 'm=auth&a=*',
		'rewrite' => 'users/auth/{a}'
	),
	array(
		'params' => 'm=register&a=*',
		'rewrite' => 'users/register/{a}'
	),
	array(
		'params' => 'm=*',
		'rewrite' => 'users/{m}'
	),
	array(
		'params' => 'f=*&s=*&w=*',
		'rewrite' => 'users/filter/{f}/sort/{s}-{w}'
	),
	array(
		'params' => 'f=*',
		'rewrite' => 'users/filter/{f}'
	),
	array(
		'params' => 'gm=*',
		'rewrite' => 'users/group/{gm}'
	),
	array(
		'params' => 'g=*',
		'rewrite' => 'users/maingroup/{g}'
	),
	array(
		'params' => '',
		'rewrite' => 'users'
	)
);
