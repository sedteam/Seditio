<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/setup.database.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=Database structure and import logic for setup installer
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
  die('Wrong URL.');
}

$sqldbprefix = isset($sqldbprefix) ? $sqldbprefix : (isset($cfg['sqldbprefix']) ? $cfg['sqldbprefix'] : 'sed_');
$cfg['mysqlcollate'] = "utf8mb4_unicode_ci";
$cfg['mysqlcharset'] = "utf8mb4";
$cfg['mysqlengine'] = "InnoDB";

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "auth (
  auth_id mediumint(8) NOT NULL auto_increment,
  auth_groupid int(11) NOT NULL DEFAULT '0',
  auth_code varchar(190) NOT NULL DEFAULT '',
  auth_option varchar(190) NOT NULL DEFAULT '',
  auth_rights tinyint(1) unsigned NOT NULL DEFAULT '0',
  auth_rights_lock tinyint(1) unsigned NOT NULL DEFAULT '0',
  auth_setbyuserid int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (auth_id),
  KEY auth_groupid (auth_groupid),
  KEY auth_code (auth_code)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "banlist (
  banlist_id int(11) NOT NULL auto_increment,
  banlist_ip varchar(45) NOT NULL DEFAULT '',
  banlist_email varchar(64) NOT NULL DEFAULT '',
  banlist_reason varchar(64) NOT NULL DEFAULT '',
  banlist_expire int(11) DEFAULT '0',
  PRIMARY KEY (banlist_id),
  KEY banlist_ip (banlist_ip)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "cache (
  c_name varchar(16) NOT NULL DEFAULT '',
  c_expire int(11) NOT NULL DEFAULT '0',
  c_auto tinyint(1) NOT NULL DEFAULT '1',
  c_value mediumtext,
  PRIMARY KEY (c_name)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "config (
  config_id mediumint(8) NOT NULL auto_increment,
  config_owner varchar(24) NOT NULL DEFAULT 'core',
  config_cat varchar(24) NOT NULL DEFAULT '',
  config_order char(2) NOT NULL DEFAULT '00',
  config_name varchar(32) NOT NULL DEFAULT '',
  config_type tinyint(2) NOT NULL DEFAULT '0',
  config_value text NOT NULL,
  config_default varchar(255) NOT NULL DEFAULT '',
  config_text varchar(255) NOT NULL DEFAULT '',
  config_variants varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (config_id),
  UNIQUE INDEX unique_config_owner_cat_name (config_owner, config_cat, config_name),
  INDEX idx_config_cat_name (config_cat, config_name),
  INDEX idx_config_load (config_owner, config_cat)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "core (
  ct_id mediumint(8) NOT NULL auto_increment,
  ct_code varchar(24) NOT NULL DEFAULT '',
  ct_title varchar(64) NOT NULL DEFAULT '',
  ct_version varchar(16) NOT NULL DEFAULT '',
  ct_state tinyint(1) unsigned NOT NULL DEFAULT '1',
  ct_lock tinyint(1) unsigned NOT NULL DEFAULT '0',
  ct_path varchar(255) NOT NULL DEFAULT '',
  ct_admin tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (ct_id),
  KEY ct_code (ct_code)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "dic (
  dic_id mediumint(8) NOT NULL auto_increment,
  dic_title varchar(255) NOT NULL DEFAULT '',
  dic_code varchar(190) NOT NULL DEFAULT '',
  dic_type tinyint(1) DEFAULT '0',
  dic_values text NOT NULL,
  dic_parent mediumint(8) NOT NULL DEFAULT '0',
  dic_mera varchar(16) NOT NULL DEFAULT '',
  dic_form_title varchar(255) NOT NULL DEFAULT '', 
  dic_form_desc varchar(255) NOT NULL DEFAULT '',
  dic_form_size smallint(5) NOT NULL DEFAULT '0',
  dic_form_maxsize smallint(5) NOT NULL DEFAULT '0',
  dic_form_cols smallint(5) NOT NULL DEFAULT '0',
  dic_form_rows smallint(5) NOT NULL DEFAULT '0',
  dic_form_wysiwyg varchar(20) NOT NULL DEFAULT 'noeditor',
  dic_extra_location varchar(40) NOT NULL DEFAULT '',
  dic_extra_type varchar(20) NOT NULL DEFAULT '',
  dic_extra_size smallint(5) NOT NULL DEFAULT '0',
  dic_extra_default VARCHAR(255) NOT NULL DEFAULT '',
  dic_extra_allownull TINYINT(1) NOT NULL DEFAULT '0',
  dic_extra_extra VARCHAR(255) NOT NULL DEFAULT '',
  KEY dic_code (dic_code), 
  KEY dic_parent (dic_parent),
  PRIMARY KEY (dic_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "dic_items (
  ditem_id mediumint(8) NOT NULL auto_increment,
  ditem_dicid mediumint(8) NOT NULL DEFAULT '0',
  ditem_title varchar(255) NOT NULL DEFAULT '',
  ditem_code varchar(255) NOT NULL DEFAULT '',
  ditem_children mediumint(8) NOT NULL DEFAULT '0',  
  ditem_defval tinyint(1) DEFAULT '0',
  KEY ditem_dicid (ditem_dicid),
  KEY ditem_children (ditem_children), 
  PRIMARY KEY  (ditem_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "groups (
  grp_id int(11) NOT NULL auto_increment,
  grp_alias varchar(24) NOT NULL DEFAULT '',
  grp_level tinyint(2) NOT NULL DEFAULT '1',
  grp_disabled tinyint(1) NOT NULL DEFAULT '0',
  grp_hidden tinyint(1) NOT NULL DEFAULT '0',
  grp_title varchar(64) NOT NULL DEFAULT '',
  grp_desc varchar(255) NOT NULL DEFAULT '',
  grp_icon varchar(128) NOT NULL DEFAULT '',
  grp_color varchar(24) NOT NULL DEFAULT 'inherit',  
  grp_pfs_maxfile int(11) NOT NULL DEFAULT '0',
  grp_pfs_maxtotal int(11) NOT NULL DEFAULT '0',
  grp_ownerid int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (grp_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "groups_users (
  gru_userid int(11) NOT NULL DEFAULT '0',
  gru_groupid int(11) NOT NULL DEFAULT '0',
  gru_state tinyint(1) NOT NULL DEFAULT '0',
  gru_extra1 varchar(255) NOT NULL DEFAULT '',
  gru_extra2 varchar(255) NOT NULL DEFAULT '',
  KEY gru_userid (gru_userid),
  UNIQUE KEY gru_groupid (gru_groupid,gru_userid)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "logger (
  log_id mediumint(11) NOT NULL auto_increment,
  log_date int(11) NOT NULL DEFAULT '0',
  log_ip varchar(45) NOT NULL DEFAULT '',
  log_name varchar(24) NOT NULL DEFAULT '',
  log_group varchar(4) NOT NULL DEFAULT 'def',
  log_text varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (log_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "online (
  online_id int(11) NOT NULL auto_increment,
  online_ip varchar(45) NOT NULL DEFAULT '',
  online_name varchar(24) NOT NULL DEFAULT '',
  online_lastseen int(11) NOT NULL DEFAULT '0',
  online_location varchar(32) NOT NULL DEFAULT '',
  online_subloc varchar(255) NOT NULL DEFAULT '',
  online_userid int(11) NOT NULL DEFAULT '0',
  online_shield int(11) NOT NULL DEFAULT '0',
  online_action varchar(32) NOT NULL DEFAULT '',
  online_hammer tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (online_id),
  KEY online_lastseen (online_lastseen)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "plugins (
  pl_id mediumint(8) NOT NULL auto_increment,
  pl_hook varchar(64) NOT NULL DEFAULT '',
  pl_code varchar(24) NOT NULL DEFAULT '',
  pl_part varchar(24) NOT NULL DEFAULT '',
  pl_title varchar(255) NOT NULL DEFAULT '',
  pl_version varchar(16) NOT NULL DEFAULT '0.0.0',
  pl_dependencies text,
  pl_file varchar(255) NOT NULL DEFAULT '',
  pl_order tinyint(2) unsigned NOT NULL DEFAULT '10',
  pl_active tinyint(1) unsigned NOT NULL DEFAULT '1',
  pl_lock tinyint(1) unsigned NOT NULL DEFAULT '0',
  pl_module tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (pl_id),
  KEY idx_type (pl_module)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "referers (
  ref_id int(11) NOT NULL auto_increment,
  ref_url varchar(255) NOT NULL DEFAULT '',
  ref_date int(11) unsigned NOT NULL DEFAULT '0',
  ref_count int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (ref_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "smilies (
  smilie_id int(11) NOT NULL auto_increment,
  smilie_code varchar(16) NOT NULL DEFAULT '',
  smilie_image varchar(128) NOT NULL DEFAULT '',
  smilie_text varchar(32) NOT NULL DEFAULT '',
  smilie_order smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (smilie_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "stats (
  stat_name varchar(32) NOT NULL DEFAULT '',
  stat_value int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (stat_name)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "users (
  user_id int(11) unsigned NOT NULL auto_increment,
  user_banexpire int(11) DEFAULT '0',
  user_name varchar(100) NOT NULL DEFAULT '',
  user_firstname varchar(100) NOT NULL DEFAULT '',   
  user_lastname varchar(100) NOT NULL DEFAULT '',  
  user_password varchar(32) NOT NULL DEFAULT '',
  user_salt varchar(16) NOT NULL DEFAULT '',
  user_secret varchar(32) NOT NULL DEFAULT '',
  user_passtype tinyint(1) DEFAULT '1',
  user_maingrp int(11) unsigned NOT NULL DEFAULT '4',
  user_country char(2) NOT NULL DEFAULT '',
  user_text text NOT NULL,
  user_avatar varchar(255) NOT NULL DEFAULT '',
  user_photo varchar(255) NOT NULL DEFAULT '',
  user_signature varchar(255) NOT NULL DEFAULT '',
  user_occupation varchar(64) NOT NULL DEFAULT '',
  user_location varchar(64) NOT NULL DEFAULT '',
  user_timezone decimal(2,0) NOT NULL DEFAULT '0',
  user_birthdate int(11) NOT NULL DEFAULT '0',
  user_gender char(1) NOT NULL DEFAULT 'U',
  user_skype varchar(64) NOT NULL DEFAULT '',
  user_website varchar(128) NOT NULL DEFAULT '',
  user_email varchar(64) NOT NULL DEFAULT '',
  user_hideemail tinyint(1) unsigned NOT NULL DEFAULT '1',
  user_pmnotify tinyint(1) unsigned NOT NULL DEFAULT '0',
  user_newpm tinyint(1) unsigned NOT NULL DEFAULT '0',
  user_skin varchar(16) NOT NULL DEFAULT '',
  user_lang varchar(16) NOT NULL DEFAULT '',
  user_regdate int(11) NOT NULL DEFAULT '0',
  user_lastlog int(11) NOT NULL DEFAULT '0',
  user_lastvisit int(11) NOT NULL DEFAULT '0',
  user_lastip varchar(45) NOT NULL DEFAULT '',
  user_logcount int(11) unsigned NOT NULL DEFAULT '0',
  user_postcount int(11) DEFAULT '0',
  user_sid char(32) NOT NULL DEFAULT '',
  user_lostpass char(32) NOT NULL DEFAULT '',
  user_auth text,
  user_token varchar(255) NOT NULL DEFAULT '',
  user_oauth_provider varchar(50) NOT NULL DEFAULT '',
  user_oauth_uid text NOT NULL,  
  PRIMARY KEY (user_id)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS " . $sqldbprefix . "menu (
  menu_id int(11) unsigned NOT NULL auto_increment,
  menu_pid int(11) NOT NULL DEFAULT '0',
  menu_title varchar(255) NOT NULL DEFAULT '',
  menu_url varchar(255) NOT NULL DEFAULT '',
  menu_position int(11) NOT NULL DEFAULT '0',
  menu_visible tinyint(1) NOT NULL DEFAULT '1',
  menu_target varchar(10) NOT NULL DEFAULT '',
  menu_cssclass varchar(255) NOT NULL DEFAULT '',
  menu_cat varchar(64) NOT NULL DEFAULT '',
  menu_cat_subcats tinyint(1) NOT NULL DEFAULT '0',
  menu_cat_pages tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (menu_id),
  KEY menu_pid (menu_pid)
) ENGINE={$cfg['mysqlengine']} DEFAULT CHARSET={$cfg['mysqlcharset']} COLLATE={$cfg['mysqlcollate']};");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (1, ':D', 'system/smilies/icon_biggrin.gif', 'Mister grin', 5);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (2, ':blush', 'system/smilies/icon_blush.gif', 'Blush', 45);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (3, ':con', 'system/smilies/icon_confused.gif', 'Confused', 42);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (4, ':)', 'system/smilies/icon_smile.gif', 'Smile', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (5, ':cry', 'system/smilies/icon_cry.gif', 'Cry', 44);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (6, ':dontgetit', 'system/smilies/icon_dontgetit.gif', 'Don\'t get it', 41);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (7, ':dozingoff', 'system/smilies/icon_dozingoff.gif', 'Dozing off', 40);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (8, ':(', 'system/smilies/icon_sad.gif', 'Sad', 50);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (9, ':((', 'system/smilies/icon_mad.gif', 'Mad', 46);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (10, ':|', 'system/smilies/icon_neutral.gif', 'Neutral', 43);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (11, ':no', 'system/smilies/icon_no.gif', 'No', 12);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (12, ':O_o', 'system/smilies/icon_o_o.gif', 'Suspicious', 7);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (13, ':p', 'system/smilies/icon_razz.gif', 'Razz', 6);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (14, ':love', 'system/smilies/icon_love.gif', 'Love', 10);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (15, ':satisfied', 'system/smilies/icon_satisfied.gif', 'Satisfied', 2);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (16, '8)', 'system/smilies/icon_cool.gif', 'Cool', 4);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (17, ':wink', 'system/smilies/icon_wink.gif', 'Wink', 3);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "smilies VALUES (18, ':yes', 'system/smilies/icon_yes.gif', 'Yes', 11);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "stats (stat_name, stat_value) VALUES ('totalmailsent', '0');");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "stats (stat_name, stat_value) VALUES ('totalantihammer', '0');");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "stats (stat_name, stat_value) VALUES ('version', '" . $cfg['version'] . "');");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (1, 'admin', 'Administration panel', '100', 1, 1, 'system/core/admin/', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (4, 'index', 'Home page', '100', 1, 1, 'system/core/index/', 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (5, 'message', 'Messages', '100', 1, 1, 'system/core/message/', 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (8, 'plug', 'Plugins', '100', 1, 0, 'system/core/plug/', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (15, 'dic', 'Directories', '177', 1, 0, 'system/core/admin/', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (16, 'menu', 'Menu manager', '178', 1, 0, 'system/core/admin/', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (17, 'log', 'Log', '100', 1, 1, 'system/core/admin/', 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "core (ct_id, ct_code, ct_title, ct_version, ct_state, ct_lock, ct_path, ct_admin) VALUES (18, 'manage', 'Manage', '100', 1, 1, 'system/core/admin/', 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (1, 'guests', 0, 0, 0, 'Guests', '', '', 'darkmagenta', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (2, 'inactive', 1, 0, 0, 'Inactive', '', '', 'white', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (3, 'banned', 1, 0, 0, 'Banned', '', '', 'gray', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (4, 'members', 1, 0, 0, 'Members', '', '', 'black', 0, 0, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (5, 'administrators', 99, 0, 0, 'Administrators', '', '', 'red', 10240, 1048576, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups VALUES (6, 'moderators', 50, 0, 0, 'Moderators', '', '', 'green', 5120, 512000, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (1, 1, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (2, 2, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (3, 3, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (4, 4, 'admin', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (5, 5, 'admin', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (6, 6, 'admin', 'a', 1, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (13, 1, 'index', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (14, 2, 'index', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (15, 3, 'index', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (16, 4, 'index', 'a', 1, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (17, 5, 'index', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (18, 6, 'index', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (19, 1, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (20, 2, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (21, 3, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (22, 4, 'message', 'a', 1, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (23, 5, 'message', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (24, 6, 'message', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (97, 1, 'dic', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (98, 2, 'dic', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (99, 3, 'dic', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (100, 4, 'dic', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (101, 5, 'dic', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (102, 6, 'dic', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (103, 1, 'menu', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (104, 2, 'menu', 'a', 1, 254, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (105, 3, 'menu', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (106, 4, 'menu', 'a', 3, 128, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (107, 5, 'menu', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (108, 6, 'menu', 'a', 131, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (109, 1, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (110, 2, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (111, 3, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (112, 4, 'log', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (113, 5, 'log', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (114, 6, 'log', 'a', 0, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (115, 1, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (116, 2, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (117, 3, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (118, 4, 'manage', 'a', 0, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (119, 5, 'manage', 'a', 255, 255, 1);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "auth VALUES (120, 6, 'manage', 'a', 0, 0, 1);");

$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(1, 0, 'Menu', '', 1, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(2, 1, 'Home', '/', 2, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(3, 1, 'Forums', 'forums/', 3, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(4, 1, 'Articles', 'articles/', 4, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(5, 1, 'Galleries', 'gallery/', 5, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(6, 1, 'Contact', 'plug/contact', 6, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(7, 4, 'Sample category 1', 'articles/sample1/', 1, 1, '', '', '', 0, 0);");
$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "menu VALUES(8, 4, 'Sample category 2', 'articles/sample2/', 2, 1, '', '', '', 0, 0);");
