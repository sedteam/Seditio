<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/lang/rss.ru.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=RSS Russian language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_rss'] = "RSS-ленты";

$L['cfg_disable_rss'] = array("Отключить RSS-ленты", "");
$L['cfg_disable_rsspages'] = array("Отключить RSS для страниц", "");
$L['cfg_disable_rsscomments'] = array("Отключить RSS для комментариев", "");
$L['cfg_rss_timetolive'] = array("Время кэширования RSS", "в секундах");
$L['cfg_rss_defaultcode'] = array("RSS по умолчанию", "код категории");
$L['cfg_rss_maxitems'] = array("Максимум записей в RSS-ленте", "");

$L['rss_commentauthor'] = "Комментарии пользователей";
$L['rss_lastcomments'] = "Последние комментарии";
$L['rss_lastforums'] = "Последнее на форумах";
$L['rss_lastsections'] = "Последние сообщения в разделе: ";
$L['rss_lasttopics'] = "Последнее сообщение в теме: ";

$L['adm_help_config_rss'] = "Ссылки на RSS-ленты: <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss (категория по умолчанию из настроек) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/pages?c=XX (XX = код категории) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/comments?id=XX (XX = ID страницы) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums (последние сообщения) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?s=XX (ID раздела) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?q=XX (ID темы)";
