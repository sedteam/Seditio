<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.inc.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$c = sed_import('c','G','TXT');
$id = sed_import('id','G','TXT');
$po = sed_import('po','G','TXT');
$p = sed_import('p','G','TXT');
$l = sed_import('l','G','TXT');
$o = sed_import('o','P','TXT');
$w = sed_import('w','P','TXT');
$u = sed_import('u','P','TXT');
$s = sed_import('s','G','ALP', 24);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'any');
sed_block($usr['auth_read']);

$enabled[0] = $L['Disabled'];
$enabled[1] = $L['Enabled'];

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

$sys['inc'] = (empty($m)) ? 'admin.home' : "admin.$m";
$sys['inc'] = (empty($s)) ? $sys['inc'] : $sys['inc'].".$s";
$sys['inc'] = 'system/core/admin/'.$sys['inc'].'.inc.php';

if (!file_exists($sys['inc']))
	{ sed_die(); }

$allow_img['0']['0'] = "<img src=\"system/img/admin/deny.gif\" alt=\"\" />";
$allow_img['1']['0'] = "<img src=\"system/img/admin/allow.gif\" alt=\"\" />";
$allow_img['0']['1'] = "<img src=\"system/img/admin/deny_locked.gif\" alt=\"\" />";
$allow_img['1']['1'] = "<img src=\"system/img/admin/allow_locked.gif\" alt=\"\" />";

$adminmenu = "<table style=\"width:100%;\"><tr>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\"><a href=\"".sed_url("admin")."\">";
$adminmenu .= "<img src=\"system/img/admin/admin.png\" alt=\"\" /><br />".$L['Home']."</a></td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=config"), "<img src=\"system/img/admin/config.png\" alt=\"\" /><br />".$L['Configuration'], sed_auth('admin', 'a', 'A'));
$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=page"), "<img src=\"system/img/admin/page.png\" alt=\"\" /><br />".$L['Pages'], sed_auth('page', 'any', 'A'));
$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=forums"), "<img src=\"system/img/admin/forums.png\" alt=\"\" /><br />".$L['Forums'], sed_auth('admin', 'a', 'A'));
$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=users"), "<img src=\"system/img/admin/users.png\" alt=\"\" /><br />".$L['Users'], sed_auth('users', 'a', 'A'));
$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\"><a href=\"".sed_url("admin", "m=tools")."\">";
$adminmenu .= "<img src=\"system/img/admin/manage.png\" alt=\"\" /><br />".$L['adm_manage']."</a></td>";

$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=plug"), "<img src=\"system/img/admin/plugins.png\" alt=\"\" /><br />".$L['Plugins'], sed_auth('admin', 'a', 'A'));
$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=trashcan"), "<img src=\"system/img/admin/trash.png\" alt=\"\" /><br />".$L['Trashcan'], sed_auth('admin', 'a', 'A'));
$adminmenu .= "</td>";

$adminmenu .= "</td>";
$adminmenu .= "<td style=\"width:11%; text-align:center;\">";
$adminmenu .= sed_linkif(sed_url("admin", "m=log"), "<img src=\"system/img/admin/log.png\" alt=\"\" /><br />".$L['adm_log'], sed_auth('admin', 'a', 'A'));
$adminmenu .= "</td>";

$adminmenu .= "</tr></table>";

require($sys['inc']);

$adminmain .= (empty($adminhelp)) ? '' : "<h4>".$L['Help']." :</h4>".$adminhelp;


$out['subtitle'] = $L['Administration'];

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('admintitle', $title_tags, $title_data);
/**/


require("system/header.php");

$t = new XTemplate("skins/".$skin."/admin.tpl");

$t->assign(array(
	"ADMIN_TITLE" => sed_build_adminsection($adminpath),
	"ADMIN_SUBTITLE" => $adminsubtitle,
	"ADMIN_MENU" => $adminmenu,
	"ADMIN_MAIN" => $adminmain,
		));

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require("system/footer.php");

?>