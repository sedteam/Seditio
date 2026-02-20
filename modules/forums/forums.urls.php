<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.urls.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=Forums URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 350;

/* Forums rewriting rules */

$mod_urlrewrite = array(
	/* Topics with alias */
	array(
		'cond' => '#^/forums/topics/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=topics&s=$1&al=$2'
	),
	/* Topics without alias */
	array(
		'cond' => '#^/forums/topics/([0-9]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=topics&s=$1'
	),
	/* Posts (topic) with alias */
	array(
		'cond' => '#^/forums/posts/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=posts&q=$1&al=$2'
	),
	/* Posts (topic) without alias */
	array(
		'cond' => '#^/forums/posts/([0-9]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=posts&q=$1'
	),
	/* Single post with alias */
	array(
		'cond' => '#^/forums/post/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=posts&p=$1&al=$2'
	),
	/* Single post without alias */
	array(
		'cond' => '#^/forums/post/([0-9]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?m=posts&p=$1'
	),
	/* Section with alias */
	array(
		'cond' => '#^/forums/([a-zA-Z0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?c=$1&al=$2'
	),
	/* Section without alias */
	array(
		'cond' => '#^/forums/([a-zA-Z0-9]+)(/?)$#',
		'rule' => 'modules/forums/forums.php?c=$1'
	),
	/* Default forums page */
	array(
		'cond' => '#^/forums(/?)$#',
		'rule' => 'modules/forums/forums.php'
	),
);

/* Forums translation rules */

$mod_urltrans = array();
$mod_urltrans['forums'] = array(
	/* Topics with alias */
	array(
		'params' => 'm=topics&s=*&al=*',
		'rewrite' => 'forums/topics/{s}{al|sed_get_forums_urltrans}'
	),
	/* Topics without alias */
	array(
		'params' => 'm=topics&s=*',
		'rewrite' => 'forums/topics/{s}'
	),
	/* Posts (topic) with alias */
	array(
		'params' => 'm=posts&q=*&al=*',
		'rewrite' => 'forums/posts/{q}{al|sed_get_forums_urltrans}'
	),
	/* Posts (topic) without alias */
	array(
		'params' => 'm=posts&q=*',
		'rewrite' => 'forums/posts/{q}'
	),
	/* Single post with alias */
	array(
		'params' => 'm=posts&p=*&al=*',
		'rewrite' => 'forums/post/{p}{al|sed_get_forums_urltrans}'
	),
	/* Single post without alias */
	array(
		'params' => 'm=posts&p=*',
		'rewrite' => 'forums/post/{p}'
	),
	/* Section with alias */
	array(
		'params' => 'c=*&al=*',
		'rewrite' => 'forums/{c}{al|sed_get_forums_urltrans}'
	),
	/* Section without alias */
	array(
		'params' => 'c=*',
		'rewrite' => 'forums/{c}'
	),
	/* Default forums page */
	array(
		'params' => '',
		'rewrite' => 'forums'
	)
);
