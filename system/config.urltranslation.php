<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/config.urlrewrite.php
Version=180
Updated=2025-jan-25
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

/*  RSS translation */

$sed_urltrans['rss'] = array(
      array(
            'params' => 'm=*',
            'rewrite' => 'rss/{m}'
      ),
      array(
            'params' => '',
            'rewrite' => 'rss'
      )
);

/*  Sitemap translation */

$sed_urltrans['sitemap'] = array(
      array(
            'params' => 'm=*',
            'rewrite' => 'sitemap/{m}.xml'
      ),
      array(
            'params' => '',
            'rewrite' => 'sitemap.xml'
      )
);

/*  Polls translation */

$sed_urltrans['polls'] = array(
      array(
            'params' => 'id=*',
            'rewrite' => 'polls/{id}'
      ),
      array(
            'params' => '',
            'rewrite' => 'polls'
      )
);

/*  Gallery translation */

$sed_urltrans['gallery'] = array(
      /**/
      array(
            'params' => 'f=*',
            'rewrite' => 'gallery/{f}'
      ),
      /**/
      array(
            'params' => 'id=*',
            'rewrite' => 'gallery/pic/{id}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'gallery'
      )
);

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

/*  Pm translation    */

$sed_urltrans['pm'] = array(
      /**/
      array(
            'params' => 'id=*',
            'rewrite' => 'pm/mess/{id}'
      ),
      /**/
      array(
            'params' => 'm=*',
            'rewrite' => 'pm/action/{m}'
      ),
      /**/
      array(
            'params' => 'f=*',
            'rewrite' => 'pm/{f}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'pm'
      )
);

/*  Forums translation */

$sed_urltrans['forums'] = array(
      /**/
      array(
            'params' => 'm=topics&s=*',
            'rewrite' => 'forums/topics/{s}'
      ),
      /**/
      array(
            'params' => 'm=posts&q=*',
            'rewrite' => 'forums/posts/{q}'
      ),
      /**/
      array(
            'params' => 'm=posts&p=*',
            'rewrite' => 'forums/post/{p}'
      ),
      /**/
      array(
            'params' => 'c=*',
            'rewrite' => 'forums/{c}'
      ),
      /**/
      array(
            'params' => '',
            'rewrite' => 'forums'
      )
);

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

/*  Lists translation */

$sed_urltrans['list'] = array(
      array(
            'params' => 'c=all&s=*&w=*',
            'rewrite' => 'all/sort/{s}-{w}/'
      ),
      array(
            'params' => 'c=*&s=*&w=*',
            'rewrite' => '{sed_get_listpath()}sort/{s}-{w}/'
      ),
      array(
            'params' => 'c=all',
            'rewrite' => 'all/'
      ),
      array(
            'params' => 'c=*',
            'rewrite' => '{sed_get_listpath()}'
      ),
      array(
            'params' => '',
            'rewrite' => 'list'
      )
);

/*  Pages translation */

$sed_urltrans['page'] = array(
      /* 1 */
      array(
            'params' => 'm=*',
            'rewrite' => 'page/{m}'
      ),
      /* 2 */
      array(
            'params' => 'id=*&a=dl',
            'rewrite' => '{sed_get_pagepath()}{id}/download'
      ),
      /* 3 */
      array(
            'params' => 'al=*&a=dl',
            'rewrite' => '{sed_get_pagepath()}{al}/download'
      ),
      /* 4 */
      /*   array( 
          'params' => 'id=*&comments=1', 
          'rewrite' => '{sed_get_pagepath()}{id}/comments'
    ),
    /* 5 */
      /*    array( 
          'params' => 'al=*&comments=1', 
          'rewrite' => '{sed_get_pagepath()}{al}/comments'
    ),
    /* 6 */
      array(
            'params' => 'id=*',
            'rewrite' => '{sed_get_pagepath()}{id}'
      ),
      /* 7 */
      array(
            'params' => 'al=*',
            'rewrite' => '{sed_get_pagepath()}{al}'
      ),
      array(
            'params' => '',
            'rewrite' => 'page'
      )
);

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

/*  Callback functions */

function sed_get_pagepath(&$args, &$section)
{
      global $sys, $sed_cat;
      $url = "";
      if (isset($sys['catcode']) && $sys['catcode'] != "system") {
            $cpath = $sed_cat[$sys['catcode']]['path'];
            $cpath_arr = explode('.', $cpath);
            foreach ($cpath_arr as $a) {
                  $url .= urlencode($a) . "/";
            }
      }
      return $url;
}

function sed_get_listpath(&$args, &$section)
{
      global $sed_cat;
      $url = '';
      $cpath = $sed_cat[$args['c']]['path'];
      $cpath_arr = explode('.', $cpath);
      foreach ($cpath_arr as $a) {
            $url .= urlencode($a) . "/";
      }
      unset($args['c']);
      return $url;
}

function sed_get_section(&$args, &$section)
{
      return $section;
}
