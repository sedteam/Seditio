<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer2/tb2.page.edit.php
Version=175
Updated=2012-dec-31
Type=Plugin
Author=Arkkimaagi
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer2
Part=page.edit
File=tb2.page.edit
Hooks=page.edit.tags
Tags=page.edit.tpl:{PAGEEDIT_FORM_TEXTBOXER}
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once("plugins/textboxer2/lang/textboxer2.".$usr['lang'].".lang.php");
require_once("plugins/textboxer2/inc/textboxer2.inc.php");

	$tb2Buttons = array(
		'tb_ieOnlyStart',
			2,
				'copy',
				'cut',
				'paste',
			'}',
		'tb_ieOnlyEnd',

		'bold',
		'underline',
		'italic',

		3,
			'left',
			'center',
			'right',
		'}',

		4,
			'quote',
			'spoiler',			
			'code',
			'list',
			'hr',
			'spacer',
			'ac',
			'p',
		'}',

		5,
			'image',
			'thumb',
			'colleft',
			'colright',
		'}',

		6,
			'url',
//			'urlp',
			'email',
//			'emailp',
		'}',

		7,
			'black',
			'grey',
			'sea',
			'blue',
			'sky',
			'green',
			'yellow',
			'orange',
			'red',
			'white',
			'pink',
			'purple',
		'}',

		8,
			'page',
//			'pagep',
			'user',
//			'link',
//			'linkp',
			'flag',
			'pfs',
			'topic',
			'post',
			'pm',
		'}',
    //********************************
		9,
			'youtube',
			'googlevideo',
			'metacafe',
			'rutube',
      'vimeo',
			'vk',
		'}',
    //*******************************
		1,
			'smilies',
		'}',
		'more',
		'title',
		'preview'
	);

$tb2DropdownIcons = array(-1,49,1,7,10,15,19,23,35,50);
$tb2MaxSmilieDropdownHeight = 300; 	// Height in px for smilie dropdown
$tb2InitialSmilieLimit = 20;		// Smilies loaded by default to dropdown
$tb2TextareaRows = 24;				// Rows of the textarea

// Do not edit below this line !

$tb2ParseBBcodes = TRUE;
$tb2ParseSmilies = TRUE;
$tb2ParseBR = TRUE;

$ta = sed_textboxer2('rpagetext',
			'update',
			sed_cc($pag['page_text']),
			$tb2TextareaRows,
			$tb2TextareaCols,
			'pageedit',
			$tb2ParseBBcodes,
			$tb2ParseSmilies,
			$tb2ParseBR,
			$tb2Buttons,
			$tb2DropdownIcons,
			$tb2MaxSmilieDropdownHeight,
			$tb2InitialSmilieLimit).$pfs;
      
$t->assign("PAGEEDIT_FORM_TEXT", $ta);
$t->assign("PAGEEDIT_FORM_TEXTBOXER", $ta);

?>