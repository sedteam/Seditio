<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/forums.install.php
Version=185
Updated=2026-feb-14
Type=Module.install
Author=Seditio Team
Description=Forums install script
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Forum tables are created by install.database.php (core tables).
// This install script only ensures the tables exist for standalone module installation.

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];

// Check if forum_posts table exists
$check = sed_sql_query("SHOW TABLES LIKE '" . $cfg['sqldbprefix'] . "forum_posts'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating forum_posts table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS " . $cfg['sqldbprefix'] . "forum_posts (
		fp_id mediumint(8) unsigned NOT NULL auto_increment,
		fp_topicid mediumint(8) NOT NULL DEFAULT '0',
		fp_sectionid smallint(5) NOT NULL DEFAULT '0',
		fp_posterid int(11) NOT NULL DEFAULT '-1',
		fp_postername varchar(24) NOT NULL DEFAULT '',
		fp_creation int(11) NOT NULL DEFAULT '0',
		fp_updated int(11) NOT NULL DEFAULT '0',
		fp_updater varchar(24) NOT NULL DEFAULT '0',
		fp_text text NOT NULL,
		fp_text_ishtml tinyint(1) DEFAULT '1',
		fp_posterip varchar(45) NOT NULL DEFAULT '',
		fp_rating tinyint(1) DEFAULT '0',
		PRIMARY KEY (fp_id),
		UNIQUE KEY fp_topicid (fp_topicid,fp_id),
		KEY fp_updated (fp_creation)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$check = sed_sql_query("SHOW TABLES LIKE '" . $cfg['sqldbprefix'] . "forum_sections'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating forum_sections table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS " . $cfg['sqldbprefix'] . "forum_sections (
		fs_id smallint(5) unsigned NOT NULL auto_increment,
		fs_state tinyint(1) unsigned NOT NULL DEFAULT '0',
		fs_order smallint(5) unsigned NOT NULL DEFAULT '0',
		fs_title varchar(255) NOT NULL DEFAULT '',
		fs_category varchar(64) NOT NULL DEFAULT '',
		fs_parentcat smallint(5) unsigned NOT NULL DEFAULT '0',
		fs_desc varchar(255) NOT NULL DEFAULT '',
		fs_icon varchar(255) NOT NULL DEFAULT '',
		fs_lt_id int(11) NOT NULL DEFAULT '0',
		fs_lt_title varchar(64) NOT NULL DEFAULT '',
		fs_lt_date int(11) NOT NULL DEFAULT '0',
		fs_lt_posterid int(11) NOT NULL DEFAULT '-1',
		fs_lt_postername varchar(24) NOT NULL DEFAULT '',
		fs_autoprune int(11) NOT NULL DEFAULT '0',
		fs_allowusertext tinyint(1) NOT NULL DEFAULT '1',
		fs_allowbbcodes tinyint(1) NOT NULL DEFAULT '1',
		fs_allowsmilies tinyint(1) NOT NULL DEFAULT '1',
		fs_allowprvtopics tinyint(1) NOT NULL DEFAULT '0',
		fs_countposts tinyint(1) NOT NULL DEFAULT '1',
		fs_topiccount mediumint(8) NOT NULL DEFAULT '0',
		fs_topiccount_pruned int(11) DEFAULT '0',
		fs_postcount mediumint(8) NOT NULL DEFAULT '0',
		fs_postcount_pruned int(11) DEFAULT '0',
		fs_viewcount mediumint(8) NOT NULL DEFAULT '0',
		PRIMARY KEY (fs_id),
		KEY fs_order (fs_order)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");

	// Default data
	sed_sql_query("INSERT INTO " . $cfg['sqldbprefix'] . "forum_sections VALUES ('1', '0', '100', 'General discussion', 'pub', 0, 'General chat.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0);");
	sed_sql_query("INSERT INTO " . $cfg['sqldbprefix'] . "forum_sections VALUES ('2', '0', '101', 'Off-topic', 'pub', 0, 'Various and off-topic.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0);");
}

// Ensure auth rows exist for all forum sections (so sections are visible to groups)
global $sed_groups, $db_auth;
$db_auth = $cfg['sqldbprefix'] . 'auth';
$forums_default_rights = array(
	SED_GROUP_DEFAULT => 'RW',
	SED_GROUP_GUESTS => 'R',
	SED_GROUP_INACTIVE => 'R',
	SED_GROUP_BANNED => '',
	SED_GROUP_MEMBERS => 'RW',
	SED_GROUP_MODERATORS => 'RWA',
	SED_GROUP_SUPERADMINS => 'RWA12345',
);
$forums_default_lock = array(
	SED_GROUP_DEFAULT => 'A',
	SED_GROUP_GUESTS => 'W12345A',
	SED_GROUP_INACTIVE => 'W12345A',
	SED_GROUP_BANNED => 'RWA12345',
	SED_GROUP_MEMBERS => 'A',
	SED_GROUP_MODERATORS => '',
	SED_GROUP_SUPERADMINS => 'RWA12345',
);
$forum_rights = array();
$forum_lock = array();
foreach ($sed_groups as $k => $v) {
	$gid = $v['id'];
	$forum_rights[$gid] = isset($forums_default_rights[$gid]) ? $forums_default_rights[$gid] : $forums_default_rights[SED_GROUP_DEFAULT];
	$forum_lock[$gid] = isset($forums_default_lock[$gid]) ? $forums_default_lock[$gid] : $forums_default_lock[SED_GROUP_DEFAULT];
}
$sql_fs = sed_sql_query("SELECT fs_id FROM " . $cfg['sqldbprefix'] . "forum_sections");
while ($fs = sed_sql_fetchassoc($sql_fs)) {
	$sid = (int)$fs['fs_id'];
	$chk = sed_sql_query("SELECT 1 FROM $db_auth WHERE auth_code='forums' AND auth_option='$sid' LIMIT 1");
	if (sed_sql_numrows($chk) == 0) {
		sed_auth_install_option('forums', $sid, $forum_rights, $forum_lock, 1);
		$res .= "Auth for forum section $sid created.<br />";
	}
}

$check = sed_sql_query("SHOW TABLES LIKE '" . $cfg['sqldbprefix'] . "forum_structure'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating forum_structure table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS " . $cfg['sqldbprefix'] . "forum_structure (
		fn_id mediumint(8) NOT NULL auto_increment,
		fn_path varchar(16) NOT NULL DEFAULT '',
		fn_code varchar(16) NOT NULL DEFAULT '',
		fn_tpl varchar(64) NOT NULL DEFAULT '',
		fn_title varchar(32) NOT NULL DEFAULT '',
		fn_desc varchar(255) NOT NULL DEFAULT '',
		fn_icon varchar(128) NOT NULL DEFAULT '',
		fn_defstate tinyint(1) NOT NULL DEFAULT '1',
		PRIMARY KEY (fn_id)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");

	sed_sql_query("INSERT INTO " . $cfg['sqldbprefix'] . "forum_structure VALUES ('1', '1', 'pub', '', 'Public', '', '', 1);");
}

$check = sed_sql_query("SHOW TABLES LIKE '" . $cfg['sqldbprefix'] . "forum_topics'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating forum_topics table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS " . $cfg['sqldbprefix'] . "forum_topics (
		ft_id mediumint(8) unsigned NOT NULL auto_increment,
		ft_mode tinyint(1) unsigned NOT NULL DEFAULT '0',
		ft_state tinyint(1) unsigned NOT NULL DEFAULT '0',
		ft_sticky tinyint(1) unsigned NOT NULL DEFAULT '0',
		ft_tag varchar(16) NOT NULL DEFAULT '',
		ft_sectionid mediumint(8) NOT NULL DEFAULT '0',
		ft_title varchar(255) NOT NULL DEFAULT '',
		ft_desc varchar(255) NOT NULL DEFAULT '',
		ft_creationdate int(11) NOT NULL DEFAULT '0',
		ft_updated int(11) NOT NULL DEFAULT '0',
		ft_postcount mediumint(8) NOT NULL DEFAULT '0',
		ft_viewcount mediumint(8) NOT NULL DEFAULT '0',
		ft_lastposterid int(11) NOT NULL DEFAULT '-1',
		ft_lastpostername varchar(24) NOT NULL DEFAULT '',
		ft_firstposterid int(11) NOT NULL DEFAULT '-1',
		ft_firstpostername varchar(24) NOT NULL DEFAULT '',
		ft_poll int(11) DEFAULT '0',
		ft_movedto int(11) DEFAULT '0',
		PRIMARY KEY (ft_id),
		KEY ft_updated (ft_updated),
		KEY ft_mode (ft_mode),
		KEY ft_state (ft_state),
		KEY ft_sticky (ft_sticky),
		KEY ft_sectionid (ft_sectionid)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$res .= "Forum tables verified.<br />";
