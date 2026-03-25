<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.admin.page.delete.done.php
Version=185
Type=Plugin
Description=Remove comments for deleted page (admin.page.delete.done)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=admin.page.delete.done
File=comments.admin.page.delete.done
Hooks=admin.page.delete.done
Order=20
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_com;

$code = sed_sql_prep($id2);
sed_sql_query("DELETE FROM $db_com WHERE com_code='" . $code . "'");
