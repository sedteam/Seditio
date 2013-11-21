<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/passrecover/passrecover.php
Version=173
Updated=2012-sep-23
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=passrecover
Part=main
File=passrecover
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) { die('Wrong URL.'); }

$a = sed_import('a','G','TXT');
$v = sed_import('v','G','TXT');
$email = sed_import('email','P','TXT');

$plugin_title = $L['plu_title'];

$generate_password = $cfg['plugin']['passrecover']['generate_password'];

if ($a=='request' && $email!='')
	{
	sed_shield_protect();
	$sql = sed_sql_query("SELECT user_id, user_name, user_lostpass FROM $db_users WHERE user_email='".sed_sql_prep($email)."' ORDER BY user_id ASC LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql))
		{
		$rusername = $row['user_name'];
		$ruserid = $row['user_id'];
		$validationkey = $row['user_lostpass'];

		if (empty($validationkey) || $validationkey=="0")
			{
			$validationkey = md5(microtime());
			$sql = sed_sql_query("UPDATE $db_users SET user_lostpass='$validationkey', user_lastip='".$usr['ip']."' WHERE user_id='$ruserid'");
			
			}

		sed_shield_update(60,"Password recovery email sent");

		$rsubject = $cfg['maintitle']." - ".$L['plu_title'];
		
		if ($generate_password == "no") 
			{
				$ractivate = $cfg['mainurl']."/plug.php?e=passrecover&a=auth&v=".$validationkey;
				$email_text = $L['plu_email1'];
				$plugin_body = $L['plu_mailsent'];
			}
		else 
			{
				$ractivate = $cfg['mainurl']."/plug.php?e=passrecover&a=newpassword&v=".$validationkey;
				$email_text = $L['plu_email2'];
				$plugin_body = $L['plu_mailsent2'];
			}	
		
		$rbody = $L['Hi']." ".$rusername.",\n\n".$email_text."\n\n".$ractivate. "\n\n".$L['aut_contactadmin'];
		sed_mail ($email, $rsubject, $rbody);
		}
	else
		{
		sed_shield_update(10,"Password recovery requested");

		sed_log("Pass recovery failed, user : ".$rusername);
		header("Location: message.php?msg=151");
		exit;
		}
	}
elseif (($a=='auth' || $a=='newpassword') && mb_strlen($v)==32)
	{
	sed_shield_protect();

	$sql = sed_sql_query("SELECT user_name, user_id, user_secret, user_email, user_maingrp, user_banexpire, user_skin FROM $db_users WHERE user_lostpass='".sed_sql_prep($v)."'");

	if ($row = sed_sql_fetchassoc($sql))
		{
		$rmdpass_secret  = $row['user_secret'];
		$rusername = $row['user_name'];
		$ruserid = $row['user_id'];
		$rdefskin = $row['user_skin'];
		$remail = $row['user_email'];

		if ($row['user_maingrp']==2)
			{
			sed_log("Password recovery failed, user inactive : ".$rusername);
			header("Location: message.php?msg=152");
			exit;
			}

	 	if ($row['user_maingrp']==3)
			{
			sed_log("Password recovery failed, user banned : ".$rusername);
			header("Location: message.php?msg=153&num=".$row['user_banexpire']);
			exit;
			}
		
		$validationkey = md5(microtime());
		$sql = sed_sql_query("UPDATE $db_users SET user_lostpass='$validationkey' WHERE user_id='$ruserid'");

		if ($generate_password == "yes" && $a=='newpassword') 
			{
				$newpassword = sed_unique(7); // New sed172  					
				$mdsalt = sed_unique(16); // New sed172    
				$mdpass = sed_hash($newpassword, 1, $mdsalt);  // New sed172    		    	
				
				$sql = sed_sql_query("UPDATE $db_users SET user_password='$mdpass', user_salt='$mdsalt', user_passtype=1 WHERE user_id='$ruserid'");
				
				$rsubject = $cfg['maintitle']." - ".$L['plu_title'];
				
				$rbody = $L['Hi']." ".$rusername.",\n\n".$L['plu_email3'].$newpassword. "\n\n".$L['aut_contactadmin'];
				sed_mail ($remail, $rsubject, $rbody);
				
				$plugin_body .= $L['plu_newpass'];
			}
		else 
			{	
				if ($cfg['authmode']==1 || $cfg['authmode']==3)
					{
					$u = base64_encode("$ruserid:_:$rmdpass_secret:_:".$cfg['defaultskin']);
					setcookie("SEDITIO", "$u", time() + 86400, $cfg['cookiepath'], $cfg['cookiedomain']);
					}

				if ($cfg['authmode']==2 || $cfg['authmode']==3)
					{
					$_SESSION['rsedition'] = $ruserid;
					$_SESSION['rseditiop'] = $rmdpass_secret;
					$_SESSION['rseditios'] = $rdefskin;
					}

				$plugin_body .= $L['plu_loggedin1'].$rusername." ".$L['plu_loggedin2']."<br />";
				$plugin_body .= $L['plu_loggedin3']."<br />";
			}
		}
	else
		{
		sed_shield_update(7,"Log in");
		sed_log("Pass recovery failed, user : ".$rusername);
		header("Location: message.php?msg=151");
		exit;
		}
	}
else
	{
	$plugin_body .= $L['plu_explain1']."<br />".$L['plu_explain2']."<br />".$L['plu_explain3']."<br />&nbsp;<br />";
	$plugin_body .= "<form name=\"reqauth\" action=\"plug.php?e=passrecover&amp;a=request\" method=\"post\">";
	$plugin_body .= $L['plu_youremail']."<input type=\"text\" class=\"text\" name=\"email\" value=\"\" size=\"40\" maxlength=\"64\" />";
	$plugin_body .= "<input type=\"submit\" class=\"submit\" value=\"".$L['plu_request']."\" /></form><br />&nbsp;<br />".$L['plu_explain4'];

	}

?>
