<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/syntaxhighlight/syntaxhighlight.php
Version=177
Updated=2012-feb-16
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=syntaxhighlight
Part=Loader
File=syntaxhighlight
Hooks=header.first
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

if (!defined('SED_CODE')) { die('Wrong URL.'); }

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$syntaxhighlight_theme = $cfg['plugin']['syntaxhighlight']['syntaxhighlight_theme'].".css";

$moremetas .= "<script type=\"text/javascript\" src=\"plugins/syntaxhighlight/scripts/shCore.js\"></script>
<script type=\"text/javascript\" src=\"plugins/syntaxhighlight/scripts/shBrush.js\"></script>
<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/syntaxhighlight/styles/shCore.css\"/>
<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/syntaxhighlight/styles/".$syntaxhighlight_theme."\"/>
<script type=\"text/javascript\">SyntaxHighlighter.config.clipboardSwf = 'plugins/syntaxhighlight/scripts/clipboard.swf'; SyntaxHighlighter.all();</script>";

?>