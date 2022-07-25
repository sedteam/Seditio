<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.page.add.first.php
Version=179
Updated=2021-jun-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Part=page
File=uploader.page.add.first
Hooks=page.add.add.first
Tags=
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$extraslot = $cfg['plugin']['uploader']['thumb_extra'];
$newpageextra = 'newpage'.$extraslot;

$imageuploader = sed_import('imageuploader', 'P', 'ARR');
if (is_array($imageuploader) && count($imageuploader) > 0)
  {
    foreach ($imageuploader as $imagename)
      {  
      $newpageextra_arr[] = sed_import($imagename,'D','TXT');
      }      
    $_POST[$newpageextra] = implode(';', $newpageextra_arr);
  }


?>