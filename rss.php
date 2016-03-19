<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=rss.php
Version=177
Updated=2015-feb-06
Type=Core
Author=Seditio Team
Description=Rss Creator
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_RSS', TRUE);
$location = 'Rss';
$z = 'rss';

require('system/functions.php');
@include('datas/config.php');
require('system/common.php');

require('system/core/rss/rss.inc.php');

?>