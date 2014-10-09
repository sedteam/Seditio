<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plug.php
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=Plugin loader
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$p = sed_import('p','G','ALP');
$e = sed_import('e','G','ALP');
$o = sed_import('o','G','ALP');
$s = sed_import('s','G','ALP');
$r = sed_import('r','G','ALP');
$h = sed_import('h','G','ALP');
$c1= sed_import('c1','G','ALP');
$c2 = sed_import('c2','G','ALP');

unset ($plugin_title, $plugin_body);

if (!empty($p))
	{

	$path_lang_def	= "plugins/$p/lang/$p.en.lang.php";
	$path_lang_alt	= "plugins/$p/lang/$p.$lang.lang.php";

	if (file_exists($path_lang_alt))
		{ require($path_lang_alt); }
	elseif (file_exists($path_lang_def))
		{ require($path_lang_def); }

	$extp = array();
	if (is_array($sed_plugins))
		{
		foreach($sed_plugins as $i => $k)
			{
			if ($k['pl_hook']=='module' && $k['pl_code']==$p)
				{ $extp[$i] = $k; }
			}
		}

	if (count($extp)==0)
		{
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
		}

	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

	}
elseif (!empty($e))
	{
	$path_lang_def	= "plugins/$e/lang/$e.en.lang.php";
	$path_lang_alt	= "plugins/$e/lang/$e.$lang.lang.php";
	$path_skin_ntg	= "skins/$skin/plugin.tpl";
	$path_skin_def	= "plugins/$e/$e.tpl";
	$path_skin_alt	= "skins/$skin/plugin.standalone.$e.tpl";

	if (file_exists($path_lang_alt))
		{ require($path_lang_alt); }
	elseif (file_exists($path_lang_def))
		{ require($path_lang_def); }

	if (file_exists($path_skin_alt))
		{
		$path_skin= $path_skin_alt;
		$autoassigntags = FALSE;
		}
	elseif (file_exists($path_skin_def))
		{
		$path_skin = $path_skin_def;
		$autoassigntags = FALSE;
		}
	elseif (file_exists($path_skin_ntg))
		{
		$path_skin = $path_skin_ntg;
		$autoassigntags = TRUE;
		}
	else
		{
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
		}

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', $e);
	sed_block($usr['auth_read']);

	if (is_array($sed_plugins))
		{
		foreach($sed_plugins as $i => $k)
			{
			if ($k['pl_hook']=='standalone' && $k['pl_code']==$e)
				{ $out['subtitle'] = $k['pl_title']; }
			}
		}

	$out['subtitle'] = (empty($L['plu_title'])) ? $out['subtitle'] : $L['plu_title'];
	$sys['sublocation'] = $out['subtitle'];
	$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
	$title_tags[] = array('%1$s', '%2$s', '%3$s');
	$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
	$out['subtitle'] = sed_title('plugtitle', $title_tags, $title_data);

	/* ============= */

	require("system/header.php");

	$t = new XTemplate($path_skin);

	$extp = array();

	if (is_array($sed_plugins))
		{
		foreach($sed_plugins as $i => $k)
			{
			if ($k['pl_hook']=='standalone' && $k['pl_code']==$e)
				{ $extp[$i] = $k; }
			}
		}

	if (count($extp)==0)
		{
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
		}

	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

	if ($autoassigntags)
		{
		$plugin_title = (empty($plugin_title)) ? $L['plu_title'] : $plugin_title;

		$t-> assign(array(
			"PLUGIN_TITLE" => "<a href=\"".sed_url("plug", "e=".$e)."\">".$plugin_title."</a>",
			"PLUGIN_SUBTITLE" => $plugin_subtitle,
			"PLUGIN_BODY" => $plugin_body
			));
		}

	$t->parse("MAIN");
	$t->out("MAIN");

	require("system/footer.php");
	}

elseif (!empty($o))

	{
	$extp = array();
	if (is_array($sed_plugins))
		{
		foreach($sed_plugins as $i => $k)
			{
			if ($k['pl_hook']=='popup' && $k['pl_code']==$o)
				{ $extp[$i] = $k; }
			}
		}

	if (count($extp)==0)
		{
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
		}

	$popup_header1 = $cfg['doctype']."<html><head>".sed_htmlmetas()."\n\n<script type=\"text/javascript\">\n<!--\nfunction add(text)\n	{\nopener.document.".$c1.".".$c2.".value += text; }\n//-->\n</script>\n";
	$popup_header2 = "</head><body>";
	$popup_footer = "</body></html>";

	/* ============= */

	$mskin = sed_skinfile(array('popup', $o));
	$t = new XTemplate($mskin);

	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

	$t->assign(array(
		"POPUP_HEADER1" => $popup_header1,
		"POPUP_HEADER2" => $popup_header2,
		"POPUP_FOOTER" => $popup_footer,
		"POPUP_BODY" => $popup_body,
			));

	$t->parse("MAIN");
	$t->out("MAIN");

	}

elseif (!empty($h))

	{
	if ($h=='smilies')
		{
		if (is_array($sed_smilies))
			{
			$popup_body = $L['Smilies']." (".$L['Smilies_explain'].") :<p>";
			$popup_body .= "<div class=\"smilies\"><table>";
			reset ($sed_smilies);

			while (list($i,$dat) = each($sed_smilies))
				{
				$popup_body .= "<tr><td style=\"text-align:right;\"><a href=\"javascript:add('".$dat['smilie_code']."')\"><img src=\"".$dat['smilie_image']."\"  alt=\"\" /></a></td><td>".$dat['smilie_code']."</td><td> ".sed_cc($dat['smilie_text'])."</td></tr>";
				}
			$popup_body .= "</table></div></p>";
			}
		else
			{ $popup_body = $L['None']; }

		}
	elseif ($h=='bbcodes')
		{
		reset ($sed_bbcodes);
		$ii=0;
		$popup_body = $L['BBcodes']." (".$L['BBcodes_explain'].") :<p>";
		$popup_body .= "<div class=\"bbcodes\"><table><tr>";

		while (list($i,$dat) = each($sed_bbcodes))
			{
			$kk = "bbcodes_".$dat[1];
			if (mb_substr($dat[1], 0, 5)=='style')
			   	{
				$popup_body .= "<td colspan=\"2\"><a href=\"javascript:add('".$dat[0]."')\">";
				$popup_body .= "<span class=\"bb".$dat[1]."\">".$L[$kk]." &nbsp;</span></td>";
			   	}
			else
			   	{
				$popup_body .= "<td><a href=\"javascript:add('".$dat[0]."')\">";
				$popup_body .= "<img src=\"system/img/bbcodes/".$dat[1].".gif\" alt=\"\" /></a></td>";
				$popup_body .= "<td>".$L[$kk]." &nbsp;</td>";
				}

			$ii++;
			if ($ii==3)
				{
				$ii=0;
				$popup_body .= "</tr><tr>";
				}
			}

		$popup_body .= "</table></div></p>";
		}
	else
		{
		$incl = "system/help/$h.txt";
		$fd = @fopen($incl, "r") or die("Couldn't find a file : ".$incl);
		$popup_body = fread($fd, filesize($incl));
		fclose($fd);
		}

	$popup_header1 = $cfg['doctype']."<html><head>".sed_htmlmetas()."\n\n<script type=\"text/javascript\">\n<!--\nfunction add(text)\n	{\nopener.document.".$c1.".".$c2.".value += text; }\n//-->\n</script>\n";
	$popup_header2 = "</head><body>";
	$popup_footer = "</body></html>";

	/* ============= */

	$mskin = sed_skinfile(array('popup', $h));
	$t = new XTemplate($mskin);

	$t->assign(array(
		"POPUP_HEADER1" => $popup_header1,
		"POPUP_HEADER2" => $popup_header2,
		"POPUP_FOOTER" => $popup_footer,
		"POPUP_BODY" => $popup_body,
			));

	$t->parse("MAIN");
	$t->out("MAIN");

	}

elseif (!empty($r))
	{
	

	if (mb_strpos($r, "\.") !== FALSE || mb_strpos($r, "/") !== FALSE)
		{ sed_die(); }

	$incl = 'plugins/code/'.$r.'.php';
	
	

	if (@file_exists($incl))
		{ require($incl); }
	else
		{ sed_die(); }
	
	
	}
else

	{ sed_die(); }

?>