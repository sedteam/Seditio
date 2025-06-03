<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=go/go.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Amro
Description=External url redirect & check referer
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_GO', TRUE);
$location = 'Extredirect';
$z = 'extredirect';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if (!empty($ref) && mb_strpos($ref, $sys['domain']) !== FALSE) {
	$url = $_GET['url'];
	if (sed_valid_base64($url)) {
		$url = base64_decode($url);
	}
	$url = sed_addhttp($url);
	header("Location: $url");
	exit();
} else {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $sys['abs_url']);
	exit();
}
