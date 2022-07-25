<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/functions.image.php
Version=179
Updated=2022-jul-15
Type=Core
Author=Amro
Description=Image Functions
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$cfg['allowed_extentions'] = array('png', 'gif', 'jpg', 'jpeg', 'ico');

$cfg['pfs_dir'] = SED_ROOT.'/datas/users/';
$cfg['res_dir'] = SED_ROOT.'/datas/resized/';

$cfg['watermark_offset_x'] = 0;
$cfg['watermark_offset_y'] = 0;
$cfg['images_sharpen'] = 0;
$cfg['watermark_transparency'] = 0;
$cfg['use_imagick'] = true;
$cfg['quality'] = 85;

function resize_image($filename, $width = 0, $height = 0, $set_watermark = false)
    {
        global $cfg;	
		
		$resized_filename = add_resize_params($filename, 'resize', $width, $height, $set_watermark);
        return $cfg['res_dir'] . $resized_filename;
    }

function crop_image($filename, $width = 0, $height = 0, $set_watermark = false)
    {
        global $cfg;
		
		$resized_filename = add_resize_params($filename, 'crop', $width, $height, $set_watermark);
        return $cfg['res_dir'] . $resized_filename;
    }

/**
  * Create preview images
  * @param $ filename image file (without file path)
  * @return string preview file name
  */
function resize($filename)
{        
	global $cfg;
	
	// Picture folder paths
	$originals_dir = $cfg['pfs_dir'];
	$preview_dir = $cfg['res_dir'];	
	
	list($original_file, $type, $width, $height, $set_watermark) = get_resize_params($filename);
	$size = $width . 'x' . $height;
	
	if (!is_array($cfg['available_image_sizes'])) 
		{
			$cfg['available_image_sizes'] = (!empty($cfg['available_image_sizes'])) ? explode('|', $cfg['available_image_sizes']) : array();			
		}	
		
	$check_ais = (count($cfg['available_image_sizes']) > 0) ? in_array($size, $cfg['available_image_sizes']) : TRUE;
	
	if (!file_exists($originals_dir . $original_file) || empty($original_file) || !$check_ais)
		{ 
		header("HTTP/1.1 404 Not Found");
		exit;
		}
	
	$resized_file = add_resize_params($original_file, $type, $width, $height, $set_watermark);

	$watermark_offset_x = $cfg['watermark_offset_x'];
	$watermark_offset_y = $cfg['watermark_offset_y'];

	$sharpen = min(100, $cfg['images_sharpen']) / 100;
	$watermark_transparency = 1 - min(100, $cfg['watermark_transparency']) / 100;

	if ($set_watermark && is_file($cfg['gallery_logofile'])) {
		$watermark = $cfg['gallery_logofile'];
	} else {
		$watermark = null;
	}
	
	if (class_exists('Imagick') && $cfg['use_imagick']) {
		image_constrain_imagick($originals_dir . $original_file, $preview_dir . $resized_file, $type, $width,
			$height, $watermark, $watermark_offset_x, $watermark_offset_y, $watermark_transparency, $sharpen);
	} else {
		image_constrain_gd($originals_dir . $original_file, $preview_dir . $resized_file, $type, $width,
			$height, $watermark, $watermark_offset_x, $watermark_offset_y, $watermark_transparency);
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
function add_resize_params($filename, $type = '', $width = 0, $height = 0, $set_watermark = false)
{
	if ('.' != ($dirname = pathinfo($filename, PATHINFO_DIRNAME))) {
		$file = $dirname . '/' . pathinfo($filename, PATHINFO_FILENAME);
	} else {
		$file = pathinfo($filename, PATHINFO_FILENAME);
	}
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	if ($width > 0 || $height > 0) {
		$resized_filename = $file . '.' . $type . ($width > 0 ? $width : '') . 'x' . ($height > 0 ? $height : '') . ($set_watermark ? 'w' : '') . '.' . $ext;
	} else {
		// TODO fix this option does not work now
		$resized_filename = $file . '.' . $type . ($set_watermark ? 'w' : '') . '.' . $ext;
	}

	return $resized_filename;
}

/**
 * @param string $filename
 * @return array|false
 */
function get_resize_params($filename)
{
	
	// Determining the resize parameters
	if (!preg_match('/(.+)\.(resize|crop)?([0-9]*)x([0-9]*)(w)?\.([^\.]+)$/', $filename, $matches)) {
		return false;
	}

	$file = $matches[1];                    // the name of the requested file
	$type = $matches[2];                    // resize or crop
	$width = $matches[3];                   // width of the future image
	$height = $matches[4];                  // height of the future image
	$set_watermark = $matches[5] == 'w';    // whether to put a watermark
	$ext = $matches[6];                     // file extension

	return array($file . '.' . $ext, $type, $width, $height, $set_watermark);
}

/**
* Create previews using gd
*
* @param string $src_file source file
* @param string $dst_file result file
* @param string $type
* @param int $max_w maximum width
* @param int $max_h maximum height
* @param null $watermark
* @param int $watermark_offset_x
* @param int $watermark_offset_y
* @param int $watermark_opacity
* @return bool
*/
function image_constrain_gd(
	$src_file,
	$dst_file,
	$type,
	$max_w,
	$max_h,
	$watermark = null,
	$watermark_offset_x = 0,
	$watermark_offset_y = 0,
	$watermark_opacity = 1
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
	@list($dst_w, $dst_h) = calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type);

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
	if ($transparent_index >= 0 && $transparent_index <= 128) {
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
			0, 0,
			$x0, $y0,
			$max_w, $max_h
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

		$watermark_x = min(($dst_w - $owidth) * $watermark_offset_x / 100, $dst_w);
		$watermark_y = min(($dst_h - $oheight) * $watermark_offset_y / 100, $dst_h);

		//imagecopy($dst_img, $overlay, $watermark_x, $watermark_y, 0, 0, $owidth, $oheight);
		//imagecopymerge($dst_img, $overlay, $watermark_x, $watermark_y, 0, 0, $owidth, $oheight, $watermark_opacity*100);
		imagecopymerge_alpha($dst_img, $overlay, $watermark_x, $watermark_y, 0, 0, $owidth, $oheight, $watermark_opacity * 100);
	}

	// recalculate quality value for png image
	if ('image/png' === $src_type) {
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
			return imageJpeg($dst_img, $dst_file, $quality);
		case 'image/gif':
			return imageGif($dst_img, $dst_file);
		case 'image/png':
			imagesavealpha($dst_img, true);
			return imagePng($dst_img, $dst_file, $quality);
		default:
			return false;
	}
}

/**
* Creation of previews by means of imagick
*
* @param resource $src_file source file
* @param resource $dst_file result file
* @param string $type
* @param int $max_w maximum width
* @param int $max_h maximum height
* @param null $watermark
* @param int $watermark_offset_x
* @param int $watermark_offset_y
* @param int $watermark_opacity
* @param float$ sharpen
* @return bool
*/
function image_constrain_imagick(
	$src_file,
	$dst_file,
	$type,
	$max_w,
	$max_h,
	$watermark = null,
	$watermark_offset_x = 0,
	$watermark_offset_y = 0,
	$watermark_opacity = 1,
	$sharpen = 0.2
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
	list($dst_w, $dst_h) = calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type);

	// Reducing
	if ($type == 'crop') {
		$x0 = ($dst_w - $max_w) / 2;
		$y0 = ($dst_h - $max_h) / 2;
		$thumb->thumbnailImage($dst_w, $dst_h);
		$dst_w = $max_w;
		$dst_h = $max_h;
		$thumb->cropImage($dst_w, $dst_h, $x0, $y0);
	} else {
		$thumb->thumbnailImage($dst_w, $dst_h);
	}
	$watermark_x = null;
	$watermark_y = null;
	// Installing the watermark
	if ($watermark && is_readable($watermark)) {
		$overlay = new Imagick($watermark);
		//$overlay->setImageOpacity($watermark_opacity);
		//$overlay_compose = $overlay->getImageCompose();
		$overlay->evaluateImage(Imagick::EVALUATE_MULTIPLY, $watermark_opacity, Imagick::CHANNEL_ALPHA);

		// Get the size of overlay
		$owidth = $overlay->getImageWidth();
		$oheight = $overlay->getImageHeight();

		$watermark_x = min(($dst_w - $owidth) * $watermark_offset_x / 100, $dst_w);
		$watermark_y = min(($dst_h - $oheight) * $watermark_offset_y / 100, $dst_h);
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
			$frame->compositeImage($overlay, imagick::COMPOSITE_OVER, $watermark_x, $watermark_y,
				imagick::COLOR_ALPHA);
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
function calc_contrain_size($src_w, $src_h, $max_w = 0, $max_h = 0, $type = 'resize')
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
			$dst_w = ( int )($max_h * $source_aspect_ratio);
		} else {
			$dst_w = $max_w;
			$dst_h = ( int )($max_w / $source_aspect_ratio);
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
	return array($dst_w, $dst_h);
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
 * @param resource $dst_im Destination image link resource
 * @param resource $src_im Source image link resource
 * @param int $dst_x x-coordinate of destination point
 * @param int $dst_y y-coordinate of destination point
 * @param int $src_x x-coordinate of source point
 * @param int $src_y y-coordinate of source point
 * @param int $src_w Source width
 * @param int $src_h Source height
 * @param int $pct Opacity or source image
 */
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
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