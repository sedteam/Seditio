<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=install.config.php
Version=180
Updated=2025-jan-25
Type=Core.install
Author=Seditio Team
Description=Configuration builder
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
    die('Wrong URL.');
}

$cfg_data = "<?PHP\n\n/* ====================\n";
$cfg_data .= "Seditio - Website engine\n";
$cfg_data .= "Copyright Neocrome & Seditio Team\n";
$cfg_data .= "https://seditio.org\n";
$cfg_data .= "[BEGIN_SED]\n";
$cfg_data .= "File=datas/config.php\n";
$cfg_data .= "Version=" . $cfg['version'] . "\n";
$cfg_data .= "Updated=" . mb_strtolower(@date('Y-M-d')) . "\n";
$cfg_data .= "Type=Config\n";
$cfg_data .= "Author=Seditio Team\n";
$cfg_data .= "Description=Configuration file (Gen.:" . @date('Y-M-d H:i:s') . ")\n";
$cfg_data .= "[END_SED]\n";
$cfg_data .= "==================== */\n";

$cfg_data .= "\n// ========================\n";
$cfg_data .= "// MySQL database parameters. Change to fit your host.\n";
$cfg_data .= "// ========================\n";

$cfg_data .= "\n\$cfg['mysqlhost'] = '" . $mysqlhost . "';			// Database host URL\n";
$cfg_data .= "\$cfg['mysqluser'] = '" . $mysqluser . "';			// Database user\n";
$cfg_data .= "\$cfg['mysqlpassword'] = '" . $mysqlpassword . "';	// Database password\n";
$cfg_data .= "\$cfg['mysqldb'] = '" . $mysqldb . "';				// Database name\n";

$cfg_data .= "\n// ========================\n";
$cfg_data .= "// Default skin and default language\n";
$cfg_data .= "// ========================\n";

$cfg_data .= "\n\$cfg['defaultskin'] = '" . $defaultskin . "';	// Default skin code. Be SURE it's pointing to a valid folder in /skins/...\n";
$cfg_data .= "\$cfg['defaultlang'] = '" . $defaultlang . "';	// Default language code\n";
$cfg_data .= "\$cfg['adminskin'] = 'sympfy';				// Default admin skin\n";

$cfg_data .= "\n// ========================\n";
$cfg_data .= "// More settings\n";
$cfg_data .= "// Should work fine in most of cases.\n";
$cfg_data .= "// If you don't know, don't change.\n";
$cfg_data .= "// TRUE = enabled / FALSE = disabled\n";
$cfg_data .= "// ========================\n";

$cfg_data .= "\n\$cfg['sqldbprefix'] = '" . $sqldbprefix . "';			// Database tables prefix\n";
$cfg_data .= "\$cfg['sqldb'] = '" . $sqldb . "';  				// Type of the database connector driver, set 'mysql' or 'mysqli'.\n";
$cfg_data .= "\$cfg['site_secret'] = '" . $md_site_secret . "'; 					// Site secret key\n";
$cfg_data .= "\$cfg['authmode'] = 3; 					// (1:cookies, 2:sessions, 3:cookies+sessions) default=3\n";
$cfg_data .= "\$cfg['redirmode'] = FALSE;				// 0 or 1, Set to '1' if you cannot sucessfully log in on your server)\n";
$cfg_data .= "\$cfg['ipcheck'] = TRUE;  				// Will kill the logged-in session if the IP has changed\n";

$cfg_data .= "\$cfg['multihost'] = TRUE;            // Allow multiple host names for this site\n";

$cfg_data .= "\n// ========================\n";
$cfg_data .= "// Name of MySQL tables\n";
$cfg_data .= "// (OPTIONAL, if missing, Seditio will set default values)\n";
$cfg_data .= "// Change the \$cfg['sqldbprefix'] above if you'd like to\n";
$cfg_data .= "// make 2 separated install in the same database.\n";
$cfg_data .= "// or you'd like to share some tables between 2 sites.\n";
$cfg_data .= "// Else do not change.\n";
$cfg_data .= "// ========================\n";

$cfg_data .= "\n";
$cfg_data .= "\$db_auth				= \$cfg['sqldbprefix'].'auth';\n";
$cfg_data .= "\$db_banlist 			= \$cfg['sqldbprefix'].'banlist';\n";
$cfg_data .= "\$db_cache 			= \$cfg['sqldbprefix'].'cache';\n";
$cfg_data .= "\$db_com 				= \$cfg['sqldbprefix'].'com';\n";
$cfg_data .= "\$db_core				= \$cfg['sqldbprefix'].'core';\n";
$cfg_data .= "\$db_config 			= \$cfg['sqldbprefix'].'config';\n";
$cfg_data .= "\$db_forum_posts 		= \$cfg['sqldbprefix'].'forum_posts';\n";
$cfg_data .= "\$db_forum_sections 	= \$cfg['sqldbprefix'].'forum_sections';\n";
$cfg_data .= "\$db_forum_structure 	= \$cfg['sqldbprefix'].'forum_structure';\n";
$cfg_data .= "\$db_forum_topics 	= \$cfg['sqldbprefix'].'forum_topics';\n";
$cfg_data .= "\$db_groups 			= \$cfg['sqldbprefix'].'groups';\n";
$cfg_data .= "\$db_groups_users 	= \$cfg['sqldbprefix'].'groups_users';\n";
$cfg_data .= "\$db_logger 			= \$cfg['sqldbprefix'].'logger';\n";
$cfg_data .= "\$db_online 			= \$cfg['sqldbprefix'].'online';\n";
$cfg_data .= "\$db_pages 			= \$cfg['sqldbprefix'].'pages';\n";
$cfg_data .= "\$db_pfs 				= \$cfg['sqldbprefix'].'pfs';\n";
$cfg_data .= "\$db_pfs_folders 		= \$cfg['sqldbprefix'].'pfs_folders';\n";
$cfg_data .= "\$db_plugins 			= \$cfg['sqldbprefix'].'plugins';\n";
$cfg_data .= "\$db_pm 				= \$cfg['sqldbprefix'].'pm';\n";
$cfg_data .= "\$db_polls 			= \$cfg['sqldbprefix'].'polls';\n";
$cfg_data .= "\$db_polls_options 	= \$cfg['sqldbprefix'].'polls_options';\n";
$cfg_data .= "\$db_polls_voters 	= \$cfg['sqldbprefix'].'polls_voters';\n";
$cfg_data .= "\$db_rated 			= \$cfg['sqldbprefix'].'rated';\n";
$cfg_data .= "\$db_ratings 			= \$cfg['sqldbprefix'].'ratings';\n";
$cfg_data .= "\$db_referers 		= \$cfg['sqldbprefix'].'referers';\n";
$cfg_data .= "\$db_smilies 			= \$cfg['sqldbprefix'].'smilies';\n";
$cfg_data .= "\$db_stats 			= \$cfg['sqldbprefix'].'stats';\n";
$cfg_data .= "\$db_structure 		= \$cfg['sqldbprefix'].'structure';\n";
$cfg_data .= "\$db_trash	 		= \$cfg['sqldbprefix'].'trash';\n";
$cfg_data .= "\$db_users 			= \$cfg['sqldbprefix'].'users';\n";

$cfg_data .= "\n?>";
