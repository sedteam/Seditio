<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ipsearch/ipsearch.php
Version=179
Updated=2022-aug-03
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
  "IPSEARCH_FORM_SEND" => sed_url('admin', 'm=tools&p=ipsearch&a=search&' . sed_xg()),
  "IPSEARCH_FORM_IPFIELD" => sed_textbox('id', $id, 16, 16)
));

if ($a == 'search') {
  sed_check_xg();
  $id_g = sed_import('id', 'G', 'TXT', 15);
  $id_p = sed_import('id', 'P', 'TXT', 15);

  if (!empty($id_g)) {
    $id = $id_g;
  } else {
    $id = $id_p;
  }

  $userip = explode(".", $id);
  if (count($userip) != 4 || mb_strlen($userip[0]) > 3 || mb_strlen($userip[1]) > 3 || mb_strlen($userip[2]) > 3 || mb_strlen($userip[3]) > 3) {
    sed_die();
  }

  $ipmask1 = $userip[0] . "." . $userip[1] . "." . $userip[2] . "." . $userip[3];
  $ipmask2 = $userip[0] . "." . $userip[1] . "." . $userip[2];
  $ipmask3 = $userip[0] . "." . $userip[1];

  $res_host = @gethostbyaddr($id);
  $res_dns = ($res_host == $id) ? 'Unknown' : $res_host;

  $sql = sed_sql_query("SELECT user_id, user_name, user_lastip FROM $db_users WHERE user_lastip = '$ipmask1' ");
  $totalmatches1 = sed_sql_numrows($sql);

  while ($row = sed_sql_fetchassoc($sql)) {
    $ipx->assign(array(
      "IPSEARCH_IPMASK1" => sed_build_user($row['user_id'], sed_cc($row['user_name'])),
      "IPSEARCH_LASTIP_IPMASK1" => sed_build_ipsearch($row['user_lastip'])
    ));
    $ipx->parse("IPSEARCH.IPSEARCH_RESULTS.IPSEARCH_IPMASK1");
  }

  $sql = sed_sql_query("SELECT user_id, user_name, user_lastip FROM $db_users WHERE user_lastip LIKE '$ipmask2.%' ");
  $totalmatches2 = sed_sql_numrows($sql);

  while ($row = sed_sql_fetchassoc($sql)) {
    $ipx->assign(array(
      "IPSEARCH_IPMASK2" => sed_build_user($row['user_id'], sed_cc($row['user_name'])),
      "IPSEARCH_LASTIP_IPMASK2" => sed_build_ipsearch($row['user_lastip'])
    ));
    $ipx->parse("IPSEARCH.IPSEARCH_RESULTS.IPSEARCH_IPMASK2");
  }

  $sql = sed_sql_query("SELECT user_id, user_name, user_lastip FROM $db_users WHERE user_lastip LIKE '$ipmask3.%.%' ");
  $totalmatches3 = sed_sql_numrows($sql);

  while ($row = sed_sql_fetchassoc($sql)) {
    $ipx->assign(array(
      "IPSEARCH_IPMASK3" => sed_build_user($row['user_id'], sed_cc($row['user_name'])),
      "IPSEARCH_LASTIP_IPMASK3" => sed_build_ipsearch($row['user_lastip'])
    ));
    $ipx->parse("IPSEARCH.IPSEARCH_RESULTS.IPSEARCH_IPMASK3");
  }

  $ipx->assign(array(
    "IPSEARCH_RESULT_DNS" => $res_dns,
    "IPSEARCH_RESULT_TOTALMATCHES1" => $totalmatches1,
    "IPSEARCH_RESULT_IPMASK1" => $ipmask1,
    "IPSEARCH_RESULT_TOTALMATCHES2" => $totalmatches2,
    "IPSEARCH_RESULT_IPMASK2" => $ipmask2,
    "IPSEARCH_RESULT_TOTALMATCHES3" => $totalmatches3,
    "IPSEARCH_RESULT_IPMASK3" => $ipmask3
  ));
  $ipx->parse("IPSEARCH.IPSEARCH_RESULTS");
}

$ipx->parse("IPSEARCH");
$plugin_body = $ipx->text("IPSEARCH");
