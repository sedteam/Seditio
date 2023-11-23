<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=view.php
Version=179
Updated=2022-jul-15
Type=Core
Author=Seditio Team
Description=View loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_VIEW', TRUE);
$location = 'Views';
$z = 'view';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

switch ($m) {
	default:
		require(SED_ROOT . '/system/core/view/view.inc.php');
		break;
}
