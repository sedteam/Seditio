<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.gallery.details.tags.php
Version=185
Type=Plugin
Description=Comments block for gallery (variant B, gallery.details.tags)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.gallery.details.tags
Hooks=gallery.details.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$comments = sed_import('comments', 'G', 'BOL');
$item_code = 'g' . $pfs['pfs_id'];
$url_gallery = array('part' => 'gallery', 'params' => "id=" . $pfs['pfs_id']);

list($comments_link, $comments_display, $comments_count) = sed_build_comments($item_code, $url_gallery, $comments, true);

$pfs['pfs_urlcom'] = sed_url("gallery", "id=" . $pfs['pfs_id'] . "&comments=1");

if (!empty($comments_link)) {
	$t->assign(array(
		"GALLERY_DETAILS_COMMENTS" => $comments_link,
		"GALLERY_DETAILS_COMMENTS_DISPLAY" => $comments_display,
		"GALLERY_DETAILS_COMMENTS_ISSHOW" => ($comments) ? " active" : "",
		"GALLERY_DETAILS_COMMENTS_JUMP" => ($comments) ? "<span class=\"spoiler-jump\"></span>" : "",
		"GALLERY_DETAILS_COMMENTS_COUNT" => $comments_count,
		"GALLERY_DETAILS_COMMENTS_URL" => $pfs['pfs_urlcom']
	));
	$t->parse("MAIN.GALLERY_DETAILS_COMMENTS");
}
