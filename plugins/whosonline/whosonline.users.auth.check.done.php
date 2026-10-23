<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/whosonline/whosonline.users.auth.check.done.php
Version=186
Updated=2026-oct-23
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=whosonline
Part=users.auth.check.done
File=whosonline.users.auth.check.done
Hooks=users.auth.check.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($db_online) && !empty($usr['ip'])) {
	sed_sql_query("DELETE FROM $db_online WHERE online_userid = -1 AND online_ip = '" . sed_sql_prep($usr['ip']) . "' LIMIT 1");
}
