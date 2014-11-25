<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=gallery.home.inc
Version=175
Updated=2012-dec-31
Type=Core
Author=Neocrome
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_GALLERY')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['auth_read']);

/* === Hook === */
$extp = sed_getextplugins('gallery.home.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_type='2' AND pff_sample='0'");
 
while ($row = sed_sql_fetchassoc($sql))
  {
  $sql1 = sed_sql_query("SELECT pfs_id FROM $db_pfs WHERE pfs_folderid='".$row['pff_id']."' ORDER BY pfs_id ASC LIMIT 1");
  if ($row1 = sed_sql_fetchassoc($sql1))
    { $sql2 = sed_sql_query("UPDATE $db_pfs_folders SET pff_sample='".$row1['pfs_id']."' WHERE pff_id='".$row['pff_id']."'"); }
  }
                                          
$sql_gal = sed_sql_query("SELECT f.*, p.pfs_file, u.user_name
		FROM $db_pfs_folders f
		LEFT JOIN $db_pfs AS p ON p.pfs_id=f.pff_sample
		LEFT JOIN $db_users AS u ON u.user_id=f.pff_userid
    WHERE p.pfs_extension IN $gd_supported_sql AND f.pff_type=2 AND p.pfs_file!='' 
		ORDER BY f.pff_id DESC");

$sql_galcount = sed_sql_query("SELECT p.pfs_folderid, COUNT(pfs_id)
		FROM $db_pfs as p
		LEFT JOIN $db_pfs_folders AS f ON p.pfs_folderid=f.pff_id
		WHERE p.pfs_extension IN $gd_supported_sql AND f.pff_type=2
    GROUP BY p.pfs_folderid");

while ($row = sed_sql_fetchassoc($sql_galcount))
	{ $galcount[$row['pfs_folderid']] = $row['COUNT(pfs_id)']; }


$sql_stats = sed_sql_query("SELECT SUM(pfs_count), SUM(pfs_size)
		FROM $db_pfs AS p
		LEFT JOIN $db_pfs_folders f ON p.pfs_folderid=f.pff_id
		WHERE f.pff_type=2");

$stats_totalviews = sed_sql_result($sql_stats, 0, "SUM(pfs_count)");
$stats_totalsize = floor(sed_sql_result($sql_stats, 0, "SUM(pfs_size)")/1024);

  /*
$sql_au = sed_sql_query("SELECT DISTINCT u.user_id, u.user_name, COUNT(*)
		FROM $db_pfs_folders f
		LEFT JOIN $db_users AS u ON u.user_id=f.pff_userid
		WHERE f.pff_type=2 
		GROUP BY f.pff_userid
		ORDER BY u.user_name ASC");
	*/

$title = $L['gallery_home_title'];
$subtitle = '';

$out['subtitle'] = $L['gallery_home_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('gallerytitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('gallery.home.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */


require("system/header.php");
$t = new XTemplate("skins/".$skin."/gallery.home.tpl");

$t-> assign(array(
	"GALLERY_HOME_TITLE" => $title,
	"GALLERY_HOME_SUBTITLE" => $subtitle
		));


/*
while ($rau = sed_sql_fetchassoc($sql_au))
	{
	$t-> assign(array(
		"GALLERY_HOME_USERS_ROW_URL" => 'gallery&amp;g='.$rau['user_id'],
		"GALLERY_HOME_USERS_ROW_COUNT" => $rau['COUNT(*)'],
		"GALLERY_HOME_USERS_ROW_AUTHOR" => sed_cc($rau['user_name'])
				));
	$t->parse("MAIN.USERS");
	}

*/

$jj=0;
$gals = 0;
$gals_t = 0;

while ($row = sed_sql_fetchassoc($sql_gal))
	{
	$jj++;
  $gals++;
	$gals_t += $galcount[$row['pff_id']];

	if ($cfg['gallery_gcol']==1)
		{
		$pfs['cond1'] = '<tr>';
		$pfs['cond2'] = '</tr>';
		}
	elseif ($jj==1)
		{
		$pfs['cond1'] = '<tr>';
		$pfs['cond2'] = '';
		}
	elseif ($jj==$cfg['gallery_gcol'])
		{
		$jj=0;
		$pfs['cond1'] = '';
		$pfs['cond2'] = '</tr>';
		}
	else
		{
		$pfs['cond1'] = '';
		$pfs['cond2'] = '';
		}

  $row['pff_desc'] = sed_parse($row['pff_desc'], $cfg['parsebbcodecom'], $cfg['parsesmiliescom'], 1, $row['pfs_desc_ishtml']);
  if (!$row['pff_desc_ishtml'] && $cfg['textmode'] == 'html')
  	{
  	$sql3 = sed_sql_query("UPDATE $db_pfs_folders SET pff_desc_ishtml=1, pff_desc='".sed_sql_prep($row['pff_desc'])."' WHERE pff_id=".$row['pff_id']); 
  	}

	$t-> assign(array(
		"GALLERY_HOME_GALLERIES_ROW_URL" => sed_url("gallery", "f=".$row['pff_id']),
		"GALLERY_HOME_GALLERIES_ROW_SAMPLE" =>  $cfg['th_dir'].$row['pfs_file'],
		"GALLERY_HOME_GALLERIES_ROW_TITLE" => sed_cc($row['pff_title']),
		"GALLERY_HOME_GALLERIES_ROW_SHORTTITLE" => sed_cutstring(sed_cc($row['pff_title']), 64),
		"GALLERY_HOME_GALLERIES_ROW_DESC" => $row['pff_desc'],
		"GALLERY_HOME_GALLERIES_ROW_COUNT" => $galcount[$row['pff_id']],
		"GALLERY_HOME_GALLERIES_ROW_USER" => sed_build_user($row['pff_userid'], sed_cc($row['user_name'])),
		"GALLERY_HOME_GALLERIES_ROW_DATE" => sed_build_date($cfg['formatyearmonthday'], $row['pff_date']),
		"GALLERY_HOME_GALLERIES_ROW_UPDATED" => sed_build_date($cfg['formatyearmonthday'], $row['pff_updated']),
		"GALLERY_HOME_GALLERIES_ROW_COND1" => $pfs['cond1'],
		"GALLERY_HOME_GALLERIES_ROW_COND2" => $pfs['cond2']
			));

	$t->parse("MAIN.GALLERIES.ROW");
	}

$t-> assign(array(
	"GALLERY_HOME_TOTALFOLDERS" => $gals,
	"GALLERY_HOME_TOTALFILES" => $gals_t,
	"GALLERY_HOME_TOTALSIZE" => $stats_totalsize,
	"GALLERY_HOME_TOTALVIEWS" => $stats_totalviews
		));

/* === Hook === */
$extp = sed_getextplugins('gallery.home.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN.GALLERIES");

$t->parse("MAIN");
$t->out("MAIN");

require("system/footer.php");


?>