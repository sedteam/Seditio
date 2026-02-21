<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/functions.admin.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=Functions
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

/* ======== Defaulting the admin variables ========= */

unset($adminmain, $adminhelp, $admin_icon, $plugin_body, $plugin_title, $plugin_help);
$adminpath = array();

/** 
 * Converts an access character mask into a permission byte 
 * 
 * @param string $mask Access character mask, e.g. 'RW1A' 
 * @return int 
 */
function sed_auth_getvalue($mask)
{
	$mn['0'] = 0;
	$mn['R'] = 1;
	$mn['W'] = 2;
	$mn['1'] = 4;
	$mn['2'] = 8;
	$mn['3'] = 16;
	$mn['4'] = 32;
	$mn['5'] = 64;
	$mn['A'] = 128;

	$masks = str_split($mask);
	$res = 0;

	foreach ($mn as $k => $v) {
		if (in_array($k, $masks)) {
			$res += $mn[$k];
		}
	}
	return ($res);
}

/**
 * Install auth rights for one (auth_code, auth_option): insert missing rows per group.
 * Uses letter masks (R, W, 1-5, A) converted via sed_auth_getvalue().
 *
 * @param string $auth_code Area code (e.g. 'page', 'forums', 'plug').
 * @param string|int $auth_option Option (e.g. category code, section id).
 * @param array $rights_by_group [groupid => letter_mask], e.g. [1 => 'R', 4 => 'RW']. Empty string = 0.
 * @param array|null $lock_by_group Same keyed by groupid for lock; null = 0 for all.
 * @param int $setby_userid Value for auth_setbyuserid.
 * @return void
 */
function sed_auth_install_option($auth_code, $auth_option, $rights_by_group, $lock_by_group = null, $setby_userid = 1)
{
	global $db_auth;

	$auth_code = sed_sql_prep($auth_code);
	$auth_option = is_int($auth_option) ? (int)$auth_option : sed_sql_prep($auth_option);
	$setby_userid = (int)$setby_userid;

	foreach ($rights_by_group as $gid => $mask) {
		$gid = (int)$gid;
		$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='$auth_code' AND auth_option='" . sed_sql_prep($auth_option) . "' AND auth_groupid=$gid LIMIT 1");
		if (sed_sql_numrows($chk) > 0) {
			continue;
		}
		$rval = sed_auth_getvalue($mask === '' ? '' : $mask);
		$lval = ($lock_by_group !== null && array_key_exists($gid, $lock_by_group)) ? sed_auth_getvalue($lock_by_group[$gid] === '' ? '' : $lock_by_group[$gid]) : 0;
		sed_sql_query("INSERT INTO $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES ($gid, '$auth_code', '" . sed_sql_prep($auth_option) . "', " . (int)$rval . ", " . (int)$lval . ", $setby_userid)");
	}
}

/**
 * Install auth rights for multiple options with the same rights per group.
 *
 * @param string $auth_code Area code.
 * @param string|int|array $options Single option or array of options.
 * @param array $rights_by_group [groupid => letter_mask].
 * @param array|null $lock_by_group Same for lock; null = 0 for all.
 * @param int $setby_userid Value for auth_setbyuserid.
 * @return void
 */
function sed_auth_install($auth_code, $options, $rights_by_group, $lock_by_group = null, $setby_userid = 1)
{
	$options = is_array($options) ? $options : array($options);
	foreach ($options as $opt) {
		sed_auth_install_option($auth_code, $opt, $rights_by_group, $lock_by_group, $setby_userid);
	}
}

/** 
 * Optimizes auth table by sorting its rows 
 * @global $db_auth 
 */
function sed_auth_reorder()
{
	global $db_auth;

	$sql = sed_sql_query("ALTER TABLE $db_auth ORDER BY auth_code ASC, auth_option ASC, auth_groupid ASC, auth_code ASC");
	return (TRUE);
}

/** 
 * Reset user auth
 *   
 * @global string $db_users
 * @return bool  
 */
function sed_auth_reset()
{
	global $db_users;
	$sql = sed_sql_query("UPDATE $db_users SET user_auth='' WHERE 1");
	return (TRUE);
}

/** 
 * Returns an access character mask for a given access byte 
 * 
 * @param int $rn Permission byte 
 * @return string 
 */
function sed_build_admrights($rn)
{
	$res = ($rn & 1) ? 'R' : '';
	$res .= (($rn & 2) == 2) ? 'W' : '';
	$res .= (($rn & 4) == 4) ? '1' : '';
	$res .= (($rn & 8) == 8) ? '2' : '';
	$res .= (($rn & 16) == 16) ? '3' : '';
	$res .= (($rn & 32) == 32) ? '4' : '';
	$res .= (($rn & 64) == 64) ? '5' : '';
	$res .= (($rn & 128) == 128) ? 'A' : '';
	return ($res);
}

/** 
 * Build admin sections path 
 * 
 * @global array $cfg Config array
 * @global array $L Lang array
 * @param array $adminpath Array with path links 
 * @return string 
 */
function sed_build_adminsection($adminpath, $breadcrumbsclass = "", $homeicon = "")
{
	global $cfg, $L;

	$result = array();
	$adminhome = sed_link(sed_url("admin"), $homeicon . $L['Adminpanel']);
	$result[] = $adminhome;
	$bread = "<ul class=\"" . $breadcrumbsclass . "\"><li>" . $adminhome . "</li>";
	foreach ($adminpath as $i => $k) {
		$result[] = sed_link($k[0], $k[1]);
		$bread .=	'<li>' . sed_link($k[0], $k[1]) . '</li>';
	}
	$result = implode(" " . $cfg['separator'] . " ", $result);
	$bread .= "</ul>";
	return ((!empty($breadcrumbsclass)) ? $bread : $result);
}

/** 
 * Build admin breadcrumbs 
 * 
 * @global array $urlpaths Urls and titles array
 * @global int $startpos Position 
 * @param bool $home Home link flag 
 * @return string 
 */
function sed_admin_breadcrumbs($urlpaths, $startpos = 1, $home = true)
{
	global $L, $t;

	if (is_array($urlpaths)) {
		$isarray = true;
	} else {
		$urlpaths = explode(',', $urlpaths);
	}

	$urlpathadmhome = array();
	$urlpathadmhome[sed_url("admin")] = $L['Adminpanel'];
	$urlpaths = ($home) ? array_merge($urlpathadmhome, $urlpaths) : $urlpaths;

	$b = new XTemplate(sed_skinfile('admin.breadcrumbs', false, true));

	foreach ($urlpaths as $url => $title) {
		$b->assign(array(
			"BREADCRUMB_URL" => $url,
			"BREADCRUMB_TITLE" => $title,
			"BREADCRUMB_POSITION" => $startpos
		));
		$startpos++;
		$b->parse("BREADCRUMBS.BREADCRUMBS_LIST");
	}

	$b->parse("BREADCRUMBS");
	$breadcrumbs = $b->text("BREADCRUMBS");
	$t->assign("BREADCRUMBS", $breadcrumbs);

	return $breadcrumbs;
}


/** 
 * Registers a set of configuration entries at once. 
 *
 * @global string $db_config Name of MySQL table config  
 * @param string $owner Option type core or plug 
 * @param string $cat Structure category code. Only for per-category config options
 * @param string $order A string that determines position of the option in the list    
 * @param string $name Option name, alphanumeric. Must be unique for a module/plugin 
 * @param string $type Option type
 * @param string $default Default and initial value, by default is an empty string
 * @param string $text Textual description. It is usually omitted and stored in langfiles 
 * @param string $variants A comma separated (without spaces) list of possible values, only for SELECT options. 
 */
function sed_config_add($owner, $cat, $order, $name, $type, $value, $default, $text, $variants)
{
	global $db_config;

	switch ($type) {
		case 'string':
			$type1 = 1;
			break;

		case 'select':
			$type1 = 2;
			break;

		case 'radio':
			$type1 = 3;
			break;

		case 'text':
			$type1 = 0;
			break;

		default:
			$type1 = 0;
			break;
	}

	$sql = sed_sql_query("INSERT into $db_config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default, config_text, config_variants)
            VALUES ('$owner', '$cat', '$order', '$name', " . (int)$type1 . ", '$value', '$default', '" . sed_sql_prep($text) . "', '$variants')");
}

/* Forum admin functions moved to modules/forums/inc/forums.functions.php */

/** 
 * Returns link or title url depending on the permissions
 * 
 * @param string $url Url
 * @param string $text Title url  
 * @param string $cond Permissions
 * @param string $class CSS class for link  
 * @return string 
 */
function sed_linkif($url, $text, $cond, $class = "")
{
	$class = (empty($class)) ? "" : " class=\"" . $class . "\"";
	if ($cond) {
		$res = sed_link($url,  '<span>' . $text . '</span>', $class);
	} else {
		$res = "<span>" . $text . "</span>";
	}

	return ($res);
}

/** 
 * Load charsets into Array
 * 
 * @return array 
 */
function sed_loadcharsets()
{
	$result = array();
	$result[] = array('ISO-10646-UTF-1', 'ISO-10646-UTF-1 / Universal Transfer Format');
	$result[] = array('UTF-8', 'UTF-8 / Standard Unicode');
	$result[] = array('ISO-8859-1', 'ISO-8859-1 / Western Europe');
	$result[] = array('ISO-8859-2', 'ISO-8859-2 / Middle Europe');
	$result[] = array('ISO-8859-3', 'ISO-8859-3 / Maltese');
	$result[] = array('ISO-8859-4', 'ISO-8859-4 / Baltic');
	$result[] = array('ISO-8859-5', 'ISO-8859-5 / Cyrillic');
	$result[] = array('ISO-8859-6', 'ISO-8859-6 / Arabic');
	$result[] = array('ISO-8859-7', 'ISO-8859-7 / Greek');
	$result[] = array('ISO-8859-8', 'ISO-8859-8 / Hebrew');
	$result[] = array('ISO-8859-9', 'ISO-8859-9 / Turkish');
	$result[] = array('ISO-2022-KR', 'ISO-2022-KR / Korean');
	$result[] = array('ISO-2022-JP', 'ISO-2022-JP / Japanese');
	$result[] = array('windows-1250', 'windows-1250 / Central European');
	$result[] = array('windows-1251', 'windows-1251 / Russian');
	$result[] = array('windows-1252', 'windows-1252 / Western Europe');
	$result[] = array('windows-1254', 'windows-1254 / Turkish');
	$result[] = array('EUC-JP', 'EUC-JP / Japanese');
	$result[] = array('GB2312', 'GB2312 / Chinese simplified');
	$result[] = array('BIG5', 'BIG5 / Chinese traditional');
	$result[] = array('tis-620', 'Tis-620 / Thai');
	return ($result);
}

/** 
 * Load default config (use only install mode, deprecated)
 * 
 * @return array 
 */
function sed_loadconfigmap()
{
	$result = array();
	$result[] = array('main', '01', 'maintitle', 1, 'Title of your site', '');
	$result[] = array('main', '02', 'subtitle', 1, 'Subtitle', '');
	$result[] = array('main', '03', 'mainurl', 1, 'http://www.yourdomain.com', '');
	$result[] = array('main', '03', 'multihost', 3, '1', '');    // New in v175
	$result[] = array('main', '04', 'absurls', 3, '0', '');   // New in v175
	$result[] = array('main', '04', 'sefurls', 3, '1', '');   // New in v175
	$result[] = array('main', '04', 'sefurls301', 3, '0', '');   // New in v175
	$result[] = array('main', '04', 'adminemail', 1, 'admin@mysite.com', '');
	$result[] = array('main', '05', 'hostip', 1, '999.999.999.999', '');
	$result[] = array('main', '06', 'cache', 3, '1', '');
	$result[] = array('main', '06', 'gzip', 3, '1', '');
	$result[] = array('main', '07', 'devmode', 3, '0', '');
	$result[] = array('main', '10', 'cookiedomain', 1, '', '');
	$result[] = array('main', '10', 'cookiepath', 1, '', '');
	$result[] = array('main', '10', 'cookielifetime', 2, '5184000', array(1800, 3600, 7200, 14400, 28800, 43200, 86400, 172800, 259200, 604800, 1296000, 2592000, 5184000));
	$result[] = array('main', '12', 'disablehitstats', 3, '0', '');
	$result[] = array('main', '13', 'ajax', 3, '1', ''); //Sed 175
	$result[] = array('main', '14', 'enablemodal', 3, '1', ''); //Sed 175
	$result[] = array('main', '20', 'shieldenabled', 3, '0', '');
	$result[] = array('main', '20', 'shieldtadjust', 2, '100', array(10, 25, 50, 75, 100, 125, 150, 200, 300, 400, 600, 800));
	$result[] = array('main', '20', 'shieldzhammer', 2, '25', array(5, 10, 15, 20, 25, 30, 40, 50, 100));
	$result[] = array('main', '21', 'maintenance', 3, '0', ''); //Sed 175
	$result[] = array('main', '22', 'maintenancelevel', 2, '95', array(0, 1, 2, 3, 4, 5, 7, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 99)); //Sed 175
	$result[] = array('main', '23', 'maintenancereason', 1, 'The site is in maintenance mode!', ''); //Sed 175

	$result[] = array('index', '01', 'hometitle', 1, '', ''); // ---- New in v179
	$result[] = array('index', '02', 'homemetadescription', 1, '', ''); // ---- New in v179
	$result[] = array('index', '03', 'homemetakeywords', 1, '', ''); // ---- New in v179

	$result[] = array('time', '11', 'dateformat', 1, 'Y-m-d H:i', '');
	$result[] = array('time', '11', 'formatmonthday', 1, 'm-d', '');
	$result[] = array('time', '11', 'formatyearmonthday', 1, 'Y-m-d', '');
	$result[] = array('time', '11', 'formatmonthdayhourmin', 1, 'm-d H:i', '');
	$result[] = array('time', '11', 'servertimezone', 1, '0', '');
	$result[] = array('time', '12', 'defaulttimezone', 1, '0', '');
	$result[] = array('time', '14', 'timedout', 2, '1200', array(30, 60, 120, 300, 600, 900, 1200, 1800, 2400, 3600));

	$result[] = array('meta', '01', 'defaulttitle', 1, '{MAINTITLE} - {SUBTITLE}', '');  //Sed 175
	$result[] = array('meta', '02', 'indextitle', 1, '{MAINTITLE} - {TITLE}', '');  //Sed 179
	/* listtitle, pagetitle moved to modules/page/page.setup.php */
	/* forumstitle config moved to forums module */
	$result[] = array('meta', '06', 'userstitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175
	/* pmtitle moved to modules/pm/pm.setup.php */
	/* gallerytitle moved to modules/gallery */
	/* pfstitle moved to modules/pfs/pfs.setup.php */
	$result[] = array('meta', '10', 'plugtitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175

	$result[] = array('skin', '02', 'forcedefaultskin', 3, '1', '');
	$result[] = array('skin', '04', 'doctypeid', 4, '8', '');
	$result[] = array('skin', '06', 'charset', 4, 'UTF-8', '');
	$result[] = array('skin', '08', 'metakeywords', 1, '', '');
	$result[] = array('skin', '08', 'separator', 1, '&raquo;', '');
	$result[] = array('skin', '15', 'disablesysinfos', 3, '1', '');
	$result[] = array('skin', '15', 'keepcrbottom', 3, '1', '');
	$result[] = array('skin', '15', 'showsqlstats', 3, '0', '');
	$result[] = array('skin', '16', 'defskin', 7, '', '');
	$result[] = array('lang', '10', 'forcedefaultlang', 3, '0',  '');
	$result[] = array('menus', '10', 'topline', 0, '', '');
	$result[] = array('menus', '10', 'banner', 0, '', '');
	$result[] = array('menus', '10', 'bottomline', 0, '', '');
	$result[] = array('menus', '15', 'menu1', 0, '', '');
	$result[] = array('menus', '15', 'menu2', 0, '',  '');
	$result[] = array('menus', '15', 'menu3', 0, '', '');
	$result[] = array('menus', '15', 'menu4', 0, '', '');
	$result[] = array('menus', '15', 'menu5', 0, '', '');
	$result[] = array('menus', '15', 'menu6', 0, '', '');
	$result[] = array('menus', '15', 'menu7', 0, '', '');
	$result[] = array('menus', '15', 'menu8', 0, '', '');
	$result[] = array('menus', '15', 'menu9', 0, '', '');
	$result[] = array('menus', '20', 'freetext1', 0, '', '');
	$result[] = array('menus', '20', 'freetext2', 0, '', '');
	$result[] = array('menus', '20', 'freetext3', 0, '', '');
	$result[] = array('menus', '20', 'freetext4', 0, '', '');
	$result[] = array('menus', '20', 'freetext5', 0, '', '');
	$result[] = array('menus', '20', 'freetext6', 0, '', '');
	$result[] = array('menus', '20', 'freetext7', 0, '', '');
	$result[] = array('menus', '20', 'freetext8', 0, '', '');
	$result[] = array('menus', '20', 'freetext9', 0, '', '');
	/* Comments config in plug (plugins/comments) */
	/* Forums config moved to modules/forums/forums.setup.php */
	/* disable_page removed: pages on/off only via Admin → Modules (sed_module_active('page')) */
	/* page config (showpagesubcatgroup, maxrowsperpage, genseourls) in modules/page/page.setup.php */
	/* PFS config (disable_pfs, pfs_filemask, pfstitle) in modules/pfs/pfs.setup.php */
	/* Images/thumbs (th_*, available_image_sizes) in core category 'images' */
	$result[] = array('images', '03', 'available_image_sizes', 1, '', '');
	$result[] = array('images', '10', 'th_amode', 2, 'GD2', array('Disabled', 'GD2', 'Imagick'));
	$result[] = array('images', '10', 'th_x', 2, '112', '');
	$result[] = array('images', '10', 'th_y', 2, '84', '');
	$result[] = array('images', '10', 'th_dimpriority', 2, 'Width', array('Width', 'Height'));
	$result[] = array('images', '10', 'th_keepratio', 3, '1', '');
	$result[] = array('images', '10', 'th_jpeg_quality', 2, '85', array(0, 5, 10, 20, 30, 40, 50, 60, 70, 75, 80, 85, 90, 95, 100));
	$result[] = array('images', '10', 'th_rel', 2, 'sedthumb', '');
	$result[] = array('images', '12', 'th_imgmaxwidth', 2, '600', '');
	$result[] = array('images', '20', 'th_logofile', 1, '', '');
	$result[] = array('images', '21', 'th_logopos', 2, 'Bottom left', array('Top left', 'Top right', 'Bottom left', 'Bottom right'));
	$result[] = array('images', '22', 'th_logotrsp', 2, '50', array(0, 5, 10, 15, 20, 30, 40, 50, 60, 70, 80, 90, 95, 100));
	$result[] = array('images', '23', 'th_logojpegqual', 2, '90', array(0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 95, 100));
	/* RSS config moved to modules/rss (install via Admin → Modules) */
	/* Gallery config moved to modules/gallery (install via Admin → Modules) */
	$result[] = array('plug', '01', 'disable_plug', 3, '0', '');
	/* PM config (pm_maxsize, pm_allownotifications) in modules/pm/pm.setup.php */
	/* polls: use module on/off (Admin → Modules), no disable_polls config */
	/* ratings: use plugin on/off (Admin → Plugins), no disable_ratings config */
	$result[] = array('trash', '01', 'trash_prunedelay', 2, '7', array(0, 1, 2, 3, 4, 5, 7, 10, 15, 20, 30, 45, 60, 90, 120));
	$result[] = array('trash', '10', 'trash_comment', 3, '1', '');
	/* trash_forum config moved to forums module */
	$result[] = array('trash', '12', 'trash_page', 3, '1', '');
	$result[] = array('trash', '13', 'trash_pm', 3, '1', '');
	$result[] = array('trash', '14', 'trash_user', 3, '1', '');
	$result[] = array('users', '01', 'disablereg', 3, '0', '');
	$result[] = array('users', '02', 'defaultcountry', 2, '', '');
	$result[] = array('users', '03', 'disablewhosonline', 3, '0', '');
	$result[] = array('users', '05', 'maxusersperpage', 2, '50', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 150, 200));
	$result[] = array('users', '07', 'regrequireadmin', 3, '0',  '');
	$result[] = array('users', '10', 'regnoactivation', 3, '0', '');
	$result[] = array('users', '10', 'useremailchange', 3, '0', '');
	$result[] = array('users', '10', 'usertextimg', 3, '0', '');
	$result[] = array('users', '10', 'color_group', 3, '0', '');   //new in v175
	$result[] = array('users', '12', 'av_maxsize', 2, '64000', '');
	$result[] = array('users', '12', 'av_maxx', 2, '128', '');
	$result[] = array('users', '12', 'av_maxy', 2, '128', '');
	$result[] = array('users', '12', 'usertextmax', 2, '300', '');
	$result[] = array('users', '13', 'sig_maxsize', 2, '64000', '');
	$result[] = array('users', '13', 'sig_maxx', 2, '640', '');
	$result[] = array('users', '13', 'sig_maxy', 2, '100', '');
	$result[] = array('users', '14', 'ph_maxsize', 2, '64000', '');
	$result[] = array('users', '14', 'ph_maxx', 2, '256', '');
	$result[] = array('users', '14', 'ph_maxy', 2, '256', '');
	return ($result);
}

/** 
 * Load doctypes
 * 
 * @return array 
 */
function sed_loaddoctypes()
{
	$result = array();
	$result[] = array(0, 'HTML 4.01');
	$result[] = array(1, 'HTML 4.01 Transitional');
	$result[] = array(2, 'HTML 4.01 Frameset');
	$result[] = array(3, 'XHTML 1.0 Strict');
	$result[] = array(4, 'XHTML 1.0 Transitional');
	$result[] = array(5, 'XHTML 1.0 Frameset');
	$result[] = array(6, 'XHTML 1.1');
	$result[] = array(7, 'XHTML 2');
	$result[] = array(8, 'HTML 5');
	return ($result);
}

/** 
 * Build plugin icon
 * 
 * @param $code Plugin code
 * @return string 
 */
function sed_plugin_icon($code)
{
	$icon = "plugins/" . $code . "/" . $code . ".png";
	if (file_exists($icon)) {
		return ("<img src=\"" . $icon . "\" alt=\"\" />");
	} else {
		return ("<img src=\"system/img/admin/plugins.png\" alt=\"\" />");
	}
}

/** 
 * Plugin installation
 * 
 * @param $pl Plugin code
 * @return string 
 */
function sed_plugin_install($pl)
{
	global $db_core, $db_plugins, $db_config, $db_auth, $db_users, $sed_groups, $usr, $cfg;

	$sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_code='$pl'");
	$res = "<h3>Installing : plugins/" . $pl . "</h3>";
	$res .= "<strong>Deleting old installation of this plugin...</strong> ";
	$res .= "Found:" . sed_sql_affectedrows() . "<br />";

	$sql = sed_sql_query("DELETE FROM $db_config WHERE config_owner='plug' and config_cat='$pl'");
	$res .= "<strong>Deleting old configuration entries...</strong> ";
	$res .= "Found:" . sed_sql_affectedrows() . "<br />";

	$extplugin_info = SED_ROOT . "/plugins/" . $pl . "/" . $pl . ".setup.php";

	$res .= "<strong>Looking for the setup file...</strong> ";

	if (file_exists($extplugin_info)) {
		$res .= "Found:1<br />";
		$info = sed_infoget($extplugin_info, 'SED_EXTPLUGIN');

		$info['Auth_members'] = (isset($info['Auth_members'])) ? $info['Auth_members'] : 'R';
		$info['Lock_members'] = (isset($info['Lock_members'])) ? $info['Lock_members'] : 'W12345A';

		// Dependencies: parse and validate
		$req_modules = isset($info['Requires_modules']) ? array_map('trim', explode(',', $info['Requires_modules'])) : array();
		$req_modules = array_filter($req_modules);
		$req_plugins = isset($info['Requires_plugins']) ? array_map('trim', explode(',', $info['Requires_plugins'])) : array();
		$req_plugins = array_filter($req_plugins);

		$res .= "<strong>Checking dependencies...</strong> ";
		foreach ($req_modules as $req) {
			$sql_dep = sed_sql_query("SELECT ct_state, ct_title FROM $db_core WHERE ct_code='" . sed_sql_prep($req) . "' LIMIT 1");
			$dep_row = sed_sql_fetchassoc($sql_dep);
			if (!$dep_row || (int)$dep_row['ct_state'] != 1) {
				$dep_name = !empty($dep_row['ct_title']) ? $dep_row['ct_title'] : $req;
				$dep_url = sed_url("admin", "m=modules&a=details&mod=" . $req);
				$res .= "<span class=\"no\">Required module " . sed_link($dep_url, sed_cc($dep_name), 'class="alert-link"') . " is not installed or not active. Installation aborted.</span><br />";
				return $res;
			}
		}
		foreach ($req_plugins as $req) {
			$sql_dep = sed_sql_query("SELECT SUM(pl_active) AS active_count FROM $db_plugins WHERE pl_code='" . sed_sql_prep($req) . "' AND pl_module=0");
			$dep_row = sed_sql_fetchassoc($sql_dep);
			if (!$dep_row || (int)$dep_row['active_count'] < 1) {
				$sql_title = sed_sql_query("SELECT pl_title FROM $db_plugins WHERE pl_code='" . sed_sql_prep($req) . "' AND pl_module=0 LIMIT 1");
				$title_row = sed_sql_fetchassoc($sql_title);
				$dep_name = ($title_row && !empty($title_row['pl_title'])) ? $title_row['pl_title'] : $req;
				$dep_url = sed_url("admin", "m=plug&a=details&pl=" . $req);
				$res .= "<span class=\"no\">Required plugin " . sed_link($dep_url, sed_cc($dep_name), 'class="alert-link"') . " is not installed or not active. Installation aborted.</span><br />";
				return $res;
			}
		}
		$res .= "OK.<br />";

		$dependencies_json = sed_sql_prep(json_encode(array('requires' => array_values($req_modules), 'requires_plugins' => array_values($req_plugins))));
		$pl_version = isset($info['Version']) ? sed_sql_prep($info['Version']) : '0.0.0';

		$handle = opendir(SED_ROOT . "/plugins/" . $pl);
		$setupfile = $pl . ".setup.php";
		$res .= "<strong>Looking for parts...</strong><br />";
		while ($f = readdir($handle)) {
			if ($f != "." && $f != ".." && $f != $setupfile && mb_strtolower(mb_substr($f, mb_strrpos($f, '.') + 1, 4)) == 'php') {
				$res .= "- Found : " . $f . "<br />";
				$parts[] = $f;
			}
		}
		closedir($handle);

		$res .= "<strong>Installing the parts...</strong><br />";
		foreach ($parts as $i => $x) {
			$res .= "- Part " . $x . " ...";
			$extplugin_file = SED_ROOT . "/plugins/" . $pl . "/" . $x;
			$info_part = sed_infoget($extplugin_file, 'SED_EXTPLUGIN');

			if (empty($info_part['Error'])) {
				$pl_lock = (isset($info_part['Lock']) && (int)$info_part['Lock'] === 1) ? 1 : 0;
				//Multihooks New v 173
				$mhooks = explode(",", $info_part['Hooks']);
				foreach ($mhooks as $k => $hook) {
					if (isset($info_part['Order'])) {
						$morder = explode(",", $info_part['Order']);
						$order = array_key_exists($k, $morder) ? $morder[$k] : $morder[0];
					} else {
						$order = 10;
					}
					$sql = sed_sql_query("INSERT into $db_plugins (pl_hook, pl_code, pl_part, pl_title, pl_version, pl_dependencies, pl_file, pl_order, pl_active, pl_lock) VALUES ('" . trim($hook) . "', '" . $info_part['Code'] . "', '" . sed_sql_prep($info_part['Part']) . "', '" . sed_sql_prep($info['Name']) . "', '" . $pl_version . "', '" . $dependencies_json . "', '" . $info_part['File'] . "', " . (int)$order . ", 1, " . (int)$pl_lock . ")");
				}

				//$sql = sed_sql_query("INSERT into $db_plugins (pl_hook, pl_code, pl_part, pl_title, pl_file, pl_order, pl_active ) VALUES ('".$info_part['Hooks']."', '".$info_part['Code']."', '".sed_sql_prep($info_part['Part'])."', '".sed_sql_prep($info['Name'])."', '".$info_part['File']."',  ".(int)$info_part['Order'].", 1)");

				$res .= " (Hooked at : " . $info_part['Hooks'] . ")";
				$res .= " Installed<br />";
			} else {
				if (mb_substr($x, -11, 11) == 'install.php') {
					$res .= "Ignoring.<br />";
				} else {
					$res .= "Error !<br />";
				}
			}
		}

		$info_cfg = sed_infoget($extplugin_info, 'SED_EXTPLUGIN_CONFIG');
		$res .= "<strong>Looking for configuration entries in the setup file...</strong> ";

		/* ===== */
		$path_lang_setup = SED_ROOT . "/plugins/" . $pl . "/lang/" . $pl . "." . $cfg['defaultlang'] . ".lang.php";
		if (file_exists($path_lang_setup)) {
			require($path_lang_setup);
		}
		/* ===== */

		if (empty($info_cfg['Error'])) {
			$res .= "Found at least 1<br/>";
			$j = 0;
			foreach ($info_cfg as $i => $x) {
				$line = explode(":", $x);

				if (is_array($line) && !empty($line[1]) && !empty($i)) {
					$j++;

					/* ===== */
					/*  if (isset($L['setup_cfg_'.$i])) { $line[4] = $L['setup_cfg_'.$i]; }  */
					/* ===== */

					sed_config_add('plug', $pl, $line[0], $i, $line[1], $line[3], $line[3], $line[4], $line[2]);
					$res .= "- Entry #$j : $i (" . $line[1] . ") Installed<br />";
				}
			}
		} else {
			$res .= "None found<br />";
		}
	} else {
		$res .= "Not found ! Installation failed !<br />";
	}

	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='plug' and auth_option='$pl'");
	$res .= "<strong>Deleting any old rights about this plugin...</strong> ";
	$res .= "Found:" . sed_sql_affectedrows() . "<br />";

	$res .= "<strong>Adding the rights for the user groups...</strong><br />";

	global $sed_groups;
	$plug_default_rights = array(
		SED_GROUP_DEFAULT => 'RW',
		SED_GROUP_GUESTS => 'R',
		SED_GROUP_INACTIVE => 'R',
		SED_GROUP_BANNED => '',
		SED_GROUP_MEMBERS => 'RW',
		SED_GROUP_MODERATORS => 'RWA',
		SED_GROUP_SUPERADMINS => 'RWA12345',
	);
	$plug_default_lock = array(
		SED_GROUP_DEFAULT => 'A',
		SED_GROUP_GUESTS => 'W12345A',
		SED_GROUP_INACTIVE => 'W12345A',
		SED_GROUP_BANNED => 'RWA12345',
		SED_GROUP_MEMBERS => 'A',
		SED_GROUP_MODERATORS => '',
		SED_GROUP_SUPERADMINS => 'RWA12345',
	);
	$plug_rights = array();
	$plug_lock = array();
	$plug_comment = array();
	foreach ($sed_groups as $k => $v) {
		$gid = $v['id'];
		$plug_rights[$gid] = isset($plug_default_rights[$gid]) ? $plug_default_rights[$gid] : $plug_default_rights[SED_GROUP_DEFAULT];
		$plug_lock[$gid] = isset($plug_default_lock[$gid]) ? $plug_default_lock[$gid] : $plug_default_lock[SED_GROUP_DEFAULT];
		$plug_comment[$gid] = ' (Plugin setup)';
		if ($gid == SED_GROUP_GUESTS || $gid == SED_GROUP_INACTIVE) {
			$plug_rights[$gid] = $info['Auth_guests'];
			$plug_lock[$gid] = $info['Lock_guests'];
			if (sed_auth_getvalue($info['Auth_guests']) > 128 || sed_auth_getvalue($info['Lock_guests']) < 128) {
				$plug_rights[$gid] = str_replace('A', '', $info['Auth_guests']);
				$plug_lock[$gid] = 'A';
				$plug_comment[$gid] = ' (System override, guests and inactive are not allowed to admin)';
			}
		} elseif ($gid == SED_GROUP_BANNED) {
			$plug_comment[$gid] = ' (System override, Banned)';
		} elseif ($gid == SED_GROUP_SUPERADMINS) {
			$plug_comment[$gid] = ' (System override, Administrators)';
		} elseif ($gid == SED_GROUP_MEMBERS || $gid == SED_GROUP_MODERATORS) {
			$plug_rights[$gid] = $info['Auth_members'];
			$plug_lock[$gid] = $info['Lock_members'];
		}
	}
	sed_auth_install_option('plug', $pl, $plug_rights, $plug_lock, $usr['id']);
	foreach ($sed_groups as $k => $v) {
		$gid = $v['id'];
		$res .= "Group #" . $gid . ", " . $sed_groups[$gid]['title'] . " : Auth=" . sed_build_admrights(sed_auth_getvalue($plug_rights[$gid])) . " / Lock=" . sed_build_admrights(sed_auth_getvalue($plug_lock[$gid])) . $plug_comment[$gid] . "<br />";
	}

	sed_auth_reset();
	$res .= "<strong>Resetting the auth column for all the users...</strong><br />";

	$extplugin_install = SED_ROOT . "/plugins/" . $pl . "/" . $pl . ".install.php";
	$res .= "<strong>Looking for the optional PHP file : " . $extplugin_install . "...</strong> ";
	if (file_exists($extplugin_install)) {
		$res .= "Found, executing...<br />";
		include($extplugin_install);
	} else {
		$res .= "Not found.<br />";
	}

	sed_auth_reorder();
	sed_cache_clearall();
	$res .= (isset($j) && $j > 0) ? "<strong>" . sed_link(sed_url("admin", "m=config&n=edit&o=plug&p=" . $pl), "There was configuration entries, click here to open the configuration panel") . "</strong><br />" : '';
	return ($res);
}

/** 
 * Plugin uninstall
 * 
 * @param $pl Plugin code
 * @param $all If TRUE - uninstall all plugins 
 * @return string 
 */
function sed_plugin_uninstall($pl, $all = FALSE)
{
	global $db_plugins, $db_config, $db_auth, $db_users;

	// New v173 Delete all plugins for upgrade mode
	$where = ($all && $pl == "all") ? "" : " WHERE pl_code='$pl' LIMIT 1";

	$sql0 = sed_sql_query("SELECT * FROM $db_plugins" . $where);
	$res = '';
	while ($row = sed_sql_fetchassoc($sql0)) {
		$pl = $row['pl_code'];
		// Check reverse dependencies (other plugins that require this one)
		$blocked = false;
		$sql_deps = sed_sql_query("SELECT pl_code, pl_title, pl_dependencies FROM $db_plugins WHERE pl_module=0 AND pl_code!='" . sed_sql_prep($pl) . "' AND pl_dependencies IS NOT NULL AND pl_dependencies != ''");
		while ($dep_row = sed_sql_fetchassoc($sql_deps)) {
			$deps = json_decode($dep_row['pl_dependencies'], true);
			if (is_array($deps) && isset($deps['requires_plugins']) && is_array($deps['requires_plugins']) && in_array($pl, $deps['requires_plugins'])) {
				$dep_name = !empty($dep_row['pl_title']) ? $dep_row['pl_title'] : $dep_row['pl_code'];
				$dep_url = sed_url("admin", "m=plug&a=details&pl=" . $dep_row['pl_code']);
				$res .= "<h3>Removing : plugins/" . $pl . "</h3>";
				$res .= "<span class=\"no\">Cannot uninstall: plugin " . sed_link($dep_url, $dep_name, 'class="alert-link"') . " depends on this plugin. Uninstall it first.</span><br />";
				$blocked = true;
				break;
			}
		}
		if ($blocked) {
			if (!$all || $pl != 'all') {
				return $res;
			}
			continue;
		}
		$res .= "<h3>Removing : plugins/" . $pl . "</h3>";
		$sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_code='$pl'");
		$res .= "Deleting old installation of this plugin... ";
		$res .= "Found:" . sed_sql_affectedrows() . "<br />";
		$sql = sed_sql_query("DELETE FROM $db_config WHERE config_owner='plug' AND config_cat='$pl'");
		$res .= "Deleting old configuration entries... ";
		$res .= "Found:" . sed_sql_affectedrows() . "<br />";
		$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='plug' and auth_option='$pl'");
		$res .= "Deleting any old rights about this plugin... ";
		$res .= "Found:" . sed_sql_affectedrows() . "<br />";
		$sql = sed_sql_query("UPDATE $db_users SET user_auth='' WHERE 1");
		$res .= "Resetting the auth column for all the users... ";
		$res .= "Found:" . sed_sql_affectedrows() . "<br />";

		$extplugin_uninstall = SED_ROOT . "/plugins/" . $pl . "/" . $pl . ".uninstall.php";
		$res .= "Looking for the optional PHP file : " . $extplugin_uninstall . "... ";
		if (file_exists($extplugin_uninstall)) {
			$res .= "Found, executing...<br />";
			include($extplugin_uninstall);
		} else {
			$res .= "Not found.<br />";
		}
	}
	sed_cache_clearall();
	return ($res);
}

/**
 * Module installation
 *
 * @param string $code Module code (directory name in /modules/)
 * @return string Installation log
 */
function sed_module_install($code)
{
	global $db_core, $db_plugins, $db_config, $db_auth, $db_users, $sed_groups, $usr, $cfg;

	$code = preg_replace('/[^a-zA-Z0-9_]/', '', $code);
	$res = "<h3>Installing module: " . $code . "</h3>";
	$module_dir = SED_ROOT . '/modules/' . $code . '/';
	$setup_file = $module_dir . $code . '.setup.php';

	// Step 1: Parse setup file
	$res .= "<strong>Looking for the setup file...</strong> ";
	if (!file_exists($setup_file)) {
		$res .= "Not found! Installation failed!<br />";
		return $res;
	}
	$res .= "Found.<br />";

	$info = sed_infoget($setup_file, 'SED_MODULE');
	if (!empty($info['Error'])) {
		$res .= "Error parsing setup file: " . $info['Error'] . "<br />";
		return $res;
	}

	$info['Name'] = isset($info['Name']) ? $info['Name'] : $code;
	$info['Version'] = isset($info['Version']) ? $info['Version'] : '1.0.0';
	$info['Requires'] = isset($info['Requires']) ? $info['Requires'] : '';
	$info['Admin'] = isset($info['Admin']) ? (int)$info['Admin'] : 0;
	$info['Auth_guests'] = isset($info['Auth_guests']) ? $info['Auth_guests'] : 'R';
	$info['Lock_guests'] = isset($info['Lock_guests']) ? $info['Lock_guests'] : 'W12345A';
	$info['Auth_members'] = isset($info['Auth_members']) ? $info['Auth_members'] : 'RW';
	$info['Lock_members'] = isset($info['Lock_members']) ? $info['Lock_members'] : '12345A';

	// Step 2: Check dependencies
	if (!empty($info['Requires'])) {
		$requires = array_map('trim', explode(',', $info['Requires']));
		foreach ($requires as $req) {
			if (empty($req)) continue;
			$sql_dep = sed_sql_query("SELECT ct_state FROM $db_core WHERE ct_code='" . sed_sql_prep($req) . "' LIMIT 1");
			$dep_row = sed_sql_fetchassoc($sql_dep);
			if (!$dep_row || $dep_row['ct_state'] != 1) {
				$res .= "<span class=\"no\">Required module '" . $req . "' is not installed or not active. Installation aborted.</span><br />";
				return $res;
			}
		}
		$res .= "Dependencies satisfied.<br />";
	}

	// Step 3: Remove old installation if exists
	$sql = sed_sql_query("DELETE FROM $db_core WHERE ct_code='" . sed_sql_prep($code) . "'");
	$sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_code='" . sed_sql_prep($code) . "' AND pl_module=1");
	$sql = sed_sql_query("DELETE FROM $db_config WHERE config_owner='module' AND config_cat='" . sed_sql_prep($code) . "'");
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='" . sed_sql_prep($code) . "'");
	$res .= "Cleaned up any previous installation.<br />";

	// Step 4: Execute install script
	$install_file = $module_dir . $code . '.install.php';
	$res .= "<strong>Looking for install script...</strong> ";
	if (file_exists($install_file)) {
		$res .= "Found, executing...<br />";
		include($install_file);
	} else {
		$res .= "Not found (optional).<br />";
	}

	// Step 5: Register in sed_core
	$ct_lock = (isset($info['Lock_module']) && (int)$info['Lock_module'] === 1) ? 1 : 0;
	$ct_path = 'modules/' . $code . '/';
	$sql = sed_sql_query("INSERT INTO $db_core (ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($info['Name']) . "', '" . sed_sql_prep($info['Version']) . "', 1, " . (int)$ct_lock . ", '" . sed_sql_prep($ct_path) . "', " . (int)$info['Admin'] . ")");
	$res .= "Registered in core registry.<br />";

	// Step 6: Register all module parts in sed_plugins (like plugin parts)
	$dependencies_json = '';
	if (!empty($info['Requires'])) {
		$dependencies_json = json_encode(array('requires' => array_map('trim', explode(',', $info['Requires']))));
	}
	$module_parts = array();
	$handle = @opendir($module_dir);
	if ($handle) {
		$skip = array($code . '.setup.php', $code . '.install.php', $code . '.uninstall.php', $code . '.urls.php');
		while (($f = readdir($handle)) !== false) {
			if ($f === '.' || $f === '..') continue;
			if (mb_strtolower(mb_substr($f, -4)) !== '.php') continue;
			if (in_array($f, $skip)) continue;
			$module_parts[] = $f;
		}
		closedir($handle);
	}
	if (empty($module_parts)) {
		$module_parts[] = $code . '.php';
	}
	sort($module_parts);
	$main_file = $code . '.php';
	$parts_ordered = array();
	if (in_array($main_file, $module_parts)) {
		$parts_ordered[] = $main_file;
		$module_parts = array_values(array_diff($module_parts, array($main_file)));
	}
	$parts_ordered = array_merge($parts_ordered, $module_parts);
	$order = 10;
	foreach ($parts_ordered as $x) {
		$part_name = mb_substr($x, 0, -4);
		$pl_part = ($part_name === $code) ? 'main' : $part_name;
		$pl_file = $part_name;
		$part_info = sed_infoget($module_dir . $x, 'SED');
		$pl_lock = (isset($part_info['Lock']) && (int)$part_info['Lock'] === 1) ? 1 : 0;
		$sql = sed_sql_query("INSERT INTO $db_plugins (pl_hook, pl_code, pl_part, pl_title, pl_version, pl_dependencies, pl_file, pl_order, pl_active, pl_lock, pl_module) VALUES ('module', '" . sed_sql_prep($code) . "', '" . sed_sql_prep($pl_part) . "', '" . sed_sql_prep($info['Name']) . "', '" . sed_sql_prep($info['Version']) . "', '" . sed_sql_prep($dependencies_json) . "', '" . sed_sql_prep($pl_file) . "', " . (int)$order . ", 1, " . (int)$pl_lock . ", 1)");
		$order += 10;
	}
	$res .= "Registered " . count($parts_ordered) . " part(s) in plugins registry (pl_module=1).<br />";

	// Step 7: Install configuration entries
	$info_cfg = sed_infoget($setup_file, 'SED_MODULE_CONFIG');
	$res .= "<strong>Looking for configuration entries...</strong> ";
	if (empty($info_cfg['Error'])) {
		$j = 0;
		foreach ($info_cfg as $i => $x) {
			$line = explode(":", $x);
			if (is_array($line) && !empty($line[1]) && !empty($i)) {
				$j++;
				sed_config_add('module', $code, $line[0], $i, $line[1], $line[3], $line[3], $line[4], isset($line[2]) ? $line[2] : '');
				$res .= "Config #$j: $i (" . $line[1] . ") Installed<br />";
			}
		}
		$res .= "Found $j entries.<br />";
	} else {
		$res .= "None found.<br />";
	}

	// Step 8: Set up rights
	$res .= "<strong>Adding rights for user groups...</strong><br />";
	global $sed_groups;
	$mod_default_rights = array(
		SED_GROUP_DEFAULT => 'RW',
		SED_GROUP_GUESTS => 'R',
		SED_GROUP_INACTIVE => 'R',
		SED_GROUP_BANNED => '',
		SED_GROUP_MEMBERS => 'RW',
		SED_GROUP_MODERATORS => 'RWA',
		SED_GROUP_SUPERADMINS => 'RWA12345',
	);
	$mod_default_lock = array(
		SED_GROUP_DEFAULT => 'A',
		SED_GROUP_GUESTS => 'W12345A',
		SED_GROUP_INACTIVE => 'W12345A',
		SED_GROUP_BANNED => 'RWA12345',
		SED_GROUP_MEMBERS => 'A',
		SED_GROUP_MODERATORS => '',
		SED_GROUP_SUPERADMINS => 'RWA12345',
	);
	$mod_rights = array();
	$mod_lock = array();
	$mod_comment = array();
	foreach ($sed_groups as $k => $v) {
		$gid = $v['id'];
		$mod_rights[$gid] = isset($mod_default_rights[$gid]) ? $mod_default_rights[$gid] : $mod_default_rights[SED_GROUP_DEFAULT];
		$mod_lock[$gid] = isset($mod_default_lock[$gid]) ? $mod_default_lock[$gid] : $mod_default_lock[SED_GROUP_DEFAULT];
		$mod_comment[$gid] = ' (Module setup)';
		if ($gid == SED_GROUP_GUESTS || $gid == SED_GROUP_INACTIVE) {
			$mod_rights[$gid] = $info['Auth_guests'];
			$mod_lock[$gid] = $info['Lock_guests'];
			$mod_comment[$gid] = ' (Guests/Inactive)';
		} elseif ($gid == SED_GROUP_BANNED) {
			$mod_comment[$gid] = ' (Banned)';
		} elseif ($gid == SED_GROUP_SUPERADMINS) {
			$mod_comment[$gid] = ' (Administrators)';
		} elseif ($gid == SED_GROUP_MEMBERS || $gid == SED_GROUP_MODERATORS) {
			$mod_rights[$gid] = $info['Auth_members'];
			$mod_lock[$gid] = $info['Lock_members'];
		}
	}
	sed_auth_install_option($code, 'a', $mod_rights, $mod_lock, $usr['id']);
	foreach ($sed_groups as $k => $v) {
		$gid = $v['id'];
		$res .= "Group #" . $gid . " (" . $v['title'] . "): Auth=" . sed_build_admrights(sed_auth_getvalue($mod_rights[$gid])) . " / Lock=" . sed_build_admrights(sed_auth_getvalue($mod_lock[$gid])) . $mod_comment[$gid] . "<br />";
	}
	sed_auth_reset();

	// Step 9: Regenerate URL cache
	sed_urls_generate();
	$res .= "URL cache regenerated.<br />";

	sed_cache_clearall();
	$res .= "<strong>Module '" . $info['Name'] . "' installed successfully.</strong><br />";
	return $res;
}

/**
 * Module uninstallation
 *
 * @param string $code Module code
 * @return string Uninstallation log
 */
function sed_module_uninstall($code)
{
	global $db_core, $db_plugins, $db_config, $db_auth, $db_users;

	$code = preg_replace('/[^a-zA-Z0-9_]/', '', $code);
	$res = "<h3>Uninstalling module: " . $code . "</h3>";

	// Check reverse dependencies (other modules and plugins)
	$code_title = $code;
	$sql_ct = sed_sql_query("SELECT ct_title FROM $db_core WHERE ct_code='" . sed_sql_prep($code) . "' LIMIT 1");
	if ($ct_row = sed_sql_fetchassoc($sql_ct)) {
		$code_title = $ct_row['ct_title'];
	}
	$code_url = sed_url("admin", "m=modules&a=details&mod=" . $code);

	// Check if module is locked
	$sql_ct_lock = sed_sql_query("SELECT ct_lock FROM $db_core WHERE ct_code='" . sed_sql_prep($code) . "' LIMIT 1");
	$ct_lock_row = sed_sql_fetchassoc($sql_ct_lock);
	if ($ct_lock_row && (int)$ct_lock_row['ct_lock'] === 1) {
		$res .= "<span class=\"no\">Module is locked. Edit the setup file (Lock_module=0) to allow uninstall.</span><br />";
		return $res;
	}

	$sql_deps = sed_sql_query("SELECT pl_code, pl_title, pl_dependencies, pl_module FROM $db_plugins WHERE pl_code!='" . sed_sql_prep($code) . "' AND pl_dependencies IS NOT NULL AND pl_dependencies != ''");
	while ($dep_row = sed_sql_fetchassoc($sql_deps)) {
		$deps = json_decode($dep_row['pl_dependencies'], true);
		if (!is_array($deps)) {
			continue;
		}
		$depends = false;
		if (isset($deps['requires']) && is_array($deps['requires']) && in_array($code, $deps['requires'])) {
			$depends = true;
		}
		if ($depends) {
			$dep_name = !empty($dep_row['pl_title']) ? $dep_row['pl_title'] : $dep_row['pl_code'];
			$dep_url = !empty($dep_row['pl_module'])
				? sed_url("admin", "m=modules&a=details&mod=" . $dep_row['pl_code'])
				: sed_url("admin", "m=plug&a=details&pl=" . $dep_row['pl_code']);
			$kind = !empty($dep_row['pl_module']) ? 'module' : 'plugin';
			$res .= "<span class=\"no\">Cannot uninstall: " . $kind . " " . sed_link($dep_url, $dep_name, 'class="alert-link"') . " depends on " . sed_link($code_url, $code_title, 'class="alert-link"') . ". Uninstall it first.</span><br />";
			return $res;
		}
	}

	// Execute uninstall script
	$module_dir = SED_ROOT . '/modules/' . $code . '/';
	$uninstall_file = $module_dir . $code . '.uninstall.php';
	$res .= "<strong>Looking for uninstall script...</strong> ";
	if (file_exists($uninstall_file)) {
		$res .= "Found, executing...<br />";
		include($uninstall_file);
	} else {
		$res .= "Not found (optional).<br />";
	}

	// Clean up database
	$sql = sed_sql_query("DELETE FROM $db_config WHERE config_owner='module' AND config_cat='" . sed_sql_prep($code) . "'");
	$res .= "Deleted configuration entries: " . sed_sql_affectedrows() . "<br />";

	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='" . sed_sql_prep($code) . "'");
	$res .= "Deleted auth entries: " . sed_sql_affectedrows() . "<br />";

	$sql = sed_sql_query("DELETE FROM $db_core WHERE ct_code='" . sed_sql_prep($code) . "'");
	$res .= "Deleted from core registry: " . sed_sql_affectedrows() . "<br />";

	$sql = sed_sql_query("DELETE FROM $db_plugins WHERE pl_code='" . sed_sql_prep($code) . "' AND pl_module=1");
	$res .= "Deleted from plugins registry: " . sed_sql_affectedrows() . "<br />";

	$sql = sed_sql_query("UPDATE $db_users SET user_auth='' WHERE 1");
	$res .= "Reset user auth cache.<br />";

	// Regenerate URL cache
	sed_urls_generate();
	$res .= "URL cache regenerated.<br />";

	sed_cache_clearall();
	$res .= "<strong>Module '" . $code . "' uninstalled.</strong><br />";
	return $res;
}

/**
 * Pause or resume a module (toggle ct_state)
 *
 * @param string $code Module code
 * @param int $state New state (0=paused, 1=active)
 * @return string Log message
 */
function sed_module_pause($code, $state)
{
	global $db_core;

	$code = preg_replace('/[^a-zA-Z0-9_]/', '', $code);
	$state = (int)$state;
	$sql = sed_sql_query("UPDATE $db_core SET ct_state=" . $state . " WHERE ct_code='" . sed_sql_prep($code) . "'");
	sed_urls_generate();
	sed_cache_clearall();
	return ($state == 1) ? "Module '" . $code . "' activated." : "Module '" . $code . "' paused.";
}

/* sed_structure_delcat, sed_structure_newcat moved to modules/page/inc/page.functions.php */

/** 
 * Removing an item from trash 
 * 
 * @param int $id Trash item ID
 * @return int      
 */
function sed_trash_delete($id)
{
	global $db_trash;

	$sql = sed_sql_query("DELETE FROM $db_trash WHERE tr_id='$id'");
	return (sed_sql_affectedrows());
}

/** 
 * Get an item from trash 
 * 
 * @param int $id Trash item ID
 * @return mixed      
 */
function sed_trash_get($id)
{
	global $db_trash;

	$sql = sed_sql_query("SELECT * FROM $db_trash WHERE tr_id='$id' LIMIT 1");
	if ($res = sed_sql_fetchassoc($sql)) {
		$res['tr_datas'] = unserialize($res['tr_datas']);
		return ($res);
	} else {
		return (FALSE);
	}
}

/** 
 * Adding an item to trash 
 * 
 * @param array $dat Data item from trash
 * @param string $db Name of DB table to restory item 
 * @return mixed      
 */
function sed_trash_insert($dat, $db)
{
	foreach ($dat as $k => $v) {
		$columns[] = $k;
		$datas[] = "'" . sed_sql_prep($v) . "'";
	}
	$sql = sed_sql_query("INSERT INTO $db (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $datas) . ")");
	return (TRUE);
}

/** 
 * Restore an item from trash 
 * 
 * @param int $id Trash item ID 
 * @return mixed      
 */
function sed_trash_restore($id)
{
	global $db_trash;

	$columns = array();
	$datas = array();

	$res = sed_trash_get($id);

	switch ($res['tr_type']) {
		case 'comment':
			global $db_com;
			sed_trash_insert($res['tr_datas'], $db_com);
			sed_log("Comment #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			return (TRUE);

		case 'forumpost':
			if (function_exists('sed_forum_resynctopic')) {
				global $db_forum_topics, $db_forum_posts;
				$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE ft_id='" . $res['tr_datas']['fp_topicid'] . "'");

				if ($row = sed_sql_fetchassoc($sql)) {
					sed_trash_insert($res['tr_datas'], $db_forum_posts);
					sed_log("Post #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
					sed_forum_resynctopic($res['tr_datas']['fp_topicid']);
					sed_forum_sectionsetlast($res['tr_datas']['fp_sectionid']);
					sed_forum_resync($res['tr_datas']['fp_sectionid']);
					return (TRUE);
				} else {
					$sql1 = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumtopic' AND tr_itemid='q" . $res['tr_datas']['fp_topicid'] . "'");
					if ($row1 = sed_sql_fetchassoc($sql1)) {
						sed_trash_restore($row1['tr_id']);
						sed_trash_delete($row1['tr_id']);
					}
				}
			}
			break;

		case 'forumtopic':
			if (function_exists('sed_forum_resynctopic')) {
				global $db_forum_topics, $db_forum_posts;
				sed_trash_insert($res['tr_datas'], $db_forum_topics);
				sed_log("Topic #" . $res['tr_datas']['ft_id'] . " restored from the trash can.", 'adm');

				$sql = sed_sql_query("SELECT tr_id FROM $db_trash WHERE tr_type='forumpost' AND tr_itemid LIKE '%-" . $res['tr_itemid'] . "'");

				while ($row = sed_sql_fetchassoc($sql)) {
					$res2 = sed_trash_get($row['tr_id']);
					sed_trash_insert($res2['tr_datas'], $db_forum_posts);
					sed_trash_delete($row['tr_id']);
					sed_log("Post #" . $res2['tr_datas']['fp_id'] . " restored from the trash can (belongs to topic #" . $res2['tr_datas']['fp_topicid'] . ").", 'adm');
				}

				sed_forum_resynctopic($res['tr_itemid']);
				sed_forum_sectionsetlast($res['tr_datas']['ft_sectionid']);
				sed_forum_resync($res['tr_datas']['ft_sectionid']);
				return (TRUE);
			}
			break;

		case 'page':
			global $db_pages, $db_structure;
			sed_trash_insert($res['tr_datas'], $db_pages);
			sed_log("Page #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='" . $res['tr_itemid'] . "'");
			$row = sed_sql_fetchassoc($sql);
			$sql = sed_sql_query("SELECT structure_id FROM $db_structure WHERE structure_code='" . $row['page_cat'] . "'");
			if (sed_sql_numrows($sql) == 0) {
				sed_structure_newcat('restored', 999, 'RESTORED', '', '', 0);
				$sql = sed_sql_query("UPDATE $db_pages SET page_cat='restored' WHERE page_id='" . $res['tr_itemid'] . "'");
			}
			return (TRUE);

		case 'pm':
			global $db_pm;
			sed_trash_insert($res['tr_datas'], $db_pm);
			sed_log("Private message #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			return (TRUE);

		case 'user':
			global $db_users;
			sed_trash_insert($res['tr_datas'], $db_users);
			sed_log("User #" . $res['tr_itemid'] . " restored from the trash can.", 'adm');
			return (TRUE);

		default:
			return (FALSE);
	}
}
