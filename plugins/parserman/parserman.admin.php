<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org

[BEGIN_SED]
File=plugins/parserman/parserman.php
Version=130
Updated=2010-feb-05
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=parserman
Part=admin
File=parserman.admin
Hooks=tools
Tags=
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$plugin_title = "Parser management";

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$id = sed_import('id', 'G', 'INT');

$parser_mode[0] = "Replace";
$parser_mode[1] = "Regular Expression";
$parser_mode_expl[0] = "Replace - <a href=\"http://www.php.net/str_replace\">str_replace()</a>";
$parser_mode_expl[1] = "Regular Expression - <a href=\"http://www.php.net/preg_replace\">preg_replace()</a>";
$parser_type[0] = "System";
$parser_type[1] = "User defined";
$parser_active[0] = $L['No'];
$parser_active[1] = $L['Yes'];

$L['Mode'] = "Mode";



// ---------------

if ($a=='details')
	{
	$sql = sed_sql_query("SELECT * FROM $db_parser WHERE parser_id='$id'");
	sed_die(sed_sql_numrows($sql)==0);
	$row = sed_sql_fetcharray($sql);

	if ($n=='update')
		{
		$rcode1 = sed_sql_prep(sed_import('rcode1', 'P', 'HTM'));
		$rcode2 = sed_sql_prep(sed_import('rcode2', 'P', 'HTM'));
		$rbb1 = sed_sql_prep(sed_import('rbb1', 'P', 'HTM'));
		$rbb2 = sed_sql_prep(sed_import('rbb2', 'P', 'HTM'));
		$rtitle = sed_sql_prep(sed_import('rtitle', 'P', 'TXT'));
		$rorder = sed_sql_prep(sed_import('rorder', 'P', 'INT'));
		$rmode = sed_sql_prep(sed_import('rmode', 'P', 'INT'));
		$ractive = sed_sql_prep(sed_import('ractive', 'P', 'BOL'));
		$rtitle = (empty($rtitle)) ? '???' : $rtitle;
		
		$sql = sed_sql_query("UPDATE $db_parser SET parser_title='$rtitle', parser_mode='$rmode', parser_order=".(int)$rorder.", parser_bb1='$rbb1', parser_bb2='$rbb2', parser_code1='$rcode1', parser_code2='$rcode2', parser_active='$ractive' WHERE parser_id='$id'");
		
		sed_cache_clear('sed_parser');
		sed_redirect(sed_url("admin", "m=tools&p=parserman", "", true));
		exit;
		}

	$plugin_body .= "<form id=\"updateparser\" action=\"".sed_url("admin", "m=tools&p=parserman&a=details&n=update&id=".$row['parser_id'])."\" method=\"post\">";
	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr><td>"."#</td><td>".$row['parser_id']."</td></tr>";
	$plugin_body .= "<tr><td>".$L['Title']." :</td><td><input type=\"text\" class=\"text\" name=\"rtitle\" value=\"".sed_cc($row['parser_title'])."\" size=\"56\" maxlength=\"64\" /></td></tr>";
	$plugin_body .= "<tr><td>".$L['Type']." :</td><td>".$parser_type[$row['parser_type']]."</td></tr>";
	$plugin_body .= "<tr><td>".$L['Mode']." :</td><td>";
	$checked = ($row['parser_mode']==0) ? "checked=\"checked\"" : '';
	$plugin_body .= "<input type=\"radio\" class=\"radio\" name=\"rmode\" value=\"0\" $checked /> ".$parser_mode_expl[0]."<br />";
	$checked = ($row['parser_mode']==1) ? "checked=\"checked\"" : '';
	$plugin_body .= "<input type=\"radio\" class=\"radio\" name=\"rmode\" value=\"1\" $checked /> ".$parser_mode_expl[1]."</td></tr>";
	$plugin_body .= "<tr><td>".$L['Order']." :</td><td><input type=\"text\" class=\"text\" name=\"rorder\" value=\"".sed_cc($row['parser_order'])."\" size=\"16\" maxlength=\"8\" /></td></tr>";

	$plugin_body .= "<tr><td>BB 1 > HTML 1 :</td><td>";
	$plugin_body .= "<table style=\"width:100%;\"><tr><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"rbb1\" rows=\"6\" cols=\"36\" class=\"noeditor\">".sed_cc($row['parser_bb1'])."</textarea></td>";
	$plugin_body .= "<td style=\"width:10%;\">--></td>";
	$plugin_body .= "<td style=\"width:45%\"><textarea name=\"rcode1\" rows=\"6\" cols=\"36\" class=\"noeditor\">".sed_cc($row['parser_code1'])."</textarea></td></tr></table>";
	$plugin_body .= "</td></tr>";
	$plugin_body .= "<tr><td>BB 2 > HTML 2 :</td><td>";
	$plugin_body .= "<table style=\"width:100%;\"><tr><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"rbb2\" rows=\"6\" cols=\"36\" class=\"noeditor\">".sed_cc($row['parser_bb2'])."</textarea></td>";
	$plugin_body .= "<td style=\"width:10%;\">--></td>";
	$plugin_body .= "<td style=\"width:45%\"><textarea name=\"rcode2\" rows=\"6\" cols=\"36\" class=\"noeditor\">".sed_cc($row['parser_code2'])."</textarea></td></tr></table>";
	$plugin_body .= "</td></tr>";
	$plugin_body .= "<tr><td>".$L['Active']." :</td><td>";
	$checked = ($row['parser_active']==1) ? "checked=\"checked\"" : '';
	$plugin_body .= "<input type=\"checkbox\" class=\"checkbox\" name=\"ractive\" ".$checked." /></td></tr>";
	$plugin_body .= "<tr><td>".$L['Delete']." :</td><td>[<a href=\"".sed_url("admin", "m=tools&p=parserman&a=delete&id=".$row['parser_id']."&".sed_xg())."\">x</a>]</td></tr>";
	$plugin_body .= "<tr><td colspan=\"2\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
	$plugin_body .= "</table></form>";
	}

elseif ($a=='update')
	{
	sed_check_xg();

	$s = sed_import('s', 'P', 'ARR');
	foreach($s as $i => $k)
		{
		if (isset($s[$i]['active'])) {$s[$i]['active']=1;}
		$sql = sed_sql_query("UPDATE $db_parser SET
			parser_order='".sed_sql_prep($s[$i]['order'])."',
			parser_active='".sed_sql_prep($s[$i]['active'])."'
			WHERE parser_id='$i'");
		}

	sed_cache_clear('sed_parser');
	sed_redirect(sed_url("admin", "m=tools&p=parserman", "", true));
	exit;
	}
elseif ($a=='add')
	{
	$ncode1 = sed_sql_prep(sed_import('ncode1', 'P', 'HTM'));
	$ncode2 = sed_sql_prep(sed_import('ncode2', 'P', 'HTM'));
	$nbb1 = sed_sql_prep(sed_import('nbb1', 'P', 'HTM'));
	$nbb2 = sed_sql_prep(sed_import('nbb2', 'P', 'HTM'));
	$ntitle = sed_sql_prep(sed_import('ntitle', 'P', 'TXT'));
	$norder = sed_sql_prep(sed_import('norder', 'P', 'INT'));
	$nmode = sed_sql_prep(sed_import('nmode', 'P', 'INT'));
	$nactive = sed_sql_prep(sed_import('nactive', 'P', 'BOL'));
	$ntitle = (empty($ntitle)) ? '???' : $ntitle;

	$sql = sed_sql_query("INSERT INTO $db_parser (parser_title, parser_type, parser_mode, parser_order, parser_bb1, parser_bb2, parser_code1, parser_code2, parser_active) VALUES ('$ntitle', 1, '$nmode', ".(int)$norder.", '$nbb1', '$nbb2', '$ncode1', '$ncode2', '$nactive')");

	sed_cache_clear('sed_parser');
	sed_redirect(sed_url("admin", "m=tools&p=parserman", "", true));
	exit;
	}
elseif ($a=='delete')
	{
	sed_check_xg();
	$sql = sed_sql_query("DELETE FROM $db_parser WHERE parser_id='$id'");
	sed_cache_clear('sed_parser');
	sed_redirect(sed_url("admin", "m=tools&p=parserman", "", true));
	exit;
	}
else
	{
	$sql = sed_sql_query("SELECT * FROM $db_parser ORDER by parser_mode ASC, parser_type ASC, parser_order ASC");

	$plugin_body .= "<h4>".$L['editdeleteentries']." :</h4>";
	$plugin_body .= "<form id=\"saveparser\" action=\"".sed_url("admin", "m=tools&p=parserman&a=update&".sed_xg())."\" method=\"post\">";
	$plugin_body .= "<table class=\"cells striped\"><tr>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:40px;\">".$L['Delete']."</td>";
	$plugin_body .= "<td class=\"coltop\" colspan=\"2\">".$L['BBcode']." ".$L['adm_clicktoedit']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:128px;\">".$L['Type']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:128px;\">".$L['Mode']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:64px;\">".$L['Order']."</td>";
	$plugin_body .= "<td class=\"coltop\" style=\"width:64px;\">".$L['Active']."</td>";
	$plugin_body .= "</tr>";
	$prevmode = -1;

	while ($row = sed_sql_fetcharray($sql))
		{
		if ($row['parser_mode']!=$prevmode)
			{
			$prevmode = $row['parser_mode'];
			$plugin_body .= "<tr><td class=\"coltop\" colspan=\"7\">".$parser_mode[$row['parser_mode']]."</td></tr>";
			}

		$plugin_body .= "<tr><td style=\"text-align:center;\">[<a href=\"".sed_url("admin", "m=tools&p=parserman&a=delete&id=".$row['parser_id']."&".sed_xg())."\">x</a>]";
		$plugin_body .= "<td style=\"text-align:left;\"><a href=\"".sed_url("admin", "m=tools&p=parserman&a=details&id=".$row['parser_id']."&".sed_xg())."\">".sed_cc($row['parser_title'])."</a></td>";
		$plugin_body .= "<td style=\"text-align:left;\">".sed_cc(sed_cutstring($row['parser_bb1'], 32))."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$parser_type[$row['parser_type']]."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">".$parser_mode[$row['parser_mode']]."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">";
		$plugin_body .= "<input type=\"text\" class=\"text\" name=\"s[".$row['parser_id']."][order]\" value=\"".$row['parser_order']."\" size=\"6\" maxlength=\"8\" /></td>";
		$plugin_body .= "<td style=\"text-align:center;\">";
		$checked = ($row['parser_active']==1) ? "checked=\"checked\"" : '';
		$plugin_body .= "<input type=\"checkbox\" class=\"checkbox\" name=\"s[".$row['parser_id']."][active]\" ".$checked." /></td>";
		$plugin_body .= "</tr>";
		}

	$plugin_body .= "<tr><td colspan=\"7\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Update']."\" /></td></tr>";
	$plugin_body .= "</table></form>";

	$plugin_body .= "<h4>".$L['addnewentry']." :</h4>";
	$plugin_body .= "<form id=\"addparser\" action=\"".sed_url("admin", "m=tools&p=parserman&a=add&".sed_xg())."\" method=\"post\">";
	$plugin_body .= "<table class=\"cells striped\">";
	$plugin_body .= "<tr><td>".$L['Title']." :</td><td><input type=\"text\" class=\"text\" name=\"ntitle\" value=\"\" size=\"56\" maxlength=\"64\" /></td></tr>";
	$plugin_body .= "<tr><td>".$L['Type']." :</td><td>".$parser_type[1]."</td></tr>";
	$plugin_body .= "<tr><td>".$L['Mode']." :</td><td>";
	$plugin_body .= "<input type=\"radio\" class=\"radio\" name=\"nmode\" value=\"0\" checked=\"checked\" /> ".$parser_mode_expl[0]."<br />";
	$plugin_body .= "<input type=\"radio\" class=\"radio\" name=\"nmode\" value=\"1\" /> ".$parser_mode_expl[1]."</td></tr>";
	$plugin_body .= "<tr><td>".$L['Order']." :</td><td><input type=\"text\" class=\"text\" name=\"norder\" value=\"\" size=\"16\" maxlength=\"8\" /></td></tr>";

	$plugin_body .= "<tr><td>BB 1 > HTML 1 :</td><td>";
	$plugin_body .= "<table style=\"width:100%;\"><tr><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"nbb1\" rows=\"6\" cols=\"36\" class=\"noeditor\"></textarea></td>";
	$plugin_body .= "<td style=\"width:10%;\">--></td><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"ncode1\" rows=\"6\" cols=\"36\" class=\"noeditor\"></textarea></td></tr></table>";
	$plugin_body .= "</td></tr>";
	$plugin_body .= "<tr><td>BB 2 > HTML 2 :</td><td>";
	$plugin_body .= "<table style=\"width:100%;\"><tr><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"nbb2\" rows=\"6\" cols=\"36\" class=\"noeditor\"></textarea></td>";
	$plugin_body .= "<td style=\"width:10%;\">--></td><td style=\"width:45%\">";
	$plugin_body .= "<textarea name=\"ncode2\" rows=\"6\" cols=\"36\" class=\"noeditor\"></textarea></td></tr></table>";
	$plugin_body .= "</td></tr>";
	$plugin_body .= "<tr><td>".$L['Active']." :</td><td>";
	$plugin_body .= "<input type=\"checkbox\" class=\"checkbox\" name=\"nactive\" checked=\"checked\" /></td></tr>";
	$plugin_body .= "<tr><td colspan=\"2\"><input type=\"submit\" class=\"submit btn\" value=\"".$L['Add']."\" colspan=\"2\" /></td></tr>";
	$plugin_body .= "</table></form>";
	}

?>