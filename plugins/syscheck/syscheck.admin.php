<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/syscheck/syscheck.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=syscheck
Part=admin
File=syscheck.admin
Hooks=tools
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

$syschecktitles['launchers'] = 'Core Router Launchers';

$systemfiles['launchers'][] = 'system/core/admin/admin.php';
$systemfiles['launchers'][] = 'system/core/forums/forums.php';
$systemfiles['launchers'][] = 'system/core/gallery/gallery.php';
$systemfiles['launchers'][] = 'system/core/index/index.php';
$systemfiles['launchers'][] = 'system/core/list/list.php';
$systemfiles['launchers'][] = 'system/core/message/message.php';
$systemfiles['launchers'][] = 'system/core/page/page.php';
$systemfiles['launchers'][] = 'system/core/pfs/pfs.php';
$systemfiles['launchers'][] = 'system/core/plug/plug.php';
$systemfiles['launchers'][] = 'system/core/pm/pm.php';
$systemfiles['launchers'][] = 'system/core/polls/polls.php';
$systemfiles['launchers'][] = 'system/core/users/users.php';
$systemfiles['launchers'][] = 'system/core/view/view.php';
$systemfiles['launchers'][] = 'system/core/captcha/captcha.php';
$systemfiles['launchers'][] = 'system/core/resizer/resizer.php';
$systemfiles['launchers'][] = 'system/core/sitemap/sitemap.php';
$systemfiles['launchers'][] = 'system/core/rss/rss.php';

$syschecktitles['config'] = 'Configuration and directory blockers';

$systemfiles['config'][] = 'datas/config.default.php';
$systemfiles['config'][] = 'datas/config.php';
$systemfiles['config'][] = 'datas/index.php';
$systemfiles['config'][] = 'datas/avatars/index.php';
$systemfiles['config'][] = 'datas/defaultav/index.php';
$systemfiles['config'][] = 'datas/photos/index.php';
$systemfiles['config'][] = 'datas/signatures/index.php';
$systemfiles['config'][] = 'datas/thumbs/index.php';
$systemfiles['config'][] = 'datas/users/index.php';

$syschecktitles['system'] = 'System';

$systemfiles['system'][] = 'system/common.php';
$systemfiles['system'][] = 'system/config.extensions.php';
$systemfiles['system'][] = 'system/database.mysqli.php';
$systemfiles['system'][] = 'system/footer.php';
$systemfiles['system'][] = 'system/functions.admin.php';
$systemfiles['system'][] = 'system/functions.php';
$systemfiles['system'][] = 'system/header.php';
$systemfiles['system'][] = 'system/index.php';
$systemfiles['system'][] = 'system/templates.php';

$syschecktitles['core'] = 'Core features';

$systemfiles['core'][] = 'system/core/admin/admin.banlist.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.cache.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.comments.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.config.gallery.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.config.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.config.lang.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.config.skin.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.config.time.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.dic.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.footer.php';
$systemfiles['core'][] = 'system/core/admin/admin.forums.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.forums.structure.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.gallery.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.header.php';
$systemfiles['core'][] = 'system/core/admin/admin.hits.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.home.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.log.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.menu.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.page.add.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.page.edit.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.page.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.page.manager.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.pfs.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.php';
$systemfiles['core'][] = 'system/core/admin/admin.plug.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.pm.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.polls.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.ratings.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.referers.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.rights.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.rightsbyitem.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.smilies.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.manage.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.trashcan.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.upgrade.inc.php';
$systemfiles['core'][] = 'system/core/admin/admin.users.inc.php';
$systemfiles['core'][] = 'system/core/captcha/captcha.php';
$systemfiles['core'][] = 'system/core/forums/forums.editpost.inc.php';
$systemfiles['core'][] = 'system/core/forums/forums.inc.php';
$systemfiles['core'][] = 'system/core/forums/forums.php';
$systemfiles['core'][] = 'system/core/forums/forums.newtopic.inc.php';
$systemfiles['core'][] = 'system/core/forums/forums.posts.inc.php';
$systemfiles['core'][] = 'system/core/forums/forums.topics.inc.php';
$systemfiles['core'][] = 'system/core/gallery/gallery.browse.inc.php';
$systemfiles['core'][] = 'system/core/gallery/gallery.details.inc.php';
$systemfiles['core'][] = 'system/core/gallery/gallery.home.inc.php';
$systemfiles['core'][] = 'system/core/index/index.inc.php';
$systemfiles['core'][] = 'system/core/index/index.php';
$systemfiles['core'][] = 'system/core/list/list.inc.php';
$systemfiles['core'][] = 'system/core/list/list.php';
$systemfiles['core'][] = 'system/core/message/message.inc.php';
$systemfiles['core'][] = 'system/core/message/message.php';
$systemfiles['core'][] = 'system/core/page/page.add.inc.php';
$systemfiles['core'][] = 'system/core/page/page.edit.inc.php';
$systemfiles['core'][] = 'system/core/page/page.inc.php';
$systemfiles['core'][] = 'system/core/page/page.php';
$systemfiles['core'][] = 'system/core/pfs/pfs.edit.inc.php';
$systemfiles['core'][] = 'system/core/pfs/pfs.editfolder.inc.php';
$systemfiles['core'][] = 'system/core/pfs/pfs.inc.php';
$systemfiles['core'][] = 'system/core/pfs/pfs.php';
$systemfiles['core'][] = 'system/core/pfs/pfs.view.inc.php';
$systemfiles['core'][] = 'system/core/plug/plug.inc.php';
$systemfiles['core'][] = 'system/core/plug/plug.php';
$systemfiles['core'][] = 'system/core/pm/pm.edit.inc.php';
$systemfiles['core'][] = 'system/core/pm/pm.inc.php';
$systemfiles['core'][] = 'system/core/pm/pm.php';
$systemfiles['core'][] = 'system/core/pm/pm.send.inc.php';
$systemfiles['core'][] = 'system/core/polls/polls.inc.php';
$systemfiles['core'][] = 'system/core/polls/polls.php';
$systemfiles['core'][] = 'system/core/resizer/resizer.php';
$systemfiles['core'][] = 'system/core/rss/rss.inc.php';
$systemfiles['core'][] = 'system/core/rss/rss.php';
$systemfiles['core'][] = 'system/core/sitemap/sitemap.inc.php';
$systemfiles['core'][] = 'system/core/sitemap/sitemap.php';
$systemfiles['core'][] = 'system/core/users/users.auth.inc.php';
$systemfiles['core'][] = 'system/core/users/users.details.inc.php';
$systemfiles['core'][] = 'system/core/users/users.edit.inc.php';
$systemfiles['core'][] = 'system/core/users/users.inc.php';
$systemfiles['core'][] = 'system/core/users/users.logout.inc.php';
$systemfiles['core'][] = 'system/core/users/users.profile.inc.php';
$systemfiles['core'][] = 'system/core/users/users.register.inc.php';
$systemfiles['core'][] = 'system/core/view/view.inc.php';
$systemfiles['core'][] = 'system/core/users/users.php';

$syschecktitles['install'] = 'Installation and upgrade';

$systemfiles['install'][] = 'system/install/install.php';
$systemfiles['install'][] = 'system/install/install.config.php';
$systemfiles['install'][] = 'system/install/install.database.php';
$systemfiles['install'][] = 'system/install/install.main.php';
$systemfiles['install'][] = 'system/install/install.setup.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_125_130.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_126_130.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_130_150.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_150_160.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_160_171.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_171_172.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_172_173.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_173_175.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_175_178.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_177_178.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_178_179.php';
$systemfiles['install'][] = 'system/upgrade/upgrade_179_180.php';

$syschecktitles['lang'] = 'Default language files';

$systemfiles['lang'][] = 'system/lang/' . $cfg['defaultlang'] . '/admin.lang.php';
$systemfiles['lang'][] = 'system/lang/' . $cfg['defaultlang'] . '/main.lang.php';
$systemfiles['lang'][] = 'system/lang/' . $cfg['defaultlang'] . '/message.lang.php';

$syschecktitles['skin'] = 'Default skin files';

$systemfiles['skin'][] = 'skins/' . $cfg['defaultskin'] . '/' . $cfg['defaultskin'] . '.' . $cfg['defaultlang'] . '.lang.php';
$systemfiles['skin'][] = 'skins/' . $cfg['defaultskin'] . '/' . $cfg['defaultskin'] . '.php';

//Default skin files

$skinfiles[] = 'comments.tpl';
$skinfiles[] = 'footer.tpl';
$skinfiles[] = 'forums.editpost.tpl';
$skinfiles[] = 'forums.newtopic.tpl';
$skinfiles[] = 'forums.posts.tpl';
$skinfiles[] = 'forums.sections.tpl';
$skinfiles[] = 'forums.topics.tpl';
$skinfiles[] = 'gallery.browse.tpl';
$skinfiles[] = 'gallery.details.tpl';
$skinfiles[] = 'gallery.home.tpl';
$skinfiles[] = 'header.tpl';
$skinfiles[] = 'index.tpl';
$skinfiles[] = 'list.group.tpl';
$skinfiles[] = 'list.tpl';
$skinfiles[] = 'message.tpl';
$skinfiles[] = 'news.tpl';
$skinfiles[] = 'page.add.tpl';
$skinfiles[] = 'page.edit.tpl';
$skinfiles[] = 'page.tpl';
$skinfiles[] = 'pfs.standalone.tpl';
$skinfiles[] = 'pfs.tpl';
$skinfiles[] = 'plugin.tpl';
$skinfiles[] = 'pm.send.tpl';
$skinfiles[] = 'pm.tpl';
$skinfiles[] = 'poll.tpl';
$skinfiles[] = 'polls.tpl';
$skinfiles[] = 'polls.standalone.tpl';
$skinfiles[] = 'popup.tpl';
$skinfiles[] = 'ratings.tpl';
$skinfiles[] = 'users.auth.tpl';
$skinfiles[] = 'users.details.tpl';
$skinfiles[] = 'users.edit.tpl';
$skinfiles[] = 'users.profile.tpl';
$skinfiles[] = 'users.register.tpl';
$skinfiles[] = 'users.tpl';

//Default admin skin files

$admskinfiles[] = 'admin.banlist.tpl';
$admskinfiles[] = 'admin.breadcrumbs.tpl';
$admskinfiles[] = 'admin.cache.tpl';
$admskinfiles[] = 'admin.comments.tpl';
$admskinfiles[] = 'admin.config.gallery.tpl';
$admskinfiles[] = 'admin.config.lang.tpl';
$admskinfiles[] = 'admin.config.skin.tpl';
$admskinfiles[] = 'admin.config.time.tpl';
$admskinfiles[] = 'admin.config.tpl';
$admskinfiles[] = 'admin.dic.tpl';
$admskinfiles[] = 'admin.footer.tpl';
$admskinfiles[] = 'admin.forums.structure.tpl';
$admskinfiles[] = 'admin.forums.tpl';
$admskinfiles[] = 'admin.gallery.tpl';
$admskinfiles[] = 'admin.header.tpl';
$admskinfiles[] = 'admin.hits.tpl';
$admskinfiles[] = 'admin.home.tpl';
$admskinfiles[] = 'admin.log.tpl';
$admskinfiles[] = 'admin.menu.tpl';
$admskinfiles[] = 'admin.nav.tpl';
$admskinfiles[] = 'admin.page.add.tpl';
$admskinfiles[] = 'admin.page.edit.tpl';
$admskinfiles[] = 'admin.page.manager.tpl';
$admskinfiles[] = 'admin.page.tpl';
$admskinfiles[] = 'admin.pfs.tpl';
$admskinfiles[] = 'admin.plug.tpl';
$admskinfiles[] = 'admin.pm.tpl';
$admskinfiles[] = 'admin.polls.tpl';
$admskinfiles[] = 'admin.ratings.tpl';
$admskinfiles[] = 'admin.referers.tpl';
$admskinfiles[] = 'admin.rights.tpl';
$admskinfiles[] = 'admin.rightsbyitem.tpl';
$admskinfiles[] = 'admin.smilies.tpl';
$admskinfiles[] = 'admin.manage.tpl';
$admskinfiles[] = 'admin.tpl';
$admskinfiles[] = 'admin.trashcan.tpl';
$admskinfiles[] = 'admin.upgrade.tpl';
$admskinfiles[] = 'admin.users.tpl';

$color[0] = "#bc6262";
$color[1] = "#62bc6a";

$plugin_body = '';

foreach ($syschecktitles as $key => $systitle) {

	$plugin_body .= '<div class="content-box">';
	$plugin_body .= '<div class="content-box-header">';
	$plugin_body .= '<h3>' . $systitle . '</h3>';
	$plugin_body .= '</div>';

	$plugin_body .= '<div class="content-box-content">';

	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr>";
	$plugin_body .= "<td class=\"coltop\">File</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:15%;\">Type</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:25%;\">Description</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Version</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Date</td>";
	$plugin_body .= "</tr>";

	foreach ($systemfiles[$key] as $file) {

		$plugin_body .= "<tr>";
		$plugin_body .= "<td>" . $file . "</td>";

		if (file_exists(SED_ROOT . "/" . $file)) {
			$info = sed_infoget($file);
			if (!empty($info['Error'])) {
				$plugin_body .= "<td colspan=\"4\">" . $info['Error'] . "</td>";
			} else {
				$bgcolor = ($info['Version'] == $cfg['version']) ? $color[1] : $color[0];
				$plugin_body .= "<td>" . $info['Type'] . "</td>";
				$plugin_body .= "<td>" . $info['Description'] . "</td>";
				$plugin_body .= "<td style=\"background-color:" . $bgcolor . "!important; text-align:center;\">" . $info['Version'] . "</td>";
				$plugin_body .= "<td style=\"text-align:center;\">" . $info['Updated'] . "</td>";
			}
		} else {
			$plugin_body .= "<td colspan=\"4\">File not found !</td>";
		}
		$plugin_body .= "</tr>";
	}

	$plugin_body .= "</table>";

	$plugin_body .= "</div>";
	$plugin_body .= "</div>";
}

$plugin_body .= '<div class="content-box">';

$plugin_body .= '<div class="content-box-header">';
$plugin_body .= '<h3>Skin files (templates) :</h3>';
$plugin_body .= '</div>';

$plugin_body .= '<div class="content-box-content">';

$plugin_body .= "<table class=\"cells striped\">";
$plugin_body .= "<tr>";
$plugin_body .= "<td class=\"coltop\">Skin file</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Found ?</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Size (Bytes)</td>";
$plugin_body .= "</tr>";

foreach ($skinfiles as $file) {
	$file = "skins/" . $skin . "/" . $file;
	$plugin_body .= "<tr>";
	$plugin_body .= "<td>" . $file . "</td>";

	if (file_exists(SED_ROOT . "/" . $file)) {
		$plugin_body .= "<td style=\"background-color:" . $color[1] . "!important; text-align:center;\">Present</td>";
		$plugin_body .= "<td style=\"text-align:right;\">" . @filesize($file) . "</td>";
	} else {
		$plugin_body .= "<td style=\"background-color:" . $color[0] . "!important; text-align:center;\">Missing !</td>";
		$plugin_body .= "<td style=\"text-align:right;\">0</td>";
	}
	$plugin_body .= "</tr>";
}

$plugin_body .= "</table>";

$plugin_body .= "</div>";
$plugin_body .= "</div>";

$plugin_body .= '<div class="content-box">';

$plugin_body .= '<div class="content-box-header">';
$plugin_body .= '<h3>Admin Skin files (templates) :</h3>';
$plugin_body .= '</div>';

$plugin_body .= '<div class="content-box-content">';

$plugin_body .= "<table class=\"cells striped\">";
$plugin_body .= "<tr>";
$plugin_body .= "<td class=\"coltop\">Skin file</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Found ?</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:100px;\">Size (Bytes)</td>";
$plugin_body .= "</tr>";

foreach ($admskinfiles as $file) {
	$file = "system/adminskin/" . $skin . "/" . $file;
	$plugin_body .= "<tr>";
	$plugin_body .= "<td>" . $file . "</td>";

	if (file_exists(SED_ROOT . "/" . $file)) {
		$plugin_body .= "<td style=\"background-color:" . $color[1] . "!important; text-align:center;\">Present</td>";
		$plugin_body .= "<td style=\"text-align:right;\">" . @filesize($file) . "</td>";
	} else {
		$plugin_body .= "<td style=\"background-color:" . $color[0] . "!important; text-align:center;\">Missing !</td>";
		$plugin_body .= "<td style=\"text-align:right;\">0</td>";
	}
	$plugin_body .= "</tr>";
}

$plugin_body .= "</table>";

$plugin_body .= "</div>";
$plugin_body .= "</div>";
