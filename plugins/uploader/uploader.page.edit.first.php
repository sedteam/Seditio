<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/uploader/uploader.page.edit.first.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.page.edit.first
Hooks=page.edit.update.first
Tags=
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

$extraslot = $cfg['plugin']['uploader']['thumb_extra'];
$rpageextra = 'rpage' . $extraslot;

$imageuploader = sed_import($extraslot . '_imageuploader', 'P', 'ARR');
if (is_array($imageuploader) && count($imageuploader) > 0) {
  foreach ($imageuploader as $imagename) {
    $rpageextra_arr[] = sed_import($imagename, 'D', 'TXT');
  }
  $_POST[$rpageextra] = implode(';', $rpageextra_arr);
}
