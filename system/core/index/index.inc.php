<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/index/index.inc.php
Version=186
Updated=2026-sep-21
Type=Core
Author=Seditio Team
Description=Home page
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* === Hook === */
foreach (sed_getextplugins('index.first') as $pl) {
	include $pl;
}
/* ===== */

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('index', 'a');

$out['subdesc'] = (!empty($cfg['homemetadescription'])) ? $cfg['homemetadescription'] : $out['subdesc'];
$out['subkeywords'] = (!empty($cfg['homemetakeywords'])) ? $cfg['homemetakeywords'] : $out['subkeywords'];
$out['subtitle'] = (!empty($cfg['hometitle'])) ? $cfg['hometitle'] : $L['Home'];

$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('indextitle', $title_tags, $title_data);

/* === Hook === */
foreach (sed_getextplugins('index.main') as $pl) {
	include $pl;
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile('index');
$t = new XTemplate($mskin);

/* === Hook === */
foreach (sed_getextplugins('index.tags') as $pl) {
	include $pl;
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
