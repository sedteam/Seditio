<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/robots/robots.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=robots
Part=main
File=robots
Hooks=common.plug.robots
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

$morerules = $cfg['plugin']['robots']['morerules'];
$noindex = ($cfg['plugin']['robots']['noindex'] == 'yes') ? true : false;
$sitemap = ($cfg['plugin']['robots']['sitemap'] == 'yes') ? true : false;

if (!isset($sed_robots_collection)) {
	$sed_robots_collection = array();
}

if (!empty($morerules) && is_string($morerules)) {
	$morerules_array = array_filter(explode("\n", trim($morerules)));
	if (!empty($morerules_array)) {
		$sed_robots_collection = array_merge($sed_robots_collection, $morerules_array);
	}
}

if ($sitemap) {
	$sed_robots_collection[] = "\nSitemap: " . $sys['abs_url'] . "sitemap.xml";
}

header('Content-Type: text/plain; charset=' . $cfg['charset']);
echo sed_generate_robots($noindex);
exit;
