<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/functions.image.php
Version=185
Updated=2026-feb-14
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

/**
 * Registered resize namespaces: cache key => relative source directory under SED_ROOT.
 *
 * @var array
 */
$sed_image_resize_namespaces = array();

/**
 * Sanitize namespace key (alphanumeric, underscore, hyphen).
 *
 * @param string $namespace
 * @return string
 */
function sed_image_resize_namespace_sanitize($namespace)
{
    return preg_replace('/[^a-z0-9_\-]/i', '', (string)$namespace);
}

/**
 * Register a source directory for a resize namespace.
 *
 * Cache files are stored as datas/resized/{namespace}/{basename}.resizeWxH.ext
 * while originals live in the registered relative directory.
 *
 * @param string $namespace Cache folder name (e.g. products, brands)
 * @param string $source_dir Relative path under SED_ROOT (e.g. datas/shop/products/)
 * @return bool
 */
function sed_image_resize_namespace_register($namespace, $source_dir)
{
    global $sed_image_resize_namespaces;

    if (!is_array($sed_image_resize_namespaces)) {
        $sed_image_resize_namespaces = array();
    }

    $namespace = sed_image_resize_namespace_sanitize($namespace);
    $source_dir = str_replace('\\', '/', trim((string)$source_dir));
    $source_dir = ltrim($source_dir, '/');

    if ($namespace === '' || $source_dir === '' || strpos($source_dir, '..') !== false) {
        return false;
    }

    $sed_image_resize_namespaces[$namespace] = rtrim($source_dir, '/') . '/';
    return true;
}

/**
 * Whether a resize namespace is registered.
 *
 * @param string $namespace
 * @return bool
 */
function sed_image_resize_namespace_registered($namespace)
{
    global $sed_image_resize_namespaces;

    $namespace = sed_image_resize_namespace_sanitize($namespace);
    return is_array($sed_image_resize_namespaces) && isset($sed_image_resize_namespaces[$namespace]);
}

/**
 * Resolve absolute path to an original image file.
 *
 * @param string $filename Basename or legacy PFS filename
 * @param string $namespace Registered namespace or empty for legacy PFS
 * @return string|false Absolute path or false if not found
 */
function sed_image_resolve_original_path($filename, $namespace = '')
{
    global $cfg, $sed_image_resize_namespaces;

    $filename = basename(str_replace('\\', '/', trim((string)$filename)));
    if ($filename === '' || strpos($filename, '..') !== false) {
        return false;
    }

    $namespace = sed_image_resize_namespace_sanitize($namespace);

    if ($namespace !== '' && sed_image_resize_namespace_registered($namespace)) {
        $rel = $sed_image_resize_namespaces[$namespace] . $filename;
        $path = SED_ROOT . '/' . $rel;
        if (!is_file($path)) {
            return false;
        }
        $real = realpath($path);
        $root = realpath(SED_ROOT);
        if ($real !== false && $root !== false && strpos($real, $root) === 0) {
            return $real;
        }
        return false;
    }

    $path = SED_ROOT . '/' . $cfg['pfs_dir'] . $filename;
    if (is_file($path)) {
        $real = realpath($path);
        return ($real !== false) ? $real : false;
    }

    return false;
}

/**
 * Delete generated resize/crop cache files for a basename.
 *
 * @param string $filename Original basename
 * @param string $namespace Registered namespace or empty for legacy PFS cache root
 */
function sed_image_delete_resized_cache($filename, $namespace = '')
{
    global $cfg;

    $filename = basename(str_replace('\\', '/', trim((string)$filename)));
    if ($filename === '') {
        return;
    }

    $base = pathinfo($filename, PATHINFO_FILENAME);
    $namespace = sed_image_resize_namespace_sanitize($namespace);
    $prefix = SED_ROOT . '/' . $cfg['res_dir'];
    if ($namespace !== '') {
        $prefix .= $namespace . '/';
    }

    $patterns = array(
        $prefix . $base . '.resize*',
        $prefix . $base . '.crop*',
    );

    foreach ($patterns as $pattern) {
        $files = glob($pattern);
        if (!is_array($files)) {
            continue;
        }
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}

function resize_image($filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false, $namespace = '')
{
    global $cfg;

    $resized_filename = sed_add_resize_params($filename, 'resize', $width, $height, $set_watermark, $use_webp, $namespace);
    $url = $cfg['res_dir'] . $resized_filename;
    if ($url !== '' && $url[0] !== '/') {
        $url = '/' . $url;
    }
    return $url;
}

function crop_image($filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false, $namespace = '')
{
    global $cfg;

    $resized_filename = sed_add_resize_params($filename, 'crop', $width, $height, $set_watermark, $use_webp, $namespace);
    $url = $cfg['res_dir'] . $resized_filename;
    if ($url !== '' && $url[0] !== '/') {
        $url = '/' . $url;
    }
    return $url;
}

/**
 * Build resized image URL for a registered namespace (XTemplate-friendly wrapper).
 *
 * @param string $namespace
 * @param string $filename
 * @param int $width
 * @param int $height
 * @param bool $set_watermark
 * @param bool $use_webp
 * @return string
 */
function resize_image_ns($namespace, $filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false)
{
    return resize_image($filename, $width, $height, $set_watermark, $use_webp, $namespace);
}

/**
 * Build cropped image URL for a registered namespace (XTemplate-friendly wrapper).
 *
 * @param string $namespace
 * @param string $filename
 * @param int $width
 * @param int $height
 * @param bool $set_watermark
 * @param bool $use_webp
 * @return string
 */
function crop_image_ns($namespace, $filename, $width = 0, $height = 0, $set_watermark = false, $use_webp = false)
{
    return crop_image($filename, $width, $height, $set_watermark, $use_webp, $namespace);
}

/**
 * Create preview images
 * @param $ filename image file (without file path)
 * @return string preview file name
 */
function sed_resize($filename)
{
    global $cfg;

    $preview_dir = SED_ROOT . '/' . $cfg['res_dir'];

    $parsed = sed_get_resize_params($filename);
    if ($parsed === false) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    list($original_file, $type, $width, $height, $set_watermark, $use_webp, $namespace) = $parsed;

    if (empty($original_file)) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    $src_path = sed_image_resolve_original_path($original_file, $namespace);
    if ($src_path === false) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    $size = $width . 'x' . $height;

    //Check available image resolutions
    if (!is_array($cfg['available_image_sizes']) && !empty($cfg['available_image_sizes'])) {
        $cfg['available_image_sizes'] = mb_strtolower(str_replace(' ', '', $cfg['available_image_sizes']));
        $cfg['available_image_sizes'] = (!empty($cfg['available_image_sizes'])) ? explode(',', $cfg['available_image_sizes']) : array();
    } else {
        $cfg['available_image_sizes'] = array();
    }

    $check_ais = (count($cfg['available_image_sizes']) > 0) ? in_array($size, $cfg['available_image_sizes']) : TRUE;

    if (!$check_ais) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    $resized_file = sed_add_resize_params($original_file, $type, $width, $height, $set_watermark, $use_webp, $namespace);

    $watermark_offset_x = $cfg['watermark_offset_x'];
    $watermark_offset_y = $cfg['watermark_offset_y'];
    $watermark_position = $cfg['th_logopos'];

    $sharpen = min(100, $cfg['images_sharpen']) / 100;
    $watermark_transparency = min(100, $cfg['th_logotrsp']);

    if (!empty($cfg['th_logofile']) && $set_watermark) {
        $watermark = (strpos($cfg['th_logofile'], "/") == 0) ? SED_ROOT . $cfg['th_logofile'] : SED_ROOT . '/' . $cfg['th_logofile'];
    }

    // Use configured JPEG quality or fallback to default
    $quality = (!empty($cfg['th_jpeg_quality'])) ? $cfg['th_jpeg_quality'] : $cfg['quality'];
    $dim_priority = (!empty($cfg['th_dimpriority'])) ? $cfg['th_dimpriority'] : 'Width';
    $keepratio = $cfg['th_keepratio'];

    $watermark  = (!empty($watermark) && $set_watermark && is_file($watermark)) ? $watermark : null;

    if (class_exists('Imagick') && ($cfg['th_amode'] == 'Imagick')) {
        sed_image_constrain_imagick(
            $src_path,
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
            $dim_priority,
            $keepratio
        );
    } else {
        sed_image_constrain_gd(
            $src_path,
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
            $dim_priority,
            $keepratio
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
function sed_add_resize_params($filename, $type = '', $width = 0, $height = 0, $set_watermark = false, $use_webp = false, $namespace = '')
{
    $resized_filename = '';
    if (!empty($filename)) {
        $filename = basename(str_replace('\\', '/', $filename));
        if ($filename === '' || strpos($filename, '..') !== false) {
            return '';
        }

        $namespace = sed_image_resize_namespace_sanitize($namespace);
        $base = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($namespace !== '') {
            $file = $namespace . '/' . $base;
        } else {
            $file = $base;
        }

        if ($width > 0 || $height > 0) {
            $resized_filename = $file . '.' . $type . $width . 'x' . $height . ($set_watermark ? 'w' : '') . '.' . $ext . ($use_webp ? '.webp' : '');
        } else {
            // TODO fix this option does not work now
            //$resized_filename = $file . '.' . $type . ($set_watermark ? 'w' : '') . '.' . $ext . ($use_webp ? '.webp' : '');
            header("HTTP/1.1 404 Not Found");
            exit;
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
    if (!preg_match('/(.+)\.(resize|crop)([0-9]+)x([0-9]+)(w)?\.([^\.]+)(\.webp)?$/', $filename, $matches)) {
        return false;
    }

    $file = $matches[1];                    // the name of the requested file
    $type = $matches[2];                    // resize or crop
    $width = $matches[3];                   // width of the future image
    $height = $matches[4];                  // height of the future image
    $set_watermark = $matches[5] == 'w';    // whether to put a watermark
    $ext = $matches[6];                     // file extension
    $use_webp = !empty($matches[7]) ? true : false;

    $namespace = '';
    $original_file = $file . '.' . $ext;

    if (strpos($file, '/') !== false) {
        $slash = strpos($file, '/');
        $ns = substr($file, 0, $slash);
        if (sed_image_resize_namespace_registered($ns)) {
            $namespace = $ns;
            $original_file = substr($file, $slash + 1) . '.' . $ext;
        }
    }

    return array($original_file, $type, $width, $height, $set_watermark, $use_webp, $namespace);
}

/**
 * Processes an image using the GD library, supporting resizing, cropping, and watermarking.
 *
 * This function handles the low-level image manipulation using GD. It supports proportional
 * resizing when $max_w or $max_h is 0, or exact dimensions when both are specified. For crop,
 * it scales proportionally to fit the target dimensions before cropping.
 *
 * @param string $src_file Path to the source image file.
 * @param string $dst_file Path to the destination image file.
 * @param string $type Processing type: 'resize' or 'crop'.
 * @param int $max_w Maximum or exact width of the output image.
 * @param int $max_h Maximum or exact height of the output image (0 for proportional resizing).
 * @param int $quality JPEG quality percentage (0-100).
 * @param string|null $watermark Path to the watermark image file (optional).
 * @param int $watermark_offset_x Horizontal offset for watermark placement (pixels).
 * @param int $watermark_offset_y Vertical offset for watermark placement (pixels).
 * @param int $watermark_opacity Watermark opacity percentage (0-100).
 * @param string $watermark_position Watermark position (e.g., 'Top left', 'Bottom right').
 * @param bool $use_webp Whether to save the output as WebP instead of the original format.
 * @param string $dim_priority Dimension to prioritize when resizing ('Width' or 'Height').
 * @param bool $keepratio Keep original ratio
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
    $watermark_position = '',
    $use_webp = false,
    $dim_priority = 'Width',
    $keepratio = false // Added to handle proportional resizing when both dimensions are non-zero
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
    // For crop: always calculate proportional dimensions to fit $max_w x $max_h.
    // For resize: if $max_w or $max_h is 0, calculate proportional dimensions; otherwise, stretch.
    // If $keepratio is true and both $max_w and $max_h are non-zero, calculate proportional dimensions.
    if ($type == 'crop') {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type, $dim_priority);
    } elseif ($max_w == 0 && $max_h > 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', 'Height');
    } elseif ($max_h == 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', $dim_priority);
    } elseif ($keepratio && $max_w > 0 && $max_h > 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', $dim_priority);
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

    // Apply cropping if requested and both $max_w and $max_h are non-zero
    if ($type == 'crop' && $max_w > 0 && $max_h > 0) {
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

        switch ($watermark_position) {
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

    return $result;
}

/**
 * Processes an image using the Imagick library, supporting resizing, cropping, and watermarking.
 *
 * This function handles image manipulation with Imagick, offering proportional resizing when
 * $max_w or $max_h is 0, or stretching to exact dimensions when both are provided. For crop,
 * it scales proportionally to fit the target dimensions before cropping.
 *
 * @param string $src_file Path to the source image file.
 * @param string $dst_file Path to the destination image file.
 * @param string $type Processing type: 'resize' or 'crop'.
 * @param int $max_w Maximum or exact width of the output image.
 * @param int $max_h Maximum or exact height of the output image (0 for proportional resizing).
 * @param int $quality JPEG quality percentage (0-100).
 * @param string|null $watermark Path to the watermark image file (optional).
 * @param int $watermark_offset_x Horizontal offset for watermark placement (pixels).
 * @param int $watermark_offset_y Vertical offset for watermark placement (pixels).
 * @param int $watermark_opacity Watermark opacity percentage (0-100).
 * @param string $watermark_position Watermark position (e.g., 'Top left', 'Bottom right').
 * @param float $sharpen Sharpening factor for Imagick (default 0.2).
 * @param bool $use_webp Whether to save the output as WebP instead of the original format.
 * @param string $dim_priority Dimension to prioritize when resizing ('Width' or 'Height').
 * @param bool $keepratio Keep original ratio
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
    $watermark_position = '',
    $sharpen = 0.2,
    $use_webp = false,
    $dim_priority = 'Width',
    $keepratio = false // Added to handle proportional resizing when both dimensions are non-zero
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

    // Skip processing if no watermark and image is within bounds (resize only)
    if (!$watermark && ($src_w <= $max_w) && ($src_h <= $max_h) && $type == 'resize') {
        $thumb->destroy();
        return copy($src_file, $dst_file);
    }

    // Determine output dimensions:
    // For crop: always calculate proportional dimensions to fit $max_w x $max_h.
    // For resize: if $max_w or $max_h is 0, calculate proportional dimensions; otherwise, stretch.
    // If $keepratio is true and both $max_w and $max_h are non-zero, calculate proportional dimensions.
    if ($type == 'crop') {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, $type, $dim_priority);
    } elseif ($max_w == 0 && $max_h > 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', 'Height');
    } elseif ($max_h == 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', $dim_priority);
    } elseif ($keepratio && $max_w > 0 && $max_h > 0) {
        list($dst_w, $dst_h) = sed_calc_contrain_size($src_w, $src_h, $max_w, $max_h, 'resize', $dim_priority);
    } else {
        $dst_w = $max_w;
        $dst_h = $max_h;
    }

    // Process image based on type
    if ($type == 'crop' && $max_w > 0 && $max_h > 0) {
        $thumb->thumbnailImage($dst_w, $dst_h, true);
        $x0 = ($dst_w - $max_w) / 2;
        $y0 = ($dst_h - $max_h) / 2;
        $thumb->cropImage($max_w, $max_h, (int)$x0, (int)$y0);
        $dst_w = $max_w;
        $dst_h = $max_h;
    } else {
        $thumb->thumbnailImage($dst_w, $dst_h, ($type == 'resize' && ($keepratio || $max_w == 0 || $max_h == 0)) || $type == 'crop');
    }

    // Convert to WebP if requested with lossy compression
    if ($use_webp) {
        $thumb->setImageFormat('webp');
        $thumb->setOption('webp:lossless', 'false');
        $thumb->setOption('webp:method', '6');
        $thumb->setOption('webp:lossy-quality', $quality);
    }

    // Optimize PNG compression if applicable
    if ($thumb->getImageFormat() == 'png' && !$use_webp) {
        $thumb->setOption('png:compression-level', '9');
    }

    // Apply watermark if provided
    if ($watermark && is_readable($watermark)) {
        $overlay = new Imagick($watermark);
        $overlay->evaluateImage(Imagick::EVALUATE_MULTIPLY, $watermark_opacity / 100, Imagick::CHANNEL_ALPHA);

        $owidth = $overlay->getImageWidth();
        $oheight = $overlay->getImageHeight();

        switch ($watermark_position) {
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
        $overlay->destroy();
    }

    // Remove metadata to reduce file size
    $thumb->stripImage();

    // Set compression quality
    $thumb->setImageCompressionQuality($quality);

    // Write the processed image
    if (!$thumb->writeImages($dst_file, true)) {
        $thumb->destroy();
        return false;
    }

    // Clean up resources
    $thumb->destroy();
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
        // Handle cases where $max_w or $max_h is 0
        if ($max_w == 0 && $max_h == 0) {
            // If both are 0, return original dimensions
            return array((int)$dst_w, (int)$dst_h);
        } elseif ($max_w == 0) {
            // Scale by height, keep width proportional
            $dst_h = $max_h;
            $dst_w = (int)($max_h * ($src_w / $src_h));
        } elseif ($max_h == 0) {
            // Scale by width, keep height proportional
            $dst_w = $max_w;
            $dst_h = (int)($max_w / ($src_w / $src_h));
        } else {
            // Both dimensions provided, scale to fit and crop
            $source_aspect_ratio = $src_w / $src_h;
            $desired_aspect_ratio = $max_w / $max_h;

            if ($source_aspect_ratio > $desired_aspect_ratio) {
                $dst_h = $max_h;
                $dst_w = (int)($max_h * $source_aspect_ratio);
            } else {
                $dst_w = $max_w;
                $dst_h = (int)($max_w / $source_aspect_ratio);
            }
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
    $final_h = $target_h; // Always pass both dimensions, keepratio is handled in lower-level functions

    // Watermark configuration: use provided value or fall back to global config
    $watermark_file = ($set_watermark == false) ? null : (isset($cfg['th_logofile']) ? $cfg['th_logofile'] : null);
    if (!empty($watermark_file)) {
        $watermark_file = (strpos($watermark_file, SED_ROOT) === 0) ? $watermark_file : SED_ROOT . '/' . ltrim($watermark_file, '/');
        $watermark_file = file_exists($watermark_file) ? $watermark_file : null;
    }

    $watermark_pos = isset($cfg['th_logopos']) ? $cfg['th_logopos'] : 'Bottom left';
    $watermark_op = isset($cfg['th_logotrsp']) ? $cfg['th_logotrsp'] : 100;
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
            $dim_priority,
            $keepratio // Pass keepratio to Imagick
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
            $dim_priority,
            $keepratio // Pass keepratio to GD
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

        return $result;
    }
}

/* ---------- Deferred image upload UI (sedjs.imageUpload) ---------- */

/**
 * Resolve $_FILES entry for image upload drop field (with legacy fallback).
 *
 * @param string $drop_field Field name without [] (e.g. category_images_drop)
 * @param string $legacy_field Legacy field name (default dropped_images)
 * @return array|null
 */
function sed_image_upload_get_dropped_files($drop_field, $legacy_field = 'dropped_images')
{
	if (!empty($drop_field) && !empty($_FILES[$drop_field]['name'])) {
		return $_FILES[$drop_field];
	}
	if (!empty($legacy_field) && !empty($_FILES[$legacy_field]['name'])) {
		return $_FILES[$legacy_field];
	}
	return null;
}

/**
 * Normalized image extensions allowed for upload (from $cfg['gd_supported']).
 *
 * @return array Lowercase extensions without dots
 */
function sed_image_upload_gd_extensions()
{
	global $cfg;

	$list = array();
	if (!empty($cfg['gd_supported']) && is_array($cfg['gd_supported'])) {
		foreach ($cfg['gd_supported'] as $ext) {
			$ext = strtolower(trim($ext));
			if ($ext !== '' && !in_array($ext, $list, true)) {
				$list[] = $ext;
			}
		}
	}

	if (count($list) === 0) {
		$list = array('jpg', 'jpeg', 'png', 'gif', 'webp');
	}

	return $list;
}

/**
 * Build HTML accept attribute value from GD-supported extensions.
 *
 * @param array|null $extensions Extension list or null to use $cfg['gd_supported']
 * @return string Comma-separated MIME types and .ext entries
 */
function sed_image_upload_accept_attr($extensions = null)
{
	if ($extensions === null) {
		$extensions = sed_image_upload_gd_extensions();
	}

	$mime_map = array(
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'webp' => 'image/webp',
		'bmp' => 'image/bmp',
	);

	$parts = array();
	foreach ($extensions as $ext) {
		$ext = strtolower(trim($ext));
		if ($ext === '') {
			continue;
		}
		if (isset($mime_map[$ext]) && !in_array($mime_map[$ext], $parts, true)) {
			$parts[] = $mime_map[$ext];
		}
		$dot_ext = '.' . $ext;
		if (!in_array($dot_ext, $parts, true)) {
			$parts[] = $dot_ext;
		}
	}

	return implode(',', $parts);
}

/**
 * Register image upload widget JS/CSS (once per request, when widget HTML is built).
 */
function sed_image_upload_register_assets()
{
	static $done = false;
	if ($done) {
		return;
	}
	$done = true;
	sed_add_javascript('system/javascript/imageupload.js', true, 15);
	sed_add_css('system/adminskin/sympfy/css/imageupload.css', true, 15);
}

/**
 * Build HTML for deferred image upload widget (sedjs.imageUpload).
 *
 * @param array $opts prefix, title, max_files, sortable, dropzone, url_upload, existing, id, accept
 * @return string
 */
function sed_image_upload_html($opts)
{
	global $L;

	sed_image_upload_register_assets();

	$gd_extensions = sed_image_upload_gd_extensions();
	$gd_accept = sed_image_upload_accept_attr($gd_extensions);

	$defaults = array(
		'prefix' => 'images',
		'title' => '',
		'max_files' => 0,
		'sortable' => true,
		'dropzone' => true,
		'url_upload' => true,
		'existing' => array(),
		'id' => '',
		'accept' => $gd_accept,
	);
	$opts = array_merge($defaults, is_array($opts) ? $opts : array());

	if (empty($opts['accept'])) {
		$opts['accept'] = $gd_accept;
	}

	$prefix = preg_replace('/[^a-z0-9_]/i', '', $opts['prefix']);
	if ($prefix === '') {
		$prefix = 'images';
	}

	$max_files = (int)$opts['max_files'];
	$single = ($max_files === 1);
	if ($single) {
		$opts['sortable'] = false;
	}

	$widget_id = !empty($opts['id']) ? $opts['id'] : 'sed-image-upload-' . $prefix;
	$keep_name = $prefix . '[]';
	$drop_name = $single ? $prefix : ($prefix . '_drop[]');
	$upload_name = $single ? $prefix : ($prefix . '_upload[]');

	$label_drop = !empty($L['sed_image_upload_drop']) ? $L['sed_image_upload_drop'] : 'Drag files here';
	$label_add = !empty($L['sed_image_upload_add']) ? $L['sed_image_upload_add'] : 'Add image';
	$label_select = !empty($L['sed_image_upload_select']) ? $L['sed_image_upload_select'] : 'Choose file';
	$label_url = !empty($L['sed_image_upload_add_url']) ? $L['sed_image_upload_add_url'] : 'upload from the internet';
	$label_sort = !empty($L['sed_image_upload_sort_hint']) ? $L['sed_image_upload_sort_hint'] : '';
	$label_or = !empty($L['Or']) ? $L['Or'] : 'or';
	$label_delete = !empty($L['Delete']) ? $L['Delete'] : 'Delete';

	$config = array(
		'prefix' => $prefix,
		'maxFiles' => $max_files,
		'sortable' => !empty($opts['sortable']),
		'dropzone' => !empty($opts['dropzone']),
		'urlUpload' => !empty($opts['url_upload']),
		'accept' => $opts['accept'],
		'extensions' => $gd_extensions,
		'singleField' => $single,
		'labels' => array(
			'delete' => $label_delete,
			'select' => $label_select,
		),
	);

	$config_json = htmlspecialchars(json_encode($config), ENT_QUOTES, 'UTF-8');

	$mode_class = empty($opts['dropzone']) ? ' sed-image-upload-mode-tiles' : ' sed-image-upload-mode-dropzone';

	$html = '<div class="sed-image-upload' . $mode_class . '" id="' . sed_cc($widget_id) . '" data-sed-image-upload="' . $config_json . '">';

	if (!empty($opts['title'])) {
		$html .= '<h3>' . sed_cc($opts['title']) . '</h3>';
	}

	if (!empty($opts['sortable']) && $label_sort !== '') {
		$html .= '<p class="sed-image-upload-sort-hint">' . sed_cc($label_sort) . '</p>';
	}

	$html .= '<ul class="sed-image-upload-list">';

	if (!empty($opts['existing']) && is_array($opts['existing'])) {
		foreach ($opts['existing'] as $item) {
			if (empty($item['url'])) {
				continue;
			}
			$item_id = isset($item['id']) ? (int)$item['id'] : 0;
			$html .= '<li>';
			$html .= '<a href="#" class="delete" title="' . sed_cc($label_delete) . '"></a>';
			$html .= '<img src="' . sed_cc($item['url']) . '" alt="" />';
			if ($item_id > 0) {
				$html .= '<input type="hidden" name="' . sed_cc($keep_name) . '" value="' . $item_id . '" />';
			} elseif (!empty($item['keep'])) {
				$html .= '<input type="hidden" name="' . sed_cc($prefix . '_keep') . '" value="1" />';
			}
			$html .= '</li>';
		}
	}

	$html .= '</ul>';

	if (!empty($opts['dropzone'])) {
		$drop_multiple = $single ? '' : ' multiple="multiple"';
		$html .= '<div class="sed-image-upload-dropzone">';
		$html .= '<div class="sed-image-upload-dropicon"></div>';
		$html .= '<div class="sed-image-upload-dropmessage">' . sed_cc($label_drop) . '</div>';
		$html .= '<div class="sed-image-upload-dropor">' . sed_cc($label_or) . '</div>';
		$html .= '<button type="button" class="sed-image-upload-select-btn">' . sed_cc($label_select) . '</button>';
		$html .= '<input type="file" name="' . sed_cc($drop_name) . '"' . $drop_multiple . ' class="sed-image-upload-dropinput" accept="' . sed_cc($opts['accept']) . '" />';
		$html .= '</div>';
	}

	$html .= '<div class="sed-image-upload-add-box"></div>';

	if (!empty($opts['url_upload'])) {
		$html .= '<div class="sed-image-upload-url-link"><i class="dash_link">' . sed_cc($label_url) . '</i></div>';
	}

	$html .= '</div>';

	return $html;
}

/**
 * Move uploaded image to destination; optionally resize or crop to fit limits.
 *
 * @param string $tmp_path Source temp path
 * @param string $dest_path Destination path (relative to SED_ROOT or absolute)
 * @param int $max_w Target max width (0 = no limit)
 * @param int $max_h Target max height (0 = no limit)
 * @param string $mode resize|crop — fit inside box or center crop to exact size
 * @param bool $is_uploaded Whether tmp_path is from is_uploaded_file()
 * @return bool True on success
 */
function sed_image_upload_save($tmp_path, $dest_path, $max_w, $max_h, $mode = 'crop', $is_uploaded = true)
{
	$dest_abs = (strpos($dest_path, SED_ROOT) === 0) ? $dest_path : SED_ROOT . '/' . ltrim($dest_path, '/');

	if ($is_uploaded) {
		if (!is_uploaded_file($tmp_path) || !move_uploaded_file($tmp_path, $dest_abs)) {
			return false;
		}
	} elseif (!@copy($tmp_path, $dest_abs)) {
		return false;
	}

	$info = @getimagesize($dest_abs);
	if (!$info) {
		@unlink($dest_abs);
		return false;
	}

	$w = (int)$info[0];
	$h = (int)$info[1];
	$max_w = (int)$max_w;
	$max_h = (int)$max_h;

	$mode = ($mode === 'resize') ? 'resize' : 'crop';

	if ($max_w > 0 && $max_h > 0 && ($w > $max_w || $h > $max_h)) {
		$keepratio = ($mode === 'resize');
		$result = sed_image_process(
			$dest_abs,
			$dest_abs,
			$max_w,
			$max_h,
			$keepratio,
			$mode,
			'Width',
			null,
			false,
			true
		);
		if ($result !== true) {
			@unlink($dest_abs);
			return false;
		}
	}

	@chmod($dest_abs, 0666);
	return true;
}

/**
 * Generic deferred image upload POST processor (stub for extrafields / custom storage).
 *
 * @param array $opts Handler options (storage path, field prefix, callbacks)
 * @return array|false Processed file list or false
 */
function sed_image_upload_process($opts)
{
	// Stage 3: extrafields and custom entity handlers will implement storage here.
	return false;
}
