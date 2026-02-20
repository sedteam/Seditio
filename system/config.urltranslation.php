<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/config.urltranslation.php
Version=185
Updated=2026-feb-14
Type=Core
Author=Seditio Team
Description=Url translation config
[END_SED]
==================== */

/*  Installation translation */

$sed_urltrans['install'] = array(
      array(
            'params' => '',
            'rewrite' => 'install'
      )
);

/*  External URL redirect translation */

$sed_urltrans['go'] = array(
      array(
            'params' => 'url=*',
            'rewrite' => 'go/?url={url}'
      )
);

/* RSS and Sitemap translation: see modules/rss/rss.urls.php and modules/sitemap/sitemap.urls.php when modules are active */

/* Polls translation: see modules/polls/polls.urls.php when module is active */

/* Gallery translation: see modules/gallery/gallery.urls.php when module is active */

/*  PFS translation */

$sed_urltrans['pfs'] = array(
      /**/
      array(
            'params' => 'f=*',
            'rewrite' => 'pfs/{f}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'pfs'
      )
);

/* Pm translation: see modules/pm/pm.urls.php when module is active */

/*  Plugins translation */

$sed_urltrans['plug'] = array(
      /**/
      array(
            'params' => 'e=contact',
            'rewrite' => 'contact'
      ),
      /**/
      array(
            'params' => 'e=whosonline',
            'rewrite' => 'whosonline'
      ),
      /**/
      array(
            'params' => 'e=passrecover',
            'rewrite' => 'passrecover'
      ),
      /**/
      array(
            'params' => 'e=*',
            'rewrite' => 'plug/{e}'
      ),
      array(
            'params' => '',
            'rewrite' => 'plug'
      )
);

/*  Admin area translation */

$sed_urltrans['admin'] = array(
      /**/
      array(
            'params' => 'm=*',
            'rewrite' => 'admin/{m}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'admin'
      )
);

/*  Users translation */

$sed_urltrans['users'] = array(
      /**/
      array(
            'params' => 'f=all&s=*&w=*&gm=*',
            'rewrite' => 'users/group/{gm}/sort/{s}-{w}'
      ),
      /**/
      array(
            'params' => 'gm=*',
            'rewrite' => 'users/group/{gm}'
      ),
      /**/
      array(
            'params' => 'f=all&s=*&w=*&g=*',
            'rewrite' => 'users/maingroup/{g}/sort/{s}-{w}'
      ),
      /**/
      array(
            'params' => 'f=*&s=*&w=*',
            'rewrite' => 'users/filter/{f}/sort/{s}-{w}'
      ),
      /**/
      array(
            'params' => 'f=*',
            'rewrite' => 'users/filter/{f}'
      ),
      /**/
      array(
            'params' => 'g=*',
            'rewrite' => 'users/maingroup/{g}'
      ),
      /**/
      array(
            'params' => 'm=*&a=*',
            'rewrite' => 'users/{m}/{a}'
      ),
      /**/
      array(
            'params' => 'm=*&id=*',
            'rewrite' => 'users/{m}/{id}'
      ),
      /**/
      array(
            'params' => 'm=auth',
            'rewrite' => 'login'
      ),
      array(
            'params' => 'm=register',
            'rewrite' => 'register'
      ),
      array(
            'params' => 'm=*',
            'rewrite' => 'users/{m}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'users'
      )
);

/*  Messages translation */

$sed_urltrans['message'] = array(
      /**/
      array(
            'params' => 'msg=*&redirect=*',
            'rewrite' => 'message/{msg}/{redirect}'
      ),
      /**/
      array(
            'params' => 'msg=*',
            'rewrite' => 'message/{msg}'
      ),
      array(
            'params' => '',
            'rewrite' => 'message'
      )
);

/*  List and page translation: see modules/page/page.urls.php when module is active */

/*  Index translation */

$sed_urltrans['index'] = array(
      array(
            'params' => '',
            'rewrite' => '/'
      )
);

/*  Default translation */

$sed_urltrans['*'] = array(
      array(
            'params' => '*',
            'rewrite' => 'index.php?module={sed_get_section()}'
      )
);

/* Callback functions sed_get_pagepath, sed_get_listpath, sed_get_section are in system/functions.php; sed_get_forums_urltrans in modules/forums/inc/forums.functions.php */
