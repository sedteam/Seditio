<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.pfs.stndl.icons.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.pfs.stndl.icons
Hooks=pfs.stndl.icons
Tags=
Minlevel=0
Order=12
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$extraslot = $cfg['plugin']['uploader']['thumb_extra'];

if (in_array($pfs_extension, $cfg['gd_supported'])) {
	if (($c2 == "newpage" . $extraslot) || ($c2 == "rpage" . $extraslot) || ($c2 == "rthumb")) {
		$uploaderId = ($c2 == "rthumb") ? "rthumb_imageuploader" : $extraslot . "_imageuploader";
		$add_file = "<a href=\"javascript:upl_addimg('" . $cfg['th_dir'] . $pfs_file . "','" . $pfs_id . "','" . $pfs_file . "','" . $uploaderId . "');\" class=\"btn-icon\">" . $out['ic_pastefile'] . "</a>";
		$add_thumbnail = '';
		$add_image = '';
		$add_video = '';
		$pfs_icon = "<a href=\"javascript:upl_addimg('" . $cfg['th_dir'] . $pfs_file . "','" . $pfs_id . "','" . $pfs_file . "','" . $uploaderId . "');\"><img src=\"" . $cfg['th_dir'] . $pfs_file . "\" alt=\"" . $pfs_file . "\"></a>";
	}
}
