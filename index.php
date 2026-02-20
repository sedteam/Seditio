<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=index.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=SEF Url's loader
[END_SED]
==================== */

define('SED_CODE', TRUE);
define('SED_ROOT', dirname(__FILE__));

/* Load URL rewrite rules from cache or system config */
$sed_urls_cache = SED_ROOT . '/datas/cache/sed_urls.php';
if (file_exists($sed_urls_cache)) {
  require($sed_urls_cache);
} else {
  require(SED_ROOT . '/system/config.urlrewrite.php');
}

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
  $m = preg_replace('/[^a-zA-Z0-9]/', '', $module);

  if (!empty($m)) {
    /* Redirect old list links to page module (list merged into page) */
    if ($m == 'list') {
      $vars_req['module'] = 'page';
      $new_query = http_build_query($vars_req);
      header('Location: ' . $subdir_uri . $request_uri . '?' . $new_query, true, 301);
      exit;
    }

    $path_modules = SED_ROOT . "/modules/" . $m . "/" . $m . ".php";

    if (file_exists($path_modules)) {
      include_once($path_modules);
      die();
    }

    if ($m == 'install') {
      $path_core = SED_ROOT . "/system/install/install.php";
    } else {
      $path_core = SED_ROOT . "/system/core/" . $m . "/" . $m . ".php";
    }

    if (file_exists($path_core)) {
      include_once($path_core);
      die();
    }
  }
}

if (str_replace("/", "", $request_uri) == "index.php") {
  header("Location: /", TRUE, 301);
  exit();
}

header("HTTP/1.1 404 Not Found");
exit;
