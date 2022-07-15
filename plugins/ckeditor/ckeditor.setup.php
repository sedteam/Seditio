<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/ckeditor/ckeditor.setup.php
Version=179
Updated=2021-nov-16
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Name=Ckeditor
Description=Ckeditor for Seditio (Universal textarea JS connector) 
Version=4.16.2
Date=2021-dec-30
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
ckeditor_skin=01:select:moono,moonocolor,moono-lisa:moono:Ckeditor skin
ckeditor_detectlang=02:select:Yes,No:Yes:Detect language interface from user profile
ckeditor_lang=03:select:en,ru,zh,hr,cs,da,nl,et,fi,fr,ka,de,el,hu,it,ja,ko,no,pl,sv,tr,uk:en:Ckeditor default language
ckeditor_color_toolbar=04:select:#FFC4C4,#FFAD69,#FFCD69,#FFE569,#FFFF69,#BFEE62,#99F299,#91E6E6,#C2CEEA,#B19FEB,#CD98EA,#F299CC:#C2CEEA:Color Toolbar
auto_popup_close=05:select:Yes,No:No:Close window after paste image/file
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
