<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/useragreement.contact.tags.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Displays user agreement and privacy policy checkboxes on contact form.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=useragreement
Part=contact
File=useragreement.contact.tags
Hooks=contact.tags
Tags=contact.tpl:{PLUGIN_CONTACT_AGREEMENT}
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
	if ($f = sed_langfile('useragreement', 'plugin')) {
		require_once($f);
	}

	$text = isset($L['useragreement_text_' . $mask]) ? $L['useragreement_text_' . $mask] : '';
	$text = str_replace(
		array('{UA_URL}', '{PP_URL}', '{DP_URL}'),
		array($ua_url, $pp_url, $dp_url),
		$text
	);

	$agreement_checkbox = sed_checkbox('ruseragreement', '1', false, false, array('style' => 'margin-top: 4px; flex-shrink: 0;'));
	$agreement_html = str_replace(' </label>', $text . '</label>', $agreement_checkbox);

	$t->assign("PLUGIN_CONTACT_AGREEMENT", $agreement_html);
}
