<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/skineditor/skineditor.php
Version=178
Updated=2021-jun-17
Type=Plugin
Author=Neocrome
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
    
    while(list($i,$x) = each($baklist))
      { $backupfile[$x] = TRUE; }
    }
    
  $adminmain .= "<table class=\"cells striped\">";
  $adminmain .= "<tr>";
  $adminmain .= "<td class=\"coltop\" style=\"width:8%;\">".$L['Edit']."</td>";
  $adminmain .= "<td class=\"coltop\" style=\"width:25%;\">".$L['File']."</td>";
  $adminmain .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Size']."</td>";
  $adminmain .= "<td class=\"coltop\" style=\"width:45%;\" colspan=\"2\">".$out['img_checked']."=".$L['plu_makbak']." &nbsp; &nbsp; ".$out['img_unchecked']."=".$L['plu_delbak']." &nbsp; &nbsp; ".$out['img_reset'] ."=".$L['plu_resbak']."</td>";
    
  $adminmain .= "</tr>";
  $j = 0;
  while(list($i,$x) = each($skinlist))
    {
    $dotpos = mb_strrpos($f,".")+1;
    $extension = mb_substr($f, $dotpos, 5);
    $file_size = @filesize($skindir.$x);
    $adminmain .= "<tr>";
    $adminmain .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&f=".$x)."\">".$out['img_edit']."</a></td>"; 
    $adminmain .= "<td><strong>".$x."</strong></td>";
    $adminmain .= "<td style=\"text-align:center;\">".$file_size."</td>";
       
    $xbak = $x.".bak";
      
    if ($backupfile[$xbak])
      {
      $adminmain .= "<td style=\"width:10%; text-align:center;\">";
      $adminmain .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=delbak&".sed_xg())."\">".$out['img_unchecked']."</a> &nbsp; ";
      $adminmain .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=resbak&".sed_xg())."\">".$out['img_reset']."</a>";
      $adminmain .= "</td>"; 
      $adminmain .= "<td>".$xbak."</td>";      
      }
    else
      {
      $adminmain .= "<td style=\"width:10%; text-align:center;\">";
      $adminmain .= "<a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&fb=".$x."&a=makbak&".sed_xg())."\">".$out['img_checked']."</a>";
      $adminmain .= "</td>";
      $adminmain .= "<td>&nbsp;</td>";      
      }

     
    $adminmain .= "</tr>";
    $j++;
    $total_size += $file_size;
    }    
  $adminmain .= "<tr>";
  $adminmain .= "<td>&nbsp;</td>";
  $adminmain .= "<td>".$j." ".$L['Files']."</td>";
  $adminmain .= "<td style=\"text-align:center;\">".$L['Total']." : ".$total_size."</td>";
  $adminmain .= "<td colspan=\"2\">&nbsp;</td>"; 
  $adminmain .= "</tr>";    
    
  $adminmain .= "</table>";
 
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
      $adminmain .= "Error !<br />Could not write the file : ".$editfile;
      }
    }

  if (!($fp = @fopen($editfile, 'r')))
    sed_die('Could not open the file');

  $filecont = fread($fp, 256000);
  @fclose($fp);
    
  $adminmain .= "<form id=\"update\" action=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk."&f=".$f."&a=update&".sed_xg())."\" method=\"post\">";
  $adminmain .= "<table class=\"cells striped\">";
  $adminmain .= "<tr>";
  $adminmain .= "<td style=\"width:15%;\">".$L['File']." :</td>";
  $adminmain .= "<td><strong>".$editfile."</strong></td><td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$sk)."\">".$L['Cancel']."</td></tr>";
  $adminmain .= "<tr><td colspan=\"3\">";
  $adminmain .= "<textarea cols=\"96\" rows=\"16\" name=\"content\" id=\"content\" class=\"noeditor\">".$filecont."</textarea>";    
  $adminmain .= "</td></tr>";
  $adminmain .= "<tr><td style=\"text-align:center;\" colspan=\"3\">";
  $adminmain .= "<input type=\"submit\" class=\"submit btn\" name=\"b1\" value=\"".$L['Update']."\">";
  $adminmain .= " <input type=\"submit\" class=\"submit btn\" name=\"b2\" value=\"".$L['plu_reopen']."\">";  
  $adminmain .= "</td></tr>";
  $adminmain .= "</table></form>";
  
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
  
  $adminmain .= "<script>
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

$adminmain .= "<table class=\"cells striped\">";
$adminmain .= "<tr>";
$adminmain .= "<td class=\"coltop\" style=\"width:8%;\">".$L['Edit']."</td>";
$adminmain .= "<td class=\"coltop\">".$L['Skin']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Code']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Version']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:15%;\">".$L['Updated']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:25%;\">".$L['Author']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Default']."</td>";
$adminmain .= "<td class=\"coltop\" style=\"width:10%;\">".$L['Set']."</td>";
$adminmain .= "</tr>";

while(list($i,$x) = each($skinlist))
	{
	$skininfo = "skins/".$x."/".$x.".php";
	$info = sed_infoget($skininfo);
  $adminmain .= "<tr>";
  $adminmain .= "<td style=\"text-align:center;\"><a href=\"".sed_url("admin", "m=tools&p=skineditor&sk=".$x)."\">".$out['img_edit']."</a></td>"; 
  $adminmain .= "<td><strong>".$info['Name']."</strong></td>";
  $adminmain .= "<td style=\"text-align:center;\">".$x."</td>";
  $adminmain .= "<td style=\"text-align:center;\">".$info['Version']."</td>";
  $adminmain .= "<td style=\"text-align:center;\">".$info['Updated']."</td>";
  $adminmain .= "<td style=\"text-align:center;\">".$info['Author']."</td>";
  $adminmain .= "<td style=\"text-align:center; vertical-align:middle; width:10%;\">";  
	$adminmain .= ($x == $cfg['defaultskin']) ? $out['img_checked'] : '';
  $adminmain .= "</td><td style=\"text-align:center; vertical-align:middle; width:10%;\">";
	$adminmain .= ($x == $skin) ? $out['img_checked'] : '';
  $adminmain .= "</td>"; 
  $adminmain .= "</tr>";
	}
$adminmain .= "</table>";
  
  break;
  
  }

?>
