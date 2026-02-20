<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/admin/forums.admin.rights.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Forums rights group in admin rights (per-group)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

if (!function_exists('sed_load_forum_structure')) {
	return array('title' => '', 'code' => 'forums', 'rows' => array());
}

$rows = array();
$sql2 = sed_sql_query("SELECT a.*, u.user_name, f.fs_id, f.fs_title, f.fs_category FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_forum_sections AS f ON f.fs_id=a.auth_option
	LEFT JOIN $db_forum_structure AS n ON n.fn_code=f.fs_category
	WHERE auth_groupid='$g' AND auth_code='forums'
	ORDER BY fn_path ASC, fs_order ASC, fs_title ASC");

while ($row = sed_sql_fetchassoc($sql2)) {
	$link = sed_url("admin", "m=forums&n=edit&id=" . $row['auth_option']);
	$section_title = (isset($row['fs_title']) && $row['fs_title'] !== '') ? sed_cutstring($row['fs_title'], 24) : (($row['auth_option'] == 'a') ? (isset($L['Forums']) ? $L['Forums'] : 'Forums') : '');
	$title = sed_build_forums($row['fs_id'], $section_title, sed_cutstring(isset($row['fs_category']) ? $row['fs_category'] : '', 32), FALSE);
	$rows[] = array('row' => $row, 'title' => $title, 'link' => $link);
}

return array(
	'title' => isset($L['Forums']) ? $L['Forums'] : 'Forums',
	'code'  => 'forums',
	'rows'  => $rows
);
