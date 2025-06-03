<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=admin.cache.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] = $L['adm_manage'];
$urlpaths[sed_url("admin", "m=cache")] = $L['adm_internalcache'];

$admintitle = $L['adm_internalcache'];

if ($a == 'purge') {
	sed_check_xg();
	sed_cache_clearall();
} elseif ($a == 'delete') {
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_cache WHERE c_name='$id'");
}

$sql = sed_sql_query("SELECT * FROM $db_cache WHERE 1 ORDER by c_name ASC");

$cachesize = 0;

$t = new XTemplate(sed_skinfile('admin.cache', false, true));

while ($row = sed_sql_fetchassoc($sql)) {
	$row['c_value'] = sed_cc($row['c_value']);
	$row['size'] = mb_strlen($row['c_value']);
	$cachesize += $row['size'];

	$t->assign(array(
		"CACHE_LIST_DELETE_URL" => sed_url("admin", "m=cache&a=delete&id=" . $row['c_name'] . "&" . sed_xg()),
		"CACHE_LIST_NAME" => $row['c_name'],
		"CACHE_LIST_EXPIRE" => ($row['c_expire'] - $sys['now']),
		"CACHE_LIST_SIZE" => $row['size'],
		"CACHE_LIST_VALUE" => ($a == 'showall') ? $row['c_value'] : sed_cutstring($row['c_value'], 80)
	));

	$t->parse("ADMIN_CACHE.CACHE_LIST");
}

$t->assign(array(
	"CACHE_REFRESH_URL" => sed_url("admin", "m=cache"),
	"CACHE_PURGE_URL" => sed_url("admin", "m=cache&a=purge&" . sed_xg()),
	"CACHE_SHOWALL_URL" => sed_url("admin", "m=cache&a=showall"),
	"CACHE_SIZE" => $cachesize
));

$t->assign("ADMIN_CACHE_TITLE", $admintitle);

$t->parse("ADMIN_CACHE");

$adminmain .= $t->text("ADMIN_CACHE");
