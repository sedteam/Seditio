<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sedcaptcha/inc/sedcaptcha.functions.php
Version=185
Updated=2026-feb-26
Type=Plugin
Author=Seditio Team
Description=Captcha functions (image, generate, verify, session)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Counter captcha error message
 *
 * @param string $message Error message
 * @return string
 */
function sed_error_msg($message)
{
	if (isset($_SESSION[$_SERVER['REMOTE_ADDR']])) {
		$_SESSION[$_SERVER['REMOTE_ADDR']]++;
	} else {
		$_SESSION[$_SERVER['REMOTE_ADDR']] = 1;
	}
	return ($message);
}

/**
 * Generate captcha code
 *
 * @return string
 */
function sed_generate_code()
{
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$length = rand(4, 6);
	$numChars = strlen($chars);

	$str = '';
	for ($i = 0; $i < $length; $i++) {
		$str .= substr($chars, rand(1, $numChars) - 1, 1);
	}

	$array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
	srand((float) microtime() * 1000000);
	shuffle($array_mix);

	$result = implode("", $array_mix);
	sed_session_write($result);

	return $result;
}

/**
 * Generate field code
 *
 * @return string
 */
function sed_generate_field_code()
{
	$captcha_field = md5(md5(uniqid('', true) . date('His')));
	sed_session_field_write($captcha_field);
	return $captcha_field;
}

/**
 * Verify captcha code
 *
 * @return string
 */
function sed_verify_code()
{
	global $L;

	$captcha_value = $_SESSION['captcha_value'];
	$captcha_field = $_SESSION['captcha_field'];
	$answer_time = $_SESSION['answer_time'];

	if (isset($_SESSION[$_SERVER['REMOTE_ADDR']]) && $_SESSION[$_SERVER['REMOTE_ADDR']] >= 10)
		return sed_error_msg($L['captcha_error_many_incorrect']);

	if (!empty($captcha_value) && !empty($captcha_field) && !empty($answer_time)) {
		$current_time = strtotime(date('d-m-Y H:i:s'));

		if ($current_time - $answer_time < 6)
			return sed_error_msg($L['captcha_error_you_robot_or_too_fast']);
		if ($_POST[$captcha_field] == '')
			return sed_error_msg($L['captcha_error_go_bad_robot']);

		if (md5(md5($_POST[$captcha_field])) == $captcha_value) {
			$ok = 1;
		} else {
			return sed_error_msg($L['captcha_error_incorrect']);
		}
	} else return sed_error_msg($L['captcha_error_hacker_go_home']);
}

/**
 * Captcha value & answer time write to $_SESSION
 *
 * @param string $code Code
 */
function sed_session_write($code)
{
	$_SESSION['captcha_value'] = md5(md5($code));
	$_SESSION['answer_time'] = strtotime(date('d-m-Y H:i:s'));
}

/**
 * Captcha field name write to $_SESSION
 *
 * @param string $code Code
 */
function sed_session_field_write($code)
{
	$_SESSION['captcha_field'] = $code;
}

/**
 * Build & display captcha image
 *
 * @param string $code Captcha code
 * @return mixed
 */
function sed_captcha_image($code)
{
	global $cfg;

	if (empty($cfg['font_dir'])) {
		$cfg['font_dir'] = (defined('SED_ROOT') ? SED_ROOT . '/' : '') . 'datas/fonts/';
	}

	$image = imagecreatetruecolor(150, 70);
	imagesetthickness($image, 2);

	$background_color = imagecolorallocate($image, rand(220, 255), rand(220, 255), rand(220, 255));
	imagefill($image, 0, 0, $background_color);

	$linenum = rand(3, 5);
	for ($i = 0; $i < $linenum; $i++) {
		$color = imagecolorallocate($image, rand(0, 150), rand(0, 100), rand(0, 150));
		imageline($image, rand(0, 150), rand(1, 70), rand(20, 150), rand(1, 70), $color);
	}

	$font_arr = array_values(array_diff(scandir($cfg['font_dir']), array('.', '..')));
	$font_size = rand(20, 30);
	$x = rand(0, 10);

	for ($i = 0; $i < strlen($code); $i++) {
		$x += 20;
		$letter = substr($code, $i, 1);
		$color = imagecolorallocate($image, rand(0, 200), 0, rand(0, 200));
		$current_font = rand(0, sizeof($font_arr) - 1);

		imagettftext($image, $font_size, rand(-10, 10), $x, rand(50, 55), $color, $cfg['font_dir'] . $font_arr[$current_font], $letter);
	}

	$pixels = rand(2000, 4000);
	for ($i = 0; $i < $pixels; $i++) {
		$color = imagecolorallocate($image, rand(0, 200), rand(0, 200), rand(0, 200));
		imagesetpixel($image, rand(0, 150), rand(0, 150), $color);
	}

	for ($i = 0; $i < $linenum; $i++) {
		$color = imagecolorallocate($image, rand(0, 255), rand(0, 200), rand(0, 255));
		imageline($image, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
	}

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);
}
