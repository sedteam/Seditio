<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=pfs.inc.php
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
list($usr['auth_read_gal'], $usr['auth_write_gal'], $usr['isadmin_gal']) = sed_auth('gallery', 'a');
sed_block($usr['auth_read']);

$id = sed_import('id', 'G', 'TXT');
$o = sed_import('o', 'G', 'TXT');
$f = sed_import('f', 'G', 'INT');
$v = sed_import('v', 'G', 'TXT');
$c1 = sed_import('c1', 'G', 'TXT');
$c2 = sed_import('c2', 'G', 'TXT');
$userid = sed_import('userid', 'G', 'INT');

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

$files_count = 0;
$folders_count = 0;
$standalone = FALSE;
$upload_status = array();
$user_info = sed_userinfo($userid);
$maingroup = ($userid == 0) ? 5 : $user_info['user_maingrp'];

$moretitle = ($userid > 0 && $useradm) ? " «" . $user_info['user_name'] . "»" : "";

$sql = sed_sql_query("SELECT grp_pfs_maxfile, grp_pfs_maxtotal FROM $db_groups WHERE grp_id='$maingroup'");
if ($row = sed_sql_fetchassoc($sql)) {
    $maxfile = $row['grp_pfs_maxfile'];
    $maxtotal = $row['grp_pfs_maxtotal'];
} else {
    sed_die();
}

if (($maxfile == 0 || $maxtotal == 0) && !$usr['isadmin']) {
    sed_block(FALSE);
}

if (!empty($c1) || !empty($c2)) {
    $more = "c1=" . $c1 . "&c2=" . $c2 . "&" . $more;
    $standalone = TRUE;
}

reset($sed_extensions);
foreach ($sed_extensions as $k => $line) {
    $icon[$line[0]] = "<img src=\"system/img/pfs/" . $line[2] . ".gif\" alt=\"" . $line[1] . "\" />";
    $icon[$line[0]] = "<img src=\"system/img/ext/" . $line[2] . ".svg\" alt=\"" . $line[1] . "\" width=\"16\" />";
    $filedesc[$line[0]] = $line[1];
}

$L['pfs_title'] = ($userid == 0) ? $L['SFS'] : $L['pfs_title'];
$title = sed_link(sed_url("pfs", $more), $L['pfs_title']);
$shorttitle = $L['pfs_title'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pfs", $more)] = $L['pfs_title'] . $moretitle;

if ($userid != $usr['id']) {
    sed_block($usr['isadmin']);
    $title .= ($userid == 0) ? '' : " (" . sed_build_user($user_info['user_id'], $user_info['user_name']) . ")";
    $urlpaths[sed_url("users", "m=details&id=" . $user_info['user_id'])] = $user_info['user_name'];
    $shorttitle = $user_info['user_name'];
}

/* === Hook === */
$extp = sed_getextplugins('pfs.first');
if (is_array($extp)) {
    foreach ($extp as $k => $pl) {
        include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
    }
}
/* ===== */

$u_totalsize = 0;
$sql = sed_sql_query("SELECT SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' ");
$pfs_totalsize = sed_sql_result($sql, 0, "SUM(pfs_size)");

if ($a == 'upload') {
    sed_block($usr['auth_write']);
    $folderid = sed_import('folderid', 'P', 'INT');
    $ntitle = sed_import('ntitle', 'P', 'ARR');
    $nresize = sed_import('nresize', 'P', 'BOL');
    $naddlogo = sed_import('naddlogo', 'P', 'BOL');
    $naddlogo = ($naddlogo) ? 1 : 0;
    $nresize = ($nresize) ? 1 : 0;

    /* === Hook === */
    $extp = sed_getextplugins('pfs.upload.first');
    if (is_array($extp)) {
        foreach ($extp as $k => $pl) {
            include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
        }
    }
    /* ===== */

    if ($folderid != 0) {
        $sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$folderid' ");
        sed_die(sed_sql_numrows($sql) == 0);
    }

    $count_userfile = isset($_FILES['userfile']['name']) ? count($_FILES['userfile']['name']) : 0;

    for ($ii = 0; $ii < $count_userfile; $ii++) {
        $u_tmp_name = $_FILES['userfile']['tmp_name'][$ii];
        $u_type = $_FILES['userfile']['type'][$ii];
        $u_name = $_FILES['userfile']['name'][$ii];
        $u_size = $_FILES['userfile']['size'][$ii];
        $u_name  = str_replace("\'", '', $u_name);
        $u_name  = trim(str_replace("\"", '', $u_name));
        $upl_stats = '';

        if (!empty($u_name)) {
            $upl_stats = $u_name . " : ";

            $u_title = isset($ntitle[$ii]) ? sed_import($ntitle[$ii], 'D', 'TXT') : "";
            $desc = '';

            $u_name = mb_strtolower($u_name);
            $dotpos = mb_strrpos($u_name, ".") + 1;
            $f_extension = mb_substr($u_name, $dotpos, 5);
            $f_extension_ok = 0;

            if ($cfg['pfs_filemask'] || file_exists($cfg['pfs_dir'] . $userid . "-" . $u_name)) {
                $u_name = sed_newname($userid . "-" . time() . sed_unique(3) . "-" . $u_name, TRUE);
            } else {
                $u_name = sed_newname($userid . "-" . $u_name, TRUE);
            }

            $u_sqlname = sed_sql_prep($u_name);

            if ($f_extension != 'php' && $f_extension != 'php3' && $f_extension != 'php4' && $f_extension != 'php5') {
                foreach ($sed_extensions as $k => $line) {
                    if (mb_strtolower($f_extension) == $line[0]) {
                        $f_extension_ok = 1;
                    }
                }
            }

            if (is_uploaded_file($u_tmp_name) && $u_size > 0 && $u_size < ($maxfile * 1024) && $f_extension_ok && ($pfs_totalsize + $u_size) < $maxtotal * 1024) {
                if (!file_exists($cfg['pfs_dir'] . $u_name)) {
                    move_uploaded_file($u_tmp_name, $cfg['pfs_dir'] . $u_name);
                    @chmod($cfg['pfs_dir'] . $u_name, 0766);

                    /* === Hook === */
                    $extp = sed_getextplugins('pfs.upload.moved');
                    if (is_array($extp)) {
                        foreach ($extp as $k => $pl) {
                            include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
                        }
                    }
                    /* ===== */

                    // Combined resize and watermark processing
                    if (($nresize || $naddlogo) && in_array($f_extension, $cfg['gd_supported'])) {
                        $do_resize = $nresize && $cfg['gallery_imgmaxwidth'] > 0;
                        $do_watermark = $naddlogo && !empty($cfg['gallery_logofile']) && @file_exists($cfg['gallery_logofile']);

                        sed_image_process(
                            $cfg['pfs_dir'] . $u_name,                      // $source
                            $cfg['pfs_dir'] . $u_name,                      // $dest (overwrite source)
                            $do_resize ? $cfg['gallery_imgmaxwidth'] : 0,   // $width
                            0,                                              // $height (auto)
                            $do_resize,                                     // $keepratio (only if resizing)
                            'resize',                                       // $type
                            'Width',                                        // $dim_priority
                            $cfg['gallery_logojpegqual'],                   // $quality
                            $do_watermark ? true : false,                   // $set_watermark
                            true                                            // $preserve_source
                        );
                    }

                    $u_size = filesize($cfg['pfs_dir'] . $u_name);

                    $sql = sed_sql_query("INSERT INTO $db_pfs
                        (pfs_userid,
                        pfs_date,
                        pfs_file,
                        pfs_extension,
                        pfs_folderid,
                        pfs_title,
                        pfs_desc,
                        pfs_size,
                        pfs_count)
                        VALUES
                        (" . (int)$userid . ",
                        " . (int)$sys['now_offset'] . ",
                        '" . sed_sql_prep($u_sqlname) . "',
                        '" . sed_sql_prep($f_extension) . "',
                        " . (int)$folderid . ",
                        '" . sed_sql_prep($u_title) . "',
                        '" . sed_sql_prep($desc) . "',
                        " . (int)$u_size . ",
                        0) ");

                    $sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_updated='" . $sys['now'] . "' WHERE pff_id='$folderid'");
                    $upl_stats .= $L['Yes'];
                    $pfs_totalsize += $u_size;

                    /* === Hook === */
                    $extp = sed_getextplugins('pfs.upload.done');
                    if (is_array($extp)) {
                        foreach ($extp as $k => $pl) {
                            include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
                        }
                    }
                    /* ===== */

                    // Thumbnail creation
                    if (in_array($f_extension, $cfg['gd_supported']) && $cfg['th_amode'] != 'Disabled' && file_exists($cfg['pfs_dir'] . $u_name)) {
                        @unlink($cfg['th_dir'] . $u_name);
                        sed_image_process(
                            $cfg['pfs_dir'] . $u_name,    // $source
                            $cfg['th_dir'] . $u_name,     // $dest
                            $cfg['th_x'],                 // $width
                            $cfg['th_y'],                 // $height
                            $cfg['th_keepratio'],         // $keepratio
                            'resize',                     // $type
                            $cfg['th_dimpriority'],       // $dim_priority
                            $cfg['th_jpeg_quality'],      // $quality
                            false,                        // $set_watermark
                            false                         // $preserve_source
                        );
                    }
                } else {
                    $upl_stats .= $L['pfs_fileexists'];
                }
            } else {
                $upl_stats .= $L['pfs_filetoobigorext'];
            }
        }
        if (!empty($upl_stats)) $upload_status[] = $upl_stats;
    }
} elseif ($a == 'delete') {
    sed_block($usr['auth_write']);
    sed_check_xg();
    $sql = sed_sql_query("SELECT pfs_file, pfs_folderid FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$id' LIMIT 1");

    if ($row = sed_sql_fetchassoc($sql)) {
        $pfs_file = $row['pfs_file'];
        $f = $row['pfs_folderid'];
        $ff = $cfg['pfs_dir'] . $pfs_file;

        if (file_exists($ff) && (mb_substr($pfs_file, 0, mb_strpos($pfs_file, "-")) == $userid || $usr['isadmin'])) {
            @unlink($ff);
            if (file_exists($cfg['th_dir'] . $pfs_file)) {
                @unlink($cfg['th_dir'] . $pfs_file);
            }
            // Resizer unlink
            $pfs_filename = substr($pfs_file, 0, strrpos($pfs_file, "."));
            array_map("unlink", glob($cfg['res_dir'] . $pfs_filename . ".crop*x*"));
            array_map("unlink", glob($cfg['res_dir'] . $pfs_filename . ".resize*x*"));
        }
        $sql = sed_sql_query("DELETE FROM $db_pfs WHERE pfs_id='$id'");
        sed_redirect(sed_url("pfs", "f=" . $f . "&o=" . $o . "&" . $more, "", true));
        exit;
    } else {
        sed_die();
    }
} elseif ($a == 'newfolder') {
    sed_block($usr['auth_write']);
    $ntitle = sed_import('ntitle', 'P', 'TXT');
    $ndesc = sed_import('ndesc', 'P', 'TXT');
    $ntype = sed_import('ntype', 'P', 'INT');
    $ntitle = (empty($ntitle)) ? '???' : $ntitle;

    $sql = sed_sql_query("INSERT INTO $db_pfs_folders
        (pff_userid,
        pff_title,
        pff_date,
        pff_updated,
        pff_desc,
        pff_type,
        pff_count)
        VALUES
        (" . (int)$userid . ",
        '" . sed_sql_prep($ntitle) . "',
        " . (int)$sys['now'] . ",
        " . (int)$sys['now'] . ",
        '" . sed_sql_prep($ndesc) . "', 
        " . (int)$ntype . ",
        0)");

    sed_redirect(sed_url("pfs", $more, "", true));
    exit;
} elseif ($a == 'deletefolder') {
    sed_block($usr['auth_write']);
    sed_check_xg();

    $sql = sed_sql_query("SELECT COUNT(*) FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid='$f'");
    $files_count = sed_sql_result($sql, 0, "COUNT(*)");
    if ($files_count == 0) {
        $sql = sed_sql_query("DELETE FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f'");
        $sql = sed_sql_query("UPDATE $db_pfs SET pfs_folderid=0 WHERE pfs_userid='$userid' AND pfs_folderid='$f'");
    }

    sed_redirect(sed_url("pfs", $more, "", true));
    exit;
} elseif ($a == 'setsample') {
    sed_block($usr['auth_write']);
    sed_check_xg();
    $id = sed_import('id', 'G', 'INT');
    $sql = sed_sql_query("UPDATE $db_pfs_folders SET pff_sample='$id' WHERE pff_id='$f' AND pff_userid='$userid'");
    sed_redirect(sed_url("pfs", "f=" . $f . "&" . $more, "", true));
    exit;
}

$f = (empty($f)) ? '0' : $f;

$subtitle = '';
$out['subtitle'] = $L['Mypfs'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

if ($standalone) {
    sed_sendheaders();

    sed_add_javascript('system/javascript/core.js', true);
    sed_add_javascript($morejavascript);
    sed_add_css($morecss);

    /* === Hook === */
    $extp = sed_getextplugins('pfs.stndl');
    if (is_array($extp)) {
        foreach ($extp as $k => $pl) {
            include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
        }
    }
    /* ===== */

    $pfs_header1 = $cfg['doctype'] . "\n<html>\n<head>
    <title>" . $cfg['maintitle'] . "</title>" . sed_htmlmetas() . $moremetas . sed_css();
    $pfs_header2 = "</head>\n<body>";
    $pfs_footer1 = sed_javascript();
    $pfs_footer2 = "</body>\n</html>";

    $mskin = sed_skinfile(array('pfs', 'standalone'));
    $t = new XTemplate($mskin);

    $t->assign(array(
        "PFS_STANDALONE_HEADER1" => $pfs_header1,
        "PFS_STANDALONE_HEADER2" => $pfs_header2,
        "PFS_STANDALONE_FOOTER1" => $pfs_footer1,
        "PFS_STANDALONE_FOOTER2" => $pfs_footer2
    ));

    $t->parse("MAIN.PFS_STANDALONE_HEADER");
    $t->parse("MAIN.PFS_STANDALONE_FOOTER");
} else {
    require(SED_ROOT . "/system/header.php");
    $mskin = sed_skinfile('pfs');
    $t = new XTemplate($mskin);
}

if ($f > 0) {
    $sql1 = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_id='$f' AND pff_userid='$userid'");
    if ($row1 = sed_sql_fetchassoc($sql1)) {
        $pff_id = $row1['pff_id'];
        $pff_title = $row1['pff_title'];
        $pff_updated = $row1['pff_updated'];
        $pff_desc = $row1['pff_desc'];
        $pff_type = $row1['pff_type'];
        $pff_count = $row1['pff_count'];
        $pff_sample = $row1['pff_sample'];

        $sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid='$f' ORDER BY pfs_date DESC");
        $title .= " " . $cfg['separator'] . " " . sed_link(sed_url("pfs", "f=" . $pff_id . "&" . $more), $pff_title);
        $shorttitle = $pff_title;
        $urlpaths[sed_url("pfs", "f=" . $pff_id . "&" . $more)] = $pff_title;
    } else {
        sed_die();
    }
    $movebox = sed_selectbox_folders($userid, "", $f);
} else {
    $sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_folderid=0 ORDER BY pfs_date DESC");
    $sql1 = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_userid='$userid' ORDER BY pff_type DESC, pff_title ASC");
    $sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_pfs WHERE pfs_folderid>0 AND pfs_userid='$userid'");
    $sql3 = sed_sql_query("SELECT pfs_folderid, COUNT(*), SUM(pfs_size) FROM $db_pfs WHERE pfs_userid='$userid' GROUP BY pfs_folderid");

    while ($row3 = sed_sql_fetchassoc($sql3)) {
        $pff_filescount[$row3['pfs_folderid']] = $row3['COUNT(*)'];
        $pff_filessize[$row3['pfs_folderid']] = $row3['SUM(pfs_size)'];
    }

    $folders_count = sed_sql_numrows($sql1);
    $subfiles_count = sed_sql_result($sql2, 0, "COUNT(*)");
    $movebox = sed_selectbox_folders($userid, "/", "");

    while ($row1 = sed_sql_fetchassoc($sql1)) {
        $pff_id = $row1['pff_id'];
        $pff_title = $row1['pff_title'];
        $pff_updated = $row1['pff_updated'];
        $pff_desc = $row1['pff_desc'];
        $pff_type = $row1['pff_type'];
        $pff_count = $row1['pff_count'];
        $pff_fcount = isset($pff_filescount[$pff_id]) ? $pff_filescount[$pff_id] : 0;
        $pff_fsize = isset($pff_filessize[$pff_id]) ? floor($pff_filessize[$pff_id] / 1024) : 0;
        $pff_fcount = (empty($pff_fcount)) ? "0" : $pff_fcount;
        $pff_fssize = (empty($pff_fsize)) ? "0" : $pff_fsize;

        if ($pff_type == 2) {
            $icon_f = $out['ic_gallery'];
        } else {
            $icon_f = $out['ic_folder'];
        }

        if ($pff_type == 2 && !$cfg['disable_gallery']) {
            $icon_g = sed_link(sed_url("gallery", "f=" . $pff_id), $out['ic_jumpto']);
        } else {
            $icon_g = '';
        }

        if ($pff_fcount == 0) {
            $t->assign(array(
                "PFS_LIST_FOLDERS_DELETE_URL" => sed_url("pfs", "a=deletefolder&" . sed_xg() . "&f=" . $pff_id . "&" . $more)
            ));
            $t->parse("MAIN.PFS_FOLDERS.PFS_LIST_FOLDERS.PFS_LIST_FOLDERS_DELETE_URL");
        }

        $t->assign(array(
            "PFS_LIST_FOLDERS_ID" => $pff_id,
            "PFS_LIST_FOLDERS_URL" => sed_url("pfs", "f=" . $pff_id . "&" . $more),
            "PFS_LIST_FOLDERS_TITLE" => $pff_title,
            "PFS_LIST_FOLDERS_EDIT_URL" => sed_url("pfs", "m=editfolder&f=" . $pff_id . "&" . $more),
            "PFS_LIST_FOLDERS_TYPE" => $icon_f . " " . $L_pff_type[$pff_type] . " " . $icon_g,
            "PFS_LIST_FOLDERS_HITS" => $pff_fcount,
            "PFS_LIST_FOLDERS_SIZE" => $pff_fsize . " " . $L['kb'],
            "PFS_LIST_FOLDERS_UPDATE" => sed_build_date($cfg['dateformat'], $row1['pff_updated']),
            "PFS_LIST_FOLDERS_VIEWCOUNTS" => $pff_count
        ));

        $t->parse("MAIN.PFS_FOLDERS.PFS_LIST_FOLDERS");
    }

    if ($folders_count > 0) {
        $t->assign(array(
            "PFS_FOLDERS_COUNT" => $folders_count,
            "PFS_FOLDERS_SUBFILES_COUNT" => $subfiles_count
        ));
        $t->parse("MAIN.PFS_FOLDERS");
    }
}

$files_count = sed_sql_numrows($sql);
$movebox = (empty($f)) ? sed_selectbox_folders($userid, "/", "") : sed_selectbox_folders($userid, "$f", "");
$pfs_foldersize = 0;
$stndl_flag = false;

while ($row = sed_sql_fetchassoc($sql)) {
    $pfs_id = $row['pfs_id'];
    $pfs_file = $row['pfs_file'];
    $pfs_date = $row['pfs_date'];
    $pfs_extension = $row['pfs_extension'];
    $pfs_desc = $row['pfs_desc'];
    $pfs_title = $row['pfs_title'];
    $pfs_fullfile = $cfg['pfs_dir'] . $pfs_file;
    $pfs_filesize = floor($row['pfs_size'] / 1024);
    $pfs_icon = $icon[$pfs_extension];

    $dotpos = mb_strrpos($pfs_file, ".") + 1;
    $pfs_realext = mb_strtolower(mb_substr($pfs_file, $dotpos, 5));

    $add_thumbnail = '';
    $add_image = '';
    $add_file = '';
    $add_video = '';

    if ($pfs_extension != $pfs_realext) {
        $sql1 = sed_sql_query("UPDATE $db_pfs SET pfs_extension='$pfs_realext' WHERE pfs_id='$pfs_id' ");
        $pfs_extension = $pfs_realext;
    }

    $setassample = "";

    if (in_array($pfs_extension, $cfg['gd_supported']) && $cfg['th_amode'] != 'Disabled') {
        $setassample = (isset($pff_sample) && $pfs_id == $pff_sample) ?
            "<span class=\"dsl-icon\">" . $out['ic_checked'] . "</span>" :
            sed_link(sed_url("pfs", "a=setsample&id=" . $pfs_id . "&f=" . $f . "&" . sed_xg() . "&" . $more), $out['ic_set'], array('title' => $L['pfs_setassample'], 'class' => 'btn-icon'));
        $pfs_icon = sed_link($pfs_fullfile, "<img src=\"" . $cfg['th_dir'] . $pfs_file . "\" alt=\"" . $pfs_file . "\">", array('rel' => $cfg['th_rel']));

        // Generate thumbnail if it doesn't exist
        if (!file_exists($cfg['th_dir'] . $pfs_file) && file_exists($cfg['pfs_dir'] . $pfs_file)) {
            sed_image_process(
                $cfg['pfs_dir'] . $pfs_file,    // $source
                $cfg['th_dir'] . $pfs_file,     // $dest
                $cfg['th_x'],                   // $width
                $cfg['th_y'],                   // $height
                $cfg['th_keepratio'],           // $keepratio
                'resize',                       // $type
                $cfg['th_dimpriority'],         // $dim_priority
                $cfg['th_jpeg_quality'],        // $quality
                null,                           // $watermark
                null,                           // $watermark_position
                null,                           // $watermark_opacity
                false                           // $preserve_source
            );
        }

        if ($standalone) {
            $add_thumbnail .= sed_link("javascript:addthumb('" . $cfg['th_dir'] . $pfs_file . "', '" . $pfs_file . "')",  $out['ic_pastethumb'], array('title' =>  $L['pfs_insertasthumbnail'],  'class' => 'btn-icon'));
            $add_image = sed_link("javascript:addpix('" . $pfs_fullfile . "')", $out['ic_pasteimage'], array('title' => $L['pfs_insertasimage'],  'class' => 'btn-icon'));
        }
    } elseif (in_array($pfs_extension, $cfg['video_supported'])) {
        if ($standalone) {
            $add_video = sed_link("javascript:addvideo('" . $pfs_fullfile . "')", $out['ic_pastevideo'], array('title' => $L['pfs_insertasvideo'], 'class' => 'btn-icon'));
        }
    }

    $add_file = ($standalone) ? sed_link("javascript:addfile('" . $pfs_file . "','" . $pfs_fullfile . "')", $out['ic_pastefile'], array('title' => $L['pfs_insertaslink'], 'class' => 'btn-icon')) : '';

    if ((($c2 == "newpageurl") || ($c2 == "rpageurl")) && ($standalone)) {
        $add_file = sed_link("javascript:addfile_pageurl('" . $pfs_fullfile . "')", $out['ic_pastefile'], array('title' => $L['pfs_insertaslink'], 'class' => 'btn-icon'));
    }

    $stndl_icons_list = "";
    $stndl_icons_disp = "";

    /* === Hook === */
    $extp = sed_getextplugins('pfs.stndl.icons');
    if (is_array($extp)) {
        foreach ($extp as $k => $pl) {
            include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
        }
    }
    /* ===== */

    if (!empty($pfs_icon)) {
        $t->assign(array(
            "PFS_LIST_FILES_ICON" => $pfs_icon
        ));
        $t->parse("MAIN.PFS_FILES.PFS_LIST_FILES.PFS_LIST_FILES_ICON");
    }

    if (!empty($add_thumbnail) || !empty($add_image) || !empty($add_file) || !empty($add_video) || !empty($stndl_icons_list)) {
        $stndl_flag = true;
        $t->assign(array(
            "PFS_LIST_FILES_STNDL"  => (empty($stndl_icons_list)) ? $add_thumbnail . " " . $add_image . " " . $add_video . " " .  $add_file : $stndl_icons_list
        ));
        $t->parse("MAIN.PFS_FILES.PFS_LIST_FILES.PFS_LIST_FILES_STNDL");
    }

    $t->assign(array(
        "PFS_LIST_FILES_ID" => $pfs_id,
        "PFS_LIST_FILES_DELETE_URL" => sed_url("pfs", "a=delete&" . sed_xg() . "&id=" . $pfs_id . "&o=" . $o . "&" . $more),
        "PFS_LIST_FILES_FILE" => $pfs_file,
        "PFS_LIST_FILES_URL" => $pfs_fullfile,
        "PFS_LIST_FILES_TITLE" => $pfs_title,
        "PFS_LIST_FILES_EDIT_URL" => sed_url("pfs", "m=edit&id=" . $pfs_id . "&" . $more),
        "PFS_LIST_FILES_SIZE" => $pfs_filesize . " " . $L['kb'],
        "PFS_LIST_FILES_UPDATE" => sed_build_date($cfg['dateformat'], $pfs_date),
        "PFS_LIST_FILES_VIEWCOUNTS" => $row['pfs_count'],
        "PFS_LIST_FILES_SETASSAMPLE" => $setassample
    ));

    $t->parse("MAIN.PFS_FILES.PFS_LIST_FILES");

    $pfs_foldersize = $pfs_foldersize + $pfs_filesize;
}

if ($files_count > 0) {
    if ($stndl_flag) {
        $t->parse("MAIN.PFS_FILES.PFS_STNDL_HEAD");
    }

    $t->assign(array(
        "PFS_FILES_COUNT" => $files_count
    ));

    $t->parse("MAIN.PFS_FILES");
} else {
    $t->parse("MAIN.PFS_EMPTY");
}

// ========== Statistics =========

$pfs_precentbar = @floor(100 * $pfs_totalsize / 1024 / $maxtotal);
$disp_stats = $L['pfs_totalsize'] . " : " . floor($pfs_totalsize / 1024) . $L['kb'] . " / " . $maxtotal . $L['kb'];
$disp_stats .= " (" . @floor(100 * $pfs_totalsize / 1024 / $maxtotal) . "%) ";
$disp_stats .= " &nbsp; " . $L['pfs_maxsize'] . " : " . $maxfile . $L['kb'];
$disp_stats .= "<div style=\"width:300px; margin:6px 0 0 0;\"><div class=\"bar_back\">";
$disp_stats .= "<div class=\"bar_front\" style=\"width:" . $pfs_precentbar . "%;\"></div></div></div>";

// ========== Allowed =========

reset($sed_extensions);
sort($sed_extensions);
$disp_allowedlist = array();

foreach ($sed_extensions as $k => $line) {
    $disp_allowedlist[] = $icon[$line[0]] . " ." . $line[0] . " (" . $filedesc[$line[0]] . ")";
}

$disp_allowed = implode(", ", $disp_allowedlist);

// ========== Icons Help =========

$disp_iconshelp = "<span class=\"dsl-icon\">" . $out['ic_pastethumb'] . "</span> " . $L['pfs_insertasthumbnail'] . "   <span class=\"dsl-icon\">" . $out['ic_pasteimage'] . "</span> " . $L['pfs_insertasimage'] . "   <span class=\"dsl-icon\">" . $out['ic_pastefile'] . "</span> " . $L['pfs_insertaslink'];

if ($standalone) {
    $t->assign(array(
        "PFS_HELP" => $disp_iconshelp
    ));
    $t->parse("MAIN.PFS_HELP");
}

// ========== Upload =========

if ($usr['auth_write']) {
    $t->parse("MAIN.PFS_UPLOAD_TAB");

    if (!empty($cfg['gallery_logofile'])) {
        $t->assign(array(
            "PFS_UPLOAD_ADD_LOGO" => sed_checkbox('naddlogo'),
            "PFS_UPLOAD_LOGO_FILE" => "<img src=\"" . $cfg['gallery_logofile'] . "\" alt=\"\" />"
        ));
        $t->parse("MAIN.PFS_UPLOAD.PFS_UPLOAD_ADD_LOGO");
    }

    if ($cfg['gallery_imgmaxwidth'] > 0) {
        $t->assign(array(
            "PFS_UPLOAD_IMG_RESIZE_SIZE" => sprintf($L['pfs_resize'], $cfg['gallery_imgmaxwidth']),
            "PFS_UPLOAD_IMG_RESIZE" => sed_checkbox('nresize')
        ));
        $t->parse("MAIN.PFS_UPLOAD.PFS_UPLOAD_IMG_RESIZE");
    }

    $t->assign(array(
        "PFS_UPLOAD_SEND" => sed_url("pfs", "a=upload" . "&" . $more),
        "PFS_UPLOAD_MAXFILESIZE" => sed_textbox_hidden('MAX_FILE_SIZE', $maxfile * 1024),
        "PFS_UPLOAD_FOLDERS" => sed_selectbox_folders($userid, "", $f),
    ));

    for ($ii = 0; $ii < $cfg['pfsmaxuploads'] * 2; $ii++) {
        if ($ii + 1 == $cfg['pfsmaxuploads']) {
            $t->assign(array(
                "PFS_UPLOAD_MORE_URL" => "javascript:sedjs.toggleblock('moreuploads')",
                "PFS_UPLOAD_MORE_ICON" => $out['ic_arrow_down']
            ));
            $t->parse("MAIN.PFS_UPLOAD.PFS_UPLOAD_LIST.PFS_UPLOAD_MORE");
        }

        $t->assign(array(
            "PFS_UPLOAD_LIST_NUM" => ($ii + 1),
            "PFS_UPLOAD_LIST_TITLE" => sed_textbox("ntitle[" . $ii . "]", '', 38, 255),
            "PFS_UPLOAD_LIST_FILE" => "<input name=\"userfile[$ii]\" type=\"file\" class=\"file\" size=\"32\" />"
        ));

        if ($ii >= $cfg['pfsmaxuploads']) {
            $t->parse("MAIN.PFS_UPLOAD.PFS_UPLOAD_MORE_LIST");
        } else {
            $t->parse("MAIN.PFS_UPLOAD.PFS_UPLOAD_LIST");
        }
    }

    $t->assign(array(
        "PFS_UPLOAD_MULTIPLE" => "<input name=\"userfile[]\" type=\"file\" class=\"file\" multiple=\"true\" size=\"32\" />",
        "PFS_ALLOWED_EXT" => ($usr['auth_write']) ? $disp_allowed : ''
    ));

    $t->parse("MAIN.PFS_UPLOAD");
}

// ========== Create a new folder =========

if ($f == 0 && $usr['auth_write']) {
    $t->parse("MAIN.PFS_NEWFOLDER_TAB");

    $ntype_arr = ($usr['auth_write_gal']) ? array(0 => $L['Private'], 1 => $L['Public'], 2 => $L['Gallery']) : array(0 => $L['Private'], 1 => $L['Public']);

    $t->assign(array(
        "PFS_NEWFOLDER_SEND" => sed_url("pfs", "a=newfolder" . "&" . $more),
        "PFS_NEWFOLDER_TITLE" => sed_textbox('ntitle', '', 56, 255),
        "PFS_NEWFOLDER_DESC" => sed_textarea('ndesc', '', 8, 56, 'Micro'),
        "PFS_NEWFOLDER_TYPE" => sed_radiobox("ntype", $ntype_arr, 0)
    ));

    $t->parse("MAIN.PFS_NEWFOLDER");
}

if (count($upload_status) > 0) {
    $t->assign(array(
        "PFS_UPLOAD_STATUS" => sed_alert(implode('<br />', $upload_status), 'i')
    ));
    $t->parse("MAIN.PFS_UPLOAD_STATUS");
}

$t->assign(array(
    "PFS_TITLE" => $title,
    "PFS_SHORTTITLE" => $shorttitle,
    "PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths, 1, !$standalone),
    "PFS_SUBTITLE" => $subtitle,
    "PFS_STATS" => $disp_stats
));

/* === Hook === */
$extp = sed_getextplugins('pfs.tags');
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
