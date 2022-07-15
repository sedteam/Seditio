<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/skineditor/skineditor.php
Version=179
Updated=2022-jul-15
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=skineditor
Part=common
File=skineditor.common
Hooks=common.tool.skineditor
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

define('SED_DISABLE_XFORM', TRUE);


?>
