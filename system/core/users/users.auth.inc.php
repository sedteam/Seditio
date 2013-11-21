<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=users.auth.inc.php
Version=173
Updated=2012-sep-23
Type=Core
Author=Neocrome
Description=User authentication
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$v = sed_import('v','G','H32');

/* === Hook === */
$extp = sed_getextplugins('users.auth.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

if ($a=='check')
	{
	sed_shield_protect();

	/* === Hook for the plugins === */
	$extp = sed_getextplugins('users.auth.check');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$rusername = sed_import('rusername','P','TXT', 24, TRUE);
	$rpassword = sed_import('rpassword','P','TXT', 16, TRUE);
	$rcookiettl = sed_import('rcookiettl','P','INT');
	
	// New in sed171
	$sql = sed_sql_query("SELECT user_salt, user_passtype FROM $db_users WHERE user_name='".sed_sql_prep($rusername)."'");

	if (sed_sql_numrows($sql) == 1)
	{
	  $row = sed_sql_fetchassoc($sql);    
	  $mdsalt = $row['user_salt']; // New sed172       	  
	  $rmdpass = ($row['user_passtype'] == 0) ?  sed_hash($rpassword, 0) : sed_hash($rpassword, 1, $mdsalt); // New sed172 
	}

	$sql = sed_sql_query("SELECT user_id, user_secret, user_maingrp, user_banexpire, user_skin, user_lang FROM $db_users WHERE user_password='$rmdpass' AND user_name='".sed_sql_prep($rusername)."'");

	if ($row = sed_sql_fetchassoc($sql))
		{
		if ($row['user_maingrp']==2)
			{
			sed_log("Log in attempt, user inactive : ".$rusername, 'usr');
			sed_redirect("message.php?msg=152");
			exit;
			}
	 	elseif ($row['user_maingrp']==3)
			{
			if ($sys['now'] > $row['user_banexpire'] && $row['user_banexpire']>0)
				{
				$sql = sed_sql_query("UPDATE $db_users SET user_maingrp='4' WHERE user_id='".$row['user_id']."'");
				}
		    else
		       	{
				sed_log("Log in attempt, user banned : ".$rusername, 'usr');
				sed_redirect("message.php?msg=153&num=".$row['user_banexpire']);
				exit;
				}
			}

		$rmdpass_secret = md5(sed_unique(16)); // New sed171
    
		$ruserid = $row['user_id'];
		$rdefskin = $row['user_skin'];

		sed_sql_query("UPDATE $db_users SET user_secret = '".$rmdpass_secret."', user_lastip='".$usr['ip']."' WHERE user_id='".$row['user_id']."' LIMIT 1");

		if ($rcookiettl>0 && ($cfg['authmode']==1 || $cfg['authmode']==3))
			{
			$rcookiettl = ($rcookiettl==0) ? 604800 : $rcookiettl;
			$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
			$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
			setcookie("SEDITIO", "$u", time()+$rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain']);
			}

		if ($cfg['authmode']==2 || $cfg['authmode']==3)
			{
			$_SESSION['rsedition'] = $ruserid;
			$_SESSION['rseditiop'] = $rmdpass_secret;
			$_SESSION['rseditios'] = $rdefskin;
			}

		/* === Hook === */
		$extp = sed_getextplugins('users.auth.check.done');
		if (is_array($extp))
			{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
		/* ===== */

		$sql = sed_sql_query("DELETE FROM $db_online WHERE online_userid='-1' AND online_ip='".$usr['ip']."' LIMIT 1");
		sed_redirect("message.php?msg=104&redirect=".$redirect);
		exit;
		}
	else
		{
		sed_shield_update(7, "Log in");
		sed_log("Log in failed, user : ".$rusername,'usr');
		sed_redirect("message.php?msg=151");
		exit;
		}
	}

else
	{ unset($redir); }

/* === Hook === */
$extp = sed_getextplugins('users.auth.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

require("system/header.php");
$t = new XTemplate("skins/".$skin."/users.auth.tpl");

$t->assign(array(
	"USERS_AUTH_TITLE" => $L['aut_logintitle'],
	"USERS_AUTH_SEND" => "users.php?m=auth&amp;a=check&amp;redirect=".$redirect,
	"USERS_AUTH_USER" => "<input type=\"text\" class=\"text\" name=\"rusername\" size=\"16\" maxlength=\"32\" />",
	"USERS_AUTH_PASSWORD" => "<input type=\"password\" class=\"password\" name=\"rpassword\" size=\"16\" maxlength=\"32\" />".$redir,
	"USERS_AUTH_REGISTER" => "users.php?m=register"
		));

/* === Hook === */
$extp = sed_getextplugins('users.auth.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require("system/footer.php");

?>
