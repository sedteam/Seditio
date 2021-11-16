<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=rss.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Seditio Team
Description=Rss Creator
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_RSS', TRUE);
$location = 'Rss';
$z = 'rss';

require('system/functions.php');
@include('datas/config.php');
require('system/common.php');

require('system/core/rss/rss.inc.php');

?>