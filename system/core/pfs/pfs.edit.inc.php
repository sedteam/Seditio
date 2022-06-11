<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=pfs.edit.inc.php
Version=178
Updated=2022-jun-12
Type=Core
Author=Neocrome
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$id = sed_import('id','G','INT');
$o = sed_import('o','G','TXT');
$f = sed_import('f','G','INT');
$v = sed_import('v','G','TXT');
$c1 = sed_import('c1','G','TXT');
$c2 = sed_import('c2','G','TXT');
$userid = sed_import('userid','G','INT');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('pfs', 'a');
sed_block($usr['auth_write']);

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

$sql = sed_sql_query("SELECT * FROM $db_pfs WHERE pfs_userid='$userid' AND pfs_id='$id' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql))
	{
	$pfs_id = $row['pfs_id'];
	$pfs_file = $row['pfs_file'];
	$pfs_date = sed_build_date($cfg['dateformat'], $row['pfs_date']);
	$pfs_folderid = $row['pfs_folderid'];
	$pfs_extension = $row['pfs_extension'];
	$pfs_desc = $row['pfs_desc'];
	$pfs_title = sed_cc($row['pfs_title']);
	$pfs_size = floor($row['pfs_size']/1024);
	$ff = $cfg['pfs_dir'].$pfs_file;
	}
	else
	{ sed_die(); }

$title .= " ".$cfg['separator']." ".sed_cc($pfs_file);


$out['subtitle'] = $L['Mypfs']." - ".$L['Edit'];
$title_tags[] = array('{MAINTITLE}', '{TITLE}', '{SUBTITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $out['subtitle'], $cfg['subtitle']);
$out['subtitle'] = sed_title('pfstitle', $title_tags, $title_data);

if ($a=='update' && !empty($id))
	{
	$rdesc = sed_import('rdesc','P','HTM');
	$rtitle = sed_import('rtitle','P','TXT');
	$folderid = sed_import('folderid','P','INT');
	if ($folderid>0)
		{
		$sql = sed_sql_query("SELECT pff_id FROM $db_pfs_folders WHERE pff_userid='$userid' AND pff_id='$folderid'");
		sed_die(sed_sql_numrows($sql)==0);
		}
	else
		{ $folderid = 0; }

	$sql = sed_sql_query("UPDATE $db_pfs SET
		pfs_desc='".sed_sql_prep($rdesc)."',
		pfs_title='".sed_sql_prep($rtitle)."',
		pfs_folderid='$folderid'
		WHERE pfs_userid='$userid' AND pfs_id='$id'");

	sed_redirect(sed_url("pfs", "f=".$pfs_folderid."&".$more, "", true));
	exit;
	}

$body .= "<table class=\"cells striped\">";
$body .= "<form id=\"edit\" action=\"".sed_url("pfs", "m=edit&a=update&id=".$pfs_id."&".$more)."\" method=\"post\">";
$body .= "<tr><td>".$L['File']." : </td><td>".$pfs_file."</td></tr>";
$body .= "<tr><td>".$L['Date']." : </td><td>".$pfs_date."</td></tr>";
$body .= "<tr><td>".$L['Folder']." : </td><td>".sed_selectbox_folders($userid, "", $pfs_folderid)."</td></tr>";
$body .= "<tr><td>".$L['URL']." : </td><td><a href=\"".$ff."\">".$ff."</a></td></tr>";
$body .= "<tr><td>".$L['Size']." : </td><td>".$pfs_size." ".$L['kb']."</td></tr>";
$body .= "<tr><td>".$L['Title']." : </td><td><input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".sed_cc($pfs_title)."\" size=\"56\" maxlength=\"255\" /></td></tr>";
$body .= "<tr><td colspan=\"2\">".$L['Description']." : <br />".sed_textarea('rdesc', $pfs_desc, 8, 56, 'Micro')."</td></tr>";
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