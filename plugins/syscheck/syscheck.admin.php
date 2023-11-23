<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/syscheck/syscheck.php
Version=179
Updated=2022-jul-15
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

$systemfiles[] = '*Core Router Launchers';

$systemfiles[] = 'system/core/admin/admin.php';
$systemfiles[] = 'system/core/forums/forums.php';
$systemfiles[] = 'system/core/gallery/gallery.php';
$systemfiles[] = 'system/core/index/index.php';
$systemfiles[] = 'system/core/list/list.php';
$systemfiles[] = 'system/core/message/message.php';
$systemfiles[] = 'system/core/page/page.php';
$systemfiles[] = 'system/core/pfs/pfs.php';
$systemfiles[] = 'system/core/plug/plug.php';
$systemfiles[] = 'system/core/pm/pm.php';
$systemfiles[] = 'system/core/polls/polls.php';
$systemfiles[] = 'system/core/users/users.php';
$systemfiles[] = 'system/core/view/view.php';

$systemfiles[] = 'system/core/captcha/captcha.php';
$systemfiles[] = 'system/core/resizer/resizer.php';
$systemfiles[] = 'system/core/sitemap/sitemap.php';
$systemfiles[] = 'system/core/rss/rss.php';

$systemfiles[] = '*Configuration and directory blockers';

$systemfiles[] = 'datas/config.default.php';
$systemfiles[] = 'datas/config.php';
$systemfiles[] = 'datas/index.php';
$systemfiles[] = 'datas/avatars/index.php';
$systemfiles[] = 'datas/defaultav/index.php';
$systemfiles[] = 'datas/photos/index.php';
$systemfiles[] = 'datas/signatures/index.php';
$systemfiles[] = 'datas/thumbs/index.php';
$systemfiles[] = 'datas/users/index.php';

$systemfiles[] = '*System';

$systemfiles[] = 'system/common.php';
$systemfiles[] = 'system/config.extensions.php';
$systemfiles[] = 'system/database.mysql.php';
$systemfiles[] = 'system/footer.php';
$systemfiles[] = 'system/functions.admin.php';
$systemfiles[] = 'system/functions.php';
$systemfiles[] = 'system/header.php';
$systemfiles[] = 'system/index.php';
$systemfiles[] = 'system/templates.php';

$systemfiles[] = '*Core features';

$systemfiles[] = 'system/core/admin/admin.banlist.inc.php';
$systemfiles[] = 'system/core/admin/admin.cache.inc.php';
$systemfiles[] = 'system/core/admin/admin.comments.inc.php';
$systemfiles[] = 'system/core/admin/admin.config.gallery.inc.php';
$systemfiles[] = 'system/core/admin/admin.config.inc.php';
$systemfiles[] = 'system/core/admin/admin.config.lang.inc.php';
$systemfiles[] = 'system/core/admin/admin.config.skin.inc.php';
$systemfiles[] = 'system/core/admin/admin.config.time.inc.php';
$systemfiles[] = 'system/core/admin/admin.dic.inc.php';
$systemfiles[] = 'system/core/admin/admin.footer.php';
$systemfiles[] = 'system/core/admin/admin.forums.inc.php';
$systemfiles[] = 'system/core/admin/admin.forums.structure.inc.php';
$systemfiles[] = 'system/core/admin/admin.gallery.inc.php';
$systemfiles[] = 'system/core/admin/admin.header.php';
$systemfiles[] = 'system/core/admin/admin.hits.inc.php';
$systemfiles[] = 'system/core/admin/admin.home.inc.php';
$systemfiles[] = 'system/core/admin/admin.inc.php';
$systemfiles[] = 'system/core/admin/admin.log.inc.php';
$systemfiles[] = 'system/core/admin/admin.menu.inc.php';
$systemfiles[] = 'system/core/admin/admin.page.add.inc.php';
$systemfiles[] = 'system/core/admin/admin.page.edit.inc.php';
$systemfiles[] = 'system/core/admin/admin.page.inc.php';
$systemfiles[] = 'system/core/admin/admin.page.manager.inc.php';
$systemfiles[] = 'system/core/admin/admin.pfs.inc.php';
$systemfiles[] = 'system/core/admin/admin.php';
$systemfiles[] = 'system/core/admin/admin.plug.inc.php';
$systemfiles[] = 'system/core/admin/admin.pm.inc.php';
$systemfiles[] = 'system/core/admin/admin.polls.inc.php';
$systemfiles[] = 'system/core/admin/admin.ratings.inc.php';
$systemfiles[] = 'system/core/admin/admin.referers.inc.php';
$systemfiles[] = 'system/core/admin/admin.rights.inc.php';
$systemfiles[] = 'system/core/admin/admin.rightsbyitem.inc.php';
$systemfiles[] = 'system/core/admin/admin.smilies.inc.php';
$systemfiles[] = 'system/core/admin/admin.tools.inc.php';
$systemfiles[] = 'system/core/admin/admin.trashcan.inc.php';
$systemfiles[] = 'system/core/admin/admin.upgrade.inc.php';
$systemfiles[] = 'system/core/admin/admin.users.inc.php';
$systemfiles[] = 'system/core/captcha/captcha.php';
$systemfiles[] = 'system/core/forums/forums.editpost.inc.php';
$systemfiles[] = 'system/core/forums/forums.inc.php';
$systemfiles[] = 'system/core/forums/forums.php';
$systemfiles[] = 'system/core/forums/forums.newtopic.inc.php';
$systemfiles[] = 'system/core/forums/forums.posts.inc.php';
$systemfiles[] = 'system/core/forums/forums.topics.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.browse.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.details.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.home.inc.php';
$systemfiles[] = 'system/core/index/index.inc.php';
$systemfiles[] = 'system/core/index/index.php';
$systemfiles[] = 'system/core/list/list.inc.php';
$systemfiles[] = 'system/core/list/list.php';
$systemfiles[] = 'system/core/message/message.inc.php';
$systemfiles[] = 'system/core/message/message.php';
$systemfiles[] = 'system/core/page/page.add.inc.php';
$systemfiles[] = 'system/core/page/page.edit.inc.php';
$systemfiles[] = 'system/core/page/page.inc.php';
$systemfiles[] = 'system/core/page/page.php';
$systemfiles[] = 'system/core/pfs/pfs.edit.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.editfolder.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.php';
$systemfiles[] = 'system/core/pfs/pfs.view.inc.php';
$systemfiles[] = 'system/core/plug/plug.inc.php';
$systemfiles[] = 'system/core/plug/plug.php';
$systemfiles[] = 'system/core/pm/pm.edit.inc.php';
$systemfiles[] = 'system/core/pm/pm.inc.php';
$systemfiles[] = 'system/core/pm/pm.php';
$systemfiles[] = 'system/core/pm/pm.send.inc.php';
$systemfiles[] = 'system/core/polls/polls.inc.php';
$systemfiles[] = 'system/core/polls/polls.php';
$systemfiles[] = 'system/core/resizer/resizer.php';
$systemfiles[] = 'system/core/rss/rss.inc.php';
$systemfiles[] = 'system/core/rss/rss.php';
$systemfiles[] = 'system/core/sitemap/sitemap.inc.php';
$systemfiles[] = 'system/core/sitemap/sitemap.php';
$systemfiles[] = 'system/core/users/users.auth.inc.php';
$systemfiles[] = 'system/core/users/users.details.inc.php';
$systemfiles[] = 'system/core/users/users.edit.inc.php';
$systemfiles[] = 'system/core/users/users.inc.php';
$systemfiles[] = 'system/core/users/users.logout.inc.php';
$systemfiles[] = 'system/core/users/users.profile.inc.php';
$systemfiles[] = 'system/core/users/users.register.inc.php';
$systemfiles[] = 'system/core/view/view.inc.php';
$systemfiles[] = 'system/core/users/users.php';

$systemfiles[] = '*Installation and upgrade';

$systemfiles[] = 'system/install/install.php';
$systemfiles[] = 'system/install/install.config.php';
$systemfiles[] = 'system/install/install.database.php';
$systemfiles[] = 'system/install/install.main.php';
$systemfiles[] = 'system/install/install.setup.php';
$systemfiles[] = 'system/upgrade/upgrade_125_130.php';
$systemfiles[] = 'system/upgrade/upgrade_126_130.php';
$systemfiles[] = 'system/upgrade/upgrade_130_150.php';
$systemfiles[] = 'system/upgrade/upgrade_150_160.php';
$systemfiles[] = 'system/upgrade/upgrade_160_171.php';
$systemfiles[] = 'system/upgrade/upgrade_171_172.php';
$systemfiles[] = 'system/upgrade/upgrade_172_173.php';
$systemfiles[] = 'system/upgrade/upgrade_173_175.php';
$systemfiles[] = 'system/upgrade/upgrade_175_178.php';
$systemfiles[] = 'system/upgrade/upgrade_177_178.php';

$systemfiles[] = '*Default language files';

$systemfiles[] = 'system/lang/' . $cfg['defaultlang'] . '/admin.lang.php';
$systemfiles[] = 'system/lang/' . $cfg['defaultlang'] . '/main.lang.php';
$systemfiles[] = 'system/lang/' . $cfg['defaultlang'] . '/message.lang.php';

$systemfiles[] = '*Default skin files';

$systemfiles[] = 'skins/' . $cfg['defaultskin'] . '/' . $cfg['defaultskin'] . '.' . $cfg['defaultlang'] . '.lang.php';
$systemfiles[] = 'skins/' . $cfg['defaultskin'] . '/' . $cfg['defaultskin'] . '.php';

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
$admskinfiles[] = 'admin.tools.tpl';
$admskinfiles[] = 'admin.tpl';
$admskinfiles[] = 'admin.trashcan.tpl';
$admskinfiles[] = 'admin.upgrade.tpl';
$admskinfiles[] = 'admin.users.tpl';

$table_header = "<table class=\"cells striped\">";
$table_header .= "<tr>";
$table_header .= "<td class=\"coltop\" style=\"width:40%;\">File</td>";
$table_header .= "<td class=\"coltop\" style=\"width:15%;\">Type</td>";
$table_header .= "<td class=\"coltop\" style=\"width:25%;\">Description</td>";
$table_header .= "<td class=\"coltop\" style=\"width:8%;\">Version</td>";
$table_header .= "<td class=\"coltop\" style=\"width:12%;\">Date</td>";
$table_header .= "</tr>";

$color[0] = "#bc6262";
$color[1] = "#62bc6a";
$plugin_body = '';

foreach ($systemfiles as $file) {
	if (mb_substr($file, 0, 1) == "*") {
		$plugin_body .= "</table><h4>" . mb_substr($file, 1, 255) . " :</h4>";
		$plugin_body .= $table_header;;
	} else {
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
}

$plugin_body .= "</table>";

$plugin_body .= "<h4>Skin files (templates) :</h4>";

$plugin_body .= "<table class=\"cells striped\">";
$plugin_body .= "<tr>";
$plugin_body .= "<td class=\"coltop\" style=\"width:40%;\">Skin file</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:30%;\">Found ?</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:30%;\">Size (Bytes)</td>";
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

$plugin_body .= "<h4>Admin Skin files (templates) :</h4>";

$plugin_body .= "<table class=\"cells striped\">";
$plugin_body .= "<tr>";
$plugin_body .= "<td class=\"coltop\" style=\"width:40%;\">Skin file</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:30%;\">Found ?</td>";
$plugin_body .= "<td class=\"coltop\" style=\"width:30%;\">Size (Bytes)</td>";
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
