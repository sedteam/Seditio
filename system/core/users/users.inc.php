<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=users.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Users
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
$y = sed_import('y', 'P', 'TXT', 32);
$sq = sed_import('sq', 'G', 'TXT', 32);

unset($localskin, $grpms);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['auth_read']);

// ---------- Extra fields - getting
$extrafields = array();
$extrafields = sed_extrafield_get('users');
$number_of_extrafields = count($extrafields);

$filter_vars = array();
$filter_sql = array();
$sql_where = "";

if (count($extrafields) > 0) {
	foreach ($extrafields as $key => $val) {
		if (in_array($val['vartype'], array('INT', 'BOL'))) {
			$filter_vars['filter_' . $key] = sed_import('filter_' . $key, 'G', $val['vartype']);
			if (!empty($filter_vars['filter_' . $key])) $filter_sql[] = " AND user_" . $key . " = '" . $filter_vars['filter_' . $key] . "'";
		}
	}
}

$sql_where = (count($filter_sql) > 0) ? implode(',', $filter_sql) : " ";

// ----------------------

/* === Hook === */
$extp = sed_getextplugins('users.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
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

if (!empty($sq)) {
	$y = $sq;
}

if ($f == 'search' && mb_strlen($y) > 1) {
	$sq = $y;
	$title .= $cfg['separator'] . " " . $L['Search'] . " '" . sed_cc($y) . "'";
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name LIKE '%" . sed_sql_prep($y) . "%' $sql_where");
	$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_name LIKE '%" . sed_sql_prep($y) . "%' $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
} elseif ($g > 1) {
	$title .= $cfg['separator'] . " " . $L['Maingroup'] . " = " . sed_build_group($g);
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_maingrp='$g' $sql_where");
	$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_maingrp='$g' $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
} elseif ($gm > 1) {
	$title .= $cfg['separator'] . " " . $L['Group'] . " = " . sed_build_group($gm);
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users as u
		LEFT JOIN $db_groups_users as g ON g.gru_userid=u.user_id
		WHERE g.gru_groupid='$gm'");
	$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT u.* FROM $db_users as u
		LEFT JOIN $db_groups_users as g ON g.gru_userid=u.user_id
		WHERE g.gru_groupid='$gm'
		ORDER BY user_$s $w
		LIMIT $d," . $cfg['maxusersperpage']);
} elseif (mb_strlen($f) == 1) {
	if ($f == "_") {
		$title .= $cfg['separator'] . " " . $L['use_byfirstletter'] . " '%'";
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name NOT REGEXP(\"^[a-zA-Z]\")");
		$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
		$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_name NOT REGEXP(\"^[a-zA-Z]\") $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
	} else {
		$f = mb_strtoupper($f);
		$title .= $cfg['separator'] . " " . $L['use_byfirstletter'] . " '" . $f . "'";
		$i = $f . "%";
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name LIKE '$i' $sql_where");
		$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
		$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_name LIKE '$i' $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
	}
} elseif (mb_substr($f, 0, 8) == 'country_') {
	$cn = mb_strtolower(mb_substr($f, 8, 2));
	$cn = isset($sed_countries[$cn]) ? $cn : '00';
	$title .= $cfg['separator'] . " " . $L['Country'] . " '";
	$title .= ($cn == '00') ? $L['None'] . "'" : $sed_countries[$cn] . "'";
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_country='$cn' $sql_where");
	$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_country='$cn' $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
} elseif ($f == 'all') {
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE 1");
	$totalusers = sed_sql_result($sql, 0, "COUNT(*)");
	if ($s == 'maingrp') {
		$sql = sed_sql_query("SELECT u.* FROM $db_users as u LEFT JOIN $db_groups as g ON g.grp_id=u.user_maingrp WHERE 1 $sql_where ORDER BY grp_level $w LIMIT $d," . $cfg['maxusersperpage']);
	} else {
		$sql = sed_sql_query("SELECT * FROM $db_users WHERE 1 $sql_where ORDER BY user_$s $w LIMIT $d," . $cfg['maxusersperpage']);
	}
}

$totalusers = isset($totalusers) ? $totalusers : 0;
$totalpage = ceil($totalusers / $cfg['maxusersperpage']);
$currentpage = ceil($d / $cfg['maxusersperpage']) + 1;

$allfilters = "<form action=\"" . sed_url("users", "f=search") . "\" method=\"post\">";
$allfilters .= "<div>" . $L['Filters'] . ": " . sed_link(sed_url("users"), $L['All']) . "</div>";
$allfilters .= "<div><select name=\"bycountry\" size=\"1\" onchange=\"sedjs.redirect(this)\">";

foreach ($sed_countries as $i => $x) {
	if ($i == '00') {
		$allfilters .= "<option value=\"" . sed_url("users") . "\">" . $L['Country'] . "...</option>";
		$selected = ("country_00" == $f) ? "selected=\"selected\"" : '';
		$allfilters .= "<option value=\"" . sed_url("users", "f=country_00") . "\" " . $selected . ">" . $L['None'] . "</option>";
	} else {
		$selected = ("country_" . $i == $f) ? "selected=\"selected\"" : '';
		$allfilters .= "<option value=\"" . sed_url("users", "f=country_" . $i) . "\" " . $selected . ">" . sed_cutstring($x, 23) . "</option>";
	}
}

$allfilters .= "</select></div>";
$allfilters .= "<div><select name=\"bymaingroup\" size=\"1\" onchange=\"sedjs.redirect(this)\"><option value=\"" . sed_url("users") . "\">" . $L['Maingroup'] . "...";
$grpms = '';
foreach ($sed_groups as $k => $i) {
	$selected = ($k == $g) ? "selected=\"selected\"" : '';
	$selected1 = ($k == $gm) ? "selected=\"selected\"" : '';
	if (!($sed_groups[$k]['hidden'] && !sed_auth('users', 'a', 'A'))) {
		$allfilters .= ($k > 1) ? "<option value=\"" . sed_url("users", "g=" . $k) . "\" $selected> " . $sed_groups[$k]['title'] : '';
		$allfilters .= ($k > 1 && $sed_groups[$k]['hidden']) ? ' (' . $L['Hidden'] . ')' : '';
		$grpms .= ($k > 1) ? "<option value=\"" . sed_url("users", "gm=" . $k) . "\" $selected1> " . $sed_groups[$k]['title'] : '';
		$grpms .= ($k > 1 && $sed_groups[$k]['hidden']) ? ' (' . $L['Hidden'] . ')' : '';
	}
}
$allfilters .= "</select></div>";
$allfilters .= "<div><select name=\"bygroupms\" size=\"1\" onchange=\"sedjs.redirect(this)\"><option value=\"" . sed_url("users") . "\">" . $L['Group'] . "...";
$allfilters .= $grpms . "</select></div>";

$allfilters .= "<div>" . sed_textbox('y', $y, 16, 32) . sed_button($L['Search'], 'submit', '', 'submit btn') . "</div></form>";

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
$extp = sed_getextplugins('users.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$t = new XTemplate($localskin);

$pagination = sed_pagination(sed_url("users", "f=" . $f . "&g=" . $g . "&gm=" . $gm . "&s=" . $s . "&w=" . $w . "&sq=" . $sq), $d, $totalusers, $cfg['maxusersperpage']);
list($pageprev, $pagenext) = sed_pagination_pn(sed_url("users", "f=" . $f . "&g=" . $g . "&gm=" . $gm . "&s=" . $s . "&w=" . $w . "&sq=" . $sq), $d, $totalusers, $cfg['maxusersperpage'], TRUE);

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
	"USERS_TOP_USERID" => sed_link(sed_url("users", "f=" . $f . "&s=id&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=id&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Userid'],
	"USERS_TOP_NAME" => sed_link(sed_url("users", "f=" . $f . "&s=name&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=name&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Username'],
	"USERS_TOP_MAINGRP" => sed_link(sed_url("users", "f=" . $f . "&s=maingrp&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=maingrp&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Maingroup'],
	"USERS_TOP_COUNTRY" => sed_link(sed_url("users", "f=" . $f . "&s=country&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=country&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Country'],
	"USERS_TOP_TIMEZONE" => sed_link(sed_url("users", "f=" . $f . "&s=timezone&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=timezone&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Timezone'],
	"USERS_TOP_EMAIL" => sed_link(sed_url("users", "f=" . $f . "&s=email&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=email&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Email'],
	"USERS_TOP_REGDATE" => sed_link(sed_url("users", "f=" . $f . "&s=regdate&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=regdate&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Registered'],
	"USERS_TOP_LASTLOGGED" => sed_link(sed_url("users", "f=" . $f . "&s=lastlog&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=lastlog&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Lastlogged'],
	"USERS_TOP_LOGCOUNT" => sed_link(sed_url("users", "f=" . $f . "&s=logcount&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=logcount&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Count'],
	"USERS_TOP_LOCATION" => sed_link(sed_url("users", "f=" . $f . "&s=location&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=location&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Location'],
	"USERS_TOP_OCCUPATION" => sed_link(sed_url("users", "f=" . $f . "&s=occupation&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=occupation&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Occupation'],
	"USERS_TOP_BIRTHDATE" => sed_link(sed_url("users", "f=" . $f . "&s=birthdate&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=birthdate&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Birthdate'],
	"USERS_TOP_GENDER" => sed_link(sed_url("users", "f=" . $f . "&s=gender&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=gender&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Gender'],
	"USERS_TOP_TIMEZONE" => sed_link(sed_url("users", "f=" . $f . "&s=timezone&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url("users", "f=" . $f . "&s=timezone&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $L['Timezone']
));

// ----- Extra fields 
if ($number_of_extrafields > 0) {
	foreach ($extrafields as $row) {
		$extratitle = isset($L['user_' . $row['code'] . '_title']) ? $L['user_' . $row['code'] . '_title'] : $row['title'];
		$t->assign('USERS_TOP_' . strtoupper($row['code']), sed_link(sed_url('users', "f=" . $f . "&s=" . $row['code'] . "&w=asc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_down']) . sed_link(sed_url('users', "f=" . $f . "&s=" . $row['code'] . "&w=desc&g=" . $g . "&gm=" . $gm . "&sq=" . $sq), $out['ic_arrow_up']) . " " . $extratitle);
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
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$t->parse("MAIN.USERS_ROW");
}

/* === Hook === */
$extp = sed_getextplugins('users.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
