<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=forums.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Forums loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_FORUMS', TRUE);
$location = 'Forums';
$z = 'forums';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

sed_dieifdisabled($cfg['disable_forums']);

switch ($m) {
	case 'topics':
		require(SED_ROOT . '/system/core/forums/forums.topics.inc.php');
		break;

	case 'posts':
		require(SED_ROOT . '/system/core/forums/forums.posts.inc.php');
		break;

	case 'editpost':
		require(SED_ROOT . '/system/core/forums/forums.editpost.inc.php');
		break;

	case 'newtopic':
		require(SED_ROOT . '/system/core/forums/forums.newtopic.inc.php');
		break;

	default:
		require(SED_ROOT . '/system/core/forums/forums.inc.php');
		break;
}
