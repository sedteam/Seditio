<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/contact/contact.setup.php
Version=150
Updated=2015-feb-06
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=contact
Name=Contact
Description=Web based email form
Version=177
Date=2010-feb-05
Author=Neocrome
Copyright=
Notes=
SQL=
Auth_guests=RW
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
emails=1:text:::Emails, separated by commas
recipients=2:text:::Names of the recipients, separated by commas, in the same order as the emails
admincopy1=3:string:::Also send a copy to this email
admincopy2=4:string:::Also send a copy to this email 
extra1=5:text:::Extra slot #1 / {PLUGIN_CONTACT_EXTRA1} in skins/.../plugin.standalone.contact.tpl
extra2=6:text:::Extra slot #2 / {PLUGIN_CONTACT_EXTRA2} in skins/.../plugin.standalone.contact.tpl
extra3=7:text:::Extra slot #3 / {PLUGIN_CONTACT_EXTRA2} in skins/.../plugin.standalone.contact.tpl
[END_SED_EXTPLUGIN_CONFIG]
 
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
