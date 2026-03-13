<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.page.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks.page.tags
Hooks=page.tags
Order=15
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (isset($cfg['plugin']['thanks']['page_on']) && $cfg['plugin']['thanks']['page_on'] == 0) {
	$t->assign("PAGE_THANKS_DISPLAY", '');
	return;
}

$allowthanks = true;
$ext = 'page';
$item = (int)$pag['page_id'];
$to_userid = (int)$pag['page_ownerid'];

$t->assign("PAGE_THANKS_DISPLAY", sed_build_thanks($ext, $item, $to_userid, $allowthanks));
