<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=gallery.details.inc
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

$comments = sed_import('comments', 'G', 'BOL');

reset($sed_extensions);
foreach ($sed_extensions as $k => $line) {
	$icon[$line[0]] = "<img src=\"system/img/pfs/" . $line[2] . ".gif\" alt=\"" . $line[1] . "\" />";
	$icon[$line[0]] = "<img src=\"system/img/ext/" . $line[2] . ".svg\" alt=\"" . $line[1] . "\" width=\"16\" />";
}

/* === Hook === */
$extp = sed_getextplugins('gallery.details.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */


$sql_pfs = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_id='$id' LIMIT 1");
sed_die(sed_sql_numrows($sql_pfs) == 0);
$pfs = sed_sql_fetchassoc($sql_pfs);
$f = $pfs['pfs_folderid'];

$sql_pff = sed_sql_query("SELECT f.*, u.user_name FROM $db_pfs_folders f
LEFT JOIN $db_users AS u ON f.pff_userid=u.user_id
WHERE pff_id='$f' AND pff_type=2");
sed_die(sed_sql_numrows($sql_pff) == 0);
$pff = sed_sql_fetchassoc($sql_pff);

$sql_pfsall = sed_sql_query("SELECT pfs_id FROM $db_pfs
WHERE pfs_folderid='$f' AND pfs_extension IN $gd_supported_sql ORDER BY pfs_id ASC");

$pos = 0;
$current = 0;

while ($row = sed_sql_fetchassoc($sql_pfsall)) {
	$pos++;
	$pfsall[$pos] = $row['pfs_id'];
	if ($row['pfs_id'] == $id) {
		$current = $pos;
	}
}
$total = count($pfsall);

$browse_list = '';

foreach ($pfsall as $j => $k) {
	$browse_list .= ($current == $j) ? "<strong>[" : '';
	$browse_list .= sed_link(sed_url("gallery", "id=" . $k), $j);
	$browse_list .= ($current == $j) ? "]</strong>" : '';
	$browse_list .= " &nbsp;";
}

$browse_prev = (isset($pfsall[$current - 1]) && $pfsall[$current - 1] > 0) ? sed_link(sed_url("gallery", "id=" . $pfsall[$current - 1]), $out['ic_gallery_prev']) : '';
$browse_next = (isset($pfsall[$current + 1]) && $pfsall[$current + 1] > 0) ? sed_link(sed_url("gallery", "id=" . $pfsall[$current + 1]), $out['ic_gallery_next']) : '';

$browse_back =  sed_link(sed_url("gallery", "f=" . $f), $out['ic_gallery_back']);

$pfs['pfs_fullfile'] = $cfg['pfs_dir'] . $pfs['pfs_file'];
$pfs['pfs_filesize'] = floor($pfs['pfs_size'] / 1024);
$pfs['pfs_imgsize'] = @getimagesize($pfs['pfs_fullfile']);
$pfs['pfs_imgsize_xy'] = $pfs['pfs_imgsize'][0] . 'x' . $pfs['pfs_imgsize'][1];
$pfs['pfs_img'] = "<img src=\"" . $cfg['pfs_dir'] . $pfs['pfs_file'] . "\" alt=\"\" />";

if ($pfs['pfs_imgsize'][0] > $cfg['gallery_imgmaxwidth']) {
	if (!file_exists($cfg['res_dir'] . $pfs['pfs_file'])) {
		sed_image_process(
			$pfs['pfs_fullfile'],                      		// $source
			$cfg['res_dir'] . $pfs['pfs_file'],             // $dest (overwrite source)
			$cfg['gallery_imgmaxwidth'],   					// $width
			0,                                              // $height (auto)
			$cfg['th_keepratio'],                           // $keepratio (only if resizing)
			'resize',                                       // $type
			'Width',                                        // $dim_priority
			$cfg['gallery_logojpegqual'],                   // $quality
			false,                   						// $set_watermark
			false                                           // $preserve_source
		);
	}

	if (file_exists($cfg['res_dir'] . $pfs['pfs_file'])) {
		$pfs['pfs_img'] = sed_link("javascript:sedjs.picture('" . sed_url("pfs", "m=view&v=" . $pfs['pfs_file']) . "', 200, 200)", "<img src=\"" . $cfg['res_dir'] . $pfs['pfs_file'] . "\" alt=\"\" />");
		$browse_zoom = sed_link("javascript:sedjs.picture('" . sed_url("pfs", "m=view&v=" . $pfs['pfs_file']) . "', 200, 200)", $out['ic_gallery_zoom']);
	}
}

$item_code = 'g' . $pfs['pfs_id'];

$url_gallery = array('part' => 'gallery', 'params' => "id=" . $pfs['pfs_id']);

list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_gallery, $comments);
$pfs['pfs_urlcom'] = sed_url("gallery", "id=" . $pfs['pfs_id'] . "&comments=1");

$sql2 = sed_sql_query("UPDATE $db_pfs SET pfs_count=pfs_count+1 WHERE pfs_id='" . $id . "' LIMIT 1");

$title = $pfs['pfs_title'];
$subtitle = '';

$out['subtitle'] = $pfs['pfs_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('gallerytitle', $title_tags, $title_data);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("gallery")] = $L['gallery_home_title'];
$urlpaths[sed_url("gallery", "f=" . $pff['pff_id'])] = $pff['pff_title'];
$urlpaths[sed_url("gallery", "id=" . $id)] = $pfs['pfs_title'];

/* === Hook === */
$extp = sed_getextplugins('gallery.details.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/" . $skin . "/gallery.details.tpl");

$pfs['pfs_desc'] = sed_parse($pfs['pfs_desc']);

if ($usr['isadmin']) {
	$pfs['admin'] = sed_link(sed_url("pfs", "m=edit&id=" . $pfs['pfs_id'] . "&userid=" . $pfs['pfs_userid']), $out['ic_edit']);
	$pfs['admin'] .= " &nbsp; " . sed_link(sed_url("pfs", "a=setsample&id=" . $pfs['pfs_id'] . "&f=" . $pff['pff_id'] . "&" . sed_xg()), $out['ic_set'], array('title' => $L['pfs_setassample']));
	$t->assign(array(
		"GALLERY_DETAILS_ADMIN" => $pfs['admin']
	));
	$t->parse("MAIN.GALLERY_DETAILS_ADMIN");
}

$t->assign(array(
	"GALLERY_DETAILS_ID" => $pfs['pfs_id'],
	"GALLERY_DETAILS_VIEWURL" => sed_url("gallery", "id=" . $pfs['pfs_id']),
	"GALLERY_DETAILS_FILE" => $pfs['pfs_file'],
	"GALLERY_DETAILS_FULLFILE" => $pfs['pfs_fullfile'],
	"GALLERY_DETAILS_THUMB" => $pfs['pfs_file'],
	"GALLERY_DETAILS_IMG" => $pfs['pfs_img'],
	"GALLERY_DETAILS_ICON" => $icon[$pfs['pfs_extension']],
	"GALLERY_DETAILS_TITLE" => $title,
	"GALLERY_DETAILS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"GALLERY_DETAILS_SUBTITLE" => $subtitle,
	"GALLERY_DETAILS_SHORTTITLE" => sed_cc($pfs['pfs_title']),
	"GALLERY_DETAILS_DESC" => $pfs['pfs_desc'],
	"GALLERY_DETAILS_SHORTDESC" => sed_cutstring(strip_tags($pfs['pfs_desc']), 48),
	"GALLERY_DETAILS_DATE" => sed_build_date($cfg['dateformat'], $pfs['pfs_date']),
	"GALLERY_DETAILS_SIZE" => $pfs['pfs_filesize'] . $L['kb'],
	"GALLERY_DETAILS_ROW_DIMX" => $pfs['pfs_imgsize'][0],
	"GALLERY_DETAILS_ROW_DIMY" => $pfs['pfs_imgsize'][1],
	"GALLERY_DETAILS_ROW_DIMXY" => $pfs['pfs_imgsize_xy'],
	"GALLERY_DETAILS_COUNT" => $pfs['pfs_count'],
	"GALLERY_DETAILS_BROWSER" => $browse_list,
	"GALLERY_DETAILS_PREV" => $browse_prev,
	"GALLERY_DETAILS_NEXT" => $browse_next,
	"GALLERY_DETAILS_BACK" => $browse_back,
	"GALLERY_DETAILS_ZOOM" => $browse_zoom,
	"GALLERY_DETAILS_COMMENTS" => $comments_link,
	"GALLERY_DETAILS_COMMENTS_DISPLAY" => $comments_display,
	"GALLERY_DETAILS_COMMENTS_ISSHOW" => ($comments) ? " active" : "",
	"GALLERY_DETAILS_COMMENTS_JUMP" => ($comments) ? "<span class=\"spoiler-jump\"></span>" : "",
	"GALLERY_DETAILS_COMMENTS_COUNT" => $comments_count,
	"GALLERY_DETAILS_COMMENTS_URL" => $pfs['pfs_urlcom']
));

if (!empty($pfs['pfs_desc'])) $t->parse("MAIN.GALLERY_DETAILS_DESC");

/* === Hook === */
$extp = sed_getextplugins('gallery.details.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
