<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer3/textboxer3.setup.php
Version=177
Updated=2014-nov-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer3
Name=Textboxer 3.0
Description=Universal BBCode & HTML Editor
Version=177
Updated=2014-nov-29
Author=Amro
Copyright=Amro
Notes=Textboxer 3.0 is the bbcode & html editor for Seditio.
SQL=
Auth_guests=0
Lock_guests=RW12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
tb3_detectlang=01:select:Yes,No:Yes:Detect language interface from user profile
tb3_lang=02:select:en,ru:en:Textboxer3 default language
tb3_other_textarea=03:select:Yes,No:No:Use Textboxer3 for other textarea
tb3_toolbar_pages=04:select:Micro,Medium,Full:Full:Toolbar for Pages textarea
tb3_pages_height=05:string::200:Height for pages textarea
tb3_toolbar_forums=06:select:Micro,Medium,Full:Medium:Toolbar for Forums textarea
tb3_forums_height=07:string::200:Height for forums textarea
tb3_toolbar_comments=08:select:Micro,Medium,Full:Micro:Toolbar for Comments textarea
tb3_comments_height=09:string::200:Height for forums textarea
tb3_toolbar_users=10:select:Micro,Medium,Full:Micro:Toolbar for Users textarea
tb3_users_height=11:string::200:Height for forums textarea
tb3_toolbar_pm=12:select:Micro,Medium,Full:Medium:Toolbar for PM textarea
tb3_pm_height=13:string::200:Height for forums textarea
tb3_toolbar_other=14:select:Micro,Medium,Full:Medium:Toolbar for other textarea
tb3_other_height=15:string::200:Height for other textarea
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
