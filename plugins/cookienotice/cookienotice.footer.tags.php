<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/cookienotice.footer.tags.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Injects cookie notice HTML into the footer template.
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=cookienotice
Part=footer
File=cookienotice.footer.tags
Hooks=footer.tags
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Do not show the cookies notice in the administration panel
if (defined('SED_ADMIN')) {
	return;
}

// Load language strings
if ($f = sed_langfile('cookienotice', 'plugin')) {
	include($f);
}

// Retrieve config options with fallback for compatibility
$stat_url = isset($cfg['plugin']['cookienotice']['cookie_url_stat']) ? trim($cfg['plugin']['cookienotice']['cookie_url_stat']) : '';
$policy_url = isset($cfg['plugin']['cookienotice']['cookie_url_policy']) ? trim($cfg['plugin']['cookienotice']['cookie_url_policy']) : '';
$cookie_text = isset($cfg['plugin']['cookienotice']['cookie_text']) ? trim($cfg['plugin']['cookienotice']['cookie_text']) : '';

// If configured text is empty, fall back to language file default
if (empty($cookie_text)) {
	$cookie_text = isset($L['cookienotice_text']) ? $L['cookienotice_text'] : '';
}

// Replace placeholders with URLs
$cookie_text = str_replace(
	array('{STAT_URL}', '{POLICY_URL}'),
	array($stat_url, $policy_url),
	$cookie_text
);

// Get button labels from language file with generic fallbacks
$btn_accept = isset($L['cookienotice_btn_accept']) ? $L['cookienotice_btn_accept'] : 'Accept all';
$btn_close = isset($L['cookienotice_btn_close']) ? $L['cookienotice_btn_close'] : 'Close';

// Construct the HTML code
$cookies_html = '
<div class="cookie-notice hidden" id="cookieNotice">
	<p>' . $cookie_text . '</p>
	<div class="cookie-buttons">
		<button class="btn" onclick="acceptCookies()">' . $btn_accept . '</button>
		<button class="btn close-btn" onclick="closeCookies()">' . $btn_close . '</button>
	</div>
</div>
';

// Assign HTML to footer template tag
$t->assign("FOOTER_COOKIENOTICE", $cookies_html);
