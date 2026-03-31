<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/view/view.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=HTML/TXT view (direct)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=view
Part=main
File=view
Hooks=direct
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

if (!sed_plug_active('view')) {
	exit();
}

$v = sed_import('v', 'G', 'TXT');

if (mb_strpos($v, "\.") !== false || mb_strpos($v, "/") !== false) {
	die('Wrong URL.');
}

$incl_html = SED_ROOT . "/datas/html/" . $v . ".html";
$incl_htm = SED_ROOT . "/datas/html/" . $v . ".htm";
$incl_txt = SED_ROOT . "/datas/html/" . $v . ".txt";

if (file_exists($incl_txt)) {
	$fd = @fopen($incl_txt, 'r') or die("Couldn't find a file : " . $incl_txt);
	$vd = fread($fd, filesize($incl_txt));
	fclose($fd);
} elseif (file_exists($incl_htm)) {
	$fd = @fopen($incl_htm, 'r') or die("Couldn't find a file : " . $incl_htm);
	$vd = fread($fd, filesize($incl_htm));
	fclose($fd);
} elseif (file_exists($incl_html)) {
	$fd = @fopen($incl_html, 'r') or die("Couldn't find a file : " . $incl_html);
	$vd = fread($fd, filesize($incl_html));
	fclose($fd);
} else {
	sed_die();
}

$ext_head = '';
$ext_body = '';

if (preg_match('@<head>(.*?)</head>@si', $vd, $head_match) == 1) {
	$ext_head = $head_match[1];
}

if (preg_match('@<body[^>]*?>(.*?)</body>@si', $vd, $body_match) == 1) {
	$ext_body = $body_match[1];
}

$vt = '&nbsp;';

if (mb_stristr($ext_head, '<meta name="sed_title"') !== false) {
	$vt = mb_stristr($ext_head, '<meta name="sed_title"');
	$vt = mb_stristr($vt, 'content="');
	$vt = mb_substr($vt, 9);
	$tag_title_end = mb_strpos($vt, '">');
	$vt = mb_substr($vt, 0, $tag_title_end);
} elseif (preg_match('@<title>(.*?)</title>@si', $ext_head, $vt) == 1) {
	$vt = $vt[1];
}

$morejavascript = '';
$moremetas = '';

if (preg_match_all('@<script[^>]*?>(.*?)</script>@si', $ext_head, $ext_js) > 0) {
	foreach ($ext_js[1] as $js) {
		$js = preg_replace(array('@<!--(.*?)\n@si', '@\/\/(.*?)-->\n@si'), array('', ''), $js);
		$morejavascript .= $js;
	}
}

if (preg_match_all('@<link[^>](.*?)>@si', $ext_head, $ext_links) > 0) {
	foreach ($ext_links[0] as $link) {
		$moremetas .= $link;
	}
}

require(SED_ROOT . "/system/header.php");
$t = new XTemplate(sed_skinfile('plugin'));

$t->assign(array(
	"PLUGIN_TITLE" => $vt,
	"PLUGIN_BODY" => $ext_body,
));

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
