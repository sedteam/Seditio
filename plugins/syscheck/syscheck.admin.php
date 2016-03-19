<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/syscheck/syscheck.php
Version=177
Updated=2015-feb-06
Type=Plugin
Author=Neocrome
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

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }


$systemfiles[] = '*Root launchers';

$systemfiles[] = 'admin.php';
$systemfiles[] = 'forums.php';
$systemfiles[] = 'gallery.php';
$systemfiles[] = 'index.php';
$systemfiles[] = 'list.php';
$systemfiles[] = 'message.php';
$systemfiles[] = 'page.php';
$systemfiles[] = 'pfs.php';
$systemfiles[] = 'plug.php';
$systemfiles[] = 'pm.php';
$systemfiles[] = 'polls.php';
$systemfiles[] = 'users.php';
$systemfiles[] = 'view.php';

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
$systemfiles[] = 'system/core/admin/admin.forums.inc.php';
$systemfiles[] = 'system/core/admin/admin.forums.structure.inc.php';
$systemfiles[] = 'system/core/admin/admin.gallery.inc.php';
$systemfiles[] = 'system/core/admin/admin.hits.inc.php';
$systemfiles[] = 'system/core/admin/admin.home.inc.php';
$systemfiles[] = 'system/core/admin/admin.inc.php';
$systemfiles[] = 'system/core/admin/admin.log.inc.php';
$systemfiles[] = 'system/core/admin/admin.page.inc.php';
$systemfiles[] = 'system/core/admin/admin.pfs.inc.php';
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
$systemfiles[] = 'system/core/forums/forums.editpost.inc.php';
$systemfiles[] = 'system/core/forums/forums.inc.php';
$systemfiles[] = 'system/core/forums/forums.newtopic.inc.php';
$systemfiles[] = 'system/core/forums/forums.posts.inc.php';
$systemfiles[] = 'system/core/forums/forums.topics.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.browse.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.details.inc.php';
$systemfiles[] = 'system/core/gallery/gallery.home.inc.php';
$systemfiles[] = 'system/core/index/index.inc.php';
$systemfiles[] = 'system/core/list/list.inc.php';
$systemfiles[] = 'system/core/message/message.inc.php';
$systemfiles[] = 'system/core/page/page.add.inc.php';
$systemfiles[] = 'system/core/page/page.edit.inc.php';
$systemfiles[] = 'system/core/page/page.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.edit.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.editfolder.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.inc.php';
$systemfiles[] = 'system/core/pfs/pfs.view.inc.php';
$systemfiles[] = 'system/core/plug/plug.inc.php';
$systemfiles[] = 'system/core/pm/pm.edit.inc.php';
$systemfiles[] = 'system/core/pm/pm.inc.php';
$systemfiles[] = 'system/core/pm/pm.send.inc.php';
$systemfiles[] = 'system/core/polls/polls.inc.php';
$systemfiles[] = 'system/core/users/users.auth.inc.php';
$systemfiles[] = 'system/core/users/users.details.inc.php';
$systemfiles[] = 'system/core/users/users.edit.inc.php';
$systemfiles[] = 'system/core/users/users.inc.php';
$systemfiles[] = 'system/core/users/users.logout.inc.php';
$systemfiles[] = 'system/core/users/users.profile.inc.php';
$systemfiles[] = 'system/core/users/users.register.inc.php';
$systemfiles[] = 'system/core/view/view.inc.php';

$systemfiles[] = '*Installation and upgrade';

$systemfiles[] = 'install.php';
$systemfiles[] = 'system/install/install.config.php';
$systemfiles[] = 'system/install/install.database.php';
$systemfiles[] = 'system/install/install.main.php';
$systemfiles[] = 'system/install/install.parser.sql';
$systemfiles[] = 'system/install/install.setup.php';
$systemfiles[] = 'system/upgrade/upgrade_125_130.php';
$systemfiles[] = 'system/upgrade/upgrade_126_130.php';
$systemfiles[] = 'system/upgrade/upgrade_130_150.php';
$systemfiles[] = 'system/upgrade/upgrade_150_160.php';
$systemfiles[] = 'system/upgrade/upgrade_160_161.php';

$systemfiles[] = '*Default language files';

$systemfiles[] = 'system/lang/'.$cfg['defaultlang'].'/admin.lang.php';
$systemfiles[] = 'system/lang/'.$cfg['defaultlang'].'/main.lang.php';
$systemfiles[] = 'system/lang/'.$cfg['defaultlang'].'/message.lang.php';

$systemfiles[] = '*Default skin files';

$systemfiles[] = 'skins/'.$cfg['defaultskin'].'/'.$cfg['defaultskin'].'.'.$cfg['defaultlang'].'.lang.php';
$systemfiles[] = 'skins/'.$cfg['defaultskin'].'/'.$cfg['defaultskin'].'.php';

$skinfiles[] = 'admin.tpl';
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
$skinfiles[] = 'pfs.tpl';
$skinfiles[] = 'plugin.tpl';
$skinfiles[] = 'pm.send.tpl';
$skinfiles[] = 'pm.tpl';
$skinfiles[] = 'polls.tpl';
$skinfiles[] = 'popup.tpl';
$skinfiles[] = 'ratings.tpl';
$skinfiles[] = 'users.auth.tpl';
$skinfiles[] = 'users.details.tpl';
$skinfiles[] = 'users.edit.tpl';
$skinfiles[] = 'users.profile.tpl';
$skinfiles[] = 'users.register.tpl';
$skinfiles[] = 'users.tpl';

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

foreach ($systemfiles as $file)
		{

		if (mb_substr($file,0, 1)=="*")
		  {
      $adminmain .= "</table><h4>".mb_substr($file,1, 255)." :</h4>";
      $adminmain .= $table_header;;
      }
    else
      {
    $adminmain .= "<tr>";
		$adminmain .= "<td>".$file."</td>";

		if (file_exists($file))
			{
			$info = sed_infoget($file);
			if (!empty($info['Error']))
			  {
        $adminmain .= "<td colspan=\"4\">".$info['Error']."</td>";
        }
      else
        {
        $bgcolor = ($info['Version']==$cfg['version']) ? $color[1] : $color[0];
        $adminmain .= "<td>".$info['Type']."</td>";
        $adminmain .= "<td>".$info['Description']."</td>";
        $adminmain .= "<td style=\"background-color:".$bgcolor."!important; text-align:center;\">".$info['Version']."</td>";
        $adminmain .= "<td style=\"text-align:center;\">".$info['Updated']."</td>";
        }
			}
		else
			{
      $adminmain .= "<td colspan=\"4\">File not found !</td>";
      }
		$adminmain .= "</tr>";
		  }
		}

$adminmain .= "</table>";

$adminmain .= "<h4>Skin files (templates) :</h4>";

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr>";
$adminmain .= "<td class=\"coltop\" style=\"width:40%;\">Skin file</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:30%;\">Found ?</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:30%;\">Size (Bytes)</td>";
$adminmain .= "</tr>";

foreach ($skinfiles as $file)
	{
	$file = "skins/".$skin."/".$file;
    $adminmain .= "<tr>";
	$adminmain .= "<td>".$file."</td>";

	if (file_exists($file))
       {
       $adminmain .= "<td style=\"background-color:".$color[1]."!important; text-align:center;\">Present</td>";
		$adminmain .= "<td style=\"text-align:right;\">".@filesize($file)."</td>";
       }
    else
       {
       $adminmain .= "<td style=\"background-color:".$color[0]."!important; text-align:center;\">Missing !</td>";
       $adminmain .= "<td style=\"text-align:right;\">0</td>";
       }
	$adminmain .= "</tr>";
	}

$adminmain .= "</table>";



?>
