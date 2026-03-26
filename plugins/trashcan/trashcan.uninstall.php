<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.uninstall.php
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
	sed_sql_query("DROP TABLE IF EXISTS " . $cfg['sqldbprefix'] . "trash");
	$res .= "Trash table dropped.<br />";
} else {
	$res .= "Trash can plugin uninstalled. Trash table preserved (restore from backup if you removed the plugin by mistake).<br />";
}
