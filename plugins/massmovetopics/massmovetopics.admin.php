<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/massmovetopics/massmovetopics.admin.php
Version=179
Updated=2022-jul-15
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=massmovetopics
Part=admin
File=massmovetopics.admin
Hooks=tools
Tags=
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$plugin_title = "Mass-move topics in forums";

$sourceid = sed_import('sourceid','P','INT');
$targetid = sed_import('targetid','P','INT');

if ($a=='move')
	{
	$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_sectionid='$targetid' WHERE ft_sectionid='$sourceid'");
	$sql = sed_sql_query("UPDATE $db_forum_posts SET fp_sectionid='$targetid' WHERE fp_sectionid='$sourceid'");
	sed_forum_sectionsetlast($sourceid);
	sed_forum_sectionsetlast($targetid);
	sed_forum_resync($sourceid);
	sed_forum_resync($targetid);
	$plugin_body .= "Done !";
	}
else
	{
	$sql = sed_sql_query("SELECT s.fs_id, s.fs_title, s.fs_category FROM $db_forum_sections AS s 
		LEFT JOIN $db_forum_structure AS n ON n.fn_code=s.fs_category
    	ORDER by fn_path ASC, fs_order ASC");
	
	$select_source = "<select name=\"sourceid\">";
	$select_target = "<select name=\"targetid\">";

	while ($row = sed_sql_fetchassoc($sql))
		{
		$select_option = "<option value=\"".$row['fs_id']."\">".sed_build_forums($row['fs_id'], $row['fs_title'], $row['fs_category'])."</option>";
		$select_source .= $select_option;
		$select_target .= $select_option;
		}
	$select_source .= "</select>";
	$select_target .= "</select>";

	$plugin_body .= "<form id=\"massmovetopics\" action=\"".sed_url("admin", "m=tools&p=massmovetopics&a=move")."\" method=\"post\">";
	$plugin_body .= "<table class=\"cells striped\"><tr><td style=\"width:33%;\">";
	$plugin_body .= "Move all the topics and posts from the section :</td><td>".$select_source."</td></tr><tr><td>";
	$plugin_body .= "... to the section :</td><td>".$select_target."</td></tr></table>";
	$plugin_body .= "<input type=\"submit\" class=\"submit btn\" value=\"".$L['Move']."\" />";
	$plugin_body .= "</form>";
	}

?>
