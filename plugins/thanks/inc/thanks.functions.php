<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/inc/thanks.functions.php
Version=185
Type=Plugin
Description=Thanks API
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

define('THANKS_ERR_NONE', 0);
define('THANKS_ERR_MAXDAY', 1);
define('THANKS_ERR_MAXUSER', 2);
define('THANKS_ERR_ITEM', 3);
define('THANKS_ERR_SELF', 4);

/**
 * Checks if it is correct to add a new thank
 *
 * @param int $touser Thank receiver ID
 * @param int $fromuser Thank sender ID
 * @param string $ext Extension code (page, forums, comments)
 * @param int $item Item ID
 * @return int THANKS_ERR_* constant
 */
function thanks_check($touser, $fromuser, $ext, $item)
{
	global $db_thanks, $cfg, $usr;

	$ext = sed_sql_prep($ext);

	list(,, $isadmin_th) = sed_auth('plug', 'thanks');
	if ($touser == $fromuser && empty($isadmin_th)) {
		return THANKS_ERR_SELF;
	}

	$maxday = (int)(isset($cfg['plugin']['thanks']['maxday']) ? $cfg['plugin']['thanks']['maxday'] : 20);
	$maxuser = (int)(isset($cfg['plugin']['thanks']['maxuser']) ? $cfg['plugin']['thanks']['maxuser'] : 5);

	$today_start = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

	if (sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_thanks WHERE th_fromuser=" . (int)$fromuser . " AND th_date >= " . $today_start), 0, 'COUNT(*)') >= $maxday) {
		return THANKS_ERR_MAXDAY;
	}

	if (sed_sql_result(sed_sql_query("SELECT COUNT(*) FROM $db_thanks WHERE th_fromuser=" . (int)$fromuser . " AND th_touser=" . (int)$touser . " AND th_date >= " . $today_start), 0, 'COUNT(*)') >= $maxuser) {
		return THANKS_ERR_MAXUSER;
	}

	if (thanks_check_item($fromuser, $ext, $item)) {
		return THANKS_ERR_ITEM;
	}

	return THANKS_ERR_NONE;
}

/**
 * Returns TRUE if user has already thanked for given item
 *
 * @param int $fromuser Thank sender ID
 * @param string $ext Extension code
 * @param int $item Item ID
 * @return bool
 */
function thanks_check_item($fromuser, $ext, $item)
{
	global $db_thanks;

	$ext = sed_sql_prep($ext);
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_thanks WHERE th_fromuser=" . (int)$fromuser . " AND th_ext='" . $ext . "' AND th_item=" . (int)$item);
	return sed_sql_result($sql, 0, 'COUNT(*)') >= 1;
}

/**
 * Adds a new thank
 *
 * @param int $touser Thank receiver ID
 * @param int $fromuser Thank sender ID
 * @param string $ext Extension code
 * @param int $item Item ID
 * @return bool
 */
function thanks_add($touser, $fromuser, $ext, $item)
{
	global $db_thanks, $db_users, $sys, $cfg;

	$ext = sed_sql_prep($ext);
	$ins = sed_sql_query("INSERT INTO $db_thanks (th_ext, th_item, th_fromuser, th_touser, th_date) VALUES ('" . $ext . "', " . (int)$item . ", " . (int)$fromuser . ", " . (int)$touser . ", " . (int)$sys['now_offset'] . ")");
	if ($ins && $touser > 0) {
		sed_sql_query("UPDATE $db_users SET user_thankscount = user_thankscount + 1 WHERE user_id = " . (int)$touser);
		if (!empty($cfg['plugin']['thanks']['notify_by_pm'])) {
			thanks_notify_by_pm($touser, $fromuser, $ext, $item);
		}
		if (!empty($cfg['plugin']['thanks']['notify_by_email'])) {
			thanks_notify_by_email($touser, $fromuser, $ext, $item);
		}
	}
	return (bool)$ins;
}

/**
 * Sends PM notification to thank receiver
 *
 * @param int $touser Thank receiver ID
 * @param int $fromuser Thank sender ID
 * @param string $ext Extension code (page, forums, comments)
 * @param int $item Item ID
 */
function thanks_notify_by_pm($touser, $fromuser, $ext, $item)
{
	global $db_pm, $db_users, $cfg, $sys, $L;

	if (!sed_module_active('pm')) {
		return;
	}

	$ext = sed_sql_prep($ext);
	$link = $cfg['mainurl'] . '/' . sed_url('plug', 'e=thanks&a=viewdetails&ext=' . $ext . '&item=' . (int)$item, '', false, false);

	$sql = sed_sql_query("SELECT user_name FROM $db_users WHERE user_id=" . (int)$fromuser . " LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	$fromname = $row ? sed_cc($row['user_name']) : 'User#' . $fromuser;

	$subject = isset($L['thanks_notify_pm_subject']) ? $L['thanks_notify_pm_subject'] : 'You received a thank';
	$body = isset($L['thanks_notify_pm_body']) ? sprintf($L['thanks_notify_pm_body'], $fromname, $link) : "User " . $fromname . " thanked you.\nView details: " . $link;

	sed_sql_query("INSERT INTO $db_pm (pm_state, pm_date, pm_fromuserid, pm_fromuser, pm_touserid, pm_title, pm_text) VALUES (0, " . (int)$sys['now_offset'] . ", " . (int)$fromuser . ", '" . sed_sql_prep($fromname) . "', " . (int)$touser . ", '" . sed_sql_prep($subject) . "', '" . sed_sql_prep($body) . "')");
	sed_sql_query("UPDATE $db_users SET user_newpm=1 WHERE user_id=" . (int)$touser);
}

/**
 * Sends email notification to thank receiver
 *
 * @param int $touser Thank receiver ID
 * @param int $fromuser Thank sender ID
 * @param string $ext Extension code (page, forums, comments)
 * @param int $item Item ID
 */
function thanks_notify_by_email($touser, $fromuser, $ext, $item)
{
	global $db_users, $cfg, $L;

	$ext = sed_sql_prep($ext);
	$link = $cfg['mainurl'] . '/' . sed_url('plug', 'e=thanks&a=viewdetails&ext=' . $ext . '&item=' . (int)$item, '', false, false);

	$sql = sed_sql_query("SELECT user_email, user_name FROM $db_users WHERE user_id=" . (int)$touser . " LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	if (!$row || empty($row['user_email'])) {
		return;
	}

	$toname = sed_cc($row['user_name']);
	$email = $row['user_email'];

	$sql2 = sed_sql_query("SELECT user_name FROM $db_users WHERE user_id=" . (int)$fromuser . " LIMIT 1");
	$row2 = sed_sql_fetchassoc($sql2);
	$fromname = $row2 ? sed_cc($row2['user_name']) : 'User#' . $fromuser;

	$subject = isset($L['thanks_notify_email_subject']) ? $L['thanks_notify_email_subject'] : 'You received a thank';
	$body = isset($L['thanks_notify_email_body']) ? sprintf($L['thanks_notify_email_body'], $toname, $fromname, $link) : "Hello " . $toname . ",\n\nUser " . $fromname . " thanked you.\nView details: " . $link;

	$headers = '';
	if (!empty($cfg['plugin']['thanks']['notify_from'])) {
		$from_addr = trim($cfg['plugin']['thanks']['notify_from']);
		$hdrs = array();
		$hdrs[] = "MIME-Version: 1.0";
		$hdrs[] = "Content-type: text/plain; charset=" . $cfg['charset'];
		$hdrs[] = "Content-Transfer-Encoding: 8bit";
		$hdrs[] = "From: <" . $from_addr . ">";
		$hdrs[] = "X-Mailer: PHP/" . phpversion();
		$headers = implode("\r\n", $hdrs);
	}

	sed_mail($email, $subject, $body, $headers);
}

/**
 * Removes a thank by ID
 *
 * @param int $id Thank ID
 * @return bool
 */
function thanks_remove($id)
{
	global $db_thanks, $db_users;

	$id = (int)$id;
	$sql = sed_sql_query("SELECT th_touser FROM $db_thanks WHERE th_id = $id LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	$rm = sed_sql_query("DELETE FROM $db_thanks WHERE th_id = $id");
	if ($rm && $row && $row['th_touser'] > 0) {
		sed_sql_query("UPDATE $db_users SET user_thankscount = GREATEST(0, user_thankscount - 1) WHERE user_id = " . (int)$row['th_touser']);
	}
	return (bool)$rm;
}

/**
 * Returns count of thanks for an item
 *
 * @param string $ext Extension code
 * @param int $item Item ID
 * @return int
 */
function thanks_count($ext, $item)
{
	global $db_thanks;

	$ext = sed_sql_prep($ext);
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_thanks WHERE th_ext='" . $ext . "' AND th_item=" . (int)$item);
	return (int)sed_sql_result($sql, 0, 'COUNT(*)');
}

/**
 * Returns count of thanks received by user
 *
 * @param int $user_id User ID
 * @return int
 */
function thanks_user_thanks_count($user_id)
{
	global $db_thanks;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_thanks WHERE th_touser=" . (int)$user_id);
	return (int)sed_sql_result($sql, 0, 'COUNT(*)');
}

/**
 * Resolves category, type and item info for a thanks list row
 *
 * @param string $ext Extension code (page, forums, comments)
 * @param int $item_id Item ID (page_id, fp_id, com_id)
 * @param array $L Lang strings (thanks_type_page, thanks_type_post, thanks_type_comment)
 * @return array ['type' => string, 'category' => string, 'item' => string HTML link]
 */
function thanks_resolve_item_info($ext, $item_id, $L)
{
	global $db_pages, $db_forum_posts, $db_forum_topics, $db_forum_sections, $db_com, $db_structure, $sed_cat;

	$ext = sed_sql_prep($ext);
	$item_id = (int)$item_id;
	$type_label = '';
	$category = '';
	$item_html = '';

	switch ($ext) {
		case 'page':
			$type_label = isset($L['thanks_type_page']) ? $L['thanks_type_page'] : 'Page';
			if (!sed_module_active('page')) {
				return array('type' => $type_label, 'category' => '-', 'item' => '#' . $item_id);
			}
			$sql = sed_sql_query("SELECT p.page_id, p.page_title, p.page_cat, p.page_alias FROM $db_pages p WHERE p.page_id=" . $item_id . " LIMIT 1");
			if ($row = sed_sql_fetchassoc($sql)) {
				$cat_title = (isset($sed_cat[$row['page_cat']]['title'])) ? $sed_cat[$row['page_cat']]['title'] : $row['page_cat'];
				if (!isset($sed_cat[$row['page_cat']]['title'])) {
					$sql_cat = sed_sql_query("SELECT structure_title FROM $db_structure WHERE structure_code='" . sed_sql_prep($row['page_cat']) . "' LIMIT 1");
					$cat = sed_sql_fetchassoc($sql_cat);
					$cat_title = $cat ? $cat['structure_title'] : $row['page_cat'];
				}
				$category = sed_link(sed_url('page', 'c=' . $row['page_cat']), sed_cc($cat_title));
				$params = !empty($row['page_alias']) ? 'al=' . $row['page_alias'] : 'id=' . $row['page_id'];
				$item_html = sed_link(sed_url('page', $params), sed_cc($row['page_title']));
			}
			break;

		case 'forums':
			$type_label = isset($L['thanks_type_post']) ? $L['thanks_type_post'] : 'Post';
			if (!sed_module_active('forums')) {
				return array('type' => $type_label, 'category' => '-', 'item' => '#' . $item_id);
			}
			$sql = sed_sql_query("SELECT fp.fp_id, fp.fp_topicid, fp.fp_sectionid FROM $db_forum_posts fp WHERE fp.fp_id=" . $item_id . " LIMIT 1");
			if ($row = sed_sql_fetchassoc($sql)) {
				$sql_ft = sed_sql_query("SELECT ft_title FROM $db_forum_topics WHERE ft_id=" . (int)$row['fp_topicid'] . " LIMIT 1");
				$ft = sed_sql_fetchassoc($sql_ft);
				$sql_fs = sed_sql_query("SELECT fs_id, fs_title FROM $db_forum_sections WHERE fs_id=" . (int)$row['fp_sectionid'] . " LIMIT 1");
				$fs = sed_sql_fetchassoc($sql_fs);
				$cat_title = $fs ? $fs['fs_title'] : '';
				$category = ($fs) ? sed_link(sed_url('forums', 'm=topics&s=' . $fs['fs_id'] . '&al=' . $cat_title), sed_cc($cat_title)) : $cat_title;
				$ft_title = $ft ? sed_cc($ft['ft_title']) : '';
				$url = sed_url('forums', 'm=posts&q=' . $row['fp_topicid'] . '&al=' . ($ft ? $ft['ft_title'] : '') . '&p=' . $row['fp_id'], '#fp' . $row['fp_id']);
				$item_html = sed_link($url, $ft_title);
			}
			break;

		case 'comments':
			$type_label = isset($L['thanks_type_comment']) ? $L['thanks_type_comment'] : 'Comment';
			if (!sed_plug_active('comments')) {
				return array('type' => $type_label, 'category' => '-', 'item' => '#' . $item_id);
			}
			$sql = sed_sql_query("SELECT com_id, com_code, com_text FROM $db_com WHERE com_id=" . $item_id . " LIMIT 1");
			if ($row = sed_sql_fetchassoc($sql)) {
				$com_type = mb_substr($row['com_code'], 0, 1);
				$value = mb_substr($row['com_code'], 1);
				$label = sed_cutstring(strip_tags($row['com_text']), 60);
				if (!$label) {
					$label = '#' . $item_id;
				}
				if ($com_type == 'p' && sed_module_active('page')) {
					$sql_pg = sed_sql_query("SELECT page_title, page_cat FROM $db_pages WHERE page_id=" . (int)$value . " LIMIT 1");
					$pg = sed_sql_fetchassoc($sql_pg);
					if ($pg) {
						$cat_title = (isset($sed_cat[$pg['page_cat']]['title'])) ? $sed_cat[$pg['page_cat']]['title'] : $pg['page_cat'];
						if (!isset($sed_cat[$pg['page_cat']]['title'])) {
							$sql_cat = sed_sql_query("SELECT structure_title FROM $db_structure WHERE structure_code='" . sed_sql_prep($pg['page_cat']) . "' LIMIT 1");
							$cat = sed_sql_fetchassoc($sql_cat);
							$cat_title = $cat ? $cat['structure_title'] : $pg['page_cat'];
						}
						$category = sed_link(sed_url('page', 'c=' . $pg['page_cat']), sed_cc($cat_title));
						$label = sed_cc($pg['page_title']) . ' — ' . $label;
					}
				}
				if (function_exists('sed_comments_item_url')) {
					$url = sed_comments_item_url($row['com_code'], '#c' . $row['com_id']);
					$item_html = $url ? sed_link($url, $label) : $label;
				} else {
					$item_html = $label;
				}
			}
			break;
	}

	return array('type' => $type_label, 'category' => $category, 'item' => $item_html);
}

/**
 * Checks if item exists
 *
 * @param string $ext Extension code
 * @param int $item Item ID
 * @return bool
 */
function thanks_item_exists($ext, $item)
{
	global $db_pages, $db_forum_posts, $db_com;

	switch ($ext) {
		case 'page':
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_id=" . (int)$item);
			return sed_sql_result($sql, 0, 'COUNT(*)') > 0;
		case 'forums':
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_id=" . (int)$item);
			return sed_sql_result($sql, 0, 'COUNT(*)') > 0;
		case 'comments':
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_com WHERE com_id=" . (int)$item);
			return sed_sql_result($sql, 0, 'COUNT(*)') > 0;
	}
	return false;
}

/**
 * Builds and renders thanks block
 *
 * @param string $ext Extension code (page, forums, comments)
 * @param int $item Item ID
 * @param int $to_userid Receiver user ID
 * @param bool $allow Whether thanks are allowed
 * @param bool $inner_only For AJAX: return only span content (no wrapper div)
 * @return string HTML (empty if hidden)
 */
function sed_build_thanks($ext, $item, $to_userid, $allow = true, $inner_only = false)
{
	global $db_thanks, $db_users, $cfg, $usr, $L;

	$ext = sed_sql_prep($ext);
	$item = (int)$item;
	$to_userid = (int)$to_userid;

	list($usr['auth_read_th'], $usr['auth_write_th'], $usr['isadmin_th']) = sed_auth('plug', 'thanks');

	if (!$usr['auth_read_th']) {
		return '';
	}

	$mask_user = '%1$s (%2$s)';
	$mask_user_short = '%1$s';
	$mask_sep = ', ';

	$tags = array(
		'THANKS_CAN' => false,
		'THANKS_BUTTON' => '',
		'THANKS_COUNT' => 0,
		'THANKS_LIST_URL' => sed_url('plug', 'e=thanks&a=viewdetails&ext=' . $ext . '&item=' . $item),
		'THANKS_USERS' => '',
		'THANKS_USERS_DATES' => '',
		'THANKS_ID' => 'thanks-' . $ext . '-' . $item,
		'THANKS_LABEL_THANKED' => $L['thanks_thanked'],
		'THANKS_LABEL_TOTAL' => $L['thanks_total']
	);

	$tags['THANKS_COUNT'] = thanks_count($ext, $item);

	$maxthanked = (int)(isset($cfg['plugin']['thanks']['maxthanked']) ? $cfg['plugin']['thanks']['maxthanked'] : 10);
	$short = !empty($cfg['plugin']['thanks']['short']);
	$sql_limit = $maxthanked > 0 ? " LIMIT " . $maxthanked : "";
	$thanks_dateformat = !empty($cfg['plugin']['thanks']['format']) ? $cfg['plugin']['thanks']['format'] : 'd.m.Y';

	$th_users_parts = array();
	$th_users_dates_parts = array();
	$th_thanked = false;

	$sql = sed_sql_query("SELECT t.*, u.user_name, u.user_maingrp FROM $db_thanks AS t LEFT JOIN $db_users AS u ON t.th_fromuser = u.user_id WHERE t.th_ext='" . $ext . "' AND t.th_item=" . $item . " ORDER BY t.th_date DESC" . $sql_limit);
	while ($row = sed_sql_fetchassoc($sql)) {
		$userlink = sed_build_user($row['th_fromuser'], sed_cc($row['user_name']), $row['user_maingrp']);
		$date = sed_build_date($thanks_dateformat, $row['th_date']);
		$th_users_parts[] = sprintf($mask_user_short, $userlink);
		$th_users_dates_parts[] = sprintf($short ? $mask_user_short : $mask_user, $userlink, $date);
		
		if ($usr['id'] == $row['th_fromuser']) {
			$th_thanked = true;
		}
	}

	$tags['THANKS_USERS'] = implode($mask_sep, $th_users_parts);
	$tags['THANKS_USERS_DATES'] = implode($mask_sep, $th_users_dates_parts);

	$tags['THANKS_ETC'] = ($tags['THANKS_COUNT'] > $maxthanked && $maxthanked > 0)
		? ' ... '
		: '. ';
	$show_more = ($tags['THANKS_COUNT'] > $maxthanked && $maxthanked > 0);
	$tags['THANKS_TOTAL_LINK'] = $show_more
		? sed_link($tags['THANKS_LIST_URL'], (string)$tags['THANKS_COUNT'])
		: (string)$tags['THANKS_COUNT'];

	$thank_url = sed_url('plug', 'e=thanks&a=thank&ext=' . $ext . '&item=' . $item . '&' . sed_xg());
	$thank_ajax_url = sed_url('plug', 'ajx=thanks&a=thank&ext=' . $ext . '&item=' . $item . '&' . sed_xg());
	$can_thank = $usr['auth_write_th'] && !$th_thanked && ($usr['isadmin_th'] || $usr['id'] != $to_userid) && $to_userid > 0 && $allow && !thanks_check_item($usr['id'], $ext, $item);

	if ($can_thank) {
		$tags['THANKS_CAN'] = true;
		$ajax = !empty($cfg['ajax']);
		if ($ajax) {
			$onclick = "javascript:sedjs.ajaxbind({'url': '" . $thank_ajax_url . "&ajax=1', 'format': 'html', 'method': 'POST', 'update': '#thanks-" . sed_cc($ext) . "-" . $item . "', 'loading': '#thanks-" . sed_cc($ext) . "-" . $item . "'}); return false;";
			$tags['THANKS_BUTTON'] = sed_link($thank_url, $L['thanks_thanks'], array('onclick' => $onclick, 'title' => $L['thanks_thanks_title'], 'class' => 'post-thanks-btn'));
		} else {
			$tags['THANKS_BUTTON'] = sed_link($thank_url, $L['thanks_thanks'], array('title' => $L['thanks_thanks_title'], 'class' => 'post-thanks-btn'));
		}
	}

	$t = new XTemplate(sed_skinfile('thanks.' . $ext, true));
	$t->assign($tags);
	$block = $inner_only ? 'THANKS_INNER' : 'THANKS_BLOCK';
	$t->parse($block);
	return $t->text($block);
}
