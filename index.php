<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=index.php
Version=180
Updated=2021-jun-15
Type=Core
Author=Seditio Team
Description=SEF Url's loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_ROOT', dirname(__FILE__));

require(SED_ROOT . '/system/config.urlrewrite.php');

$request_uri = $_SERVER['REQUEST_URI'];

if (preg_match('/\/{2,}$/', $request_uri)) {
  $new_uri = preg_replace('/\/{2,}$/', '/', $request_uri);
  header('Location: ' . $new_uri, true, 301);
  exit;
}

$subdir_uri = (mb_strlen(dirname($_SERVER['PHP_SELF'])) > 1) ? dirname($_SERVER['PHP_SELF']) : "";
$request_uri = str_replace($subdir_uri, "", $request_uri);

$vars_req = array();
$params_req = "";

if (($pos = strpos($request_uri, "?")) !== false) {
  $params_req = mb_substr($request_uri, $pos + 1);
  $request_uri = mb_substr($request_uri, 0, $pos);
  parse_str($params_req, $vars_req);
  $params_req = "&" . urldecode($params_req);
}

foreach ($sed_urlrewrite as $val) {
  if (preg_match($val['cond'], $request_uri)) {
    $url = preg_replace($val['cond'], $val['rule'], $request_uri);

    $url = urldecode($url);

    if (strpos($url, '../') !== false || strpos($url, '..\\') !== false) {
      header("HTTP/1.1 403 Forbidden");
      exit;
    }

    if (($pos = mb_strpos($url, "?")) !== false) {
      $params = mb_substr($url, $pos + 1);
      parse_str($params, $vars);

      $vars += $vars_req;
      $params = $params . $params_req;

      $_GET += $vars;
      $_REQUEST += $vars;
      //$GLOBALS += $vars;
      $_SERVER["QUERY_STRING"] = $QUERY_STRING = $params;
    }

    $pos = mb_strpos($url, ".php");
    $incl = mb_substr($url, 0, $pos + 4);

    include_once(SED_ROOT . '/' . $incl);
    die();
  }
}

$module = @$_GET['module'];

if (!empty($module)) {
  $system_core = array("install", "admin", "captcha", "resizer", "forums", "gallery", "index", "list", "message", "page", "pfs", "plug", "pm", "polls", "rss", "sitemap", "users", "view");
  if (in_array($module, $system_core)) {
    $system_incl_dir = ($module == "install") ? SED_ROOT . "/system/install/" : SED_ROOT . "/system/core/" . $module . "/";
    include_once($system_incl_dir . $module . ".php");
    die();
  }
}

if (str_replace("/", "", $request_uri) == "index.php") {
  header("Location: /", TRUE, 301);
  exit();
}

header("HTTP/1.1 404 Not Found");
exit;
