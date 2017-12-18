<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/smtp/smtp.mail.connector.php
Version=177
Updated=2017-dec-09
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=smtp
Part=mail
File=smtp.mail.connector
Hooks=mail.connector
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$smtp_active = $cfg['plugin']['smtp']['smtp_active'];

if ($smtp_active == 'yes')
  { 
		if (empty($c_fmail)) { return(FALSE); } 

		$smtp_host = $cfg['plugin']['smtp']['smtp_host'];
		$smtp_port = $cfg['plugin']['smtp']['smtp_port'];
		$smtp_login = $cfg['plugin']['smtp']['smtp_login'];
		$smtp_pass = $cfg['plugin']['smtp']['smtp_pass'];
		$smtp_from = $cfg['plugin']['smtp']['smtp_from'];

		$connector = 1;

		require_once('plugins/smtp/inc/smtp.class.php');

		$c_body .= "\n\n".$cfg['maintitle']." - ".$cfg['mainurl']."\n".$cfg['subtitle'];

		$mail_type = ($c_content == 'plain') ? false : true;

		$mail = new Email($smtp_host, $smtp_port);
		$mail->setLogin($smtp_login, $smtp_pass);
		$mail->addTo($c_fmail, $c_fmail);
		$mail->setFrom($smtp_from, $cfg['maintitle']);
		$mail->setSubject($c_subject);
		$mail->setMessage($c_body, $mail_type);
		  
		if($mail->send()){
			sed_stat_inc('totalmailsent');
		} else {
			return(TRUE); 
		}     	
  }







?>