<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/useragreement/lang/useragreement.ru.lang.php
Version=1.0.0
Updated=2026-jul-13
Type=Plugin
Author=Seditio Team
Description=Russian language file for useragreement plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_user_agreement_url'] = array("Ссылка на 'Пользовательское соглашение'", "Оставьте пустой, если не используется");
$L['cfg_privacy_policy_url'] = array("Ссылка на 'Политику конфиденциальности'", "Оставьте пустой, если не используется");
$L['cfg_data_processing_url'] = array("Ссылка на 'Согласие на обработку персональных данных'", "Оставьте пустой, если не используется");

// Text variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_text_1'] = 'Я принимаю условия <a href="{UA_URL}" target="_blank">Пользовательского соглашения</a>.';
// Mask 2: PP
$L['useragreement_text_2'] = 'Я ознакомлен с <a href="{PP_URL}" target="_blank">Политикой конфиденциальности</a>.';
// Mask 3: UA + PP
$L['useragreement_text_3'] = 'Я принимаю условия <a href="{UA_URL}" target="_blank">Пользовательского соглашения</a> и ознакомлен с <a href="{PP_URL}" target="_blank">Политикой конфиденциальности</a>.';
// Mask 4: DP
$L['useragreement_text_4'] = 'Даю <a href="{DP_URL}" target="_blank">согласие на обработку моих персональных данных</a>.';
// Mask 5: UA + DP
$L['useragreement_text_5'] = 'Я принимаю условия <a href="{UA_URL}" target="_blank">Пользовательского соглашения</a> и даю <a href="{DP_URL}" target="_blank">согласие на обработку моих персональных данных</a>.';
// Mask 6: PP + DP
$L['useragreement_text_6'] = 'Даю <a href="{DP_URL}" target="_blank">согласие на обработку моих персональных данных</a>, с <a href="{PP_URL}" target="_blank">политикой конфиденциальности</a> ознакомлен.';
// Mask 7: UA + PP + DP
$L['useragreement_text_7'] = 'Я принимаю условия <a href="{UA_URL}" target="_blank">Пользовательского соглашения</a>, <a href="{PP_URL}" target="_blank">Политики конфиденциальности</a> и даю <a href="{DP_URL}" target="_blank">согласие на обработку персональных данных</a>.';

// Error variants (1-7 depending on active settings)
// Mask 1: UA
$L['useragreement_error_1'] = 'Вы должны принять условия Пользовательского соглашения.';
// Mask 2: PP
$L['useragreement_error_2'] = 'Вы должны ознакомиться с Политикой конфиденциальности.';
// Mask 3: UA + PP
$L['useragreement_error_3'] = 'Вы должны принять условия Пользовательского соглашения и ознакомиться с Политикой конфиденциальности.';
// Mask 4: DP
$L['useragreement_error_4'] = 'Вы должны дать согласие на обработку персональных данных.';
// Mask 5: UA + DP
$L['useragreement_error_5'] = 'Вы должны принять условия Пользовательского соглашения и дать согласие на обработку персональных данных.';
// Mask 6: PP + DP
$L['useragreement_error_6'] = 'Вы должны дать согласие на обработку персональных данных и ознакомиться с Политикой конфиденциальности.';
// Mask 7: UA + PP + DP
$L['useragreement_error_7'] = 'Вы должны принять условия Пользовательского соглашения, Политики конфиденциальности и дать согласие на обработку персональных данных.';
