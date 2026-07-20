<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/inc/setup.functions.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=Helper functions for setup installer
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_INSTALL')) {
	die('Wrong URL.');
}

// Global language names mapping for setup module
$sed_setup_lang_names = array(
    'en' => 'English',
    'ru' => 'Русский',
    'de' => 'Deutsch',
    'es' => 'Español',
    'fr' => 'Français',
    'it' => 'Italiano',
    'nl' => 'Nederlands',
    'tr' => 'Türkçe',
    'pl' => 'Polski',
    'pt' => 'Português',
    'cn' => '中文',
    'se' => 'Svenska',
    'dk' => 'Dansk',
    'fi' => 'Suomi',
    'gr' => 'Ελληνικά',
    'hu' => 'Magyar',
    'jp' => '日本語',
    'kr' => '한국어'
);

/**
 * Perform environment checks (PHP version, extensions, writable folders, config file)
 * 
 * @param array $L Language array
 * @param array $rwfolders Array of folders that require write permissions
 * @param array $cfg System configuration array
 * @return array Checks array and overall proceed flag
 */
function sed_setup_get_checks($L, $rwfolders, $cfg) {
    $checks = array();
    
    // PHP version check
    $php_ok = version_compare(PHP_VERSION, '5.6', '>=');
    $checks[] = array(
        'name' => isset($L['setup_php_version']) ? $L['setup_php_version'] : 'PHP version',
        'value' => PHP_VERSION,
        'ok' => $php_ok
    );
    
    // MySQLi extension check
    $mysqli_ok = extension_loaded('mysqli');
    $checks[] = array(
        'name' => 'MySQLi extension',
        'value' => $mysqli_ok ? (isset($L['setup_available']) ? $L['setup_available'] : 'Available') : (isset($L['setup_missing']) ? $L['setup_missing'] : 'Missing'),
        'ok' => $mysqli_ok
    );
    
    // GD Library check
    $gd_ok = extension_loaded('gd');
    $checks[] = array(
        'name' => 'GD extension',
        'value' => $gd_ok ? (isset($L['setup_available']) ? $L['setup_available'] : 'Available') : (isset($L['setup_missing']) ? $L['setup_missing'] : 'Missing'),
        'ok' => $gd_ok
    );
    
    // MB String check
    $mb_ok = extension_loaded('mbstring');
    $checks[] = array(
        'name' => 'MB String extension',
        'value' => $mb_ok ? (isset($L['setup_available']) ? $L['setup_available'] : 'Available') : (isset($L['setup_missing']) ? $L['setup_missing'] : 'Missing'),
        'ok' => $mb_ok
    );
    
    // Writable directories checks
    $folders_ok = true;
    if (is_array($rwfolders)) {
        foreach ($rwfolders as $folder) {
            $path = SED_ROOT . '/' . $folder;
            $exists = file_exists($path);
            $writable = $exists && is_writable($path);
            if (!$writable) {
                $folders_ok = false;
            }
            
            $val_text = '';
            if ($exists) {
                $val_text = $writable ? (isset($L['setup_writable']) ? $L['setup_writable'] : 'Writable') : (isset($L['setup_not_writable']) ? $L['setup_not_writable'] : 'Not writable');
            } else {
                $val_text = isset($L['setup_not_found']) ? $L['setup_not_found'] : 'Not found';
            }
            
            $checks[] = array(
                'name' => (isset($L['setup_folder']) ? $L['setup_folder'] : 'Folder') . ' ' . $folder,
                'value' => $val_text,
                'ok' => $writable
            );
        }
    }
    
    // Config file check
    $config_file = isset($cfg['config_file']) ? $cfg['config_file'] : 'datas/config.php';
    $data_root = isset($cfg['data_root']) ? $cfg['data_root'] : 'datas';
    $config_path = SED_ROOT . '/' . $config_file;
    $config_dir = SED_ROOT . '/' . $data_root;
    $config_ok = false;
    $config_val = '';
    
    if (file_exists($config_path)) {
        $config_ok = is_writable($config_path);
        $config_val = $config_ok ? (isset($L['setup_found_writable']) ? $L['setup_found_writable'] : 'Writable') : (isset($L['setup_found_notwritable']) ? $L['setup_found_notwritable'] : 'Not writable');
    } else {
        $config_ok = is_writable($config_dir);
        $config_val = $config_ok ? (isset($L['setup_notfound_folderwritable']) ? $L['setup_notfound_folderwritable'] : 'Folder writable') : (isset($L['setup_notfound_foldernotwritable']) ? $L['setup_notfound_foldernotwritable'] : 'Folder not writable');
    }
    
    $checks[] = array(
        'name' => (isset($L['setup_folder']) ? $L['setup_folder'] : 'File') . ' ' . $config_file,
        'value' => $config_val,
        'ok' => $config_ok
    );
    
    $can_proceed = $php_ok && $mysqli_ok && $mb_ok && $folders_ok && $config_ok;
    
    return array(
        'checks' => $checks,
        'can_proceed' => $can_proceed
    );
}

/**
 * Scan skins directory and return available skins
 * 
 * @return array Skins list
 */
function sed_setup_get_skins() {
    $skins = array();
    $dir = SED_ROOT . '/skins';
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($f = readdir($handle)) {
            if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                $preview = '';
                if (file_exists($dir . '/' . $f . '/' . $f . '.png')) {
                    $preview = 'skins/' . $f . '/' . $f . '.png';
                }
                $skins[] = array(
                    'code' => $f,
                    'name' => ucfirst($f),
                    'preview' => $preview
                );
            }
        }
        closedir($handle);
        sort($skins);
    }
    return $skins;
}

/**
 * Scan system/lang directory and return available languages
 * 
 * @return array Languages list
 */
function sed_setup_get_langs() {
    global $sed_setup_lang_names;
    
    $langs = array();
    $dir = SED_ROOT . '/system/lang';
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($f = readdir($handle)) {
            if ($f[0] != '.' && is_dir($dir . '/' . $f)) {
                $name = isset($sed_setup_lang_names[$f]) ? $sed_setup_lang_names[$f] : ucfirst($f);
                
                $setup_lang_file = SED_ROOT . '/system/setup/lang/' . $f . '/setup.' . $f . '.lang.php';
                if (file_exists($setup_lang_file)) {
                    $L = array();
                    @include($setup_lang_file);
                    if (!empty($L['lang_name'])) {
                        $name = $L['lang_name'];
                    }
                } else {
                    $lang_main = $dir . '/' . $f . '/main.lang.php';
                    if (file_exists($lang_main)) {
                        $sed_languages = array();
                        @include($lang_main);
                        if (!empty($sed_languages[$f])) {
                            $name = $sed_languages[$f];
                        }
                    }
                }
                
                $langs[] = array(
                    'code' => $f,
                    'name' => $name
                );
            }
        }
        closedir($handle);
        usort($langs, function($a, $b) {
            return strcmp($a['code'], $b['code']);
        });
    }
    return $langs;
}

/**
 * Scan modules directory and return available modules
 * 
 * @return array Modules list
 */
function sed_setup_get_modules() {
    $list = array();
    $dir = SED_ROOT . '/modules';
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($f = readdir($handle)) {
            if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                $setup = $dir . '/' . $f . '/' . $f . '.setup.php';
                if (file_exists($setup)) {
                    $info = sed_infoget($setup, 'SED_MODULE');
                    $icon = '';
                    if (file_exists($dir . '/' . $f . '/' . $f . '.png')) {
                        $icon = 'modules/' . $f . '/' . $f . '.png';
                    } elseif (file_exists($dir . '/' . $f . '/icon.png')) {
                        $icon = 'modules/' . $f . '/icon.png';
                    }
                    $list[] = array(
                        'code' => $f,
                        'name' => isset($info['Name']) ? $info['Name'] : $f,
                        'desc' => isset($info['Description']) ? $info['Description'] : '',
                        'icon' => $icon,
                        'locked' => (isset($info['Lock_module']) && (int)$info['Lock_module'] === 1),
                        'skip' => (isset($info['Installer_skip']) && $info['Installer_skip'] == 1)
                    );
                }
            }
        }
        closedir($handle);
        sort($list);
    }
    return $list;
}

/**
 * Scan plugins directory and return available plugins
 * 
 * @return array Plugins list
 */
function sed_setup_get_plugins() {
    $list = array();
    $dir = SED_ROOT . '/plugins';
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($f = readdir($handle)) {
            if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                $setup = $dir . '/' . $f . '/' . $f . '.setup.php';
                if (file_exists($setup)) {
                    $info = sed_infoget($setup, 'SED_EXTPLUGIN');
                    $icon = '';
                    if (file_exists($dir . '/' . $f . '/' . $f . '.png')) {
                        $icon = 'plugins/' . $f . '/' . $f . '.png';
                    } elseif (file_exists($dir . '/' . $f . '/icon.png')) {
                        $icon = 'plugins/' . $f . '/icon.png';
                    }
                    $list[] = array(
                        'code' => $f,
                        'name' => isset($info['Name']) ? $info['Name'] : $f,
                        'desc' => isset($info['Description']) ? $info['Description'] : '',
                        'icon' => $icon,
                        'skip' => (isset($info['Installer_skip']) && $info['Installer_skip'] == 1)
                    );
                }
            }
        }
        closedir($handle);
        sort($list);
    }
    return $list;
}

/**
 * Output buffer filter to clean up non-JSON prefixes
 * 
 * @param string $buffer Output buffer content
 * @return string Filtered buffer
 */
function setup_ob_filter($buffer) {
    $first_brace = strpos($buffer, '{');
    $first_bracket = strpos($buffer, '[');
    if ($first_brace === false && $first_bracket === false) return $buffer;
    
    $pos = false;
    if ($first_brace !== false && $first_bracket !== false) {
        $pos = min($first_brace, $first_bracket);
    } else {
        $pos = ($first_brace !== false) ? $first_brace : $first_bracket;
    }
    
    if ($pos > 0) {
        $candidate = substr($buffer, $pos);
        if (@json_decode($candidate) !== null) {
            return $candidate;
        }
    }
    return $buffer;
}

/**
 * Generate HTML option tags for install language selection
 * 
 * @param string $check Currently selected language code
 * @return string HTML option elements
 */
function get_install_lang_options($check) {
    global $sed_setup_lang_names;

    $langlist = array();
    $dir = SED_ROOT . '/system/setup/lang/';
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($f = readdir($handle)) {
            if ($f[0] != '.' && is_dir($dir . $f)) {
                $name = isset($sed_setup_lang_names[$f]) ? $sed_setup_lang_names[$f] : ucfirst($f);
                $lang_file = $dir . $f . '/setup.' . $f . '.lang.php';
                if (file_exists($lang_file)) {
                    $L = array();
                    @include($lang_file);
                    if (!empty($L['lang_name'])) {
                        $name = $L['lang_name'];
                    }
                }
                $langlist[$f] = $name;
            }
        }
        closedir($handle);
        ksort($langlist);
    }
    
    $result = '';
    foreach ($langlist as $code => $name) {
        $selected = ($code == $check) ? "selected=\"selected\"" : '';
        $result .= "<option value=\"$code\" $selected>$name ($code)</option>";
    }
    return $result;
}

/**
 * Generate HTML option tags for country selection
 * 
 * @param string $check Currently selected country code
 * @return string HTML option elements
 */
function get_country_options($check) {
    global $sed_countries;
    if (empty($sed_countries)) {
        $lang_path = SED_ROOT . '/system/lang/' . $_SESSION['ilang'] . '/main.lang.php';
        if (file_exists($lang_path)) {
            @include($lang_path);
        } else {
            $lang_path_en = SED_ROOT . '/system/lang/en/main.lang.php';
            if (file_exists($lang_path_en)) {
                @include($lang_path_en);
            }
        }
    }
    
    $result = '';
    if (is_array($sed_countries) && count($sed_countries) > 0) {
        foreach ($sed_countries as $code => $name) {
            $selected = ($code == $check) ? "selected=\"selected\"" : '';
            $result .= "<option value=\"$code\" $selected>" . htmlspecialchars($name) . "</option>";
        }
    } else {
        $result .= "<option value=\"00\" selected>---</option>";
    }
    return $result;
}
