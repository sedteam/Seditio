<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/lang/contact.en.lang.php
Version=186
Updated=2026-sep-17
Type=
Author=Seditio Team
Description=
[END_SED]
==================== */

$L['plu_title'] = "Contact";

$L['plu_explain'] = "Fill this form to send us an email, we'll reply as soon as possible !";
$L['plu_recipients_title'] = "Recipient";
$L['plu_email_title'] = "Email";
$L['plu_name_title'] = "Name";
$L['plu_phone_title'] = "Phone number";
$L['plu_subject_title'] = "Subject";
$L['plu_message_title'] = "Message";
$L['plu_required'] = "* = Required";
$L['plu_verify'] = "Just to be sure you're human, please re-enter this number, without the dots : ";
$L['plu_send'] = "Send";

$L['plu_fieldempty'] = "At least one field is empty.";
$L['plu_wrongentry'] = "There's a mistake in at least one field.";
$L['plu_antispam'] = "The Spambot protection key was wrong, please retype it !";
$L['plu_notsent'] = "The message was NOT sent.";
$L['plu_sent'] = "Message successfully sent !";
$L['plu_notice'] = "This message was sent from " . (isset($cfg['maintitle']) ? $cfg['maintitle'] : "") . " by : ";

$L['cfg_emails'] = array("List of email addresses, comma-separated", "");
$L['cfg_recipients'] = array("Names of recipients, comma-separated, in order of email list", "");
$L['cfg_admincopy1'] = array("Send copy of message to email", "");
$L['cfg_admincopy2'] = array("Send copy of message to email", "");
$L['cfg_extra1'] = array("Extra slot #1 / {PLUGIN_CONTACT_EXTRA1} in skins/.../plugin.standalone.contact.tpl", "");
$L['cfg_extra2'] = array("Extra slot #2 / {PLUGIN_CONTACT_EXTRA2} in skins/.../plugin.standalone.contact.tpl", "");
$L['cfg_extra3'] = array("Extra slot #3 / {PLUGIN_CONTACT_EXTRA3} in skins/.../plugin.standalone.contact.tpl", "");
