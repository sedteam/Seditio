<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/footer.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Global footer
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* === Hook === */
$extp = sed_getextplugins('footer.first');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$out['bottomline'] = ($cfg['keepcrbottom']) ? $out['copyright'] : '';

/* ======== Who's online (part 2) ======== */

if (!$cfg['disablewhosonline']) {
	if ($usr['id'] > 0) {
		$sql = sed_sql_query("SELECT online_id FROM $db_online WHERE online_userid='" . $usr['id'] . "'");

		if ($row = sed_sql_fetchassoc($sql)) {
			$online_count = 1;
			$sql2 = sed_sql_query("UPDATE $db_online SET online_lastseen='" . $sys['now'] . "', online_location='" . sed_sql_prep($location) . "', online_subloc='" . sed_sql_prep($sys['sublocation']) . "', online_hammer=" . (int)$shield_hammer . " WHERE online_userid='" . $usr['id'] . "'");
		} else {
			$sql2 = sed_sql_query("INSERT INTO $db_online (online_ip, online_name, online_lastseen, online_location, online_subloc, online_userid, online_shield, online_hammer) VALUES ('" . $usr['ip'] . "', '" . sed_sql_prep($usr['name']) . "', " . (int)$sys['now'] . ", '" . sed_sql_prep($location) . "',  '" . sed_sql_prep($sys['sublocation']) . "', " . (int)$usr['id'] . ", 0, 0)");
		}
	} else {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_online WHERE online_ip='" . $usr['ip'] . "'");
		$online_count = sed_sql_result($sql, 0, 'COUNT(*)');

		if ($online_count > 0) {
			$sql2 = sed_sql_query("UPDATE $db_online SET online_lastseen='" . $sys['now'] . "', online_location='" . $location . "', online_subloc='" . sed_sql_prep($sys['sublocation']) . "', online_hammer=" . (int)$shield_hammer . " WHERE online_userid = -1 AND online_ip='" . $usr['ip'] . "'");
		} else {
			$sql2 = sed_sql_query("INSERT INTO $db_online (online_ip, online_name, online_lastseen, online_location, online_subloc, online_userid, online_shield, online_hammer) VALUES ('" . $usr['ip'] . "', 'v', " . (int)$sys['now'] . ", '" . $location . "', '" . sed_sql_prep($sys['sublocation']) . "', -1, 0, 0)");
		}
	}
}

/* === Hook === */
$extp = sed_getextplugins('footer.main');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$mskin = sed_skinfile('admin.footer', false, true);

$t = new XTemplate($mskin);

$t->assign(array(
	"FOOTER_BOTTOMLINE" => $out['bottomline'],
	"FOOTER_COPYRIGHT" => $out['copyright'],
	"FOOTER_LOGSTATUS" => $out['logstatus'],
	"FOOTER_PMREMINDER" => isset($out['pmreminder']) ? $out['pmreminder'] : '',
	"FOOTER_ADMINPANEL" => $out['adminpanel'],
	"FOOTER_JAVASCRIPT" => $out['javascript']
));

/* === Hook === */
$extp = sed_getextplugins('footer.tags');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */

$i = explode(' ', microtime());
$sys['endtime'] = $i[1] + $i[0];
$sys['creationtime'] = round(($sys['endtime'] - $sys['starttime']), 3);

/* ========================================================
#
#  If you're not owner of a commercial licence or a copyright removal,
#  please do not delete the copyright line in the footer, thanks.
#  With doing this you'd break the licence agrement for personal users,
#  you probably won't go in jail for this but that's bad bad bad !
#
========================================================== */

$out['creationtime'] = (!$cfg['disablesysinfos']) ? $L['foo_created'] . ' ' . $sys['creationtime'] . ' ' . $L['foo_seconds'] : '';
$out['sqlstatistics'] = ($cfg['showsqlstats']) ? $L['foo_sqltotal'] . ': ' . round($sys['tcount'], 3) . ' ' . $L['foo_seconds'] . ' - ' . $L['foo_sqlqueries'] . ': ' . $sys['qcount'] . ' - ' . $L['foo_sqlaverage'] . ': ' . round(($sys['tcount'] / $sys['qcount']), 5) . ' ' . $L['foo_seconds'] : '';

if ($cfg['devmode'] && sed_auth('admin', 'a', 'A')) {

	$out['devmode'] = "<h2>Dev-mode :</h2>";
	$out['devmode'] .= "<div class=\"sedtabs\" style=\"color:#000; margin:0 0 20px 0\">";
	$out['devmode'] .= "<ul class=\"tabs\">";
	$out['devmode'] .= "<li><a href=\"" . $sys['request_uri'] . "#tab101\" class=\"selected\">Hooks</a></li>";
	$out['devmode'] .= "<li><a href=\"" . $sys['request_uri'] . "#tab102\" class=\"selected\">SQL queries</a></li>";
	$out['devmode'] .= "<li><a href=\"" . $sys['request_uri'] . "#tab103\" class=\"selected\">Auth</a></li>";
	$out['devmode'] .= "<li><a href=\"" . $sys['request_uri'] . "#tab104\" class=\"selected\">" . '$sys' . "</a></li>";
	$out['devmode'] .= "</ul>";
	$out['devmode'] .= "<div class=\"tab-box\">";
	$out['devmode'] .= "<div id=\"tab101\" class=\"tabs\">";
	$out['devmode'] .= "<h4>Hooks :</h4>";

	if (is_array($sys['devmode']['hooks'])) {
		$out['devmode'] .= "<table class=\"cells hovered\"><tr>";
		$out['devmode'] .= "<td class=\"coltop\">#</td><td class=\"coltop\">Hook</td>";
		$out['devmode'] .= "<td class=\"coltop\">Code</td><td class=\"coltop\">Part</td>";
		$out['devmode'] .= "<td class=\"coltop\">Plugin</td><td class=\"coltop\">File</td>";
		$out['devmode'] .= "<td class=\"coltop\">Order</td>";
		$out['devmode'] .= "</tr>";

		foreach ($sys['devmode']['hooks'] as $k => $i) {
			$out['devmode'] .= "<tr><td>" . $i[0] . "</td>";
			$out['devmode'] .= "<td>" . $i[1] . "</td>";
			$out['devmode'] .= "<td>" . $i[2] . "</td>";
			$out['devmode'] .= "<td>" . $i[3] . "</td>";
			$out['devmode'] .= "<td>" . $i[4] . "</td>";
			$out['devmode'] .= "<td>plugins/" . $i[2] . "/" . $i[5] . ".php</td>";
			$out['devmode'] .= "<td>" . $i[6] . "</td>";
			$out['devmode'] .= "</tr>";
		}
		$out['devmode'] .= "</table>";
	} else {
		$out['devmode'] .= "None.";
	}

	$out['devmode'] .= "</div><div id=\"tab102\" class=\"tabs\">";
	$out['devmode'] .= "<h4>SQL queries :</h4>";
	$out['devmode'] .= "<table class=\"cells hovered\"><tr>";
	$out['devmode'] .= "<td class=\"coltop\" style=\"width:10%;\">SQL query</td><td class=\"coltop\" style=\"width:10%;\">SQL Duration</td>";
	$out['devmode'] .= "<td class=\"coltop\" style=\"width:10%;\">Timeline</td><td class=\"coltop\">Query</td></tr>";
	$out['devmode'] .= "<tr><td colspan=\"2\">BEGIN</td>";
	$out['devmode'] .= "<td style=\"text-align:right;\">0.000 ms</td><td>&nbsp;</td></tr>";

	foreach ($sys['devmode']['queries'] as $k => $i) {
		$out['devmode'] .= "<tr><td>#" . $i[0] . " &nbsp;</td>";
		$out['devmode'] .= "<td style=\"text-align:right;\">" . sprintf("%.3f", round($i[1] * 1000, 3)) . " ms</td>";
		$out['devmode'] .= "<td style=\"text-align:right;\">" . sprintf("%.3f", round($sys['devmode']['timeline'][$k] * 1000, 3)) . " ms</td>";
		$out['devmode'] .= "<td style=\"text-align:left;\">" . sed_cc($i[2]) . "</td></tr>";
	}
	$out['devmode'] .= "<tr><td>END</td><td><strong>Tot.: " . sprintf("%.3f", round($sys['tcount'] * 1000, 3)) . "ms</strong></td>";
	$out['devmode'] .= "<td style=\"text-align:right;\"><strong>" . sprintf("%.3f", round($sys['creationtime'] * 1000, 3)) . " ms</strong></td>";
	$out['devmode'] .= "<td><strong>Queries : " . $sys['qcount'] . " ,  Average : " . sprintf("%.3f", round(($sys['tcount'] / $sys['qcount']) * 1000, 3)) . "ms/query</strong>";
	$out['devmode'] .= "</td></tr></table>";

	$out['devmode'] .= "</div><div id=\"tab103\" class=\"tabs\">";
	$out['devmode'] .= "<h4>Auth :</h4>";

	$out['devauth'] = is_array($sys['auth_log']) ? "AUTHLOG: " . implode(', ', $sys['auth_log']) : '';

	$txt_r = ($usr['auth_read']) ? '1' : '0';
	$txt_w = ($usr['auth_write']) ? '1' : '0';
	$txt_a = ($usr['isadmin']) ? '1' : '0';
	$out['devauth'] .= " &nbsp; AUTH_FINAL_RWA:" . $txt_r . $txt_w . $txt_a;
	$out['devmode']	.= $out['devauth'];
	$out['devmode'] .= "</div><div id=\"tab104\" class=\"tabs\">";
	$out['devmode'] .= '<h4>$sys :</h4>';
	$out['devmode'] .= sed_vardump($sys, 'print_r');
	$out['devmode']	.= "</div></div></div>";
}

$t->assign(array(
	"FOOTER_CREATIONTIME" => $out['creationtime'],
	"FOOTER_SQLSTATISTICS" => $out['sqlstatistics'],
	"FOOTER_DEVMODE" => isset($out['devmode']) ? $out['devmode'] : ''
));


if ($usr['id'] > 0) {
	$t->parse("FOOTER.USER");
} else {
	$t->parse("FOOTER.GUEST");
}

$t->parse("FOOTER");
$t->out("FOOTER");

@ob_end_flush();
@ob_end_flush();

sed_sql_close($connection_id);
