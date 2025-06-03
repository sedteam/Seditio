<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/smtp/smtp.mail.connector.php
Version=180
Updated=2025-jan-31
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

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$smtp_active = $cfg['plugin']['smtp']['smtp_active'];
$smtp_debug = $cfg['plugin']['smtp']['smtp_debug'];

if ($smtp_active == 'yes') {
	if (empty($c_fmail)) {
		return (FALSE);
	}

	$smtp_host = $cfg['plugin']['smtp']['smtp_host'];
	$smtp_port = $cfg['plugin']['smtp']['smtp_port'];
	$smtp_login = $cfg['plugin']['smtp']['smtp_login'];
	$smtp_pass = $cfg['plugin']['smtp']['smtp_pass'];
	$smtp_from = $cfg['plugin']['smtp']['smtp_from'];
	$smtp_from_title = $cfg['plugin']['smtp']['smtp_from_title'];
	$smtp_from_title = (!empty($smtp_from_title)) ? $smtp_from_title : $cfg['maintitle'];
	$smtp_tls = ($cfg['plugin']['smtp']['smtp_ssl'] == 'yes') ? 'ssl' : 'tls';
	$smtp_connection_timeout = 30;
	$smtp_response_timeout = 8;

	$connector = 1;

	require_once(SED_ROOT . '/plugins/smtp/inc/smtp.class.php');

	$c_body .= ($c_content == "html") ? "<p>" . $cfg['maintitle'] . " - " . $cfg['mainurl'] . "</p>" : "\n\n" . $cfg['maintitle'] . " - " . $cfg['mainurl'];

	$mail = new Email($smtp_host, $smtp_port, $smtp_connection_timeout, $smtp_response_timeout, $smtp_tls);

	if ($smtp_debug == "yes") {
		$mail->setLogFile(SED_ROOT . '/plugins/smtp/log/log.txt');
	}

	$mail->setLogin($smtp_login, $smtp_pass);

	$mail->addTo($c_fmail, $c_fmail);

	$mail->setFrom($smtp_from, $smtp_from_title);

	$mail->setSubject($c_subject);

	if (isset($c_attach) && is_array($c_attach)) {
		foreach ($c_attach as $attach) {
			$mail->addAttachment($attach);
		}
	}

	if ($c_content == 'html') {
		$mail->setHTML($c_body);
	} else {
		$mail->setText($c_body);
	}

	if ($mail->send()) {
		sed_stat_inc('totalmailsent');
		return (TRUE); // Email sent successfully!
	} else {
		return (FALSE); // Failed to send email!
	}
}
