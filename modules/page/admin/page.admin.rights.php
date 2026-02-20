<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/page/admin/page.admin.rights.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=Page rights group in admin rights (per-group)
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$rows = array();
$sql3 = sed_sql_query("SELECT a.*, u.user_name, s.structure_id, s.structure_path, s.structure_title FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_structure AS s ON s.structure_code=a.auth_option
	WHERE auth_groupid='$g' AND auth_code='page'
	ORDER BY s.structure_path ASC, a.auth_option ASC");

while ($row = sed_sql_fetcharray($sql3)) {
	if ($row['auth_option'] === 'a' || empty($row['structure_id'])) {
		$link = sed_url("admin", "m=page&mn=structure");
		$title = ($row['auth_option'] === 'a') ? (isset($L['Pages']) ? $L['Pages'] : 'Pages') . ' (any)' : $row['auth_option'];
	} else {
		$link = sed_url("admin", "m=page&mn=structure&n=options&id=" . (int)$row['structure_id'] . "&" . sed_xg());
		$title = !empty($row['structure_title']) ? $row['structure_title'] : (!empty($row['structure_path']) ? $row['structure_path'] : $row['auth_option']);
	}
	$rows[] = array('row' => $row, 'title' => $title, 'link' => $link);
}

return array(
	'title' => isset($L['Pages']) ? $L['Pages'] : 'Pages',
	'code'  => 'page',
	'rows'  => $rows
);
