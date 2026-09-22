<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/core/admin/admin.footer.php
Version=186
Updated=2026-sep-21
Type=Core
Author=Seditio Team
Description=Global admin footer
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* === Hook === */
foreach (sed_getextplugins('footer.first') as $pl) {
	include $pl;
}
/* ===== */

$out['bottomline'] = ($cfg['keepcrbottom']) ? $out['copyright'] : '';

/* ======== Shield protection ======== */

if ($cfg['shieldenabled']) {
	$sql = sed_sql_query("INSERT INTO $db_shield (shield_ip, shield_lastseen, shield_hammer, shield_limit, shield_action) 
		VALUES ('" . $usr['ip'] . "', " . (int)$sys['now'] . ", " . (int)$shield_hammer . ", 0, '') 
		ON DUPLICATE KEY UPDATE shield_lastseen = " . (int)$sys['now'] . ", shield_hammer = " . (int)$shield_hammer);
}

/* === Hook === */
foreach (sed_getextplugins('footer.main') as $pl) {
	include $pl;
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
foreach (sed_getextplugins('footer.tags') as $pl) {
	include $pl;
}
/* ===== */

$i = explode(' ', microtime());
$sys['endtime'] = $i[1] + $i[0];
$sys['creationtime'] = round(($sys['endtime'] - $sys['starttime']), 3);

/* ========================================================
#
#  Seditio is distributed completely free of charge under the BSD License.
#  Developing and supporting this CMS takes a lot of time and effort.
#  Keeping the copyright and support link in the footer is your simple way
#  to help promote and grow the project. Please do not remove it, thanks!
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
	$out['devmode'] .= "<li><a href=\"" . $sys['request_uri'] . "#tab105\" class=\"selected\">Stats</a></li>";
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
		$out['devmode'] .= "<td class=\"coltop\">Dependencies</td>";
		$out['devmode'] .= "</tr>";

		foreach ($sys['devmode']['hooks'] as $k => $pl) {
			$deps_str = sed_parse_pl_dependencies(isset($pl['pl_dependencies']) ? $pl['pl_dependencies'] : null);
			$dir = !empty($pl['pl_module']) ? 'modules' : 'plugins';
			$out['devmode'] .= "<tr><td>" . $pl['pl_id'] . "</td>";
			$out['devmode'] .= "<td>" . sed_cc($pl['pl_hook']) . "</td>";
			$out['devmode'] .= "<td>" . sed_cc($pl['pl_code']) . "</td>";
			$out['devmode'] .= "<td>" . sed_cc($pl['pl_part']) . "</td>";
			$out['devmode'] .= "<td>" . sed_cc($pl['pl_title']) . "</td>";
			$out['devmode'] .= "<td>" . $dir . "/" . $pl['pl_code'] . "/" . $pl['pl_file'] . ".php</td>";
			$out['devmode'] .= "<td>" . $pl['pl_order'] . "</td>";
			$out['devmode'] .= "<td>" . sed_cc($deps_str) . "</td>";
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
	$out['devmode'] .= "</div><div id=\"tab105\" class=\"tabs\">";
	$out['devmode'] .= "<h4>Stats :</h4>";

	$time_php = max(0, $sys['creationtime'] - $sys['tcount']);
	$time_php_pct = ($sys['creationtime'] > 0) ? round(($time_php / $sys['creationtime']) * 100, 1) : 0;
	$time_sql_pct = ($sys['creationtime'] > 0) ? round(($sys['tcount'] / $sys['creationtime']) * 100, 1) : 0;

	$mem_usage = memory_get_usage();
	$mem_peak = memory_get_peak_usage();
	$mem_limit = ini_get('memory_limit');

	$included_files = get_included_files();
	$inc_count = count($included_files);
	$inc_total_size = 0;

	$opcache_status = (extension_loaded('Zend OPcache') && ini_get('opcache.enable')) ? 'Enabled' : 'Disabled';

	$groups = array(
		'Core'            => array(),
		'Plugins'         => array(),
		'Languages'       => array(),
		'Skins'           => array(),
		'Configs & Cache' => array(),
		'Other'           => array()
	);

	$group_sizes = array(
		'Core'            => 0,
		'Plugins'         => 0,
		'Languages'       => 0,
		'Skins'           => 0,
		'Configs & Cache' => 0,
		'Other'           => 0
	);

	$root_raw = defined('SED_ROOT') ? realpath(SED_ROOT) : realpath('./');
	$root_dir = $root_raw ? str_replace('\\', '/', $root_raw) : '';
	$root_len = strlen($root_dir);

	foreach ($included_files as $idx => $inc_file) {
		$norm_file = str_replace('\\', '/', $inc_file);
		if (!empty($root_dir) && substr($norm_file, 0, $root_len) === $root_dir) {
			$rel_file = ltrim(substr($norm_file, $root_len), '/');
		} else {
			$rel_file = $norm_file;
		}
		$fsize = @filesize($inc_file);
		$fsize_val = $fsize ? $fsize : 0;
		$inc_total_size += $fsize_val;

		if (preg_match('#(^|/)lang/|\.lang\.php$#i', $rel_file)) {
			$grp = 'Languages';
		} elseif (strpos($rel_file, 'skins/') === 0 || strpos($rel_file, 'system/adminskin/') === 0) {
			$grp = 'Skins';
		} elseif (strpos($rel_file, 'plugins/') === 0) {
			$grp = 'Plugins';
		} elseif (strpos($rel_file, 'datas/') === 0) {
			$grp = 'Configs & Cache';
		} elseif (strpos($rel_file, 'system/') === 0 || strpos($rel_file, '/') === false) {
			$grp = 'Core';
		} else {
			$grp = 'Other';
		}

		$groups[$grp][] = array(
			'num'  => $idx + 1,
			'file' => $rel_file,
			'size' => $fsize_val
		);
		$group_sizes[$grp] += $fsize_val;
	}

	$breakdown_parts = array();
	foreach ($groups as $grp_name => $files) {
		if (count($files) > 0) {
			$breakdown_parts[] = "[" . $grp_name . ": " . count($files) . "]";
		}
	}
	$breakdown_str = implode(' ', $breakdown_parts);

	$out['devmode'] .= "<table class=\"cells hovered\" style=\"margin-bottom:15px;\">";
	$out['devmode'] .= "<tr><td class=\"coltop\" colspan=\"2\">Summary</td></tr>";
	$out['devmode'] .= "<tr><td style=\"width:25%;\"><strong>Execution Time</strong></td>";
	$out['devmode'] .= "<td>" . sprintf("%.3f", round($sys['creationtime'], 3)) . " s (PHP: " . sprintf("%.3f", round($time_php, 3)) . " s / " . $time_php_pct . "%, SQL: " . sprintf("%.3f", round($sys['tcount'], 3)) . " s / " . $time_sql_pct . "%)</td></tr>";
	$out['devmode'] .= "<tr><td><strong>Memory Usage</strong></td>";
	$out['devmode'] .= "<td>" . sed_format_size($mem_usage, array('precision' => 2)) . " (Peak: " . sed_format_size($mem_peak, array('precision' => 2)) . ", Limit: " . $mem_limit . ")</td></tr>";
	$out['devmode'] .= "<tr><td><strong>Environment</strong></td>";
	$out['devmode'] .= "<td>PHP " . PHP_VERSION . " (" . PHP_SAPI . ") / OPcache: " . $opcache_status . "</td></tr>";
	$out['devmode'] .= "<tr><td><strong>Included Files</strong></td>";
	$out['devmode'] .= "<td>" . $inc_count . " files (" . sed_format_size($inc_total_size, array('precision' => 1)) . ") &nbsp; " . $breakdown_str . "</td></tr>";
	$out['devmode'] .= "</table>";

	$out['devmode'] .= "<h4>Included files (" . $inc_count . ") :</h4>";
	$out['devmode'] .= "<table class=\"cells hovered\">";
	$out['devmode'] .= "<tr><td class=\"coltop\" style=\"width:5%;\">#</td>";
	$out['devmode'] .= "<td class=\"coltop\">File</td>";
	$out['devmode'] .= "<td class=\"coltop\" style=\"width:15%; text-align:right;\">Size</td></tr>";

	foreach ($groups as $grp_name => $files) {
		if (empty($files)) {
			continue;
		}
		$out['devmode'] .= "<tr><td colspan=\"3\" style=\"font-weight:bold; background:rgba(0,0,0,0.05); padding:6px 8px;\">" . $grp_name . " (" . count($files) . ") &ndash; " . sed_format_size($group_sizes[$grp_name], array('precision' => 1)) . "</td></tr>";
		foreach ($files as $f) {
			$out['devmode'] .= "<tr><td>" . $f['num'] . "</td>";
			$out['devmode'] .= "<td style=\"text-align:left;\">" . sed_cc($f['file']) . "</td>";
			$out['devmode'] .= "<td style=\"text-align:right;\">" . ($f['size'] ? sed_format_size($f['size'], array('precision' => 1)) : '&ndash;') . "</td></tr>";
		}
	}

	$out['devmode'] .= "<tr><td>END</td>";
	$out['devmode'] .= "<td><strong>Tot.: " . $inc_count . " files</strong></td>";
	$out['devmode'] .= "<td style=\"text-align:right;\"><strong>" . sed_format_size($inc_total_size, array('precision' => 1)) . "</strong></td></tr>";
	$out['devmode'] .= "</table>";
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
