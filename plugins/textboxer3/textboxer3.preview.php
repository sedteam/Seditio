<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/textboxer3/textboxer3.preview.php
Version=177
Updated=2014-nov-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=textboxer3
Part=Ajax
File=textboxer3.preview
Hooks=ajax
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$textareaname = sed_import('textareaname', 'G', 'TXT');
$textareavalue = sed_import($textareaname, 'P', 'HTM');

$parsevalue = sed_parse($textareavalue, 1, 1, 1, $ishtml);

$preview = $cfg['doctype']."<html><head>
<title>".$cfg['maintitle']."</title>".sed_htmlmetas()."</head>
<body>";

$preview .= $parsevalue;

$preview .= "</body></html>";

sed_sendheaders();

echo $preview;

?>