<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.upgrade.inc.php
Version=178
Updated=2022-jun-12
Type=Core.upgrade
Author=Neocrome
Description=Users
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }


list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

$adminpath[] = array (sed_url("admin", "m=upgrade"), $L['upg_upgrade']);

$cfg['sqlversion'] = sed_stat_get('version');
$upg_file = SED_ROOT."/system/upgrade/upgrade_".$cfg['sqlversion']."_".$cfg['version'].".php";

if ($cfg['version'] <= $cfg['sqlversion'] || !file_exists($upg_file))
	{
	sed_redirect(sed_url("admin", "", "", true));
	exit;
  }

$adminmain .= $cfg['sqlversion']." --> ".$cfg['version']."<br />".$L['File']." : ".$upg_file."<br />";

$upg_status = FALSE;
require($upg_file);

$adminmain .= ($upg_status) ? "<a href=\"".sed_url("admin")."\">".$L['upg_success']."</a>" : "<a href=\"".sed_url("admin")."\">".$L['upg_failure']."</a>"; 

?>