<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=gallery.browse.inc
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_GALLERY')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['auth_read']);

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$sql_pff = sed_sql_query("SELECT f.*, u.user_name FROM $db_pfs_folders f
LEFT JOIN $db_users AS u ON f.pff_userid=u.user_id
WHERE pff_id='$f' AND pff_type=2");
sed_die(sed_sql_numrows($sql_pff) == 0);
$pff = sed_sql_fetchassoc($sql_pff);

$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_folderid='$f' ORDER BY pfs_id ASC");
$nbitems = sed_sql_numrows($sql_pff);
$pff['pff_title'] = sed_cc($pff['pff_title']);
$userid = $pff['pff_userid'];

$sql2 = sed_sql_query("UPDATE $db_pfs_folders SET pff_count=pff_count+1 WHERE pff_id='" . $f . "' LIMIT 1");

$title = $pff['pff_title'];
$subtitle = '';

reset($sed_extensions);
foreach ($sed_extensions as $k => $line) {
	$icon[$line[0]] = "<img src=\"system/img/pfs/" . $line[2] . ".gif\" alt=\"" . $line[1] . "\" />";
	$icon[$line[0]] = "<img src=\"system/img/ext/" . $line[2] . ".svg\" alt=\"" . $line[1] . "\" width=\"16\" />";
}

$out['subtitle'] = $pff['pff_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('gallerytitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("gallery")] = $L['gallery_home_title'];
$urlpaths[sed_url("gallery", "f=" . $pff['pff_id'])] = $pff['pff_title'];

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/gallery.browse.tpl");

$t->assign(array(
	"GALLERY_BROWSE_ID" => $pff['pff_id'],
	"GALLERY_BROWSE_TITLE" => $title,
	"GALLERY_BROWSE_SUBTITLE" => $subtitle,
	"GALLERY_BROWSE_DESC" => $pff['pff_desc'],
	"GALLERY_BROWSE_COUNT" => $pff['pff_count'],
	"GALLERY_BROWSE_BREADCRUMBS" => sed_breadcrumbs($urlpaths)
));

$jj = 0;

while ($pfs = sed_sql_fetchassoc($sql)) {
	$jj++;
	$pfs['pfs_fullfile'] = $cfg['pfs_dir'] . $pfs['pfs_file'];
	$pfs['pfs_filesize'] = floor($pfs['pfs_size'] / 1024);

	if (($pfs['pfs_extension'] == 'jpg' || $pfs['pfs_extension'] == 'jpeg' || $pfs['pfs_extension'] == 'png') && $cfg['th_amode'] != 'Disabled') {
		if (!file_exists($cfg['th_dir'] . $pfs['pfs_file']) && file_exists($cfg['pfs_dir'] . $pfs['pfs_file'])) {			
			sed_image_process(
				$cfg['pfs_dir'] . $pfs['pfs_file'],   	// $source
				$cfg['th_dir'] . $pfs['pfs_file'],    	// $dest
				$cfg['th_x'],                 			// $width
				$cfg['th_y'],                 			// $height
				$cfg['th_keepratio'],         			// $keepratio
				'resize',                     			// $type
				$cfg['th_dimpriority'],       			// $dim_priority
				$cfg['th_jpeg_quality'],      			// $quality
				false,                        			// $set_watermark
				false                         			// $preserve_source
			);
		}
	}

	$pfs['popup'] = "<a href=\"javascript:sedjs.picture('" . sed_url("pfs", "m=view&v=" . $pfs['pfs_file']) . "', 200, 200)\">";

	if ($usr['isadmin']) {
		$pfs['admin'] = "<a href=\"" . sed_url("pfs", "m=edit&id=" . $pfs['pfs_id'] . "&userid=" . $userid) . "\">" . $out['ic_edit'] . "</a>";
	}

	$pfs['pfs_desc'] = sed_parse($pfs['pfs_desc']);

	$t->assign(array(
		"GALLERY_BROWSE_ROW_ID" => $pfs['pfs_id'],
		"GALLERY_BROWSE_ROW_VIEWURL" => sed_url("gallery", "id=" . $pfs['pfs_id']),
		"GALLERY_BROWSE_ROW_VIEWPOPUP" => $pfs['popup'],
		"GALLERY_BROWSE_ROW_FILE" => $pfs['pfs_file'],
		"GALLERY_BROWSE_ROW_FULLFILE" => $pfs['pfs_fullfile'],
		"GALLERY_BROWSE_ROW_THUMB" => $pfs['pfs_file'],
		"GALLERY_BROWSE_ROW_ICON" => $icon[$pfs['pfs_extension']],
		"GALLERY_BROWSE_ROW_TITLE" => sed_cc($pfs['pfs_title']),
		"GALLERY_BROWSE_ROW_DESC" => $pfs['pfs_desc'],
		"GALLERY_BROWSE_ROW_SHORTDESC" => sed_cutstring(strip_tags($pfs['pfs_desc']), 48),
		"GALLERY_BROWSE_ROW_DATE" => sed_build_date($cfg['dateformat'], $pfs['pfs_date']),
		"GALLERY_BROWSE_ROW_SIZE" => $pfs['pfs_filesize'] . $L['kb'],
		"GALLERY_BROWSE_ROW_COUNT" => $pfs['pfs_count'],
		"GALLERY_BROWSE_ROW_ADMIN" => isset($pfs['admin']) ? $pfs['admin'] : ''
	));

	if (!empty($pfs['pfs_desc'])) $t->parse("MAIN.GALLERY.ROW.GALLERY_BROWSE_ROW_DESC");

	$t->parse("MAIN.GALLERY.ROW");
}

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN.GALLERY");

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
