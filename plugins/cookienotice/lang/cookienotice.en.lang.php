<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/lang/cookienotice.en.lang.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=English language file for cookienotice plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_cookie_text'] = array("Cookie notice text", "Leave empty to use default language string. Supports placeholders: {STAT_URL} — link to statistics page, {POLICY_URL} — link to privacy policy.");
$L['cfg_cookie_url_stat'] = array("Link to statistic services page", "Example: /sborstat");
$L['cfg_cookie_url_policy'] = array("Link to Privacy Policy page", "Example: /policy");

// Front-end text
$L['cookienotice_text'] = 'To improve the website performance we use <a href="{STAT_URL}">cookies and statistic services</a>. You can accept all cookies or configure them in accordance with our <a href="{POLICY_URL}">Privacy Policy</a>.';
$L['cookienotice_btn_accept'] = 'Accept all';
$L['cookienotice_btn_close'] = 'Decline';
