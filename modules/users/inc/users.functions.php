<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/inc/users.functions.php
Version=185
Updated=2026-feb-21
Type=Module
Author=Seditio Team
Description=Users API functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Returns link to user profile
 *
 * @param int $id User ID
 * @param string $user User name
 * @param int $group User group
 * @return string
 */
function sed_build_user($id, $user, $group = '')
{
	global $cfg, $sed_groups, $db_users;

	if ($cfg['color_group']) {
		if (($id > 0) && !empty($user) && empty($group)) {
			$sql = sed_sql_query("SELECT user_maingrp FROM $db_users WHERE user_id='$id' LIMIT 1");
			if (sed_sql_numrows($sql) > 0) {
				$row = sed_sql_fetchassoc($sql);
				$color = $sed_groups[$row['user_maingrp']]['color'];
			} else {
				$color = "inherit";
			}
		} elseif (($id > 0) && !empty($user) && !empty($group)) {
			$color =  $sed_groups[$group]['color'];
		} else {
			$color =  $sed_groups[1]['color'];
		}
	} else {
		$color = "inherit";
	}

	if (($id == 0 && !empty($user))) {
		$result = "<span style=\"color:" . $color . ";\">" . $user . "</span>";
	} elseif ($id == 0) {
		$result = '';
	} else {
		$result = (!empty($user)) ? sed_link(sed_url("users", "m=details&id=" . $id), "<span style=\"color:" . $color . ";\">" . $user . "</span></a>") : '?';
	}
	return ($result);
}

/**
 * Append cache-busting query to a local user image URL (mtime of file).
 *
 * @param string $image Image path relative to site root or absolute under SED_ROOT
 * @return string URL with ?timestamp when file exists
 */
function sed_userimage_url($image)
{
	if (empty($image)) {
		return '';
	}

	if (preg_match('#^(https?:)?//#i', $image) || strpos($image, 'data:') === 0) {
		return $image;
	}

	$path = $image;
	$qpos = strpos($path, '?');
	if ($qpos !== false) {
		$path = substr($path, 0, $qpos);
	}

	$abs = (strpos($path, SED_ROOT) === 0) ? $path : SED_ROOT . '/' . ltrim($path, '/');
	if (is_file($abs)) {
		return $path . '?' . filemtime($abs);
	}

	return $image;
}

/**
 * Returns user avatar image
 *
 * @param string $image Image src
 * @return string
 */
function sed_build_userimage($image)
{
	global $cfg;
	if (!empty($image)) {
		$result = "<img src=\"" . sed_userimage_url($image) . "\" alt=\"\" class=\"avatar post-author-avatar\" />";
	} else {
		$result = "<img src=\"" . sed_userimage_url($cfg['defav_dir'] . 'default.png') . "\" alt=\"\" class=\"avatar post-author-avatar\" />";
	}
	return ($result);
}

/**
 * Parsing user signature text
 *
 * @param string $text Signature text
 * @return string
 */
function sed_build_usertext($text)
{
	global $cfg;
	/*
    $text = sed_cc($text); 
    $text = nl2br($text); 
	*/
	return ($text);
}

/**
 * Returns group membership checkboxes
 *
 * @param int $userid Edited user ID
 * @param bool $edit Permission
 * @param int $maingrp User main group
 * @return string
 */
function sed_build_groupsms($userid, $edit = false, $maingrp = 0)
{
	global $db_groups_users, $sed_groups, $L;

	$sql = sed_sql_query("SELECT gru_groupid FROM $db_groups_users WHERE gru_userid='$userid'");

	while ($row = sed_sql_fetchassoc($sql)) {
		$member[$row['gru_groupid']] = true;
	}

	$res = '';
	foreach ($sed_groups as $k => $i) {
		$checked = (isset($member[$k]) && $member[$k]) ? "checked=\"checked\"" : '';
		$checked_maingrp = ($maingrp == $k) ? "checked=\"checked\"" : '';
		$readonly = (!$edit || $k == 1 || $k == 2 || $k == 3 || ($k == 5 && $userid == 1)) ? "disabled=\"disabled\"" : '';
		$readonly_maingrp = (!$edit || $k == 1 || ($k == 2 && $userid == 1) || ($k == 3 && $userid == 1)) ? "disabled=\"disabled\"" : '';

		if ((isset($member[$k]) && $member[$k]) || $edit) {
			if (!($sed_groups[$k]['hidden'] && !sed_auth('users', 'a', 'A'))) {
				$res .= "<span class=\"radio-item\"><input type=\"radio\" class=\"radio\" id=\"rusermaingrp_$k\" name=\"rusermaingrp\" value=\"$k\" " . $checked_maingrp . " " . $readonly_maingrp . " /><label for=\"rusermaingrp_$k\"></label></span>\n";
				$res .= "<span class=\"checkbox-item\"><input type=\"checkbox\" class=\"checkbox\" id=\"rusergroupsms_$k\" name=\"rusergroupsms[$k]\" " . $checked . " $readonly /><label for=\"rusergroupsms_$k\"></label></span>\n";
				$res .= ($k == 1) ? $sed_groups[$k]['title'] : sed_link(sed_url("users", "g=" . $k), $sed_groups[$k]['title']);
				$res .= ($sed_groups[$k]['hidden']) ? ' (' . $L['Hidden'] . ')' : '';
				$res .= "<br />";
			}
		}
	}

	return ($res);
}

/**
 * Gets huge user selection box
 *
 * @param int $to Selected user ID
 * @return string
 */
function sed_selectbox_users($to)
{
	global $db_users;

	$result = "<select name=\"userid\">";
	$sql = sed_sql_query("SELECT user_id, user_name FROM $db_users ORDER BY user_name ASC");
	while ($row = sed_sql_fetchassoc($sql)) {
		$selected = ($row['user_id'] == $to) ? "selected=\"selected\"" : '';
		$result .= "<option value=\"" . $row['user_id'] . "\" $selected>" . sed_cc($row['user_name']) . "</option>";
	}
	$result .= "</select>";
	return ($result);
}

/**
 * Fetches user entry from DB
 *
 * @param int $id User ID
 * @return array
 */
function sed_userinfo($id)
{
	global $db_users;

	$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_id='$id'");
	if ($res = sed_sql_fetchassoc($sql)) {
		return ($res);
	} else {
		$res['user_name'] = '?';
		return ($res);
	}
}

/**
 * Checks whether user is online
 *
 * @param int $id User ID
 * @return bool
 */
function sed_userisonline($id)
{
	global $sed_usersonline;

	$res = FALSE;
	if (is_array($sed_usersonline)) {
		$res = (in_array($id, $sed_usersonline)) ? TRUE : FALSE;
	}
	return ($res);
}

/**
 * Profile image kind limits and POST/DB mapping.
 *
 * @param string $kind avatar|photo|signature
 * @return array|false
 */
function sed_users_profile_image_limits($kind)
{
	global $cfg;

	$map = array(
		'avatar' => array(
			'prefix' => 'userfile',
			'dest_dir' => $cfg['av_dir'],
			'basename_suffix' => '-avatar',
			'max_w' => $cfg['av_maxx'],
			'max_h' => $cfg['av_maxy'],
			'max_size' => $cfg['av_maxsize'],
			'db_field' => 'user_avatar',
			'hook' => 'profile.update.avatar',
			'mode' => 'crop',
		),
		'photo' => array(
			'prefix' => 'userphoto',
			'dest_dir' => $cfg['photos_dir'],
			'basename_suffix' => '-photo',
			'max_w' => $cfg['ph_maxx'],
			'max_h' => $cfg['ph_maxy'],
			'max_size' => $cfg['ph_maxsize'],
			'db_field' => 'user_photo',
			'hook' => 'profile.update.photo',
			'mode' => 'resize',
		),
		'signature' => array(
			'prefix' => 'usersig',
			'dest_dir' => $cfg['sig_dir'],
			'basename_suffix' => '-signature',
			'max_w' => $cfg['sig_maxx'],
			'max_h' => $cfg['sig_maxy'],
			'max_size' => $cfg['sig_maxsize'],
			'db_field' => 'user_signature',
			'hook' => 'profile.update.signature',
			'mode' => 'resize',
		),
	);

	return isset($map[$kind]) ? $map[$kind] : false;
}

/**
 * Whether user attempted a profile image upload in this request.
 *
 * @param array $file $_FILES element
 * @return bool
 */
function sed_users_profile_image_attempted($file)
{
	if (empty($file) || !is_array($file) || empty($file['name'])) {
		return false;
	}

	$upload_error = isset($file['error']) ? (int)$file['error'] : UPLOAD_ERR_NO_FILE;
	if ($upload_error !== UPLOAD_ERR_NO_FILE) {
		return true;
	}

	return !empty($file['tmp_name']);
}

/**
 * Validate and save one profile image upload.
 *
 * @param array $file $_FILES element
 * @param int $user_id User ID
 * @param string $kind avatar|photo|signature
 * @param bool $enforce_size Apply configured max file size in bytes
 * @return array Result with success flag; on success path, filename, extension
 */
function sed_users_profile_image_save($file, $user_id, $kind, $enforce_size = true)
{
	global $cfg;

	$limits = sed_users_profile_image_limits($kind);
	if (!$limits) {
		return array('success' => false, 'error' => 'unknown_kind');
	}

	$upload_error = isset($file['error']) ? (int)$file['error'] : UPLOAD_ERR_NO_FILE;
	if ($upload_error === UPLOAD_ERR_NO_FILE) {
		return array('success' => false, 'error' => 'no_file');
	}
	if ($upload_error !== UPLOAD_ERR_OK) {
		return array('success' => false, 'error' => 'php_upload', 'php_error' => $upload_error);
	}

	if (empty($file['tmp_name']) || empty($file['name']) || !is_uploaded_file($file['tmp_name'])) {
		return array('success' => false, 'error' => 'not_uploaded');
	}

	$size = isset($file['size']) ? (int)$file['size'] : 0;
	if ($size <= 0) {
		return array('success' => false, 'error' => 'empty');
	}
	if ($enforce_size && $size > (int)$limits['max_size']) {
		return array('success' => false, 'error' => 'size', 'max_size' => (int)$limits['max_size']);
	}

	$dotpos = mb_strrpos($file['name'], '.') + 1;
	$ext = mb_strtolower(mb_substr($file['name'], $dotpos, 5));
	if ($ext === '' || !in_array($ext, $cfg['gd_supported'])) {
		return array('success' => false, 'error' => 'extension');
	}

	$user_id = (int)$user_id;
	$basename = $user_id . $limits['basename_suffix'];
	$filename = $basename . '.' . $ext;
	$dest_path = $limits['dest_dir'] . $filename;

	if (file_exists($dest_path)) {
		@unlink($dest_path);
	}

	$mode = (isset($limits['mode']) && $limits['mode'] === 'resize') ? 'resize' : 'crop';

	if (!sed_image_upload_save($file['tmp_name'], $dest_path, (int)$limits['max_w'], (int)$limits['max_h'], $mode, true)) {
		return array('success' => false, 'error' => 'process');
	}

	return array(
		'success' => true,
		'path' => $dest_path,
		'filename' => $filename,
		'extension' => $ext,
	);
}

/**
 * Persist saved profile image path to DB (and PFS when active).
 *
 * @param int $user_id User ID
 * @param string $kind avatar|photo|signature
 * @param array $saved Result from sed_users_profile_image_save()
 * @return bool
 */
function sed_users_profile_image_apply($user_id, $kind, $saved)
{
	global $db_users, $db_pfs;

	$limits = sed_users_profile_image_limits($kind);
	if (!$limits || empty($saved['success']) || empty($saved['path'])) {
		return false;
	}

	$user_id = (int)$user_id;
	$path = $saved['path'];
	$filename = $saved['filename'];
	$extension = $saved['extension'];
	$db_field = $limits['db_field'];

	/* === Hook === */
	$extp = sed_getextplugins($limits['hook']);
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$file_size = filesize($path);
	sed_sql_query("UPDATE $db_users SET " . $db_field . "='" . sed_sql_prep($path) . "' WHERE user_id='" . $user_id . "'");

	if (sed_module_active('pfs')) {
		sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='" . sed_sql_prep($filename) . "'");
		sed_sql_query("INSERT INTO $db_pfs (pfs_userid, pfs_file, pfs_extension, pfs_folderid, pfs_desc, pfs_size, pfs_count) VALUES (" . $user_id . ", '" . sed_sql_prep($filename) . "', '" . sed_sql_prep($extension) . "', -1, '', " . (int)$file_size . ", 0)");
	}

	return true;
}

/**
 * Build localized profile image upload error line.
 *
 * @param string $kind avatar|photo|signature
 * @param array $result Result from sed_users_profile_image_save()
 * @return string HTML line with trailing br
 */
function sed_users_profile_image_error_message($kind, $result)
{
	global $L;

	if (!empty($result['success']) || empty($result['error']) || $result['error'] === 'no_file') {
		return '';
	}

	$labels = array(
		'avatar' => !empty($L['pro_avatarsupload']) ? $L['pro_avatarsupload'] : 'Avatar',
		'photo' => !empty($L['pro_photoupload']) ? $L['pro_photoupload'] : 'Photo',
		'signature' => !empty($L['pro_sigupload']) ? $L['pro_sigupload'] : 'Signature',
	);

	$label = isset($labels[$kind]) ? $labels[$kind] : $kind;
	$code = $result['error'];

	switch ($code) {
		case 'size':
			$max = isset($result['max_size']) ? (int)$result['max_size'] : 0;
			$max_label = sed_format_size($max);
			$msg = !empty($L['pro_imageupload_toobig']) ? sprintf($L['pro_imageupload_toobig'], $max_label) : 'File is too large';
			break;
		case 'extension':
			$exts = implode(', ', sed_image_upload_gd_extensions());
			$msg = !empty($L['pro_imageupload_badext']) ? sprintf($L['pro_imageupload_badext'], $exts) : 'File type not allowed';
			break;
		case 'process':
			$msg = !empty($L['pro_imageupload_process']) ? $L['pro_imageupload_process'] : 'Image processing failed';
			break;
		case 'php_upload':
			$php_err = isset($result['php_error']) ? (int)$result['php_error'] : 0;
			if ($php_err === UPLOAD_ERR_INI_SIZE || $php_err === UPLOAD_ERR_FORM_SIZE) {
				$msg = !empty($L['pro_imageupload_toobig_server']) ? $L['pro_imageupload_toobig_server'] : 'File exceeds server upload limit';
			} else {
				$msg = !empty($L['pro_imageupload_php']) ? sprintf($L['pro_imageupload_php'], $php_err) : 'Upload error';
			}
			break;
		default:
			if ($kind === 'avatar' && !empty($L['pro_avataruploadfailed'])) {
				$msg = $L['pro_avataruploadfailed'];
			} elseif ($kind === 'photo' && !empty($L['pro_photouploadfailed'])) {
				$msg = $L['pro_photouploadfailed'];
			} elseif ($kind === 'signature' && !empty($L['pro_siguploadfailed'])) {
				$msg = $L['pro_siguploadfailed'];
			} else {
				$msg = !empty($L['pro_imageupload_failed']) ? $L['pro_imageupload_failed'] : 'Upload failed';
			}
			break;
	}

	if (!empty($L['pro_imageupload_item'])) {
		return sprintf($L['pro_imageupload_item'], $label, $msg) . '<br />';
	}

	return $label . ': ' . $msg . '<br />';
}

/**
 * Delete one profile image (avatar, photo, signature) from disk and DB.
 *
 * @param int $user_id User ID
 * @param string $kind avatar|photo|signature
 * @return bool
 */
function sed_users_profile_image_delete($user_id, $kind)
{
	global $cfg, $db_users, $db_pfs;

	$limits = sed_users_profile_image_limits($kind);
	if (!$limits) {
		return false;
	}

	$user_id = (int)$user_id;
	if ($user_id <= 0) {
		return false;
	}

	$db_field = $limits['db_field'];
	$sql = sed_sql_query("SELECT $db_field FROM $db_users WHERE user_id='$user_id' LIMIT 1");
	if (sed_sql_numrows($sql) == 0) {
		return false;
	}
	$row = sed_sql_fetchassoc($sql);
	$path = isset($row[$db_field]) ? $row[$db_field] : '';
	if ($path === '') {
		return true;
	}

	$file = str_replace($limits['dest_dir'], '', $path);
	$skip_unlink = ($kind === 'avatar' && mb_strpos($path, $cfg['defav_dir']) !== false);

	if (!$skip_unlink && file_exists($path)) {
		@unlink($path);
	}

	if (sed_module_active('pfs') && $file !== '') {
		sed_sql_query("DELETE FROM $db_pfs WHERE pfs_file='" . sed_sql_prep($file) . "'");
	}

	sed_sql_query("UPDATE $db_users SET $db_field='' WHERE user_id='$user_id'");
	return true;
}

/**
 * Remove profile images cleared in the upload widget (empty list on save).
 *
 * @param int $user_id User ID
 * @param array $saved Kinds saved in this request via sed_users_profile_images_process()
 */
function sed_users_profile_images_process_removals($user_id, $saved = array())
{
	global $db_users;

	$user_id = (int)$user_id;
	if ($user_id <= 0) {
		return;
	}

	$sql = sed_sql_query("SELECT user_avatar, user_photo, user_signature FROM $db_users WHERE user_id='$user_id' LIMIT 1");
	if (sed_sql_numrows($sql) == 0) {
		return;
	}
	$row = sed_sql_fetchassoc($sql);

	foreach (array('avatar', 'photo', 'signature') as $kind) {
		if (!empty($saved[$kind])) {
			continue;
		}

		$limits = sed_users_profile_image_limits($kind);
		if (!$limits) {
			continue;
		}

		$db_field = $limits['db_field'];
		if (empty($row[$db_field])) {
			continue;
		}

		$keep_field = $limits['prefix'] . '_keep';
		if (sed_import($keep_field, 'P', 'BOL')) {
			continue;
		}

		if (isset($_FILES[$limits['prefix']]) && sed_users_profile_image_attempted($_FILES[$limits['prefix']])) {
			continue;
		}

		sed_users_profile_image_delete($user_id, $kind);
	}
}

/**
 * Process all profile image uploads from current POST (avatar, photo, signature).
 *
 * @param int $user_id User ID
 * @param bool $enforce_size Apply configured max file size in bytes
 * @return array errors (array of HTML strings), saved (array kind => saved data)
 */
function sed_users_profile_images_process($user_id, $enforce_size = true)
{
	$errors = array();
	$saved = array();
	$attempted = false;

	foreach (array('avatar', 'photo', 'signature') as $kind) {
		$limits = sed_users_profile_image_limits($kind);
		if (!$limits) {
			continue;
		}

		$prefix = $limits['prefix'];
		if (!isset($_FILES[$prefix])) {
			continue;
		}

		if (!sed_users_profile_image_attempted($_FILES[$prefix])) {
			continue;
		}

		$attempted = true;
		$result = sed_users_profile_image_save($_FILES[$prefix], $user_id, $kind, $enforce_size);

		if (!empty($result['success'])) {
			sed_users_profile_image_apply($user_id, $kind, $result);
			$saved[$kind] = $result;
		} else {
			$msg = sed_users_profile_image_error_message($kind, $result);
			if ($msg !== '') {
				$errors[] = $msg;
			}
		}
	}

	if ($attempted) {
		@clearstatcache();
	}

	return array(
		'errors' => $errors,
		'saved' => $saved,
	);
}

/**
 * Build profile image upload widget HTML for avatar, photo, or signature.
 *
 * @param string $kind avatar|photo|signature
 * @param array $urr User row from DB
 * @param string $widget_id DOM id for sedjs.imageUpload container
 * @param array $opts enforce_size (bool, default true)
 * @return string
 */
function sed_users_profile_image_upload_html($kind, $urr, $widget_id, $opts = array())
{
	global $L;

	$enforce_size = !isset($opts['enforce_size']) || !empty($opts['enforce_size']);
	$limits = sed_users_profile_image_limits($kind);
	if (!$limits || !is_array($urr)) {
		return '';
	}

	$labels = array(
		'avatar' => !empty($L['pro_avatarsupload']) ? $L['pro_avatarsupload'] : 'Avatar',
		'photo' => !empty($L['pro_photoupload']) ? $L['pro_photoupload'] : 'Photo',
		'signature' => !empty($L['pro_sigupload']) ? $L['pro_sigupload'] : 'Signature',
	);
	$label = isset($labels[$kind]) ? $labels[$kind] : $kind;

	$html = $label;
	if ($enforce_size) {
		$html .= ' (' . $limits['max_w'] . 'x' . $limits['max_h'] . ', ' . sed_format_size((int)$limits['max_size']) . ')';
	} elseif ((int)$limits['max_w'] > 0 || (int)$limits['max_h'] > 0) {
		$html .= ' (' . $limits['max_w'] . 'x' . $limits['max_h'] . ')';
	}
	$html .= '<br />';

	if ($enforce_size && (int)$limits['max_size'] > 0) {
		$html .= sed_textbox_hidden('MAX_FILE_SIZE', (int)$limits['max_size']);
	}

	$existing = array();
	$db_field = $limits['db_field'];
	if (!empty($urr[$db_field])) {
		$existing[] = array(
			'id' => 0,
			'url' => sed_userimage_url($urr[$db_field]),
			'keep' => true,
		);
	}

	$html .= sed_image_upload_html(array(
		'prefix' => $limits['prefix'],
		'max_files' => 1,
		'sortable' => false,
		'dropzone' => false,
		'url_upload' => false,
		'existing' => $existing,
		'id' => $widget_id,
	));

	return $html;
}
