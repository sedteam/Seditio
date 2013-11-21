<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/recentitems/recentitems.php
Version=173
Updated=2008-may-26
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=recentitems
Part=main
File=recentitems
Hooks=index.tags
Tags=index.tpl:{PLUGIN_LATESTPAGES},{PLUGIN_LATESTTOPICS},{PLUGIN_LATESTPOLL}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/* ============ MASKS FOR THE HTML OUTPUTS =========== */

include_once("plugins/recentitems/inc/recentitems.inc.php");

$cfg['plu_mask_pages'] = "%3\$s"." : "."%1\$s"." ".$cfg['separator']." "."%2\$s"."<br />";
// %1\$s = Link to the category
// %2\$s = Link to the page
// %3\$s = Date

$cfg['plu_mask_topics'] =  "%2\$s"." : "."%3\$s"." ".$cfg['separator']." "."%4\$s"." ("."%5\$s".")<br />";
// %1\$s = "Follow" image
// %2\$s = Date
// %3\$s = Section
// %4\$s = Topic title
// %5\$s = Number of replies

$cfg['plu_mask_polls'] =  "<div>%1\$s</div>";

$plu_empty = $L['None']."<br />";

if ($cfg['plugin']['recentitems']['maxpages']>0 && !$cfg['disable_page'])
	{ $latestpages = sed_get_latestpages($cfg['plugin']['recentitems']['maxpages'], $cfg['plu_mask_pages']); }

if ($cfg['plugin']['recentitems']['maxtopics']>0 && !$cfg['disable_forums'])
	{ $latesttopics = sed_get_latesttopics($cfg['plugin']['recentitems']['maxtopics'], $cfg['plu_mask_topics']); }

if ($cfg['plugin']['recentitems']['maxpolls']>0 && !$cfg['disable_polls'])
	{ $latestpoll = sed_get_latestpolls($cfg['plugin']['recentitems']['maxpolls'], $cfg['plu_mask_polls']); }

$t-> assign(array(
	"PLUGIN_LATESTPAGES" => $latestpages,
	"PLUGIN_LATESTTOPICS" => $latesttopics,
	"PLUGIN_LATESTPOLL" => $latestpoll,
	));

?>