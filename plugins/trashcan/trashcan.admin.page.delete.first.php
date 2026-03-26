<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.admin.page.delete.first.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=admin.page.delete.first
Hooks=admin.page.delete.first
File=trashcan.admin.page.delete.first
Order=1
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $L;

if (!empty($cfg['plugin']['trashcan']['trash_page']) && !empty($row)) {
	sed_trash_put('page', $L['Page'] . " #" . $id . " " . $row['page_title'], $id, $row);
}
