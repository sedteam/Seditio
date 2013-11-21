<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.polls.php
Version=172
Updated=2012-feb-16
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ckeditor
Part=Loader
File=ckeditor.polls
Hooks=polls.main
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

if (!defined('SED_CODE')) { die('Wrong URL.'); }

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

global $usr, $db_smilies;

if ($usr['maingrp'] > 3) {
  /* ===== Load configuration Ckeditor ===== */
  $ckeditor_detect_lang = $cfg['plugin']['ckeditor']['ckeditor_detectlang'];
  
  $ckeditor_lang = ($ckeditor_detect_lang == "No") ? $cfg['plugin']['ckeditor']['ckeditor_lang'] : $usr['lang'];
    
  $ckeditor_skin = $cfg['plugin']['ckeditor']['ckeditor_skin'];
  $ckeditor_color_toolbar = $cfg['plugin']['ckeditor']['ckeditor_color_toolbar'];
  //
  $ckeditor_other_height = $cfg['plugin']['ckeditor']['ckeditor_other_height'];
  $ckeditor_other_toolbar = $cfg['plugin']['ckeditor']['ckeditor_other_toolbar'];
  $ckeditor_other_textarea = $cfg['plugin']['ckeditor']['ckeditor_other_textarea']; 
  //
  $ckeditor_global_toolbar = 'Default';
  $tmp = 'ckeditor_grp'.$usr['maingrp']; 
  $ckeditor_global_toolbar = (!empty($cfg['plugin']['ckeditor'][$tmp])) ? $cfg['plugin']['ckeditor'][$tmp] : $ckeditor_global_toolbar;
  //
  if ($location == 'Forums') { $cfnt = 'newmsg'; $cfnh = 'newmsg_height'; } else  { $cfnt = 'rtext'; $cfnh = 'rtext_height'; } 
  //  
  $ck_rusertext_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor']['rusertext'] : $ckeditor_global_toolbar;
  $ck_rtext_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor'][$cfnt] : $ckeditor_global_toolbar;  
  $ck_newmsg_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor']['newmsg'] : $ckeditor_global_toolbar; 
  $ck_newpagetext_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor']['newpagetext'] : $ckeditor_global_toolbar;
  $ck_rpagetext_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor']['rpagetext'] : $ckeditor_global_toolbar;
  $ck_newpmtext_toolbar = ($ckeditor_global_toolbar == 'Default') ? $cfg['plugin']['ckeditor']['newpmtext'] : $ckeditor_global_toolbar; 
  //
  $ck_rusertext_height = $cfg['plugin']['ckeditor']['rusertext_height'];  
  $ck_rtext_height = $cfg['plugin']['ckeditor'][$cfnh];
  $ck_newmsg_height = $cfg['plugin']['ckeditor']['newmsg_height'];
  $ck_newpagetext_height = $cfg['plugin']['ckeditor']['newpagetext_height'];
  $ck_rpagetext_height = $cfg['plugin']['ckeditor']['rpagetext_height'];
  $ck_newpmtext_height = $cfg['plugin']['ckeditor']['newpmtext_height'];
  //
  $CkTextareas_option = "CkTextareasName['rusertext'] = '".$ck_rusertext_toolbar."'; "."CkTextareasHeight['rusertext'] = '".$ck_rusertext_height."';";
  $CkTextareas_option .= "CkTextareasName['rtext'] = '".$ck_rtext_toolbar."'; "."CkTextareasHeight['rtext'] = '".$ck_rtext_height."';";
  $CkTextareas_option .= "CkTextareasName['newmsg'] = '".$ck_newmsg_toolbar."'; "."CkTextareasHeight['newmsg'] = '".$ck_newmsg_height."';";
  $CkTextareas_option .= "CkTextareasName['newpagetext'] = '".$ck_newpagetext_toolbar."'; "."CkTextareasHeight['newpagetext'] = '".$ck_newpagetext_height."';";
  $CkTextareas_option .= "CkTextareasName['rpagetext'] = '".$ck_rpagetext_toolbar."'; "."CkTextareasHeight['rpagetext'] = '".$ck_rpagetext_height."';";
  $CkTextareas_option .= "CkTextareasName['newpmtext'] = '".$ck_newpmtext_toolbar."'; "."CkTextareasHeight['newpmtext'] = '".$ck_newpmtext_height."';";     
  /* ===== Load smiles ===== */      
  $sql2 = sed_sql_query("SELECT * FROM $db_smilies WHERE 1 ORDER BY smilie_order ASC");      
  $smiley_path = "[";
  $smiley_descriptions = "[";       
  while ($row = sed_sql_fetchassoc($sql2)) {
       $row['smilie_text'] = sed_cc($row['smilie_text']);
       $smiley_path .= "'".$row['smilie_image']."',";
  	   $smiley_descriptions .= "'".sed_cc($row['smilie_text'], ENT_QUOTES)."',";
  	}      
  $pointpos_sp = mb_strrpos($smiley_path,",")+1;
  $smiley_path = mb_substr($smiley_path, 0, $pointpos_sp-1);	
  $pointpos_sd = mb_strrpos($smiley_descriptions,",")+1;
  $smiley_descriptions = mb_substr($smiley_descriptions, 0, $pointpos_sd-1);	
  $smiley_path .= "]";
  $smiley_descriptions .= "]";      	
  $ck_config = "sed_config.js";     	
  /* ===== Init Ckeditor ===== */  
  if ($ckeditor_other_textarea == "Yes") {  
  $ck_other = "if (textareas[i].getAttribute('class') != 'noeditor') {      
        CKEDITOR.config.customConfig = '".$ck_config."';
        CKEDITOR.replace(textareas[i], {toolbar: '".$ckeditor_other_toolbar."', skin: '".$ckeditor_skin."',  language: '".$ckeditor_lang."', uiColor: '".$ckeditor_color_toolbar."', smiley_path: '/', smiley_images: ".$smiley_path.", 
        smiley_descriptions: ".$smiley_descriptions.",         
        height: ".$ckeditor_other_height."});             
      }";  
  } else { $ck_other = ""; }  
  //  
  $init_ck = "<script type=\"text/javascript\">
  var CkTextareasName = Array(); var CkTextareasHeight = Array(); ".$CkTextareas_option."
  function ckeditorReplace() { 
    var textareas = document.getElementsByTagName('textarea');
  	for (var i = 0; i < textareas.length; i++) { 
      if (CkTextareasName[textareas[i].getAttribute('name')] != undefined) {
        CKEDITOR.config.customConfig = '".$ck_config."';
        CKEDITOR.replace(textareas[i], {toolbar: CkTextareasName[textareas[i].getAttribute('name')], skin: '".$ckeditor_skin."',  language: '".$ckeditor_lang."', uiColor: '".$ckeditor_color_toolbar."', smiley_path: '/', smiley_images: ".$smiley_path.", 
        smiley_descriptions: ".$smiley_descriptions.",         
        height: CkTextareasHeight[textareas[i].getAttribute('name')]}); 
      } ".$ck_other."
      }}
  if (typeof jQuery == 'undefined') { if (window.addEventListener) { window.addEventListener('load', ckeditorReplace, false);
  	} else if (window.attachEvent) { window.attachEvent('onload', ckeditorReplace); } else { window.onload = ckeditorReplace; }
  } else { $(document).ready(ckeditorReplace); ajaxSuccessHandlers.push(ckeditorReplace); }
  </script>";
  $polls_header2 = "<script src=\"plugins/ckeditor/lib/ckeditor.js\" type=\"text/javascript\"></script>".$init_ck."\n".$polls_header2;
}

?>

