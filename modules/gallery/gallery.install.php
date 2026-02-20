<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/gallery/gallery.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=Gallery install script
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Gallery has no dedicated tables; it uses PFS (pfs_folders, pfs). Core entry, config and auth
// are created by sed_module_install() when the module is installed from Admin → Modules.
