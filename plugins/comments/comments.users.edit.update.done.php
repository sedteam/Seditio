<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.users.edit.update.done.php
Version=185
Type=Plugin
Description=Sync com_author when user name changes (users.edit.update.done)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=users.edit.update.done
File=comments.users.edit.update.done
Hooks=users.edit.update.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!sed_plug_active('comments')) {
	return;
}

if (!isset($rusername, $urr['user_name']) || $rusername === $urr['user_name']) {
	return;
}

global $db_com;

$oldname = sed_sql_prep($urr['user_name']);
$newname = sed_sql_prep($rusername);
sed_sql_query("UPDATE $db_com SET com_author='" . $newname . "' WHERE com_author='" . $oldname . "'");
