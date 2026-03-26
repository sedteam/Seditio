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

global $db_core, $db_plugins, $db_config, $db_menu;

@sed_sql_query("ALTER TABLE $db_core ADD COLUMN ct_path VARCHAR(255) NOT NULL DEFAULT ''");
@sed_sql_query("ALTER TABLE $db_core ADD COLUMN ct_admin TINYINT(1) UNSIGNED NOT NULL DEFAULT 0");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_module TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER pl_active");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_version VARCHAR(16) NOT NULL DEFAULT '0.0.0' AFTER pl_title");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_dependencies TEXT AFTER pl_version");
@sed_sql_query("ALTER TABLE $db_plugins ADD COLUMN pl_lock TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER pl_active");
@sed_sql_query("ALTER TABLE $db_plugins ADD INDEX idx_type (pl_module)");
@sed_sql_query("ALTER TABLE $db_config ADD INDEX idx_config_load (config_owner, config_cat)");
@sed_sql_query("ALTER TABLE $db_menu ADD COLUMN menu_cssclass varchar(255) NOT NULL DEFAULT ''");

/* Fill ct_path and ct_admin so module functions (e.g. users/inc/users.functions.php) load correctly */
$core_paths = array(
	'admin'   => array('path' => 'system/core/admin/',   'admin' => 1),
	'forums'  => array('path' => 'modules/forums/',      'admin' => 1),
	'index'   => array('path' => 'system/core/index/',   'admin' => 0),
	'message' => array('path' => 'system/core/message/', 'admin' => 0),
	'page'    => array('path' => 'modules/page/',        'admin' => 1),
	'pfs'     => array('path' => 'modules/pfs/',         'admin' => 1),
	'plug'    => array('path' => 'system/core/plug/',    'admin' => 1),
	'pm'      => array('path' => 'modules/pm/',          'admin' => 1),
	'polls'   => array('path' => 'modules/polls/',       'admin' => 1),
	'users'   => array('path' => 'modules/users/',       'admin' => 1),
	'gallery' => array('path' => 'modules/gallery/',     'admin' => 1),
	'dic'     => array('path' => 'system/core/',         'admin' => 1),
	'menu'    => array('path' => 'system/core/',         'admin' => 1),
	'comments'=> array('path' => 'system/core/',         'admin' => 1),
	'ratings' => array('path' => 'system/core/',         'admin' => 1),
);
foreach ($core_paths as $code => $data) {
	@sed_sql_query("UPDATE $db_core SET ct_path='" . sed_sql_prep($data['path']) . "', ct_admin=" . (int)$data['admin'] . " WHERE ct_code='" . sed_sql_prep($code) . "'");
}
