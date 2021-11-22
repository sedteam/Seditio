<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=gallery.browse.inc
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_GALLERY')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('gallery', 'a');
sed_block($usr['auth_read']);

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$sql_pff = sed_sql_query("SELECT f.*, u.user_name FROM $db_pfs_folders f
LEFT JOIN $db_users AS u ON f.pff_userid=u.user_id
WHERE pff_id='$f' AND pff_type=2");
sed_die(sed_sql_numrows($sql_pff)==0);
$pff = sed_sql_fetchassoc($sql_pff);

$pff['pff_desc'] = sed_parse($pff['pff_desc'], $cfg['parsebbcodecom'], $cfg['parsesmiliescom'], 1, $pff['pfs_desc_ishtml']);
if (!$pff['pff_desc_ishtml'] && $cfg['textmode'] == 'html')
	{
	$sql3 = sed_sql_query("UPDATE $db_pfs_folders SET pff_desc_ishtml=1, pff_desc='".sed_sql_prep($pff['pff_desc'])."' WHERE pff_id=".$pff['pff_id']); 
	}	

$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_folderid='$f' ORDER BY pfs_id ASC");
$nbitems = sed_sql_numrows($sql_pff);
$pff['pff_title'] = sed_cc($pff['pff_title']);
$userid = $pff['pff_userid'];

$sql2 = sed_sql_query("UPDATE $db_pfs_folders SET pff_count=pff_count+1 WHERE pff_id='".$f."' LIMIT 1");

$title = "<a href=\"".sed_url("gallery")."\">".$L['gallery_home_title']."</a> ".$cfg['separator']." <a href=\"".sed_url("gallery", "f=".$pff['pff_id'])."\">".$pff['pff_title']."</a>";
$subtitle = '';

$out['subtitle'] = $pff['pff_title'];
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('gallerytitle', $title_tags, $title_data);

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

require(SED_ROOT . "/system/header.php");
$t = new XTemplate("skins/".$skin."/gallery.browse.tpl");

$t-> assign(array(
	"GALLERY_BROWSE_TITLE" => $title,
	"GALLERY_BROWSE_SUBTITLE" => $subtitle
		));

$t->assign(array(
	"GALLERY_BROWSE_ID" => $pff['pff_id'],
	"GALLERY_BROWSE_DESC" => $pff['pff_desc'],
	"GALLERY_BROWSE_COUNT" => $pff['pff_count']
		));

$jj = 0;

while ($pfs = sed_sql_fetchassoc($sql))
	{
	$jj++;
	$pfs['pfs_fullfile'] = $cfg['pfs_dir'].$pfs['pfs_file'];
	$pfs['pfs_filesize'] = floor($pfs['pfs_size']/1024);

	if (($pfs['extension']=='jpg' || $pfs['extension']=='jpeg' || $pfs['extension']=='png') && $cfg['th_amode']!='Disabled')
		{
		if (!file_exists($cfg['th_dir'].$pfs['pfs_file']) && file_exists($cfg['pfs_dir'].$pfs['pfs_file']))
			{
			$pfs['th_colortext'] = array(
				hexdec(mb_substr($cfg['th_colortext'], 0, 2)),
				hexdec(mb_substr($cfg['th_colortext'], 2 ,2)),
				hexdec(mb_substr($cfg['th_colortext'], 4 ,2))
					);

			$pfs['th_colorbg'] = array(
				hexdec(mb_substr($cfg['th_colorbg'], 0, 2)),
				hexdec(mb_substr($cfg['th_colorbg'], 2, 2)),
				hexdec(mb_substr($cfg['th_colorbg'], 4, 2))
					);

			sed_createthumb(
				$cfg['pfs_dir'].$pfs['pfs_file'],
				$cfg['th_dir'].$pfs['pfs_file'],
				$cfg['th_x'],
				$cfg['th_y'],
				$cfg['th_keepratio'],
				$pfs['pfs_extension'],
				$pfs['pfs_file'],
				$pfs['pfs_filesize'],
				$pfs['th_colortext'],
				$cfg['th_textsize'],
				$pfs['th_colorbg'],
				$cfg['th_border'],
				$cfg['th_jpeg_quality']
					);
			}
 		}

	if ($cfg['gallery_bcol']==1)
		{
		$pfs['cond1'] = '<tr>';
		$pfs['cond2'] = '</tr>';
		}
	elseif ($jj==1)
		{
		$pfs['cond1'] = '<tr>';
		$pfs['cond2'] = '';
		}
	elseif ($jj==$cfg['gallery_bcol'])
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

	$pfs['popup'] = "<a href=\"javascript:sedjs.picture('".sed_url("pfs", "m=view&v=".$pfs['pfs_file'])."',200,200)\">";

	if ($usr['isadmin'])
		{
		$pfs['admin'] = "<a href=\"".sed_url("pfs", "m=edit&id=".$pfs['pfs_id']."&userid=".$userid)."\">".$out['img_edit']."</a>";
		}
	
  $pfs['pfs_desc'] = sed_parse($pfs['pfs_desc'], $cfg['parsebbcodecom'], $cfg['parsesmiliescom'], 1, $pfs['pfs_desc_ishtml']);
  if (!$pfs['pfs_desc_ishtml'] && $cfg['textmode'] == 'html')
  	{
  	$sql3 = sed_sql_query("UPDATE $db_pfs SET pfs_desc_ishtml=1, pfs_desc='".sed_sql_prep($pfs['pfs_desc'])."' WHERE pfs_id=".$pfs['pfs_id']); 
  	}	
    
  $t-> assign(array(
		"GALLERY_BROWSE_ROW_ID" => $pfs['pfs_id'],
		"GALLERY_BROWSE_ROW_VIEWURL" => sed_url("gallery", "id=".$pfs['pfs_id']),
		"GALLERY_BROWSE_ROW_VIEWPOPUP" => $pfs['popup'],
		"GALLERY_BROWSE_ROW_FILE" => $pfs['pfs_file'],
		"GALLERY_BROWSE_ROW_FULLFILE" => $pfs['pfs_fullfile'],
		"GALLERY_BROWSE_ROW_THUMB" => "<img src=\"".$cfg['th_dir'].$pfs['pfs_file']."\" alt=\"\" />",
		"GALLERY_BROWSE_ROW_ICON" => $icon[$pfs['pfs_extension']],
    "GALLERY_BROWSE_ROW_TITLE" => sed_cc($pfs['pfs_title']),
		"GALLERY_BROWSE_ROW_DESC" => $pfs['pfs_desc'],
		"GALLERY_BROWSE_ROW_SHORTDESC" => sed_cutstring(strip_tags($pfs['pfs_desc']), 48),
		"GALLERY_BROWSE_ROW_DATE" => sed_build_date($cfg['dateformat'], $pfs['pfs_date']),
		"GALLERY_BROWSE_ROW_SIZE" => $pfs['pfs_filesize'].$L['kb'],
		"GALLERY_BROWSE_ROW_COUNT" => $pfs['pfs_count'],
		"GALLERY_BROWSE_ROW_ADMIN" => $pfs['admin'],
		"GALLERY_BROWSE_ROW_COND1" => $pfs['cond1'],
		"GALLERY_BROWSE_ROW_COND2" => $pfs['cond2']
			));
	$t->parse("MAIN.ROW");
	}

/* === Hook === */
$extp = sed_getextplugins('gallery.browse.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");


?>