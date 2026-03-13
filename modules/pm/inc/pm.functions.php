<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/inc/pm.functions.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=PM module icons and helpers
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* PM icons are set in system/common.php (with isset protection for $L) */

/* TODO: implement universal function sed_pm_send($fromuserid, $fromname, $touserid, $subject, $body) for sending PM.
 * Used by: pm.send.php, plugins (e.g. thanks_notify_by_pm). Consolidates INSERT into db_pm + UPDATE user_newpm.
 */
