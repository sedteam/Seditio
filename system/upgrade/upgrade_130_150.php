<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=upgrade_130_150.php
Version=180
Updated=2025-jan-25
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
  die('Wrong URL.');
}

// -----------------

$adminmain .= "-----------------------<br />";


$adminmain .= "Adding the column to store the 'displayed' thumbnail in the gallery<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs_folders ADD pff_sample INT(11) NOT NULL DEFAULT '0' AFTER pff_isgallery";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$adminmain .= "-----------------------<br />";

$adminmain .= "Adding the Gallery into the core<br />";
$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "core (ct_code, ct_title, ct_version, ct_state, ct_lock) VALUES ('gallery', 'Gallery', '150', '1', '0')";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '01', 'disable_gallery', '3', '0', '' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '10', 'gallery_gcol', '2', '5', '' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '11', 'gallery_bcol', '2', '6', '' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '12', 'gallery_imgmaxwidth', '2', '600', '' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '20', 'gallery_logofile', '1', '', '' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '21', 'gallery_logopos', '2', 'Bottom left', 'Top left,Top right,Bottom left,Bottom right' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '22', 'gallery_logotrsp', '2', '50', '0,5,10,15,20,30,40,50,60,70,80,90,95,100' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);

$sqlqr = "INSERT INTO " . $cfg['sqldbprefix'] . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default)
VALUES ('core', 'gallery', '23', 'gallery_logojpegqual', '2', '90', '0,5,10,20,30,40,50,60,70,80,90,95,100' )";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$adminmain .= "-----------------------<br />";

$adminmain .= "Adding the rights for the gallery";
$sqlqr = "SELECT grp_id FROM " . $cfg['sqldbprefix'] . "groups WHERE 1 order by grp_id ASC";
$sql = sed_sql_query($sqlqr);

while ($row = sed_sql_fetchassoc($sql)) {
  if ($row['grp_id'] == 1 || $row['grp_id'] == 2) {
    $val_rights = 1;
    $val_lock = 254;
  } elseif ($row['grp_id'] == 3) {
    $val_rights = 0;
    $val_lock = 255;
  } elseif ($row['grp_id'] == 5) {
    $val_rights = 255;
    $val_lock = 255;
  } elseif ($row['grp_id'] == 4) {
    $val_rights = 1;
    $val_lock = 128;
  } else {
    $val_rights = 1;
    $val_lock = 0;
  }

  $sqlqr2 = "INSERT INTO " . $cfg['sqldbprefix'] . "auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES ('" . $row['grp_id'] . "', 'gallery', 'a', '" . $val_rights . "', '" . $val_lock . "', 1)";
  $adminmain .= sed_cc($sqlqr2) . "<br />";
  $sql2 = sed_sql_query($sqlqr2);
}
sed_auth_reorder();
sed_auth_clear('all');
$adminmain .= "-----------------------<br />";

$adminmain .= "Changing the PFS description to TEXT type<br />";
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs CHANGE pfs_desc pfs_desc TEXT NOT NULL";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$adminmain .= "-----------------------<br />";

$adminmain .= "Updating the PFS folders<br />";
$sqlqr = "UPDATE " . $cfg['sqldbprefix'] . "pfs_folders SET pff_isgallery=pff_isgallery+pff_ispublic WHERE 1";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs_folders DROP pff_ispublic";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$sqlqr = "ALTER TABLE " . $cfg['sqldbprefix'] . "pfs_folders CHANGE pff_isgallery pff_type TINYINT(1) NOT NULL DEFAULT '0'";
$adminmain .= sed_cc($sqlqr) . "<br />";
$sql = sed_sql_query($sqlqr);
$adminmain .= "-----------------------<br />";

// $adminmain .= "Deprecating the folder storage mode for the PFS<br />";
// $sqlqr = "DELETE FROM ".$cfg['sqldbprefix']."config  WHERE config_name='pfsuserfolder'";
// $adminmain .= sed_cc($sqlqr)."<br />";
// $sql = sed_sql_query($sqlqr);
// $adminmain .= "-----------------------<br />";

$adminmain .= "Changing the SQL version number to 150...<br />";
$sql = sed_sql_query("UPDATE " . $cfg['sqldbprefix'] . "stats SET stat_value=150 WHERE stat_name='version'");
$adminmain .= "-----------------------<br />";

$upg_status = TRUE;
