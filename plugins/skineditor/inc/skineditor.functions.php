<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/skineditor/inc/skineditor.functions.php
Version=185
Updated=2026-apr-03
Type=Plugin
Author=Seditio Team
Description=Skineditor helpers
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * @param string[] $allow_extensions
 */
function skineditor_tpl_is_editable($filename, $allow_extensions)
{
	if ($filename === '.' || $filename === '..') {
		return false;
	}
	$bn = basename($filename);
	if ($bn !== $filename) {
		return false;
	}
	if (preg_match('/^admin\..*\.tpl$/i', $bn)) {
		return false;
	}
	$extension_arr = explode(".", $bn);
	$extension = end($extension_arr);
	return in_array(mb_strtolower($extension), $allow_extensions) && (mb_strrpos($bn, ".") > 0);
}

/**
 * @param string[] $allow_extensions
 * @return string|false absolute normalized path
 */
function skineditor_safe_file_under_root($root, $filename, $allow_extensions)
{
	$bn = basename($filename);
	if (!skineditor_tpl_is_editable($bn, $allow_extensions)) {
		return false;
	}
	$root = rtrim(str_replace('\\', '/', $root), '/') . '/';
	$full = $root . $bn;
	if (!is_file($full)) {
		return false;
	}
	$rp_root = realpath($root);
	$rp_file = realpath($full);
	if ($rp_root === false || $rp_file === false) {
		return false;
	}
	$rp_root = str_replace('\\', '/', $rp_root) . '/';
	$rp_file = str_replace('\\', '/', $rp_file);
	if (strpos($rp_file, $rp_root) !== 0) {
		return false;
	}
	return $full;
}

/**
 * @return array|null scope, root, code, title
 */
function skineditor_resolve_context($scope, $sk, $mod, $pl)
{
	if ($scope === 'module') {
		if ($mod === '' || $mod === null) {
			return null;
		}
		$root = SED_ROOT . '/modules/' . $mod . '/tpl/';
		if (!is_dir($root)) {
			return null;
		}
		return array(
			'scope' => 'module',
			'root' => $root,
			'code' => $mod,
			'title' => $mod
		);
	}
	if ($scope === 'plugin') {
		if ($pl === '' || $pl === null) {
			return null;
		}
		$root = SED_ROOT . '/plugins/' . $pl . '/tpl/';
		if (!is_dir($root)) {
			return null;
		}
		return array(
			'scope' => 'plugin',
			'root' => $root,
			'code' => $pl,
			'title' => $pl
		);
	}
	if ($sk === '' || $sk === null) {
		return null;
	}
	$skininfo = SED_ROOT . '/skins/' . $sk . '/' . $sk . '.php';
	if (!file_exists($skininfo)) {
		return null;
	}
	$info = sed_infoget($skininfo);
	$root = SED_ROOT . '/skins/' . $sk . '/';
	return array(
		'scope' => 'skin',
		'root' => $root,
		'code' => $sk,
		'title' => $info['Name']
	);
}

function skineditor_context_query($ctx)
{
	if ($ctx['scope'] === 'module') {
		return 'scope=module&mod=' . $ctx['code'];
	}
	if ($ctx['scope'] === 'plugin') {
		return 'scope=plugin&pl=' . $ctx['code'];
	}
	return 'sk=' . $ctx['code'];
}

function skineditor_list_back_url($ctx)
{
	return sed_url('admin', 'm=manage&p=skineditor&' . skineditor_context_query($ctx));
}

/**
 * @param string[] $allow_extensions
 */
function skineditor_tpldir_has_editable($tpldir, $allow_extensions)
{
	if (!is_dir($tpldir)) {
		return false;
	}
	$h = @opendir($tpldir);
	if ($h === false) {
		return false;
	}
	while (($fe = readdir($h)) !== false) {
		if (skineditor_tpl_is_editable($fe, $allow_extensions)) {
			closedir($h);
			return true;
		}
	}
	closedir($h);
	return false;
}

/**
 * @return string[]
 */
function skineditor_scan_ext_with_tpl($subdir, $allow_extensions)
{
	$out = array();
	$base = SED_ROOT . '/' . $subdir . '/';
	if (!is_dir($base)) {
		return $out;
	}
	$h = @opendir($base);
	if ($h === false) {
		return $out;
	}
	while (($name = readdir($h)) !== false) {
		if ($name === '.' || $name === '..' || mb_strpos($name, '.') !== false) {
			continue;
		}
		$tpldir = $base . $name . '/tpl/';
		if (skineditor_tpldir_has_editable($tpldir, $allow_extensions)) {
			$out[] = $name;
		}
	}
	closedir($h);
	sort($out);
	return $out;
}
