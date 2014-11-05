<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/ckeditor/ckeditor.setup.php
Version=175
Updated=2014-oct-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Name=Ckeditor
Description=Ckeditor for Seditio (Universal textarea JS connector) 
Version=4.2.1
Date=2014-oct-30
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=0
Lock_guests=RW12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
ckeditor_skin=01:select:moono,moonocolor:moono:Ckeditor skin
ckeditor_detectlang=01:select:Yes,No:Yes:Detect language interface from user profile
ckeditor_lang=02:select:en,ru,zh,hr,cs,da,nl,et,fi,fr,ka,de,el,hu,it,ja,ko,no,pl,sv,tr,uk:en:Ckeditor default language
ckeditor_color_toolbar=03:select:#FFC4C4,#FFAD69,#FFCD69,#FFE569,#FFFF69,#BFEE62,#99F299,#91E6E6,#C2CEEA,#B19FEB,#CD98EA,#F299CC:#C2CEEA:Color Toolbar
ckeditor_other_textarea=04:select:Yes,No:No:Use Ckeditor for Other textarea
ckeditor_other_toolbar=05:select:Micro,Basic,Extended:Basic:Default toolbar for Other textarea
newpagetext=06:select:Micro,Basic,Extended:Extended:Default toolbar for Page Add textarea
rpagetext=07:select:Micro,Basic,Extended:Extended:Default toolbar for Page Edit textarea
newpmtext=08:select:Micro,Basic,Extended:Basic:Default toolbar for PM textarea
rusertext=09:select:Micro,Basic,Extended:Micro:Default toolbar for User Text textarea 
rtext=10:select:Micro,Basic,Extended:Micro:Default toolbar for Comments textarea
newmsg=11:select:Micro,Basic,Extended:Basic:Default toolbar for Forums textarea 
newpagetext_height=12:string::400:Height for Page Add textarea
rpagetext_height=13:string::400:Height for Page Edit textarea
newpmtext_height=14:string::200:Height for PM textarea
rusertext_height=15:string::150:Height for User Text textarea
rtext_height=16:string::150:Height for Comments textarea
newmsg_height=17:string::200:Height for Forums textarea
ckeditor_other_height=18:string::200:Height for Other textarea
auto_popup_close=19:select:Yes,No:No:Close window after paste image/file
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
