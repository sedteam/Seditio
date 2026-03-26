<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.admin.home.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=admin.home.first
Hooks=admin.home.first
File=trashcan.admin.home
Order=5
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $sys, $db_trash;

if (empty($cfg['plugin']['trashcan']['trash_prunedelay']) || (int)$cfg['plugin']['trashcan']['trash_prunedelay'] <= 0) {
	return;
}

$trash_prunedelay = (int)$cfg['plugin']['trashcan']['trash_prunedelay'];
$timeago = $sys['now_offset'] - ($trash_prunedelay * 86400);
$sqltmp = sed_sql_query("DELETE FROM $db_trash WHERE tr_date<$timeago");
$deleted = sed_sql_affectedrows();
if ($deleted > 0) {
	sed_log($deleted . ' old item(s) removed from the trashcan, older than ' . $trash_prunedelay . ' days', 'adm');
}
