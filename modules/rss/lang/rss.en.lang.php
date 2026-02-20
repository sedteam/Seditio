<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/rss/lang/rss.en.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=RSS English language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_rss'] = "RSS feeds";

$L['cfg_disable_rss'] = array("Disable RSS feeds", "");
$L['cfg_disable_rsspages'] = array("Disable RSS feed for pages", "");
$L['cfg_disable_rsscomments'] = array("Disable RSS feed for comments", "");
$L['cfg_rss_timetolive'] = array("Cache time for RSS feed", "in seconds");
$L['cfg_rss_defaultcode'] = array("Default RSS feed", "enter the category code");
$L['cfg_rss_maxitems'] = array("The maximum number of rows in the RSS feed", "");

$L['rss_commentauthor'] = "User Comments";
$L['rss_lastcomments'] = "Recent Comments";
$L['rss_lastforums'] = "Latest on forums";
$L['rss_lastsections'] = "Latest posts in the forum: ";
$L['rss_lasttopics'] = "Last post in the topic: ";

$L['adm_help_config_rss'] = "Links to open RSS feeds: <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss (default category from settings) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/pages?c=XX (XX = category code) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/comments?id=XX (XX = page ID) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums (latest posts) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?s=XX (section ID) <br />" . (isset($cfg['mainurl']) ? $cfg['mainurl'] : '') . "/rss/forums?q=XX (topic ID)";
