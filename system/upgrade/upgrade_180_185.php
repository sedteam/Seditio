<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/upgrade/upgrade_180_185.php
Version=185
Updated=2026-feb-20
Type=Core.upgrade
Author=Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$adminmain .= "Clearing the internal SQL cache...<br />";
sed_sql_query("TRUNCATE TABLE $db_cache");

/* ======== Forum table alterations (only if tables exist) ======== */
$adminmain .= "Checking forum tables...<br />";
$chk_ft = @sed_sql_query("SHOW TABLES LIKE '$db_forum_topics'");
$chk_fs = @sed_sql_query("SHOW TABLES LIKE '$db_forum_sections'");
if ($chk_ft && sed_sql_numrows($chk_ft) > 0) {
	@sed_sql_query("ALTER TABLE $db_forum_topics MODIFY ft_title VARCHAR(255) NOT NULL DEFAULT ''");
	$adminmain .= "forum_topics: ft_title modified.<br />";
}
if ($chk_fs && sed_sql_numrows($chk_fs) > 0) {
	@sed_sql_query("ALTER TABLE $db_forum_sections MODIFY fs_title VARCHAR(255) NOT NULL DEFAULT ''");
	$adminmain .= "forum_sections: fs_title modified.<br />";
}

/* ======== Dic table alterations (only if table exists) ======== */
$adminmain .= "Checking dic table...<br />";
$chk_dic = @sed_sql_query("SHOW TABLES LIKE '$db_dic'");
if ($chk_dic && sed_sql_numrows($chk_dic) > 0) {
	@sed_sql_query("ALTER TABLE $db_dic ADD COLUMN dic_extra_default VARCHAR(255) NOT NULL DEFAULT ''");
	@sed_sql_query("ALTER TABLE $db_dic ADD COLUMN dic_extra_allownull TINYINT(1) NOT NULL DEFAULT 0");
	@sed_sql_query("ALTER TABLE $db_dic ADD COLUMN dic_extra_extra VARCHAR(255) NOT NULL DEFAULT ''");
	$adminmain .= "dic: extra columns added.<br />";
}

$adminmain .= "-----------------------<br />";

/* ======== Modular architecture: fill ct_path, ct_admin for core entries ======== */
$adminmain .= "Filling ct_path and ct_admin for existing core entries...<br />";
$core_paths = array(
	'admin'   => array('path' => 'system/core/admin/',   'admin' => 1),
	'forums'  => array('path' => 'modules/forums/',      'admin' => 1),
	'index'   => array('path' => 'system/core/index/',   'admin' => 0),
	'message' => array('path' => 'system/core/message/', 'admin' => 0),
	'page'    => array('path' => 'modules/page/',        'admin' => 1),
	'pfs'     => array('path' => 'modules/pfs/',         'admin' => 1),
	'plug'    => array('path' => 'system/core/plug/',    'admin' => 1),
	'pm'      => array('path' => 'modules/pm/',          'admin' => 1),
	'polls'   => array('path' => 'modules/polls/',       'admin' => 1),
	'users'   => array('path' => 'modules/users/',        'admin' => 1),
	'trash'   => array('path' => 'system/core/',         'admin' => 1),
	'gallery' => array('path' => 'modules/gallery/',     'admin' => 1),
	'dic'     => array('path' => 'system/core/',         'admin' => 1),
	'menu'    => array('path' => 'system/core/',         'admin' => 1),
);
foreach ($core_paths as $code => $data) {
	sed_sql_query("UPDATE $db_core SET ct_path='" . sed_sql_prep($data['path']) . "', ct_admin=" . (int)$data['admin'] . " WHERE ct_code='" . sed_sql_prep($code) . "'");
}

/* Comments: remove from core (handled by Comments plugin); migrate config to plug */
sed_sql_query("DELETE FROM $db_core WHERE ct_code='comments'");
$adminmain .= "Comments removed from core (use Comments plugin).<br />";
sed_sql_query("UPDATE $db_config SET config_owner='plug' WHERE config_owner='core' AND config_cat='comments'");
$adminmain .= "Comments config migrated to plug.<br />";
sed_sql_query("DELETE FROM $db_config WHERE config_owner='plug' AND config_cat='comments' AND config_name='disable_comments'");
$com_orders = array('showcommentsonpage' => '01', 'maxcommentsperpage' => '02', 'maxtimeallowcomedit' => '03', 'maxcommentlenght' => '04', 'countcomments' => '05', 'commentsorder' => '06');
foreach ($com_orders as $cname => $corder) {
	sed_sql_query("UPDATE $db_config SET config_order='" . sed_sql_prep($corder) . "' WHERE config_owner='plug' AND config_cat='comments' AND config_name='" . sed_sql_prep($cname) . "'");
}
$adminmain .= "Comments config_order normalized.<br />";

/* ======== Image config: pfs -> images, gallery (gallery_* -> th_*) -> images ======== */
$adminmain .= "Moving image config from core pfs to core images...<br />";
sed_sql_query("UPDATE $db_config SET config_cat='images' WHERE config_owner='core' AND config_cat='pfs' AND config_name IN ('available_image_sizes','th_amode','th_x','th_y','th_dimpriority','th_keepratio','th_jpeg_quality','th_rel')");

$adminmain .= "Moving gallery image config to core images (gallery_* -> th_*)...<br />";
$gallery_to_images = array(
	'gallery_imgmaxwidth'  => array('th_imgmaxwidth',  '12', '2', '600', ''),
	'gallery_logofile'     => array('th_logofile',     '20', '1', '', ''),
	'gallery_logopos'      => array('th_logopos',      '21', '2', 'Bottom left', 'Top left,Top right,Bottom left,Bottom right'),
	'gallery_logotrsp'     => array('th_logotrsp',     '22', '2', '50', '0,5,10,15,20,30,40,50,60,70,80,90,95,100'),
	'gallery_logojpegqual' => array('th_logojpegqual', '23', '2', '90', '0,5,10,20,30,40,50,60,70,80,90,95,100'),
);
foreach ($gallery_to_images as $old_name => $new_data) {
	list($new_name, $order, $type, $default, $variants) = $new_data;
	$sql = sed_sql_query("SELECT config_value, config_default, config_text, config_variants FROM $db_config WHERE config_owner='core' AND config_cat='gallery' AND config_name='" . sed_sql_prep($old_name) . "'");
	if ($row = sed_sql_fetchassoc($sql)) {
		$chk = sed_sql_query("SELECT 1 FROM $db_config WHERE config_owner='core' AND config_cat='images' AND config_name='" . sed_sql_prep($new_name) . "'");
		if (!sed_sql_fetchassoc($chk)) {
			$val = sed_sql_prep($row['config_value']);
			$def = sed_sql_prep($row['config_default']);
			$txt = sed_sql_prep($row['config_text']);
			$var = sed_sql_prep($row['config_variants']);
			sed_sql_query("INSERT INTO $db_config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default, config_text, config_variants) VALUES ('core', 'images', '$order', '$new_name', '$type', '$val', '$def', '$txt', '$var')");
			$adminmain .= "Insert $new_name.<br />";
		}
	}
}
sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='gallery' AND config_name IN ('gallery_imgmaxwidth','gallery_logofile','gallery_logopos','gallery_logotrsp','gallery_logojpegqual')");
$adminmain .= "Done.<br />";

/* ======== RSS config migration ======== */
$adminmain .= "Migrating RSS config from core to module...<br />";
sed_sql_query("UPDATE $db_config SET config_owner='module' WHERE config_owner='core' AND config_cat='rss'");
sed_sql_query("DELETE FROM $db_config WHERE config_cat='rss' AND config_name='disable_rssforums'");

/* ======== Remove obsolete disable_* configs ======== */
$adminmain .= "Removing obsolete disable_* configs...<br />";
sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='polls' AND config_name='disable_polls'");
sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='pm' AND config_name='disable_pm'");
sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='page' AND config_name='disable_page'");

/* ======== PM config migration ======== */
$adminmain .= "Moving PM config to module...<br />";
sed_sql_query("UPDATE $db_config SET config_owner='module' WHERE config_owner='core' AND config_cat='pm' AND config_name IN ('pm_maxsize', 'pm_allownotifications')");
sed_sql_query("UPDATE $db_config SET config_owner='module', config_cat='pm' WHERE config_owner='core' AND config_cat='meta' AND config_name='pmtitle'");

/* ======== Page config migration ======== */
$adminmain .= "Moving listtitle, pagetitle from core/meta to module/page...<br />";
sed_sql_query("UPDATE $db_config SET config_owner='module', config_cat='page' WHERE config_owner='core' AND config_cat='meta' AND config_name IN ('listtitle', 'pagetitle')");

/* ======== Ratings: remove from core (handled by Ratings plugin) ======== */
sed_sql_query("DELETE FROM $db_core WHERE ct_code='ratings'");
$adminmain .= "Ratings removed from core (use Ratings plugin).<br />";
sed_sql_query("DELETE FROM $db_auth WHERE auth_code='ratings'");
sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='ratings' AND config_name='disable_ratings'");

/* Admin plugin convention: admin.plug instead of admin.section.handlers + admin.manage.tags */
sed_sql_query("DELETE FROM $db_plugins WHERE pl_hook IN ('admin.section.handlers','admin.manage.tags') AND pl_code IN ('comments','ratings') AND pl_module=0");
$adminmain .= "Removed old admin section handlers and admin.tags parts (comments, ratings).<br />";
foreach (array('comments' => 'Comments', 'ratings' => 'Ratings') as $pcode => $ptitle) {
	$chk = sed_sql_query("SELECT 1 FROM $db_plugins WHERE pl_hook='admin.plug' AND pl_code='" . sed_sql_prep($pcode) . "' AND pl_module=0 LIMIT 1");
	if (!sed_sql_fetchassoc($chk)) {
		sed_sql_query("INSERT INTO $db_plugins (pl_hook, pl_code, pl_part, pl_title, pl_version, pl_dependencies, pl_file, pl_order, pl_active, pl_module) VALUES ('admin.plug', '" . sed_sql_prep($pcode) . "', 'admin.plug', '" . sed_sql_prep($ptitle) . "', '1.0', '', '" . sed_sql_prep($pcode) . ".admin.plug', 10, 1, 0)");
		$adminmain .= "Added admin.plug part for $pcode.<br />";
	}
}

/* Plugin dependencies: otherpages, similarpages, slider -> page; uploader -> pfs */
$deps_page = sed_sql_prep(json_encode(array('requires' => array('page'), 'requires_plugins' => array())));
$deps_pfs = sed_sql_prep(json_encode(array('requires' => array('pfs'), 'requires_plugins' => array())));
$adminmain .= "Updating plugin dependencies & lock status...<br />";
sed_sql_query("UPDATE $db_plugins SET pl_dependencies='$deps_page' WHERE pl_module=0 AND pl_code IN ('otherpages','similarpages','slider')");
sed_sql_query("UPDATE $db_plugins SET pl_dependencies='$deps_pfs' WHERE pl_module=0 AND pl_code='uploader'");
sed_sql_query("UPDATE $db_plugins SET pl_lock=0 WHERE 1");

$adminmain .= "-----------------------<br />";

/* ======== Users: migrate from core to module ======== */
$adminmain .= "Migrating Users from core to module...<br />";

$saved_users_auth = array();
$sql_ua = sed_sql_query("SELECT * FROM $db_auth WHERE auth_code='users'");
while ($row_ua = sed_sql_fetchassoc($sql_ua)) {
	$saved_users_auth[] = $row_ua;
}

sed_sql_query("DELETE FROM $db_core WHERE ct_code='users'");
sed_sql_query("DELETE FROM $db_plugins WHERE pl_code='users' AND pl_module=1");
sed_sql_query("UPDATE $db_config SET config_owner='module' WHERE config_owner='core' AND config_cat='users'");
$adminmain .= "Users core entry removed, config owner updated.<br />";

$adminmain .= "-----------------------<br />";

/* ======== Clean up old core configs that now belong to modules ======== */
$adminmain .= "Removing old core configs for modules (will be recreated by module install)...<br />";
$modules_to_install = array('users', 'page', 'forums', 'pfs', 'pm', 'polls', 'gallery', 'rss', 'sitemap', 'view');
foreach ($modules_to_install as $mod_code) {
	sed_sql_query("DELETE FROM $db_config WHERE config_owner='core' AND config_cat='" . sed_sql_prep($mod_code) . "'");
}
$adminmain .= "Done.<br />";

/* ======== Full module reinstallation ======== */
$adminmain .= "<strong>Reinstalling all modules...</strong><br />";
foreach ($modules_to_install as $mod_code) {
	$setup_file = SED_ROOT . '/modules/' . $mod_code . '/' . $mod_code . '.setup.php';
	if (file_exists($setup_file)) {
		$adminmain .= sed_module_install($mod_code);
	} else {
		$adminmain .= "Module $mod_code: setup file not found, skipped.<br />";
	}
}

/* ======== Users: restore custom auth entries ======== */
if (!empty($saved_users_auth)) {
	$adminmain .= "Restoring custom Users auth entries...<br />";
	foreach ($saved_users_auth as $sa) {
		sed_sql_query("UPDATE $db_auth SET auth_rights=" . (int)$sa['auth_rights'] . ", auth_rights_lock=" . (int)$sa['auth_rights_lock'] . " WHERE auth_groupid=" . (int)$sa['auth_groupid'] . " AND auth_code='users' AND auth_option='" . sed_sql_prep($sa['auth_option']) . "'");
	}
	$adminmain .= "Done.<br />";
}

/* ======== Full plugin reinstallation ======== */
$adminmain .= "<strong>Reinstalling all plugins...</strong><br />";
$plugin_dirs = glob(SED_ROOT . '/plugins/*/', GLOB_ONLYDIR);
if (is_array($plugin_dirs)) {
	foreach ($plugin_dirs as $pdir) {
		$pcode = basename($pdir);
		if (file_exists($pdir . $pcode . '.setup.php')) {
			$adminmain .= sed_plugin_install($pcode);
		}
	}
}

$adminmain .= "-----------------------<br />";
$adminmain .= "Regenerating URL cache...<br />";
sed_urls_generate();
$adminmain .= "URL cache regenerated.<br />";
sed_cache_clearall();

$adminmain .= "Changing the SQL version number to 185...<br />";
sed_sql_query("UPDATE $db_stats SET stat_value='185' WHERE stat_name='version'");
$upg_status = TRUE;
