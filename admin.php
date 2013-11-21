<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=admin.php
Version=173
Updated=2012-sep-23
Type=Core
Author=Neocrome
Description=Administration panel loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_ADMIN', TRUE);
$location = 'Administration';
$z = 'admin';

require('system/functions.php');
require('system/functions.admin.php');
require('datas/config.php');
require('system/common.php');
require("system/lang/".$usr['lang']."/admin.lang.php");
require("system/core/admin/admin.inc.php");

?>
