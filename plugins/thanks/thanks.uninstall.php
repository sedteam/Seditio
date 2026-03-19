<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.uninstall.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!empty($sed_uninstall_drop_tables)) {
	$prefix = $cfg['sqldbprefix'];
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}thanks");
	sed_extrafield_remove('users', 'thankscount');
	$res .= "Thanks table dropped and extrafield removed.<br />";
} else {
	$res .= "Thanks plugin uninstalled. Thanks table preserved.<br />";
}