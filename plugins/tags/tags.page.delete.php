<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.page.delete.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=main
File=tags.page.delete
Hooks=page.edit.update.first
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$rpagedelete_check = sed_import('rpagedelete', 'P', 'BOL');

if ($rpagedelete_check && (int)$id > 0) {
	sed_tag_remove_all((int)$id, 'pages');
}
