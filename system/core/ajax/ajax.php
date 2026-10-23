<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/ajax/ajax.php
Version=186
Updated=2026-oct-23
Type=Core
Author=Amro
Description=Ajax Interface
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

$location = 'Ajax';
$z = 'ajax';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

if (!sed_check_csrf()) {
    sed_sendheaders('application/json', 403);
    echo json_encode(['error' => 'Access denied. Invalid CSRF AJAX header.']);
    exit;
}

$query = sed_import('query', 'G', 'TXT');
$query = sed_sql_prep($query);

$m = sed_import('m', 'G', 'ALP');
$c = sed_import('c', 'G', 'TXT');

$suggestions = array();

if ($m == 'pages' && !empty($query)) {
	$sql_where = '';
	if (empty($c)) {
		list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
		sed_block($usr['isadmin']);
		foreach ($sed_cat as $key => $val) {
			if (mb_stripos($val['title'], $query) !== false)
				{
				$sys['catcode'] = $key;
				$suggestion = new stdClass();
				$suggestion->id = $val['id'];
				$suggestion->title = $val['title'];
				$suggestion->value = $val['title'];
				$suggestion->url = sed_url("page", "c=" . $key);
				$suggestion->data = $val;
				$suggestions[] = $suggestion;
				}
		}
	} else {
		list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $c);
		sed_block($usr['auth_read']);
		$sys['catcode'] = $c;
		if (isset($sed_cat[$c])) {
			$suggestion = new stdClass();
			$suggestion->id = $sed_cat[$c]['id'];
			$suggestion->title = $sed_cat[$c]['title'];
			$suggestion->value = $sed_cat[$c]['title'];
			$suggestion->url = sed_url("page", "c=" . $c);
			$suggestion->data = $sed_cat[$c];
			$suggestions[] = $suggestion;
		}
		$sql_where = "page_cat='" . sed_sql_prep($c) . "' AND";
	}

	$sql_search = sed_sql_query("SELECT page_id, page_title, page_cat, page_alias, page_thumb FROM $db_pages WHERE $sql_where page_title LIKE '%" . $query . "%' LIMIT 300");
	if (sed_sql_numrows($sql_search) > 0) {
		while ($row = sed_sql_fetchassoc($sql_search)) {
			$sys['catcode'] = $row['page_cat'];
			$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id']) : sed_url("page", "al=" . $row['page_alias']);
			$suggestion = new stdClass();
			$suggestion->id = $row['page_id'];
			$suggestion->title = $row['page_title'];
			$suggestion->value = $row['page_title'];
			$suggestion->url = $row['page_pageurl'];
			$suggestion->data = $row;
			$suggestions[] = $suggestion;
		}
	} else {
		$result = array('type' => 'error');
	}
}

$res = new stdClass;
$res->suggestions = $suggestions;

sed_sendheaders('application/json');
print json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;
