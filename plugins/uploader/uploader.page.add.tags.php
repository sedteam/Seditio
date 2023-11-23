<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.page.add.tags.php
Version=179
Updated=2021-jun-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.page.add.tags
Hooks=page.add.tags
Tags=page.add.tpl
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

require_once("plugins/uploader/lang/uploader." . $usr['lang'] . ".lang.php");

$extraslot = $cfg['plugin']['uploader']['thumb_extra'];
$newpageextra = 'newpage' . $extraslot;

//setting
$use_sortable = ($cfg['plugin']['uploader']['use_sortable'] == "yes") ? 'true' : 'false';
$use_dragndrop = ($cfg['plugin']['uploader']['use_dragndrop'] == "yes") ? 'true' : 'false';
$use_rotation = ($cfg['plugin']['uploader']['use_rotation'] == "yes") ? 'true' : 'false';
$maximum_uploads = $cfg['plugin']['uploader']['maximum_uploads'];

$uploader = new XTemplate('plugins/uploader/uploader.tpl');

$page_thumbs_array = isset($$newpageextra) ? trim($$newpageextra) : '';
$preload_images = '';

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
	$preload_images = (count($preload_images_arr) > 0) ? "sed_uploader_attach_images: [" . $preload_images . "]," : "";
}

$uploader->assign(array(
	"UPLOADER_PRELOAD_IMAGES" => $preload_images,
	"UPLOADER_PRELOAD_USE_SORTABLE" => $use_sortable,
	"UPLOADER_PRELOAD_USE_DRAGNDROP" => $use_dragndrop,
	"UPLOADER_PRELOAD_USE_ROTATION" => $use_rotation,
	"UPLOADER_PRELOAD_MAXIMUM_UPLOADS" => $maximum_uploads,
	"UPLOADER_PRELOAD_USERID" => $usr['id'],
	"UPLOADER_PRELOAD_ACTION" => 'newpage',
	"UPLOADER_PRELOAD_EXTRA" => $newpageextra,
	"UPLOADER_PRELOAD_ISMODAL" => ($cfg['enablemodal']) ? 1 : 0
));

$uploader->parse("UPLOADER");

$t->assign("PAGEADD_FORM_" . mb_strtoupper($extraslot), "<div id=\"uploader\"><div id=\"imageuploader\" sed_uploader=\"on\"></div></div>");

$out['uploader_footer'] = "<script src=\"plugins/uploader/js/uploader.js\" type=\"text/javascript\"></script>";
$out['uploader_footer'] .= $uploader->text("UPLOADER"); //in footer

$out['uploader_footer_admin'] = "<script src=\"plugins/uploader/js/uploader.js\" type=\"text/javascript\"></script>";
$out['uploader_footer_admin'] .= $uploader->text("UPLOADER"); //in footer
