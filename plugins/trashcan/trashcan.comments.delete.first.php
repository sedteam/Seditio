<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.comments.delete.first.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=comments.delete.first
Hooks=comments.delete.first
File=trashcan.comments.delete.first
Order=1
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $L;

if (empty($cfg['plugin']['trashcan']['trash_comment']) || empty($order) || !is_array($rows_by_id) || !is_array($path)) {
	return;
}

foreach ($order as $cid) {
	if (!isset($rows_by_id[$cid], $path[$cid])) {
		continue;
	}
	$r = $rows_by_id[$cid];
	sed_trash_put('comment', $L['Comment'] . " #" . $cid . " (" . $r['com_author'] . ")", $path[$cid], $r);
}
