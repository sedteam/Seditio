<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/config.urlrewrite.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=Url rewriting config
[END_SED]
==================== */

/**
 * URL rewrite rules use numeric 'order' for processing sequence (lower = earlier).
 * Ranges: 100-199 system (go, ajax, resizer, install, captcha, robots);
 * 200-499 modules (view, rss, sitemap, polls, gallery, pfs, pm, forums);
 * 500-549 plug, admin; 550-599 users, message; 600-649 list; 650-699 page; 700 index.
 * Modules set $mod_urlrewrite_order in their .urls.php. See sed_urls_generate() in functions.php.
 */

$sed_urlrewrite = array(

     /*  External URL redirect rewriting */
     array(
          'order' => 100,
          'cond' => '#^/go/#',
          'rule' => 'system/core/go/go.php'
     ),

     /*  Ajax rewriting */
     array(
          'order' => 110,
          'cond' => '#^/ajax(/?)$#',
          'rule' => 'system/core/ajax/ajax.php'
     ),

     /*  Resizer rewriting */
     array(
          'order' => 120,
          'cond' => '#^/datas/resized/([a-zA-Z0-9_/.-]+\.(?:jpg|jpeg|png|gif|webp))$#',
          'rule' => 'system/core/resizer/resizer.php?file=$1'
     ),

     /*  Installation rewriting */
     array(
          'order' => 130,
          'cond' => '#^/install(/?)$#',
          'rule' => 'system/install/install.php'
     ),

     /*  Captcha rewriting */
     array(
          'order' => 140,
          'cond' => '#^/captcha(/?)$#',
          'rule' => 'system/core/captcha/captcha.php'
     ),
     array(
          'order' => 150,
          'cond' => '#^/captcha.png$#',
          'rule' => 'system/core/captcha/captcha.php'
     ),

     /*  Robots rewriting */
     array(
          'order' => 160,
          'cond' => '#^/robots.txt$#',
          'rule' => 'system/core/plug/plug.php?e=robots'
     ),

     /*  PFS rewriting (launcher in modules/pfs/; rules also in pfs.urls.php when module active) */
     array(
          'order' => 170,
          'cond' => '#^/pfs/([0-9]+)(/?)$#',
          'rule' => 'modules/pfs/pfs.php?f=$1'
     ),
     array(
          'order' => 180,
          'cond' => '#^/pfs(/?)$#',
          'rule' => 'modules/pfs/pfs.php'
     ),

     /*  Plugins rewriting */
     array(
          'order' => 500,
          'cond' => '#^/contact(/?)$#',
          'rule' => 'system/core/plug/plug.php?e=contact'
     ),
     array(
          'order' => 505,
          'cond' => '#^/whosonline(/?)$#',
          'rule' => 'system/core/plug/plug.php?e=whosonline'
     ),
     array(
          'order' => 510,
          'cond' => '#^/passrecover(/?)$#',
          'rule' => 'system/core/plug/plug.php?e=passrecover'
     ),
     array(
          'order' => 515,
          'cond' => '#^/plug/([a-zA-Z0-9_-]+)(/?)$#',
          'rule' => 'system/core/plug/plug.php?e=$1'
     ),
     array(
          'order' => 520,
          'cond' => '#^/plug(/?)$#',
          'rule' => 'system/core/plug/plug.php'
     ),

     /*  Admin area rewriting */
     array(
          'order' => 525,
          'cond' => '#^/admin/([a-zA-Z0-9_-]+)(/?)$#',
          'rule' => 'system/core/admin/admin.php?m=$1'
     ),
     array(
          'order' => 530,
          'cond' => '#^/admin(/?)$#',
          'rule' => 'system/core/admin/admin.php'
     ),

     /* Users rewriting rules moved to modules/users/users.urls.php */

     /*  Messages rewriting */
     array(
          'order' => 595,
          'cond' => '#^/message/([0-9]+)/([a-zA-Z0-9]+)(/?)$#',
          'rule' => 'system/core/message/message.php?msg=$1&redirect=$2'
     ),
     array(
          'order' => 600,
          'cond' => '#^/message/([0-9]+)(/?)$#',
          'rule' => 'system/core/message/message.php?msg=$1'
     ),

     /*  List and page rewriting: see modules/page/page.urls.php when module is active */

     /*  Index rewriting */
     array(
          'order' => 700,
          'cond' => '#^/$#',
          'rule' => 'system/core/index/index.php'
     )
);

/* Sort by order when config is included directly (e.g. no cache). index.php uses only cond/rule. */
if (!function_exists('sed_urlrewrite_sort')) {
	function sed_urlrewrite_sort($a, $b) {
		$oa = isset($a['order']) ? (int)$a['order'] : 500;
		$ob = isset($b['order']) ? (int)$b['order'] : 500;
		return $oa - $ob;
	}
}
usort($sed_urlrewrite, 'sed_urlrewrite_sort');
