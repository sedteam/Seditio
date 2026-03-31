<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sitemap/lang/sitemap.ru.lang.php
Version=185
Updated=2026-mar-31
Type=Plugin.lang
Author=Seditio Team
Description=Sitemap Russian language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_sitemap'] = "Карта сайта";

$L['cfg_disable_sitemap_pages'] = array("Отключить карту для страниц и списков", "");
$L['cfg_disable_sitemap_forums'] = array("Отключить карту для форумов", "");
$L['cfg_sm_pages_changefreq'] = array("Частота изменений страниц", "always/hourly/daily/weekly/monthly/yearly/never");
$L['cfg_sm_pages_priority'] = array("Приоритет страниц", "0.0–1.0");
$L['cfg_sm_pages_limit'] = array("Лимит страниц", "Макс. URL в карте страниц");
$L['cfg_sm_lists_changefreq'] = array("Частота изменений списков", "");
$L['cfg_sm_lists_priority'] = array("Приоритет списков", "");
$L['cfg_sm_lists_limit'] = array("Лимит списков", "");
$L['cfg_sm_index_changefreq'] = array("Частота изменений индекса", "");
$L['cfg_sm_index_priority'] = array("Приоритет индекса", "");
$L['cfg_sm_forums_changefreq'] = array("Частота изменений форумов", "");
$L['cfg_sm_forums_priority'] = array("Приоритет форумов", "");
$L['cfg_sm_forums_limit'] = array("Лимит форумов", "");

$L['adm_help_config_sitemap'] = "Адреса XML-карты (при ЧПУ): <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/sitemap.xml (индекс) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/sitemap_pages.xml, sitemap_lists.xml, sitemap_forums.xml, sitemap_index.xml";
