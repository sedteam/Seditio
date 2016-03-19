<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=upgrade_173_175.php
Version=177
Updated=2013-jun-25
Type=Core.upgrade
Author=Neocrome & Seditio Team
Description=Database upgrade
[END_SED]
==================== */

if ( !defined('SED_CODE') || !defined('SED_ADMIN') ) { die('Wrong URL.'); }

$adminmain .= "Clearing the internal SQL cache...<br />";
$sql = sed_sql_query("TRUNCATE TABLE ".$cfg['sqldbprefix']."cache");

$adminmain .= "Changing the SQL version number to 177...<br />";

$sqlqr = "CREATE TABLE ".$cfg['sqldbprefix']."extra_fields (
  field_location varchar(255) NOT NULL,
  field_name varchar(255) NOT NULL,
  field_type varchar(255) NOT NULL,
  field_html text NOT NULL,
  field_variants text NOT NULL,
  field_description text NOT NULL,
  KEY field_location (field_location),
  KEY field_name (field_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."extra_fields VALUES
('pages', 'extra1', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('pages', 'extra2', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('pages', 'extra3', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('pages', 'extra4', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('pages', 'extra5', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra1', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra2', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra3', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra4', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra5', 'input', '<input class=\"text\" type=\"text\" maxlength=\"255\" size=\"56\" />', '', ''),
('users', 'extra6', 'textarea', '<textarea cols=\"80\" rows=\"6\" ></textarea>', '', ''),
('users', 'extra7', 'textarea', '<textarea cols=\"80\" rows=\"6\" ></textarea>', '', ''),
('users', 'extra8', 'textarea', '<textarea cols=\"80\" rows=\"6\" ></textarea>', '', ''),
('users', 'extra9', 'textarea', '<textarea cols=\"80\" rows=\"6\" ></textarea>', '', '');";

$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."core VALUES ('', 'dic', 'Directories', '150', 1, 0);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 1, 'dic', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 2, 'dic', 'a', 1, 254, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 3, 'dic', 'a', 0, 255, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 4, 'dic', 'a', 3, 128, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 5, 'dic', 'a', 255, 255, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);  

$sqlqr = "INSERT INTO ".$cfg['sqldbprefix']."auth VALUES ('', 6, 'dic', 'a', 131, 0, 1);";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr); 

$sqlqr = "CREATE TABLE ".$cfg['sqldbprefix']."dic (
  dic_id mediumint(8) NOT NULL auto_increment,
  dic_title varchar(255) NOT NULL default '',
  dic_code varchar(255) NOT NULL default '',
  dic_type tinyint(10) default '0',
  PRIMARY KEY  (dic_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr); 

$sqlqr = "CREATE TABLE ".$cfg['sqldbprefix']."dic_items (
  ditem_id mediumint(8) NOT NULL auto_increment,
  ditem_dicid mediumint(8) NOT NULL default '0',
  ditem_title varchar(255) NOT NULL default '',
  ditem_code varchar(255) NOT NULL default '',
  ditem_defval tinyint(1) default '0',
  KEY ditem_dicid (ditem_dicid), 
  PRIMARY KEY  (ditem_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"; 

$adminmain .= "Adding the 'page_extra6' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_extra6 varchar(255) NOT NULL DEFAULT '' AFTER page_extra5";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_extra7' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_extra7 varchar(255) NOT NULL DEFAULT '' AFTER page_extra6";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_extra8' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_extra8 varchar(255) NOT NULL DEFAULT '' AFTER page_extra7";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_extra9' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_extra9 varchar(255) NOT NULL DEFAULT '' AFTER page_extra8";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_extra10' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_extra10 varchar(255) NOT NULL DEFAULT '' AFTER page_extra9";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_price' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_price varchar(11) NOT NULL DEFAULT '0' AFTER page_seo_keywords";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= "Adding the 'page_thumb' column to table pages...<br />";
$sqlqr = "ALTER TABLE ".$cfg['sqldbprefix']."pages ADD page_thumb varchar(255) NOT NULL DEFAULT '' AFTER page_price";
$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);

$adminmain .= sed_cc($sqlqr)."<br />";
$sql = sed_sql_query($sqlqr);             

$sql = sed_sql_query("UPDATE ".$cfg['sqldbprefix']."stats SET stat_value=177 WHERE stat_name='version'");
$upg_status = TRUE;

?>
