<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/trashcan.forums.posts.topic.delete.first.php
Version=185
Updated=2026-mar-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=trashcan
Part=forums.posts.topic.delete.first
Hooks=forums.posts.topic.delete.first
File=trashcan.forums.posts.topic.delete.first
Order=1
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $L;

if (!empty($cfg['plugin']['trashcan']['trash_forum']) && !empty($row)) {
	sed_trash_put('forumtopic', $L['Topic'] . " #" . $q . " (no post left)", "q" . $q, $row);
}
