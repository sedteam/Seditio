<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/comments/comments.list.loop.php
Version=185
Type=Plugin
Description=Comments row data for list (variant B, list.loop)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=comments
Part=main
File=comments.list.loop
Hooks=list.loop
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$pcomments = (!empty($cfg['plugin']['comments']['showcommentsonpage'])) ? "" : "&comments=1";
$pag['page_pageurlcom'] = (empty($pag['page_alias'])) ? sed_url("page", "id=" . $pag['page_id'] . $pcomments) : sed_url("page", "al=" . $pag['page_alias'] . $pcomments);
$pag['page_comcount'] = (!$pag['page_comcount']) ? "0" : $pag['page_comcount'];
$pag['page_comments'] = sed_link($pag['page_pageurlcom'], $out['ic_comment'] . " (" . $pag['page_comcount'] . ")");

$t->assign(array(
	"LIST_ROW_COMMENTS" => $pag['page_comments'],
	"LIST_ROW_COMCOUNT" => $pag['page_comcount'],
	"LIST_ROW_COMURL" => $pag['page_pageurlcom']
));
