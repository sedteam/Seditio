<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/lang/forums.en.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=Forums English language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ====== Forums ======= */

$L['Forums'] = "Forums";
$L['Topics'] = "Topics";
$L['Posts'] = "Posts";
$L['Post'] = "Post";
$L['Topic'] = "Topic";
$L['Replies'] = "Replies";
$L['Lastpost'] = "Last post";
$L['Started'] = "Started";
$L['Topicoptions'] = "Topic options";
$L['Topiclocked'] = "This topic is locked, new posts are not allowed.";
$L['Announcement'] = "Announcement";
$L['Bump'] = "Bump";
$L['Ghost'] = "Ghost";
$L['Lock'] = "Lock";
$L['Makesticky'] = "Sticky";
$L['Moved'] = "Moved";
$L['Poll'] = "Poll";
$L['Private'] = "Private";

$L['for_newtopic'] = "New topic";
$L['for_markallasread'] = "Mark all posts as read";
$L['for_titletooshort'] = "The title is too short or missing";
$L['for_msgtooshort'] = "Text of the topic is too short or missing";
$L['for_updatedby'] = "<br /><em>This post was edited by %1\$s (%2\$s, %3\$s ago)</em>";
$L['for_antibump'] = "The anti-bump protection is up, you cannot post twice in a row.";
$L['for_mod_clear'] = "Clear the ratings";
$L['for_mod_force'] = "Force the rating to ";
$L['for_quickpost'] = "Quick response";
$L['for_post_text'] = "Post text";

/* ====== Forums admin ======= */

$L['adm_forums'] = "Forums";
$L['adm_forums_main'] = "Structure of the forums";
$L['adm_autoprune'] = "Auto-prune topics after * days";
$L['adm_postcounters'] = "Check the counters";
$L['adm_help_forums'] = "Not available";
$L['adm_forum_structure'] = "Structure of the forums (categories)";
$L['adm_forum_structure_cat'] = "Structure of the forums";
$L['adm_help_forums_structure'] = "Not available";

/* ====== Config labels ======= */

$L['core_forums'] = "Forums";
$L['cfg_formatmonthdayhourmin'] = array("Forum date mask", "Default: m-d H:i");
$L['cfg_hideprivateforums'] = array("Hide private forums", "");
$L['cfg_hottopictrigger'] = array("Posts for a topic to be 'hot'", "");
$L['cfg_maxtopicsperpage'] = array("Maximum topics or posts per page", "");
$L['cfg_antibumpforums'] = array("Anti-bump protection", "Will prevent users from posting twice in a row in the same topic");
$L['cfg_disable_forums'] = array("Disable the forums", "");
$L['cfg_trash_forum'] = array("Use the trash can for the forums", "");
$L['cfg_forumstitle'] = array("Title for forums", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_disable_rssforums'] = array("Disable RSS feed for the forums", "");
