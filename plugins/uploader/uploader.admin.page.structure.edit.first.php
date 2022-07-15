<?PHP
/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.admin.page.structure.edit.first.php
Version=179
Updated=2021-jun-19
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

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$rextra = 'rthumb';

$imageuploader = sed_import('imageuploader', 'P', 'ARR');
if (count($imageuploader) > 0)
  {
    foreach ($imageuploader as $imagename)
      {  
      $rextra_arr[] = sed_import($imagename,'D','TXT');
      }      
    $_POST[$rextra] = implode(';', $rextra_arr);
  }


?>