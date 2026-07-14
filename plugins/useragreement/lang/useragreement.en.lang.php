<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/lang/useragreement.en.lang.php
Version=1.0.0
Updated=2026-jul-13
Type=Plugin
Author=Seditio Team
Description=English language file for useragreement plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_user_agreement_url'] = array("User Agreement URL", "Leave empty if not used");
$L['cfg_privacy_policy_url'] = array("Privacy Policy URL", "Leave empty if not used");
$L['cfg_data_processing_url'] = array("Consent to Personal Data Processing URL", "Leave empty if not used");

// Text variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_text_1'] = 'I accept the terms of the <a href="{UA_URL}" target="_blank">User Agreement</a>.';
// Mask 2: PP
$L['useragreement_text_2'] = 'I have read and agree to the <a href="{PP_URL}" target="_blank">Privacy Policy</a>.';
// Mask 3: UA + PP
$L['useragreement_text_3'] = 'I accept the terms of the <a href="{UA_URL}" target="_blank">User Agreement</a> and agree to the <a href="{PP_URL}" target="_blank">Privacy Policy</a>.';
// Mask 4: DP
$L['useragreement_text_4'] = 'I give my <a href="{DP_URL}" target="_blank">consent to the processing of my personal data</a>.';
// Mask 5: UA + DP
$L['useragreement_text_5'] = 'I accept the terms of the <a href="{UA_URL}" target="_blank">User Agreement</a> and give my <a href="{DP_URL}" target="_blank">consent to the processing of my personal data</a>.';
// Mask 6: PP + DP
$L['useragreement_text_6'] = 'I give my <a href="{DP_URL}" target="_blank">consent to the processing of my personal data</a> and agree to the <a href="{PP_URL}" target="_blank">Privacy Policy</a>.';
// Mask 7: UA + PP + DP
$L['useragreement_text_7'] = 'I accept the terms of the <a href="{UA_URL}" target="_blank">User Agreement</a>, <a href="{PP_URL}" target="_blank">Privacy Policy</a> and give my <a href="{DP_URL}" target="_blank">consent to the processing of my personal data</a>.';

// Error variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_error_1'] = 'You must accept the terms of the User Agreement.';
// Mask 2: PP
$L['useragreement_error_2'] = 'You must agree to the Privacy Policy.';
// Mask 3: UA + PP
$L['useragreement_error_3'] = 'You must accept the terms of the User Agreement and agree to the Privacy Policy.';
// Mask 4: DP
$L['useragreement_error_4'] = 'You must give consent to the processing of personal data.';
// Mask 5: UA + DP
$L['useragreement_error_5'] = 'You must accept the terms of the User Agreement and give consent to the processing of personal data.';
// Mask 6: PP + DP
$L['useragreement_error_6'] = 'You must give consent to the processing of personal data and agree to the Privacy Policy.';
// Mask 7: UA + PP + DP
$L['useragreement_error_7'] = 'You must accept the terms of the User Agreement, Privacy Policy and give consent to the processing of personal data.';
