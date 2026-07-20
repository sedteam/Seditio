<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/setup.main.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=Main controller and API for modern setup installer
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
	die('Wrong URL.');
}

// 1. Session and installation language initialization
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$langinstall = sed_import('langinstall', 'P', 'TXT', 10);
if (!empty($langinstall)) {
	$_SESSION['ilang'] = $langinstall;
} elseif (isset($_SESSION['ilang'])) {
	$langinstall = $_SESSION['ilang'];
} else {
	$langinstall = "ru"; // Default language
}

// Include language files
$lang_file = SED_ROOT . '/system/setup/lang/' . $langinstall . '/setup.' . $langinstall . '.lang.php';
if (file_exists($lang_file)) {
    require($lang_file);
} else {
    require(SED_ROOT . '/system/setup/lang/en/setup.en.lang.php');
}

// Installer steps & configuration defaults
$cfg['config_file'] = "datas/config.php";
$cfg['data_root'] = "datas";
$cfg['default_skin'] = 'sympfy';
$cfg['sqldb'] = "mysqli";

$steps = [
    1 => ['key' => 'welcome',    'icon' => 'hand', 'title' => $L['setup_step1']],
    2 => ['key' => 'system',     'icon' => 'shield', 'title' => $L['setup_step2']],
    3 => ['key' => 'database',   'icon' => 'database', 'title' => $L['setup_step3']],
    4 => ['key' => 'settings',   'icon' => 'settings', 'title' => $L['setup_step4']],
    5 => ['key' => 'extensions', 'icon' => 'puzzle', 'title' => $L['setup_step5']],
    6 => ['key' => 'install',    'icon' => 'rocket', 'title' => $L['setup_step6']],
];

$rwfolders = [
    "datas/defaultav",
    "datas/avatars",
    "datas/photos",
    "datas/signatures",
    "datas/thumbs",
    "datas/users"
];

if (file_exists(SED_ROOT . '/datas/config.php')) {
    @include(SED_ROOT . '/datas/config.php');
}

// Include setup helper functions
require(SED_ROOT . '/system/setup/inc/setup.functions.php');

// CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
}
$csrf_token = $_SESSION['csrf_token'];

// Define path and URI variables
$sys['secure'] = sed_is_ssl();
$sys['scheme'] = $sys['secure'] ? 'https' : 'http';
$sys['host'] = $_SERVER['HTTP_HOST'];
$sys['dir_uri'] = (mb_strlen(dirname($_SERVER['PHP_SELF'])) > 1) ? dirname($_SERVER['PHP_SELF']) : "/";
if ($sys['dir_uri'][mb_strlen($sys['dir_uri']) - 1] != '/') {
	$sys['dir_uri'] .= '/';
}
// Remove system/setup from URI to obtain site root
$sys['dir_uri'] = str_replace('system/setup/', '', $sys['dir_uri']);
$sys['abs_url'] = $sys['scheme'] . '://' . $sys['host'] . $sys['dir_uri'];

// ==========================================
// 2. AJAX API HANDLER
// ==========================================
if (!empty($_POST['ajax_action'])) {
    ob_start('setup_ob_filter');
    header('Content-Type: application/json; charset=utf-8');
    
    // Check CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['ok' => false, 'error' => 'Invalid CSRF Token. Please refresh the page.']);
        exit;
    }
    
    $action = $_POST['ajax_action'];
    
    switch ($action) {
        case 'set_lang':
            $new_lang = sed_import('langinstall', 'P', 'ALP', 10);
            $lang_file = SED_ROOT . '/system/setup/lang/' . $new_lang . '/setup.' . $new_lang . '.lang.php';
            if (!empty($new_lang) && file_exists($lang_file)) {
                $_SESSION['ilang'] = $new_lang;
                echo json_encode(array('ok' => true));
            } else {
                echo json_encode(array('ok' => false, 'error' => 'Unsupported language'));
            }
            break;
            
        case 'get_step':
            $step = sed_import('step', 'P', 'INT');
            if ($step < 1 || $step > 7) $step = 1;
            
            require_once(SED_ROOT . '/system/templates.php');
            $st = new XTemplate(SED_ROOT . '/system/setup/tpl/setup.step.tpl');
            $html = '';
            
            switch ($step) {
                case 1:
                    $st->assign('LANG_OPTIONS', get_install_lang_options($langinstall));
                    $st->parse('STEP_WELCOME');
                    $html = $st->text('STEP_WELCOME');
                    break;
                    
                case 2:
                    $checks_data = sed_setup_get_checks($L, $rwfolders, $cfg);
                    foreach ($checks_data['checks'] as $c) {
                        $st->assign(array(
                            'CHECK_NAME' => $c['name'],
                            'CHECK_VALUE' => $c['value'],
                            'CHECK_STATUS_CLASS' => $c['ok'] ? 'ok' : 'fail',
                            'CHECK_STATUS_SYMBOL' => $c['ok'] ? '✓' : '✗'
                        ));
                        $st->parse('STEP_SYSTEM.CHECK_ROW');
                    }
                    $st->assign('CAN_PROCEED_DISABLED', $checks_data['can_proceed'] ? '' : 'disabled');
                    $st->parse('STEP_SYSTEM');
                    $html = $st->text('STEP_SYSTEM');
                    break;
                    
                case 3:
                    $st->assign(array(
                        'DB_HOST' => 'localhost',
                        'DB_NAME' => '',
                        'DB_USER' => 'root',
                        'DB_PASS' => '',
                        'DB_PREFIX' => 'sed_',
                        'DB_CLEAR_CHECKED' => ''
                    ));
                    $st->parse('STEP_DATABASE');
                    $html = $st->text('STEP_DATABASE');
                    break;
                    
                case 4:
                    $skins = sed_setup_get_skins();
                    foreach ($skins as $s) {
                        $st->assign(array(
                            'SKIN_CODE' => $s['code'],
                            'SKIN_NAME' => $s['name'],
                            'SKIN_PREVIEW' => !empty($s['preview']) ? $s['preview'] : 'system/setup/img/seditio.svg',
                            'SKIN_ACTIVE' => ($s['code'] === 'sympfy') ? 'active' : ''
                        ));
                        $st->parse('STEP_SETTINGS.SKIN_ROW');
                    }
                    $langs = sed_setup_get_langs();
                    foreach ($langs as $l) {
                        $st->assign(array(
                            'LANG_CODE' => $l['code'],
                            'LANG_NAME' => $l['name'],
                            'LANG_SELECTED' => ($l['code'] === $langinstall) ? 'selected' : ''
                        ));
                        $st->parse('STEP_SETTINGS.LANG_ROW');
                    }
                    $st->assign(array(
                        'ADMIN_NAME' => 'admin',
                        'ADMIN_PASS' => '',
                        'ADMIN_EMAIL' => '',
                        'COUNTRY_OPTIONS' => get_country_options('RU')
                    ));
                    $st->parse('STEP_SETTINGS');
                    $html = $st->text('STEP_SETTINGS');
                    break;
                    
                case 5:
                    $modules = sed_setup_get_modules();
                    if (!empty($modules)) {
                        foreach ($modules as $m) {
                            $isLocked = $m['locked'];
                            $isChecked = $isLocked || !$m['skip'];
                            $iconHTML = !empty($m['icon']) ? '<img src="' . $m['icon'] . '" alt="' . $m['name'] . '" style="width: 24px; height: 24px; object-fit: contain;">' : '<i class="ic-box"></i>';
                            $st->assign(array(
                                'MODULE_CODE' => $m['code'],
                                'MODULE_NAME' => $m['name'],
                                'MODULE_DESC' => htmlspecialchars($m['desc']),
                                'MODULE_ICON_HTML' => $iconHTML,
                                'MODULE_LOCKED_CLASS' => $isLocked ? 'is-locked' : '',
                                'MODULE_BADGE' => $isLocked ? '<span class="ext-badge">' . $L['setup_locked_module'] . '</span>' : '',
                                'MODULE_CHECKED' => $isChecked ? 'checked' : '',
                                'MODULE_DISABLED' => $isLocked ? 'disabled' : ''
                            ));
                            $st->parse('STEP_EXTENSIONS.MODULE_ROW');
                        }
                    } else {
                        $st->parse('STEP_EXTENSIONS.NO_MODULES');
                    }
                    
                    $plugins = sed_setup_get_plugins();
                    if (!empty($plugins)) {
                        foreach ($plugins as $p) {
                            $isChecked = !$p['skip'];
                            $iconHTML = !empty($p['icon']) ? '<img src="' . $p['icon'] . '" alt="' . $p['name'] . '" style="width: 24px; height: 24px; object-fit: contain;">' : '<i class="ic-plug"></i>';
                            $st->assign(array(
                                'PLUGIN_CODE' => $p['code'],
                                'PLUGIN_NAME' => $p['name'],
                                'PLUGIN_DESC' => htmlspecialchars($p['desc']),
                                'PLUGIN_ICON_HTML' => $iconHTML,
                                'PLUGIN_CHECKED' => $isChecked ? 'checked' : ''
                            ));
                            $st->parse('STEP_EXTENSIONS.PLUGIN_ROW');
                        }
                    } else {
                        $st->parse('STEP_EXTENSIONS.NO_PLUGINS');
                    }
                    
                    $st->parse('STEP_EXTENSIONS');
                    $html = $st->text('STEP_EXTENSIONS');
                    break;
                    
                case 6:
                    $st->parse('STEP_INSTALL');
                    $html = $st->text('STEP_INSTALL');
                    break;
                    
                case 7:
                    $st->parse('STEP_COMPLETE');
                    $html = $st->text('STEP_COMPLETE');
                    break;
            }
            
            echo json_encode(['ok' => true, 'html' => $html]);
            break;
            
        case 'check_system':
            $res = sed_setup_get_checks($L, $rwfolders, $cfg);
            echo json_encode([
                'ok' => true,
                'checks' => $res['checks'],
                'can_proceed' => $res['can_proceed']
            ]);
            break;
            
        case 'test_db':
            $mysqlhost = sed_import('host', 'P', 'TXT', 128);
            $mysqluser = sed_import('user', 'P', 'TXT', 128);
            $mysqlpassword = sed_import('pass', 'P', 'TXT', 128);
            $mysqldb = sed_import('name', 'P', 'TXT', 128);
            
            // Silent error mode
            mysqli_report(MYSQLI_REPORT_OFF);
            $conn = @mysqli_connect($mysqlhost, $mysqluser, $mysqlpassword, $mysqldb);
            
            if (!$conn) {
                echo json_encode([
                    'ok' => false,
                    'error' => mysqli_connect_error()
                ]);
            } else {
                $version = mysqli_get_server_info($conn);
                mysqli_close($conn);
                echo json_encode([
                    'ok' => true,
                    'message' => sprintf($L['setup_db_connected'], $version)
                ]);
            }
            break;
            
        case 'get_skins':
            $skins = sed_setup_get_skins();
            echo json_encode(['ok' => true, 'skins' => $skins]);
            break;
            
        case 'get_langs':
            $langs = sed_setup_get_langs();
            echo json_encode(array('ok' => true, 'langs' => $langs));
            break;
            
        case 'get_modules':
            $list = sed_setup_get_modules();
            echo json_encode(['ok' => true, 'list' => $list]);
            break;
            
        case 'get_plugins':
            $list = sed_setup_get_plugins();
            echo json_encode(['ok' => true, 'list' => $list]);
            break;
            
        case 'run_install':
            $mysqlhost = sed_import('mysqlhost', 'P', 'TXT', 128);
            $mysqluser = sed_import('mysqluser', 'P', 'TXT', 128);
            $mysqlpassword = sed_import('mysqlpassword', 'P', 'TXT', 128);
            $mysqldb = sed_import('mysqldb', 'P', 'TXT', 128);
            $sqldbprefix = sed_import('sqldbprefix', 'P', 'TXT', 16);
            if ($sqldbprefix === null) $sqldbprefix = 'sed_';
            $cfg['sqldbprefix'] = $sqldbprefix;
            
            // Dynamically re-initialize all $db_* global variables with actual user prefix
            global $sed_dbnames;
            if (is_array($sed_dbnames)) {
                foreach ($sed_dbnames as $tbl_name) {
                    $var_name = 'db_' . $tbl_name;
                    $GLOBALS[$var_name] = $sqldbprefix . $tbl_name;
                }
            }
            
            // Explicitly declare globals used in setup scope
            global $db_auth, $db_banlist, $db_cache, $db_com, $db_core, $db_config, $db_dic, $db_dic_items,
                   $db_forum_posts, $db_forum_sections, $db_forum_structure, $db_forum_topics, $db_groups,
                   $db_groups_users, $db_menu, $db_online, $db_pages, $db_pfs, $db_pfs_folders, $db_plugins,
                   $db_pm, $db_polls, $db_polls_options, $db_polls_voters, $db_rated, $db_ratings, $db_referers,
                   $db_smilies, $db_structure, $db_stats, $db_users, $db_users_meta;

            $db_auth            = $sqldbprefix . 'auth';
            $db_banlist         = $sqldbprefix . 'banlist';
            $db_cache           = $sqldbprefix . 'cache';
            $db_com             = $sqldbprefix . 'com';
            $db_core            = $sqldbprefix . 'core';
            $db_config          = $sqldbprefix . 'config';
            $db_dic             = $sqldbprefix . 'dic';
            $db_dic_items       = $sqldbprefix . 'dic_items';
            $db_forum_posts     = $sqldbprefix . 'forum_posts';
            $db_forum_sections  = $sqldbprefix . 'forum_sections';
            $db_forum_structure = $sqldbprefix . 'forum_structure';
            $db_forum_topics    = $sqldbprefix . 'forum_topics';
            $db_groups          = $sqldbprefix . 'groups';
            $db_groups_users    = $sqldbprefix . 'groups_users';
            $db_menu            = $sqldbprefix . 'menu';
            $db_online          = $sqldbprefix . 'online';
            $db_pages           = $sqldbprefix . 'pages';
            $db_pfs             = $sqldbprefix . 'pfs';
            $db_pfs_folders     = $sqldbprefix . 'pfs_folders';
            $db_plugins         = $sqldbprefix . 'plugins';
            $db_pm              = $sqldbprefix . 'pm';
            $db_polls           = $sqldbprefix . 'polls';
            $db_polls_options   = $sqldbprefix . 'polls_options';
            $db_polls_voters    = $sqldbprefix . 'polls_voters';
            $db_rated           = $sqldbprefix . 'rated';
            $db_ratings         = $sqldbprefix . 'ratings';
            $db_referers        = $sqldbprefix . 'referers';
            $db_smilies         = $sqldbprefix . 'smilies';
            $db_structure       = $sqldbprefix . 'structure';
            $db_stats           = $sqldbprefix . 'stats';
            $db_users           = $sqldbprefix . 'users';
            $db_users_meta      = $sqldbprefix . 'users_meta';
            
            $db_clear_before_import = sed_import('db_clear_before_import', 'P', 'INT');
            
            $defaultskin = sed_import('defaultskin', 'P', 'TXT', 32);
            $defaultlang = sed_import('defaultlang', 'P', 'ALP', 5);
            if (empty($defaultskin)) $defaultskin = 'sympfy';
            if (empty($defaultlang)) $defaultlang = isset($_SESSION['ilang']) ? $_SESSION['ilang'] : 'ru';

            $cfg['defaultskin'] = $defaultskin;
            $cfg['defaultlang'] = $defaultlang;
            $cfg['cookiedomain'] = isset($cfg['cookiedomain']) ? $cfg['cookiedomain'] : '';
            $cfg['cookiepath'] = isset($cfg['cookiepath']) ? $cfg['cookiepath'] : '/';
            
            $rusername = sed_import('admin_name', 'P', 'TXT', 24, TRUE);
            $rpassword = sed_import('admin_pass', 'P', 'TXT', 32);
            $ruseremail = sed_import('admin_email', 'P', 'TXT', 64, TRUE);
            $rcountry = sed_import('admin_country', 'P', 'TXT');
            
            $sel_modules = sed_import('modules', 'P', 'ARR');
            $sel_plugins = sed_import('plugins', 'P', 'ARR');
            
            $log = [];
            
            // 1. Database connection
            require_once(SED_ROOT . '/system/database.mysqli.php');
            mysqli_report(MYSQLI_REPORT_OFF);
            $connection_id = @sed_sql_connect($mysqlhost, $mysqluser, $mysqlpassword, $mysqldb);
            
            if ($connection_id === false) {
                echo json_encode(['ok' => false, 'error' => mysqli_connect_error()]);
                exit;
            }
            sed_sql_set_charset($connection_id, 'utf8');
            $log[] = ['ok' => true, 'msg' => $L['setup_connected_to_db']];
            // 2. Clear DB if requested
            if ($db_clear_before_import) {
                sed_sql_query("SET FOREIGN_KEY_CHECKS = 0");
                $sql_tables = sed_sql_query("SHOW TABLES FROM `$mysqldb`");
                $dropped = 0;
                while ($row = sed_sql_fetcharray($sql_tables)) {
                    $tbl = current($row);
                    if ($tbl !== '') {
                        sed_sql_query("DROP TABLE IF EXISTS `" . sed_sql_prep($tbl) . "`");
                        $dropped++;
                    }
                }
                sed_sql_query("SET FOREIGN_KEY_CHECKS = 1");
                $log[] = ['ok' => true, 'msg' => $L['setup_database_cleared'] . ' (' . $dropped . ' ' . $L['setup_tables_dropped'] . ')'];
            }
            
            // 3. Create configuration file
            $md_site_secret = md5(sed_unique(16));
            $cfg['site_secret'] = $md_site_secret;
            $cfg['version'] = isset($cfg['version']) ? $cfg['version'] : '186';
            
            require(SED_ROOT . '/system/setup/setup.config.php');
            
            $config_path = SED_ROOT . '/' . $cfg['config_file'];
            if (file_exists($config_path)) {
                @chmod($config_path, 0666);
                @unlink($config_path);
            }
            $fp = @fopen($config_path, 'w');
            if ($fp) {
                @fwrite($fp, $cfg_data);
                @fclose($fp);
                @chmod($config_path, 0444);
                $log[] = ['ok' => true, 'msg' => sprintf($L['setup_config_created'] . ' (%s)', basename($config_path))];
            } else {
                echo json_encode(['ok' => false, 'error' => $L['setup_error_config_write'] . " (Path: " . $config_path . ")"]);
                exit;
            }
            
            // 4. Create database tables
            require(SED_ROOT . '/system/setup/setup.database.php');
            $log[] = ['ok' => true, 'msg' => $L['setup_tables_created']];
            
            // 5. Config map (default options)
            $cfgmap = sed_loadconfigmap();
			$query = [];
			foreach ($cfgmap as $i => $line) {
				$line[5] = (!empty($line[5]) && is_array($line[5])) ? implode(',', $line[5]) : '';
				$query[] = "('core','" . $line[0] . "','" . $line[1] . "','" . $line[2] . "'," . (int)$line[3] . ",'" . $line[4] . "','" . $line[4] . "','" . $line[5] . "')";
			}
			$query = implode(",", $query);
			$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "config (config_owner, config_cat, config_order, config_name, config_type, config_value, config_default, config_variants) VALUES " . $query);
            $log[] = ['ok' => true, 'msg' => $L['setup_config_loaded']];
            
            // 6. Superadministrator account creation
            $ip = $_SERVER['REMOTE_ADDR'];
            $defgroup = 5; // Administrators
			$mdsalt = sed_unique(16);
			$mdpass = sed_hash($rpassword, 1, $mdsalt);
			$mdpass_secret = md5(sed_unique(16));
			$validationkey = md5(microtime());
            
            $sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "users
			(user_name,
			user_password,
			user_salt,
			user_secret,
			user_passtype,
			user_maingrp,
			user_country,
			user_location,
			user_timezone,
			user_occupation,
			user_text,
			user_email,
			user_hideemail,
			user_pmnotify,
			user_skin,
			user_lang,
			user_regdate,
			user_logcount,
			user_lostpass,
			user_gender,
			user_birthdate,
			user_skype,
			user_website,
			user_lastip)
			VALUES
			('" . sed_sql_prep($rusername) . "',
			'$mdpass',
			'$mdsalt',
			'$mdpass_secret',
			1,			
			" . (int)$defgroup . ",
			'" . sed_sql_prep($rcountry) . "',
			'',
			'0',
			'',
			'',
			'" . sed_sql_prep($ruseremail) . "',
			1,
			0,
			'" . sed_sql_prep($defaultskin) . "',
			'" . sed_sql_prep($defaultlang) . "',
			" . (int)time() . ",
			0,
			'$validationkey',
			'',
			0,
			'', '', '" . $ip . "')");
            
            $userid = sed_sql_insertid();
			$sql = sed_sql_query("INSERT INTO " . $sqldbprefix . "groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$defgroup . ")");
            
            // Save to user session and authenticate on site
            $usr['id'] = $userid;
			$usr['name'] = $rusername;
			$usr['ip'] = $ip;
			$_SESSION['usr'] = $usr;
            
            $site_id = 'sed' . substr(md5($cfg['site_secret']), 0, 16);
            $_SESSION[$site_id . '_n'] = $userid;
            $_SESSION[$site_id . '_p'] = $mdpass_secret;
            $_SESSION[$site_id . '_s'] = '';
            
            $u = base64_encode($userid . ':_:' . $mdpass_secret . ':_:' . '');
            $cookiepath = !empty($cfg['cookiepath']) ? $cfg['cookiepath'] : '/';
            $cookiedomain = !empty($cfg['cookiedomain']) ? $cfg['cookiedomain'] : '';
            sed_setcookie($site_id, $u, time() + 3600 * 24 * 30, $cookiepath, $cookiedomain, $sys['secure'], true);
            
            $log[] = ['ok' => true, 'msg' => $L['setup_admin_created']];
            
            // Load user groups for extension installer
            if (!isset($sed_groups)) {
                $sed_groups = [];
                $sql_g = sed_sql_query("SELECT * FROM " . $sqldbprefix . "groups WHERE grp_disabled = 0 ORDER BY grp_level DESC");
                while ($row = sed_sql_fetchassoc($sql_g)) {
                    $sed_groups[$row['grp_id']] = [
                        'id' => $row['grp_id'],
                        'alias' => $row['grp_alias'],
                        'title' => $row['grp_title'],
                        'level' => $row['grp_level']
                    ];
                }
            }
            
            // 7. Install modules
            if (is_array($sel_modules)) {
                foreach ($sel_modules as $mod) {
                    sed_module_install($mod);
                    $log[] = ['ok' => true, 'msg' => "Module '$mod' installed"];
                }
            }
            
            // 8. Install plugins
            if (is_array($sel_plugins)) {
                foreach ($sel_plugins as $pl) {
                    sed_plugin_install($pl);
                    $log[] = ['ok' => true, 'msg' => "Plugin '$pl' installed"];
                }
            }
            
            // 9. Finalization
            sed_urls_generate();
            sed_stat_create('installed', 1);
            $log[] = ['ok' => true, 'msg' => $L['setup_complete']];
            
            $st = new XTemplate(SED_ROOT . '/system/setup/tpl/setup.step.tpl');
            $st->parse('STEP_COMPLETE');
            $complete_html = $st->text('STEP_COMPLETE');
            
            echo json_encode([
                'ok' => true,
                'log' => $log,
                'complete_html' => $complete_html
            ]);
            exit;
    }
    exit;
}

// ==========================================
// 3. COLLECT STRINGS FOR JS LOCALIZATION
// ==========================================
$L_setup_js = [];
foreach ($L as $key => $val) {
    if (strpos($key, 'setup_') === 0) {
        $js_key = str_replace('setup_', '', $key);
        $L_setup_js[$js_key] = $val;
    }
}

// Add missing variables to JS config
$L_setup_js['current_lang'] = $langinstall;
$L_setup_js['lang_options'] = get_install_lang_options($langinstall);
$L_setup_js['country_options'] = get_country_options('00');

// Steps for JS
for ($i = 1; $i <= count($steps); $i++) {
    $L_setup_js["step{$i}_title"] = $steps[$i]['title'];
    $L_setup_js["step{$i}_icon"] = $steps[$i]['icon'];
}

$cfg['version'] = isset($cfg['version']) ? $cfg['version'] : '186';

// ==========================================
// 5. RENDER INTERFACE VIA XTEMPLATE
// ==========================================
require_once(SED_ROOT . '/system/templates.php');

// Parse initial step 1 from setup.step.tpl
$st = new XTemplate(SED_ROOT . '/system/setup/tpl/setup.step.tpl');
$st->assign('LANG_OPTIONS', get_install_lang_options($langinstall));
$st->parse('STEP_WELCOME');
$step1_html = $st->text('STEP_WELCOME');

// Parse master page shell from setup.tpl
$t = new XTemplate(SED_ROOT . '/system/setup/tpl/setup.tpl');
$t->assign(array(
    'STEP_CONTENT' => $step1_html,
    'SETUP_LANG_JSON' => json_encode($L_setup_js),
    'SETUP_CLEAN_JS' => file_exists(SED_ROOT . '/datas/config.php') ? 'false' : 'true',
    'YEAR' => date('Y')
));

$t->parse('MAIN');
$t->out('MAIN');
?>
