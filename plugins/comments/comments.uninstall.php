<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.uninstall.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

// Config and auth are removed by core (sed_plugin_uninstall) before this file is included.
// Do not drop table to preserve data: DROP TABLE commented out
// sed_sql_query("DROP TABLE IF EXISTS " . $db_com);
