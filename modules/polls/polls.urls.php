<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/polls/polls.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Polls URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 240;

/* Polls rewriting */
$mod_urlrewrite = array(
	array(
		'cond' => '#^/polls/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/polls/polls.php?id=$1'
	),
	array(
		'cond' => '#^/polls(/?)$#',
		'rule' => 'modules/polls/polls.php'
	),
);

$mod_urltrans = array();
$mod_urltrans['polls'] = array(
	array(
		'params' => 'id=*',
		'rewrite' => 'polls/{id}'
	),
	array(
		'params' => '',
		'rewrite' => 'polls'
	)
);
