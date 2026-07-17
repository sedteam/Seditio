<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/i18n/i18n.admin.structure.delete.done.php
Version=186
Updated=2026-jul-09
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=i18n
Part=admin.structure.delete.done
File=i18n.admin.structure.delete.done
Hooks=admin.page.structure.delete.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $db_i18n_structure;

// $c is available in function scope of sed_structure_delcat
if (!empty($c)) {
	sed_sql_query("DELETE FROM $db_i18n_structure WHERE ist_structure_code = '" . sed_sql_prep($c) . "'");
}
