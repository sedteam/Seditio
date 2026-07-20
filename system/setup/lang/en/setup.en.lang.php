<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/lang/en/setup.en.lang.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=English setup language file
[END_SED]
==================== */

// Language name
$L['lang_name'] = "English";

// Steps
$L['setup_step1'] = "Welcome";
$L['setup_step2'] = "Checks";
$L['setup_step3'] = "Database";
$L['setup_step4'] = "Settings";
$L['setup_step5'] = "Extensions";
$L['setup_step6'] = "Installation";

// Navigation
$L['setup_next_step'] = "Next";
$L['setup_prev_step'] = "Back";

// Welcome
$L['setup_welcome_title'] = "Welcome to Seditio";
$L['setup_welcome_desc'] = "This wizard will help you set up and configure Seditio CMS from scratch in just a few simple steps.";
$L['setup_select_language'] = "Select installation language";

// System Check
$L['setup_system_check_title'] = "Compatibility Check";
$L['setup_system_check_desc'] = "We check your server configurations and folder permissions to ensure the engine runs correctly.";
$L['setup_checking'] = "Checking";
$L['setup_php_version'] = "PHP Version";
$L['setup_php_min_74'] = "PHP 5.6 or higher is required";
$L['setup_available'] = "Available";
$L['setup_missing'] = "Not available";
$L['setup_folder'] = "Folder";
$L['setup_writable'] = "Writable";
$L['setup_not_writable'] = "Not writable";
$L['setup_not_found'] = "Not found";
$L['setup_found_writable'] = "Found and writable";
$L['setup_found_notwritable'] = "Found, but protected from writing";
$L['setup_notfound_folderwritable'] = "Not found, but the folder is writable";
$L['setup_notfound_foldernotwritable'] = "Not found, and the folder is protected from writing";

// DB
$L['setup_db_title'] = "Database Connection";
$L['setup_db_desc'] = "Specify connection parameters for your MySQL server.";
$L['setup_db_host'] = "DB Host";
$L['setup_db_host_hint'] = "Almost always 'localhost'";
$L['setup_db_name'] = "Database Name";
$L['setup_db_user'] = "DB User";
$L['setup_db_password'] = "DB Password";
$L['setup_db_prefix'] = "Table Prefix";
$L['setup_db_prefix_hint'] = "Normally 'sed_' (do not change unless sure)";
$L['setup_db_clear'] = "Clear database before import";
$L['setup_db_clear_hint'] = "Warning: all tables in the specified DB will be dropped!";
$L['setup_test_connection'] = "Test Connection";
$L['setup_testing'] = "Testing...";
$L['setup_db_connected'] = "Connection established! MySQL version: %s";
$L['setup_check_credentials'] = "Connection failed. Please check your credentials.";

// Settings
$L['setup_settings_title'] = "Site Settings";
$L['setup_default_skin'] = "Default Skin";
$L['setup_default_lang'] = "Default Language";
$L['setup_admin_account'] = "Superadministrator Account";
$L['setup_admin_name'] = "Username";
$L['setup_admin_pass'] = "Password";
$L['setup_admin_email'] = "Email Address";
$L['setup_admin_country'] = "Country";
$L['setup_generate_password'] = "Generate";
$L['setup_password_copied'] = "Password copied to clipboard!";
$L['setup_password_min'] = "Minimum 8 characters";
$L['setup_ownaccount_name'] = "Your username in the system";
$L['setup_least8chars'] = "At least 8 characters";
$L['setup_doublecheck'] = "Double-check, it is important!";

// Extensions
$L['setup_extensions_title'] = "Modules & Plugins selection";
$L['setup_tab_modules'] = "Modules";
$L['setup_tab_plugins'] = "Plugins";
$L['setup_locked_module'] = "Required";
$L['setup_select_all'] = "Select all";
$L['setup_deselect_all'] = "Deselect all";
$L['setup_optional_modules'] = "Select modules for your website:";
$L['setup_optional_plugins'] = "Select additional plugins:";
$L['setup_no_modules'] = "No modules found in /modules/.";
$L['setup_no_plugins'] = "No plugins found in /plugins/.";

// Installation
$L['setup_install'] = "Install";
$L['setup_installing_title'] = "Installation Process";
$L['setup_connected_to_db'] = "Connecting to the database";
$L['setup_config_created'] = "Writing datas/config.php configuration file";
$L['setup_tables_created'] = "Creating database tables";
$L['setup_config_loaded'] = "Importing default configurations";
$L['setup_admin_created'] = "Creating administrator account";
$L['setup_complete'] = "Setup successfully completed";
$L['setup_success_title'] = "Seditio installed successfully!";
$L['setup_success_desc'] = "Your website is fully ready. Please delete the setup files for security reasons!";
$L['setup_go_home'] = "Go to Homepage";
$L['setup_go_admin'] = "Administration Panel";

// Errors
$L['setup_error_db_connection'] = "Cannot connect to the database.";
$L['setup_error_config_write'] = "Error: could not write datas/config.php. Check permissions.";
$L['setup_error_field_required'] = "This field is required.";
$L['setup_error_notwrite'] = "Error, cannot write, please check CHMOD permissions.";
$L['setup_wrong_manual'] = "Something went wrong. A detailed manual setup guide is available <a href=\"https://seditio.org/doc/\" target=\"_blank\">here</a>.";
$L['setup_title'] = "Seditio — Installation";
$L['setup_database_cleared'] = "Database successfully cleared";
$L['setup_tables_dropped'] = "table(s) dropped";
$L['setup_config_size'] = "Configuration file size: %s bytes.";
$L['setup_plugin_skipped'] = "skipped (required module %s is not installed)";
