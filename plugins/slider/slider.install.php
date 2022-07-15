<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://www.seditio.org

[BEGIN_SED]
File=plugins/slider/slider.install.php
Version=179
Updated=2020-feb-26
Type=Plugin
Author=Amro
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $db_dic, $db_dic_items, $db_pages;

$dtitle = "Slider";
$dcode = "slider";
$dparent = 0;
$dtype = 2;
$dvalues = "";
$dmera = "";
$dformtitle = "";	 
$dformdesc = "";	 
$dformsize = "";	 
$dformmaxsize = "";	 
$dformcols = "";	 
$dformrows = "";

$sql = sed_sql_query("INSERT into $db_dic 
	(dic_title, 
	dic_code, 
	dic_type, 
	dic_values, 
	dic_mera,
	dic_form_title, 
	dic_form_desc,
	dic_form_size,
	dic_form_maxsize,
	dic_form_cols,
	dic_form_rows             
	) 
	VALUES 
	('".sed_sql_prep($dtitle)."', 
	'".sed_sql_prep($dcode)."', 
	'".(int)$dtype."', 
	'".sed_sql_prep($dvalues)."', 
	'".sed_sql_prep($dmera)."',
	'".sed_sql_prep($dformtitle)."', 
	'".sed_sql_prep($dformdesc)."',
	'".sed_sql_prep($dformsize)."',
	'".sed_sql_prep($dformmaxsize)."',
	'".sed_sql_prep($dformcols)."',
	'".sed_sql_prep($dformrows)."')");
	
$did = sed_sql_insertid();
		
$sql = sed_sql_query("INSERT into $db_dic_items (ditem_dicid, ditem_title, ditem_code, ditem_children, ditem_defval) 
		VALUES (".(int)$did.", 'Yes', '1', '0', '0')");
$sql = sed_sql_query("INSERT into $db_dic_items (ditem_dicid, ditem_title, ditem_code, ditem_children, ditem_defval) 
		VALUES (".(int)$did.", 'No', '0', '0', '1')");	

sed_log("Added new dic & terms #".$did,'adm');

sed_extrafield_add("pages", "slider", "tinyint", "2");

sed_cache_clear('sed_dic');

?>