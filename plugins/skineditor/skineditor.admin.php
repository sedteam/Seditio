<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/skineditor/skineditor.php
Version=185
Updated=2026-feb-14
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=skineditor
Part=admin
File=skineditor.admin
Hooks=tools
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

require_once SED_ROOT . '/plugins/skineditor/inc/skineditor.functions.php';

$scope_raw = sed_import('scope', 'G', 'ALP', 16);
$scope = ($scope_raw === 'module' || $scope_raw === 'plugin') ? $scope_raw : 'skin';
$sk = sed_import('sk', 'G', 'ALP', 24);
$mod = sed_import('mod', 'G', 'ALP', 24);
$pl = sed_import('pl', 'G', 'ALP', 24);
$f = sed_import('f', 'G', 'TXT', 128);
$fb = sed_import('fb', 'G', 'TXT', 128);

$error_string = '';

$allow_extensions = array('tpl', 'txt', 'css', 'js');

global $L;

$mskin = sed_skinfile('admin.skineditor', false, true);
if ($mskin === '') {
	$mskin = SED_ROOT . '/plugins/skineditor/tpl/admin.skineditor.tpl';
}
$sx = new XTemplate($mskin);

$ctx = skineditor_resolve_context($scope, $sk, $mod, $pl);

$n = 'home';
if ($ctx !== null) {
	$n = ($f !== '' && $f !== null) ? 'edit' : 'list';
}

switch ($n) {
	case 'list':
		$adminpath[] = array(skineditor_list_back_url($ctx), $ctx['title']);

		$root = $ctx['root'];
		$qbase = skineditor_context_query($ctx);

		if ($a == 'makbak') {
			sed_check_xg();
			$fbn = basename($fb);
			$src = skineditor_safe_file_under_root($root, $fbn, $allow_extensions);
			if ($src !== false) {
				copy($src, $src . '.bak');
			}
			sed_redirect(sed_url('admin', 'm=manage&p=skineditor&' . $qbase, '', true), false, array('msg' => '917'));
			exit;
		} elseif ($a == 'resbak') {
			sed_check_xg();
			$fbn = basename($fb);
			$src = skineditor_safe_file_under_root($root, $fbn, $allow_extensions);
			if ($src !== false) {
				$bak = $src . '.bak';
				if (is_file($bak)) {
					if (is_file($src)) {
						unlink($src);
					}
					if (copy($bak, $src)) {
						unlink($bak);
					}
				}
			}
			sed_redirect(sed_url('admin', 'm=manage&p=skineditor&' . $qbase, '', true), false, array('msg' => '917'));
			exit;
		} elseif ($a == 'delbak') {
			sed_check_xg();
			$fbn = basename($fb);
			$src = skineditor_safe_file_under_root($root, $fbn, $allow_extensions);
			if ($src !== false && is_file($src . '.bak')) {
				unlink($src . '.bak');
			}
			sed_redirect(sed_url('admin', 'm=manage&p=skineditor&' . $qbase, '', true), false, array('msg' => '302'));
			exit;
		}

		$skinlist = array();
		$baklist = array();
		$handle = opendir($root);
		if ($handle === false) {
			sed_die('Could not open directory');
		}
		while (($fe = readdir($handle)) !== false) {
			$extension_arr = explode(".", $fe);
			$extension = end($extension_arr);
			if (skineditor_tpl_is_editable($fe, $allow_extensions)) {
				$skinlist[] = $fe;
			} elseif (mb_strtolower($extension) == 'bak') {
				$baklist[] = $fe;
			}
		}
		closedir($handle);
		sort($skinlist);

		$backupfile = array();
		if (isset($baklist) && is_array($baklist)) {
			@sort($baklist);
			foreach ($baklist as $i => $xb) {
				$backupfile[$xb] = true;
			}
		}

		$j = 0;
		$total_size = 0;

		if ($ctx['scope'] === 'skin') {
			$list_title = $L['skineditor_tpl_skin'] . ': ' . sed_cc($ctx['title']);
		} elseif ($ctx['scope'] === 'module') {
			$list_title = $L['skineditor_tpl_module'] . ': ' . sed_cc($ctx['code']);
		} else {
			$list_title = $L['skineditor_tpl_plugin'] . ': ' . sed_cc($ctx['code']);
		}
		$sx->assign('TPL_SKIN_LIST_HEADER', $list_title);

		foreach ($skinlist as $i => $x) {
			$file_size = @filesize($root . $x);

			$sx->assign(array(
				'TPL_SKIN_LIST_ROW_EDIT' => '<a href="' . sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&f=' . rawurlencode($x)) . '">' . $out['ic_edit'] . '</a>',
				'TPL_SKIN_LIST_ROW_EDITURL' => sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&f=' . rawurlencode($x)),
				'TPL_SKIN_LIST_ROW_TPLFILE' => sed_cc($x),
				'TPL_SKIN_LIST_ROW_TPLSIZE' => $file_size
			));

			$xbak = $x . '.bak';
			$bcf = (isset($backupfile[$xbak]) && $backupfile[$xbak]) ? true : false;

			$sx->assign(array(
				'TPL_SKIN_LIST_ROW_BACKUP' => ($bcf) ? '' : '<a href="' . sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=makbak&' . sed_xg()) . '">' . $out['ic_checked'] . '</a>',
				'TPL_SKIN_LIST_ROW_BACKUPURL' => ($bcf) ? '' : sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=makbak&' . sed_xg()),
				'TPL_SKIN_LIST_ROW_DELETE_BACKUP' => ($bcf) ? '<a href="' . sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=delbak&' . sed_xg()) . '">' . $out['ic_unchecked'] . '</a>' : '',
				'TPL_SKIN_LIST_ROW_DELETE_BACKUPURL' => ($bcf) ? sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=delbak&' . sed_xg()) : '',
				'TPL_SKIN_LIST_ROW_RESTORE_BACKUP' => ($bcf) ? '<a href="' . sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=resbak&' . sed_xg()) . '">' . $out['ic_reset'] . '</a>' : '',
				'TPL_SKIN_LIST_ROW_RESTORE_BACKUPURL' => ($bcf) ? sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&fb=' . rawurlencode($x) . '&a=resbak&' . sed_xg()) : '',
				'TPL_SKIN_LIST_ROW_XBACKUP' => ($bcf) ? $xbak : ''
			));

			$j++;
			$total_size += $file_size;

			$sx->parse('SKINEDITOR.TPL_SKIN_LIST.TPL_SKIN_LIST_ROW');
		}

		$sx->assign(array(
			'TPL_SKIN_LIST_TOTALSIZE' => $total_size,
			'TPL_SKIN_LIST_FILES' => $j
		));

		$sx->parse('SKINEDITOR.TPL_SKIN_LIST');

		break;

	case 'edit':
		$b1 = sed_import('b1', 'P', 'ALP', 16);
		$adminpath[] = array(skineditor_list_back_url($ctx), $ctx['title']);

		$root = $ctx['root'];
		$qbase = skineditor_context_query($ctx);
		$editfile = skineditor_safe_file_under_root($root, $f, $allow_extensions);

		if ($editfile === false) {
			sed_die('Wrong file name');
		}

		$fbasename = basename($editfile);
		$extension_arr = explode('.', $fbasename);
		$extension = end($extension_arr);

		if ($a == 'update') {
			$content = sed_import('content', 'P', 'HTR');
			$file_isup = true;
			if (!($fp = @fopen($editfile, 'w'))) {
				$file_isup = false;
			} else {
				if (!(@fwrite($fp, $content))) {
					$file_isup = false;
				}
				@fclose($fp);
			}

			if ($file_isup) {
				if ($b1) {
					sed_redirect(sed_url('admin', 'm=manage&p=skineditor&' . $qbase, '', true), false, array('msg' => '917'));
					exit;
				} else {
					sed_redirect(sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&f=' . rawurlencode($fbasename), '', true), false, array('msg' => '917'));
					exit;
				}
			} else {
				$error_string .= 'Error !<br />Could not write the file : ' . sed_cc($editfile);
			}
		}

		if (!($fp = @fopen($editfile, 'r'))) {
			sed_die('Could not open the file');
		}

		$filecont = fread($fp, 256000);
		@fclose($fp);

		switch ($extension) {
			case 'css':
				$hmode = 'text/css';
				break;
			case 'js':
				$hmode = 'text/javascript';
				break;
			case 'php':
				$hmode = 'application/x-httpd-php';
				break;
			default:
				$hmode = 'text/html';
		}

		$sx->assign(array(
			'TPL_SKIN_EDIT_SEND' => sed_url('admin', 'm=manage&p=skineditor&' . $qbase . '&f=' . rawurlencode($fbasename) . '&a=update&' . sed_xg()),
			'TPL_SKIN_EDIT_FILE' => sed_cc($editfile),
			'TPL_SKIN_EDIT_CHANCEL_URL' => skineditor_list_back_url($ctx),
			'TPL_SKIN_EDIT_TEXTAREA' => '<textarea cols="96" rows="16" name="content" id="content" class="noeditor">' . sed_cc($filecont, ENT_QUOTES) . '</textarea>',
			'TPL_SKIN_EDIT_HMODE' => $hmode
		));

		$sx->parse('SKINEDITOR.TPL_SKIN_EDIT');

		break;

	default:
		$handle = opendir(SED_ROOT . '/skins/');
		$skinlist = array();
		if ($handle !== false) {
			while (($fe = readdir($handle)) !== false) {
				if (mb_strpos($fe, '.') === false) {
					$skinlist[] = $fe;
				}
			}
			closedir($handle);
		}
		sort($skinlist);

		foreach ($skinlist as $i => $x) {
			$skininfo = SED_ROOT . '/skins/' . $x . '/' . $x . '.php';
			$info = sed_infoget($skininfo);

			$sx->assign(array(
				'SKIN_LIST_ROW_EDIT' => '<a href="' . sed_url('admin', 'm=manage&p=skineditor&sk=' . $x) . '">' . $out['ic_edit'] . '</a>',
				'SKIN_LIST_ROW_EDITURL' => sed_url('admin', 'm=manage&p=skineditor&sk=' . $x),
				'SKIN_LIST_ROW_NAME' => $info['Name'],
				'SKIN_LIST_ROW_CODE' => $x,
				'SKIN_LIST_ROW_VERSION' => $info['Version'],
				'SKIN_LIST_ROW_UPDATED' => $info['Updated'],
				'SKIN_LIST_ROW_AUTHOR' => $info['Author'],
				'SKIN_LIST_ROW_DEFAULT' => ($x == $cfg['defaultskin']) ? $out['ic_checked'] : '',
				'SKIN_LIST_ROW_SET' => ($x == $skin) ? $out['ic_checked'] : ''
			));

			$sx->parse('SKINEDITOR.SKIN_LIST.SKIN_LIST_ROW');
		}

		$sx->parse('SKINEDITOR.SKIN_LIST');

		$modlist = skineditor_scan_ext_with_tpl('modules', $allow_extensions);
		foreach ($modlist as $x) {
			$sx->assign(array(
				'MODULE_LIST_ROW_EDIT' => '<a href="' . sed_url('admin', 'm=manage&p=skineditor&scope=module&mod=' . $x) . '">' . $out['ic_edit'] . '</a>',
				'MODULE_LIST_ROW_EDITURL' => sed_url('admin', 'm=manage&p=skineditor&scope=module&mod=' . $x),
				'MODULE_LIST_ROW_CODE' => sed_cc($x)
			));
			$sx->parse('SKINEDITOR.MODULE_LIST.MODULE_LIST_ROW');
		}
		$sx->parse('SKINEDITOR.MODULE_LIST');

		$pluglist = skineditor_scan_ext_with_tpl('plugins', $allow_extensions);
		foreach ($pluglist as $x) {
			$sx->assign(array(
				'PLUGIN_LIST_ROW_EDIT' => '<a href="' . sed_url('admin', 'm=manage&p=skineditor&scope=plugin&pl=' . $x) . '">' . $out['ic_edit'] . '</a>',
				'PLUGIN_LIST_ROW_EDITURL' => sed_url('admin', 'm=manage&p=skineditor&scope=plugin&pl=' . $x),
				'PLUGIN_LIST_ROW_CODE' => sed_cc($x)
			));
			$sx->parse('SKINEDITOR.PLUGIN_LIST.PLUGIN_LIST_ROW');
		}
		$sx->parse('SKINEDITOR.PLUGIN_LIST');

		break;
}

if (!empty($error_string)) {
	$sx->assign('SKINEDITOR_ERROR_BODY', sed_alert($error_string, 'e'));
	$sx->parse('SKINEDITOR.SKINEDITOR_ERROR');
}

$sx->parse('SKINEDITOR');
$plugin_body = $sx->text('SKINEDITOR');
