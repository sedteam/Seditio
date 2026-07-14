<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.list.fetch.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=list.fetch
File=i18n.list.fetch
Hooks=list.fetch
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $c, $row2, $sed_cat, $i18n_langs;

if (empty($i18n_langs) || empty($usr['lang']) || $usr['lang'] == $cfg['defaultlang']) {
	return;
}

if ($c != 'all' && in_array($usr['lang'], $i18n_langs)) {
	if (!empty($sed_cat[$c]['text'])) {
		$row2['structure_text'] = $sed_cat[$c]['text'];
	}
	if (!empty($sed_cat[$c]['title'])) {
		$row2['structure_title'] = $sed_cat[$c]['title'];
	}
	if (!empty($sed_cat[$c]['desc'])) {
		$row2['structure_desc'] = $sed_cat[$c]['desc'];
	}
}
