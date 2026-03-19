<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/tags.ajax.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=AjaxTags
File=tags.ajax
Hooks=ajax
Order=10
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$q_input = sed_import('q', 'G', 'TXT');
if (empty($q_input)) {
	$q_input = sed_import('query', 'G', 'TXT');
}

$result = array('suggestions' => array());

if (!empty($q_input)) {
	$tags = sed_tag_complete($q_input, 20);
	if (is_array($tags)) {
		foreach ($tags as $tag) {
			$result['suggestions'][] = array('value' => sed_tag_title($tag));
		}
	}
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($result);
exit;
