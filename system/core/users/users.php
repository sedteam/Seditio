<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=users.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Users loader
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

define('SED_USERS', TRUE);
$location = 'Users';
$z = 'users';

require(SED_ROOT . '/system/functions.php');
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');

switch($m)
	{
	case 'register':
	require(SED_ROOT . '/system/core/users/users.register.inc.php');
	break;

	case 'auth':
	require(SED_ROOT . '/system/core/users/users.auth.inc.php');
	break;

	case 'details':
	require(SED_ROOT . '/system/core/users/users.details.inc.php');
	break;

	case 'edit':
	require(SED_ROOT . '/system/core/users/users.edit.inc.php');
	break;

	case 'logout':
	require(SED_ROOT . '/system/core/users/users.logout.inc.php');
	break;

	case 'profile':
	require(SED_ROOT . '/system/core/users/users.profile.inc.php');
	break;

	default:
	require(SED_ROOT . '/system/core/users/users.inc.php');
	break;


	}

?>
