<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=forums.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Forums
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$id = sed_import('id', 'G', 'INT');
$s = sed_import('s', 'G', 'INT');
$q = sed_import('q', 'G', 'INT');
$p = sed_import('p', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');
$o = sed_import('o', 'G', 'ALP');
$w = sed_import('w', 'G', 'ALP', 4);
$quote = sed_import('quote', 'G', 'INT');
$poll = sed_import('poll', 'G', 'INT');
$vote = sed_import('vote', 'G', 'INT');

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require_once(SED_ROOT . '/system/core/polls/polls.functions.php');

sed_blockguests();
sed_check_xg();

$sql = sed_sql_query("SELECT * FROM $db_forum_posts WHERE fp_id='$p' and fp_topicid='$q' and fp_sectionid='$s' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	$fp_text = $row['fp_text'];
	$fp_posterid = $row['fp_posterid'];
	$fp_postername = $row['fp_postername'];
	$fp_sectionid = $row['fp_sectionid'];
	$fp_topicid = $row['fp_topicid'];
	$fp_updated = $row['fp_updated'];
	$fp_updater = $row['fp_updater'];

	list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('forums', $s);

	if (!$usr['isadmin'] && $fp_posterid != $usr['id']) {
		sed_log("Attempt to edit a post without rights", 'sec');
		sed_die();
	}
	sed_block($usr['auth_read']);
} else {
	sed_die();
}

$sql = sed_sql_query("SELECT fs_state, fs_title, fs_category FROM $db_forum_sections WHERE fs_id='$s' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	if ($row['fs_state']) {
		sed_redirect(sed_url('message', 'msg=602', '', true));
	}
	$fs_title = $row['fs_title'];
	$fs_category = $row['fs_category'];
} else {
	sed_die();
}

$sql = sed_sql_query("SELECT ft_state, ft_mode, ft_title, ft_desc, ft_poll FROM $db_forum_topics WHERE ft_id = '$q' LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	if ($row['ft_state'] && !$usr['isadmin']) {
		sed_redirect(sed_url('message', 'msg=603', '', true));
	}
	$ft_title = sed_cc($row['ft_title']);
	$ft_desc = sed_cc($row['ft_desc']);
	$ft_poll = $row['ft_poll'];
	$ft_fulltitle = ($row['ft_mode'] == 1) ? "# " . $ft_title : $ft_title;
	$sys['sublocation'] = 'q' . $q;
} else {
	sed_die();
}

if ($a == 'update') {
	/* === Hook === */
	$extp = sed_getextplugins('forums.editpost.update.first');
	if (is_array($extp)) {
		foreach ($extp as $k => $pl) {
			include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
		}
	}
	/* ===== */

	$rmsg = sed_import('rmsg', 'P', 'HTM');
	$rtopictitle = sed_import('rtopictitle', 'P', 'TXT', 64);
	$rtopicdesc = sed_import('rtopicdesc', 'P', 'TXT', 64);
	$rupdater = ($fp_posterid == $usr['id'] && ($sys['now_offset'] < $fp_updated + 300) && empty($fp_updater)) ? '' : $usr['name'];

	$error_string .= ((!empty($rtopictitle)) && mb_strlen($rtopictitle) < 2) ? $L['for_titletooshort'] . "<br />" : '';
	$error_string .= (mb_strlen($rmsg) < 2) ? $L['for_msgtooshort'] . "<br />" : '';

	if (empty($error_string)) {
		if (!empty($rmsg)) {
			$sql = sed_sql_query("UPDATE $db_forum_posts SET fp_text = '" . sed_sql_prep($rmsg) . "', fp_updated = '" . $sys['now_offset'] . "', fp_updater = '" . sed_sql_prep($rupdater) . "' WHERE fp_id = '$p'");
		}
		$is_topic = false;
		if (!empty($rtopictitle)) {
			$sql = sed_sql_query("SELECT fp_id FROM $db_forum_posts WHERE fp_topicid = '$q' ORDER BY fp_id ASC LIMIT 1");
			if ($row = sed_sql_fetchassoc($sql)) {
				$fp_idp = $row['fp_id'];
				if ($fp_idp == $p) {
					if (mb_substr($rtopictitle, 0, 1) == "#") {
						$rtopictitle = str_replace('#', '', $rtopictitle);
					}
					$sql = sed_sql_query("UPDATE $db_forum_topics SET ft_title = '" . sed_sql_prep($rtopictitle) . "', ft_desc = '" . sed_sql_prep($rtopicdesc) . "' WHERE ft_id = '$q'");
					$is_topic = true;
					if ($ft_poll > 0) {
						sed_poll_editsave($ft_poll);
					}
				}
			}
		}

		/* === Hook === */
		$extp = sed_getextplugins('forums.editpost.update.done');
		if (is_array($extp)) {
			foreach ($extp as $k => $pl) {
				include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
			}
		}
		/* ===== */

		sed_forum_sectionsetlast($fp_sectionid);
		sed_redirect(sed_url('forums', 'm=posts&p=' . $p, '#' . $p, true));
	}
}

$sql = sed_sql_query("SELECT fp_id FROM $db_forum_posts WHERE fp_topicid='$q' ORDER BY fp_id ASC LIMIT 1");

if ($row = sed_sql_fetchassoc($sql)) {
	$fp_idp = $row['fp_id'];
	$firstpost = ($fp_idp == $p) ? TRUE : FALSE;
}

$pfs = sed_build_pfs($usr['id'], 'editpost', 'rmsg', $L['Mypfs']);
$pfs .= (sed_auth('pfs', 'a', 'A')) ? " &nbsp; " . sed_build_pfs(0, "editpost", "rmsg", $L['SFS']) : '';
$morejavascript .= sed_build_addtxt('editpost', 'rmsg');

$toptitle = sed_link(sed_url("forums"), $L['Forums']) . $cfg['separator'] . " " . sed_build_forums($s, $fs_title, $fs_category) . " " . $cfg['separator'] . " " . sed_link(sed_url("forums", "m=posts&p=" . $p, "#" . $p), $ft_fulltitle) . " ";
$toptitle .= $cfg['separator'] . " " . sed_link(sed_url("forums", "m=editpost&s=" . $s . "&q=" . $q . "&p=" . $p . "&" . sed_xg()), $L['Edit']);
$toptitle .= ($usr['isadmin']) ? " *" : '';

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("forums")] = $L['Forums'];
sed_build_forums_bc($s, $fs_title, $fs_category);
$urlpaths[sed_url("forums", "m=editpost&s=" . $s . "&q=" . $q . "&p=" . $p . "&" . sed_xg())] = $L['Edit'];

$sys['sublocation'] = $fs_title;
$out['subtitle'] = $L['Forums'];

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('forumstitle', $title_tags, $title_data);
/**/

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile(array('forums', 'editpost', $fs_category, $fp_sectionid));
$t = new XTemplate($mskin);

if (!empty($error_string)) {
	$t->assign("FORUMS_EDITPOST_ERROR_BODY", sed_alert($error_string, 'e'));
	$t->parse("MAIN.FORUMS_EDITPOST_ERROR");
}

if (($ft_poll > 0) && !$cfg['disable_polls']) {
	sed_poll_edit("MAIN.FORUMS", $ft_poll);
}

$t->assign(array(
	"FORUMS_EDITPOST_PAGETITLE" => $toptitle,
	"FORUMS_EDITPOST_SHORTTITLE" => $fs_title . " - " . $L['Edit'],
	"FORUMS_EDITPOST_SUBTITLE" => "#" . $fp_posterid . " " . $fp_postername . " - " . sed_build_date($cfg['dateformat'], $fp_updated) . " " . $usr['timetext'],
	"FORUMS_EDITPOST_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"FORUMS_EDITPOST_SEND" => sed_url("forums", "m=editpost&a=update&s=" . $s . "&q=" . $q . "&p=" . $p . "&" . sed_xg()),
	"FORUMS_EDITPOST_TEXT" => sed_textarea('rmsg', $fp_text, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Basic') . " " . $pfs,
	"FORUMS_EDITPOST_TEXTONLY" => sed_textarea('rmsg', $fp_text, $cfg['textarea_default_height'], $cfg['textarea_default_width'], 'Basic'),
	"FORUMS_EDITPOST_TITLE" => sed_textbox('rtopictitle', $ft_title, 56, 64),
	"FORUMS_EDITPOST_DESC" => sed_textbox('rtopicdesc', $ft_desc, 56, 64),
	"FORUMS_EDITPOST_MYPFS" => $pfs
));

if ($firstpost) {
	$t->parse("MAIN.FORUMS_EDITPOST_FIRST");
}

/* === Hook === */
$extp = sed_getextplugins('forums.editpost.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");
