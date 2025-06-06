<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Part=Loader
File=ckeditor
Hooks=header.first,pfs.stndl,polls.stndl
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $usr, $db_smilies;

if ($usr['maingrp'] > 3) {

	/* ===== Load configuration Ckeditor ===== */

	$ckeditor_detect_lang = $cfg['plugin']['ckeditor']['ckeditor_detectlang'];
	$ckeditor_lang = ($ckeditor_detect_lang == "No") ? $cfg['plugin']['ckeditor']['ckeditor_lang'] : $usr['lang'];
	$ckeditor_skin = $cfg['plugin']['ckeditor']['ckeditor_skin'];
	$ckeditor_color_toolbar = $cfg['plugin']['ckeditor']['ckeditor_color_toolbar'];

	/* ===== Load smiles ===== */

	$sql2 = sed_sql_query("SELECT * FROM $db_smilies WHERE 1 ORDER BY smilie_order ASC");
	$smiley_path = "[";
	$smiley_descriptions = "[";
	while ($row = sed_sql_fetchassoc($sql2)) {
		$row['smilie_text'] = sed_cc($row['smilie_text']);
		$smiley_path .= "'" . $row['smilie_image'] . "',";
		$smiley_descriptions .= "'" . sed_cc($row['smilie_text'], ENT_QUOTES) . "',";
	}
	$pointpos_sp = mb_strrpos($smiley_path, ",") + 1;
	$smiley_path = mb_substr($smiley_path, 0, $pointpos_sp - 1);
	$pointpos_sd = mb_strrpos($smiley_descriptions, ",") + 1;
	$smiley_descriptions = mb_substr($smiley_descriptions, 0, $pointpos_sd - 1);
	$smiley_path .= "]";
	$smiley_descriptions .= "]";
	$ck_config = "sed_config.js" . "?" . sed_unique(5);

	$tmp = 'ckeditor_grp' . $usr['maingrp'];
	$ck_toolbar = (!empty($cfg['plugin']['ckeditor'][$tmp] && $cfg['plugin']['ckeditor'][$tmp] != 'Default')) ? "'" . $cfg['plugin']['ckeditor'][$tmp] . "'" : "textareas[i].getAttribute('data-editor')";

	/* ===== Init Ckeditor ===== */

	$init_ck = "
		var CkTextareas = Array(); CkTextareas['Micro'] = 150; CkTextareas['Basic'] = 200; CkTextareas['Extended'] = 400; CkTextareas['Full'] = 600;
		var toolbarHeight = 40; // Approximate height of the toolbar in pixels

		function ckeditorReplace() {
			var textareas = document.getElementsByTagName('textarea');
			for (var i = 0; i < textareas.length; i++) {
				var textarea = textareas[i];
				var editorType = textarea.getAttribute('data-editor');
				var rows = textarea.getAttribute('rows');
				var height;

				if (editorType && editorType !== 'noeditor' && CkTextareas[editorType] != undefined) {
					if (rows) {
						var lineHeight = 20; // Approximate line height in pixels
						height = rows * lineHeight + toolbarHeight;
					} else {
						height = CkTextareas[editorType];
					}

					CKEDITOR.timestamp='ABCDFGH';
					CKEDITOR.config.customConfig = '" . $ck_config . "';
					CKEDITOR.config.baseHref = '" . $sys['abs_url'] . "';
					CKEDITOR.replace(textarea, {
						toolbar: " . $ck_toolbar . ",
						skin: '" . $ckeditor_skin . "',
						language: '" . $ckeditor_lang . "',
						uiColor: '" . $ckeditor_color_toolbar . "',
						smiley_path: '/',
						smiley_images: " . $smiley_path . ",
						smiley_descriptions: " . $smiley_descriptions . ",
						height: height
					});
				}
			}
		}
		if (window.addEventListener) {
			window.addEventListener('load', ckeditorReplace, false);
		} else if (window.attachEvent) {
			window.attachEvent('onload', ckeditorReplace);
		} else {
			window.onload = ckeditorReplace;
		}
		";
	
	sed_add_javascript('plugins/ckeditor/lib/ckeditor.js?v=4', true);
	sed_add_javascript($init_ck);	
}
