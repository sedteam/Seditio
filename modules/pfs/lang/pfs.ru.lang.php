<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pfs/lang/pfs.ru.lang.php
Version=185
Updated=2026-feb-16
Type=Module.lang
Author=Seditio Team
Description=PFS module Russian language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_pfs'] = "Файловые архивы";

/* ====== Personal file space ====== */

$L['pfs_title'] = "Персональный Файловый Архив";
$L['pfs_filetoobigorext'] = "Загрузка не удалась. Возможно, этот файл слишком большой или расширение файла не поддерживается.";
$L['pfs_fileexists'] = "Загрузка прервана, файл с таким именем уже существует.";
$L['pfs_filelistempty'] = "Список пуст.";
$L['pfs_folderistempty'] = "Эта папка пуста.";
$L['pfs_totalsize'] = "Общий размер";
$L['pfs_maxspace'] = "Максимально доступный объем";
$L['pfs_maxsize'] = "Максимальный размер файла";
$L['pfs_filesintheroot'] = "Файл(ов) в корневом каталоге";
$L['pfs_filesinthisfolder'] = "Файл(ов) в этой папке";
$L['pfs_newfile'] = "Загрузить файл";
$L['pfs_newfolder'] = "Создать новую папку";
$L['pfs_editfolder'] = "Редактирование папки";
$L['pfs_editfile'] = "Редактирование файла";
$L['pfs_ispublic'] = "Публичная?";
$L['pfs_isgallery'] = "Галерея?";
$L['pfs_extallowed'] = "Допустимые расширения";

$L['pfs_insertasthumbnail'] = "Вставить как эскиз"; // New in v175
$L['pfs_insertasimage'] = "Вставить изображение"; // New in v175
$L['pfs_insertaslink'] = "Вставить ссылку на файл"; // New in v175
$L['pfs_multiuploading'] = "Мультизагрузка файлов"; // New in v175

$L['pfs_insertasvideo'] = "Вставить видео"; // New in v180

$L['pfs_setassample'] = "Образец";  // New in v150
$L['pfs_addlogo'] = "Добавить лого";  // New in v150
$L['pfs_resize'] = "Изменить размер, если больше чем %1\$s пикселей";  // New in v150
