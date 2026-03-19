<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/lang/thanks.en.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['thanks_thanks'] = '<i class="ic-thumb-up"></i> Thank you!';
$L['thanks_thanks_title'] = 'Thank you!';
$L['thanks_done'] = 'You thanked the author';
$L['thanks_title'] = 'Thanks to users';
$L['thanks_title_short'] = 'Thanks';
$L['thanks_title_user'] = 'Thanks received by user';
$L['thanks_err_maxday'] = 'Sorry, you cannot give more thanks today';
$L['thanks_err_maxuser'] = 'Sorry, you cannot thank this user again today';
$L['thanks_err_item'] = 'Sorry, you have already thanked for this item';
$L['thanks_err_self'] = 'You cannot thank yourself';
$L['thanks_none'] = 'No thanks';
$L['thanks_users_none'] = 'No users with thanks';
$L['thanks_date'] = 'Date';
$L['thanks_from'] = 'From';
$L['thanks_to'] = 'To';
$L['thanks_item'] = 'Item';
$L['thanks_category'] = 'Category';
$L['thanks_for'] = 'For';
$L['thanks_type_page'] = 'Page';
$L['thanks_type_post'] = 'Post';
$L['thanks_type_comment'] = 'Comment';
$L['thanks_total'] = 'Total';
$L['thanks_thanked'] = 'Thanked';
$L['thanks_thanked_times'] = 'Thanked: %s times';
$L['thanks_etc'] = '...';
$L['thanks_notify_pm_subject'] = 'You received a thank';
$L['thanks_notify_pm_body'] = "User %1\$s thanked you.\nView details: %2\$s";
$L['thanks_notify_email_subject'] = 'You received a thank';
$L['thanks_notify_email_body'] = "Hello %1\$s,\n\nUser %2\$s thanked you.\nView details: %3\$s";

/* Config */
$L['cfg_maxday'] = array("Max thanks a user can give per day", "");
$L['cfg_maxuser'] = array("Max thanks per day to one user", "");
$L['cfg_maxthanked'] = array("How many thankers to show (0=all)", "");
$L['cfg_page_on'] = array("Thanks for pages", "");
$L['cfg_forums_on'] = array("Thanks for forum posts", "");
$L['cfg_comments_on'] = array("Thanks for comments", "");
$L['cfg_short'] = array("Short format (names only)", "");
$L['cfg_notify_by_pm'] = array("Notify by PM on new thank", "");
$L['cfg_notify_by_email'] = array("Notify by email on new thank", "");
$L['cfg_notify_from'] = array("Email from for notifications", "");
$L['cfg_thanksperpage'] = array("Thanks per page in lists", "");
$L['cfg_format'] = array("Date format mask (empty = system default)", "");
$L['cfg_css'] = array("Include plugin CSS", "");
