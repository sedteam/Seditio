<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=sitemap.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=XML Sitemap Generator
[END_SED]
==================== */

$smcfg['pages']['changefreq']  = "daily"; // (always/hourly/daily/weekly/monthly/yearly/never)
$smcfg['pages']['priority']    = "0.8";   // (default: 0.5)
$smcfg['pages']['limit']       = 40000;

$smcfg['lists']['changefreq']  = "weekly";
$smcfg['lists']['priority']    = "0.5";
$smcfg['lists']['limit']       = 1000;

$smcfg['index']['changefreq']  = "always";
$smcfg['index']['priority']    = "1.0";

$smcfg['forums']['changefreq'] = "daily";
$smcfg['forums']['priority']   = "0.2";
$smcfg['forums']['limit']      = 3000;

$m = sed_import('m', 'G', 'ALP'); // (index/lists/pages/forums)

$items = array();

$main_url = ($cfg['absurls']) ? $sys['abs_url'] : $cfg['mainurl'] . "/";

if (isset($smcfg[$m])) {
	$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$feed .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"; // current version
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

		$sql = sed_sql_query("SELECT structure_code FROM $db_structure WHERE structure_code NOT LIKE 'system' ORDER BY structure_path ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', $row['structure_code']);
			if ($usr['auth_read']) {
				$i++;
				$sys['catcode'] = $row['structure_code']; //new in v175 
				$row['list_url'] = sed_url("list", "c=" . $row['structure_code'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['list_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $sys['now']);
				$items[$i]['changefreq'] = $smcfg['lists']['changefreq'];
				$items[$i]['priority'] = $smcfg['lists']['priority'];
			}
		}

		break;

	case 'pages':

		if ($cfg['disable_page']) {
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
				$sys['catcode'] = $row['page_cat']; //new in v175        
				$row['page_pageurl'] = (empty($row['page_alias'])) ? sed_url("page", "id=" . $row['page_id'], "", false, false) : sed_url("page", "al=" . $row['page_alias'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['page_pageurl'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['page_date']);
				$items[$i]['changefreq'] = $smcfg['pages']['changefreq'];
				$items[$i]['priority'] = $smcfg['pages']['priority'];
			}
		}

		break;

	case 'forums':

		// forum sections

		$sql = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category, s.fs_topiccount, s.fs_postcount, s.fs_lt_date FROM $db_forum_sections AS s LEFT JOIN
        $db_forum_structure AS n ON n.fn_code=s.fs_category ORDER by fn_path ASC, fs_order ASC");

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$row['fs_url'] = sed_url("forums", "m=topics&s=" . $row['fs_id'], "", false, false);
				$items[$i]['loc'] = $main_url . $row['fs_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fs_lt_date']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		// forum posts

		$sql = sed_sql_query("SELECT t.ft_id, t.ft_movedto, s.fs_id, p.fp_updated FROM $db_forum_posts p 
       LEFT JOIN $db_forum_topics t ON ( p.fp_topicid = t.ft_id ) LEFT JOIN $db_forum_sections s ON ( p.fp_sectionid = s.fs_id ) 
       GROUP BY t.ft_id ORDER BY t.ft_sticky DESC, p.fp_creation DESC LIMIT " . $smcfg['forums']['limit']);

		while ($row = sed_sql_fetchassoc($sql)) {
			list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $row['fs_id']);
			if ($usr['auth_read']) {
				$i++;
				$moved = ($row['ft_movedto']) ? $row['ft_movedto'] : $row['ft_id'];
				$row['fp_url'] = sed_url('forums', 'm=posts&q=' . $moved, "", false, false);
				$items[$i]['loc'] = $main_url . $row['fp_url'];
				$items[$i]['lastmod'] = @date("Y-m-d\TH:i:s+00:00", $row['fp_updated']);
				$items[$i]['changefreq'] = $smcfg['forums']['changefreq'];
				$items[$i]['priority'] = $smcfg['forums']['priority'];
			}
		}

		break;

	default:

		// site map index by default
		$feed .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$feed .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		foreach ($smcfg as $key => $value) {
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

function miscGzHandler($buf)
{
	$zipRatio = 5;    // 0 <= zipRatio <= 9 depends of your server
	$bufZiped = gzcompress($buf, $zipRatio);
	$bufZiped = pack('cccccccc', 0x1f, 0x8b, 0x08, 0x00, 0x00, 0x00, 0x00, 0x00)
		. substr($bufZiped, 0, -4)
		. pack('V', crc32($buf))
		. pack('V', strlen($buf));
	header('Content-description: File Transfer');
	header('Content-type: application/x-gzip');
	header('Content-encoding: gzip/x-gzip');
	header('Content-length: ' . strlen($bufZiped));
	header("Content-Disposition: attachment; filename=sitemap.xml.gz");
	return $bufZiped;
}

/*
@ob_start('miscGzHandler');
echo(utf8_encode($feed));
@ob_end_flush(); // miscGzHandler
*/

@ob_clean();
header("Content-type: text/xml; charset=UTF-8");
echo (utf8_encode($feed));
exit;
