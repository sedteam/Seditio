<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.admin.page.structure.edit.tags.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.admin.page.structure.edit.tags
Hooks=admin.page.structure.edit.tags
Tags=admin.page.tpl
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once("plugins/uploader/lang/uploader." . $usr['lang'] . ".lang.php");

$extraslot = 'rthumb';

//setting
$use_sortable = ($cfg['plugin']['uploader']['use_sortable'] == "yes") ? 'true' : 'false';
$use_dragndrop = ($cfg['plugin']['uploader']['use_dragndrop'] == "yes") ? 'true' : 'false';
$use_rotation = ($cfg['plugin']['uploader']['use_rotation'] == "yes") ? 'true' : 'false';
//$maximum_uploads = $cfg['plugin']['uploader']['maximum_uploads'];

$uploader = new XTemplate('plugins/uploader/uploader.tpl');

$page_thumbs_array = rtrim($row['structure_thumb']);
$preload_images = '[]';

if (!empty($page_thumbs_array)) {
	if ($page_thumbs_array[mb_strlen($page_thumbs_array) - 1] == ';') {
		$page_thumbs_array = mb_substr($page_thumbs_array, 0, -1);
	}
	$page_thumbs_array = explode(";", $page_thumbs_array);
	$preload_images_arr = array();
	foreach ($page_thumbs_array as $imgfile) {
		if ($imgfile && ($imgfile != '')) $preload_images_arr[] =  "'" . $imgfile . "'";
	}
	$preload_images = implode(',', $preload_images_arr);
	$preload_images = (count($preload_images_arr) > 0) ? "[" . $preload_images . "]" : "[]";
}

$uploader->assign(array(
	"UPLOADER_ATTACH_IMAGES" => $preload_images,
	"UPLOADER_USE_SORTABLE" => $use_sortable,
	"UPLOADER_USE_DRAGNDROP" => $use_dragndrop,
	"UPLOADER_USE_ROTATION" => $use_rotation,
	"UPLOADER_MAXIMUM_UPLOADS" => 1,
	"UPLOADER_USERID" => $usr['id'],
	"UPLOADER_ACTION" => 'savestructure',
	"UPLOADER_EXTRA" => $extraslot,
	"UPLOADER_ID" => $extraslot . "_imageuploader",
	"UPLOADER_ISMODAL" => ($cfg['enablemodal']) ? 1 : 0
));

$uploader->parse("UPLOADER");

$t->assign("STRUCTURE_UPDATE_THUMB", "<div id=\"uploader\"><div id=\"" . $extraslot . "_imageuploader\" sed_uploader=\"on\"></div></div>");

sed_add_javascript($uploader->text("UPLOADER"));
