<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/sednews/sednews.php
Version=178
Updated=2022-jun-12
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=sednews
Part=main
File=sednews
Hooks=admin.home
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once(SED_ROOT . '/plugins/sednews/lang/sednews.'.$usr['lang'].'.lang.php');

function sed_get_rss($rss_content) {    
	if (trim($rss_content) == '') {
        return array();
    }	
	libxml_use_internal_errors(true);
	$rss_xml = simplexml_load_string($rss_content, 'SimpleXMLElement', LIBXML_NOCDATA);
    if ($rss_xml === false) {
        return array();
    } else {
		$rss_arr = json_decode(json_encode((array) $rss_xml), true);
		$rss_arr = array($rss_xml->getName() => $rss_arr);
		return $rss_arr;
    }
}

$sednews_maxitems = $cfg['plugin']['sednews']['maxitems'];
$sednews_rssfeed = $cfg['plugin']['sednews']['rssfeed'];

$sednews_rssfeed = (!empty($sednews_rssfeed)) ? $sednews_rssfeed : "https://seditio.org/rss";

$t->assign(array(
	"ADMIN_RSS_NEWS_TAB_TITLE" => $L['sednews_title']
));

$t->parse("ADMIN_HOME.ADMIN_RSS_NEWS_TAB"); 

$sn = new XTemplate(SED_ROOT . '/plugins/sednews/sednews.tpl');	

if (!$sed_rss_news)
	{
	$rss_content = @file_get_contents($sednews_rssfeed);
	$sed_rss_news = sed_get_rss($rss_content);
	sed_cache_store('sed_rss_news', $sed_rss_news, 3600);	
	}

if (count($sed_rss_news) > 0)
{
	$ii = 0;
	foreach ($sed_rss_news['rss']['channel']['item'] as $item)
		{	
		$ii++;
		$sn->assign(array(
			"RSS_NEWS_TITLE" => $item['title'],
			"RSS_NEWS_URL" => $item['link'],
			"RSS_NEWS_DATE" => sed_build_date($cfg['dateformat'], strtotime($item['pubDate'])),
			"RSS_NEWS_DESC" => sed_cutstring(strip_tags($item['description']), 150),
		));	
		$sn->parse("ADMIN_RSS_NEWS.ADMIN_RSS_NEWS_ROW"); 	
		if ($ii >= $sednews_maxitems) break;
		}
	$sn->parse("ADMIN_RSS_NEWS"); 	

	$t->assign("ADMIN_RSS_NEWS", $sn->text("ADMIN_RSS_NEWS"));
	$t->parse("ADMIN_HOME.ADMIN_RSS_NEWS_TABBODY"); 
}

?>