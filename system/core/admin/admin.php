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

require('system/functions.php');
require('system/functions.admin.php');
require('datas/config.php');
require('system/common.php');
require("system/lang/".$usr['lang']."/admin.lang.php");
require("system/core/admin/admin.inc.php");

?>