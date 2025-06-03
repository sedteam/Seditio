<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=pm.send.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Private messages
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pm', 'a');
sed_block($usr['auth_write']);

$id = sed_import('id', 'G', 'INT');
$f = sed_import('f', 'G', 'ALP');
$to = sed_import('to', 'G', 'TXT');
$q = sed_import('q', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');

unset($touser);
$totalrecipients = 0;
$touser_all = array();
$touser_sql = array();
$touser_ids = array();
$touser_names = array();

/* === Hook === */
$extp = sed_getextplugins('pm.send.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=2");
$totalarchives = sed_sql_result($sql, 0, "COUNT(*)");
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_fromuserid='" . $usr['id'] . "' AND pm_state=0");
$totalsentbox = sed_sql_result($sql, 0, "COUNT(*)");
$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state<2");
$totalinbox = sed_sql_result($sql, 0, "COUNT(*)");

if ($a == 'send') {

	/* === Hook === */
	$extp = sed_getextplugins('pm.send.send.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	sed_shield_protect();
	$newpmtitle = sed_import('newpmtitle', 'P', 'TXT');
	$newpmtext = sed_import('newpmtext', 'P', 'HTM');
	$newpmrecipient = sed_import('newpmrecipient', 'P', 'TXT');
	$userid = sed_import('userid', 'P', 'INT');
	$touser_src = explode(",", $newpmrecipient);
	$touser_req = count($touser_src);

	foreach ($touser_src as $k => $i) {
		$touser_sql[] = "'" . sed_sql_prep(trim(sed_import($i, 'D', 'TXT'))) . "'";
	}

	$touser_sql = implode(',', $touser_sql);
	$touser_sql = '(' . $touser_sql . ')';
	$sql = sed_sql_query("SELECT user_id, user_name FROM $db_users WHERE user_name IN $touser_sql");
	$totalrecipients = sed_sql_numrows($sql);

	while ($row = sed_sql_fetchassoc($sql)) {
		$touser_ids[] = $row['user_id'];
		$row['user_name'] = sed_cc($row['user_name']);
		$touser_names[] = $row['user_name'];
		$touser_usrlnk[] = sed_build_user($row['user_id'], $row['user_name']);
	}

	$touser = ($totalrecipients > 0) ? implode(",", $touser_names) : '';
	$error_string .= (mb_strlen($newpmtitle) < 2) ? $L['pm_titletooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($newpmtext) < 2) ? $L['pm_bodytooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($newpmtext) > $cfg['pm_maxsize']) ? $L['pm_bodytoolong'] . "<br />" : '';
	$error_string .= ($totalrecipients < $touser_req) ? $L['pm_wrongname'] . "<br />" : '';
	$error_string .= ($totalrecipients > 10) ? sprintf($L['pm_toomanyrecipients'], 10) . "<br />" : '';

	if (empty($error_string)) {
		$newpmtext .= ($totalrecipients > 1) ? "\n\n" . sprintf($L['pm_multiplerecipients'], $totalrecipients - 1) . "\n" . implode(', ', $touser_usrlnk)  : '';

		foreach ($touser_ids as $k => $userid) {
			$sql = sed_sql_query("INSERT into $db_pm
				(pm_state,
				pm_date,
				pm_fromuserid,
				pm_fromuser,
				pm_touserid,
				pm_title,
				pm_text)
				VALUES
				(0,
				" . (int)$sys['now_offset'] . ",
				" . (int)$usr['id'] . ",
				'" . sed_sql_prep($usr['name']) . "',
				" . (int)$userid . ",
				'" . sed_sql_prep($newpmtitle) . "',
				'" . sed_sql_prep($newpmtext) . "')");

			$sql = sed_sql_query("UPDATE $db_users SET user_newpm=1 WHERE user_id='" . $userid . "'");

			if ($cfg['pm_allownotifications']) {
				$sql = sed_sql_query("SELECT user_email, user_name
					FROM $db_users
					WHERE user_id='$userid' AND user_pmnotify=1 AND user_maingrp>3");

				if ($row = sed_sql_fetchassoc($sql)) {
					$rusername = sed_cc($row['user_name']);
					$remail = $row['user_email'];
					$rsubject = $cfg['maintitle'] . " - " . $L['pm_notifytitle'];
					$rbody = sprintf($L['pm_notify'], $rusername, sed_cc($usr['name']), $cfg['mainurl'] . "/" . sed_url("pm", "", "", false, false));
					sed_mail($remail, $rsubject, $rbody);
					sed_stat_inc('totalmailpmnot');
				}
			}
		}

		/* === Hook === */
		$extp = sed_getextplugins('pm.send.send.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		sed_stat_inc('totalpms');
		sed_shield_update(30, "New private message (" . $totalrecipients . ")");
		sed_redirect(sed_url("message", "msg=502", "", true));
		exit;
	}
} elseif (!empty($to)) {
	if (mb_substr(mb_strtolower($to), 0, 1) == 'g' && $usr['maingrp'] == 5) {
		$group = sed_import(mb_substr($to, 1, 8), 'D', 'INT');
		if ($group > 1) {
			$sql = sed_sql_query("SELECT user_id, user_name FROM $db_users WHERE user_maingrp='$group' ORDER BY user_name ASC");
			$totalrecipients = sed_sql_numrows($sql);
		}
	} else {
		$touser_src = explode('-', $to);
		$touser_req = count($touser_src);

		foreach ($touser_src as $k => $i) {
			$userid = sed_import($i, 'D', 'INT');
			if ($userid > 0) {
				$touser_sql[] = "'" . $userid . "'";
			}
		}
		if (count($touser_sql) > 0) {
			$touser_sql = implode(',', $touser_sql);
			$touser_sql = '(' . $touser_sql . ')';
			$sql = sed_sql_query("SELECT user_id, user_name FROM $db_users WHERE user_id IN $touser_sql");
			$totalrecipients = sed_sql_numrows($sql);
		}
	}

	if ($totalrecipients > 0) {
		while ($row = sed_sql_fetchassoc($sql)) {
			$touser_ids[] = $row['user_id'];
			$touser_names[] = sed_cc($row['user_name']);
		}
		$touser = implode(", ", $touser_names);
		$error_string .= ($totalrecipients < $touser_req) ? $L['pm_wrongname'] . "<br />" : '';
		$error_string .= ($totalrecipients > 10) ? sprintf($L['pm_toomanyrecipients'], 10) . "<br />" : '';
	}
}

if (!empty($q) && empty($newpmtext)) {
	$sql = sed_sql_query("SELECT pm_date,pm_title,pm_text FROM $db_pm WHERE pm_id='$q' AND pm_touserid='" . $usr['id'] . "' AND pm_state<3 ");

	if ($row = sed_sql_fetchassoc($sql)) {
		$pm_date = sed_build_date($cfg['dateformat'], $row['pm_date']) . ' GMT';
		$newpmtext = "<br /><br />-------- " . $L['Originalmessage'] . " --------<br />" . $L['Date'] . " : " . $pm_date . "<br />" . $L['Title'] . " : " . $row['pm_title'] . "\n" . $row['pm_text'] . "<br />-------------<br />";
		$newpmtitle = "Re: " . $row['pm_title'];
	}
}

$pfs = sed_build_pfs($usr['id'], 'newlink', 'newpmtext', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, 'newlink', 'newpmtext', $L['SFS']) : '';
$pm_sendlink = ($usr['auth_write']) ? sed_link(sed_url("pm", "m=send"), $L['pm_sendnew']) : '';

$out['subtitle'] = $L['Private_Messages'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pmtitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pm")] = $L['Private_Messages'];
$urlpaths[sed_url("pm", "m=send")] = $L['pmsend_title'];

/* === Hook === */
$extp = sed_getextplugins('pm.send.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/pm.send.tpl");

if (!empty($error_string)) {
	$t->assign("PMSEND_ERROR_BODY", sed_alert($error_string, 'e'));
	$t->parse("MAIN.PMSEND_ERROR");
}

$t->assign(array(
	"PMSEND_TITLE" => sed_link(sed_url("pm"), $L['Private_Messages']) . " " . $cfg['separator'] . " " . $L['pmsend_title'],
	"PMSEND_SHORTTITLE" => $L['pmsend_title'],
	"PMSEND_SUBTITLE" => $L['pmsend_subtitle'],
	"PMSEND_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PMSEND_SENDNEWPM" => $pm_sendlink,
	"PMSEND_INBOX" => sed_link(sed_url("pm"), $L['pm_inbox']) . ": " . $totalinbox,
	"PMSEND_ARCHIVES" => sed_link(sed_url("pm", "f=archives"), $L['pm_archives']) . ": " . $totalarchives,
	"PMSEND_SENTBOX" => sed_link(sed_url("pm", "f=sentbox"), $L['pm_sentbox']) . ": " . $totalsentbox,
	"PMSEND_FORM_SEND" => sed_url("pm", "m=send&a=send&to=" . $to),
	"PMSEND_FORM_TITLE" => sed_textbox('newpmtitle', isset($newpmtitle) ? $newpmtitle : '', 64, 64),
	"PMSEND_FORM_TEXT" =>  sed_textarea('newpmtext', isset($newpmtext) ? $newpmtext : '', $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Basic') . $pfs,
	"PMSEND_FORM_MYPFS" => $pfs,
	"PMSEND_FORM_TOUSER" => sed_textarea('newpmrecipient', isset($touser) ? $touser : '', 2, $cfg['textarea_default_width'])
));

/* === Hook === */
$extp = sed_getextplugins('pm.send.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
