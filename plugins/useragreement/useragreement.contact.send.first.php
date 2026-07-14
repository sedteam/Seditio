<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/useragreement.contact.send.first.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Validates user agreement checkbox during contact form submission.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=useragreement
Part=validation
File=useragreement.contact.send.first
Hooks=contact.send.first
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

$ua_url = isset($cfg['plugin']['useragreement']['user_agreement_url']) ? trim($cfg['plugin']['useragreement']['user_agreement_url']) : '';
$pp_url = isset($cfg['plugin']['useragreement']['privacy_policy_url']) ? trim($cfg['plugin']['useragreement']['privacy_policy_url']) : '';
$dp_url = isset($cfg['plugin']['useragreement']['data_processing_url']) ? trim($cfg['plugin']['useragreement']['data_processing_url']) : '';

$mask = 0;
if (!empty($ua_url)) { $mask += 1; }
if (!empty($pp_url)) { $mask += 2; }
if (!empty($dp_url)) { $mask += 4; }

if ($mask > 0) {
	$ruseragreement = sed_import('ruseragreement', 'P', 'INT');

	if ($ruseragreement !== 1) {
		if ($f = sed_langfile('useragreement', 'plugin')) {
			require_once($f);
		}

		$error_msg = isset($L['useragreement_error_' . $mask]) ? $L['useragreement_error_' . $mask] : '';
		$error_string .= (!empty($error_msg)) ? $error_msg . "<br />" : "You must accept the terms.<br />";
	}
}
