<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=pfs.editfolder.inc.php
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

$id = sed_import('id', 'G', 'TXT');
$o = sed_import('o', 'G', 'TXT');
$f = sed_import('f', 'G', 'INT');
$v = sed_import('v', 'G', 'TXT');
$c1 = sed_import('c1', 'G', 'TXT');
$c2 = sed_import('c2', 'G', 'TXT');
$userid = sed_import('userid', 'G', 'INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
list($usr['auth_read_gal'], $usr['auth_write_gal'], $usr['isadmin_gal']) = sed_auth('gallery', 'a');
sed_block($usr['auth_write']);

$L_pff_type[0] = $L['Private'];
$L_pff_type[1] = $L['Public'];
$L_pff_type[2] = $L['Gallery'];

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

$user_info = sed_userinfo($userid);

$standalone = FALSE;
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

$title = $L['pfs_editfolder'];
$shorttitle = $L['pfs_editfolder'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pfs", $more)] = $L['PFS'] . $moretitle;
$urlpaths[sed_url("pfs", "m=editfolder&f=" . $f . "&" . $more)] = $L['pfs_editfolder'];

if ($userid != $usr['id']) {
	sed_block($usr['isadmin']);
}

$subtitle = '';
$out['subtitle'] = $L['Mypfs'] . " - " . $L['Edit'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

$sql = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	$pff_id = $row['pff_id'];
	$pff_date = sed_build_date($cfg['dateformat'], $row['pff_date']);
	$pff_updated = sed_build_date($cfg['dateformat'], $row['pff_updated']);
	$pff_title = $row['pff_title'];
	$pff_desc = $row['pff_desc'];
	$pff_type = $row['pff_type'];
	$pff_count = $row['pff_count'];
	$title = sed_cc($pff_title);
} else {
	sed_die();
}

if ($a == 'update' && !empty($f)) {
	$rtitle = sed_import('rtitle', 'P', 'TXT');
	$rdesc = sed_import('rdesc', 'P', 'HTM');
	$folderid = sed_import('folderid', 'P', 'INT');
	$rtype = sed_import('rtype', 'P', 'INT');
	$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f' ");
	sed_die(sed_sql_numrows($sql) == 0);
	$rtype = ($rtype == 2 && !$usr['auth_write_gal']) ? 1 : $rtype;

	$sql = sed_sql_query("UPDATE $db_pfs_folders SET
		pff_title='" . sed_sql_prep($rtitle) . "',
		pff_updated='" . $sys['now'] . "',
		pff_desc='" . sed_sql_prep($rdesc) . "',
		pff_type='$rtype'
		WHERE pff_userid='$userid' AND pff_id='$f' ");

	sed_redirect(sed_url("pfs", $more, "", true));
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

$rtype_arr = ($usr['auth_write_gal']) ? array(0 => $L['Private'], 1 => $L['Public'], 2 => $L['Gallery']) : array(0 => $L['Private'], 1 => $L['Public']);

$t->assign(array(
	"PFS_EDITFOLDER_SEND" => sed_url("pfs", "m=editfolder&a=update&f=" . $pff_id . "&" . $more),
	"PFS_EDITFOLDER_TITLE" => sed_textbox('rtitle', $pff_title, 56, 255),
	"PFS_EDITFOLDER_DESC" => sed_textarea('rdesc', $pff_desc, 8, 56, 'Micro'),
	"PFS_EDITFOLDER_DATE" => $pff_date,
	"PFS_EDITFOLDER_UPDATE" => $pff_updated,
	"PFS_EDITFOLDER_TYPE" => sed_radiobox("rtype", $rtype_arr, $pff_type)
));

$t->parse("MAIN.PFS_EDITFOLDER");


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
