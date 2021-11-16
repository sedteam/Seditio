<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/smtp/smtp.setup.php
Version=178
Updated=2017-dec-09
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=smtp
Name=SMTP Sender 1.0
Description=SMTP mail sender for Seditio 17x
Version=178
Date=2017-dec-09
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
smtp_active=01:select:yes,no:no:Enable SMTP mail sender
smtp_host=02:string::mail.domain.com:SMTP host
smtp_port=03:string::25:SMTP port
smtp_login=04:string::noreply@domain.com:SMTP login
smtp_pass=05:string::your pass:SMTP password
smtp_from=06:string::noreply@domain.com:SMTP sender email
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>