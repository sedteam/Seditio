<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/lang/trashcan.en.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['Trashcan'] = "Trash can";

$L['cfg_trash_prunedelay'] = array("Remove the items from the trash can after * days (Zero to keep forever)", "");
$L['cfg_trash_comment'] = array("Use the trash can for the comments", "");
$L['cfg_trash_forum'] = array("Use the trash can for the forums", "");
$L['cfg_trash_page'] = array("Use the trash can for the pages", "");
$L['cfg_trash_pm'] = array("Use the trash can for the private messages", "");
$L['cfg_trash_user'] = array("Use the trash can for the users", "");

$L['adm_help_trashcan'] = "Here are listed the items recently deleted by the users and moderators.<br />Note that restoring a forum topic will also restore all the posts that belongs to the topic.<br />And restoring a post in a deleted topic will restore the whole topic (if available) and all the child posts.<br />&nbsp;<br />Wipe : Delete the item forever.<br />Restore : Put the item back in the live database.";
