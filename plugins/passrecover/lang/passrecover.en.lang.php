<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/passrecover/lang/passrecover.en.lang.php
Version=177
Updated=2006-jun-05
Type=Plugin.standalone
Author=Neocrome
Description=
[END_SED]
==================== */


$L['plu_title'] = "Password recovery";

$L['plu_explain1'] = "1 : Enter your email address and the verification code, and we will email instructions to you on how to reset your password.";
$L['plu_explain2'] = "2 : You will receive a message with an emergency link, click it to log in.";
$L['plu_explain3'] = "3 : Then go in your profile, and set yourself a new password.";
$L['plu_explain4'] = "If you emptied the email field in your profile, you won't be able to recover your password.<br />In this case, please contact the webmaster with the contact form.";
$L['plu_mailsent'] = "Done, please check your mailbox in few minutes, and click the emergency link.<br />Then follow instructions.";
$L['plu_mailsent2'] = "Done, please check your mailbox in few minutes, and click the link to change your password. <br /> Then follow instructions.";
$L['plu_youremail'] = "Your email : ";
$L['plu_request'] = "Request";
$L['plu_loggedin1'] = "Welcome back, ";
$L['plu_loggedin2'] = "you're now logged in.";
$L['plu_loggedin3'] = "You may now go to your <a href=\"".sed_url("users", "m=profile")."\">profile</a>, and set yourself a new password.";
$L['plu_email1'] = "You are receiving this email because you have (or someone pretending to be you has) requested an emergency link to log in at a site powered by the Seditio engine. If you did not request this email then please ignore it, if you keep receiving it please contact the site administrator.\n\nYou may now log in with the link below, then follow instructions :";
$L['plu_email2'] = "You're asked to enter the password recovery to our website. \r\nClick the link below to generate a new password. A new password will be sent to your e-mail.";
$L['plu_email3'] = "In your request a new password. Change it as soon as possible and delete this email.\r\n\r\nYour new password: "; 
$L['plu_newpass'] = "Done!<br /><br />Soon you will receive a new password to access the site.";

?>
