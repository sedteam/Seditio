<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/lang/cookienotice.tr.lang.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Turkish language file for cookienotice plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_cookie_text'] = array("Çerez bildirim metni", "Varsayılan dil metnini kullanmak için boş bırakın. Yer tutucuları destekler: {STAT_URL} — istatistik sayfası bağlantısı, {POLICY_URL} — gizlilik politikası bağlantısı.");
$L['cfg_cookie_url_stat'] = array("İstatistik servisleri sayfası bağlantısı", "Örnek: /sborstat");
$L['cfg_cookie_url_policy'] = array("Gizlilik Politikası sayfası bağlantısı", "Örnek: /policy");

// Front-end text
$L['cookienotice_text'] = 'Web sitemizin performansını artırmak amacıyla <a href="{STAT_URL}">çerezleri ve istatistik servislerini</a> kullanıyoruz. Tüm çerezleri kabul edebilir veya kullanım şartlarını <a href="{POLICY_URL}">Gizlilik Politikamıza</a> uygun olarak yapılandırabilirsiniz.';
$L['cookienotice_btn_accept'] = 'Tümünü kabul et';
$L['cookienotice_btn_close'] = 'Reddet';
