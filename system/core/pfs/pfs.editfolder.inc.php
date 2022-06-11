<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=pfs.editfolder.inc.php
Version=178
Updated=2022-jun-12
Type=Core
Author=Neocrome
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','TXT');
$o = sed_import('o','G','TXT');
$f = sed_import('f','G','INT');
$v = sed_import('v','G','TXT');
$c1 = sed_import('c1','G','TXT');
$c2 = sed_import('c2','G','TXT');
$userid = sed_import('userid','G','INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
list($usr['auth_read_gal'], $usr['auth_write_gal'], $usr['isadmin_gal']) = sed_auth('gallery', 'a');
sed_block($usr['auth_write']);

$L_pff_type[0] = $L['Private'];
$L_pff_type[1] = $L['Public'];
$L_pff_type[2] = $L['Gallery'];

if (!$usr['isadmin'] || $userid=='')
	{
	$userid = $usr['id'];
	}
else
	{
	$more = "userid=".$userid;
	}

if ($userid!=$usr['id'])
	{ sed_block($usr['isadmin']); }

$standalone = FALSE;
$user_info = sed_userinfo($userid);
$maingroup = ($userid==0) ? 5 : $user_info['user_maingrp'];

reset($sed_extensions);
foreach ($sed_extensions as $k => $line)
	{
 	$icon[$line[0]] = "<img src=\"system/img/pfs/".$line[2].".gif\" alt=\"".$line[1]."\" />";
 	$filedesc[$line[0]] = $line[1];
 	}

if (!empty($c1) || !empty($c2))
	{
	$more = "c1=".$c1."&c2=".$c2."&".$more;
	$standalone = TRUE;
	}

/* ============= */

$L['pfs_title'] = ($userid==0) ? $L['SFS'] : $L['pfs_title'];
$title = "<a href=\"".sed_url("pfs", $more)."\">".$L['pfs_title']."</a>";
$shorttitle = $L['pfs_title'];

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("pfs", $more)] = $L['pfs_title'];

if ($userid!=$usr['id'])
	{
	sed_block($usr['isadmin']);
	$title .= ($userid==0) ? '' : " (".sed_build_user($user_info['user_id'], $user_info['user_name']).")";
	}

$title .= " ".$cfg['separator']." ".$L['Edit'];

$out['subtitle'] = $L['Mypfs']." - ".$L['Edit'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

$sql = sed_sql_query("SELECT * FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql))
	{
	$pff_id=$row['pff_id'];
	$pff_date = $row['pff_date'];
	$pff_updated = $row['pff_updated'];
	$pff_title = $row['pff_title'];
	$pff_desc = $row['pff_desc'];
	$pff_type = $row['pff_type'];
	$pff_count = $row['pff_count'];
	$title .= " : ".sed_cc($pff_title);
	}
else
	{ sed_die(); }

if ($a=='update' && !empty($f))
	{
	$rtitle = sed_import('rtitle','P','TXT');
	$rdesc = sed_import('rdesc','P','HTM');
	$folderid = sed_import('folderid','P','INT');
	$rtype = sed_import('rtype','P','INT');
	$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$f' ");
	sed_die(sed_sql_numrows($sql)==0);
	$rtype = ($rtype==2 && !$usr['auth_write_gal']) ? 1 : $rtype;

	$sql = sed_sql_query("UPDATE $db_pfs_folders SET
		pff_title='".sed_sql_prep($rtitle)."',
		pff_updated='".$sys['now']."',
		pff_desc='".sed_sql_prep($rdesc)."',
		pff_type='$rtype'
		WHERE pff_userid='$userid' AND pff_id='$f' " );

	sed_redirect(sed_url("pfs", $more, "", true));
	exit;
	}

$row['pff_date'] = sed_build_date($cfg['dateformat'], $row['pff_date']);
$row['pff_updated'] = sed_build_date($cfg['dateformat'], $row['pff_updated']);

$body .= "<table class=\"cells striped\">";
$body .= "<form id=\"editfolder\" action=\"".sed_url("pfs" ,"m=editfolder&a=update&f=".$pff_id."&".$more)."\" method=\"post\">";
$body .= "<tr><td>".$L['Folder']." : </td><td><input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".sed_cc($pff_title)."\" size=\"56\" maxlength=\"255\" /></td></tr>";
$body .= "<tr><td>".$L['Date']." : </td><td>".$row['pff_date']."</td></tr>";
$body .= "<tr><td>".$L['Updated']." : </td><td>".$row['pff_updated']."</td></tr>";
$body .= "<tr><td>".$L['Type']." : </td><td>";

$rtype_arr = ($usr['auth_write_gal']) ? array(0 => $L['Private'], 1 => $L['Public'], 2 => $L['Gallery']) : array(0 => $L['Private'], 1 => $L['Public']);	
$body .= sed_radiobox("rtype", $rtype_arr, $row['pff_type']);

$body .= "</td></tr>";
$body .= "<tr><td colspan=\"2\">".$L['Description']." : <br />".sed_textarea('rdesc', $pff_desc, 8, 56, 'Micro')."</td></tr>";
$body .= "<tr><td colspan=\"2\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
$body .= "</form></table>";

/* ============= */

if ($standalone)
	{
	$pfs_header1 = $cfg['doctype']."<html><head>".sed_htmlmetas()."<title>".$out['subtitle']."</title>";
	$pfs_header2 = "</head><body>";
	$pfs_footer = "</body></html>";
	
	/* === New Hook Sed 175 === */
	$extp = sed_getextplugins('pfs.stndl');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ================================ */	

	$mskin = sed_skinfile(array('pfs', 'standalone'));
	$t = new XTemplate($mskin);

	$t->assign(array(
		"PFS_STANDALONE_HEADER1" => $pfs_header1,
		"PFS_STANDALONE_HEADER2" => $pfs_header2,
		"PFS_STANDALONE_FOOTER" => $pfs_footer,
	));

	$t->parse("MAIN.STANDALONE_HEADER");
	$t->parse("MAIN.STANDALONE_FOOTER");

	$t-> assign(array(
		"PFS_TITLE" => $title,
		"PFS_SHORTTITLE" => $shorttitle,
		"PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths, 1, false),
		"PFS_BODY" => $body
	));

	$t->parse("MAIN");
	$t->out("MAIN");
	}
else
	{
	require(SED_ROOT . "/system/header.php");

	$t = new XTemplate("skins/".$skin."/pfs.tpl");
	
	$t-> assign(array(
		"PFS_TITLE" => $title,
		"PFS_SHORTTITLE" => $shorttitle,
		"PFS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),		
		"PFS_BODY" => $body
	));

	$t->parse("MAIN");
	$t->out("MAIN");

	require(SED_ROOT . "/system/footer.php");
	}
?>