<?php

require_once('../system/functions.image.php');

$filename = $_GET['file'];
$token = $_GET['token'];
	
$resized_filename =  resize($filename);

if(is_readable($resized_filename))
{
	header('Content-type: image');
	print file_get_contents($resized_filename);
}

