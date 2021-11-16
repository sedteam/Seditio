<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=message.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Messages loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_MESSAGE', TRUE);
$location = 'Messages';
$z = 'message';

require('system/functions.php');
require('datas/config.php');
require('system/common.php');

switch($m)
	{
	default:
	require('system/core/message/message.inc.php');
	break;
	}

?>
