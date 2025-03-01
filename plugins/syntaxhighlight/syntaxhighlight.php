<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/syntaxhighlight/syntaxhighlight.php
Version=180
Updated=2025-mar-01
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

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$sh_theme = $cfg['plugin']['syntaxhighlight']['syntaxhighlight_theme'] . ".css";
$sv_ver = $cfg['plugin']['syntaxhighlight']['syntaxhighlight_version'];
$sh_dir = 'plugins/syntaxhighlight/lib/sh' . $sv_ver . '/scripts/';

$sh_autoloader = "
	SyntaxHighlighter.autoloader(
		'applescript			" . $sh_dir . "shBrushAppleScript.js',
		'actionscript3 as3		" . $sh_dir . "shBrushAS3.js',
		'bash shell				" . $sh_dir . "shBrushBash.js',
		'coldfusion cf			" . $sh_dir . "shBrushColdFusion.js',
		'cpp c					" . $sh_dir . "shBrushCpp.js',
		'c# c-sharp csharp		" . $sh_dir . "shBrushCSharp.js',
		'css					" . $sh_dir . "shBrushCss.js',
		'delphi pascal			" . $sh_dir . "shBrushDelphi.js',
		'diff patch pas			" . $sh_dir . "shBrushDiff.js',
		'erl erlang				" . $sh_dir . "shBrushErlang.js',
		'groovy					" . $sh_dir . "shBrushGroovy.js',
		'haxe hx				" . $sh_dir . "shBrushHaxe.js',
		'java					" . $sh_dir . "shBrushJava.js',
		'jfx javafx				" . $sh_dir . "shBrushJavaFX.js',
		'js jscript javascript	" . $sh_dir . "shBrushJScript.js',
		'perl pl				" . $sh_dir . "shBrushPerl.js',
		'php					" . $sh_dir . "shBrushPhp.js',
		'text plain				" . $sh_dir . "shBrushPlain.js',
		'py python				" . $sh_dir . "shBrushPython.js',
		'ruby rails ror rb		" . $sh_dir . "shBrushRuby.js',
		'scala					" . $sh_dir . "shBrushScala.js',
		'sql					" . $sh_dir . "hBrushSql.js',
		'vb vbnet				" . $sh_dir . "shBrushVb.js',
		'xml xhtml xslt html	" . $sh_dir . "shBrushXml.js'
	);
	SyntaxHighlighter.all();
";

sed_add_javascript('plugins/syntaxhighlight/lib/sh' . $sv_ver . '/scripts/shCore.js', true);

if ($sv_ver == 3) {
	sed_add_javascript('plugins/syntaxhighlight/lib/sh' . $sv_ver . '/scripts/shAutoloader.js', true);
	sed_add_javascript($sh_autoloader);
} else {
	sed_add_javascript('plugins/syntaxhighlight/lib/sh' . $sv_ver . '/scripts/shBrush.js', true);
	sed_add_javascript('SyntaxHighlighter.all();');
}

sed_add_css('plugins/syntaxhighlight/lib/sh' . $sv_ver . '/styles/shCore.css', true);
sed_add_css('plugins/syntaxhighlight/lib/sh' . $sv_ver . '/styles/shTheme' . $sh_theme, true);
