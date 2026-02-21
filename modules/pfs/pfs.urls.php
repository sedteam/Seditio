<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/pfs.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PFS URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 170;

/* PFS rewriting */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/pfs/([0-9]+)(/?)$#',
		'rule' => 'modules/pfs/pfs.php?f=$1'
	),
	array(
		'cond' => '#^/pfs(/?)$#',
		'rule' => 'modules/pfs/pfs.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['pfs'] = array(
	array(
		'params' => 'f=*',
		'rewrite' => 'pfs/{f}'
	),
	array(
		'params' => '',
		'rewrite' => 'pfs'
	),
);
