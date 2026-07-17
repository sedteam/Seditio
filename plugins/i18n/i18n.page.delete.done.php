<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.page.delete.done.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=page.delete.done
File=i18n.page.delete.done
Hooks=page.delete.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $id, $db_i18n_pages;

$page_id = (int)$id;

if ($page_id > 0) {
	sed_sql_query("DELETE FROM $db_i18n_pages WHERE ipt_page_id = $page_id");
}
