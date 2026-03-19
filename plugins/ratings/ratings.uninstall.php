<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.uninstall.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

// Config and auth are removed by core (sed_plugin_uninstall) before this file is included.
if (!empty($sed_uninstall_drop_tables)) {
	global $cfg;
	$prefix = $cfg['sqldbprefix'];
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}rated");
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}ratings");
	$res .= "Ratings tables dropped.<br />";
} else {
	$res .= "Ratings plugin uninstalled. Ratings tables preserved.<br />";
}
