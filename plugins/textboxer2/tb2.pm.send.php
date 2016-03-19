<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer2/tb2.pm.send.php
Version=177
Updated=2015-feb-06
Type=Plugin
Author=Arkkimaagi
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer2
Part=pm.send
File=tb2.pm.send
Hooks=pm.send.tags
Tags=pm.send.tpl:{PMSEND_FORM_TEXTBOXER}
Order=10
[END_SED_EXTPLUGIN]

=============S======= */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once("plugins/textboxer2/lang/textboxer2.".$usr['lang'].".lang.php");
require_once("plugins/textboxer2/inc/textboxer2.inc.php");

$tb2DropdownIcons = array(-1,49,1,7,10,15,19,23,35,50);
$tb2MaxSmilieDropdownHeight = 300; 	// Height in px for smilie dropdown
$tb2InitialSmilieLimit = 20;		// Smilies loaded by default to dropdown
$tb2TextareaRows = 16;				// Rows of the textarea

// Do not edit below this line !

$tb2ParseBBcodes = TRUE;
$tb2ParseSmilies = TRUE;
$tb2ParseBR = TRUE;

$ta =sed_textboxer2('newpmtext',
			'newlink',
			sed_cc($newpmtext),
			$tb2TextareaRows,
			$tb2TextareaCols,
			'pmsend',
			$tb2ParseBBcodes,
			$tb2ParseSmilies,
			$tb2ParseBR,
			$tb2Buttons,
			$tb2DropdownIcons,
			$tb2MaxSmilieDropdownHeight,
			$tb2InitialSmilieLimit).$pfs;

$t->assign("PMSEND_FORM_TEXT", $ta);
$t->assign("PMSEND_FORM_TEXTBOXER", $ta);

?>
