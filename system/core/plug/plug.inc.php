<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plug.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Plugin loader
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$p = sed_import('p', 'G', 'ALP');
$e = sed_import('e', 'G', 'ALP');
$o = sed_import('o', 'G', 'ALP');
$s = sed_import('s', 'G', 'ALP');
$r = sed_import('r', 'G', 'ALP');
$h = sed_import('h', 'G', 'ALP');
$c1 = sed_import('c1', 'G', 'ALP');
$c2 = sed_import('c2', 'G', 'ALP');
$ajx = sed_import('ajx', 'G', 'ALP');

unset($plugin_title, $plugin_body);

if (!empty($p)) {

	$path_lang_def	= SED_ROOT . "/plugins/$p/lang/$p.en.lang.php";
	$path_lang_alt	= SED_ROOT . "/plugins/$p/lang/$p.$lang.lang.php";

	if (file_exists($path_lang_alt)) {
		require($path_lang_alt);
	} elseif (file_exists($path_lang_def)) {
		require($path_lang_def);
	}

	$extp = array();

	if (is_array($sed_plugins) && isset($sed_plugins['module'])) {
		foreach ($sed_plugins['module'] as $i => $k) {
			if ($k['pl_code'] == $p) {
				$extp[$i] = $k;
			}
		}
	}

	if (count($extp) == 0) {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
} elseif (!empty($e)) {

	$path_lang_def	= SED_ROOT . "/plugins/$e/lang/$e.en.lang.php";
	$path_lang_alt	= SED_ROOT . "/plugins/$e/lang/$e.$lang.lang.php";

	$path_skin_ntg  = sed_skinfile('plugin');
	$path_skin_def  = SED_ROOT . "/plugins/$e/$e.tpl";
	$path_skin_alt  = sed_skinfile($e, true);

	if (file_exists($path_lang_alt)) {
		require($path_lang_alt);
	} elseif (file_exists($path_lang_def)) {
		require($path_lang_def);
	}

	if (!empty($path_skin_alt) && file_exists($path_skin_alt)) {
		$path_skin = $path_skin_alt;
		$autoassigntags = FALSE;
	} elseif (!empty($path_skin_def) && file_exists($path_skin_def)) {
		$path_skin = $path_skin_def;
		$autoassigntags = FALSE;
	} elseif (!empty($path_skin_ntg) && file_exists($path_skin_ntg)) {
		$path_skin = $path_skin_ntg;
		$autoassigntags = TRUE;
	} else {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', $e);
	sed_block($usr['auth_read']);

	if (is_array($sed_plugins) && isset($sed_plugins['standalone'])) {
		foreach ($sed_plugins['standalone'] as $i => $k) {
			if ($k['pl_code'] == $e) {
				$out['subtitle'] = $k['pl_title'];
			}
		}
	}

	$out['subtitle'] = (empty($L['plu_title'])) ? $out['subtitle'] : $L['plu_title'];
	$sys['sublocation'] = $out['subtitle'];
	$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
	$title_tags[] = array('%1$s', '%2$s', '%3$s');
	$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
	$out['subtitle'] = sed_title('plugtitle', $title_tags, $title_data);

	/* ============= */

	require(SED_ROOT . "/system/header.php");

	$t = new XTemplate($path_skin);

	$extp = array();

	if (is_array($sed_plugins) && isset($sed_plugins['standalone'])) {
		foreach ($sed_plugins['standalone'] as $i => $k) {
			if ($k['pl_code'] == $e) {
				$extp[$i] = $k;
			}
		}
	}

	if (count($extp) == 0) {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	if ($autoassigntags) {
		$plugin_title = (empty($plugin_title)) ? $L['plu_title'] : $plugin_title;

		// ---------- Breadcrumbs
		$urlpaths = array();
		$urlpaths[sed_url("plug", "e=" . $e)] = $plugin_title;

		$t->assign(array(
			"PLUGIN_TITLE" => "<a href=\"" . sed_url("plug", "e=" . $e) . "\">" . $plugin_title . "</a>",
			"PLUGIN_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
			"PLUGIN_SHORTTITLE" => $plugin_title,
			"PLUGIN_URL" => sed_url("plug", "e=" . $e),
			"PLUGIN_SUBTITLE" => isset($plugin_subtitle) ? $plugin_subtitle : '',
			"PLUGIN_BODY" => $plugin_body
		));
	}

	$t->parse("MAIN");
	$t->out("MAIN");

	require(SED_ROOT . "/system/footer.php");
} elseif (!empty($o)) {

	$path_lang_def	= SED_ROOT . "/plugins/$o/lang/$o.en.lang.php";
	$path_lang_alt	= SED_ROOT . "/plugins/$o/lang/$o.$lang.lang.php";

	if (file_exists($path_lang_alt)) {
		require($path_lang_alt);
	} elseif (file_exists($path_lang_def)) {
		require($path_lang_def);
	}

	$extp = array();
	if (is_array($sed_plugins) && isset($sed_plugins['popup'])) {
		foreach ($sed_plugins['popup'] as $i => $k) {
			if ($k['pl_code'] == $o) {
				$extp[$i] = $k;
			}
		}
	}

	if (count($extp) == 0) {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';

	$popup_header1 = $cfg['doctype'] . "<html><head>" . sed_htmlmetas() . $moremetas . sed_javascript($morejavascript) . "\n\n<script type=\"text/javascript\">\n<!--\nfunction add(text)\n	{\n" . $openerparent . ".document." . $c1 . "." . $c2 . ".value += text; }\n//-->\n</script>\n";
	$popup_header2 = "</head><body>";
	$popup_footer = "</body></html>";

	/* ============= */

	$mskin = sed_skinfile(array('popup', $o));
	$t = new XTemplate($mskin);

	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}

	$t->assign(array(
		"POPUP_HEADER1" => $popup_header1,
		"POPUP_HEADER2" => $popup_header2,
		"POPUP_FOOTER" => $popup_footer,
		"POPUP_BODY" => $popup_body,
	));

	$t->parse("MAIN");
	$t->out("MAIN");
} elseif (!empty($h)) {

	$incl = "system/help/$h.txt";
	$fd = @fopen($incl, "r") or die("Couldn't find a file : " . $incl);
	$popup_body = fread($fd, filesize($incl));
	fclose($fd);

	$openerparent = ($cfg['enablemodal']) ? 'parent' : 'opener';

	$popup_header1 = $cfg['doctype'] . "<html><head>" . sed_htmlmetas() . "\n\n<script type=\"text/javascript\">\n<!--\nfunction add(text)\n	{\n" . $openerparent . ".document." . $c1 . "." . $c2 . ".value += text; }\n//-->\n</script>\n";
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
} elseif (!empty($r)) {

	if (mb_strpos($r, "\.") !== FALSE || mb_strpos($r, "/") !== FALSE) {
		sed_die();
	}

	$incl = 'plugins/code/' . $r . '.php';

	if (@file_exists($incl)) {
		require($incl);
	} else {
		sed_die();
	}
} elseif (!empty($ajx)) {
	$extp = array();
	if (is_array($sed_plugins) && isset($sed_plugins['ajax'])) {
		foreach ($sed_plugins['ajax'] as $i => $k) {
			if ($k['pl_code'] == $ajx) {
				$extp[$i] = $k;
			}
		}
	}

	if (count($extp) == 0) {
		sed_redirect(sed_url("message", "msg=907", "", true));
		exit;
	}

	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
} else {
	sed_die();
}
