<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/functions.admin.php
Version=180
Updated=2025-jan-25
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

/** 
 * Delete forums section 
 * 
 * @param int $id Section ID 
 * @return int Count deleted rows 
 */
function sed_forum_deletesection($id)
{
	global $db_forum_topics, $db_forum_posts, $db_forum_sections, $db_auth;

	$sql = sed_sql_query("DELETE FROM $db_forum_posts WHERE fp_sectionid='$id'");
	$num = sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_forum_topics WHERE ft_sectionid='$id'");
	$num = $num + sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_forum_sections WHERE fs_id='$id'");
	$num = $num + sed_sql_affectedrows();
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='forums' AND auth_option='$id'");
	$num = $num + sed_sql_affectedrows();
	sed_log("Forums : Deleted section " . $id, 'adm');
	return ($num);
}

/** 
 * Recounts posts & topics in section
 * 
 * @param int $id Section ID 
 */
function sed_forum_resync($id)
{
	global $db_forum_topics, $db_forum_posts, $db_forum_sections;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_sectionid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("SELECT ft_id FROM $db_forum_topics WHERE 1");
	while ($row = sed_sql_fetchassoc($sql)) {
		sed_forum_resynctopic($row['ft_id']);
	}

	$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_topiccount='$num' WHERE fs_id='$id'");
	sed_forum_sectionsetlast($id);

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_sectionid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");

	$sql = sed_sql_query("UPDATE $db_forum_sections SET fs_postcount='$num' WHERE fs_id='$id'");

	sed_log("Forums : Re-synced section " . $id, 'adm');
	return;
}

/** 
 * Recounts posts in a given topic 
 * 
 * @param int $id Topic ID 
 */
function sed_forum_resynctopic($id)
{
	global $db_forum_topics, $db_forum_posts;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_topicid='$id'");
	$num = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_postcount='$num' WHERE ft_id='$id'");

	$sql = sed_sql_query("SELECT fp_posterid, fp_postername, fp_updated
		FROM $db_forum_posts
		WHERE fp_topicid='$id'
		ORDER BY fp_id DESC LIMIT 1");

	if ($row = sed_sql_fetchassoc($sql)) {
		$sql = sed_sql_query("UPDATE $db_forum_topics SET
			ft_lastposterid='" . (int)$row['fp_posterid'] . "',
			ft_lastpostername='" . sed_sql_prep($row['fp_postername']) . "',
			ft_updated='" . (int)$row['fp_updated'] . "'
			WHERE ft_id='$id'");
	}
	return;
}

/** 
 * Recounts posts & topics all sections
 * 
 * @param int $id Section ID 
 */
function sed_forum_resyncall()
{
	global $db_forum_sections;

	$sql = sed_sql_query("SELECT fs_id FROM $db_forum_sections");
	while ($row = sed_sql_fetchassoc($sql)) {
		sed_forum_resync($row['fs_id']);
	}
	return;
}

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
	$result[] = array('meta', '03', 'listtitle', 1, '{MAINTITLE} - {TITLE}', '');  //Sed 175
	$result[] = array('meta', '04', 'pagetitle', 1, '{MAINTITLE} - {TITLE}', '');  //Sed 175
	$result[] = array('meta', '05', 'forumstitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175
	$result[] = array('meta', '06', 'userstitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175
	$result[] = array('meta', '07', 'pmtitle', 1, '{MAINTITLE} - {TITLE}', '');  //Sed 175
	$result[] = array('meta', '08', 'gallerytitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175
	$result[] = array('meta', '09', 'pfstitle', 1, '{MAINTITLE} - {TITLE}', ''); //Sed 175
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
	$result[] = array('comments', '01', 'disable_comments', 3, '0', '');
	$result[] = array('comments', '04', 'showcommentsonpage', 3, '0', ''); //New v172
	$result[] = array('comments', '05', 'maxcommentsperpage', 2, '30', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70, 80, 90)); //New v173
	$result[] = array('comments', '06', 'maxtimeallowcomedit', 2, '15', array(0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70, 80, 90)); //New v173
	$result[] = array('comments', '07', 'maxcommentlenght', 1, '2000', '');
	$result[] = array('comments', '10', 'countcomments', 3, '1', '');
	$result[] = array('comments', '11', 'commentsorder', 2, 'ASC', array('ASC', 'DESC')); //New v173
	$result[] = array('forums', '01', 'disable_forums', 3, '0', '');
	$result[] = array('forums', '10', 'hideprivateforums', 3, '0', '');
	$result[] = array('forums', '10', 'hottopictrigger', 2, '20', array(5, 10, 15, 20, 25, 30, 35, 40, 50));
	$result[] = array('forums', '10', 'maxtopicsperpage', 2, '30', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70, 80, 90));
	$result[] = array('forums', '12', 'antibumpforums', 3, '0', '');
	$result[] = array('page', '01', 'disable_page', 3, '0', '');
	$result[] = array('page', '03', 'showpagesubcatgroup', 3, '0', '');
	$result[] = array('page', '05', 'maxrowsperpage', 2, '15', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70, 80, 90));
	$result[] = array('page', '06', 'genseourls', 3, '1', '');   // New in v178
	$result[] = array('pfs', '01', 'disable_pfs', 3, '0', '');
	$result[] = array('pfs', '02', 'pfs_filemask', 3, '0', '');
	$result[] = array('pfs', '03', 'available_image_sizes', 1, '', '');
	// $result[] = array ('pfs', '02', 'pfsuserfolder', 3, '0', '');
	$result[] = array('pfs', '10', 'th_amode', 2, 'GD2', array('Disabled', 'GD2', 'Imagick'));
	$result[] = array('pfs', '10', 'th_x', 2, '112', '');
	$result[] = array('pfs', '10', 'th_y', 2, '84', '');
	//$result[] = array('pfs', '10', 'th_border', 2, '0', '');
	$result[] = array('pfs', '10', 'th_dimpriority', 2, 'Width', array('Width', 'Height'));
	$result[] = array('pfs', '10', 'th_keepratio', 3, '1', '');
	$result[] = array('pfs', '10', 'th_jpeg_quality', 2, '85', array(0, 5, 10, 20, 30, 40, 50, 60, 70, 75, 80, 85, 90, 95, 100));
	//$result[] = array('pfs', '10', 'th_colorbg', 2, '000000', '');
	//$result[] = array('pfs', '10', 'th_colortext', 2, 'FFFFFF', '');
	$result[] = array('pfs', '10', 'th_rel', 2, 'sedthumb', '');
	//$result[] = array('pfs', '10', 'th_textsize', 2, '0', array(0, 1, 2, 3, 4, 5));
	// ---- New in v173
	$result[] = array('rss', '01', 'disable_rss', 3, '0', '');
	$result[] = array('rss', '02', 'disable_rsspages', 3, '0', '');
	$result[] = array('rss', '03', 'disable_rsscomments', 3, '0', '');
	$result[] = array('rss', '04', 'disable_rssforums', 3, '0', '');
	$result[] = array('rss', '05', 'rss_timetolive', 2, '300', '');
	$result[] = array('rss', '06', 'rss_maxitems', 2, '30', array(0, 5, 10, 20, 30, 40, 50, 60, 70, 75, 80, 85, 90, 95, 100));
	$result[] = array('rss', '07', 'rss_defaultcode', 2, 'news', '');
	// ----
	$result[] = array('gallery', '01', 'disable_gallery', 3, '0', '');
	$result[] = array('gallery', '10', 'gallery_gcol', 2, '4', '');
	$result[] = array('gallery', '11', 'gallery_bcol', 2, '6', '');
	$result[] = array('gallery', '12', 'gallery_imgmaxwidth', 2, '600', '');
	$result[] = array('gallery', '20', 'gallery_logofile', 1, '', '');
	$result[] = array('gallery', '21', 'gallery_logopos', 2, 'Bottom left', array('Top left', 'Top right', 'Bottom left', 'Bottom right'));
	$result[] = array('gallery', '22', 'gallery_logotrsp', 2, '50', array(0, 5, 10, 15, 20, 30, 40, 50, 60, 70, 80, 90, 95, 100));
	$result[] = array('gallery', '23', 'gallery_logojpegqual', 2, '90', array(0, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 95, 100));
	$result[] = array('plug', '01', 'disable_plug', 3, '0', '');
	$result[] = array('pm', '01', 'disable_pm', 3, '0', '');
	$result[] = array('pm', '10', 'pm_maxsize', 2, '10000', array(200, 500, 1000, 2000, 5000, 10000, 15000, 20000, 30000, 50000, 65000));
	$result[] = array('pm', '10', 'pm_allownotifications', 3, '1', '');
	$result[] = array('polls', '01', 'disable_polls', 3, '0', '');
	$result[] = array('ratings', '01', 'disable_ratings', 3, '0', '');
	$result[] = array('trash', '01', 'trash_prunedelay', 2, '7', array(0, 1, 2, 3, 4, 5, 7, 10, 15, 20, 30, 45, 60, 90, 120));
	$result[] = array('trash', '10', 'trash_comment', 3, '1', '');
	$result[] = array('trash', '11', 'trash_forum', 3, '1', '');
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
	global $db_plugins, $db_config, $db_auth, $db_users, $sed_groups, $usr, $cfg;

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
				//Multihooks New v 173
				$mhooks = explode(",", $info_part['Hooks']);
				foreach ($mhooks as $k => $hook) {
					if (isset($info_part['Order'])) {
						$morder = explode(",", $info_part['Order']);
						$order = array_key_exists($k, $morder) ? $morder[$k] : $morder[0];
					} else {
						$order = 10;
					}
					$sql = sed_sql_query("INSERT into $db_plugins (pl_hook, pl_code, pl_part, pl_title, pl_file, pl_order, pl_active ) VALUES ('" . trim($hook) . "', '" . $info_part['Code'] . "', '" . sed_sql_prep($info_part['Part']) . "', '" . sed_sql_prep($info['Name']) . "', '" . $info_part['File'] . "',  " . (int)$order . ", 1)");
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

	foreach ($sed_groups as $k => $v) {
		$comment = ' (Plugin setup)';

		if ($v['id'] == 1 || $v['id'] == 2) {
			$ins_auth = sed_auth_getvalue($info['Auth_guests']);
			$ins_lock = sed_auth_getvalue($info['Lock_guests']);

			if ($ins_auth > 128 || $ins_lock < 128) {
				$ins_auth = ($ins_auth > 127) ? $ins_auth - 128 : $ins_auth;
				$ins_lock = 128;
				$comment = ' (System override, guests and inactive are not allowed to admin)';
			}
		} elseif ($v['id'] == 3) {
			$ins_auth = 0;
			$ins_lock = 255;
			$comment = ' (System override, Banned)';
		} elseif ($v['id'] == 5) {
			$ins_auth = 255;
			$ins_lock = 255;
			$comment = ' (System override, Administrators)';
		} else {
			$ins_auth = sed_auth_getvalue($info['Auth_members']);
			$ins_lock = sed_auth_getvalue($info['Lock_members']);
		}

		$sql = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$v['id'] . ", 'plug', '$pl', " . (int)$ins_auth . ", " . (int)$ins_lock . ", " . (int)$usr['id'] . ")");

		$res .= "Group #" . $v['id'] . ", " . $sed_groups[$v['id']]['title'] . " : Auth=" . sed_build_admrights($ins_auth) . " / Lock=" . sed_build_admrights($ins_lock) . $comment . "<br />";
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
 * Removes a category 
 * 
 * @param int $id Category ID
 * @param string $c Category code
 */
function sed_structure_delcat($id, $c)
{
	global $db_structure, $db_auth;

	$sql = sed_sql_query("DELETE FROM $db_structure WHERE structure_id='$id'");
	$sql = sed_sql_query("DELETE FROM $db_auth WHERE auth_code='page' AND auth_option='$c'");
	sed_auth_clear('all');
	sed_cache_clear('sed_cat');
}

/** 
 * Add a new category 
 * 
 * @param string $code Category code 
 * @param string $path Category path
 * @param string $title Category title 
 * @param string $desc Category description
 * @param string $icon Category icon src path
 * @param int $group Category group flag
 * @return bool      
 */
function sed_structure_newcat($code, $path, $title, $desc, $icon, $group)
{
	global $db_structure, $db_auth, $sed_groups, $usr;

	$res = FALSE;

	if (!empty($title) && !empty($code) && !empty($path) && $code != 'all') {
		$code = sed_replacespace($code);  //New in175

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code='$code' LIMIT 1");
		if (sed_sql_numrows($sql) == 0) {
			$sql = sed_sql_query("INSERT INTO $db_structure (structure_code, structure_path, structure_title, structure_desc, structure_icon, structure_group, structure_order) 
					VALUES ('" . sed_sql_prep($code) . "', '" . sed_sql_prep($path) . "', '" . sed_sql_prep($title) . "', '" . sed_sql_prep($desc) . "', '" . sed_sql_prep($icon) . "', " . (int)$group . ", 'date.desc')");

			foreach ($sed_groups as $k => $v) {
				if ($v['id'] == 1 || $v['id'] == 2) {
					$ins_auth = 1;
					$ins_lock = 254;
				} elseif ($v['id'] == 3) {
					$ins_auth = 0;
					$ins_lock = 255;
				} elseif ($v['id'] == 5) {
					$ins_auth = 255;
					$ins_lock = 255;
				} else {
					$ins_auth = 3;
					$ins_lock = ($k == 4) ? 128 : 0;
				}
				$sql = sed_sql_query("INSERT into $db_auth (auth_groupid, auth_code, auth_option, auth_rights, auth_rights_lock, auth_setbyuserid) VALUES (" . (int)$v['id'] . ", 'page', '$code', " . (int)$ins_auth . ", " . (int)$ins_lock . ", " . (int)$usr['id'] . ")");
				$res = TRUE;
			}
			sed_auth_reorder();
			sed_auth_clear('all');
			sed_cache_clear('sed_cat');
		}
	}
	return ($res);
}


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
	global $db_forum_topics, $db_forum_posts, $db_trash;

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
			global $db_forum_posts;
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

			break;

		case 'forumtopic':
			global $db_forum_topics;
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
