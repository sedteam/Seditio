<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=pfs.edit.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$id = sed_import('id', 'G', 'INT');
$o = sed_import('o', 'G', 'TXT');
$f = sed_import('f', 'G', 'INT');
$v = sed_import('v', 'G', 'TXT');
$c1 = sed_import('c1', 'G', 'TXT');
$c2 = sed_import('c2', 'G', 'TXT');
$userid = sed_import('userid', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
sed_block($usr['auth_write']);

$more = '';

if (!$usr['isadmin'] || $userid == '') {
	$userid = $usr['id'];
	$useradm = FALSE;
} else {
	$more = "userid=" . $userid;
	$useradm = ($userid != $usr['id']) ? TRUE : FALSE;
}

if ($userid != $usr['id']) {
	sed_block($usr['isadmin']);
}

$standalone = FALSE;
$user_info = sed_userinfo($userid);
$maingroup = ($userid == 0) ? 5 : $user_info['user_maingrp'];

$moretitle = ($userid > 0 && $useradm) ? " &laquo;" . $user_info['user_name'] . "&raquo;" : "";

reset($sed_extensions);
foreach ($sed_extensions as $k => $line) {
	$icon[$line[0]] = "<img src=\"system/img/pfs/" . $line[2] . ".gif\" alt=\"" . $line[1] . "\" />";
	$filedesc[$line[0]] = $line[1];
}

if (!empty($c1) || !empty($c2)) {
	$more = "c1=" . $c1 . "&c2=" . $c2 . "&" . $more;
	$standalone = TRUE;
}

/* ============= */

$title = $L['pfs_editfile'];
$shorttitle = $L['pfs_editfile'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pfs", $more)] = $L['PFS'] . $moretitle;

if ($userid != $usr['id']) {
	sed_block($usr['isadmin']);
}

$urlpaths[sed_url("pfs", "m=edit&id=" . $id . "&" . $more)] = $L['pfs_editfile'];

$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$id' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	$pfs_id = $row['pfs_id'];
	$pfs_file = $row['pfs_file'];
	$pfs_date = sed_build_date($cfg['dateformat'], $row['pfs_date']);
	$pfs_folderid = $row['pfs_folderid'];
	$pfs_extension = $row['pfs_extension'];
	$pfs_desc = $row['pfs_desc'];
	$pfs_title = sed_cc($row['pfs_title']);
	$pfs_size = floor($row['pfs_size'] / 1024);
	$ff = $cfg['pfs_dir'] . $pfs_file;
} else {
	sed_die();
}

$title .= " " . $cfg['separator'] . " " . sed_cc($pfs_file);

$subtitle = '';
$out['subtitle'] = $L['Mypfs'] . " - " . $L['Edit'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

if ($a == 'update' && !empty($id)) {
	$rdesc = sed_import('rdesc', 'P', 'HTM');
	$rtitle = sed_import('rtitle', 'P', 'TXT');
	$folderid = sed_import('folderid', 'P', 'INT');
	if ($folderid > 0) {
		$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$folderid'");
		sed_die(sed_sql_numrows($sql) == 0);
	} else {
		$folderid = 0;
	}

	$sql = sed_sql_query("UPDATE $db_pfs SET
		pfs_desc='" . sed_sql_prep($rdesc) . "',
		pfs_title='" . sed_sql_prep($rtitle) . "',
		pfs_folderid='$folderid'
		WHERE pfs_userid='$userid' AND pfs_id='$id'");

	sed_redirect(sed_url("pfs", "f=" . $pfs_folderid . "&" . $more, "", true));
	exit;
}

if ($standalone) {
	sed_sendheaders();

	$pfs_header1 = $cfg['doctype'] . "\n<html>\n<head>
	<title>" . $cfg['maintitle'] . "</title>" . sed_htmlmetas() . $moremetas . sed_javascript($morejavascript);
	$pfs_header2 = "</head>\n<body>";
	$pfs_footer = "</body>\n</html>";

	/* === Hook === */
	$extp = sed_getextplugins('pfs.stndl');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$mskin = sed_skinfile(array('pfs.edit', 'standalone'));
	$t = new XTemplate($mskin);

	$t->assign(array(
		"PFS_STANDALONE_HEADER1" => $pfs_header1,
		"PFS_STANDALONE_HEADER2" => $pfs_header2,
		"PFS_STANDALONE_FOOTER" => $pfs_footer
	));
} else {
	require(SED_ROOT . "/system/header.php");
	$mskin = sed_skinfile('pfs.edit');
	$t = new XTemplate($mskin);
}

$t->assign(array(
	"PFS_EDITFILE_SEND" => sed_url("pfs", "m=edit&a=update&id=" . $pfs_id . "&" . $more),
	"PFS_EDITFILE_TITLE" => sed_textbox('rtitle', $pfs_title, 56, 255),
	"PFS_EDITFILE_DESC" => sed_textarea('rdesc', $pfs_desc, 8, 56, 'Micro'),
	"PFS_EDITFILE_FILE" => $pfs_file,
	"PFS_EDITFILE_FOLDER" => sed_selectbox_folders($userid, "", $pfs_folderid),
	"PFS_EDITFILE_DATE" => $pfs_date,
	"PFS_EDITFILE_URL" => sed_link($ff, $ff),
	"PFS_EDITFILE_SIZE" => $pfs_size . " " . $L['kb'],
));

$t->parse("MAIN.PFS_EDITFILE");

$t->assign(array(
	"PFS_TITLE" => $title,
	"PFS_SHORTTITLE" => $shorttitle,
	"PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths, 1, !$standalone),
	"PFS_SUBTITLE" => $subtitle
));

/* === Hook === */
$extp = sed_getextplugins('pfs.editfolder.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

if ($standalone) {
	@ob_end_flush();
	@ob_end_flush();
	sed_sql_close($connection_id);
} else {
	require(SED_ROOT . "/system/footer.php");
}
