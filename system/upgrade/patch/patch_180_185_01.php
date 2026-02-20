<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/upgrade/patch/patch_180_185_01.php
Version=185
Updated=2026-feb-20
Type=Core.patch
Author=Seditio Team
Description=Schema patch for v180->v185: add ct_path, ct_admin, pl_module, pl_version, pl_dependencies
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* Add columns required for v185 code to run on v180 database.
 * @ suppresses errors if columns/indexes already exist (idempotent). */

global $db_core, $db_plugins, $db_config;

@sed_sql_query("ALTER TABLE $db_core ADD COLUMN ct_path VARCHAR(255) NOT NULL DEFAULT ''");
@sed_sql_query("ALTER TABLE $db_core ADD COLUMN ct_admin TINYINT(1) UNSIGNED NOT NULL DEFAULT 0");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_module TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER pl_active");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_version VARCHAR(16) NOT NULL DEFAULT '0.0.0' AFTER pl_title");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_dependencies TEXT AFTER pl_version");
@sed_sql_query("ALTER TABLE $db_plugins ADD INDEX idx_type (pl_module)");
@sed_sql_query("ALTER TABLE $db_config ADD INDEX idx_config_load (config_owner, config_cat)");
