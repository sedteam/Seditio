<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.ajax.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=AjaxThanks
File=thanks.ajax
Hooks=ajax
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_thanks, $db_pages, $db_forum_posts, $db_com, $db_users, $cfg, $usr, $L, $sys;

$a = sed_import('a', 'G', 'ALP') ?: sed_import('a', 'P', 'ALP');
$ext = sed_import('ext', 'G', 'ALP') ?: sed_import('ext', 'P', 'ALP');
$item = (int)(sed_import('item', 'G', 'INT') ?: sed_import('item', 'P', 'INT'));

if ($a != 'thank' || empty($ext) || $item <= 0 || $usr['id'] <= 0) {
	sed_ajax_flush('<span class="text-muted">' . $L['thanks_err_item'] . '</span>', true);
	exit;
}

$ext_ok = ($ext == 'page' && sed_module_active('page')) || ($ext == 'forums' && sed_module_active('forums')) || ($ext == 'comments' && sed_plug_active('comments'));
if (!$ext_ok) {
	sed_ajax_flush('<span id="thanks-' . $ext . '-' . $item . '"><span class="post-thanked text-warning">' . sed_cc($L['thanks_err_item']) . '</span></span>', true);
	exit;
}

$touser = 0;
if ($ext == 'page') {
	$sql = sed_sql_query("SELECT page_ownerid FROM $db_pages WHERE page_id=" . (int)$item . " LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$touser = (int)$row['page_ownerid'];
	}
} elseif ($ext == 'forums') {
	$sql = sed_sql_query("SELECT fp_posterid FROM $db_forum_posts WHERE fp_id=" . (int)$item . " LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$touser = (int)$row['fp_posterid'];
	}
} elseif ($ext == 'comments') {
	$sql = sed_sql_query("SELECT com_authorid FROM $db_com WHERE com_id=" . (int)$item . " LIMIT 1");
	if ($row = sed_sql_fetchassoc($sql)) {
		$touser = (int)$row['com_authorid'];
	}
}

if ($touser <= 0) {
	sed_ajax_flush('<span id="thanks-' . $ext . '-' . $item . '"><span class="post-thanked text-warning">' . sed_cc($L['thanks_err_item']) . '</span></span>', true);
	exit;
}

$status = thanks_check($touser, $usr['id'], $ext, $item);

if ($status == THANKS_ERR_NONE) {
	thanks_add($touser, $usr['id'], $ext, $item);
	$html = sed_build_thanks($ext, $item, $touser, false, true);
	sed_ajax_flush($html, true);
	exit;
}

$err = $L['thanks_err_item'];
if ($status == THANKS_ERR_MAXDAY) $err = $L['thanks_err_maxday'];
if ($status == THANKS_ERR_MAXUSER) $err = $L['thanks_err_maxuser'];
if ($status == THANKS_ERR_SELF) $err = $L['thanks_err_self'];
sed_ajax_flush('<span id="thanks-' . $ext . '-' . $item . '"><span class="post-thanked text-warning">' . sed_cc($err) . '</span></span>', true);
exit;
