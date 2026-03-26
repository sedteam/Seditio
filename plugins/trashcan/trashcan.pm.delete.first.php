<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.pm.delete.first.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=pm.delete.first
Hooks=pm.delete.first
File=trashcan.pm.delete.first
Order=1
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $L;

if (!empty($cfg['plugin']['trashcan']['trash_pm']) && !empty($row)) {
	sed_trash_put('pm', $L['Private_Messages'] . " #" . $id . " " . $row['pm_title'] . " (" . $row['pm_fromuser'] . ")", $id, $row);
}
