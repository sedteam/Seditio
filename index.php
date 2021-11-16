<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=index.php
Version=178
Updated=2021-jun-15
Type=Core
Author=Seditio Team
Description=SEF Url's loader
[END_SED]
==================== */

define('SED_CODE', TRUE);

require('system/config.urlrewrite.php');

$request_uri = $_SERVER['REQUEST_URI'];

$subdir_uri = (mb_strlen(dirname($_SERVER['PHP_SELF'])) > 1) ? dirname($_SERVER['PHP_SELF']) : "";
$request_uri = str_replace($subdir_uri, "", $request_uri);

$vars_req = array();
$params_req = "";

if (($pos = strpos($request_uri, "?")) !== false) 
{
    $params_req = mb_substr($request_uri, $pos+1); 
    $request_uri = mb_substr($request_uri, 0, $pos);
    parse_str($params_req, $vars_req);
    $params_req = "&".urldecode($params_req);   
}

foreach($sed_urlrewrite as $val)
{
    if(preg_match($val['cond'], $request_uri))
    {         
      $url = preg_replace($val['cond'], $val['rule'], $request_uri);         
      
      $url = urldecode($url);
              
      if (($pos = mb_strpos($url, "?")) !== false)
       {
          $params = mb_substr($url, $pos+1);
          parse_str($params, $vars);
          
          $vars += $vars_req;
          $params = $params.$params_req;          
                            
          $_GET += $vars;          
          $_REQUEST += $vars;
          //$GLOBALS += $vars;
          $_SERVER["QUERY_STRING"] = $QUERY_STRING = $params;
       }        
    
      $pos = mb_strpos($url, ".php");
      $incl = mb_substr($url, 0, $pos+4);   
     
      include_once($incl);
      die();
    }
}

/**/
/* For old urls */
$incl_php = str_replace("/", "", $request_uri);
$system_core = array("install", "admin", "captcha", "resizer", "forums", "gallery", "index", "list", "message", "page", "pfs", "plug", "pm", "polls", "rss", "sitemap", "users", "view");
$pos = mb_strpos($incl_php, ".php");

if ($pos > 0) {
	$incl = mb_substr($incl_php, 0, $pos);  
	if (in_array($incl, $system_core)) {
		$system_incl_dir = ($incl == "install") ? "system/install/" : "system/core/".$incl."/";
		include_once($system_incl_dir.$incl_php);
		die();
	}
}

/**/

header("HTTP/1.1 404 Not Found");
//header("Location: ".$subdir_uri."/message/404");
exit; 
   
?>
