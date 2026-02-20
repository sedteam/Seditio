<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/inc/comments.functions.php
Version=185
Updated=2026-feb-16
Type=Plugin
Author=Seditio Team
Description=Comments API
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/** 
 * Builds comments 
 * 
 * @param string $code Item code 
 * @param string $url Base url 
 * @param int $display Display comments on page
 * @param bool $allow Enable or disable comments an item  
 * @return array 
 */
function sed_build_comments($code, $url, $display, $allow = TRUE)
{
	global $db_com, $db_users, $db_pages, $cfg, $out, $usr, $L, $sys, $skin, $flocation;

	$code = sed_sql_prep($code);
	$flocation = 'Comments';
	$pc = isset($cfg['plugin']['comments']) ? $cfg['plugin']['comments'] : array();
	$maxcommentlenght = (int)(isset($pc['maxcommentlenght']) && $pc['maxcommentlenght'] !== '' ? $pc['maxcommentlenght'] : 2000);
	$showcommentsonpage = isset($pc['showcommentsonpage']) ? $pc['showcommentsonpage'] : '0';
	$maxcommentsperpage = isset($pc['maxcommentsperpage']) ? $pc['maxcommentsperpage'] : '30';
	$maxtimeallowcomedit = isset($pc['maxtimeallowcomedit']) ? $pc['maxtimeallowcomedit'] : '15';
	$commentsorder = isset($pc['commentsorder']) ? $pc['commentsorder'] : 'ASC';
	$countcomments = isset($pc['countcomments']) ? $pc['countcomments'] : '1';

	$n = sed_import('n', 'G', 'ALP');
	$a = sed_import('a', 'G', 'ALP');
	$b = sed_import('b', 'G', 'INT');
	$quote = sed_import('quote', 'G', 'INT');
	$d = sed_import('d', 'G', 'INT');

	$error_string = '';
	$ssql_extra_columns = '';
	$ssql_extra_values = '';
	$ssql_extra = '';
	$newcommentextrafields = array();

	//fix for sed_url()
	if (is_array($url)) {
		$url_part = $url['part'];
		$url_params = $url['params'];
	} else {
		$url = str_replace('&amp;', '&', $url);
		$url_part = mb_substr($url, 0, mb_strpos($url, '.php'));
		$url_params = mb_substr($url, mb_strpos($url, '?') + 1, mb_strlen($url));
	}
	$lurl = ($showcommentsonpage) ? "" : "&comments=1";

	if (!empty($b)) {
		$before_after = ($commentsorder == "DESC") ? ">" : "<";
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_com WHERE com_code='$code' AND com_id " . $before_after . " '$b'");
		$com_before_after = sed_sql_result($sql, 0, "COUNT(*)");
		$d = $maxcommentsperpage * floor($com_before_after / $maxcommentsperpage);
	}

	$d = empty($d) ? 0 : (int)$d;

	list($usr['auth_read_com'], $usr['auth_write_com'], $usr['isadmin_com']) = sed_auth('plug', 'comments');
	sed_block($usr['auth_read_com']);

	if (!$usr['auth_read_com']) {
		return (array('', '', ''));
	}

	$extrafields = array();
	$extrafields = sed_extrafield_get('com');
	$number_of_extrafields = count($extrafields);

	if ($display) {
		if ($n == 'send' && $usr['auth_write_com'] && $allow) {
			sed_shield_protect();

			$rtext = sed_import('rtext', 'P', 'HTM');

			if ($number_of_extrafields > 0) $newcommentextrafields = sed_extrafield_buildvar($extrafields, 'r', 'com');

			$extp = sed_getextplugins('comments.send.first');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}

			$error_string .= (mb_strlen($rtext) < 2) ? $L['com_commenttooshort'] . "<br />" : '';
			$error_string .= (mb_strlen($rtext) > $maxcommentlenght) ? $L['com_commenttoolong'] . "<br />" : '';

			if (empty($error_string)) {

				if (count($extrafields) > 0) {
					foreach ($extrafields as $i => $row) {
						$ssql_extra_columns .= ', com_' . $row['code'];
						$ssql_extra_values .= ", '" . sed_sql_prep($newcommentextrafields['com_' . $row['code']]) . "'";
					}
				}

				$sql = sed_sql_query("INSERT INTO $db_com 
						(com_code, 
						com_author, 
						com_authorid, 
						com_authorip, 
						com_text, 
						com_date" . $ssql_extra_columns . ") 
					VALUES 
						('" . sed_sql_prep($code) . "', 
						'" . sed_sql_prep($usr['name']) . "', 
						" . (int)$usr['id'] . ", 
						'" . $usr['ip'] . "', 
						'" . sed_sql_prep($rtext) . "', 
						" . (int)$sys['now_offset'] . $ssql_extra_values . ")");

				if (mb_substr($code, 0, 1) == 'p') {
					$page_id = mb_substr($code, 1, 10);
					$sql = sed_sql_query("UPDATE $db_pages SET page_comcount='" . sed_get_comcount($code) . "' WHERE page_id='" . $page_id . "'");
				}

				$extp = sed_getextplugins('comments.send.new');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}

				sed_shield_update(20, "New comment");
				sed_redirect(sed_url($url_part, $url_params . $lurl, "", true));
				exit;
			}
		}

		if ($n == 'delete') {
			sed_check_xg();

			$sql = sed_sql_query("SELECT * FROM $db_com WHERE com_id='$b' LIMIT 1");
			$row = sed_sql_fetchassoc($sql);

			$time_limit = ($sys['now_offset'] < ($row['com_date'] + $maxtimeallowcomedit * 60)) ? TRUE : FALSE;
			$usr['isowner_com'] = ($row['com_authorid'] == $usr['id'] && $time_limit);
			$usr['allow_edit_com'] = ($usr['isadmin'] || $usr['isowner_com']);

			if (!$usr['allow_edit_com']) {
				$error_string .= $L['com_commentdeleteallowtime'] . "<br />";
			}

			if (empty($error_string)) {
				sed_block($usr['allow_edit_com']);
				if ((sed_sql_numrows($sql) > 0) && ($usr['isowner_com'] || $usr['isadmin_com'])) {
					if ($cfg['trash_comment']) {
						sed_trash_put('comment', $L['Comment'] . " #" . $b . " (" . $row['com_author'] . ")", $b, $row);
					}

					$sql = sed_sql_query("DELETE FROM $db_com WHERE com_id='$b'");

					if (mb_substr($row['com_code'], 0, 1) == 'p') {
						$page_id = mb_substr($row['com_code'], 1, 10);
						$sql = sed_sql_query("UPDATE $db_pages SET page_comcount=" . sed_get_comcount($row['com_code']) . " WHERE page_id=" . $page_id);
					}
					$com_grp = ($usr['isadmin']) ? "adm" : "usr";
					sed_log("Deleted comment #" . $b . " in '" . $code . "'", $com_grp);
				}
				sed_redirect(sed_url($url_part, $url_params . $lurl, "", true));
				exit;
			}
		}

		if ($a == "edit") {
			$sql1 = sed_sql_query("SELECT * FROM $db_com WHERE com_id='$b' LIMIT 1");
			sed_die(sed_sql_numrows($sql1) == 0);

			$row = sed_sql_fetchassoc($sql1);

			$time_limit = ($sys['now_offset'] < ($row['com_date'] + $maxtimeallowcomedit * 60)) ? TRUE : FALSE;
			$usr['isowner_com'] = ($row['com_authorid'] == $usr['id'] && $time_limit);
			$usr['allow_edit_com'] = ($usr['isadmin'] || $usr['isowner_com']);

			if (!$usr['allow_edit_com']) {
				$error_string .= $L['com_commenteditallowtime'] . "<br />";
			}

			if ($n == 'update') {
				sed_check_xg();
				sed_shield_protect();

				$rtext = sed_import('rtext', 'P', 'HTM');

				if ($number_of_extrafields > 0) $rcommentextrafields = sed_extrafield_buildvar($extrafields, 'r', 'com');

				$extp = sed_getextplugins('comments.edit.update.first');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}

				$error_string .= (mb_strlen($rtext) < 2) ? $L['com_commenttooshort'] . "<br />" : '';
				$error_string .= (mb_strlen($rtext) > $maxcommentlenght) ? $L['com_commenttoolong'] . "<br />" : '';

				if (empty($error_string)) {
					sed_block($usr['allow_edit_com']);

					$ssql_extra = '';
					if (count($extrafields) > 0) {
						foreach ($extrafields as $i => $row) {
							$ssql_extra .= ", com_" . $row['code'] . " = " . "'" . sed_sql_prep($rcommentextrafields['com_' . $row['code']]) . "'";
						}
					}

					$sql3 = sed_sql_query("UPDATE $db_com SET com_text = '" . sed_sql_prep($rtext) . "'" . $ssql_extra . " WHERE com_id='$b'");

					$extp = sed_getextplugins('comments.edit.update.done');
					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}

					unset($rtext);

					$com_grp = ($usr['isadmin']) ? "adm" : "usr";
					sed_log("Edited comment #" . $b . " in '" . $code . "'", $com_grp);
					sed_redirect(sed_url($url_part, $url_params . $lurl . "&b=" . $b, "#c" . $b, true));
					exit;
				}
			}

			$t = new XTemplate(sed_skinfile('comments', true));

			$extp = sed_getextplugins('comments.main');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}

			if (!empty($error_string)) {
				$t->assign("COMMENTS_ERROR_BODY", $error_string);
				$t->parse("COMMENTS.COMMENTS_ERROR");
			}

			if ($usr['allow_edit_com']) {
				$pfs = '';
				$post_main = '';
				if ($usr['auth_write_com']) {
					$pfs = ($usr['id'] > 0) ? sed_build_pfs($usr['id'], "editcomment", "rtext", $L['Mypfs']) : '';
					$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, "editcomment", "rtext", $L['SFS']) : '';
					$post_main = sed_textarea('rtext', $row['com_text'], 6, $cfg['textarea_default_width'], 'Micro') . " " . $pfs;
				}

				$t->assign(array(
					"COMMENTS_EDIT_CODE" => $code,
					"COMMENTS_EDIT_FORM_ID" => $row['com_id'],
					"COMMENTS_EDIT_FORM_SEND" => sed_url($url_part, $url_params . $lurl . "&a=edit&n=update&b=" . $b . "&" . sed_xg()),
					"COMMENTS_EDIT_FORM_URL" => sed_url($url_part, $url_params . $lurl, "#" . $row['com_id']),
					"COMMENTS_EDIT_FORM_AUTHOR" => $usr['name'],
					"COMMENTS_EDIT_FORM_AUTHORID" => $usr['id'],
					"COMMENTS_EDIT_FORM_TEXT" => $post_main,
					"COMMENTS_EDIT_FORM_MYPFS" => $pfs
				));

				if (count($extrafields) > 0) {
					$extra_array = sed_build_extrafields('com', 'COMMENTS_EDIT_FORM', $extrafields, $row, 'r');
					$t->assign($extra_array);
				}

				if ($usr['auth_write_com']) {
					$extp = sed_getextplugins('comments.editcomment.tags');
					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}
					$t->parse("COMMENTS.COMMENTS_EDITCOMMENT");
				}
			}
		} else {
			$error_string .= ($n == 'added') ? $L['com_commentadded'] . "<br />" : '';

			$t = new XTemplate(sed_skinfile('comments', true));

			$extp = sed_getextplugins('comments.main');
			if (is_array($extp)) {
				foreach ($extp as $k => $pl) {
					include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
				}
			}

			if (!empty($error_string)) {
				$t->assign("COMMENTS_ERROR_BODY", sed_alert($error_string, 'e'));
				$t->parse("COMMENTS.COMMENTS_ERROR");
			}

			$pfs = '';
			$post_main = '';

			if ($usr['auth_write_com'] && $allow) {
				$rtext = sed_import('rtext', 'P', 'HTM');
				if ($quote > 0) {
					$sqlq = sed_sql_query("SELECT com_id, com_author, com_text FROM $db_com WHERE com_id = '$quote' LIMIT 1");
					if ($rowq = sed_sql_fetchassoc($sqlq)) {
						$rtext = "<blockquote>" . sed_link(sed_url($url_part, $url_params . $lurl, "#" . $rowq['com_id']), "#" . $rowq['com_id']) . " <strong>" . $rowq['com_author'] . " :</strong><br />" . $rowq['com_text'] . "</blockquote><br />";
					}
				}
				$pfs = ($usr['id'] > 0) ? sed_build_pfs($usr['id'], "newcomment", "rtext", $L['Mypfs']) : '';
				$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, "newcomment", "rtext", $L['SFS']) : '';
				$post_main = sed_textarea('rtext', $rtext, 6, $cfg['textarea_default_width'], 'Micro') . " " . $pfs;
			}

			$t->assign(array(
				"COMMENTS_CODE" => $code,
				"COMMENTS_FORM_SEND" => sed_url($url_part, $url_params . $lurl . "&n=send"),
				"COMMENTS_FORM_AUTHOR" => $usr['name'],
				"COMMENTS_FORM_AUTHORID" => $usr['id'],
				"COMMENTS_FORM_TEXT" => $post_main,
				"COMMENTS_FORM_MYPFS" => $pfs
			));

			if (count($extrafields) > 0) {
				$extra_array = sed_build_extrafields('com', 'COMMENTS_FORM', $extrafields, $newcommentextrafields, 'r');
				$t->assign($extra_array);
			}

			if ($usr['auth_write_com'] && $allow) {
				$extp = sed_getextplugins('comments.newcomment.tags');
				if (is_array($extp)) {
					foreach ($extp as $k => $pl) {
						include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}
				$t->parse("COMMENTS.COMMENTS_NEWCOMMENT");
			}

			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_com AS c
					LEFT JOIN $db_users AS u ON u.user_id=c.com_authorid
					WHERE com_code='$code'");

			$totallines = sed_sql_result($sql, 0, "COUNT(*)");
			$totalpages = ceil($totallines / $maxcommentsperpage);

			$pagination = sed_pagination(sed_url($url_part, $url_params . $lurl), $d, $totallines, $maxcommentsperpage);
			list($pageprev, $pagenext) = sed_pagination_pn(sed_url($url_part, $url_params . $lurl), $d, $totallines, $maxcommentsperpage, TRUE);

			$t->assign(array(
				"COMMENTS_PAGINATION" => $pagination,
				"COMMENTS_PAGEPREV" => $pageprev,
				"COMMENTS_PAGENEXT" => $pagenext
			));

			$sql = sed_sql_query("SELECT c.*, u.user_id, u.user_avatar, u.user_maingrp FROM $db_com AS c
					LEFT JOIN $db_users AS u ON u.user_id=c.com_authorid
					WHERE com_code='$code' ORDER BY com_id " . $commentsorder . " LIMIT $d, " . $maxcommentsperpage);

			if (sed_sql_numrows($sql) > 0) {
				$i = 0;

				$extp = sed_getextplugins('comments.loop');

				while ($row = sed_sql_fetchassoc($sql)) {
					$row['com_text'] = sed_parse($row['com_text']);
					$i++;
					$com_author = sed_cc($row['com_author']);
					$com_text = "<div id=\"blkcom_" . $row['com_id'] . "\" >" . $row['com_text'] . "</div>";

					$time_limit = ($sys['now_offset'] < ($row['com_date'] + $maxtimeallowcomedit * 60)) ? TRUE : FALSE;
					$usr['isowner_com'] = ($row['com_authorid'] == $usr['id'] && $time_limit);
					$com_gup = $sys['now_offset'] - ($row['com_date'] + $maxtimeallowcomedit * 60);
					$allowed_time = ($usr['isowner_com'] && !$usr['isadmin']) ? " - " . sed_build_timegap($sys['now_offset'] + $com_gup, $sys['now_offset']) . $L['com_gup'] : '';

					$com_quote = ($usr['id'] > 0) ? sed_link(sed_url($url_part, $url_params . $lurl . "&quote=" . $row['com_id'] . "&" . sed_xg(), "#nc"), $L['Quote'], array('class' => 'btn btn-adm')) . "&nbsp;" : "";

					$com_admin = ($usr['isadmin_com'] || $usr['isowner_com']) ?
						sed_link(sed_url($url_part, $url_params . $lurl . "&a=edit&b=" . $row['com_id'] . "&" . sed_xg(), "#c" . $row['com_id']), $L['Edit'], array('title' => $L['Edit'] . $allowed_time, 'class' => 'btn btn-adm')) . "&nbsp;" .
						sed_link(sed_url($url_part, $url_params . $lurl . "&n=delete&b=" . $row['com_id'] . "&" . sed_xg()), $L['Delete'], array('class' => 'btn btn-adm')) . "&nbsp;" .
						$L['Ip'] . ":" . sed_build_ipsearch($row['com_authorip']) : '';

					$com_authorlink = ($row['com_authorid'] > 0 && $row['user_id'] > 0) ? sed_build_user($row['com_authorid'], $com_author, $row['user_maingrp']) : $com_author;

					$t->assign(array(
						"COMMENTS_ROW_ID" => $row['com_id'],
						"COMMENTS_ROW_ORDER" => $i + $d,
						"COMMENTS_ROW_URL" => sed_url($url_part, $url_params . $lurl . "&b=" . $row['com_id'], "#c" . $row['com_id']),
						"COMMENTS_ROW_AUTHOR" => $com_authorlink,
						"COMMENTS_ROW_AUTHORID" => $row['com_authorid'],
						"COMMENTS_ROW_AVATAR" => sed_build_userimage($row['user_avatar']),
						"COMMENTS_ROW_TEXT" => $com_text,
						"COMMENTS_ROW_DATE" => sed_build_date($cfg['dateformat'], $row['com_date']),
						"COMMENTS_ROW_ODDEVEN" => sed_build_oddeven($i),
						"COMMENTS_ROW_ADMIN" => $com_quote . $com_admin
					));

					if (count($extrafields) > 0) {
						$extra_array = sed_build_extrafields_data('com', 'COMMENTS_ROW', $extrafields, $row);
						$t->assign($extra_array);
					}

					if (is_array($extp)) {
						foreach ($extp as $k => $pl) {
							include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
						}
					}

					$t->parse("COMMENTS.COMMENTS_ROW");
				}
			} elseif ($allow) {
				$t->assign(array(
					"COMMENTS_EMPTYTEXT" => $L['com_nocommentsyet']
				));
				$t->parse("COMMENTS.COMMENTS_EMPTY");
			}

			if (!$allow) {
				$t->assign(array(
					"COMMENTS_DISABLETEXT" => $L['com_disable']
				));
				$t->parse("COMMENTS.COMMENTS_DISABLE");
			}
		}

		$extp = sed_getextplugins('comments.tags');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}

		$t->parse("COMMENTS");
		$res_display = $t->text("COMMENTS");
	} else {
		$res_display = '';
	}

	$nbcomment = "";
	$nbcomment_link = $out['ic_comment'];
	if ($countcomments) {
		$nbcomment = sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_com where com_code='$code'"), 0, "COUNT(*)");
		$nbcomment_link .= " (" . $nbcomment . ")";
	}
	$res = sed_link(sed_url($url_part, $url_params . $lurl), $nbcomment_link);

	return (array($res, $res_display, $nbcomment));
}

/**
 * Returns number of comments for item
 *
 * @param string $code Item code
 * @return int
 */
function sed_get_comcount($code)
{
	global $db_com;

	$code = sed_sql_prep($code);
	$sql = sed_sql_query("SELECT DISTINCT com_code, COUNT(*) FROM $db_com WHERE com_code='$code' GROUP BY com_code");

	if ($row = sed_sql_fetchassoc($sql)) {
		return ($row['COUNT(*)']);
	} else {
		return ("0");
	}
}

/**
 * Build URL to the comment context (page/gallery/polls/users) with optional anchor.
 * Returns empty string if the target module is disabled.
 *
 * @param string $com_code Item code (e.g. p123, g45, v1, u2)
 * @param string $anchor   Optional fragment, e.g. "#c" . $com_id
 * @return string URL or empty string if module disabled / unknown type
 */
function sed_comments_item_url($com_code, $anchor = '')
{
	global $db_pages, $sys;

	$type = mb_substr($com_code, 0, 1);
	$value = mb_substr($com_code, 1);

	switch ($type) {
		case 'p':
			if (sed_module_active('page')) {
				$sql = sed_sql_query("SELECT page_id, page_cat, page_alias FROM $db_pages WHERE page_id=" . (int)$value . " LIMIT 1");
				if (sed_sql_numrows($sql) > 0) {
					$row = sed_sql_fetchassoc($sql);
					$sys['catcode'] = $row['page_cat'];
					$params = (empty($row['page_alias'])) ? "id=" . $row['page_id'] . "&comments=1" : "al=" . $row['page_alias'] . "&comments=1";
					return sed_url("page", $params, $anchor);
				}
				return sed_url("page", "id=" . $value . "&comments=1", $anchor);
			}
			break;
		case 'g':
			if (sed_module_active('gallery')) {
				return sed_url("gallery", "id=" . $value . "&comments=1", $anchor);
			}
			break;
		case 'u':
			if (sed_module_active('users')) {
				return sed_url("users", "m=details&id=" . $value . "&comments=1", $anchor);
			}
			break;
		case 'v':
			if (sed_module_active('polls')) {
				return sed_url("polls", "id=" . $value . "&comments=1", $anchor);
			}
			break;
		default:
			return '';
	}
	return '';
}
