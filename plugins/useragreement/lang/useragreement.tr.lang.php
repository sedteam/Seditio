<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/lang/useragreement.tr.lang.php
Version=1.0.0
Updated=2026-jul-13
Type=Plugin
Author=Seditio Team
Description=Turkish language file for useragreement plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_user_agreement_url'] = array("Kullanıcı Sözleşmesi URL'si", "Kullanılmıyorsa boş bırakın");
$L['cfg_privacy_policy_url'] = array("Gizlilik Politikası URL'si", "Kullanılmıyorsa boş bırakın");
$L['cfg_data_processing_url'] = array("Kişisel Verilerin İşlenmesi Rızası URL'si", "Kullanılmıyorsa boş bırakın");

// Text variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_text_1'] = '<a href="{UA_URL}" target="_blank">Kullanıcı Sözleşmesi</a> şartlarını kabul ediyorum.';
// Mask 2: PP
$L['useragreement_text_2'] = '<a href="{PP_URL}" target="_blank">Gizlilik Politikası</a>\'nı okudum ve kabul ediyorum.';
// Mask 3: UA + PP
$L['useragreement_text_3'] = '<a href="{UA_URL}" target="_blank">Kullanıcı Sözleşmesi</a> ve <a href="{PP_URL}" target="_blank">Gizlilik Politikası</a> şartlarını kabul ediyorum.';
// Mask 4: DP
$L['useragreement_text_4'] = '<a href="{DP_URL}" target="_blank">Kişisel verilerimin işlenmesine rıza gösteriyorum</a>.';
// Mask 5: UA + DP
$L['useragreement_text_5'] = '<a href="{UA_URL}" target="_blank">Kullanıcı Sözleşmesi</a> şartlarını kabul ediyor ve <a href="{DP_URL}" target="_blank">kişisel verilerimin işlenmesine rıza gösteriyorum</a>.';
// Mask 6: PP + DP
$L['useragreement_text_6'] = '<a href="{DP_URL}" target="_blank">Kişisel verilerimin işlenmesine rıza gösteriyor</a>, <a href="{PP_URL}" target="_blank">Gizlilik Politikası</a>\'nı kabul ediyorum.';
// Mask 7: UA + PP + DP
$L['useragreement_text_7'] = '<a href="{UA_URL}" target="_blank">Kullanıcı Sözleşmesi</a>, <a href="{PP_URL}" target="_blank">Gizlilik Politikası</a> şartlarını kabul ediyor ve <a href="{DP_URL}" target="_blank">kişisel verilerimin işlenmesine rıza gösteriyorum</a>.';

// Error variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_error_1'] = 'Kullanıcı Sözleşmesi şartlarını kabul etmelisiniz.';
// Mask 2: PP
$L['useragreement_error_2'] = 'Gizlilik Politikası\'nı kabul etmelisiniz.';
// Mask 3: UA + PP
$L['useragreement_error_3'] = 'Kullanıcı Sözleşmesi ve Gizlilik Politikası şartlarını kabul etmelisiniz.';
// Mask 4: DP
$L['useragreement_error_4'] = 'Kişisel verilerin işlenmesine rıza göstermelisiniz.';
// Mask 5: UA + DP
$L['useragreement_error_5'] = 'Kullanıcı Sözleşmesi şartlarını kabul etmeli ve kişisel verilerin işlenmesine rıza göstermelisiniz.';
// Mask 6: PP + DP
$L['useragreement_error_6'] = 'Kişisel verilerin işlenmesine rıza göstermeli ve Gizlilik Politikası\'nı kabul etmelisiniz.';
// Mask 7: UA + PP + DP
$L['useragreement_error_7'] = 'Kullanıcı Sözleşmesi, Gizlilik Politikası şartlarını kabul etmeli ve kişisel verilerin işlenmesine rıza göstermelisiniz.';
