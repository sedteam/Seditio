<?php
/* Auto-generated URL cache. Do not edit manually. */
/* Generated: 2026-02-26 19:10:18 */

$sed_urlrewrite = array (
  0 => 
  array (
    'cond' => '#^/go/#',
    'rule' => 'system/core/go/go.php',
  ),
  1 => 
  array (
    'cond' => '#^/ajax(/?)$#',
    'rule' => 'system/core/ajax/ajax.php',
  ),
  2 => 
  array (
    'cond' => '#^/datas/resized/([a-zA-Z0-9_/.-]+\\.(?:jpg|jpeg|png|gif|webp))$#',
    'rule' => 'system/core/resizer/resizer.php?file=$1',
  ),
  3 => 
  array (
    'cond' => '#^/install(/?)$#',
    'rule' => 'system/install/install.php',
  ),
  4 => 
  array (
    'cond' => '#^/captcha(/?)$#',
    'rule' => 'plugins/sedcaptcha/inc/sedcaptcha.php',
  ),
  5 => 
  array (
    'cond' => '#^/captcha\\.png$#',
    'rule' => 'plugins/sedcaptcha/inc/sedcaptcha.php',
  ),
  6 => 
  array (
    'cond' => '#^/robots.txt$#',
    'rule' => 'system/core/plug/plug.php?e=robots',
  ),
  7 => 
  array (
    'cond' => '#^/pfs/([0-9]+)(/?)$#',
    'rule' => 'modules/pfs/pfs.php?f=$1',
  ),
  8 => 
  array (
    'cond' => '#^/pfs(/?)$#',
    'rule' => 'modules/pfs/pfs.php',
  ),
  9 => 
  array (
    'cond' => '#^/view/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/view/view.php?v=$1',
  ),
  10 => 
  array (
    'cond' => '#^/rss/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/rss/rss.php?m=$1',
  ),
  11 => 
  array (
    'cond' => '#^/rss(/?)$#',
    'rule' => 'modules/rss/rss.php',
  ),
  12 => 
  array (
    'cond' => '#^/sitemap_([a-zA-Z0-9]+)\\.xml$#',
    'rule' => 'modules/sitemap/sitemap.php?m=$1',
  ),
  13 => 
  array (
    'cond' => '#^/sitemap\\.xml$#',
    'rule' => 'modules/sitemap/sitemap.php',
  ),
  14 => 
  array (
    'cond' => '#^/polls/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/polls/polls.php?id=$1',
  ),
  15 => 
  array (
    'cond' => '#^/polls(/?)$#',
    'rule' => 'modules/polls/polls.php',
  ),
  16 => 
  array (
    'cond' => '#^/gallery/pic/([0-9]+)(/?)$#',
    'rule' => 'modules/gallery/gallery.php?id=$1',
  ),
  17 => 
  array (
    'cond' => '#^/gallery/([0-9]+)(/?)$#',
    'rule' => 'modules/gallery/gallery.php?f=$1',
  ),
  18 => 
  array (
    'cond' => '#^/gallery(/?)$#',
    'rule' => 'modules/gallery/gallery.php',
  ),
  19 => 
  array (
    'cond' => '#^/pm/mess/([0-9]+)(/?)$#',
    'rule' => 'modules/pm/pm.php?id=$1',
  ),
  20 => 
  array (
    'cond' => '#^/pm/action/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/pm/pm.php?m=$1',
  ),
  21 => 
  array (
    'cond' => '#^/pm/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/pm/pm.php?f=$1',
  ),
  22 => 
  array (
    'cond' => '#^/pm(/?)$#',
    'rule' => 'modules/pm/pm.php',
  ),
  23 => 
  array (
    'cond' => '#^/forums/topics/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=topics&s=$1&al=$2',
  ),
  24 => 
  array (
    'cond' => '#^/forums/topics/([0-9]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=topics&s=$1',
  ),
  25 => 
  array (
    'cond' => '#^/forums/posts/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=posts&q=$1&al=$2',
  ),
  26 => 
  array (
    'cond' => '#^/forums/posts/([0-9]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=posts&q=$1',
  ),
  27 => 
  array (
    'cond' => '#^/forums/post/([0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=posts&p=$1&al=$2',
  ),
  28 => 
  array (
    'cond' => '#^/forums/post/([0-9]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?m=posts&p=$1',
  ),
  29 => 
  array (
    'cond' => '#^/forums/([a-zA-Z0-9]+)-([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?c=$1&al=$2',
  ),
  30 => 
  array (
    'cond' => '#^/forums/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'modules/forums/forums.php?c=$1',
  ),
  31 => 
  array (
    'cond' => '#^/forums(/?)$#',
    'rule' => 'modules/forums/forums.php',
  ),
  32 => 
  array (
    'cond' => '#^/contact(/?)$#',
    'rule' => 'system/core/plug/plug.php?e=contact',
  ),
  33 => 
  array (
    'cond' => '#^/whosonline(/?)$#',
    'rule' => 'system/core/plug/plug.php?e=whosonline',
  ),
  34 => 
  array (
    'cond' => '#^/passrecover(/?)$#',
    'rule' => 'system/core/plug/plug.php?e=passrecover',
  ),
  35 => 
  array (
    'cond' => '#^/plug/([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'system/core/plug/plug.php?e=$1',
  ),
  36 => 
  array (
    'cond' => '#^/plug(/?)$#',
    'rule' => 'system/core/plug/plug.php',
  ),
  37 => 
  array (
    'cond' => '#^/admin/([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'system/core/admin/admin.php?m=$1',
  ),
  38 => 
  array (
    'cond' => '#^/admin(/?)$#',
    'rule' => 'system/core/admin/admin.php',
  ),
  39 => 
  array (
    'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
    'rule' => 'modules/users/users.php?f=$1&s=$2&w=$3',
  ),
  40 => 
  array (
    'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)(/?)$#',
    'rule' => 'modules/users/users.php?f=$1',
  ),
  41 => 
  array (
    'cond' => '#^/users/group/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
    'rule' => 'modules/users/users.php?f=all&gm=$1&s=$2&w=$3',
  ),
  42 => 
  array (
    'cond' => '#^/users/group/([0-9]+)(/?)$#',
    'rule' => 'modules/users/users.php?gm=$1',
  ),
  43 => 
  array (
    'cond' => '#^/users/maingroup/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
    'rule' => 'modules/users/users.php?f=all&g=$1&s=$2&w=$3',
  ),
  44 => 
  array (
    'cond' => '#^/users/maingroup/([0-9]+)(/?)$#',
    'rule' => 'modules/users/users.php?g=$1',
  ),
  45 => 
  array (
    'cond' => '#^/users/([a-zA-Z]+)/([a-zA-Z]+)(/?)$#',
    'rule' => 'modules/users/users.php?m=$1&a=$2',
  ),
  46 => 
  array (
    'cond' => '#^/users/([a-zA-Z]+)/([0-9]+)(/?)$#',
    'rule' => 'modules/users/users.php?m=$1&id=$2',
  ),
  47 => 
  array (
    'cond' => '#^/users/([a-zA-Z]+)(/?)$#',
    'rule' => 'modules/users/users.php?m=$1',
  ),
  48 => 
  array (
    'cond' => '#^/users(/?)$#',
    'rule' => 'modules/users/users.php',
  ),
  49 => 
  array (
    'cond' => '#^/register(/?)$#',
    'rule' => 'modules/users/users.php?m=register',
  ),
  50 => 
  array (
    'cond' => '#^/login(/?)$#',
    'rule' => 'modules/users/users.php?m=auth',
  ),
  51 => 
  array (
    'cond' => '#^/message/([0-9]+)/([a-zA-Z0-9]+)(/?)$#',
    'rule' => 'system/core/message/message.php?msg=$1&redirect=$2',
  ),
  52 => 
  array (
    'cond' => '#^/message/([0-9]+)(/?)$#',
    'rule' => 'system/core/message/message.php?msg=$1',
  ),
  53 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([a-zA-Z0-9_\\-\\+/%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
    'rule' => 'modules/page/page.php?c=$2&s=$3&w=$4',
  ),
  54 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
    'rule' => 'modules/page/page.php?c=$1&s=$2&w=$3',
  ),
  55 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([a-zA-Z0-9_\\-\\+/%]+)/$#',
    'rule' => 'modules/page/page.php?c=$2',
  ),
  56 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+%]+)/$#',
    'rule' => 'modules/page/page.php?c=$1',
  ),
  57 => 
  array (
    'cond' => '#^/page/([a-zA-Z]+)(/?)$#',
    'rule' => 'modules/page/page.php?m=$1',
  ),
  58 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([0-9]+)/download(/?)$#',
    'rule' => 'modules/page/page.php?id=$2&a=dl',
  ),
  59 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([a-zA-Z0-9_\\-\\+%]+)/download(/?)$#',
    'rule' => 'modules/page/page.php?al=$2&a=dl',
  ),
  60 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([0-9]+)/comments(/?)$#',
    'rule' => 'modules/page/page.php?id=$2&comments=1',
  ),
  61 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([a-zA-Z0-9_\\-\\+%]+)/comments(/?)$#',
    'rule' => 'modules/page/page.php?al=$2&comments=1',
  ),
  62 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([0-9]+)$#',
    'rule' => 'modules/page/page.php?id=$2',
  ),
  63 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+/%]+)/([a-zA-Z0-9_\\-\\+%]+)$#',
    'rule' => 'modules/page/page.php?al=$2',
  ),
  64 => 
  array (
    'cond' => '#^/([0-9]+)$#',
    'rule' => 'modules/page/page.php?id=$1',
  ),
  65 => 
  array (
    'cond' => '#^/([a-zA-Z0-9_\\-\\+%]+)$#',
    'rule' => 'modules/page/page.php?al=$1',
  ),
  66 => 
  array (
    'cond' => '#^/$#',
    'rule' => 'system/core/index/index.php',
  ),
);

$sed_urltrans = array (
  'install' => 
  array (
    0 => 
    array (
      'params' => '',
      'rewrite' => 'install',
    ),
  ),
  'go' => 
  array (
    0 => 
    array (
      'params' => 'url=*',
      'rewrite' => 'go/?url={url}',
    ),
  ),
  'plug' => 
  array (
    0 => 
    array (
      'params' => 'e=whosonline',
      'rewrite' => 'whosonline',
    ),
    1 => 
    array (
      'params' => 'e=robots',
      'rewrite' => 'robots.txt',
    ),
    2 => 
    array (
      'params' => 'e=passrecover',
      'rewrite' => 'passrecover',
    ),
    3 => 
    array (
      'params' => 'e=contact',
      'rewrite' => 'contact',
    ),
    4 => 
    array (
      'params' => 'e=*',
      'rewrite' => 'plug/{e}',
    ),
    5 => 
    array (
      'params' => '',
      'rewrite' => 'plug',
    ),
  ),
  'admin' => 
  array (
    0 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'admin/{m}',
    ),
    1 => 
    array (
      'params' => '',
      'rewrite' => 'admin',
    ),
  ),
  'message' => 
  array (
    0 => 
    array (
      'params' => 'msg=*&redirect=*',
      'rewrite' => 'message/{msg}/{redirect}',
    ),
    1 => 
    array (
      'params' => 'msg=*',
      'rewrite' => 'message/{msg}',
    ),
    2 => 
    array (
      'params' => '',
      'rewrite' => 'message',
    ),
  ),
  'index' => 
  array (
    0 => 
    array (
      'params' => '',
      'rewrite' => '/',
    ),
  ),
  '*' => 
  array (
    0 => 
    array (
      'params' => '*',
      'rewrite' => 'index.php?module={sed_get_section()}',
    ),
  ),
  'forums' => 
  array (
    0 => 
    array (
      'params' => 'm=topics&s=*&al=*',
      'rewrite' => 'forums/topics/{s}{al|sed_get_forums_urltrans}',
    ),
    1 => 
    array (
      'params' => 'm=topics&s=*',
      'rewrite' => 'forums/topics/{s}',
    ),
    2 => 
    array (
      'params' => 'm=posts&q=*&al=*',
      'rewrite' => 'forums/posts/{q}{al|sed_get_forums_urltrans}',
    ),
    3 => 
    array (
      'params' => 'm=posts&q=*',
      'rewrite' => 'forums/posts/{q}',
    ),
    4 => 
    array (
      'params' => 'm=posts&p=*&al=*',
      'rewrite' => 'forums/post/{p}{al|sed_get_forums_urltrans}',
    ),
    5 => 
    array (
      'params' => 'm=posts&p=*',
      'rewrite' => 'forums/post/{p}',
    ),
    6 => 
    array (
      'params' => 'c=*&al=*',
      'rewrite' => 'forums/{c}{al|sed_get_forums_urltrans}',
    ),
    7 => 
    array (
      'params' => 'c=*',
      'rewrite' => 'forums/{c}',
    ),
    8 => 
    array (
      'params' => '',
      'rewrite' => 'forums',
    ),
  ),
  'page' => 
  array (
    0 => 
    array (
      'params' => 'c=all&s=*&w=*',
      'rewrite' => 'all/sort/{s}-{w}/',
    ),
    1 => 
    array (
      'params' => 'c=*&s=*&w=*',
      'rewrite' => '{sed_get_listpath()}sort/{s}-{w}/',
    ),
    2 => 
    array (
      'params' => 'c=all',
      'rewrite' => 'all/',
    ),
    3 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'page/{m}',
    ),
    4 => 
    array (
      'params' => 'c=*',
      'rewrite' => '{sed_get_listpath()}',
    ),
    5 => 
    array (
      'params' => 'id=*&a=dl',
      'rewrite' => '{sed_get_pagepath()}{id}/download',
    ),
    6 => 
    array (
      'params' => 'al=*&a=dl',
      'rewrite' => '{sed_get_pagepath()}{al}/download',
    ),
    7 => 
    array (
      'params' => 'id=*',
      'rewrite' => '{sed_get_pagepath()}{id}',
    ),
    8 => 
    array (
      'params' => 'al=*',
      'rewrite' => '{sed_get_pagepath()}{al}',
    ),
    9 => 
    array (
      'params' => '',
      'rewrite' => 'page',
    ),
  ),
  'pfs' => 
  array (
    0 => 
    array (
      'params' => 'f=*',
      'rewrite' => 'pfs/{f}',
    ),
    1 => 
    array (
      'params' => '',
      'rewrite' => 'pfs',
    ),
  ),
  'pm' => 
  array (
    0 => 
    array (
      'params' => 'id=*',
      'rewrite' => 'pm/mess/{id}',
    ),
    1 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'pm/action/{m}',
    ),
    2 => 
    array (
      'params' => 'f=*',
      'rewrite' => 'pm/{f}',
    ),
    3 => 
    array (
      'params' => '',
      'rewrite' => 'pm',
    ),
  ),
  'polls' => 
  array (
    0 => 
    array (
      'params' => 'id=*',
      'rewrite' => 'polls/{id}',
    ),
    1 => 
    array (
      'params' => '',
      'rewrite' => 'polls',
    ),
  ),
  'rss' => 
  array (
    0 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'rss/{m}',
    ),
    1 => 
    array (
      'params' => '',
      'rewrite' => 'rss',
    ),
  ),
  'sitemap' => 
  array (
    0 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'sitemap_{m}.xml',
    ),
    1 => 
    array (
      'params' => '',
      'rewrite' => 'sitemap.xml',
    ),
  ),
  'users' => 
  array (
    0 => 
    array (
      'params' => 'f=all&s=*&w=*&gm=*',
      'rewrite' => 'users/group/{gm}/sort/{s}-{w}',
    ),
    1 => 
    array (
      'params' => 'gm=*',
      'rewrite' => 'users/group/{gm}',
    ),
    2 => 
    array (
      'params' => 'f=all&s=*&w=*&g=*',
      'rewrite' => 'users/maingroup/{g}/sort/{s}-{w}',
    ),
    3 => 
    array (
      'params' => 'f=*&s=*&w=*',
      'rewrite' => 'users/filter/{f}/sort/{s}-{w}',
    ),
    4 => 
    array (
      'params' => 'f=*',
      'rewrite' => 'users/filter/{f}',
    ),
    5 => 
    array (
      'params' => 'g=*',
      'rewrite' => 'users/maingroup/{g}',
    ),
    6 => 
    array (
      'params' => 'm=auth&a=*',
      'rewrite' => 'users/auth/{a}',
    ),
    7 => 
    array (
      'params' => 'm=register&a=*',
      'rewrite' => 'users/register/{a}',
    ),
    8 => 
    array (
      'params' => 'm=*&a=*',
      'rewrite' => 'users/{m}/{a}',
    ),
    9 => 
    array (
      'params' => 'm=details&id=*',
      'rewrite' => 'users/details/{id}',
    ),
    10 => 
    array (
      'params' => 'm=edit&id=*',
      'rewrite' => 'users/edit/{id}',
    ),
    11 => 
    array (
      'params' => 'm=*&id=*',
      'rewrite' => 'users/{m}/{id}',
    ),
    12 => 
    array (
      'params' => 'm=auth',
      'rewrite' => 'login',
    ),
    13 => 
    array (
      'params' => 'm=register',
      'rewrite' => 'register',
    ),
    14 => 
    array (
      'params' => 'm=*',
      'rewrite' => 'users/{m}',
    ),
    15 => 
    array (
      'params' => '',
      'rewrite' => 'users',
    ),
  ),
  'gallery' => 
  array (
    0 => 
    array (
      'params' => 'f=*',
      'rewrite' => 'gallery/{f}',
    ),
    1 => 
    array (
      'params' => 'id=*',
      'rewrite' => 'gallery/pic/{id}',
    ),
    2 => 
    array (
      'params' => '',
      'rewrite' => 'gallery',
    ),
  ),
);
