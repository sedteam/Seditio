<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=resizer/resizer.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Amro
Description=Image Resizer
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_RESIZER', TRUE);
$location = 'Resizer';
$z = 'resizer';

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

$filename = $_GET['file'];
	
$resized_filename = resize($filename);

if (is_readable($resized_filename))
	{
	header('Content-type: image');
	print file_get_contents($resized_filename);
	}

