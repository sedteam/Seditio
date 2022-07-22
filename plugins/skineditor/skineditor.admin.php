<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/skineditor/skineditor.php
Version=179
Updated=2022-jul-15
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=skineditor
Part=admin
File=skineditor.admin
Hooks=tools
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$sk = sed_import('sk','G','ALP',24);
$f = sed_import('f','G','TXT',128);
$fb = sed_import('fb','G','TXT',128);
$out['img_reset'] = "<img src=\"system/img/admin/reset.png\" alt=\"\" />";

if (!empty($sk))
  {
  if (!empty($f))
    { $n = 'edit'; } 
  else
    { $n = 'skin'; }
  }

switch ($n)
  {
  case 'skin' :
  
	$skininfo = "skins/".$sk."/".$sk.".php";
	$skindir = "skins/".$sk."/";

	if (!file_exists($skininfo))
		{ die ('Wrong skin code.'); }

	$info = sed_infoget($skininfo);
	$adminpath[] = array (sed_url("admin", "m=tools&p=skineditor&sk=".$sk), $info['Name']);

	if ($a=='makbak')
		{
		sed_check_xg();
		copy($skindir.$fb, $skindir.$fb.".bak");
		}
	elseif ($a=='resbak')
		{
		sed_check_xg();
		if (file_exists($skindir.$fb.".bak"))
			{
			if (file_exists($skindir.$fb))
				{ unlink($skindir.$fb); }
			if (copy($skindir.$fb.".bak", $skindir.$fb))
				{ unlink($skindir.$fb.".bak"); }     
			}
		}
	elseif ($a=='delbak')
	{
	sed_check_xg();
	unlink($skindir.$fb.".bak");
	}

	$handle = opendir($skindir);

	while ($f = readdir($handle))
		{
		$dotpos = mb_strrpos($f,".")+1;
		$extension = mb_substr($f, $dotpos, 5);
		 
		if (mb_strtolower($extension)=='tpl' || mb_strtolower($extension)=='css' || mb_strtolower($extension)=='txt' || mb_strtolower($extension)=='php')
		   { $skinlist[] = $f; }
		elseif (mb_strtolower($extension)=='bak')
		   { $baklist[] = $f; }   
		}
		
	closedir($handle);
	sort($skinlist);
	
	if (is_array($baklist))
		{
		@sort($baklist);
		foreach($baklist as $i => $x)
			{ $backupfile[$x] = TRUE; }
		}

	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:8%;\">".$L['Edit']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:25%;\">".$L['File']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Size']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:45%;\" colspan=\"2\">".$out['ic_checked']."=".$L['plu_makbak']." &nbsp; &nbsp; ".$out['img_unchecked']."=".$L['plu_delbak']." &nbsp; &nbsp; ".$out['img_reset'] ."=".$L['plu_resbak']."</td>";

	$plugin_body .= "</tr>";
	$j = 0;
	
	foreach($skinlist as $i => $x)
		{
		$dotpos = mb_strrpos($f,".")+1;
		$extension = mb_substr($f, $dotpos, 5);
		$file_size = @filesize($skindir.$x);
		$plugin_body .= "<tr>";
		$plugin_body .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&f=".$x)."\">".$out['ic_edit']."</a></td>"; 
		$plugin_body .= "<td><strong>".$x."</strong></td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$file_size."</td>";
		   
		$xbak = $x.".bak";
		  
		if ($backupfile[$xbak])
			{
			$plugin_body .= "<td style=\"width:10%; text-align:center;\">";
			$plugin_body .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=delbak&".sed_xg())."\">".$out['img_unchecked']."</a> &nbsp; ";
			$plugin_body .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=resbak&".sed_xg())."\">".$out['img_reset']."</a>";
			$plugin_body .= "</td>"; 
			$plugin_body .= "<td>".$xbak."</td>";      
			}
		else
			{
			$plugin_body .= "<td style=\"width:10%; text-align:center;\">";
			$plugin_body .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=makbak&".sed_xg())."\">".$out['ic_checked']."</a>";
			$plugin_body .= "</td>";
			$plugin_body .= "<td>&nbsp;</td>";      
			}
		$plugin_body .= "</tr>";
		$j++;
		$total_size += $file_size;
		}    
	$plugin_body .= "<tr>";
	$plugin_body .= "<td>&nbsp;</td>";
	$plugin_body .= "<td>".$j." ".$L['Files']."</td>";
	$plugin_body .= "<td style=\"text-align:center;\">".$L['Total']." : ".$total_size."</td>";
	$plugin_body .= "<td colspan=\"2\">&nbsp;</td>"; 
	$plugin_body .= "</tr>";    

	$plugin_body .= "</table>";
 
  break;
  
  
  // ================================

  case 'edit' :

	$skininfo = "skins/".$sk."/".$sk.".php";
	$skindir = "skins/".$sk."/";
	$b1 = sed_import('b1','P','ALP',16);
  
	if (!file_exists($skininfo))
	sed_die('Wrong file name');

	$info = sed_infoget($skininfo);
	$adminpath[] = array (sed_url("admin", "m=tools&p=skineditor&sk=".$sk), $info['Name']);

	$editfile = "skins/".$sk."/".$f;

	if ($a=='update')
	{
	$content = sed_import('content','P','HTR');
	$file_isup = TRUE;

	if (!($fp = @fopen($editfile, 'w'))) { $file_isup = FALSE; }
	if (!(@fwrite($fp, $content))) { $file_isup = FALSE; }
	
	@fclose($fp);

	if ($file_isup)
	  {
	  if($b1)
		{
		sed_redirect(sed_url("admin", "m=tools&p=skineditor&sk=".$sk, "", true));
		exit;
		}
	  else
		{
		sed_redirect(sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&f=".$f, "", true));
		exit;
		}
	  }
	else
	  {
	  $plugin_body .= "Error !<br />Could not write the file : ".$editfile;
	  }
	}

	if (!($fp = @fopen($editfile, 'r')))
	sed_die('Could not open the file');

	$filecont = fread($fp, 256000);
	@fclose($fp);

	$plugin_body .= "<form id=\"update\" action=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&f=".$f."&a=update&".sed_xg())."\" method=\"post\">";
	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr>";
	$plugin_body .= "<td style=\"width:15%;\">".$L['File']." :</td>";
	$plugin_body .= "<td><strong>".$editfile."</strong></td><td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk)."\">".$L['Cancel']."</td></tr>";
	$plugin_body .= "<tr><td colspan=\"3\">";
	$plugin_body .= "<textarea cols=\"96\" rows=\"16\" name=\"content\" id=\"content\" class=\"noeditor\">".sed_cc($filecont, ENT_QUOTES)."</textarea>";    
	$plugin_body .= "</td></tr>";
	$plugin_body .= "<tr><td style=\"text-align:center;\" colspan=\"3\">";
	$plugin_body .= "<input type=\"submit\" class=\"submit btn\" name=\"b1\" value=\"".$L['Update']."\">";
	$plugin_body .= " <input type=\"submit\" class=\"submit btn\" name=\"b2\" value=\"".$L['plu_reopen']."\">";  
	$plugin_body .= "</td></tr>";
	$plugin_body .= "</table></form>";
  
	switch (pathinfo($editfile,PATHINFO_EXTENSION)) {
		case 'css':
			$hmode = 'text/css';
			break;
		case 'js':
			$hmode = 'text/javascript';
			break;
		case 'php':
			$hmode = 'application/x-httpd-php';
			break;
		default:
			$hmode = 'text/html';
	}
  
	$plugin_body .= "<script>
	var editor = CodeMirror.fromTextArea(document.getElementById(\"content\"), {
	   lineNumbers: true,
		matchBrackets: true,
		indentUnit: 4,
		indentWithTabs: true,
		mode: \"".$hmode."\",
		tabMode: \"shift\",
		theme:'default'
		});
	</script>";
        
  break;
  
  // ================================
  
  default:
  
	$handle = opendir("skins/");

	while ($f = readdir($handle))
		{
		if (mb_strpos($f, '.')  === FALSE)
			{ $skinlist[] = $f; }
		}
	closedir($handle);
	sort($skinlist);

	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:8%;\">".$L['Edit']."</td>";
	$plugin_body .= "<td class=\"coltop\">".$L['Skin']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Code']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Version']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Updated']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:25%;\">".$L['Author']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Default']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Set']."</td>";
	$plugin_body .= "</tr>";

	foreach ($skinlist as $i => $x)
		{
		$skininfo = "skins/".$x."/".$x.".php";
		$info = sed_infoget($skininfo);
		$plugin_body .= "<tr>";
		$plugin_body .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$x)."\">".$out['ic_edit']."</a></td>"; 
		$plugin_body .= "<td><strong>".$info['Name']."</strong></td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$x."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$info['Version']."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$info['Updated']."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$info['Author']."</td>";
		$plugin_body .= "<td style=\"text-align:center; vertical-align:middle; width:10%;\">";  
		$plugin_body .= ($x == $cfg['defaultskin']) ? $out['ic_checked'] : '';
		$plugin_body .= "</td><td style=\"text-align:center; vertical-align:middle; width:10%;\">";
		$plugin_body .= ($x == $skin) ? $out['ic_checked'] : '';
		$plugin_body .= "</td>"; 
		$plugin_body .= "</tr>";
		}
	$plugin_body .= "</table>";

	break;
  
  }

?>
