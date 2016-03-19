<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/passrecover/passrecover.setup.php
Version=177
Updated=2015-feb-06
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=passrecover
Name=Password recovery
Description=Sends emails to users so they can recover their lost passwords
Version=177
Date=2012-okt-04
Author=Neocrome & Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
generate_password=01:select:yes,no:no:Generate a new password and send to the email?
[END_SED_EXTPLUGIN_CONFIG]


==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
