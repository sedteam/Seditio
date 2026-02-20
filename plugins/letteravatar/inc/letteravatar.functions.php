<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/letteravatar/inc/letteravatar.functions.php
Version=185
Updated=2026-feb-16
Type=Plugin
Author=Seditio Team
Description=Letter avatar helper functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Convert hex value to rgb array (used by letter avatar only).
 *
 * @param string $colour Hex code
 * @return array|bool RGB code
 */
function letteravatar_hextorgb($colour)
{
	if ($colour[0] == '#') {
		$colour = substr($colour, 1);
	}
	if (strlen($colour) == 6) {
		list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
	} elseif (strlen($colour) == 3) {
		list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
	} else {
		return false;
	}
	$r = hexdec($r);
	$g = hexdec($g);
	$b = hexdec($b);
	return array('r' => $r, 'g' => $g, 'b' => $b);
}

/**
 * Get center position on image for TTF text (used by letter avatar only).
 *
 * @param GdImage|resource $image
 * @param string $text
 * @param string $font
 * @param float $size
 * @param float $angle
 * @return array Position
 */
function letteravatar_image_ttf_center($image, $text, $font, $size, $angle = 8)
{
	$xi = imagesx($image);
	$yi = imagesy($image);
	$box = imagettfbbox($size, $angle, $font, $text);
	$xr = abs(max($box[2], $box[4])) + 5;
	$yr = abs(max($box[5], $box[7]));
	$x = intval(($xi - $xr) / 2);
	$y = intval(($yi + $yr) / 2);
	return array($x, $y);
}

/**
 * Generate letter avatar image from text (first letters of words).
 *
 * @param string $text User name or text
 * @param int $uid User ID
 * @param int $fontSize Font size
 * @param int $imgWidth Image width
 * @param int $imgHeight Image height
 * @return array status, image, imagepath
 */
function letteravatar_gen_letteravatar($text, $uid, $fontSize, $imgWidth, $imgHeight)
{
	global $cfg;

	$font = SED_ROOT . "/" . $cfg['font_dir'] . 'calibri.ttf';

	$words = explode(" ", $text);
	$text = "";
	foreach ($words as $w) {
		$text .= mb_substr($w, 0, 1);
	}

	$fileName = $uid . '-avatar.jpg';
	$textColor = '#FFF';
	$textColor = letteravatar_hextorgb($textColor);

	if (file_exists($cfg['av_dir'] . $fileName)) {
		return array('status' => TRUE, 'image' => $fileName, 'imagepath' => $cfg['av_dir'] . $fileName);
	}

	$im = imagecreatetruecolor($imgWidth, $imgHeight);
	$textColor = imagecolorallocate($im, $textColor['r'], $textColor['g'], $textColor['b']);

	$colorCode = array("#56aad8", "#61c4a8", "#d3ab92", "#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22", "#e74c3c", "#f39c12", "#d35400", "#c0392b", "#7f8c8d");
	$backgroundColor = letteravatar_hextorgb($colorCode[rand(0, count($colorCode) - 1)]);
	$backgroundColor = imagecolorallocate($im, $backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);

	imagefill($im, 0, 0, $backgroundColor);
	list($x, $y) = letteravatar_image_ttf_center($im, $text, $font, $fontSize);
	imagettftext($im, $fontSize, 0, $x, $y, $textColor, $font, $text);

	if (imagejpeg($im, $cfg['av_dir'] . $fileName, 90)) {
		imagedestroy($im);
		return array('status' => TRUE, 'image' => $fileName, 'imagepath' => $cfg['av_dir'] . $fileName);
	}
	return array('status' => FALSE);
}

/**
 * Autogenerate letter avatar for user and update DB/PFS.
 *
 * @param int $uid User ID
 * @return array|bool status result (array with status, image, imagepath or false)
 */
function letteravatar_autogen($uid)
{
	global $cfg, $db_pfs, $db_users;

	$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='" . (int)$uid . "' LIMIT 1");
	if (sed_sql_numrows($sql) == 0) {
		return FALSE;
	}

	$urr = sed_sql_fetchassoc($sql);
	$gen_avatar = letteravatar_gen_letteravatar($urr['user_name'], $uid, (int)$cfg['av_maxy'] / 2, $cfg['av_maxx'], $cfg['av_maxy']);

	if ($gen_avatar['status']) {
		$avatarpath = $gen_avatar['imagepath'];
		$avatar = $gen_avatar['image'];
		$dotpos = mb_strrpos($avatar, ".") + 1;
		$f_extension = mb_strtolower(mb_substr($avatar, $dotpos, 5));
		$uav_size = filesize($avatarpath);
		$avatarpath_sql = sed_sql_prep($avatarpath);
		$avatar_sql = sed_sql_prep($avatar);
		sed_sql_query("UPDATE $db_users SET user_avatar='$avatarpath_sql' WHERE user_id='" . (int)$urr['user_id'] . "'");
		sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='$avatar_sql'");
		sed_sql_query("INSERT INTO $db_pfs (pfs_userid, pfs_file, pfs_extension, pfs_folderid, pfs_desc, pfs_size, pfs_count) VALUES (" . (int)$urr['user_id'] . ", '$avatar_sql', '" . sed_sql_prep($f_extension) . "', -1, '', " . (int)$uav_size . ", 0)");
		@chmod($avatarpath, 0666);
	}

	return $gen_avatar;
}
