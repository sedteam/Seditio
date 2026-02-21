<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/page.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Page and list URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 620;

/* List and page rewriting: all rules point to modules/page/page.php. Order: list 620-635, page 640-680. */

$mod_urlrewrite = array(
	/* Lists rewriting */
	array(
		'order' => 620,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+/%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
		'rule' => 'modules/page/page.php?c=$2&s=$3&w=$4'
	),
	array(
		'order' => 625,
		'cond' => '#^/([a-zA-Z0-9_\-\+%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
		'rule' => 'modules/page/page.php?c=$1&s=$2&w=$3'
	),
	array(
		'order' => 630,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+/%]+)/$#',
		'rule' => 'modules/page/page.php?c=$2'
	),
	array(
		'order' => 635,
		'cond' => '#^/([a-zA-Z0-9_\-\+%]+)/$#',
		'rule' => 'modules/page/page.php?c=$1'
	),

	/* Pages rewriting */
	array(
		'order' => 640,
		'cond' => '#^/page/([a-zA-Z]+)(/?)$#',
		'rule' => 'modules/page/page.php?m=$1'
	),
	array(
		'order' => 645,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)/download(/?)$#',
		'rule' => 'modules/page/page.php?id=$2&a=dl'
	),
	array(
		'order' => 650,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)/download(/?)$#',
		'rule' => 'modules/page/page.php?al=$2&a=dl'
	),
	array(
		'order' => 655,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)/comments(/?)$#',
		'rule' => 'modules/page/page.php?id=$2&comments=1'
	),
	array(
		'order' => 660,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)/comments(/?)$#',
		'rule' => 'modules/page/page.php?al=$2&comments=1'
	),
	array(
		'order' => 665,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)$#',
		'rule' => 'modules/page/page.php?id=$2'
	),
	array(
		'order' => 670,
		'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)$#',
		'rule' => 'modules/page/page.php?al=$2'
	),
	array(
		'order' => 675,
		'cond' => '#^/([0-9]+)$#',
		'rule' => 'modules/page/page.php?id=$1'
	),
	array(
		'order' => 680,
		'cond' => '#^/([a-zA-Z0-9_\-\+%]+)$#',
		'rule' => 'modules/page/page.php?al=$1'
	),
);

/* List and page translation: callbacks sed_get_listpath, sed_get_pagepath in modules/page/inc/page.functions.php. List rules merged into page so sed_url('page', 'c=...') builds category URLs. */

$mod_urltrans = array();
$mod_urltrans['page'] = array(
	/* List (category) rules first so sed_url('page', 'c=...') matches */
	array(
		'params' => 'c=all&s=*&w=*',
		'rewrite' => 'all/sort/{s}-{w}/'
	),
	array(
		'params' => 'c=*&s=*&w=*',
		'rewrite' => '{sed_get_listpath()}sort/{s}-{w}/'
	),
	array(
		'params' => 'c=all',
		'rewrite' => 'all/'
	),
	array(
		'params' => 'm=*',
		'rewrite' => 'page/{m}'
	),
	array(
		'params' => 'c=*',
		'rewrite' => '{sed_get_listpath()}'
	),
	/* Page rules */
	array(
		'params' => 'id=*&a=dl',
		'rewrite' => '{sed_get_pagepath()}{id}/download'
	),
	array(
		'params' => 'al=*&a=dl',
		'rewrite' => '{sed_get_pagepath()}{al}/download'
	),
	array(
		'params' => 'id=*',
		'rewrite' => '{sed_get_pagepath()}{id}'
	),
	array(
		'params' => 'al=*',
		'rewrite' => '{sed_get_pagepath()}{al}'
	),
	array(
		'params' => '',
		'rewrite' => 'page'
	)
);
