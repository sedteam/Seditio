<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=install.database.php
Version=180
Updated=2025-jan-25
Type=Core.install
Author=Seditio Team
Description=Database builder
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
  die('Wrong URL.');
}

$cfg['mysqlcollate'] = "utf8mb4_unicode_ci";
$cfg['mysqlcharset'] = "utf8mb4";
$cfg['mysqlengine'] = version_compare(sed_sql_version(), '5.6', '>=') ? "InnoDB" : "MyISAM";

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "auth (
  auth_id mediumint(8) NOT NULL auto_increment,
  auth_groupid int(11) NOT NULL default '0',
  auth_code varchar(190) NOT NULL default '',
  auth_option varchar(190) NOT NULL default '',
  auth_rights tinyint(1) unsigned NOT NULL default '0',
  auth_rights_lock tinyint(1) unsigned NOT NULL default '0',
  auth_setbyuserid int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (auth_id),
  KEY auth_groupid (auth_groupid),
  KEY auth_code (auth_code)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "banlist (
  banlist_id int(11) NOT NULL auto_increment,
  banlist_ip varchar(45) NOT NULL default '',
  banlist_email varchar(64) NOT NULL default '',
  banlist_reason varchar(64) NOT NULL default '',
  banlist_expire int(11) default '0',
  PRIMARY KEY (banlist_id),
  KEY banlist_ip (banlist_ip)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "cache (
  c_name varchar(16) NOT NULL default '',
  c_expire int(11) NOT NULL default '0',
  c_auto tinyint(1) NOT NULL default '1',
  c_value text,
  PRIMARY KEY (c_name)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "com (
  com_id int(11) NOT NULL auto_increment,
  com_code varchar(16) NOT NULL default '',
  com_author varchar(24) NOT NULL default '',
  com_authorid int(11) default NULL,
  com_authorip varchar(45) NOT NULL default '',
  com_text text NOT NULL,
  com_text_ishtml tinyint(1) DEFAULT '1',
  com_date int(11) NOT NULL default '0',
  com_count int(11) NOT NULL default '0',
  com_rating tinyint(1) DEFAULT '0',
  com_isspecial tinyint(1) NOT NULL default '0',
  PRIMARY KEY (com_id),
  KEY com_code (com_code)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "config (
  config_id mediumint(8) NOT NULL auto_increment,
  config_owner varchar(24) NOT NULL default 'core',
  config_cat varchar(24) NOT NULL default '',
  config_order char(2) NOT NULL default '00',
  config_name varchar(32) NOT NULL default '',
  config_type tinyint(2) NOT NULL default '0',
  config_value text NOT NULL,
  config_default varchar(255) NOT NULL default '',
  config_text varchar(255) NOT NULL default '',
  config_variants varchar(255) NOT NULL default '',
  PRIMARY KEY (config_id),
  UNIQUE INDEX unique_config_owner_cat_name (config_owner, config_cat, config_name),
  INDEX idx_config_cat_name (config_cat, config_name)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "core (
  ct_id mediumint(8) NOT NULL auto_increment,
  ct_code varchar(24) NOT NULL default '',
  ct_title varchar(64) NOT NULL default '',
  ct_version varchar(16) NOT NULL default '',
  ct_state tinyint(1) unsigned NOT NULL default '1',
  ct_lock tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (ct_id),
  KEY ct_code (ct_code)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "dic (
  dic_id mediumint(8) NOT NULL auto_increment,
  dic_title varchar(255) NOT NULL default '',
  dic_code varchar(190) NOT NULL default '',
  dic_type tinyint(1) default '0',
  dic_values text NOT NULL,
  dic_parent mediumint(8) NOT NULL default '0',
  dic_mera varchar(16) NOT NULL default '',
  dic_form_title varchar(255) NOT NULL default '', 
  dic_form_desc varchar(255) NOT NULL default '',
  dic_form_size smallint(5) NOT NULL default '0',
  dic_form_maxsize smallint(5) NOT NULL default '0',
  dic_form_cols smallint(5) NOT NULL default '0',
  dic_form_rows smallint(5) NOT NULL default '0',
  dic_form_wysiwyg varchar(20) NOT NULL default 'noeditor',
  dic_extra_location varchar(40) NOT NULL default '',
  dic_extra_type varchar(20) NOT NULL default '',
  dic_extra_size smallint(5) NOT NULL default '0',
  KEY dic_code (dic_code), 
  KEY dic_parent (dic_parent),
  PRIMARY KEY (dic_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "dic_items (
  ditem_id mediumint(8) NOT NULL auto_increment,
  ditem_dicid mediumint(8) NOT NULL default '0',
  ditem_title varchar(255) NOT NULL default '',
  ditem_code varchar(255) NOT NULL default '',
  ditem_children mediumint(8) NOT NULL default '0',  
  ditem_defval tinyint(1) default '0',
  KEY ditem_dicid (ditem_dicid),
  KEY ditem_children (ditem_children), 
  PRIMARY KEY  (ditem_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "forum_posts (
  fp_id mediumint(8) unsigned NOT NULL auto_increment,
  fp_topicid mediumint(8) NOT NULL default '0',
  fp_sectionid smallint(5) NOT NULL default '0',
  fp_posterid int(11) NOT NULL default '-1',
  fp_postername varchar(24) NOT NULL default '',
  fp_creation int(11) NOT NULL default '0',
  fp_updated int(11) NOT NULL default '0',
  fp_updater varchar(24) NOT NULL default '0',
  fp_text text NOT NULL,
  fp_text_ishtml tinyint(1) DEFAULT '1',
  fp_posterip varchar(45) NOT NULL default '',
  fp_rating tinyint(1) DEFAULT '0',
  PRIMARY KEY (fp_id),
  UNIQUE KEY fp_topicid (fp_topicid,fp_id),
  KEY fp_updated (fp_creation)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "forum_sections (
  fs_id smallint(5) unsigned NOT NULL auto_increment,
  fs_state tinyint(1) unsigned NOT NULL default '0',
  fs_order smallint(5) unsigned NOT NULL default '0',
  fs_title varchar(128) NOT NULL default '',
  fs_category varchar(64) NOT NULL default '',
  fs_parentcat smallint(5) unsigned NOT NULL default '0',
  fs_desc varchar(255) NOT NULL default '',
  fs_icon varchar(255) NOT NULL default '',
  fs_lt_id int(11) NOT NULL default '0',
  fs_lt_title varchar(64) NOT NULL default '',
  fs_lt_date int(11) NOT NULL default '0',
  fs_lt_posterid int(11) NOT NULL default '-1',
  fs_lt_postername varchar(24) NOT NULL default '',
  fs_autoprune int(11) NOT NULL default '0',
  fs_allowusertext tinyint(1) NOT NULL default '1',
  fs_allowbbcodes tinyint(1) NOT NULL default '1',
  fs_allowsmilies tinyint(1) NOT NULL default '1',
  fs_allowprvtopics tinyint(1) NOT NULL default '0',
  fs_countposts tinyint(1) NOT NULL default '1',
  fs_topiccount mediumint(8) NOT NULL default '0',
  fs_topiccount_pruned int(11) default '0',
  fs_postcount mediumint(8) NOT NULL default '0',
  fs_postcount_pruned int(11) default '0',
  fs_viewcount mediumint(8) NOT NULL default '0',
  PRIMARY KEY (fs_id),
  KEY fs_order (fs_order)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "forum_structure (
  fn_id mediumint(8) NOT NULL auto_increment,
  fn_path varchar(16) NOT NULL default '',
  fn_code varchar(16) NOT NULL default '',
  fn_tpl varchar(64) NOT NULL default '',
  fn_title varchar(32) NOT NULL default '',
  fn_desc varchar(255) NOT NULL default '',
  fn_icon varchar(128) NOT NULL default '',
  fn_defstate tinyint(1) NOT NULL default '1',
  PRIMARY KEY (fn_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "forum_topics (
  ft_id mediumint(8) unsigned NOT NULL auto_increment,
  ft_mode tinyint(1) unsigned NOT NULL default '0',
  ft_state tinyint(1) unsigned NOT NULL default '0',
  ft_sticky tinyint(1) unsigned NOT NULL default '0',
  ft_tag varchar(16) NOT NULL default '',
  ft_sectionid mediumint(8) NOT NULL default '0',
  ft_title varchar(128) NOT NULL default '',
  ft_desc varchar(255) NOT NULL default '',
  ft_creationdate int(11) NOT NULL default '0',
  ft_updated int(11) NOT NULL default '0',
  ft_postcount mediumint(8) NOT NULL default '0',
  ft_viewcount mediumint(8) NOT NULL default '0',
  ft_lastposterid int(11) NOT NULL default '-1',
  ft_lastpostername varchar(24) NOT NULL default '',
  ft_firstposterid int(11) NOT NULL default '-1',
  ft_firstpostername varchar(24) NOT NULL default '',
  ft_poll int(11) default '0',
  ft_movedto int(11) default '0',
  PRIMARY KEY  (ft_id),
  KEY ft_updated (ft_updated),
  KEY ft_mode (ft_mode),
  KEY ft_state (ft_state),
  KEY ft_sticky (ft_sticky),
  KEY ft_sectionid (ft_sectionid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "groups (
  grp_id int(11) NOT NULL auto_increment,
  grp_alias varchar(24) NOT NULL default '',
  grp_level tinyint(2) NOT NULL default '1',
  grp_disabled tinyint(1) NOT NULL default '0',
  grp_hidden tinyint(1) NOT NULL default '0',
  grp_title varchar(64) NOT NULL default '',
  grp_desc varchar(255) NOT NULL default '',
  grp_icon varchar(128) NOT NULL default '',
  grp_color varchar(24) NOT NULL default 'inherit',  
  grp_pfs_maxfile int(11) NOT NULL default '0',
  grp_pfs_maxtotal int(11) NOT NULL default '0',
  grp_ownerid int(11) NOT NULL default '0',
  PRIMARY KEY (grp_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "groups_users (
  gru_userid int(11) NOT NULL default '0',
  gru_groupid int(11) NOT NULL default '0',
  gru_state tinyint(1) NOT NULL default '0',
  gru_extra1 varchar(255) NOT NULL default '',
  gru_extra2 varchar(255) NOT NULL default '',
  KEY gru_userid (gru_userid),
  UNIQUE KEY gru_groupid (gru_groupid,gru_userid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "logger (
  log_id mediumint(11) NOT NULL auto_increment,
  log_date int(11) NOT NULL default '0',
  log_ip varchar(45) NOT NULL default '',
  log_name varchar(24) NOT NULL default '',
  log_group varchar(4) NOT NULL default 'def',
  log_text varchar(255) NOT NULL default '',
  PRIMARY KEY (log_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "online (
  online_id int(11) NOT NULL auto_increment,
  online_ip varchar(45) NOT NULL default '',
  online_name varchar(24) NOT NULL default '',
  online_lastseen int(11) NOT NULL default '0',
  online_location varchar(32) NOT NULL default '',
  online_subloc varchar(255) NOT NULL default '',
  online_userid int(11) NOT NULL default '0',
  online_shield int(11) NOT NULL default '0',
  online_action varchar(32) NOT NULL default '',
  online_hammer tinyint(1) NOT NULL default '0',
  PRIMARY KEY (online_id),
  KEY online_lastseen (online_lastseen)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "pages (
  page_id int(11) unsigned NOT NULL auto_increment,
  page_state tinyint(1) unsigned NOT NULL default '0',
  page_cat varchar(190) default NULL,
  page_key varchar(16) default NULL,
  page_title varchar(255) default NULL,
  page_desc varchar(255) default NULL,
  page_text text,
  page_text2 text,	  
  page_author varchar(24) default NULL,
  page_ownerid int(11) NOT NULL default '0',
  page_date int(11) NOT NULL default '0',
  page_begin int(11) NOT NULL default '0',
  page_expire int(11) NOT NULL default '0',
  page_file tinyint(1) default NULL,
  page_url varchar(255) default NULL,
  page_size varchar(16) default NULL,
  page_count mediumint(8) unsigned default '0',
  page_allowcomments tinyint(1) NOT NULL default '1',
  page_allowratings tinyint(1) NOT NULL default '1',
  page_rating decimal(5,2) NOT NULL default '0.00',
  page_comcount mediumint(8) unsigned default '0',
  page_filecount mediumint(8) unsigned default '0',
  page_alias varchar(255) NOT NULL default '',
  page_seo_title varchar(255) default NULL,
  page_seo_desc varchar(255) default NULL,
  page_seo_keywords varchar(255) default NULL, 
  page_seo_h1 varchar(255) default NULL,
  page_seo_index tinyint(1) unsigned NOT NULL default '1',
  page_seo_follow tinyint(1) unsigned NOT NULL default '1', 
  page_thumb varchar(255) NOT NULL default '',
  PRIMARY KEY (page_id),
  KEY page_cat (page_cat)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "pfs (
  pfs_id int(11) NOT NULL auto_increment,
  pfs_userid int(11) NOT NULL default '0',
  pfs_date int(11) NOT NULL default '0',
  pfs_file varchar(255) NOT NULL default '',
  pfs_extension varchar(8) NOT NULL default '',
  pfs_folderid int(11) NOT NULL default '0',
  pfs_title varchar(255) NOT NULL default '',  
  pfs_desc text NOT NULL,
  pfs_size int(11) NOT NULL default '0',
  pfs_count int(11) NOT NULL default '0',
  PRIMARY KEY (pfs_id),
  KEY pfs_userid (pfs_userid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "pfs_folders (
  pff_id int(11) NOT NULL auto_increment,
  pff_userid int(11) NOT NULL default '0',
  pff_date int(11) NOT NULL default '0',
  pff_updated int(11) NOT NULL default '0',
  pff_title varchar(255) NOT NULL default '',
  pff_desc text NOT NULL, 
  pff_type tinyint(1) NOT NULL default '0',
  pff_sample int(11) NOT NULL default '0',
  pff_count int(11) NOT NULL default '0',
  PRIMARY KEY (pff_id),
  KEY pff_userid (pff_userid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "plugins (
  pl_id mediumint(8) NOT NULL auto_increment,
  pl_hook varchar(64) NOT NULL default '',
  pl_code varchar(24) NOT NULL default '',
  pl_part varchar(24) NOT NULL default '',
  pl_title varchar(255) NOT NULL default '',
  pl_file varchar(255) NOT NULL default '',
  pl_order tinyint(2) unsigned NOT NULL default '10',
  pl_active tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (pl_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "pm (
  pm_id int(11) unsigned NOT NULL auto_increment,
  pm_state tinyint(2) NOT NULL default '0',
  pm_date int(11) NOT NULL default '0',
  pm_fromuserid int(11) NOT NULL default '0',
  pm_fromuser varchar(24) NOT NULL default '0',
  pm_touserid int(11) NOT NULL default '0',
  pm_title varchar(64) NOT NULL default '0',
  pm_text text NOT NULL,
  PRIMARY KEY (pm_id),
  KEY pm_fromuserid (pm_fromuserid),
  KEY pm_touserid (pm_touserid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "polls (
  poll_id mediumint(8) NOT NULL auto_increment,
  poll_type tinyint(1) default '0',
  poll_state tinyint(1) NOT NULL default '0',
  poll_creationdate int(11) NOT NULL default '0',
  poll_text varchar(255) NOT NULL default '',
  poll_ownerid int(11) NOT NULL default '0',
  poll_code varchar(16) NOT NULL default '',
  PRIMARY KEY (poll_id),
  KEY poll_creationdate (poll_creationdate)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "polls_options (
  po_id mediumint(8) unsigned NOT NULL auto_increment,
  po_pollid mediumint(8) unsigned NOT NULL default '0',
  po_text varchar(128) NOT NULL default '',
  po_count mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY (po_id),
  KEY po_pollid (po_pollid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "polls_voters (
  pv_id mediumint(8) unsigned NOT NULL auto_increment,
  pv_pollid mediumint(8) NOT NULL default '0',
  pv_userid mediumint(8) NOT NULL default '0',
  pv_userip varchar(45) NOT NULL default '',
  PRIMARY KEY (pv_id),
  KEY pv_pollid (pv_pollid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "rated (
  rated_id int(11) unsigned NOT NULL auto_increment,
  rated_code varchar(16) default NULL,
  rated_userid int(11) default NULL,
  rated_value tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (rated_id),
  KEY rated_code (rated_code)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "ratings (
  rating_id int(11) NOT NULL auto_increment,
  rating_code varchar(16) NOT NULL default '',
  rating_state tinyint(2) NOT NULL default '0',
  rating_average decimal(5,2) NOT NULL default '0.00',
  rating_creationdate int(11) NOT NULL default '0',
  rating_text varchar(128) NOT NULL default '',
  PRIMARY KEY (rating_id),
  KEY rating_code (rating_code)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "referers (
  ref_id int(11) NOT NULL auto_increment,
  ref_url varchar(255) NOT NULL default '',
  ref_date int(11) unsigned NOT NULL default '0',
  ref_count int(11) NOT NULL default '0',
  PRIMARY KEY (ref_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "smilies (
  smilie_id int(11) NOT NULL auto_increment,
  smilie_code varchar(16) NOT NULL default '',
  smilie_image varchar(128) NOT NULL default '',
  smilie_text varchar(32) NOT NULL default '',
  smilie_order smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY (smilie_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "stats (
  stat_name varchar(32) NOT NULL default '',
  stat_value int(11) NOT NULL default '0',
  PRIMARY KEY (stat_name)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "structure (
  structure_id mediumint(8) NOT NULL auto_increment,
  structure_code varchar(190) NOT NULL default '',
  structure_path varchar(190) NOT NULL default '',
  structure_tpl varchar(64) NOT NULL default '',
  structure_title varchar(100) NOT NULL default '',
  structure_desc varchar(255) NOT NULL default '',
  structure_text text,
  structure_icon varchar(128) NOT NULL default '',
  structure_group tinyint(1) NOT NULL default '0',
  structure_order varchar(16) NOT NULL default 'date.desc',
  structure_allowcomments tinyint(1) NOT NULL default '1',
  structure_allowratings tinyint(1) NOT NULL default '1',
  structure_thumb varchar(255) NOT NULL default '',
  structure_seo_title varchar(255) default NULL,
  structure_seo_desc varchar(255) default NULL,
  structure_seo_keywords varchar(255) default NULL, 
  structure_seo_h1 varchar(255) default NULL, 
  structure_seo_index tinyint(1) unsigned NOT NULL default '1',
  structure_seo_follow tinyint(1) unsigned NOT NULL default '1', 
  PRIMARY KEY (structure_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "trash (
  tr_id int(11) NOT NULL auto_increment,
  tr_date int(11) unsigned NOT NULL default '0',
  tr_type varchar(24) NOT NULL default '',
  tr_title varchar(128) NOT NULL default '',
  tr_itemid varchar(24) NOT NULL default '',
  tr_trashedby int(11) NOT NULL default '0',
  tr_datas mediumblob,
  PRIMARY KEY (tr_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "users (
  user_id int(11) unsigned NOT NULL auto_increment,
  user_banexpire int(11) default '0',
  user_name varchar(100) NOT NULL default '',
  user_firstname varchar(100) NOT NULL default '',   
  user_lastname varchar(100) NOT NULL default '',  
  user_password varchar(32) NOT NULL default '',
  user_salt varchar(16) NOT NULL default '',
  user_secret varchar(32) NOT NULL default '',
  user_passtype tinyint(1) DEFAULT '1',
  user_maingrp int(11) unsigned NOT NULL default '4',
  user_country char(2) NOT NULL default '',
  user_text text NOT NULL,
  user_avatar varchar(255) NOT NULL default '',
  user_photo varchar(255) NOT NULL default '',
  user_signature varchar(255) NOT NULL default '',
  user_occupation varchar(64) NOT NULL default '',
  user_location varchar(64) NOT NULL default '',
  user_timezone decimal(2,0) NOT NULL default '0',
  user_birthdate int(11) NOT NULL default '0',
  user_gender char(1) NOT NULL default 'U',
  user_skype varchar(64) NOT NULL default '',
  user_website varchar(128) NOT NULL default '',
  user_email varchar(64) NOT NULL default '',
  user_hideemail tinyint(1) unsigned NOT NULL default '1',
  user_pmnotify tinyint(1) unsigned NOT NULL default '0',
  user_newpm tinyint(1) unsigned NOT NULL default '0',
  user_skin varchar(16) NOT NULL default '',
  user_lang varchar(16) NOT NULL default '',
  user_regdate int(11) NOT NULL default '0',
  user_lastlog int(11) NOT NULL default '0',
  user_lastvisit int(11) NOT NULL default '0',
  user_lastip varchar(45) NOT NULL default '',
  user_logcount int(11) unsigned NOT NULL default '0',
  user_postcount int(11) default '0',
  user_sid char(32) NOT NULL default '',
  user_lostpass char(32) NOT NULL default '',
  user_auth text,
  user_token varchar(255) NOT NULL default '',
  user_oauth_provider varchar(50) NOT NULL default '',
  user_oauth_uid text NOT NULL,  
  PRIMARY KEY (user_id)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("CREATE TABLE " . $cfg['mysqldb'] . "menu (
  menu_id int(11) unsigned NOT NULL auto_increment,
  menu_pid int(11) NOT NULL DEFAULT '0',
  menu_title varchar(255) NOT NULL DEFAULT '',
  menu_url varchar(255) NOT NULL DEFAULT '',
  menu_position int(11) NOT NULL DEFAULT '0',
  menu_visible tinyint(1) NOT NULL DEFAULT '1',
  menu_target varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (menu_id),
  KEY menu_pid (menu_pid)
) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "structure VALUES (1, 'articles', '1', '', 'Articles', '', '', '', 1 ,'title.asc', 1, 1, '', '', '', '', '', '', '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "structure VALUES (2, 'sample1', '1.1', '', 'Sample category 1', 'Description for the Sample category 1', '', '',  0 ,'title.asc', 1, 1, '', '', '', '', '', '', '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "structure VALUES (3, 'sample2', '1.2', '', 'Sample category 2', 'Description for the Sample category 2', '', '',  0 ,'title.asc', 1, 1, '', '', '', '', '', '', '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "structure VALUES (4, 'news', '2', '', 'News', '', '', '', 0 ,'date.desc', 1, 1, '', '', '', '', '', '', '');");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "forum_sections VALUES ('1', '0', '100', 'General discussion', 'pub', 0, 'General chat.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "forum_sections VALUES ('2', '0', '101', 'Off-topic', 'pub', 0, 'Various and off-topic.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "forum_structure VALUES ('1', '1', 'pub', '', 'Public', '', '', 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (1, ':D', 'system/smilies/icon_biggrin.gif', 'Mister grin', 5);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (2, ':blush', 'system/smilies/icon_blush.gif', 'Blush', 45);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (3, ':con', 'system/smilies/icon_confused.gif', 'Confused', 42);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (4, ':)', 'system/smilies/icon_smile.gif', 'Smile', 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (5, ':cry', 'system/smilies/icon_cry.gif', 'Cry', 44);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (6, ':dontgetit', 'system/smilies/icon_dontgetit.gif', 'Don\'t get it', 41);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (7, ':dozingoff', 'system/smilies/icon_dozingoff.gif', 'Dozing off', 40);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (8, ':(', 'system/smilies/icon_sad.gif', 'Sad', 50);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (9, ':((', 'system/smilies/icon_mad.gif', 'Mad', 46);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (10, ':|', 'system/smilies/icon_neutral.gif', 'Neutral', 43);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (11, ':no', 'system/smilies/icon_no.gif', 'No', 12);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (12, ':O_o', 'system/smilies/icon_o_o.gif', 'Suspicious', 7);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (13, ':p', 'system/smilies/icon_razz.gif', 'Razz', 6);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (14, ':love', 'system/smilies/icon_love.gif', 'Love', 10);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (15, ':satisfied', 'system/smilies/icon_satisfied.gif', 'Satisfied', 2);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (16, '8)', 'system/smilies/icon_cool.gif', 'Cool', 4);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (17, ':wink', 'system/smilies/icon_wink.gif', 'Wink', 3);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "smilies VALUES (18, ':yes', 'system/smilies/icon_yes.gif', 'Yes', 11);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('totalpages', '0');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('totalmailsent', '0');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('totalmailpmnot', '0');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('totalpms', '0');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('totalantihammer', '0');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "stats (stat_name, stat_value) VALUES ('version', '" . $cfg['version'] . "');");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (1, 'admin', 'Administration panel', '100', 1, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (2, 'comments', 'Comments', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (3, 'forums', 'Forums', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (4, 'index', 'Home page', '100', 1, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (5, 'message', 'Messages', '100', 1, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (6, 'page', 'Pages', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (7, 'pfs', 'Personal File Space', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (8, 'plug', 'Plugins', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (9, 'pm', 'Private messages', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (10, 'polls', 'Polls', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (11, 'ratings', 'Ratings', '100', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (12, 'users', 'Users', '100', 1, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (13, 'trash', 'Trash Can', '110', 1, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (14, 'gallery', 'Gallery', '150', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (15, 'dic', 'Directories', '177', 1, 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "core VALUES (16, 'menu', 'Menu manager', '178', 1, 0);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (1, 'guests', 0, 0, 0, 'Guests', '', '', 'darkmagenta', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (2, 'inactive', 1, 0, 0, 'Inactive', '', '', 'white', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (3, 'banned', 1, 0, 0, 'Banned', '', '', 'gray', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (4, 'members', 1, 0, 0, 'Members', '', '', 'black', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (5, 'administrators', 99, 0, 0, 'Administrators', '', '', 'red', 10240, 1048576, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "groups VALUES (6, 'moderators', 50, 0, 0, 'Moderators', '', '', 'green', 5120, 512000, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (1, 1, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (2, 2, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (3, 3, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (4, 4, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (5, 5, 'admin', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (6, 6, 'admin', 'a', 1, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (7, 1, 'comments', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (8, 2, 'comments', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (9, 3, 'comments', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (10, 4, 'comments', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (11, 5, 'comments', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (12, 6, 'comments', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (13, 1, 'index', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (14, 2, 'index', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (15, 3, 'index', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (16, 4, 'index', 'a', 1, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (17, 5, 'index', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (18, 6, 'index', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (19, 1, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (20, 2, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (21, 3, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (22, 4, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (23, 5, 'message', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (24, 6, 'message', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (25, 1, 'pfs', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (26, 2, 'pfs', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (27, 3, 'pfs', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (28, 4, 'pfs', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (29, 5, 'pfs', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (30, 6, 'pfs', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (31, 1, 'pm', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (32, 2, 'pm', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (33, 3, 'pm', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (34, 4, 'pm', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (35, 5, 'pm', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (36, 6, 'pm', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (37, 1, 'polls', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (38, 2, 'polls', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (39, 3, 'polls', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (40, 4, 'polls', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (41, 5, 'polls', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (42, 6, 'polls', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (43, 1, 'ratings', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (44, 2, 'ratings', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (45, 3, 'ratings', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (46, 4, 'ratings', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (47, 5, 'ratings', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (48, 6, 'ratings', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (49, 1, 'users', 'a', 0, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (50, 2, 'users', 'a', 0, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (51, 3, 'users', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (52, 4, 'users', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (53, 5, 'users', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (54, 6, 'users', 'a', 3, 128, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (55, 1, 'forums', '1', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (56, 2, 'forums', '1', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (57, 3, 'forums', '1', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (58, 4, 'forums', '1', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (59, 5, 'forums', '1', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (60, 6, 'forums', '1', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (61, 1, 'forums', '2', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (62, 2, 'forums', '2', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (63, 3, 'forums', '2', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (64, 4, 'forums', '2', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (65, 5, 'forums', '2', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (66, 6, 'forums', '2', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (67, 1, 'page', 'articles', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (68, 2, 'page', 'articles', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (69, 3, 'page', 'articles', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (70, 4, 'page', 'articles', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (71, 5, 'page', 'articles', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (72, 6, 'page', 'articles', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (73, 1, 'page', 'sample1', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (74, 2, 'page', 'sample1', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (75, 3, 'page', 'sample1', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (76, 4, 'page', 'sample1', 3, 252, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (77, 5, 'page', 'sample1', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (78, 6, 'page', 'sample1', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (79, 1, 'page', 'sample2', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (80, 2, 'page', 'sample2', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (81, 3, 'page', 'sample2', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (82, 4, 'page', 'sample2', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (83, 5, 'page', 'sample2', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (84, 6, 'page', 'sample2', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (85, 1, 'page', 'news', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (86, 2, 'page', 'news', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (87, 3, 'page', 'news', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (88, 4, 'page', 'news', 3, 252, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (89, 5, 'page', 'news', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (90, 6, 'page', 'news', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (91, 1, 'gallery', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (92, 2, 'gallery', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (93, 3, 'gallery', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (94, 4, 'gallery', 'a', 1, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (95, 5, 'gallery', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (96, 6, 'gallery', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (97, 1, 'dic', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (98, 2, 'dic', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (99, 3, 'dic', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (100, 4, 'dic', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (101, 5, 'dic', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (102, 6, 'dic', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (103, 1, 'menu', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (104, 2, 'menu', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (105, 3, 'menu', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (106, 4, 'menu', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (107, 5, 'menu', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (108, 6, 'menu', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (109, 1, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (110, 2, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (111, 3, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (112, 4, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (113, 5, 'log', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (114, 6, 'log', 'a', 0, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (115, 1, 'trash', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (116, 2, 'trash', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (117, 3, 'trash', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (118, 4, 'trash', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (119, 5, 'trash', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (120, 6, 'trash', 'a', 0, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (121, 1, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (122, 2, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (123, 3, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (124, 4, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (125, 5, 'manage', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "auth VALUES (126, 6, 'manage', 'a', 0, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(1, 0, 'Menu', '', 1, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(2, 1, 'Home', '/', 2, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(3, 1, 'Forums', 'forums/', 3, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(4, 1, 'Articles', 'articles/', 4, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(5, 1, 'Galleries', 'gallery/', 5, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(6, 1, 'Contact', 'plug/contact', 6, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(7, 4, 'Sample category 1', 'articles/sample1/', 1, 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "menu VALUES(8, 4, 'Sample category 2', 'articles/sample2/', 2, 1, '');");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "polls VALUES(1, 0, 0, 1654936152, 'Looking forward to a new version of Seditio?', 1, '');");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "polls_options VALUES(1, 1, 'Yes', 0);");
$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "polls_options VALUES(2, 1, 'No', 0);");

$sql = sed_sql_query("INSERT INTO " . $cfg['mysqldb'] . "pages VALUES
(1, 0, 'news', '', 'Welcome !', '...', 'Congratulations, your website is up and running !<br />\r\n<br />\r\nThe next step is to go in the <a href=\"/admin/\">Administration panel</a>, tab <a href=\"admin/config\">Configuration</a>, and there tweak the settings for the system.<br />\r\nYou''ll find more instructions and tutorials in the <a href=\"https://seditio.org/doc/\">Documentation page for Seditio at Seditio.org</a>, and technical support in our <a href=\"https://seditio.org/forums/\">discussion forums</a>.', '', '', 1, 1263945600, 1263942000, 1861959600, 0, '', '', 38, 1, 1, 0.00, 0, 0, '', '', '', '', '', 1, 1, '');");
