<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/thanks.users.details.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=thanks
Part=main
File=thanks.users.details.tags
Hooks=users.details.tags
Order=15
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$user_id = (int)$urr['user_id'];
$thanks_count = thanks_user_thanks_count($user_id);

if ($thanks_count > 0) {
	$t->assign(array(
		"USERS_DETAILS_THANKS_COUNT" => $thanks_count,
		"USERS_DETAILS_THANKS_URL" => sed_url('plug', 'e=thanks&user=' . $user_id)
	));
	$t->parse("MAIN.USERS_DETAILS_THANKS");
}
