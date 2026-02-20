<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/lang/pm.en.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=PM English language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_pm'] = "Private messages";

$L['cfg_pmtitle'] = array("Title for PM", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_pm_maxsize'] = array("Maximum length for messages", "Default: 10000 chars");
$L['cfg_pm_allownotifications'] = array("Allow PM notifications by email", "");

$L['pm_titletooshort'] = "The title is too short or missing";
$L['pm_bodytooshort'] = "The body of the private message is too short or missing";
$L['pm_bodytoolong'] = "The body of the private message is too long, " . (isset($cfg['pm_maxsize']) ? $cfg['pm_maxsize'] : 10000) . " chars maximum";
$L['pm_wrongname'] = "At least one recipient was wrong, and so removed from the list";
$L['pm_toomanyrecipients'] = "%1\$s recipients maximum please";
$L['pmsend_title'] = "Send a new private message";
$L['pmsend_subtitle'] = "";
$L['pm_sendnew'] = "Send a new private message";
$L['pm_inbox'] = "In-box";
$L['pm_inboxsubtitle'] = "Private messages, newest is at top";
$L['pm_sentbox'] = "Sent-box";
$L['pm_sentboxsubtitle'] = "Messages sent and not yet displayed by the recipient";
$L['pm_archives'] = "Archives";
$L['pm_arcsubtitle'] = "Old messages, newest is at top";
$L['pm_replyto'] = "Reply to this user";
$L['pm_putinarchives'] = "Put in archives";
$L['pm_notifytitle'] = "New private message";
$L['pm_notify'] = "Hi %1\$s,\n\nYou are receiving this email because there is a new private message in your inbox.\nThe sender is : %2\$s\nClick this link to read it : %3\$s";
$L['pm_multiplerecipients'] = "This private messages was also sent to %1\$s other recipient(s).";
