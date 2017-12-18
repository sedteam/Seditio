<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=install.setup.php
Version=177
Updated=2015-feb-06
Type=Core.install
Author=Neocrome
Description=Install setup
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_INSTALL') ) { die('Wrong URL.'); }

$cfg['config_file'] = "datas/config.php";
$cfg['data_root'] = "datas";
$cfg['default_skin'] = 'simple';

$cfg['sqldb'] = "mysql";

$steps[0] = $L['install_step0'];
$steps[1] = $L['install_step1'];
$steps[2] = $L['install_step2'];
$steps[3] = $L['install_step3'];
$steps[4] = $L['install_step4'];
$steps[5] = $L['install_step5'];

$rwfolders[] = "datas/defaultav";
$rwfolders[] = "datas/avatars";
$rwfolders[] = "datas/photos";
$rwfolders[] = "datas/signatures";
$rwfolders[] = "datas/thumbs";
$rwfolders[] = "datas/users";



function sed_selectbox_lang_install($check, $name)
	{
	global $sed_languages, $sed_countries;

	$handle = opendir("system/install/lang/");
	while ($f = readdir($handle))
		{
		if ($f[0] != '.')
			{ $langlist[] = $f; }
		}
	closedir($handle);
	sort($langlist);

	$result = "<select name=\"$name\" size=\"1\">";
	while(list($i,$x) = each($langlist))
		{
		$selected = ($x==$check) ? "selected=\"selected\"" : '';
		$lng = (empty($sed_languages[$x])) ? $sed_countries[$x] : $sed_languages[$x];
		$result .= "<option value=\"$x\" $selected>".$lng." (".$x.")</option>";
		}
	$result .= "</select>";

	return($result);
	}



       
?>