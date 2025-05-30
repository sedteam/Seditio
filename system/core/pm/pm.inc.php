<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=pm.inc.php
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
sed_block($usr['auth_read']);

$id = sed_import('id', 'G', 'INT');
$f = sed_import('f', 'G', 'ALP');
$to = sed_import('to', 'G', 'TXT');
$q = sed_import('q', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');

unset($touser, $pm_editbox);
$totalrecipients = 0;
$touser_all = array();
$touser_sql = array();
$touser_ids = array();
$touser_names = array();

/* === Hook === */
$extp = sed_getextplugins('pm.first');
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

if (empty($d)) {
	$d = '0';
}
unset($pageprev, $pagenext);

// ---------- Breadcrumbs
$urlpaths = array();

if (!empty($id)) // -------------- Single mode
{
	unset($mode);
	$sql1 = sed_sql_query("SELECT pm_touserid, pm_fromuserid, pm_state FROM $db_pm WHERE pm_id='" . $id . "'");
	sed_die(sed_sql_numrows($sql1) == 0);
	$row1 = sed_sql_fetchassoc($sql1);

	$title = sed_link(sed_url("pm"), $L['Private_Messages']) . $cfg['separator'];

	$shorttitle = $L['Private_Messages'];
	$urlpaths[sed_url("pm")] = $L['Private_Messages'];

	if ($row1['pm_touserid'] == $usr['id'] && $row1['pm_state'] == 2) {
		$f = 'archives';
		$title .= " " . sed_link(sed_url("pm", "f=archives"), $L['pm_archives']);
		$subtitle = '';

		$shorttitle = $L['pm_archives'];
		$urlpaths[sed_url("pm", "f=archives")] = $L['pm_archives'];
	} elseif ($row1['pm_touserid'] == $usr['id'] && $row1['pm_state'] < 2) {
		$f = 'inbox';
		$title .= " " . sed_link(sed_url("pm", "f=inbox"), $L['pm_inbox']);
		$subtitle = '';

		$shorttitle = $L['pm_inbox'];
		$urlpaths[sed_url("pm", "f=inbox")] = $L['pm_inbox'];

		if ($row1['pm_state'] == 0) {
			$sql1 = sed_sql_query("UPDATE $db_pm SET pm_state=1 WHERE pm_touserid='" . $usr['id'] . "' AND pm_id='" . $id . "'");
			$sql1 = sed_sql_query("SELECT COUNT(*) FROM $db_pm WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=0");
			$notread = sed_sql_result($sql1, 0, 'COUNT(*)');
			if ($notread == 0) {
				$sql = sed_sql_query("UPDATE $db_users SET user_newpm=0 WHERE user_id='" . $usr['id'] . "'");
			}
		}
	} elseif ($row1['pm_fromuserid'] == $usr['id'] && $row1['pm_state'] == 0) {
		$f = 'sentbox';
		$title .= " " . sed_link(sed_url("pm", "f=sentbox"), $L['pm_sentbox']);
		$subtitle = '';

		$shorttitle = $L['pm_sentbox'];
		$urlpaths[sed_url("pm", "f=sentbox")] = $L['pm_sentbox'];
	} else {
		sed_die();
	}

	$title .= ' ' . $cfg['separator'] . " " . sed_link(sed_url("pm", "id=" . $id), "#" . $id);
	$urlpaths[sed_url("pm", "id=" . $id)] = "#" . $id;

	$sql = sed_sql_query("SELECT *, u.user_name FROM $db_pm AS p LEFT JOIN $db_users AS u ON u.user_id=p.pm_touserid WHERE pm_id='" . $id . "'");
} else // --------------- List mode

{
	unset($id);

	$title = sed_link(sed_url("pm"), $L['Private_Messages']) . " " . $cfg['separator'];

	if ($f == 'archives') {
		$totallines = $totalarchives;
		$sql = sed_sql_query("SELECT * FROM $db_pm
			WHERE pm_touserid='" . $usr['id'] . "' AND pm_state=2
			ORDER BY pm_date DESC LIMIT $d," . $cfg['maxrowsperpage']);
		$title .= " " . sed_link(sed_url("pm", "f=archives"), $L['pm_archives']);
		$subtitle = $L['pm_arcsubtitle'];

		$shorttitle = $L['pm_archives'];
		$urlpaths[sed_url("pm", "f=archives")] = $L['pm_archives'];
	} elseif ($f == 'sentbox') {
		$totallines = $totalsentbox;
		$sql = sed_sql_query("SELECT p.*, u.user_name FROM $db_pm p, $db_users u
       		WHERE p.pm_fromuserid='" . $usr['id'] . "' AND p.pm_state=0 AND u.user_id=p.pm_touserid
			ORDER BY pm_date DESC LIMIT $d," . $cfg['maxrowsperpage']);
		$title .= " " . sed_link(sed_url("pm", "f=sentbox"), $L['pm_sentbox']);
		$subtitle = $L['pm_sentboxsubtitle'];

		$shorttitle = $L['pm_sentbox'];
		$urlpaths[sed_url("pm", "f=sentbox")] = $L['pm_sentbox'];
	} else {
		$f = 'inbox';
		$totallines = $totalinbox;
		$sql = sed_sql_query("SELECT * FROM $db_pm
			WHERE pm_touserid='" . $usr['id'] . "' AND pm_state<2
			ORDER BY pm_date DESC LIMIT  $d," . $cfg['maxrowsperpage']);
		$title .= " " . sed_link(sed_url("pm"), $L['pm_inbox']);
		$subtitle = $L['pm_inboxsubtitle'];

		$shorttitle = $L['pm_inbox'];
		$urlpaths[sed_url("pm")] = $L['pm_inbox'];
	}

	$pm_totalpages = ceil($totallines / $cfg['maxrowsperpage']);
	$pm_currentpage = ceil($d / $cfg['maxrowsperpage']) + 1;

	$pm_pagination = sed_pagination(sed_url("pm", "f=" . $f), $d, $totallines, $cfg['maxrowsperpage']);
	list($pm_pageprev, $pm_pagenext) = sed_pagination_pn(sed_url("pm", "f=" . $f), $d, $totallines, $cfg['maxrowsperpage'], TRUE);
}

$out['subtitle'] = $L['Private_Messages'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pmtitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('pm.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$pm_sendlink = ($usr['auth_write']) ? sed_link(sed_url("pm", "m=send"), $L['pm_sendnew']) : '';

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/pm.tpl");

$t->assign(array(
	"PM_PAGETITLE" => $title,
	"PM_SHORTTITLE" => $shorttitle,
	"PM_SUBTITLE" => $subtitle,
	"PM_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"PM_SENDNEWPM" => $pm_sendlink,
	"PM_INBOX" => sed_link(sed_url("pm"), $L['pm_inbox']) . ": " . $totalinbox,
	"PM_ARCHIVES" => sed_link(sed_url("pm", "f=archives"), $L['pm_archives']) . ": " . $totalarchives,
	"PM_SENTBOX" => sed_link(sed_url("pm", "f=sentbox"), $L['pm_sentbox']) . ": " . $totalsentbox,
	"PM_TOP_PAGEPREV" => isset($pm_pageprev) ? $pm_pageprev : '',
	"PM_TOP_PAGENEXT" => isset($pm_pagenext) ? $pm_pagenext : '',
	"PM_TOP_PAGINATION" => isset($pm_pagination) ? $pm_pagination : '',
	"PM_TOP_CURRENTPAGE" => isset($pm_currentpage) ? $pm_currentpage : '',
	"PM_TOP_TOTALPAGES" => isset($pm_totalpages) ? $pm_totalpages : '0'
));

$jj = 0;

/* === Hook - Part1 : Set === */
$extp = sed_getextplugins('pm.loop');
/* ===== */

while ($row = sed_sql_fetchassoc($sql) and ($jj < $cfg['maxrowsperpage'])) {
	$jj++;
	$row['pm_icon_status'] = ($row['pm_state'] == '0' && $f != 'sentbox') ?
		sed_link(sed_url("pm", "id=" . $row['pm_id']), $out['ic_pm_new']) :
		sed_link(sed_url("pm", "id=" . $row['pm_id']), $out['ic_pm']);

	if ($f == 'sentbox') {
		$pm_fromuserid = $usr['id'];
		$pm_fromuser = sed_cc($usr['name']);
		$pm_touserid = $row['pm_touserid'];
		$pm_touser = sed_cc($row['user_name']);
		$pm_fromortouser = sed_build_user($pm_touserid, $pm_touser);
		$row['pm_icon_action'] = sed_link(sed_url("pm", "m=edit&a=delete&" . sed_xg() . "&id=" . $row['pm_id'] . "&f=" . $f), $out['ic_pm_trashcan']);

		if (!empty($id)) {
			$pm_editbox = "<h4>" . $L['Edit'] . " :</h4>";
			$pm_editbox .= "<form id=\"newlink\" action=\"" . sed_url("pm", "m=edit&a=update&" . sed_xg() . "&id=" . $id) . "\" method=\"post\">";
			$pm_editbox .= sed_textarea('newpmtext', $row['pm_text'], 8, 56, 'Basic');
			$pm_editbox .= "<br />&nbsp;<br />" . sed_button($L['Update'], 'submit', 'submit', 'submit btn', false) . "</form>";
		}
	} elseif ($f == 'archives') {
		$pm_fromuserid = $row['pm_fromuserid'];
		$pm_fromuser = sed_cc($row['pm_fromuser']);
		$pm_touserid = $usr['id'];
		$pm_touser = sed_cc($usr['name']);
		$pm_fromortouser = sed_build_user($pm_fromuserid, $pm_fromuser);
		$row['pm_icon_action'] = sed_link(sed_url("pm", "m=send&to=" . $row['pm_fromuserid'] . "&q=" . $row['pm_id']), $out['ic_pm_reply']) .  " " .
			sed_link(sed_url("pm", "m=edit&a=index&" . sed_xg() . "&id=" . $row['pm_id']), $out['ic_pm_archive']);
		$row['pm_icon_action'] .= " " . sed_link(sed_url("pm", "m=edit&a=delete&" . sed_xg() . "&id=" . $row['pm_id'] . "&f=" . $f), $out['ic_pm_trashcan']);
	} else {
		$pm_fromuserid = $row['pm_fromuserid'];
		$pm_fromuser = sed_cc($row['pm_fromuser']);
		$pm_touserid = $usr['id'];
		$pm_touser = sed_cc($usr['name']);
		$pm_fromortouser = sed_build_user($pm_fromuserid, $pm_fromuser);
		$row['pm_icon_action'] = sed_link(sed_url("pm", "m=send&to=" . $row['pm_fromuserid'] . "&q=" . $row['pm_id']), $out['ic_pm_reply']) . " " .
			sed_link(sed_url("pm", "m=edit&a=archive&" . sed_xg() . "&id=" . $row['pm_id']), $out['ic_pm_archive']);
		$row['pm_icon_action'] .= ($row['pm_state'] > 0) ? " " . sed_link(sed_url("pm", "m=edit&a=delete&" . sed_xg() . "&id=" . $row['pm_id'] . "&f=" . $f), $out['ic_pm_trashcan']) : '';
	}

	$row['pm_text'] = sed_parse($row['pm_text']);

	$t->assign(array(
		"PM_ROW_ID" => $row['pm_id'],
		"PM_ROW_STATE" => $row['pm_state'],
		"PM_ROW_DATE" => sed_build_date($cfg['dateformat'], $row['pm_date']),
		"PM_ROW_FROMUSERID" => $pm_fromuserid,
		"PM_ROW_FROMUSER" => sed_build_user($pm_fromuserid, $pm_fromuser),
		"PM_ROW_TOUSERID" => $pm_touserid,
		"PM_ROW_TOUSER" => sed_build_user($pm_touserid, $pm_touser),
		"PM_ROW_TITLE" => sed_link(sed_url("pm", "id=" . $row['pm_id']), sed_cc($row['pm_title'])),
		"PM_ROW_TEXT" => $row['pm_text'] . (isset($pm_editbox) ? $pm_editbox : ''),
		"PM_ROW_FROMORTOUSER" => $pm_fromortouser,
		"PM_ROW_ICON_STATUS" => $row['pm_icon_status'],
		"PM_ROW_ICON_ACTION" => $row['pm_icon_action'],
		"PM_ROW_ODDEVEN" => sed_build_oddeven($jj)
	));

	/* === Hook - Part2 : Include === */
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	if (empty($id)) {
		$t->parse("MAIN.PM_ROW");
	} else {
		$t->parse("MAIN.PM_DETAILS");
	}
}

if (empty($id)) {
	if ($f == 'sentbox') {
		$t->parse("MAIN.PM_TITLE_SENTBOX");
	} else {
		$t->parse("MAIN.PM_TITLE");
	}

	if ($jj == 0) {
		$t->parse("MAIN.PM_ROW_EMPTY");
	}

	$t->parse("MAIN.PM_FOOTER");
}

/* === Hook === */
$extp = sed_getextplugins('pm.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
