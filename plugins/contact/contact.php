<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/contact/contact.php
Version=177
Updated=2015-feb-06
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=contact
Part=main
File=contact
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) { die('Wrong URL.'); }

   
$id = sed_import('id','G','INT');

$sender_email = sed_import('sender_email','P','HTM');
$sender_name = sed_import('sender_name','P','HTM');
$sender_tel = sed_import('sender_tel','P','HTM');
$sender_subject = sed_import('sender_subject','P','HTM');
$sender_message = sed_import('sender_message','P','HTM');
$sender_recip = sed_import('sender_recip','P','INT');
$sender_tarp = sed_import('sender_tarp','P','TXT',8);
$sender_key = sed_import('sender_key','P','TXT',8);
$sender_fak = sed_import('sender_fak','P','TXT',8);

unset($error, $message);
$antispam_key = array();

if (empty($cfg['plugin']['contact']['emails']))
    { $cfg['plugin']['contact']['emails'] = $cfg['adminemail']; }
if (empty($cfg['plugin']['contact']['recipients']))
    { $cfg['plugin']['contact']['recipients'] = $cfg['maintitle']; }

$cfg_emails = explode (",", $cfg['plugin']['contact']['emails']);
$cfg_names = explode (",", $cfg['plugin']['contact']['recipients']);


if ($a=='send')
	{
	if (!empty($sender_tarp))
		{ die("Error."); }

 	if ($sender_key != strrev(str_replace('.', '',$sender_fak)))
		{ $error .= $L['plu_antispam']."<br />"; }

	if (empty($sender_email) || empty($sender_name) || empty($sender_subject) || empty($sender_message))
		{ $error .= $L['plu_fieldempty']."<br />"; }

	if (!mb_strpos($sender_email, '@') || !mb_strpos($sender_email, '.'))
		{ $error .= $L['plu_wrongentry']."<br />"; }

	if (empty($error))
		{
		$fheaders = ("From: ".$sender_email."\n"."Content-Type: text/plain; charset=".$cfg['charset']."\n");
		$fbody = $L['plu_notice'];
		$fbody .= $sender_name."\n".$L['plu_email_title']." : ".$sender_email."\n".$L['plu_phone_title']." : ".$sender_tel."\n\n";
		$fbody .= $L['plu_message_title']." : \n\n".sed_br2nl($sender_message);
		sed_mail($cfg_emails[$sender_recip], $sender_subject, $fbody, $fheaders);
		
		if (!empty($cfg['plugin']['contact']['admincopy1']))
		  { sed_mail(trim($cfg['plugin']['contact']['admincopy1']), "COPY: ".$sender_subject, $fbody, $fheaders); }

		if (!empty($cfg['plugin']['contact']['admincopy2']))
		  { sed_mail(trim($cfg['plugin']['contact']['admincopy2']), "COPY: ".$sender_subject, $fbody, $fheaders); }    

		sed_redirect(sed_url("plug", "e=contact&a=done", "", true));
		exit;
		}
	}
elseif ($a=='done')
	{
	$message = "<div class=\"error\">".$L['plu_sent']."</div>";
	unset($sender_email, $sender_name, $sender_subject, $sender_message, $sender_recip);
	}

// ======================================================

$error_string = (!empty($error)) ? $error.$L['plu_notsent'] : '';
$error_string .= (!empty($message)) ? $message : '';

$recipients = "<select name=\"sender_recip\">\n";
foreach($cfg_emails as $k => $i)
	{
	$selected = ($i==$id) ? "selected=\"selected\"" : "" ;
	$recipients .= "<option value=\"".$k."\" $selected >".trim($cfg_names[$k])."</option>\n";
	$i++;
	}
$recipients .= "</select>\n";

$sender_email = (empty($sender_email) && !empty($usr['profile']['user_email'])) ? $usr['profile']['user_email'] : $sender_email;
$sender_name = (empty($sender_name) && $usr['id']>0) ? $usr['name']: $sender_name;

for ($i = 1; $i <= 3; $i++)
  { $antispam_key [] = rand(1,9); }

$antispam_fak = array_reverse($antispam_key);
$antispam_key = implode ('.', $antispam_key);
$antispam_fak = implode ('.', $antispam_fak);

$antispam .= "&nbsp; <strong>".$antispam_key."</strong> &nbsp;";
$antispam .= "<input type=\"text\" class=\"text\" name=\"sender_key\" value=\"\" size=\"8\" maxlength=\"8\" />";
$antispam .= "<input type=\"hidden\" name=\"sender_fak\" value=\"".$antispam_fak."\" /></div>";

// ======================================================

if (!empty($error_string))
	{
	$t->assign("PLUGIN_CONTACT_ERROR_BODY",$error_string);
	$t->parse("MAIN.PLUGIN_CONTACT_ERROR");
	}

$t->assign(array(
  "PLUGIN_CONTACT_TITLE" => "<a href=\"".sed_url("plug", "e=contact")."\">".$L['plu_title'] ."</a>",
  "PLUGIN_CONTACT_EXPLAIN" => $L['plu_explain'],
  "PLUGIN_CONTACT_FORM" => sed_url("plug", "e=contact&a=send"),
  "PLUGIN_CONTACT_RECIPIENTS_TITLE" => $L['plu_recipients_title'],
  "PLUGIN_CONTACT_RECIPIENTS" => $recipients,
  "PLUGIN_CONTACT_EMAIL_TITLE" => $L['plu_email_title'],
  "PLUGIN_CONTACT_EMAIL" => "<input type=\"text\" class=\"text\" name=\"sender_email\" value=\"".sed_cc($sender_email)."\" size=\"32\" maxlength=\"64\" />",
  "PLUGIN_CONTACT_NAME_TITLE" => $L['plu_name_title'],
  "PLUGIN_CONTACT_NAME" => "<input type=\"text\" class=\"text\" name=\"sender_name\" value=\"".sed_cc($sender_name)."\" size=\"32\" maxlength=\"64\" />",      
  "PLUGIN_CONTACT_PHONE_TITLE" => $L['plu_phone_title'],
  "PLUGIN_CONTACT_PHONE" =>  "<input type=\"text\" class=\"text\" name=\"sender_tel\" value=\"".sed_cc($sender_tel)."\" size=\"32\" maxlength=\"18\" /></td>",
  "PLUGIN_CONTACT_SUBJECT_TITLE" => $L['plu_subject_title'],
  "PLUGIN_CONTACT_SUBJECT" => "<input type=\"text\" class=\"text\" name=\"sender_subject\" value=\"".sed_cc($sender_subject)."\" size=\"48\" maxlength=\"64\" />",
  "PLUGIN_CONTACT_BODY_TITLE" => $L['plu_message_title'],
  "PLUGIN_CONTACT_BODY" => "<textarea cols=\"48\" rows=\"8\" name=\"sender_message\" class=\"noeditor\">".sed_cc($sender_message)."</textarea><input type=\"text\" class=\"text\" name=\"sender_tarp\" value=\"\" size=\"20\" maxlength=\"20\" style=\"display:none;\"/>",
  "PLUGIN_CONTACT_REQUIRED" => $L['plu_required'],
  "PLUGIN_CONTACT_ANTISPAM" => $L['plu_verify'].$antispam,
  "PLUGIN_CONTACT_EXTRA1" => $cfg['plugin']['contact']['extra1'],
  "PLUGIN_CONTACT_EXTRA2" => $cfg['plugin']['contact']['extra2'],
  "PLUGIN_CONTACT_EXTRA3" => $cfg['plugin']['contact']['extra3'],
  "PLUGIN_CONTACT_SEND" => $L['plu_send']
	));


?>
