<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer3/textboxer3.php
Version=175
Updated=2014-nov-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer3
Part=Loader
File=textboxer3
Hooks=header.first,pfs.stndl
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$tb3_detect_lang = $cfg['plugin']['textboxer3']['tb3_detectlang'];

$tb3_lang = ($tb3_detect_lang == "No") ? $cfg['plugin']['textboxer3']['tb3_lang'] : $usr['lang'];

$tb3_mode = ($cfg['textmode'] == 'html') ? 'html' : 'bbcode'; 

// Toolbars
$tb3_toolbar['pages'] = $cfg['plugin']['textboxer3']['tb3_toolbar_pages'];  //pages toolbar
$tb3_toolbar['forums'] = $cfg['plugin']['textboxer3']['tb3_toolbar_forums'];  //forums toolbar
$tb3_toolbar['users'] = $cfg['plugin']['textboxer3']['tb3_toolbar_users']; //users toolbar
$tb3_toolbar['comments'] = $cfg['plugin']['textboxer3']['tb3_toolbar_comments']; //comments toolbar
$tb3_toolbar['pm'] = $cfg['plugin']['textboxer3']['tb3_toolbar_pm']; //pm toolbar
$tb3_toolbar['other'] = $cfg['plugin']['textboxer3']['tb3_toolbar_other']; //toolbar for other textarea 

// Editor Height
$tb3_height['pages'] = $cfg['plugin']['textboxer3']['tb3_pages_height'];  
$tb3_height['forums'] = $cfg['plugin']['textboxer3']['tb3_forums_height']; 
$tb3_height['users'] = $cfg['plugin']['textboxer3']['tb3_users_height']; 
$tb3_height['comments'] = $cfg['plugin']['textboxer3']['tb3_comments_height']; 
$tb3_height['pm'] = $cfg['plugin']['textboxer3']['tb3_pm_height']; 
$tb3_height['other'] = $cfg['plugin']['textboxer3']['tb3_other_height']; 

/* ===== Load smiles ===== */      

$sql2 = sed_sql_query("SELECT * FROM $db_smilies WHERE 1 ORDER BY smilie_order ASC");      

$smiley .= "Tb3.smilies = [";     
while ($row = sed_sql_fetchassoc($sql2)) {
     $smiley .= "[".$row['smilie_id'].", '".$row['smilie_image']."', '".$row['smilie_code']."', '".sed_cc($row['smilie_text'], ENT_QUOTES)."'],";
	}      
$smiley = mb_substr($smiley, 0, mb_strrpos($smiley,","));
$smiley .= "];";

$moremetas .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/textboxer3/lib/theme/style.css\" />
<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/textboxer3/lib/theme/default/style.css\" />
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/tb3.js\"></script>
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/lang/tb3.lang.".$tb3_lang.".js\"></script>
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/addons/commons/addons.js\"></script>
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/conf/tb3.conf.bbcode.js\"></script>
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/conf/tb3.conf.html.js\"></script>
<script type=\"text/javascript\" src=\"plugins/textboxer3/lib/conf/tb3.toolbars.js\"></script>";

$moremetas .= "<script type=\"text/javascript\">
".$smiley."
var tb3textareasName = {'rusertext':'".$tb3_toolbar['users']."','rtext':'".$tb3_toolbar['comments']."','rstext':'".$tb3_toolbar['pages']."','rdesc':'".$tb3_toolbar['users']."','rmsg':'".$tb3_toolbar['forums']."','newmsg':'".$tb3_toolbar['forums']."','newpagetext':'".$tb3_toolbar['pages']."','rpagetext':'".$tb3_toolbar['pages']."','newpmtext':'".$tb3_toolbar['pm']."'};
var tb3textareasHeight = {'rusertext':'".$tb3_height['users']."','rtext':'".$tb3_height['comments']."','rstext':'".$tb3_height['pages']."','rdesc':'".$tb3_height['users']."','rmsg':'".$tb3_height['forums']."','newmsg':'".$tb3_height['forums']."','newpagetext':'".$tb3_height['pages']."','rpagetext':'".$tb3_height['pages']."','newpmtext':'".$tb3_height['pm']."'};

function tb3Replace() {
	var textareas = document.getElementsByTagName('textarea');
	for (var i = 0; i < textareas.length; i++) { 
		if (tb3textareasName[textareas[i].getAttribute('name')] != undefined) {
			 Tb3.toolbar = tb3toolbars[tb3textareasName[textareas[i].getAttribute('name')]];
			 Tb3.bind(textareas[i], '".$tb3_mode."', tb3textareasHeight[textareas[i].getAttribute('name')]);
		}
		if (textareas[i].getAttribute('class') != 'noeditor' && tb3textareasName[textareas[i].getAttribute('name')] == undefined) {
			 Tb3.toolbar = tb3toolbars[tb3textareasName[textareas[i].getAttribute('name')]];
			 Tb3.bind(textareas[i], '".$tb3_mode."', ".$tb3_height['other'].");
		}
	}
}
if (window.addEventListener) { window.addEventListener('load', tb3Replace, false);
} else if (window.attachEvent) { window.attachEvent('onload', tb3Replace); } else { window.onload = tb3Replace; }
</script>";

?>