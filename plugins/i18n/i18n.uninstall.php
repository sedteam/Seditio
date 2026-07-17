<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.uninstall.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

$prefix = $cfg['sqldbprefix'];

if (!empty($sed_uninstall_drop_tables)) {
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}i18n_pages");
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}i18n_structure");
	$res .= "i18n translation tables dropped.<br />";
} else {
	$res .= "i18n plugin uninstalled. Translation tables preserved.<br />";
}
