<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/lang/rss.tr.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=RSS Turkish language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_rss'] = "RSS beslemeleri";

$L['cfg_disable_rss'] = array("RSS beslemelerini devre dışı bırak", "");
$L['cfg_disable_rsspages'] = array("Sayfalar için RSS beslemesini devre dışı bırak", "");
$L['cfg_disable_rsscomments'] = array("Yorumlar için RSS beslemesini devre dışı bırak", "");
$L['cfg_rss_timetolive'] = array("RSS önbellek süresi", "saniye cinsinden");
$L['cfg_rss_defaultcode'] = array("Varsayılan RSS", "kategori kodu");
$L['cfg_rss_maxitems'] = array("RSS beslemesindeki maksimum kayıt sayısı", "");

$L['rss_commentauthor'] = "Kullanıcı yorumları";
$L['rss_lastcomments'] = "Son yorumlar";
$L['rss_lastforums'] = "Forumlarda son";
$L['rss_lastsections'] = "Bölümdeki son iletiler: ";
$L['rss_lasttopics'] = "Konudaki son ileti: ";

$L['adm_help_config_rss'] = "RSS beslemelerinin açılacağı bağlantılar: <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss (ayarlardan varsayılan kategori) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/pages?c=XX (XX = kategori kodu) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/comments?id=XX (XX = sayfa ID) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums (son iletiler) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?s=XX (bölüm ID) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?q=XX (konu ID)";
