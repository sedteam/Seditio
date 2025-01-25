<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.gallery.inc.php
Version=180
Updated=2025-jan-25
Type=Core.admin
Author=Seditio Team
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
    die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=manage")] =  $L['adm_manage'];
$urlpaths[sed_url("admin", "m=gallery")] =  $L['Gallery'];

$admintitle = $L['Gallery'];

$t = new XTemplate(sed_skinfile('admin.gallery', false, true));

$t->parse("ADMIN_GALLERY");

$t->assign("ADMIN_GALLERY_TITLE", $admintitle);

$adminmain .= $t->text("ADMIN_GALLERY");
