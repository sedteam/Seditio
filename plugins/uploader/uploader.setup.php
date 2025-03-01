<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/uploader/uploader.setup.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=uploader
Name=Uploader
Description=Uploader plugin
Version=3.0
Date=2025-mar-01
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=0
Lock_guests=RW12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
thumb_extra=01:select:thumb,thumbs:thumb:Select use Thumb extra field
pfs_delete=02:select:yes,no:no:Delete files physically from a PFS
use_sortable=03:select:yes,no:yes:If set to yes, the sorting setting will be enabled.
use_dragndrop=04:select:yes,no:yes:If set to yes, the Drag & Drop setting will be enabled.
use_rotation=05:select:yes,no:yes:If set to yes, the image rotation setting will be enabled.
maximum_uploads=08:string::100:The maximum number of files that can be uploaded at a time.
buildfolder=015:select:yes,no:no:Create a folder with the name of the month.
buildfilename=016:select:timestamp,autoincrement:autoincrement:Timestamp in the file name or mask with autoincrement
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
