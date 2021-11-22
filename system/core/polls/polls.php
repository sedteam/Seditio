<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=polls.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Polls
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_POLLS', TRUE);
$location = 'Polls';
$z = 'polls';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_polls']);

switch($m)
	{
	default:
	require(SED_ROOT . '/system/core/polls/polls.inc.php');
	break;
	}

?>
