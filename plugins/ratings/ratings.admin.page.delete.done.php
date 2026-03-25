<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.admin.page.delete.done.php
Version=185
Type=Plugin
Description=Remove ratings for deleted page (admin.page.delete.done)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Part=admin.page.delete.done
File=ratings.admin.page.delete.done
Hooks=admin.page.delete.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_ratings, $db_rated;

$code = sed_sql_prep($id2);
sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='" . $code . "'");
sed_sql_query("DELETE FROM $db_rated WHERE rated_code='" . $code . "'");
