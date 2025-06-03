<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/skineditor/skineditor.php
Version=180
Updated=2025-jan-25
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
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$sk = sed_import('sk', 'G', 'ALP', 24);
$f = sed_import('f', 'G', 'TXT', 128);
$fb = sed_import('fb', 'G', 'TXT', 128);

$error_string = '';

// For show & edit php file add into array 'php'
$allow_extensions = array('tpl', 'txt', 'css', 'js');

$sx = new XTemplate(SED_ROOT . '/plugins/skineditor/skineditor.tpl');

if (!empty($sk)) {
	if (!empty($f)) {
		$n = 'edit';
	} else {
		$n = 'skin';
	}
}

switch ($n) {
	case 'skin':

		$skininfo = SED_ROOT . "/skins/" . $sk . "/" . $sk . ".php";
		$skindir = SED_ROOT . "/skins/" . $sk . "/";

		if (!file_exists($skininfo)) {
			sed_die('Wrong skin code.');
		}

		$info = sed_infoget($skininfo);
		$adminpath[] = array(sed_url("admin", "m=manage&p=skineditor&sk=" . $sk), $info['Name']);

		if ($a == 'makbak') {
			sed_check_xg();
			copy($skindir . $fb, $skindir . $fb . ".bak");
		} elseif ($a == 'resbak') {
			sed_check_xg();
			if (file_exists($skindir . $fb . ".bak")) {
				if (file_exists($skindir . $fb)) {
					unlink($skindir . $fb);
				}
				if (copy($skindir . $fb . ".bak", $skindir . $fb)) {
					unlink($skindir . $fb . ".bak");
				}
			}
		} elseif ($a == 'delbak') {
			sed_check_xg();
			unlink($skindir . $fb . ".bak");
		}

		$handle = opendir($skindir);

		while ($f = readdir($handle)) {
			$extension_arr = explode(".", $f);
			$extension = end($extension_arr);

			if (in_array(mb_strtolower($extension), $allow_extensions) && (mb_strrpos($f, ".") > 0)) {
				$skinlist[] = $f;
			} elseif (mb_strtolower($extension) == 'bak') {
				$baklist[] = $f;
			}
		}

		closedir($handle);
		sort($skinlist);

		if (isset($baklist) && is_array($baklist)) {
			@sort($baklist);
			foreach ($baklist as $i => $x) {
				$backupfile[$x] = TRUE;
			}
		}

		$j = 0;
		$total_size = 0;

		foreach ($skinlist as $i => $x) {
			$file_size = @filesize($skindir . $x);

			$sx->assign(array(
				"TPL_SKIN_LIST_ROW_EDIT" => "<a href=\"" . sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&f=" . $x) . "\">" . $out['ic_edit'] . "</a>",
				"TPL_SKIN_LIST_ROW_EDITURL" => sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&f=" . $x),
				"TPL_SKIN_LIST_ROW_TPLFILE" => $x,
				"TPL_SKIN_LIST_ROW_TPLSIZE" => $file_size
			));

			$xbak = $x . ".bak";

			$bcf = (isset($backupfile[$xbak]) && $backupfile[$xbak]) ? TRUE : FALSE;

			$sx->assign(array(
				"TPL_SKIN_LIST_ROW_BACKUP" => ($bcf) ? "" : "<a href=\"" . sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=makbak&" . sed_xg()) . "\">" . $out['ic_checked'] . "</a>",
				"TPL_SKIN_LIST_ROW_BACKUPURL" => ($bcf) ? "" : sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=makbak&" . sed_xg()),
				"TPL_SKIN_LIST_ROW_DELETE_BACKUP" => ($bcf) ? "<a href=\"" . sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=delbak&" . sed_xg()) . "\">" . $out['ic_unchecked'] . "</a>" : "",
				"TPL_SKIN_LIST_ROW_DELETE_BACKUPURL" => ($bcf) ? sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=delbak&" . sed_xg()) : "",
				"TPL_SKIN_LIST_ROW_RESTORE_BACKUP" => ($bcf) ? "<a href=\"" . sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=resbak&" . sed_xg()) . "\">" . $out['ic_reset'] . "</a>" : "",
				"TPL_SKIN_LIST_ROW_RESTORE_BACKUPURL" => ($bcf) ? sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&fb=" . $x . "&a=resbak&" . sed_xg()) : "",
				"TPL_SKIN_LIST_ROW_XBACKUP" => ($bcf) ? $xbak : ""
			));

			$j++;
			$total_size += $file_size;

			$sx->parse("SKINEDITOR.TPL_SKIN_LIST.TPL_SKIN_LIST_ROW");
		}

		$sx->assign(array(
			"TPL_SKIN_LIST_TOTALSIZE" => $total_size,
			"TPL_SKIN_LIST_FILES" => $j
		));

		$sx->parse("SKINEDITOR.TPL_SKIN_LIST");

		break;

	case 'edit':

		$skininfo = SED_ROOT . "/skins/" . $sk . "/" . $sk . ".php";
		$skindir = SED_ROOT . "/skins/" . $sk . "/";

		$b1 = sed_import('b1', 'P', 'ALP', 16);

		if (!file_exists($skininfo)) {
			sed_die('Wrong file name');
		}

		$info = sed_infoget($skininfo);
		$adminpath[] = array(sed_url("admin", "m=manage&p=skineditor&sk=" . $sk), $info['Name']);

		$editfile = SED_ROOT . "/skins/" . $sk . "/" . $f;

		$extension_arr = explode(".", $f);
		$extension = end($extension_arr);

		if (in_array(mb_strtolower($extension), $allow_extensions) && (mb_strrpos($f, ".") > 0)) {
			if ($a == 'update') {
				$content = sed_import('content', 'P', 'HTR');
				$file_isup = TRUE;

				if (!($fp = @fopen($editfile, 'w'))) {
					$file_isup = FALSE;
				}
				if (!(@fwrite($fp, $content))) {
					$file_isup = FALSE;
				}

				@fclose($fp);

				if ($file_isup) {
					if ($b1) {
						sed_redirect(sed_url("admin", "m=manage&p=skineditor&sk=" . $sk, "", true));
						exit;
					} else {
						sed_redirect(sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&f=" . $f, "", true));
						exit;
					}
				} else {
					$error_string .= "Error !<br />Could not write the file : " . $editfile;
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
				"TPL_SKIN_EDIT_SEND" => sed_url("admin", "m=manage&p=skineditor&sk=" . $sk . "&f=" . $f . "&a=update&" . sed_xg()),
				"TPL_SKIN_EDIT_FILE" => $editfile,
				"TPL_SKIN_EDIT_CHANCEL_URL" => sed_url("admin", "m=manage&p=skineditor&sk=" . $sk),
				"TPL_SKIN_EDIT_TEXTAREA" => "<textarea cols=\"96\" rows=\"16\" name=\"content\" id=\"content\" class=\"noeditor\">" . sed_cc($filecont, ENT_QUOTES) . "</textarea>",
				"TPL_SKIN_EDIT_HMODE" => $hmode
			));

			$sx->parse("SKINEDITOR.TPL_SKIN_EDIT");
		} else {
			$error_string .= "Not allowed file extension : " . $editfile;
		}

		break;

	default:

		$handle = opendir(SED_ROOT . "/skins/");

		while ($f = readdir($handle)) {
			if (mb_strpos($f, '.')  === FALSE) {
				$skinlist[] = $f;
			}
		}
		closedir($handle);
		sort($skinlist);

		foreach ($skinlist as $i => $x) {

			$skininfo = SED_ROOT . "/skins/" . $x . "/" . $x . ".php";
			$info = sed_infoget($skininfo);

			$sx->assign(array(
				"SKIN_LIST_ROW_EDIT" => "<a href=\"" . sed_url("admin", "m=manage&p=skineditor&sk=" . $x) . "\">" . $out['ic_edit'] . "</a>",
				"SKIN_LIST_ROW_EDITURL" => sed_url("admin", "m=manage&p=skineditor&sk=" . $x),
				"SKIN_LIST_ROW_NAME" => $info['Name'],
				"SKIN_LIST_ROW_CODE" => $x,
				"SKIN_LIST_ROW_VERSION" => $info['Version'],
				"SKIN_LIST_ROW_UPDATED" => $info['Updated'],
				"SKIN_LIST_ROW_AUTHOR" => $info['Author'],
				"SKIN_LIST_ROW_DEFAULT" => ($x == $cfg['defaultskin']) ? $out['ic_checked'] : '',
				"SKIN_LIST_ROW_SET" => ($x == $skin) ? $out['ic_checked'] : ''
			));

			$sx->parse("SKINEDITOR.SKIN_LIST.SKIN_LIST_ROW");
		}

		$sx->parse("SKINEDITOR.SKIN_LIST");

		break;
}

if (!empty($error_string)) {
	$sx->assign("SKINEDITOR_ERROR_BODY", sed_alert($error_string, 'e'));
	$sx->parse("SKINEDITOR.SKINEDITOR_ERROR");
}

$sx->parse("SKINEDITOR");
$plugin_body = $sx->text("SKINEDITOR");
