<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/smtp/smtp.setup.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=smtp
Name=SMTP Sender 3.0
Description=SMTP mail sender for Seditio 17x
Version=180
Date=2025-jan-31
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
smtp_host=02:string::smtp.test.ru:SMTP host
smtp_port=03:string::465:SMTP port
smtp_login=04:string::noreply@test.ru:SMTP login
smtp_pass=05:string::ZdvXSikP:SMTP password
smtp_from=06:string::noreply@test.ru:SMTP sender email
smtp_from_title=07:string::RobotMail:Mail From title
smtp_ssl=08:select:yes,no:yes:Use SSL (if no - TLC)?
smtp_debug=09:select:yes,no:yes:Use Debug with log file?
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
