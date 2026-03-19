<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.install.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}tags'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating tags dictionary table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS {$prefix}tags (
		tag varchar(255) NOT NULL,
		PRIMARY KEY (tag)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate}");
}

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}tag_references'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating tag_references table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS {$prefix}tag_references (
		tag varchar(255) NOT NULL,
		tag_item int(10) unsigned NOT NULL,
		tag_area varchar(64) NOT NULL DEFAULT 'pages',
		PRIMARY KEY (tag, tag_area, tag_item),
		KEY tag_area (tag_area),
		KEY tag_area_item (tag_area, tag_item),
		KEY tag (tag)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate}");
}

$res .= "Tags plugin tables installed.<br />";
