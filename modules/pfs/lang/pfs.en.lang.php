<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/lang/pfs.en.lang.php
Version=185
Updated=2026-feb-16
Type=Module.lang
Author=Seditio Team
Description=PFS module English language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_pfs'] = "PFS";

/* ====== Personal file space ====== */

$L['pfs_title'] = "My Personal File Space";
$L['pfs_filetoobigorext'] = "The upload failed, this file is too big maybe, or the extension is not allowed ?";
$L['pfs_fileexists'] = "The upload failed, there's already a file with this name ?";
$L['pfs_filelistempty'] = "List is empty.";
$L['pfs_folderistempty'] = "This folder is empty.";
$L['pfs_totalsize'] = "Total size";
$L['pfs_maxspace'] = "Maximum space allowed";
$L['pfs_maxsize'] = "Maximum size for a file";
$L['pfs_filesintheroot'] = "File(s) in the root";
$L['pfs_filesinthisfolder'] = "File(s) in this folder";
$L['pfs_newfile'] = "Upload a file :";
$L['pfs_newfolder'] = "Create a new folder :";
$L['pfs_editfolder'] = "Editing a folder";
$L['pfs_editfile'] = "Editing a file";
$L['pfs_ispublic'] = "Public?";
$L['pfs_isgallery'] = "Gallery?";
$L['pfs_extallowed'] = "Extensions allowed";

$L['pfs_insertasthumbnail'] = "Insert as thumbnail"; // New in v175
$L['pfs_insertasimage'] = "Insert as full size image"; // New in v175
$L['pfs_insertaslink'] = "Insert as a link to the file"; // New in v175
$L['pfs_multiuploading'] = "Multiple File Upload"; // New in v175

$L['pfs_insertasvideo'] = "Insert as video"; // New in v180

$L['pfs_setassample'] = "Set as sample";  // New in v150
$L['pfs_addlogo'] = "Add the logo";  // New in v150
$L['pfs_resize'] = "Resize if larger than %1\$s pixels";  // New in v150
