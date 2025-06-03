<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
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

$cfg['watermark_offset_x'] = 8;
$cfg['watermark_offset_y'] = 8;

$cfg['images_sharpen'] = 0;
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

    //Check available image resolutions
    if (!is_array($cfg['available_image_sizes']) && !empty($cfg['available_image_sizes'])) {
        $cfg['available_image_sizes'] = mb_strtolower(str_replace(' ', '', $cfg['available_image_sizes']));
        $cfg['available_image_sizes'] = (!empty($cfg['available_image_sizes'])) ? explode(',', $cfg['available_image_sizes']) : array();
    } else {
        $cfg['available_image_sizes'] = array();
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

    // Use configured JPEG quality or fallback to default
    $quality = (!empty($cfg['th_jpeg_quality'])) ? $cfg['th_jpeg_quality'] : $cfg['quality'];
    $dim_priority = (!empty($cfg['th_dimpriority'])) ? $cfg['th_dimpriority'] : 'Width';

    $watermark  = (!empty($watermark) && $set_watermark && is_file($watermark)) ? $watermark : null;

    if (class_exists('Imagick') && ($cfg['th_amode'] == 'Imagick')) {
        sed_image_constrain_imagick(
            $originals_dir . $original_file,
            $preview_dir . $resized_file,
            $type,
            $width,
            $height,
            $quality,
            $watermark,
            $watermark_offset_x,
            $watermark_offset_y,
            $watermark_transparency,
            $watermark_position,
            $sharpen,
            $use_webp,
            $dim_priority
        );
    } else {
        sed_image_constrain_gd(
            $originals_dir . $original_file,
            $preview_dir . $resized_file,
            $type,
            $width,
            $height,
            $quality,
            $watermark,
            $watermark_offset_x,
            $watermark_offset_y,
            $watermark_transparency,
            $watermark_position,
            $use_webp,
            $dim_priority
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
 * Processes an image using the GD library, supporting resizing, cropping, and watermarking.
 *
 * This function handles the low-level image manipulation using GD. It supports proportional
 * resizing when $max_h is 0, or exact dimensions when both $max_w and $max_h are specified.
 *
 * @param string $src_file Path to the source image file.
 * @param string $dst_file Path to the destination image file.
 * @param string $type Processing type: 'resize' or 'crop'.
 * @param int $max_w Maximum or exact width of the output image.
 * @param int $max_h Maximum or exact height of the output image (0 for proportional resizing).
 * @param string|null $watermark Path to the watermark image file (optional).
 * @param int $watermark_offset_x Horizontal offset for watermark placement (pixels).
 * @param int $watermark_offset_y Vertical offset for watermark placement (pixels).
 * @param int $watermark_opacity Watermark opacity percentage (0-100).
 * @param string $watermark_postition Watermark position (e.g., 'Top left', 'Bottom right').
 * @param bool $use_webp Whether to save the output as WebP instead of the original format.
 * @return bool True on success, false on failure.
 */
function sed_image_constrain_gd(
    $src_file,
    $dst_file,
    $type,
    $max_w,
    $max_h,
    $quality,
    $watermark = null,
    $watermark_offset_x = 0,
    $watermark_offset_y = 0,
    $watermark_opacity = 100,
    $watermark_postition = '',
    $use_webp = false,
    $dim_priority = 'Width'
) {
    global $cfg;

    // Get source image details
    list($src_w, $src_h, $src_type) = array_values(getimagesize($src_file));
    $src_type = image_type_to_mime_type($src_type);

    if (empty($src_w) || empty($src_h) || empty($src_type)) {
        return false;
    }

    // Ensure destination directory exists
    if ($dst_file) {
        $directory = dirname($dst_file);
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }
    }

    // Skip processing if no watermark and image is within bounds (resize only)
    if (!$watermark && ($src_w <= $max_w) && ($src_h <= $max_h) && $type == 'resize') {
        return copy($src_file, $dst_file);
    }

    // Load source image based on its type
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
        case 'image/webp':
            $src_img = imageCreateFromWebp($src_file);
            break;
        default:
            return false;
    }

    if (empty($src_img)) {
        return false;
    }

    // Determine output dimensions:
    // If $max_h is 0, calculate proportional dimensions using sed_calc_contrain_size (for $keepratio = true);
    // otherwise, use $max_w and $max_h directly as exact dimensions (for $keepratio = false)
    if ($max_h == 0) {
        @list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type, $dim_priority);
    } else {
        $dst_w = $max_w;
        $dst_h = $max_h;
    }

    // Create destination image canvas
    $dst_img = imagecreatetruecolor($dst_w, $dst_h);
    if ($src_type === 'image/png') {
        imagealphablending($dst_img, false);
        $transparency = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
        imagefill($dst_img, 0, 0, $transparency);
        imagesavealpha($dst_img, true);
    }

    // Resample source image into destination canvas
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

    // Apply cropping if requested
    if ($type == 'crop') {
        $x0 = ($dst_w - $max_w) / 2;
        $y0 = ($dst_h - $max_h) / 2;
        $_dst_img = imagecreatetruecolor($max_w, $max_h);
        imagealphablending($_dst_img, false);
        imagesavealpha($_dst_img, true);
        imagecopy($_dst_img, $dst_img, 0, 0, (int)$x0, (int)$y0, $max_w, $max_h);
        $dst_img = $_dst_img;
        $dst_w = $max_w;
        $dst_h = $max_h;
    }

    // Apply watermark if provided
    if (!empty($watermark) && is_readable($watermark)) {
        $overlay = imagecreatefrompng($watermark);
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

    // Adjust quality for PNG if not using WebP
    if ('image/png' === $src_type && !$use_webp) {
        $quality = round(($quality / 100) * 10);
        $quality = max(1, min(10, $quality));
        $quality = 10 - $quality;
    }

    // Save the processed image
    switch ($src_type) {
        case 'image/jpeg':
            $result = $use_webp ? imagewebp($dst_img, $dst_file, $quality) : imageJpeg($dst_img, $dst_file, $quality);
            break;
        case 'image/gif':
            $result = $use_webp ? imagewebp($dst_img, $dst_file) : imageGif($dst_img, $dst_file);
            break;
        case 'image/png':
            imagepalettetotruecolor($dst_img);
            imagealphablending($dst_img, true);
            imagesavealpha($dst_img, true);
            $result = $use_webp ? imagewebp($dst_img, $dst_file, $quality) : imagePng($dst_img, $dst_file, $quality);
            break;
        case 'image/webp':
            $result = imagewebp($dst_img, $dst_file, $quality);
            break;
        default:
            $result = false;
    }

    // Clean up resources
    imagedestroy($src_img);
    imagedestroy($dst_img);
    return $result;
}

/**
 * Processes an image using the Imagick library, supporting resizing, cropping, and watermarking.
 *
 * This function handles image manipulation with Imagick, offering proportional resizing when
 * $max_h is 0, or exact dimensions when both $max_w and $max_h are provided. It also supports
 * sharpening and WebP output.
 *
 * @param string $src_file Path to the source image file.
 * @param string $dst_file Path to the destination image file.
 * @param string $type Processing type: 'resize' or 'crop'.
 * @param int $max_w Maximum or exact width of the output image.
 * @param int $max_h Maximum or exact height of the output image (0 for proportional resizing).
 * @param string|null $watermark Path to the watermark image file (optional).
 * @param int $watermark_offset_x Horizontal offset for watermark placement (pixels).
 * @param int $watermark_offset_y Vertical offset for watermark placement (pixels).
 * @param int $watermark_opacity Watermark opacity percentage (0-100).
 * @param string $watermark_postition Watermark position (e.g., 'Top left', 'Bottom right').
 * @param float $sharpen Sharpening factor for Imagick (default 0.2).
 * @param bool $use_webp Whether to save the output as WebP instead of the original format.
 * @return bool True on success, false on failure.
 */
function sed_image_constrain_imagick(
    $src_file,
    $dst_file,
    $type,
    $max_w,
    $max_h,
    $quality,
    $watermark = null,
    $watermark_offset_x = 0,
    $watermark_offset_y = 0,
    $watermark_opacity = 100,
    $watermark_postition = '',
    $sharpen = 0.2,
    $use_webp = false,
    $dim_priority = 'Width'
) {
    global $cfg;

    // Initialize Imagick object and load source image
    $thumb = new Imagick();
    if (!$thumb->readImage($src_file)) {
        return false;
    }

    // Ensure destination directory exists
    if ($dst_file) {
        $directory = dirname($dst_file);
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }
    }

    // Get source image dimensions
    $src_w = $thumb->getImageWidth();
    $src_h = $thumb->getImageHeight();

    // Skip processing if no watermark and image is within bounds
    if (!$watermark && ($src_w <= $max_w) && ($src_h <= $max_h)) {
        return copy($src_file, $dst_file);
    }

    // Determine output dimensions:
    // If $max_h is 0, calculate proportional dimensions using sed_calc_contrain_size (for $keepratio = true);
    // otherwise, use $max_w and $max_h directly as exact dimensions (for $keepratio = false)
    if ($max_h == 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type, $dim_priority);
    } else {
        $dst_w = $max_w;
        $dst_h = $max_h;
    }

    // Process image based on type
    if ($type == 'crop') {
        $x0 = ($dst_w - $max_w) / 2;
        $y0 = ($dst_h - $max_h) / 2;
        $thumb->thumbnailImage($dst_w, $dst_h);
        $thumb->cropImage($max_w, $max_h, (int)$x0, (int)$y0);
        $dst_w = $max_w;
        $dst_h = $max_h;
    } else {
        $thumb->thumbnailImage($dst_w, $dst_h);
    }

    // Convert to WebP if requested
    if ($use_webp) {
        $thumb->setImageFormat('webp');
        $thumb->setOption('webp:lossless', 'true');
    }

    // Apply watermark if provided
    $watermark_x = null;
    $watermark_y = null;
    if ($watermark && is_readable($watermark)) {
        $overlay = new Imagick($watermark);
        $overlay->evaluateImage(Imagick::EVALUATE_MULTIPLY, $watermark_opacity / 100, Imagick::CHANNEL_ALPHA);

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

        foreach ($thumb as $frame) {
            $frame->setImagePage($dst_w, $dst_h, 0, 0);
            if ($sharpen > 0) {
                $thumb->adaptiveSharpenImage($sharpen, $sharpen);
            }
            $frame->compositeImage($overlay, Imagick::COMPOSITE_OVER, $watermark_x, $watermark_y);
        }
    }

    // Finalize image settings
    $thumb->stripImage();
    $thumb->setImageCompressionQuality($quality);

    // Write the processed image
    if (!$thumb->writeImages($dst_file, true)) {
        return false;
    }

    // Clean up resources
    $thumb->destroy();
    if (isset($overlay) && is_object($overlay)) {
        $overlay->destroy();
    }

    return true;
}

/**
 * Calculates constrained image size based on priority dimension
 *
 * @param int $src_w Source width
 * @param int $src_h Source height
 * @param int $max_w Maximum width
 * @param int $max_h Maximum height
 * @param string $type 'resize' or 'crop'
 * @param string $dim_priority 'Width' or 'Height'
 * @return array|bool Calculated dimensions or false on error
 */
function sed_calc_contrain_size($src_w, $src_h, $max_w = 0, $max_h = 0, $type = 'resize', $dim_priority = 'Width')
{
    if ($src_w == 0 || $src_h == 0) {
        return false;
    }

    $dst_w = $src_w;
    $dst_h = $src_h;

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
        if ($dim_priority == 'Width' && $max_w > 0) {
            if ($src_w > $max_w) {
                $dst_h = $src_h * ($max_w / $src_w);
                $dst_w = $max_w;
            }
            if ($max_h > 0 && $dst_h > $max_h) {
                $dst_w = $dst_w * ($max_h / $dst_h);
                $dst_h = $max_h;
            }
        } elseif ($dim_priority == 'Height' && $max_h > 0) {
            if ($src_h > $max_h) {
                $dst_w = $src_w * ($max_h / $src_h);
                $dst_h = $max_h;
            }
            if ($max_w > 0 && $dst_w > $max_w) {
                $dst_h = $dst_h * ($max_w / $dst_w);
                $dst_w = $max_w;
            }
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
 * Processes an image with resizing, watermarking, or cropping capabilities.
 *
 * This function prepares the source and destination paths, calculates target dimensions,
 * and delegates the actual image manipulation to either GD or Imagick based on configuration.
 * It supports proportional resizing or exact dimensions depending on the $keepratio parameter.
 *
 * @param string $source Path to the source image file (relative or absolute).
 * @param string $dest Path to the destination image file (relative or absolute).
 * @param int $width Desired width of the output image (0 or negative uses original width).
 * @param int $height Desired height of the output image (0 or negative uses original height).
 * @param bool $keepratio Whether to maintain the aspect ratio of the original image.
 * @param string $type Processing type: 'resize' (default) or 'crop'.
 * @param string $dim_priority Dimension to prioritize when resizing ('Width' or 'Height', default 'Width').
 * @param int $quality JPEG quality percentage (0-100, default 85).
 * @param bool $set_watermark
 * @param bool $preserve_source If true, overwrites the source file instead of creating a new one.
 * @return bool|string True on success, error message string on failure.
 */
function sed_image_process(
    $source,
    $dest,
    $width,
    $height,
    $keepratio,
    $type = 'resize',
    $dim_priority = 'Width',
    $quality = null,
    $set_watermark = false,
    $preserve_source = false
) {
    global $cfg;

    // Normalize paths to absolute using SED_ROOT
    $source = (strpos($source, SED_ROOT) === 0) ? $source : SED_ROOT . '/' . ltrim($source, '/');
    $dest = (strpos($dest, SED_ROOT) === 0) ? $dest : SED_ROOT . '/' . ltrim($dest, '/');

    // Check if source file exists
    if (!file_exists($source)) {
        return "Error: Source file does not exist: $source";
    }

    // Get original image dimensions
    list($src_w, $src_h) = getimagesize($source);

    // Set target dimensions, defaulting to original if not provided
    $target_w = ($width <= 0) ? $src_w : $width;
    $target_h = ($height <= 0) ? $src_h : $height;

    // Prepare final dimensions for processing
    $final_w = $target_w;
    // If $keepratio is true, set height to 0 to let lower-level functions calculate it proportionally;
    // if false, use the exact $target_h to enforce fixed dimensions
    $final_h = $keepratio ? 0 : $target_h;

    // Watermark configuration: use provided value or fall back to global config
    $watermark_file = ($set_watermark == false) ? null : (isset($cfg['gallery_logofile']) ? $cfg['gallery_logofile'] : null);
    if (!empty($watermark_file)) {
        $watermark_file = (strpos($watermark_file, SED_ROOT) === 0) ? $watermark_file : SED_ROOT . '/' . ltrim($watermark_file, '/');
        $watermark_file = file_exists($watermark_file) ? $watermark_file : null;
    }

    $watermark_pos = isset($cfg['gallery_logopos']) ? $cfg['gallery_logopos'] : 'Bottom left';
    $watermark_op = isset($cfg['gallery_logotrsp']) ? $cfg['gallery_logotrsp'] : 100;
    $watermark_op = min(100, max(0, (int)$watermark_op));
    $watermark_offset_x = isset($cfg['watermark_offset_x']) ? $cfg['watermark_offset_x'] : 8;
    $watermark_offset_y = isset($cfg['watermark_offset_y']) ? $cfg['watermark_offset_y'] : 8;

    // Use configured JPEG quality or fallback to default
    $quality = ($quality === null || $quality === '') ? (isset($cfg['th_jpeg_quality']) ? $cfg['th_jpeg_quality'] : $cfg['quality']) : $quality;

    // Delegate to Imagick if available and enabled, otherwise use GD
    if (class_exists('Imagick') && ($cfg['th_amode'] == 'Imagick')) {
        $result = sed_image_constrain_imagick(
            $source,
            $preserve_source ? $source : $dest,
            $type,
            $final_w,
            $final_h,
            $quality,
            $watermark_file,
            $watermark_offset_x,
            $watermark_offset_y,
            $watermark_op,
            $watermark_pos,
            0.2, // Default sharpening factor for Imagick
            false,
            $dim_priority
        );
    } else {
        $result = sed_image_constrain_gd(
            $source,
            $preserve_source ? $source : $dest,
            $type,
            $final_w,
            $final_h,
            $quality,
            $watermark_file,
            $watermark_offset_x,
            $watermark_offset_y,
            $watermark_op,
            $watermark_pos,
            false,
            $dim_priority
        );
    }

    return $result === true ? true : "Error: Image processing failed";
}

/** 
 * Creates image thumbnail (wrapper for sed_image_process)  (DEPRECATED)
 * 
 * @param string $img_big Original image path 
 * @param string $img_small Thumbnail path 
 * @param int $small_x Thumbnail width 
 * @param int $small_y Thumbnail height 
 * @param bool $keepratio Keep original ratio 
 * @param string $type 'resize' or 'crop' 
 * @param string $dim_priority Resize priority dimension 
 * @param int $jpegquality JPEG quality in % 
 * @return bool|string TRUE on success, error message on failure 
 */
function sed_createthumb($img_big, $img_small, $small_x, $small_y, $keepratio, $type = 'resize', $dim_priority = 'Width', $jpegquality = 85)
{
    return sed_image_process(
        $img_big,         // $source
        $img_small,       // $dest
        $small_x,         // $width
        $small_y,         // $height
        $keepratio,       // $keepratio
        $type,            // $type
        $dim_priority,    // $dim_priority
        $jpegquality      // $quality
    );
}

/** 
 * Simple Creates image thumbnail (DEPRECATED)
 * 
 * @param string $img_big Original image path 
 * @param string $img_small Thumbnail path 
 * @param int $small_x Thumbnail width 
 * @param int $small_y Thumbnail height 
 * @param int $jpegquality JPEG quality in % 
 * @param string $type 'resize' or 'crop' 
 * @param bool $keepratio Keep original ratio 
 * @param string $dim_priority Resize priority dimension 
 */
function sed_sm_createthumb($img_big, $img_small, $small_x, $small_y, $jpegquality = "90", $type = "resize", $keepratio = false, $dim_priority = "Width")
{
    return sed_image_process($img_big, $img_small, $small_x, $small_y, $keepratio, $type, $dim_priority, $jpegquality);
}

/** 
 * Image Resize (DEPRECATED)
 * 
 * @param string $img_big Original big image path 
 * @param string $img_small Resized image path 
 * @param int $small_x Resized image width 
 * @param string $extension Image extension (ignored) 
 * @param int $jpegquality JPEG quality in % 
 */
function sed_image_resize($img_big, $img_small, $small_x, $extension, $jpegquality)
{
    return sed_image_process($img_big, $img_small, $small_x, 0, true, 'resize', 'Width', $jpegquality);
}

/** 
 * Image Merge (DEPRECATED)
 * 
 * @param string $img1_file Original one image path 
 * @param string $img1_extension One Image extension (ignored) 
 * @param string $img2_file Original two image path 
 * @param string $img2_extension Two Image extension (ignored)
 * @param int $img2_x1 Two Image X coordinate
 * @param int $img2_y1 Two Image Y coordinate
 * @param string $position Position the insertion 
 * @param int $trsp Merge percentage in %
 * @param int $jpegqual JPEG quality in %
 */
function sed_image_merge($img1_file, $img1_extension, $img2_file, $img2_extension, $img2_x1, $img2_y1, $position = 'Param', $trsp = 100, $jpegqual = 100)
{
    return sed_image_process(
        $img1_file,       // $source
        $img1_file,       // $dest (overwrite source)
        0,                // $width (use original)
        0,                // $height (use original)
        false,            // $keepratio (no resize)
        'resize',         // $type (no actual resize)
        'Width',          // $dim_priority (irrelevant)
        $jpegqual,        // $quality
        $img2_file,       // $watermark
        $position,        // $watermark_position
        $trsp,            // $watermark_opacity
        true              // $preserve_source
    );
}

/**
 * Rotates an image using either GD or Imagick based on configuration.
 *
 * @param string $image_source Path to the original image file.
 * @param int $degree_lvl Rotation level (number of 90-degree increments, e.g., 1 = 90°, 2 = 180°).
 * @param int $jpegquality JPEG/WebP quality percentage (0-100, default 90).
 * @return bool True on success, false on failure.
 */
function sed_rotateimage($image_source, $degree_lvl, $jpegquality = 90)
{
    global $cfg;

    // Normalize paths to absolute using SED_ROOT
    $image_source = (strpos($image_source, SED_ROOT) === 0) ? $image_source : SED_ROOT . '/' . ltrim($image_source, '/');

    // Validate input
    if (!file_exists($image_source)) {
        return false;
    }

    $extension = strtolower(pathinfo($image_source, PATHINFO_EXTENSION));
    $quality = min(100, max(0, (int)$jpegquality)); // Ensure quality is within 0-100

    // Use Imagick if available and configured
    if (class_exists('Imagick') && ($cfg['th_amode'] == 'Imagick')) {
        // Imagick rotation
        $image = new Imagick();
        if (!$image->readImage($image_source)) {
            return false;
        }

        // Rotate image (positive degrees for Imagick)
        $image->rotateImage(new ImagickPixel('none'), -90 * $degree_lvl);

        // Set compression quality
        $image->setImageCompressionQuality($quality);

        // Save based on extension
        switch ($extension) {
            case 'gif':
                $image->setImageFormat('gif');
                break;
            case 'png':
                $image->setImageFormat('png');
                break;
            case 'webp':
                $image->setImageFormat('webp');
                break;
            default:
                $image->setImageFormat('jpeg');
                break;
        }

        $result = $image->writeImage($image_source);
        $image->destroy();
        return $result;
    } else {
        // GD rotation (fallback)
        if (!function_exists('gd_info')) {
            return false;
        }

        // Load image based on type
        switch ($extension) {
            case 'gif':
                $source = imagecreatefromgif($image_source);
                break;
            case 'png':
                $source = imagecreatefrompng($image_source);
                break;
            case 'webp':
                $source = imagecreatefromwebp($image_source);
                break;
            default:
                $source = imagecreatefromjpeg($image_source);
                break;
        }

        if (!$source) {
            return false;
        }

        // Rotate with transparency
        $transColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
        $rotated_image = imagerotate($source, -90 * $degree_lvl, $transColor);

        if (!$rotated_image) {
            imagedestroy($source);
            return false;
        }

        // Preserve transparency for PNG and WebP
        imagealphablending($rotated_image, false);
        imagesavealpha($rotated_image, true);

        // Save based on extension
        switch ($extension) {
            case 'gif':
                $result = imagegif($rotated_image, $image_source);
                break;
            case 'png':
                $quality = round(($quality / 100) * 10); // Convert 0-100 to 0-9 scale
                $quality = max(0, min(9, $quality)); // PNG compression: 0 (best) to 9 (worst)
                $result = imagepng($rotated_image, $image_source, $quality);
                break;
            case 'webp':
                $result = imagewebp($rotated_image, $image_source, $quality);
                break;
            default:
                $result = imagejpeg($rotated_image, $image_source, $quality);
                break;
        }

        // Clean up
        imagedestroy($rotated_image);
        imagedestroy($source);
        return $result;
    }
}
