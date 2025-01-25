<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=list.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Pages loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_LIST', TRUE);
$location = 'Pages';
$z = 'page';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_page']);

switch ($m) {
	default:
		require(SED_ROOT . '/system/core/list/list.inc.php');
		break;
}
