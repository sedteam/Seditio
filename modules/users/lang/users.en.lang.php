<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/lang/users.en.lang.php
Version=185
Updated=2026-feb-21
Type=Module.lang
Author=Seditio Team
Description=Users English language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ====== Auth ====== */

$L['aut_usernametooshort'] = "The user name must be at least 2 chars long";
$L['aut_passwordtooshort'] = "The password must be at least 4 chars long and must consist of alphanumerical characters and underscore only.";
$L['aut_emailtooshort'] = "The email is not valid.";
$L['aut_usernamealreadyindb'] = "The user name you provided is already in the database";
$L['aut_emailalreadyindb'] = "The email you provided is already in the database";
$L['aut_passwordmismatch'] = "The password fields do not match !";
$L['aut_emailbanned'] = "This email (or this host) is banned, reason is : ";

$L['aut_contactadmin'] = "If you have any difficulties please contact the board administrator";

$L['aut_regrequesttitle'] = "Registration request";
$L['aut_regrequest'] = "Hi %1\$s,\n\nYou are receiving this email because you have (or someone pretending to be you has) registered a new account on our website. If you did not request this email then please ignore it, if you keep receiving it please contact the site administrator. \n\nYour account is currently inactive, an administrator will need to activate it before you can log in. You will receive another email when this has occured. Then you will be able to login with : \n\nUsername = %1\$s \nPassword = %2\$s";

$L['aut_regreqnoticetitle'] = "New account request";
$L['aut_regreqnotice'] = "Hi,\n\nYou are receiving this email because %1\$s requested a new account.\nThis user won't be able to login until you manually set the account as 'active', here :\n\n %2\$s";

$L['aut_emailreg'] = "Hi %1\$s,\n\nYou are receiving this email because you have (or someone pretending to be you has) registered a new account on our website. If you did not request this email then please ignore it, if you keep receiving it please contact the site administrator.\n\nTo use your account you need to activate it with this link :\n\n %3\$s \n\n Then you'll be able to login with : \n\nUsername = %1\$s \nPassword = %2\$s";

$L['aut_registertitle'] = "Register a new member account";
$L['aut_registersubtitle'] = "";
$L['aut_logintitle'] = "Login form";

/* ====== Users ====== */

$L['use_title'] = "Users";
$L['use_subtitle'] = "Registered members";
$L['useed_accountactivated'] = "Account activated";
$L['useed_email'] = "You are receiving this email because an administrator activated your account.\nYou may now login using the username and password you received in a previous email.\n\n";
$L['useed_title'] = "Edit";
$L['useed_subtitle'] = "&nbsp;";
$L['use_byfirstletter'] = "Name starting by";
$L['use_allusers'] = "All users";
$L['use_allbannedusers'] = "Users banned";
$L['use_allinactiveusers'] = "Users inactive";

/* ====== Profile ====== */

$L['pro_title'] = "Profile";
$L['pro_subtitle'] = "Your personal account";
$L['pro_passtoshort'] = "The password must be at least 4 chars long and must consist of alphanumerical characters and underscore only.";
$L['pro_passdiffer'] = "The 2 password fields do not match";
$L['pro_wrongpass'] = "You didn't enter your present password, or it's wrong";
$L['pro_avatarsupload'] = "Upload an avatar";
$L['pro_sigupload'] = "Upload a signature";
$L['pro_photoupload'] = "Upload a photography";
$L['pro_avatarspreset'] = "...or click here to display a gallery of pre-loaded avatars";
$L['pro_avatarschoose'] = "Click an image below to set it as your own avatar";
$L['pro_avataruploadfailed'] = "The upload failed, delete the old avatar before to free the slot !";

/* ====== Config ====== */

$L['cfg_disablereg'] = array("Disable registration process", "Prevent users from registering new accounts");
$L['cfg_defaultcountry'] = array("Default country for the new users", "2 letters country code");    // New in v130
$L['cfg_disablewhosonline'] = array("Disable who's online", "Automatically enabled if you turn on the Shield");
$L['cfg_maxusersperpage'] = array("Maximum lines in userlist", "");
$L['cfg_regrequireadmin'] = array("Administrators must validate new accounts", "");
$L['cfg_regnoactivation'] = array("Skip email check for new users", "\"No\"recommended, for security reasons");
$L['cfg_useremailchange'] = array("Allow users to change their email address", "\"No\" recommended, for security reasons");
$L['cfg_usertextimg'] = array("Allow images and HTML in user signature", "\"No\" recommended, for security reasons");
$L['cfg_color_group'] = array("Colorize group of users", "Default: No, for better performance");  // New in v175
$L['cfg_av_maxsize'] = array("Avatar, maximum file size", "Default: 8000 bytes");
$L['cfg_av_maxx'] = array("Avatar, maximum width", "Default: 64 pixels");
$L['cfg_av_maxy'] = array("Avatar, maximum height", "Default: 64 pixels");
$L['cfg_usertextmax'] = array("Maximum length for user signature", "Default: 300 chars");
$L['cfg_sig_maxsize'] = array("Signature, maximum file size", "Default: 50000 bytes");
$L['cfg_sig_maxx'] = array("Signature, maximum width", "Default: 468 pixels");
$L['cfg_sig_maxy'] = array("Signature, maximum height", "Default: 60 pixels");
$L['cfg_ph_maxsize'] = array("Photo, maximum file size", "Default: 8000 bytes");
$L['cfg_ph_maxx'] = array("Photo, maximum width", "Default: 96 pixels");
$L['cfg_ph_maxy'] = array("Photo, maximum height", "Default: 96 pixels");
