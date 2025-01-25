<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/functions.image.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Amro
Description=Image Functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$cfg['allowed_extentions'] = array('png', 'gif', 'jpg', 'jpeg', 'ico');

$cfg['watermark_offset_x'] = 8;
$cfg['watermark_offset_y'] = 8;

$cfg['images_sharpen'] = 0;
$cfg['use_imagick'] = true;
$cfg['quality'] = 85;

function resize_image($filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false)
{
	global $cfg;

	$resized_filename = sed_add_resize_params($filename, 'resize', $width, $height, $set_watermark, $use_webp);
	return $cfg['res_dir'] . $resized_filename;
}

function crop_image($filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false)
{
	global $cfg;

	$resized_filename = sed_add_resize_params($filename, 'crop', $width, $height, $set_watermark, $use_webp);
	return $cfg['res_dir'] . $resized_filename;
}

/**
 * Create preview images
 * @param $ filename image file (without file path)
 * @return string preview file name
 */
function sed_resize($filename)
{
	global $cfg;

	// Picture folder paths
	$originals_dir = SED_ROOT . '/' . $cfg['pfs_dir'];
	$preview_dir = SED_ROOT . '/' . $cfg['res_dir'];

	list($original_file, $type, $width, $height, $set_watermark, $use_webp) = sed_get_resize_params($filename);
	$size = $width . 'x' . $height;

	if (!is_array($cfg['available_image_sizes'])) {
		$cfg['available_image_sizes'] = (!empty($cfg['available_image_sizes'])) ? explode('|', $cfg['available_image_sizes']) : array();
	}

	$check_ais = (count($cfg['available_image_sizes']) > 0) ? in_array($size, $cfg['available_image_sizes']) : TRUE;

	if (!file_exists($originals_dir . $original_file) || empty($original_file) || !$check_ais) {
		header("HTTP/1.1 404 Not Found");
		exit;
	}

	$resized_file = sed_add_resize_params($original_file, $type, $width, $height, $set_watermark, $use_webp);

	$watermark_offset_x = $cfg['watermark_offset_x'];
	$watermark_offset_y = $cfg['watermark_offset_y'];
	$watermark_position = $cfg['gallery_logopos'];

	$sharpen = min(100, $cfg['images_sharpen']) / 100;
	$watermark_transparency = min(100, $cfg['gallery_logotrsp']);

	if (!empty($cfg['gallery_logofile']) && $set_watermark) {
		$watermark = (strpos($cfg['gallery_logofile'], "/") == 0) ? SED_ROOT . $cfg['gallery_logofile'] : SED_ROOT . '/' . $cfg['gallery_logofile'];
	}

	$watermark  = (!empty($watermark) && $set_watermark && is_file($watermark)) ? $watermark : null;

	if (class_exists('Imagick') && $cfg['use_imagick']) {
		sed_image_constrain_imagick(
			$originals_dir . $original_file,
			$preview_dir . $resized_file,
			$type,
			$width,
			$height,
			$watermark,
			$watermark_offset_x,
			$watermark_offset_y,
			$watermark_transparency,
			$watermark_position,
			$sharpen,
			$use_webp
		);
	} else {
		sed_image_constrain_gd(
			$originals_dir . $original_file,
			$preview_dir . $resized_file,
			$type,
			$width,
			$height,
			$watermark,
			$watermark_offset_x,
			$watermark_offset_y,
			$watermark_transparency,
			$watermark_position,
			$use_webp
		);
	}

	return $preview_dir . $resized_file;
}

/**
 * @param $filename
 * @param string $type
 * @param int $width
 * @param int $height
 * @param bool $set_watermark
 * @return string
 */
function sed_add_resize_params($filename, $type = '', $width = 0, $height = 0, $set_watermark = false, $use_webp = false)
{
	$resized_filename = '';
	if (!empty($filename)) {
		if ('.' != ($dirname = pathinfo($filename, PATHINFO_DIRNAME))) {
			$file = $dirname . '/' . pathinfo($filename, PATHINFO_FILENAME);
		} else {
			$file = pathinfo($filename, PATHINFO_FILENAME);
		}

		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if ($width > 0 || $height > 0) {
			$resized_filename = $file . '.' . $type . ($width > 0 ? $width : '') . 'x' . ($height > 0 ? $height : '') . ($set_watermark ? 'w' : '') . '.' . $ext . ($use_webp ? '.webp' : '');
		} else {
			// TODO fix this option does not work now
			$resized_filename = $file . '.' . $type . ($set_watermark ? 'w' : '') . '.' . $ext . ($use_webp ? '.webp' : '');
		}
	}
	return $resized_filename;
}

/**
 * @param string $filename
 * @return array|false
 */
function sed_get_resize_params($filename)
{
	// Determining the resize parameters
	if (!preg_match('/(.+)\.(resize|crop)?([0-9]*)x([0-9]*)(w)?\.([^\.]+)(\.webp)?$/', $filename, $matches)) {
		return false;
	}

	$file = $matches[1];                    // the name of the requested file
	$type = $matches[2];                    // resize or crop
	$width = $matches[3];                   // width of the future image
	$height = $matches[4];                  // height of the future image
	$set_watermark = $matches[5] == 'w';    // whether to put a watermark
	$ext = $matches[6];                     // file extension
	$use_webp = !empty($matches[7]) ? true : false;

	return array($file . '.' . $ext, $type, $width, $height, $set_watermark, $use_webp);
}

/**
 * Create previews using gd
 *
 * @param string $src_file source file
 * @param string $dst_file result file
 * @param string $type
 * @param int $max_w maximum width
 * @param int $max_h maximum height
 * @param string $watermark
 * @param int $watermark_offset_x
 * @param int $watermark_offset_y
 * @param int $watermark_opacity
 * @return bool
 */
function sed_image_constrain_gd(
	$src_file,
	$dst_file,
	$type,
	$max_w,
	$max_h,
	$watermark = null,
	$watermark_offset_x = 0,
	$watermark_offset_y = 0,
	$watermark_opacity = 100,
	$watermark_postition = '',
	$use_webp = false
) {

	global $cfg;

	// todo put into settings
	$quality = (!empty($cfg['th_jpeg_quality'])) ? $cfg['th_jpeg_quality'] : $cfg['quality'];

	// Source image parameters
	list($src_w, $src_h, $src_type) = array_values(getimagesize($src_file));
	$src_type = image_type_to_mime_type($src_type);

	if (empty($src_w) || empty($src_h) || empty($src_type)) {
		return false;
	}

	if ($dst_file) {
		$directory = dirname($dst_file);
		if (!is_dir($directory)) {
			@mkdir($directory, 0777, true);
		}
	}

	// Do I need to crop?
	if (!$watermark && ($src_w <= $max_w) && ($src_h <= $max_h) && $type == 'resize') {
		// No - just copy the file
		if (!copy($src_file, $dst_file)) {
			return false;
		}
		return true;
	}

	// Reading the image
	switch ($src_type) {
		case 'image/jpeg':
			$src_img = imageCreateFromJpeg($src_file);
			break;
		case 'image/gif':
			$src_img = imageCreateFromGif($src_file);
			break;
		case 'image/png':
			$src_img = imageCreateFromPng($src_file);
			imagealphablending($src_img, true);
			break;
		default:
			return false;
	}

	if (empty($src_img)) {
		return false;
	}

	// Preview sizes at proportional reduction
	@list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type);

	$src_colors = imagecolorstotal($src_img);

	// create destination image (indexed, if possible)
	if ($src_colors > 0 && $src_colors <= 256) {
		$dst_img = imagecreate($dst_w, $dst_h);
	} else {
		$dst_img = imagecreatetruecolor($dst_w, $dst_h);
	}

	if (empty($dst_img)) {
		return false;
	}

	$transparent_index = imagecolortransparent($src_img);
	if ($transparent_index >= 0 && $transparent_index < imagecolorstotal($src_img)) {
		$t_c = imagecolorsforindex($src_img, $transparent_index);
		$transparent_index = imagecolorallocate($dst_img, $t_c['red'], $t_c['green'], $t_c['blue']);
		if ($transparent_index === false) {
			return false;
		}
		if (!imagefill($dst_img, 0, 0, $transparent_index)) {
			return false;
		}
		imagecolortransparent($dst_img, $transparent_index);
	} // or preserve alpha transparency for png
	elseif ($src_type === 'image/png') {
		if (!imagealphablending($dst_img, false)) {
			return false;
		}
		$transparency = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
		if (false === $transparency) {
			return false;
		}
		if (!imagefill($dst_img, 0, 0, $transparency)) {
			return false;
		}
		if (!imagesavealpha($dst_img, true)) {
			return false;
		}
	}

	// re-sample the image with new sizes
	if (!imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h)) {
		return false;
	}

	if ($type == 'crop') {
		$x0 = ($dst_w - $max_w) / 2;
		$y0 = ($dst_h - $max_h) / 2;
		$_dst_img = imagecreatetruecolor($max_w, $max_h);

		imagealphablending($_dst_img, false); //Set the blending mode for an image  	
		imagesavealpha($_dst_img, true); //Set the flag to save full alpha channel information  

		imagecopy(
			$_dst_img,
			$dst_img,
			0,
			0,
			(int)$x0,
			(int)$y0,
			$max_w,
			$max_h
		);

		$dst_img = $_dst_img;
		$dst_w = $max_w;
		$dst_h = $max_h;
	}

	// Watermark
	if (!empty($watermark) && is_readable($watermark)) {
		$overlay = imagecreatefrompng($watermark);

		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);

		switch ($watermark_postition) {
			case 'Top left':
				$watermark_x = $watermark_offset_x;
				$watermark_y = $watermark_offset_y;
				break;

			case 'Top right':
				$watermark_x = $dst_w - $watermark_offset_x - $owidth;
				$watermark_y = $watermark_offset_y;
				break;

			case 'Bottom left':
				$watermark_x = $watermark_offset_x;
				$watermark_y = $dst_h - $watermark_offset_y - $oheight;
				break;

			case 'Bottom right':
				$watermark_x = $dst_w - $watermark_offset_x - $owidth;
				$watermark_y = $dst_h - $watermark_offset_y - $oheight;
				break;

			default:
				$watermark_x = min(($dst_w - $owidth) * $watermark_offset_x / 100, $dst_w);
				$watermark_y = min(($dst_h - $oheight) * $watermark_offset_y / 100, $dst_h);
				break;
		}

		sed_imagecopymerge_alpha($dst_img, $overlay, $watermark_x, $watermark_y, 0, 0, $owidth, $oheight, $watermark_opacity);
	}

	// recalculate quality value for png image
	if ('image/png' === $src_type && !$use_webp) {
		$quality = round(($quality / 100) * 10);
		if ($quality < 1) {
			$quality = 1;
		} elseif ($quality > 10) {
			$quality = 10;
		}
		$quality = 10 - $quality;
	}

	// Save the image
	switch ($src_type) {
		case 'image/jpeg':
			return ($use_webp) ? imagewebp($dst_img, $dst_file, $quality) : imageJpeg($dst_img, $dst_file, $quality);
		case 'image/gif':
			return ($use_webp) ? imagewebp($dst_img, $dst_file) : imageGif($dst_img, $dst_file);
		case 'image/png':
			imagepalettetotruecolor($dst_img);
			imagealphablending($dst_img, true);
			imagesavealpha($dst_img, true);
			return ($use_webp) ? imagewebp($dst_img, $dst_file, $quality) : imagePng($dst_img, $dst_file, $quality);
		default:
			return false;
	}
}

/**
 * Creation of previews by means of imagick
 *
 * @param string $src_file source file
 * @param string $dst_file result file
 * @param string $type
 * @param int $max_w maximum width
 * @param int $max_h maximum height
 * @param string $watermark
 * @param int $watermark_offset_x
 * @param int $watermark_offset_y
 * @param int $watermark_opacity
 * @param float$ sharpen
 * @return bool
 */
function sed_image_constrain_imagick(
	$src_file,
	$dst_file,
	$type,
	$max_w,
	$max_h,
	$watermark = null,
	$watermark_offset_x = 0,
	$watermark_offset_y = 0,
	$watermark_opacity = 100,
	$watermark_postition = '',
	$sharpen = 0.2,
	$use_webp = false
) {
	global $cfg;

	$thumb = new Imagick();

	// Reading the image

	if (!$thumb->readImage($src_file)) {
		return false;
	}

	if ($dst_file) {
		$directory = dirname($dst_file);
		if (!is_dir($directory)) {
			@mkdir($directory, 0777, true);
		}
	}

	// Dimensions of the original image
	$src_w = $thumb->getImageWidth();
	$src_h = $thumb->getImageHeight();

	// Do I need to crop cut?
	if (!$watermark && ($src_w <= $max_w) && ($src_h <= $max_h)) {
		// No - just copy the file
		if (!copy($src_file, $dst_file)) {
			return false;
		}
		return true;
	}

	// Preview sizes at proportional reduction
	list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type);

	// Reducing
	if ($type == 'crop') {
		$x0 = ($dst_w - $max_w) / 2;
		$y0 = ($dst_h - $max_h) / 2;
		$thumb->thumbnailImage($dst_w, $dst_h);
		$dst_w = $max_w;
		$dst_h = $max_h;
		$thumb->cropImage($dst_w, $dst_h, (int)$x0, (int)$y0);
	} else {
		$thumb->thumbnailImage($dst_w, $dst_h);
	}

	if ($use_webp) {
		$thumb->setImageFormat('webp');
		$thumb->setOption('webp:lossless', 'true');
	}

	$watermark_x = null;
	$watermark_y = null;

	// Installing the watermark
	if ($watermark && is_readable($watermark)) {
		$overlay = new Imagick($watermark);
		//$overlay->setImageOpacity($watermark_opacity);
		//$overlay_compose = $overlay->getImageCompose();
		$overlay->evaluateImage(Imagick::EVALUATE_MULTIPLY, $watermark_opacity / 100, Imagick::CHANNEL_ALPHA);

		// Get the size of overlay
		$owidth = $overlay->getImageWidth();
		$oheight = $overlay->getImageHeight();

		switch ($watermark_postition) {
			case 'Top left':
				$watermark_x = $watermark_offset_x;
				$watermark_y = $watermark_offset_y;
				break;

			case 'Top right':
				$watermark_x = $dst_w - $watermark_offset_x - $owidth;
				$watermark_y = $watermark_offset_y;
				break;

			case 'Bottom left':
				$watermark_x = $watermark_offset_x;
				$watermark_y = $dst_h - $watermark_offset_y - $oheight;
				break;

			case 'Bottom right':
				$watermark_x = $dst_w - $watermark_offset_x - $owidth;
				$watermark_y = $dst_h - $watermark_offset_y - $oheight;
				break;

			default:
				$watermark_x = min(($dst_w - $owidth) * $watermark_offset_x / 100, $dst_w);
				$watermark_y = min(($dst_h - $oheight) * $watermark_offset_y / 100, $dst_h);
				break;
		}
	}


	// Animated gifs require frame traversal
	foreach ($thumb as $frame) {
		// Reducing
		$frame->thumbnailImage($dst_w, $dst_h);

		/* Set the virtual canvas to correct size */
		$frame->setImagePage($dst_w, $dst_h, 0, 0);

		// Sharpening
		if ($sharpen > 0) {
			$thumb->adaptiveSharpenImage($sharpen, $sharpen);
		}

		if (isset($overlay) && is_object($overlay)) {
			// $frame->compositeImage($overlay, $overlay_compose, $watermark_x, $watermark_y, imagick::COLOR_ALPHA);
			$frame->compositeImage($overlay, imagick::COMPOSITE_OVER, $watermark_x, $watermark_y);
		}
	}

	// We remove comments, etc. from picture
	$thumb->stripImage();

	// TODO put into settings
	$quality = $cfg['quality'];

	$thumb->setImageCompressionQuality($quality);

	// We record a picture
	if (!$thumb->writeImages($dst_file, true)) {
		return false;
	}

	// Cleaning
	$thumb->destroy();
	if (isset($overlay) && is_object($overlay)) {
		$overlay->destroy();
	}

	return true;
}

/**
 * Calculates the size of the image to which you need to proportionally reduce it to fit into the square $max_w x $max_h
 *
 * @param int $src_w source image width
 * @param int $src_h source image height
 * @param int $max_w maximum width
 * @param int $max_h maximum height
 * @param string $type
 * @return array | bool
 */
function sed_calc_contrain_size($src_w, $src_h, $max_w = 0, $max_h = 0, $type = 'resize')
{
	if ($src_w == 0 || $src_h == 0) {
		return false;
	}

	$dst_w = $src_w;
	$dst_h = $src_h;
	// image cropping calculator
	if ($type == 'crop') {
		$source_aspect_ratio = $src_w / $src_h;
		$desired_aspect_ratio = $max_w / $max_h;

		if ($source_aspect_ratio > $desired_aspect_ratio) {
			$dst_h = $max_h;
			$dst_w = (int)($max_h * $source_aspect_ratio);
		} else {
			$dst_w = $max_w;
			$dst_h = (int)($max_w / $source_aspect_ratio);
		}
	} else {
		// image resize calculator
		if ($src_w > $max_w && $max_w > 0) {
			$dst_h = $src_h * ($max_w / $src_w);
			$dst_w = $max_w;
		}
		if ($dst_h > $max_h && $max_h > 0) {
			$dst_w = $dst_w * ($max_h / $dst_h);
			$dst_h = $max_h;
		}
	}
	return array((int)$dst_w, (int)$dst_h);
}

/**
 * merge two true colour images while maintaining alpha transparency of both
 * images.
 *
 * known issues : Opacity values other than 100% get a bit screwy, the source
 *                composition determines how much this issue will annoy you.
 *                if in doubt, use as you would imagecopy_alpha (i.e. keep
 *                opacity at 100%)
 *
 * @param GdImage $dst_im Destination image link resource
 * @param GdImage $src_im Source image link resource
 * @param int $dst_x x-coordinate of destination point
 * @param int $dst_y y-coordinate of destination point
 * @param int $src_x x-coordinate of source point
 * @param int $src_y y-coordinate of source point
 * @param int $src_w Source width
 * @param int $src_h Source height
 * @param int $pct Opacity or source image
 */
function sed_imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
	// creating a cut resource
	$cut = imagecreatetruecolor($src_w, $src_h);
	// copying relevant section from background to the cut resource
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

	// copying relevant section from watermark to the cut resource
	imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);

	// insert cut resource to destination image
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

/** 
 * Image Merge
 * 
 * @param string $img1_file Original one image path 
 * @param string $img1_extension One Image extension  
 * @param string $img2_file Original two image path 
 * @param string $img2_extension Two Image extension
 * @param int $img2_x1 Two Image width
 * @param int $img2_y1 Two Image height
 * @param string $position Position the insertion 
 * @param int $trsp Merge percentage in %
 * @param int $jpegqual JPEG quality in %
 */
function sed_image_merge($img1_file, $img1_extension, $img2_file, $img2_extension, $img2_x1, $img2_y1, $position = 'Param', $trsp = 100, $jpegqual = 100)
{
	global $cfg;

	switch ($img1_extension) {
		case 'gif':
			$img1 = imagecreatefromgif($img1_file);
			break;

		case 'png':
			$img1 = imagecreatefrompng($img1_file);
			break;

		default:
			$img1 = imagecreatefromjpeg($img1_file);
			break;
	}

	switch ($img2_extension) {
		case 'gif':
			$img2 = imagecreatefromgif($img2_file);
			break;

		case 'png':
			$img2 = imagecreatefrompng($img2_file);
			break;

		default:
			$img2 = imagecreatefromjpeg($img2_file);
			break;
	}

	$img1_w = imagesx($img1);
	$img1_h = imagesy($img1);
	$img2_w = imagesx($img2);
	$img2_h = imagesy($img2);

	switch ($position) {
		case 'Top left':
			$img2_x = 8;
			$img2_y = 8;
			break;

		case 'Top right':
			$img2_x = $img1_w - 8 - $img2_w;
			$img2_y = 8;
			break;

		case 'Bottom left':
			$img2_x = 8;
			$img2_y = $img1_h - 8 - $img2_h;
			break;

		case 'Bottom right':
			$img2_x = $img1_w - 8 - $img2_w;
			$img2_y = $img1_h - 8 - $img2_h;
			break;

		default:
			$img2_x = $img2_x1;
			$img2_y = $img2_y1;
			break;
	}

	imagecopymerge($img1, $img2, $img2_x, $img2_y, 0, 0, $img2_w, $img2_h, $trsp);

	switch ($img1_extension) {
		case 'gif':
			imagegif($img1, $img1_file);
			break;

		case 'png':
			imagepng($img1, $img1_file);
			break;

		default:
			imagejpeg($img1, $img1_file, $jpegqual);
			break;
	}

	imagedestroy($img1);
	imagedestroy($img2);
}

/** 
 * Image Resize
 * 
 * @param string $img_big Original big image path 
 * @param int $img_small Resized image path
 * @param int $small_x Resized image width
 * @param string $extension Image extension
 * @param int $jpegquality JPEG quality in %
 */
function sed_image_resize($img_big, $img_small, $small_x, $extension, $jpegquality)
{
	if (!function_exists('gd_info')) {
		return;
	}

	global $cfg;

	switch ($extension) {
		case 'gif':
			$source = imagecreatefromgif($img_big);
			break;

		case 'png':
			$source = imagecreatefrompng($img_big);
			break;

		default:
			$source = imagecreatefromjpeg($img_big);
			break;
	}

	$big_x = imagesx($source);
	$big_y = imagesy($source);

	$thumb_x = $small_x;
	$thumb_y = floor($big_y * ($small_x / $big_x));

	if ($cfg['th_amode'] == 'GD1') {
		$new = imagecreate($thumb_x, $thumb_y);
	} else {
		$new = imagecreatetruecolor($thumb_x, $thumb_y);
	}

	imagealphablending($new, false); //Set the blending mode for an image  
	imagesavealpha($new, true); //Set the flag to save full alpha channel information    

	if ($cfg['th_amode'] == 'GD1') {
		imagecopyresized($new, $source, 0, 0, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
	} else {
		imagecopyresampled($new, $source, 0, 0, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
	}

	switch ($extension) {
		case 'gif':
			imagegif($new, $img_small);
			break;

		case 'png':
			imagepng($new, $img_small);
			break;

		default:
			imagejpeg($new, $img_small, $jpegquality);
			break;
	}

	imagedestroy($new);
	imagedestroy($source);
	return;
}

/** 
 * Creates image thumbnail 
 * 
 * @param string $img_big Original image path 
 * @param string $img_small Thumbnail path 
 * @param int $small_x Thumbnail width 
 * @param int $small_y Thumbnail height 
 * @param bool $keepratio Keep original ratio 
 * @param string $extension Image type 
 * @param string $filen Original file name 
 * @param int $fsize File size in kB 
 * @param string $textcolor Text color 
 * @param int $textsize Text size 
 * @param string $bgcolor Background color 
 * @param int $bordersize Border thickness 
 * @param int $jpegquality JPEG quality in % 
 * @param string $dim_priority Resize priority dimension 
 */
function sed_createthumb($img_big, $img_small, $small_x, $small_y, $keepratio, $extension, $filen, $fsize, $textcolor, $textsize, $bgcolor, $bordersize, $jpegquality, $dim_priority = "Width")
{
	if (!function_exists('gd_info')) {
		return;
	}

	global $cfg;

	switch ($extension) {
		case 'gif':
			$source = imagecreatefromgif($img_big);
			break;

		case 'png':
			$source = imagecreatefrompng($img_big);
			break;

		default:
			$source = imagecreatefromjpeg($img_big);
			break;
	}

	$big_x = imagesx($source);
	$big_y = imagesy($source);

	if (!$keepratio) {
		$thumb_x = $small_x;
		$thumb_y = $small_y;
	} elseif ($dim_priority == "Width") {
		$thumb_x = $small_x;
		$thumb_y = floor($big_y * ($small_x / $big_x));
	} else {
		$thumb_x = floor($big_x * ($small_y / $big_y));
		$thumb_y = $small_y;
	}

	if ($textsize == 0) {
		if ($cfg['th_amode'] == 'GD1') {
			$new = imagecreate($thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2);
		} else {
			$new = imagecreatetruecolor($thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2);
		}

		imagealphablending($new, false); //Set the blending mode for an image  	
		imagesavealpha($new, true); //Set the flag to save full alpha channel information  

		$background_color = imagecolorallocate($new, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
		imagefilledrectangle($new, 0, 0, $thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2, $background_color);

		if ($cfg['th_amode'] == 'GD1') {
			imagecopyresized($new, $source, $bordersize, $bordersize, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
		} else {
			imagecopyresampled($new, $source, $bordersize, $bordersize, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
		}
	} else {
		if ($cfg['th_amode'] == 'GD1') {
			$new = imagecreate($thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2 + floor($textsize * 3.5) + 6);
		} else {
			$new = imagecreatetruecolor($thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2 + floor($textsize * 3.5) + 6);
		}

		imagealphablending($new, false);  //Set the blending mode for an image    
		imagesavealpha($new, true);  //Set the flag to save full alpha channel information

		$background_color = imagecolorallocate($new, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
		imagefilledrectangle($new, 0, 0, $thumb_x + $bordersize * 2, $thumb_y + $bordersize * 2 + $textsize * 4 + 14, $background_color);
		$text_color = imagecolorallocate($new, $textcolor[0], $textcolor[1], $textcolor[2]);

		if ($cfg['th_amode'] == 'GD1') {
			imagecopyresized($new, $source, $bordersize, $bordersize, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
		} else {
			imagecopyresampled($new, $source, $bordersize, $bordersize, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
		}

		imagestring($new, $textsize, $bordersize, $thumb_y + $bordersize + $textsize + 1, $big_x . "x" . $big_y . " " . $fsize . "kb", $text_color);
	}

	switch ($extension) {
		case 'gif':
			imagegif($new, $img_small);
			break;

		case 'png':
			imagepng($new, $img_small);
			break;

		default:
			imagejpeg($new, $img_small, $jpegquality);
			break;
	}

	imagedestroy($new);
	imagedestroy($source);
	return;
}

/** 
 * Simple Creates image thumbnail 
 * 
 * @param string $img_big Original image path 
 * @param string $img_small Thumbnail path 
 * @param int $small_x Thumbnail width 
 * @param int $small_y Thumbnail height 
 * @param int $jpegquality JPEG quality in % 
 * @param bool $keepratio Keep original ratio 
 * @param string $bgcolor Background color 
 * @param int $bordersize Border thickness  
 * @param string $dim_priority Resize priority dimension 
 */

function sed_sm_createthumb($img_big, $img_small, $small_x, $small_y, $jpegquality = "90", $type = "resize", $keepratio = FALSE, $dim_priority = "Width")
{
	global $cfg;

	if (!function_exists('gd_info')) {
		return;
	}

	$extension = @end(explode(".", $img_big));

	switch ($extension) {
		case 'gif':
			$source = imagecreatefromgif($img_big);
			break;

		case 'png':
			$source = imagecreatefrompng($img_big);
			break;

		default:
			$source = imagecreatefromjpeg($img_big);
			break;
	}

	$big_x = imagesx($source);
	$big_y = imagesy($source);

	if (!$keepratio || $type == "crop") {
		$thumb_x = $small_x;
		$thumb_y = $small_y;
	} elseif ($dim_priority == "Width") {
		$thumb_x = $small_x;
		$thumb_y = floor($big_y * ($small_x / $big_x));
	} else {
		$thumb_x = floor($big_x * ($small_y / $big_y));
		$thumb_y = $small_y;
	}

	$new = imagecreatetruecolor($thumb_x, $thumb_y);
	imagealphablending($new, false); //Set the blending mode for an image  	
	imagesavealpha($new, true); //Set the flag to save full alpha channel information 	

	// crop
	if ($type == "crop") {
		$big_x_new = $big_y * $small_x / $small_y;
		$big_y_new = $big_x * $small_y / $small_x;
		if ($big_x_new > $big_x) {
			$h_point = (($big_y - $big_y_new) / 2);
			imagecopyresampled($new, $source, 0, 0, 0, $h_point, $thumb_x, $thumb_y, $big_x, $big_y_new);
		} else {
			$w_point = (($big_x - $big_x_new) / 2);
			imagecopyresampled($new, $source, 0, 0, $w_point, 0, $thumb_x, $thumb_y, $big_x_new, $big_y);
		}
	}
	// resize
	else {
		imagecopyresampled($new, $source, 0, 0, 0, 0, $thumb_x, $thumb_y, $big_x, $big_y);
	}

	switch ($extension) {
		case 'gif':
			imagegif($new, $img_small);
			break;

		case 'png':
			imagepng($new, $img_small);
			break;

		default:
			imagejpeg($new, $img_small, $jpegquality);
			break;
	}

	imagedestroy($new);
	imagedestroy($source);
	return;
}

/** 
 * Simple Rotate Image
 * 
 * @param string $image_source Original image path 
 * @param string $degree_lvl Degree level 
 */

function sed_rotateimage($image_source, $degree_lvl, $jpegquality = "90")
{
	global $cfg;

	if (!function_exists('gd_info')) {
		return;
	}

	$extension = @end(explode(".", $image_source));

	switch ($extension) {
		case 'gif':
			$source = imagecreatefromgif($image_source);
			break;

		case 'png':
			$source = imagecreatefrompng($image_source);
			break;

		default:
			$source = imagecreatefromjpeg($image_source);
			break;
	}

	$transColor = imagecolorallocatealpha($source, 255, 255, 255, 0);
	$rotated_image = imagerotate($source, -90 * $degree_lvl, 0);

	imagealphablending($rotated_image, false); //Set the blending mode for an image  	
	imagesavealpha($rotated_image, true); //Set the flag to save full alpha channel information	

	switch ($extension) {
		case 'gif':
			imagegif($rotated_image, $image_source);
			break;

		case 'png':
			imagepng($rotated_image, $image_source);
			break;

		default:
			imagejpeg($rotated_image, $image_source, $jpegquality);
			break;
	}

	imagedestroy($rotated_image);
	imagedestroy($source);
	return;
}
