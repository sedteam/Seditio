<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/jevix/jevix.import.filter.php
Version=175
Updated=2012-may-22
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=jevix
Part=jeviximport
File=jevix.import.filter
Hooks=import.filter
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

global $usr, $location;

require_once('plugins/jevix/inc/jevix.class.php');

// Use XHTML ?
$use_xhtml = ($cfg['plugin']['jevix']['use_xhtml'] == "yes") ? true : false;  

// Use for Administrators ?
$use_admin = (($cfg['plugin']['jevix']['use_for_admin'] == "no") && ($usr['maingrp'] == 5)) ? false : true;

$v = jevix($v, $use_xhtml, $use_admin);



?>
