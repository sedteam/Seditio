<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/sitemap/sitemap.main.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Seditio Team
Description=XML Sitemap Generator
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* Build $smcfg from module config ($cfg) */
$smcfg['index'] = array(
	'changefreq' => isset($cfg['sm_index_changefreq']) ? $cfg['sm_index_changefreq'] : 'always',
	'priority'   => isset($cfg['sm_index_priority']) ? $cfg['sm_index_priority'] : '1.0',
);
$smcfg['pages'] = array(
	'changefreq' => isset($cfg['sm_pages_changefreq']) ? $cfg['sm_pages_changefreq'] : 'daily',
	'priority'   => isset($cfg['sm_pages_priority']) ? $cfg['sm_pages_priority'] : '0.8',
	'limit'      => isset($cfg['sm_pages_limit']) ? (int)$cfg['sm_pages_limit'] : 40000,
);
$smcfg['lists'] = array(
	'changefreq' => isset($cfg['sm_lists_changefreq']) ? $cfg['sm_lists_changefreq'] : 'weekly',
	'priority'   => isset($cfg['sm_lists_priority']) ? $cfg['sm_lists_priority'] : '0.5',
	'limit'      => isset($cfg['sm_lists_limit']) ? (int)$cfg['sm_lists_limit'] : 1000,
);
$smcfg['forums'] = array(
	'changefreq' => isset($cfg['sm_forums_changefreq']) ? $cfg['sm_forums_changefreq'] : 'daily',
	'priority'   => isset($cfg['sm_forums_priority']) ? $cfg['sm_forums_priority'] : '0.2',
	'limit'      => isset($cfg['sm_forums_limit']) ? (int)$cfg['sm_forums_limit'] : 3000,
);

$m = sed_import('m', 'G', 'ALP'); // (index/lists/pages/forums)

$items = array();
$feed = '';

$main_url = ($cfg['absurls']) ? $sys['abs_url'] : $cfg['mainurl'] . "/";

if (isset($smcfg[$m])) {
	$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$feed .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
}

$i = 1;

switch ($m) {
	case 'index':

		$items[$i]['loc'] = $cfg['mainurl'] . "/";
		$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $sys['now']);
		$items[$i]['changefreq'] = $smcfg['index']['changefreq'];
		$items[$i]['priority'] = $smcfg['index']['priority'];

		break;

	case 'lists':

		if (isset($cfg['disable_sitemap_pages']) && $cfg['disable_sitemap_pages'] == '1') {
			break;
		}

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code NOT LIKE 'system' ORDER BY structure_path ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row['structure_code']);
			if ($usr['auth_read']) {
				$i++;
				$sys['catcode'] = $row['structure_code'];
				$row['list_url'] = sed_url("page", "c=" . $row['structure_code'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['list_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $sys['now']);
				$items[$i]['changefreq'] = $smcfg['lists']['changefreq'];
				$items[$i]['priority'] = $smcfg['lists']['priority'];
			}
		}

		break;

	case 'pages':

		if (isset($cfg['disable_sitemap_pages']) && $cfg['disable_sitemap_pages'] == '1') {
			break;
		}

		if (!sed_module_active('page')) {
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
				$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id'], "", false, false) : sed_url("page", "al=" . $row['page_alias'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['page_pageurl'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['page_date']);
				$items[$i]['changefreq'] = $smcfg['pages']['changefreq'];
				$items[$i]['priority'] = $smcfg['pages']['priority'];
			}
		}

		break;

	case 'forums':

		if (!sed_module_active('forums')) {
			break;
		}
		if (isset($cfg['disable_sitemap_forums']) && $cfg['disable_sitemap_forums'] == '1') {
			break;
		}

		// forum sections

		$sql = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category, s.fs_topiccount, s.fs_postcount, s.fs_lt_date FROM $db_forum_sections AS s LEFT JOIN
        $db_forum_structure AS n ON n.fn_code=s.fs_category ORDER by fn_path ASC, fs_order ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$row['fs_url'] = sed_url("forums", "m=topics&s=" . $row['fs_id'] . "&al=" . $row['fs_title'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['fs_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fs_lt_date']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		// forum posts

		$sql = sed_sql_query("SELECT t.ft_id, t.ft_title, t.ft_movedto, s.fs_id, p.fp_updated FROM $db_forum_posts p 
       LEFT JOIN $db_forum_topics t ON ( p.fp_topicid = t.ft_id ) LEFT JOIN $db_forum_sections s ON ( p.fp_sectionid = s.fs_id ) 
       GROUP BY t.ft_id ORDER BY t.ft_sticky DESC, p.fp_creation DESC LIMIT " . $smcfg['forums']['limit']);

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$moved = ($row['ft_movedto']) ? $row['ft_movedto'] : $row['ft_id'];
				$row['fp_url'] = sed_url('forums', 'm=posts&q=' . $moved . "&al=" . $row['ft_title'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['fp_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fp_updated']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		break;

	default:

		// sitemap index: only include sections that are enabled
		$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$feed .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

		$index_sections = array('index');
		if (empty($cfg['disable_sitemap_pages']) || $cfg['disable_sitemap_pages'] != '1') {
			$index_sections[] = 'pages';
			$index_sections[] = 'lists';
		}
		if (sed_module_active('forums') &&
			(empty($cfg['disable_sitemap_forums']) || $cfg['disable_sitemap_forums'] != '1')) {
			$index_sections[] = 'forums';
		}

		foreach ($index_sections as $key) {
			$feed .= "<sitemap>\n";
			$feed .= "<loc>" . $main_url . sed_url("sitemap", "m=" . $key, "", false, false) . "</loc>\n";
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
