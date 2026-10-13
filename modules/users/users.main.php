<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/users.main.php
Version=186
Updated=2026-oct-09
Type=Module
Author=Seditio Team
Description=Users list
Lock=0
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$available_sort = array('id', 'name', 'maingrp', 'country', 'timezone', 'email', 'regdate', 'lastlog', 'logcount', 'location', 'occupation', 'birthdate', 'gender');
$available_way = array('asc', 'desc');

$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'ALP', 13);
$w = sed_import('w', 'G', 'ALP', 4);
$d = sed_import('d', 'G', 'INT');
$f = sed_import('f', 'G', 'ALP', 16);
$g = sed_import('g', 'G', 'INT');
$gm = sed_import('gm', 'G', 'INT');
$country = sed_import('country', 'G', 'ALP', 2);
$y = sed_import('y', 'P', 'TXT', 32);
$sq = sed_import('sq', 'G', 'TXT', 32);

// POST filters from user search form
$bycountry = sed_import('bycountry', 'P', 'ALP', 2);
if ($bycountry !== null && $bycountry !== '') {
	$country = $bycountry;
} elseif (empty($country) && !empty($f) && mb_substr($f, 0, 8) == 'country_') {
	$country = mb_strtolower(mb_substr($f, 8, 2));
}

$bymaingroup = sed_import('bymaingroup', 'P', 'INT');
if ($bymaingroup > 0) {
	$g = $bymaingroup;
}

$bygroupms = sed_import('bygroupms', 'P', 'INT');
if ($bygroupms > 0) {
	$gm = $bygroupms;
}

if (!empty($y)) {
	$sq = $y;
} elseif (!empty($sq)) {
	$y = $sq;
}

unset($localskin, $grpms);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['auth_read']);

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('users');
$number_of_extrafields = count($extrafields);

$filter_vars = array();
$filter_sql = array();

if (count($extrafields) > 0) {
	foreach ($extrafields as $key => $val) {
		if (in_array($val['vartype'], array('INT', 'BOL'))) {
			$filter_vars['filter_' . $key] = sed_import('filter_' . $key, 'G', $val['vartype']);
			if (!empty($filter_vars['filter_' . $key])) {
				$filter_sql[] = "user_" . $key . " = '" . $filter_vars['filter_' . $key] . "'";
			}
		}
	}
}

// ----------------------

/* === Hook === */
foreach (sed_getextplugins('users.first') as $pl) {
	include $pl;
}
/* ===== */

if ((!empty($s) && !in_array($s, $available_sort)) || empty($s)) {
	$s = 'name';
}

if ((!empty($w) && !in_array($w, $available_way)) || empty($w)) {
	$w = 'asc';
}

if (empty($f)) {
	$f = 'all';
}
if (empty($d)) {
	$d = '0';
}

$title = sed_link(sed_url("users"), $L['Users']) . " ";
$localskin = sed_skinfile('users');

$where = array();
$title_parts = array();

// 1. Text search by username
if (!empty($y) && mb_strlen($y) > 0) {
	$where[] = "u.user_name LIKE '%" . sed_sql_prep($y) . "%'";
	$title_parts[] = $L['Search'] . " '" . sed_cc($y) . "'";
}

// 2. Filter by first letter
if (mb_strlen($f) == 1) {
	if ($f == "_") {
		$where[] = "u.user_name NOT REGEXP('^[a-zA-Z]')";
		$title_parts[] = $L['use_byfirstletter'] . " '%'";
	} else {
		$f = mb_strtoupper($f);
		$where[] = "u.user_name LIKE '" . sed_sql_prep($f) . "%'";
		$title_parts[] = $L['use_byfirstletter'] . " '" . $f . "'";
	}
}

// 3. Filter by country
if (!empty($country)) {
	if ($country == '00') {
		$where[] = "(u.user_country = '' OR u.user_country = '00' OR u.user_country IS NULL)";
		$title_parts[] = $L['Country'] . " '" . $L['None'] . "'";
	} elseif (isset($sed_countries[$country])) {
		$where[] = "u.user_country = '" . sed_sql_prep($country) . "'";
		$title_parts[] = $L['Country'] . " '" . $sed_countries[$country] . "'";
	}
}

// 4. Filter by main group
if ($g > 1) {
	$where[] = "u.user_maingrp = " . (int)$g;
	$title_parts[] = $L['Maingroup'] . " = " . sed_build_group($g);
}

// 5. Filter by secondary group
$join_groups = "";
if ($gm > 1) {
	$join_groups = " LEFT JOIN $db_groups_users as gu ON gu.gru_userid=u.user_id ";
	$where[] = "gu.gru_groupid = " . (int)$gm;
	$title_parts[] = $L['Group'] . " = " . sed_build_group($gm);
}

// 6. Extra fields filter
if (!empty($filter_sql)) {
	foreach ($filter_sql as $fsql) {
		$where[] = "u." . $fsql;
	}
}

$sql_where = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

if (!empty($title_parts)) {
	$title .= $cfg['separator'] . " " . implode(', ', $title_parts);
}

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users as u " . $join_groups . $sql_where);
$totalusers = (int)sed_sql_result($sql, 0, "COUNT(*)");

if ($s == 'maingrp') {
	$sql = sed_sql_query("SELECT u.* FROM $db_users as u " . $join_groups . " LEFT JOIN $db_groups as g ON g.grp_id=u.user_maingrp " . $sql_where . " ORDER BY grp_level $w LIMIT $d," . $cfg['maxusersperpage']);
} else {
	$sql = sed_sql_query("SELECT u.* FROM $db_users as u " . $join_groups . $sql_where . " ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
}

$totalusers = isset($totalusers) ? $totalusers : 0;
$totalpage = ceil($totalusers / $cfg['maxusersperpage']);
$currentpage = ceil($d / $cfg['maxusersperpage']) + 1;

// Build search & filter form
$allfilters = "<form action=\"" . sed_url("users") . "\" method=\"post\">";
$allfilters .= "<div>" . $L['Filters'] . ": " . sed_link(sed_url("users"), $L['All']) . "</div>";
if (mb_strlen($f) == 1) {
	$allfilters .= sed_textbox_hidden('f', $f);
}
if (!empty($s)) {
	$allfilters .= sed_textbox_hidden('s', $s);
}
if (!empty($w)) {
	$allfilters .= sed_textbox_hidden('w', $w);
}

// Country select
$allfilters .= "<div><select name=\"bycountry\" class=\"sed-select\" size=\"1\" onchange=\"this.form.submit()\">";
$allfilters .= "<option value=\"\">" . $L['Country'] . "...</option>";
$selected = ($country === '00') ? 'selected="selected"' : '';
$allfilters .= "<option value=\"00\" " . $selected . ">" . $L['None'] . "</option>";

foreach ($sed_countries as $i => $x) {
	if ($i != '00') {
		$selected = ($i === $country) ? 'selected="selected"' : '';
		$allfilters .= "<option value=\"" . $i . "\" " . $selected . ">" . sed_cutstring($x, 23) . "</option>";
	}
}
$allfilters .= "</select></div>";

// Main group select
$allfilters .= "<div><select name=\"bymaingroup\" class=\"sed-select\" size=\"1\" onchange=\"this.form.submit()\"><option value=\"\">" . $L['Maingroup'] . "...</option>";
foreach ($sed_groups as $k => $i) {
	$selected = ($k == $g) ? 'selected="selected"' : '';
	if (!($sed_groups[$k]['hidden'] && !sed_auth('users', 'a', 'A'))) {
		$allfilters .= ($k > 1) ? "<option value=\"" . $k . "\" $selected>" . $sed_groups[$k]['title'] . ($sed_groups[$k]['hidden'] ? ' (' . $L['Hidden'] . ')' : '') . "</option>" : '';
	}
}
$allfilters .= "</select></div>";

// Secondary group select
$allfilters .= "<div><select name=\"bygroupms\" class=\"sed-select\" size=\"1\" onchange=\"this.form.submit()\"><option value=\"\">" . $L['Group'] . "...</option>";
foreach ($sed_groups as $k => $i) {
	$selected1 = ($k == $gm) ? 'selected="selected"' : '';
	if (!($sed_groups[$k]['hidden'] && !sed_auth('users', 'a', 'A'))) {
		$allfilters .= ($k > 1) ? "<option value=\"" . $k . "\" $selected1>" . $sed_groups[$k]['title'] . ($sed_groups[$k]['hidden'] ? ' (' . $L['Hidden'] . ')' : '') . "</option>" : '';
	}
}
$allfilters .= "</select></div>";

$allfilters .= "<div>" . sed_textbox('y', $y, 16, 32) . " " . sed_button($L['Search'], 'submit', '', 'submit btn') . "</div></form>";

$alpafilters = "\n" . $L['Byfirstletter'] . ":";

for ($i = 1; $i <= 26; $i++) {
	$j = chr($i + 64);
	$alpafilters .= " " . sed_link(sed_url("users", "f=" . $j), $j, array('class' => 'alfabeta'));
}

$alpafilters .= " " . sed_link(sed_url("users", "f=_"), '%', array('class' => 'alfabeta'));

$out['subtitle'] = $L['Users'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('userstitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths[sed_url("users")] = $L['Users'];

/* === Hook === */
foreach (sed_getextplugins('users.main') as $pl) {
	include $pl;
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$t = new XTemplate($localskin);

$filter_query_params = array();
if (!empty($country)) $filter_query_params[] = "country=" . $country;
if ($g > 1) $filter_query_params[] = "g=" . $g;
if ($gm > 1) $filter_query_params[] = "gm=" . $gm;
if (!empty($sq)) $filter_query_params[] = "sq=" . urlencode($sq);
if (mb_strlen($f) == 1) $filter_query_params[] = "f=" . $f;
$base_filter_query = !empty($filter_query_params) ? implode('&', $filter_query_params) : "";
$base_url_sort = !empty($base_filter_query) ? $base_filter_query . "&" : "";

$pagination_url = !empty($base_filter_query) ? $base_filter_query . "&s=" . $s . "&w=" . $w : "s=" . $s . "&w=" . $w;
$pagination = sed_pagination(sed_url("users", $pagination_url), $d, $totalusers, $cfg['maxusersperpage']);
list($pageprev, $pagenext) = sed_pagination_pn(sed_url("users", $pagination_url), $d, $totalusers, $cfg['maxusersperpage'], TRUE);

if (!empty($pagination)) {
	$t->assign(array(
		"USERS_TOP_PAGEPREV" => $pageprev,
		"USERS_TOP_PAGENEXT" => $pagenext,
		"USERS_TOP_PAGINATION" => $pagination,
	));
	$t->parse("MAIN.USERS_PAGINATION_TP");
	$t->parse("MAIN.USERS_PAGINATION_BM");
}

$t->assign(array(
	"USERS_TITLE" => $title,
	"USERS_SHORTTITLE" => $title,
	"USERS_URL" => sed_url("users"),
	"USERS_SUBTITLE" => $L['use_subtitle'],
	"USERS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"USERS_CURRENTFILTER" => $f,
	"USERS_TOP_CURRENTPAGE" => $currentpage,
	"USERS_TOP_TOTALPAGE" => $totalpage,
	"USERS_TOP_MAXPERPAGE" => $cfg['maxusersperpage'],
	"USERS_TOP_TOTALUSERS" => $totalusers,
	"USERS_TOP_FILTERS" => $allfilters,
	"USERS_TOP_ALPHAFILTERS" => $alpafilters,
	"USERS_TOP_PM" => $L['Message'],
	"USERS_TOP_USERID" => sed_link(sed_url("users", $base_url_sort . "s=id&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=id&w=desc"), $out['ic_arrow_up']) . " " . $L['Userid'],
	"USERS_TOP_NAME" => sed_link(sed_url("users", $base_url_sort . "s=name&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=name&w=desc"), $out['ic_arrow_up']) . " " . $L['Username'],
	"USERS_TOP_MAINGRP" => sed_link(sed_url("users", $base_url_sort . "s=maingrp&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=maingrp&w=desc"), $out['ic_arrow_up']) . " " . $L['Maingroup'],
	"USERS_TOP_COUNTRY" => sed_link(sed_url("users", $base_url_sort . "s=country&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=country&w=desc"), $out['ic_arrow_up']) . " " . $L['Country'],
	"USERS_TOP_TIMEZONE" => sed_link(sed_url("users", $base_url_sort . "s=timezone&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=timezone&w=desc"), $out['ic_arrow_up']) . " " . $L['Timezone'],
	"USERS_TOP_EMAIL" => sed_link(sed_url("users", $base_url_sort . "s=email&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=email&w=desc"), $out['ic_arrow_up']) . " " . $L['Email'],
	"USERS_TOP_REGDATE" => sed_link(sed_url("users", $base_url_sort . "s=regdate&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=regdate&w=desc"), $out['ic_arrow_up']) . " " . $L['Registered'],
	"USERS_TOP_LASTLOGGED" => sed_link(sed_url("users", $base_url_sort . "s=lastlog&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=lastlog&w=desc"), $out['ic_arrow_up']) . " " . $L['Lastlogged'],
	"USERS_TOP_LOGCOUNT" => sed_link(sed_url("users", $base_url_sort . "s=logcount&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=logcount&w=desc"), $out['ic_arrow_up']) . " " . $L['Count'],
	"USERS_TOP_LOCATION" => sed_link(sed_url("users", $base_url_sort . "s=location&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=location&w=desc"), $out['ic_arrow_up']) . " " . $L['Location'],
	"USERS_TOP_OCCUPATION" => sed_link(sed_url("users", $base_url_sort . "s=occupation&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=occupation&w=desc"), $out['ic_arrow_up']) . " " . $L['Occupation'],
	"USERS_TOP_BIRTHDATE" => sed_link(sed_url("users", $base_url_sort . "s=birthdate&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=birthdate&w=desc"), $out['ic_arrow_up']) . " " . $L['Birthdate'],
	"USERS_TOP_GENDER" => sed_link(sed_url("users", $base_url_sort . "s=gender&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=gender&w=desc"), $out['ic_arrow_up']) . " " . $L['Gender'],
	"USERS_TOP_TIMEZONE" => sed_link(sed_url("users", $base_url_sort . "s=timezone&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url("users", $base_url_sort . "s=timezone&w=desc"), $out['ic_arrow_up']) . " " . $L['Timezone']
));

// ----- Extra fields 
if ($number_of_extrafields > 0) {
	foreach ($extrafields as $row) {
		$extratitle = isset($L['user_' . $row['code'] . '_title']) ? $L['user_' . $row['code'] . '_title'] : $row['title'];
		$t->assign('USERS_TOP_' . strtoupper($row['code']), sed_link(sed_url('users', $base_url_sort . "s=" . $row['code'] . "&w=asc"), $out['ic_arrow_down']) . sed_link(sed_url('users', $base_url_sort . "s=" . $row['code'] . "&w=desc"), $out['ic_arrow_up']) . " " . $extratitle);
	}
}
//--------------- 

$jj = 0;

/* === Hook - Part1 : Set === */
$extp = sed_getextplugins('users.loop');
/* ===== */

while ($urr = sed_sql_fetchassoc($sql) and $jj < $cfg['maxusersperpage']) {
	$jj++;
	$urr['user_age'] = ($urr['user_birthdate'] > 0) ? sed_build_age($urr['user_birthdate']) : '';
	$urr['user_birthdate'] = ($urr['user_birthdate'] > 0) ? @date($cfg['formatyearmonthday'], $urr['user_birthdate']) : '';
	$urr['user_gender'] = ($urr['user_gender'] == '' || $urr['user_gender'] == 'U') ?  '' : $L["Gender_" . $urr['user_gender']];

	$t->assign(array(
		"USERS_ROW_USERID" => $urr['user_id'],
		"USERS_ROW_PM" => sed_build_pm($urr['user_id']),
		"USERS_ROW_NAME" => sed_build_user($urr['user_id'], sed_cc($urr['user_name'])),
		"USERS_ROW_FIRSTNAME" => sed_cc($urr['user_firstname']),
		"USERS_ROW_LASTNAME" => sed_cc($urr['user_lastname']),
		"USERS_ROW_USERURL" => sed_url("users", "m=details&id=" . $urr['user_id']),
		"USERS_ROW_MAINGRP" => sed_build_group($urr['user_maingrp']),
		"USERS_ROW_MAINGRPID" => $urr['user_maingrp'],
		"USERS_ROW_MAINGRPSTARS" => sed_build_stars($sed_groups[$urr['user_maingrp']]['level']),
		"USERS_ROW_MAINGRPICON" => sed_build_userimage($sed_groups[$urr['user_maingrp']]['icon']),
		"USERS_ROW_COUNTRY" => sed_build_country($urr['user_country']),
		"USERS_ROW_COUNTRYFLAG" => sed_build_flag($urr['user_country']),
		"USERS_ROW_TEXT" => $urr['user_text'],
		"USERS_ROW_WEBSITE" => sed_build_url($urr['user_website']),
		"USERS_ROW_GENDER" => $urr['user_gender'],
		"USERS_ROW_BIRTHDATE" => $urr['user_birthdate'],
		"USERS_ROW_AGE" => $urr['user_age'],
		"USERS_ROW_TIMEZONE" => sed_build_timezone($urr['user_timezone']),
		"USERS_ROW_LOCATION" => sed_cc($urr['user_location']),
		"USERS_ROW_OCCUPATION" => sed_cc($urr['user_occupation']),
		"USERS_ROW_AVATAR" => sed_build_userimage($urr['user_avatar']),
		"USERS_ROW_SIGNATURE" => sed_build_userimage($urr['user_signature']),
		"USERS_ROW_PHOTO" => sed_build_userimage($urr['user_photo']),
		"USERS_ROW_EMAIL" => sed_build_email($urr['user_email'], $urr['user_hideemail']),
		"USERS_ROW_REGDATE" => sed_build_date($cfg['formatyearmonthday'], $urr['user_regdate']),
		"USERS_ROW_PMNOTIFY" => $sed_yesno[$urr['user_pmnotify']],
		"USERS_ROW_LASTLOG" => sed_build_date($cfg['dateformat'], $urr['user_lastlog']),
		"USERS_ROW_LOGCOUNT" => $urr['user_logcount'],
		"USERS_ROW_LASTIP" => $urr['user_lastip'],
		"USERS_ROW_ODDEVEN" => sed_build_oddeven($jj),
		"USERS_ROW" => $urr
	));

	// ---------- Extra fields - getting
	if (count($extrafields) > 0) {
		$extra_array = sed_build_extrafields_data('user', 'USERS_ROW', $extrafields, $urr);
		$t->assign($extra_array);
	}
	// ----------------------		

	/* === Hook - Part2 : Include === */
	foreach ($extp as $pl) {
		include $pl;
	}
	/* ===== */

	$t->parse("MAIN.USERS_ROW");
}

/* === Hook === */
foreach (sed_getextplugins('users.tags') as $pl) {
	include $pl;
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
