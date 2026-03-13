<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks
Hooks=standalone
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

global $db_thanks, $db_pages, $db_forum_posts, $db_forum_topics, $db_forum_sections, $db_com, $db_users, $db_structure, $cfg, $usr, $L, $sys;

$a = sed_import('a', 'G', 'ALP');
$ext = sed_import('ext', 'G', 'ALP');
$item = sed_import('item', 'G', 'INT');
$user = sed_import('user', 'G', 'INT');

if ($a == 'thank' && !empty($ext) && $item > 0 && $usr['id'] > 0) {
	$ext_ok = ($ext == 'page' && sed_module_active('page')) || ($ext == 'forums' && sed_module_active('forums')) || ($ext == 'comments' && sed_plug_active('comments'));
	if (!$ext_ok) {
		sed_redirect(sed_url('plug', 'e=thanks'));
		exit;
	}
	$touser = 0;
	if ($ext == 'page') {
		$sql = sed_sql_query("SELECT page_ownerid FROM $db_pages WHERE page_id=" . (int)$item . " LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			$touser = (int)$row['page_ownerid'];
		}
	} elseif ($ext == 'forums') {
		$sql = sed_sql_query("SELECT fp_posterid FROM $db_forum_posts WHERE fp_id=" . (int)$item . " LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			$touser = (int)$row['fp_posterid'];
		}
	} elseif ($ext == 'comments') {
		$sql = sed_sql_query("SELECT com_authorid FROM $db_com WHERE com_id=" . (int)$item . " LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			$touser = (int)$row['com_authorid'];
		}
	}

	if ($touser > 0) {
		$status = thanks_check($touser, $usr['id'], $ext, $item);
		if ($status == THANKS_ERR_NONE) {
			thanks_add($touser, $usr['id'], $ext, $item);
			$ref = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : sed_url('plug', 'e=thanks');
			sed_redirect($ref);
			exit;
		}
	}
}

$plugin_title = isset($L['thanks_title']) ? $L['thanks_title'] : 'Thanks';
$plugin_subtitle = '';
$urlpaths = array(sed_url('plug', 'e=thanks') => $plugin_title);
$thanks_dateformat = !empty($cfg['plugin']['thanks']['format']) ? $cfg['plugin']['thanks']['format'] : 'd.m.Y';

if ($a == 'viewdetails' && !empty($ext) && $item > 0 && thanks_count($ext, $item) > 0) {
	$ext_ok = ($ext == 'page' && sed_module_active('page')) || ($ext == 'forums' && sed_module_active('forums')) || ($ext == 'comments' && sed_plug_active('comments'));
	if (!$ext_ok) {
		sed_redirect(sed_url('plug', 'e=thanks'));
		exit;
	}
	$perpage = (int)(isset($cfg['plugin']['thanks']['thanksperpage']) ? $cfg['plugin']['thanks']['thanksperpage'] : 20);
	
	$d = sed_import('d', 'G', 'INT');
	if (empty($d)) $d = 0;

	$totallines = thanks_count($ext, $item);
	$baseurl = sed_url('plug', 'e=thanks&a=viewdetails&ext=' . $ext . '&item=' . $item);
	$item_info = thanks_resolve_item_info($ext, $item, $L);
	
	$sql = sed_sql_query("SELECT t.*, u.user_name, u.user_maingrp FROM $db_thanks AS t 
		LEFT JOIN $db_users AS u ON t.th_fromuser = u.user_id 
		WHERE t.th_ext='" . sed_sql_prep($ext) . "' AND t.th_item=" . (int)$item . " 
		ORDER BY t.th_date DESC LIMIT " . $d . "," . $perpage);
	
	while ($row = sed_sql_fetchassoc($sql)) {
		$userlink = sed_build_user($row['th_fromuser'], sed_cc($row['user_name']), $row['user_maingrp']);		
		$t->assign(array(
			'THANKS_LIST_ROW_DATE' => sed_build_date($thanks_dateformat, $row['th_date']),
			'THANKS_LIST_ROW_FROM' => $userlink,
			'THANKS_LIST_ROW_TYPE' => $item_info['type'],
			'THANKS_LIST_ROW_CATEGORY' => $item_info['category'],
			'THANKS_LIST_ROW_ITEM' => $item_info['item']
		));
		$t->parse('MAIN.THANKS_LIST.THANKS_LIST_ROW');
	}

	$pagination = ($perpage > 0 && $totallines > $perpage) ? sed_pagination($baseurl, $d, $totallines, $perpage) : '';
	$t->assign('THANKS_PAGINATION', $pagination);
	$t->parse('MAIN.THANKS_LIST');

	if ($ext == 'page') {
		$r = sed_sql_fetchassoc(sed_sql_query("SELECT page_title FROM $db_pages WHERE page_id=" . (int)$item . " LIMIT 1"));
		$plugin_subtitle = $r ? $r['page_title'] : 'Page #' . $item;
	} elseif ($ext == 'forums') {
		$plugin_subtitle = 'Post #' . $item;
	} else {
		$plugin_subtitle = 'Comment #' . $item;
	}
	$urlpaths[$baseurl] = $plugin_subtitle;

} elseif (!empty($user)) {
	$perpage = (int)(isset($cfg['plugin']['thanks']['thanksperpage']) ? $cfg['plugin']['thanks']['thanksperpage'] : 20);
	
	$d = sed_import('d', 'G', 'INT');
	if (empty($d)) $d = 0;

	$totallines = thanks_user_thanks_count($user);
	$baseurl = sed_url('plug', 'e=thanks&user=' . $user);
	
	$sql = sed_sql_query("SELECT t.*, u.user_name, u.user_maingrp 
		FROM $db_thanks AS t LEFT JOIN $db_users AS u ON t.th_fromuser = u.user_id 
		WHERE t.th_touser=" . (int)$user . " ORDER BY t.th_date DESC LIMIT " . $d . "," . $perpage);

	if ($totallines > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$userlink = sed_build_user($row['th_fromuser'], sed_cc($row['user_name']), $row['user_maingrp']);
			$item_info = thanks_resolve_item_info($row['th_ext'], $row['th_item'], $L);
			
			$t->assign(array(
				'THANKS_LIST_ROW_DATE' => sed_build_date($thanks_dateformat, $row['th_date']),
				'THANKS_LIST_ROW_FROM' => $userlink,
				'THANKS_LIST_ROW_TYPE' => $item_info['type'],
				'THANKS_LIST_ROW_CATEGORY' => $item_info['category'],
				'THANKS_LIST_ROW_ITEM' => $item_info['item']
			));
			$t->parse('MAIN.THANKS_LIST.THANKS_LIST_ROW');
		}

		$pagination = ($perpage > 0 && $totallines > $perpage) ? sed_pagination($baseurl, $d, $totallines, $perpage) : '';
		$t->assign('THANKS_PAGINATION', $pagination);
		$t->parse('MAIN.THANKS_LIST');
	} else {
		$t->parse('MAIN.THANKS_EMPTY');
	}

	$ur = sed_sql_fetchassoc(sed_sql_query("SELECT user_name FROM $db_users WHERE user_id=" . (int)$user . " LIMIT 1"));
	$plugin_subtitle = $ur ? $ur['user_name'] : 'User #' . $user;
	$urlpaths[$baseurl] = $plugin_subtitle;
	$plugin_shorttitle = (isset($L['thanks_title_user']) ? $L['thanks_title_user'] : 'Thanks to user') . ' ' . $plugin_subtitle;

} else {
	$sql = sed_sql_query("SELECT th_touser, COUNT(*) AS cnt FROM $db_thanks GROUP BY th_touser ORDER BY cnt DESC LIMIT 50");
	$items = array();

	while ($row = sed_sql_fetchassoc($sql)) {
		$u = sed_sql_fetchassoc(sed_sql_query("SELECT user_name, user_maingrp FROM $db_users WHERE user_id=" . (int)$row['th_touser'] . " LIMIT 1"));
		$items[] = array(
			'user_id' => $row['th_touser'],
			'user_name' => $u ? $u['user_name'] : '',
			'user_maingrp' => $u ? $u['user_maingrp'] : '',
			'cnt' => $row['cnt']
		);
	}

	if (count($items) > 0) {
		$i = 1;
		foreach ($items as $r) {
			$userlink = sed_build_user($r['user_id'], sed_cc($r['user_name']), $r['user_maingrp']);
			$t->assign(array(
				'THANKS_TOP_ROW_NUM' => $i,
				'THANKS_TOP_ROW_USER' => $userlink,
				'THANKS_TOP_ROW_CNT' => $r['cnt'],
				'THANKS_TOP_ROW_LINK' => sed_link(sed_url('plug', 'e=thanks&user=' . $r['user_id']), '&raquo;')
			));
			$t->parse('MAIN.THANKS_TOP.THANKS_TOP_ROW');
			$i++;
		}
		$t->parse('MAIN.THANKS_TOP');
	} else {
		$t->parse('MAIN.THANKS_EMPTY');
	}
}

$plugin_shorttitle = isset($plugin_shorttitle) ? $plugin_shorttitle : ($plugin_title . ($plugin_subtitle ? ' &mdash; ' . $plugin_subtitle : ''));
$t->assign(array(
	'THANKS_BREADCRUMBS' => sed_breadcrumbs($urlpaths),
	'THANKS_TITLE' => $plugin_shorttitle
));
