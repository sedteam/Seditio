<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=admin.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Administration panel loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_ADMIN', TRUE);
$location = 'Administration';
$z = 'admin';
$adminskin = true;

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/system/functions.admin.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');
require("system/lang/".$usr['lang']."/admin.lang.php");
require("system/core/admin/admin.inc.php");

?>