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
 * Returns user avatar image
 *
 * @param string $image Image src
 * @return string
 */
function sed_build_userimage($image)
{
	global $cfg;
	if (!empty($image)) {
		$result = "<img src=\"" . $image . "\" alt=\"\" class=\"avatar post-author-avatar\" />";
	} else {
		$result = "<img src=\"" . $cfg['defav_dir'] . "default.png\" alt=\"\" class=\"avatar post-author-avatar\" />";
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
