<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sitemap/lang/sitemap.tr.lang.php
Version=185
Updated=2026-mar-31
Type=Plugin.lang
Author=Seditio Team
Description=Sitemap Turkish language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_sitemap'] = "Site haritası";

$L['cfg_disable_sitemap_pages'] = array("Sayfalar ve listeler için site haritasını devre dışı bırak", "");
$L['cfg_disable_sitemap_forums'] = array("Forumlar için site haritasını devre dışı bırak", "");
$L['cfg_sm_pages_changefreq'] = array("Sayfa changefreq", "always/hourly/daily/weekly/monthly/yearly/never");
$L['cfg_sm_pages_priority'] = array("Sayfa önceliği", "0.0–1.0");
$L['cfg_sm_pages_limit'] = array("Sayfa limiti", "Sayfa haritasındaki maks. URL");
$L['cfg_sm_lists_changefreq'] = array("Liste changefreq", "");
$L['cfg_sm_lists_priority'] = array("Liste önceliği", "");
$L['cfg_sm_lists_limit'] = array("Liste limiti", "");
$L['cfg_sm_index_changefreq'] = array("İndeks changefreq", "");
$L['cfg_sm_index_priority'] = array("İndeks önceliği", "");
$L['cfg_sm_forums_changefreq'] = array("Forum changefreq", "");
$L['cfg_sm_forums_priority'] = array("Forum önceliği", "");
$L['cfg_sm_forums_limit'] = array("Forum limiti", "");

$L['adm_help_config_sitemap'] = "XML site haritası URL'leri (SEO URL açıkken): <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/sitemap.xml (dizin) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/sitemap_pages.xml, sitemap_lists.xml, sitemap_forums.xml, sitemap_index.xml";
