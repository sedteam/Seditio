<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/admin/pfs.admin.menu.php
Version=185
Updated=2026-feb-14
Type=Module.admin
Author=Seditio Team
Description=PFS admin menu (sidebar and list open link)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// No sidebar item for PFS; only adminlink for the module list arrow (open → admin?m=pfs)
return array(
	'adminlink' => sed_url('admin', 'm=pfs')
);
