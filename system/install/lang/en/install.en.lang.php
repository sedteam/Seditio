<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=install.en.lang.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=English installation lang file
[END_SED]
==================== */

$L['install_step0'] = "Select language installation";
$L['install_step1'] = "Compatibility";
$L['install_step2'] = "Configuration file";
$L['install_step3'] = "MySQL database";
$L['install_step4'] = "Plugins";
$L['install_step5'] = "Done";

$L['install_language installation'] = "Language installation";
$L['install_select_language installation'] = "Select the installation language";
$L['install_title'] = "Seditio - Installation";
$L['install_build_config'] = "Building the configuration file ";
$L['install_looks_chmod'] = "Looks like a success, silently trying to CHMOD the file as read-only...";
$L['install_setting_mysql'] = "Setting up the SQL database...";
$L['install_creating_mysql'] = "Creating the SQL tables...";
$L['install_presettings'] = "Pre-setting the configuration entries...";
$L['install_adding_administrator'] = "Adding the administrator account...";
$L['install_done'] = "Done.";
$L['install_contine_toplugins'] = "Continue to the plugins";
$L['install_error_notwrite'] = "Error, could not write the file, please check that this file is writable.";
$L['install_now'] = "Install now";
$L['install_plugins'] = "Plugins";
$L['install_install'] = "Install";
$L['install_optional_plugins'] = "Here you can install plugins and get new features.<br /> They are optional, \n
           and you can always change them later, from the administration panel, tab 'Plugins'.<br /> \n
           If you don't know exactly what does what, just leave the checkboxes as-is.<br />";
$L['install_installing_plugins'] = "Installing the plugins :";
$L['install_installed_plugins'] = "plugins installed (";
$L['install_display_log'] = "Display the log";
$L['install_contine_homepage'] = "Continue to the home page";
$L['install_error'] = "Error !";
$L['install_wrong_manual'] = "Something went wrong, you'll have to manually setup the engine, the steps are detailled <a href=\"http://www.neocrome.net/page.php?al=install\">here</a>.";
$L['install_database_setup'] = "SQL database setup :";
$L['install_database_hosturl'] = "Database host URL :";
$L['install_always_localhost'] = "It's almost always 'localhost'";
$L['install_database_user'] = "Database user :";
$L['install_see_yourhosting'] = "See in your hosting control panel for this";
$L['install_database_password'] = "Database password :";
$L['install_database_name'] = "Database name :";
$L['install_database_tableprefix'] = "Database table prefix :";
$L['install_seditio_already'] = "Don't change, unless you already have a Seditio in this database";
$L['install_input_mode'] = "Input mode for text areas :";
$L['install_html_mode'] = "<strong>HTML</strong> (recommended)<br /> \n
           The textareas for pages, posts in forums and private messages are natively handled as HTML code.<br />\n
           A WYSIWYG HTML editor will be automatically installed.";
$L['install_bbcode_mode'] = "<strong>BBcode</strong><br />
          The textareas for pages, posts in forums and private messages are handled as raw text and [bbcode] tags.<br />\n
          An inline BBcode editor will be automatically installed.";
$L['install_skinandlang'] = "Skin and language :";
$L['install_default_skin'] = "Default skin :<br />(Skins files are stored in the folder /skins/...";
$L['install_default_lang'] = "Default language :";
$L['install_admin_account'] = "Administrator account :";
$L['install_account_name'] = "Account name :";
$L['install_ownaccount_name'] = "Your own account name";
$L['install_password'] = "Password :";
$L['install_least8chars'] = "At least 8 chars";
$L['install_email'] = "Email :";
$L['install_doublecheck'] = "Double-check, it's important!";
$L['install_country'] = "Country :";
$L['install_validate'] = "Validate";
$L['install_auto_installer'] = "This is the auto-installer for Seditio (build " . @$cfg['version'] . ")";
$L['install_create_configfile'] = "It will create the configuration file <strong>" . @$cfg['config_file'] . "</strong>, \n
	         then will create and populate the tables in your MySQL database.<br /> \n
	         Prior to running this tool, you have to create the database itself with your hosting panel, \n
	         and all the PHP and system files have to be uploaded on your web host.<br />&nbsp<br /> \n
	         In case something goes wrong during the installation process, delete the file <strong>" . @$cfg['config_file'] . "</strong> with your FTP client, and re-open the web root URL in your browser.<br />&nbsp<br /> \n
	         Right now, CHMOD 0777 any folder listed below that is not already writable, with your FTP client :<br />";
$L['install_folder'] = "Folder";
$L['install_writable'] = "Writable";
$L['install_not_writable'] = "Not writable";
$L['install_not_found'] = "Not found";
$L['install_file'] = "File";
$L['install_found_writable'] = "Found and writable";
$L['install_found_notwritable'] = "Found, Not writable";
$L['install_notfound_folderwritable'] = "Not found, and the folder is writable, so should be ok.";
$L['install_notfound_foldernotwritable'] = "Not found, and the folder is not writable";
$L['install_phpversion'] = "PHP version :";
$L['install_ok'] = "Ok";
$L['install_too_old'] = "Too old";
$L['install_mysql_extension'] = "MySQL extension :";
$L['install_gd_extension'] = "GD extension :";
$L['install_mysqli_extension'] = "MySQLi extension :";
$L['install_mysql_connector'] = "MySQL connector driver :";
$L['install_mysql_preffered'] = "Most preferred MySQLi extension";
$L['install_available'] = "Available";
$L['install_missing'] = "Missing ?";
$L['install_refresh'] = "Refresh";
$L['install_nextstep'] = "Next step";
