<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ipsearch/ipsearch.php
Version=180
Updated=2025-jan-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ipsearch
Part=admin
File=ipsearch.admin
Hooks=tools
Tags=
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

$ipx = new XTemplate(SED_ROOT . '/plugins/ipsearch/ipsearch.tpl');

$ipx->assign(array(
  "IPSEARCH_FORM_SEND" => sed_url('admin', 'm=manage&p=ipsearch&a=search&' . sed_xg()),
  "IPSEARCH_FORM_IPFIELD" => sed_textbox('id', $id, 45, 45)
));

if ($a == 'search') {
  sed_check_xg();
  $id_g = sed_import('id', 'G', 'TXT', 45);
  $id_p = sed_import('id', 'P', 'TXT', 45);

  if (!empty($id_g)) {
    $id = $id_g;
  } else {
    $id = $id_p;
  }

  // Determine if the IP is IPv4 or IPv6
  if (strpos($id, ':') !== false) {
    // Handle IPv6
    $userip = explode(':', $id);
    $ipmasks = [
      $id,                                                 // Full IPv6 address
      implode(':', array_slice($userip, 0, 7)) . ':*',     // First 7 groups
      implode(':', array_slice($userip, 0, 6)) . ':*:*',   // First 6 groups
      implode(':', array_slice($userip, 0, 5)) . ':*:*:*', // First 5 groups
    ];
  } else {
    // Handle IPv4
    $userip = explode('.', $id);
    if (count($userip) != 4 || mb_strlen($userip[0]) > 3 || mb_strlen($userip[1]) > 3 || mb_strlen($userip[2]) > 3 || mb_strlen($userip[3]) > 3) {
      sed_die();
    }
    $ipmasks = [
      $id,                                                 // Full IPv4 address
      $userip[0] . "." . $userip[1] . "." . $userip[2],    // First 3 groups
      $userip[0] . "." . $userip[1],                       // First 2 groups
    ];
  }

  $res_host = @gethostbyaddr($id);
  $res_dns = ($res_host == $id) ? 'Unknown' : $res_host;

  foreach ($ipmasks as $index => $ipmask) {
    $sql = sed_sql_query("SELECT user_id, user_name, user_lastip FROM $db_users WHERE user_lastip LIKE '$ipmask%' ");
    $totalmatches = sed_sql_numrows($sql);

    while ($row = sed_sql_fetchassoc($sql)) {
      $ipx->assign(array(
        "IPSEARCH_IPMASK" => sed_build_user($row['user_id'], sed_cc($row['user_name'])),
        "IPSEARCH_LASTIP_IPMASK" => sed_build_ipsearch($row['user_lastip'])
      ));
      $ipx->parse("IPSEARCH.IPSEARCH_RESULTS.IPSEARCH_IPMASK.IPSEARCH_IPMASK_RESULTS");
    }

    $ipx->assign(array(
      "IPSEARCH_RESULT_TOTALMATCHES" => $totalmatches,
      "IPSEARCH_RESULT_IPMASK" => $ipmask
    ));

    $ipx->parse("IPSEARCH.IPSEARCH_RESULTS.IPSEARCH_IPMASK");
  }

  $ipx->assign(array(
    "IPSEARCH_RESULT_DNS" => $res_dns
  ));

  $ipx->parse("IPSEARCH.IPSEARCH_RESULTS");
}

$ipx->parse("IPSEARCH");
$plugin_body = $ipx->text("IPSEARCH");
