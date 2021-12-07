<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/smtp/smtp.mail.connector.php
Version=178
Updated=2021-dec-07
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
$smtp_debug = $cfg['plugin']['smtp']['smtp_debug'];

if ($smtp_active == 'yes')
	{ 
		if (empty($c_fmail)) { return(FALSE); } 

		$smtp_host = $cfg['plugin']['smtp']['smtp_host'];
		$smtp_port = $cfg['plugin']['smtp']['smtp_port'];
		$smtp_login = $cfg['plugin']['smtp']['smtp_login'];
		$smtp_pass = $cfg['plugin']['smtp']['smtp_pass'];
		$smtp_from = $cfg['plugin']['smtp']['smtp_from'];
		$smtp_from_title = $cfg['plugin']['smtp']['smtp_from_title'];
		$smtp_from_title = (!empty($smtp_from_title)) ? $smtp_from_title : $cfg['maintitle'];
		$smtp_ssl = ($cfg['plugin']['smtp']['smtp_ssl'] == 'yes') ? 'ssl' : '';   
		$smtp_connection_timeout = 30;
		$smtp_response_timeout = 8;

		$connector = 1;

		require_once(SED_ROOT . '/plugins/smtp/inc/smtp.class.php');

		$c_body .= ($c_content == "html") ? "<p>".$cfg['maintitle']." - ".$cfg['mainurl']."</p>" : "\n\n".$cfg['maintitle']." - ".$cfg['mainurl'];    

		$mail_type = ($c_content == 'plain') ? false : true;

		$mail = new Email($smtp_host, $smtp_port, $smtp_connection_timeout, $smtp_response_timeout, $smtp_ssl);
    
		$mail->setLogin($smtp_login, $smtp_pass);
		$mail->addTo($c_fmail, $c_fmail);
		
		$mail->setFrom($smtp_from, $smtp_from_title);
		$mail->setSubject($c_subject);
		$mail->setMessage($c_body, $mail_type);  
		
		if (is_array($c_attach)) $mail->setAttach($c_attach);
    		  
		if ($mail->send()) {
			if ($smtp_debug == "yes") {
				$log = $mail->getLog();
				file_put_contents(SED_ROOT . '/plugins/smtp/log/log.txt', $log, FILE_APPEND | LOCK_EX);
			}
			sed_stat_inc('totalmailsent');
			return(TRUE); 
		} else {
			if ($smtp_debug == "yes") {
				$log = $mail->getLog();
				file_put_contents(SED_ROOT . '/plugins/smtp/log/log.txt', $log, FILE_APPEND | LOCK_EX);
			}
			return(FALSE); 
		}
	}


?>
