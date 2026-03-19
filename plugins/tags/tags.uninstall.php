<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.uninstall.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

$prefix = $cfg['sqldbprefix'];

/*

sed_sql_query("DROP TABLE IF EXISTS {$prefix}tag_references");
sed_sql_query("DROP TABLE IF EXISTS {$prefix}tags");

*/