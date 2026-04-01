<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sitemap/sitemap.php
Version=185
Updated=2026-mar-31
Type=Plugin
Author=Seditio Team
Description=XML Sitemap (direct)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sitemap
Part=main
File=sitemap
Hooks=direct
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

/**
 * Absolute &lt;loc&gt; URL: sed_url with enableamp (&amp; for XML); if not http(s), prefix mainurl.
 */
function sed_sitemap_loc($section, $params = '')
{
	global $cfg;

	$url = sed_url($section, $params, '', false, true);
	if (preg_match('#^https?://#i', $url)) {
		return preg_replace('~(^|[^:])//+~', '\\1/', $url);
	}
	$base = rtrim($cfg['mainurl'], '/') . '/';

	return preg_replace('~(^|[^:])//+~', '\\1/', $base . $url);
}

global $cfg, $usr, $sys, $sed_cat, $db_pages, $db_structure, $db_forum_posts, $db_forum_topics, $db_forum_sections, $db_forum_structure;

$m = sed_import('m', 'G', 'ALP');
$c = sed_import('c', 'G', 'TXT');

$smcfg['index'] = array(
	'changefreq' => ($cfg['plugin']['sitemap']['sm_index_changefreq'] !== '') ? $cfg['plugin']['sitemap']['sm_index_changefreq'] : 'always',
	'priority'   => ($cfg['plugin']['sitemap']['sm_index_priority'] !== '') ? $cfg['plugin']['sitemap']['sm_index_priority'] : '1.0',
);
$smcfg['pages'] = array(
	'changefreq' => ($cfg['plugin']['sitemap']['sm_pages_changefreq'] !== '') ? $cfg['plugin']['sitemap']['sm_pages_changefreq'] : 'daily',
	'priority'   => ($cfg['plugin']['sitemap']['sm_pages_priority'] !== '') ? $cfg['plugin']['sitemap']['sm_pages_priority'] : '0.8',
	'limit'      => (int) (($cfg['plugin']['sitemap']['sm_pages_limit'] !== '') ? $cfg['plugin']['sitemap']['sm_pages_limit'] : '40000'),
);
$smcfg['lists'] = array(
	'changefreq' => ($cfg['plugin']['sitemap']['sm_lists_changefreq'] !== '') ? $cfg['plugin']['sitemap']['sm_lists_changefreq'] : 'weekly',
	'priority'   => ($cfg['plugin']['sitemap']['sm_lists_priority'] !== '') ? $cfg['plugin']['sitemap']['sm_lists_priority'] : '0.5',
	'limit'      => (int) (($cfg['plugin']['sitemap']['sm_lists_limit'] !== '') ? $cfg['plugin']['sitemap']['sm_lists_limit'] : '1000'),
);
$smcfg['forums'] = array(
	'changefreq' => ($cfg['plugin']['sitemap']['sm_forums_changefreq'] !== '') ? $cfg['plugin']['sitemap']['sm_forums_changefreq'] : 'daily',
	'priority'   => ($cfg['plugin']['sitemap']['sm_forums_priority'] !== '') ? $cfg['plugin']['sitemap']['sm_forums_priority'] : '0.2',
	'limit'      => (int) (($cfg['plugin']['sitemap']['sm_forums_limit'] !== '') ? $cfg['plugin']['sitemap']['sm_forums_limit'] : '3000'),
);

$items = array();
$feed = '';

if (isset($smcfg[$m])) {
	$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$feed .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
}

$i = 1;

switch ($m) {
	case 'index':

		$items[$i]['loc'] = sed_sitemap_loc('index', '');
		$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $sys['now']);
		$items[$i]['changefreq'] = $smcfg['index']['changefreq'];
		$items[$i]['priority'] = $smcfg['index']['priority'];

		break;

	case 'lists':

		if (!sed_module_active('page') || $cfg['plugin']['sitemap']['disable_sitemap_pages'] === '1') {
			break;
		}

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code NOT LIKE 'system' ORDER BY structure_path ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row['structure_code']);
			if ($usr['auth_read']) {
				$i++;
				$sys['catcode'] = $row['structure_code'];
				$items[$i]['loc'] = sed_sitemap_loc('page', 'c=' . $row['structure_code']);
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $sys['now']);
				$items[$i]['changefreq'] = $smcfg['lists']['changefreq'];
				$items[$i]['priority'] = $smcfg['lists']['priority'];
			}
		}

		break;

	case 'pages':

		if (!sed_module_active('page') || $cfg['plugin']['sitemap']['disable_sitemap_pages'] === '1') {
			break;
		}

		$sql_cat = "";
		if (!empty($c)) {
			$mtch = $sed_cat[$c]['path'] . ".";
			$mtchlen = mb_strlen($mtch);
			$catsub = array();
			$catsub[] = $c;
			foreach ($sed_cat as $k => $x) {
				if (mb_substr($x['path'], 0, $mtchlen) == $mtch && sed_auth('page', $k, 'R')) {
					$catsub[] = $k;
				}
			}
			$sql_cat = " AND page_cat IN ('" . implode("','", $catsub) . "')";
		}

		$sql = sed_sql_query("SELECT page_id, page_alias, page_title, page_cat, page_date FROM $db_pages 
        WHERE page_state = 0 AND page_cat NOT LIKE 'system'" . $sql_cat . " ORDER by page_date DESC LIMIT " . $smcfg['pages']['limit']);

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row['page_cat']);
			if ($usr['auth_read']) {
				$i++;
				$sys['catcode'] = $row['page_cat'];
				$items[$i]['loc'] = empty($row['page_alias'])
					? sed_sitemap_loc('page', 'id=' . $row['page_id'])
					: sed_sitemap_loc('page', 'al=' . $row['page_alias']);
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['page_date']);
				$items[$i]['changefreq'] = $smcfg['pages']['changefreq'];
				$items[$i]['priority'] = $smcfg['pages']['priority'];
			}
		}

		break;

	case 'forums':

		if (!sed_module_active('forums') || $cfg['plugin']['sitemap']['disable_sitemap_forums'] === '1') {
			break;
		}

		$sql = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category, s.fs_topiccount, s.fs_postcount, s.fs_lt_date FROM $db_forum_sections AS s LEFT JOIN
        $db_forum_structure AS n ON n.fn_code=s.fs_category ORDER by fn_path ASC, fs_order ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$items[$i]['loc'] = sed_sitemap_loc('forums', 'm=topics&s=' . $row['fs_id'] . '&al=' . $row['fs_title']);
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fs_lt_date']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		$sql = sed_sql_query("SELECT t.ft_id, t.ft_title, t.ft_movedto, s.fs_id, p.fp_updated FROM $db_forum_posts p 
       LEFT JOIN $db_forum_topics t ON ( p.fp_topicid = t.ft_id ) LEFT JOIN $db_forum_sections s ON ( p.fp_sectionid = s.fs_id ) 
       GROUP BY t.ft_id ORDER BY t.ft_sticky DESC, p.fp_creation DESC LIMIT " . $smcfg['forums']['limit']);

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$moved = ($row['ft_movedto']) ? $row['ft_movedto'] : $row['ft_id'];
				$items[$i]['loc'] = sed_sitemap_loc('forums', 'm=posts&q=' . $moved . '&al=' . $row['ft_title']);
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fp_updated']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		break;

	default:

		$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$feed .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

		$index_sections = array('index');
		if (sed_module_active('page') && $cfg['plugin']['sitemap']['disable_sitemap_pages'] !== '1') {
			$index_sections[] = 'pages';
			$index_sections[] = 'lists';
		}
		if (sed_module_active('forums') && $cfg['plugin']['sitemap']['disable_sitemap_forums'] !== '1') {
			$index_sections[] = 'forums';
		}

		foreach ($index_sections as $key) {
			$feed .= "<sitemap>\n";
			$feed .= "<loc>" . sed_sitemap_loc('plug', 'e=sitemap&m=' . $key) . "</loc>\n";
			$feed .= "<lastmod>" . @date("Y-m-d\TH:i:s+00:00", $sys['now']) . "</lastmod>\n";
			$feed .= "</sitemap>\n";
		}
		$feed .= "</sitemapindex>\n";
		break;
}

if (isset($smcfg[$m])) {
	foreach ($items as $item) {
		$feed .= "<url>\n";
		$feed .= "<loc>" . $item['loc'] . "</loc>\n";
		$feed .= "<lastmod>" . $item['lastmod'] . "</lastmod>\n";
		$feed .= "<changefreq>" . $item['changefreq'] . "</changefreq>\n";
		$feed .= "<priority>" . $item['priority'] . "</priority>\n";
		$feed .= "</url>\n";
	}
	$feed .= "</urlset>";
}

@ob_clean();
header("Content-type: text/xml; charset=UTF-8");
echo (utf8_encode($feed));
exit;
