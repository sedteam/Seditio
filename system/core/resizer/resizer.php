<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=resizer/resizer.php
Version=178
Updated=2022-jun-12
Type=Core
Author=Amro
Description=Image Resizer
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_RESIZER', TRUE);
$location = 'Resizer';
$z = 'resizer';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

$filename = $_GET['file'];
	
$resized_filename = resize($filename);

if (is_readable($resized_filename))
	{
	header('Content-type: image');
	print file_get_contents($resized_filename);
	}
