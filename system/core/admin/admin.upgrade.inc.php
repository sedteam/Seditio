<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.upgrade.inc.php
Version=173
Updated=2012-sep-23
Type=Core.upgrade
Author=Neocrome
Description=Users
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }


list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array ("admin.php?m=upgrade", $L['upg_upgrade']);

$cfg['sqlversion'] = sed_stat_get('version');
$upg_file = "system/upgrade/upgrade_".$cfg['sqlversion']."_".$cfg['version'].".php";

if ($cfg['version'] <= $cfg['sqlversion'] || !file_exists($upg_file))
	{
  header("Location: admin.php");
	exit;
  }

$adminmain .= $cfg['sqlversion']." --> ".$cfg['version']."<br />".$L['File']." : ".$upg_file."<br />";

$upg_status = FALSE;
require($upg_file);

$adminmain .= ($upg_status) ? "<a href=\"admin.php\">".$L['upg_success']."</a>" : "<a href=\"admin.php\">".$L['upg_failure']."</a>"; 


?>