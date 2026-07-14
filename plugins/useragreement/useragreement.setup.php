<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/useragreement.setup.php
Version=1.0.0
Updated=2026-jul-13
Type=Plugin
Author=Seditio Team
Description=Plugin to display user agreement, privacy policy, and personal data processing checkboxes on registration form.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=useragreement
Name=User Agreement
Description=Displays user agreement, privacy policy, and personal data processing checkboxes on registration form.
Version=1.0.0
Date=2026-jul-13
Author=Seditio Team
Copyright=Copyright (c) Seditio Team
Notes=
SQL=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
user_agreement_url=01:string:::User Agreement Document URL
privacy_policy_url=02:string:::Privacy Policy Document URL
data_processing_url=03:string:::Personal Data Processing Consent Document URL
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}
