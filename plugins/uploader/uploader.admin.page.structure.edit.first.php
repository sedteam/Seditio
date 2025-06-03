<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.admin.page.structure.edit.first.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.admin.page.structure.edit.first
Hooks=admin.page.structure.edit.first
Tags=
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

$extraslot = 'rthumb';

$imageuploader = sed_import($extraslot . '_imageuploader', 'P', 'ARR');
if (is_array($imageuploader) && count($imageuploader) > 0) {
  foreach ($imageuploader as $imagename) {
    $rextra_arr[] = sed_import($imagename, 'D', 'TXT');
  }
  $_POST[$extraslot] = implode(';', $rextra_arr);
}
