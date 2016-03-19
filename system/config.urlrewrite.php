<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.seditio.org
[BEGIN_SED]
File=system/config.urlrewrite.php
Version=177
Updated=2013-sep-26
Type=Core
Author=Seditio Team
Description=Url rewriting config
[END_SED]
==================== */

$sed_urlrewrite = array(
    
    /*  Viewer rewriting */
    array(
         'cond' => '#^/view/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'view.php?v=$1'
    ),
    
    /*  RSS rewriting */
    array(
         'cond' => '#^/rss/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'rss.php?m=$1'
    ),
    array(
         'cond' => '#^/rss(/?)$#',
         'rule' => 'rss.php'
    ),
    
    /*  Poll rewriting */
    array(
         'cond' => '#^/polls/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'polls.php?id=$1'
    ),
    
    /*  Gallery rewriting */
    array(
         'cond' => '#^/gallery/pic/([0-9]+)(/?)$#',
         'rule' => 'gallery.php?id=$1'
    ),
    array(
         'cond' => '#^/gallery/([0-9]+)(/?)$#',
         'rule' => 'gallery.php?f=$1'
    ),    
    array(
         'cond' => '#^/gallery(/?)$#',
         'rule' => 'gallery.php'
    ),  
    
    /*  PFS rewriting */
    array(
         'cond' => '#^/pfs/([0-9]+)(/?)$#',
         'rule' => 'pfs.php?f=$1'
    ),
    array(
         'cond' => '#^/pfs(/?)$#',
         'rule' => 'pfs.php'
    ),     
    
    /*  Pm rewriting */
    array(
         'cond' => '#^/pm/mess/([0-9]+)(/?)$#',
         'rule' => 'pm.php?id=$1'
    ), 
    array(
         'cond' => '#^/pm/action/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'pm.php?m=$1'
    ),                         
    array(
         'cond' => '#^/pm/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'pm.php?f=$1'
    ),  
    array(
         'cond' => '#^/pm(/?)$#',
         'rule' => 'pm.php'
    ),
    
    /*  Forums rewriting */
    array(
         'cond' => '#^/forums/topics/([0-9]+)(/?)$#',
         'rule' => 'forums.php?m=topics&s=$1'
    ), 
    array(
         'cond' => '#^/forums/posts/([0-9]+)(/?)$#',
         'rule' => 'forums.php?m=posts&q=$1'
    ),
    array(
         'cond' => '#^/forums/post/([0-9]+)(/?)$#',
         'rule' => 'forums.php?m=posts&p=$1'
    ),
    array(
         'cond' => '#^/forums/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'forums.php?c=$1'
    ),
    array(
         'cond' => '#^/forums(/?)$#',
         'rule' => 'forums.php'
    ), 
    
    /*  Plugins rewriting */   
    array(
         'cond' => '#^/contact(/?)$#',
         'rule' => 'plug.php?e=contact'
    ),   
    array(
         'cond' => '#^/whosonline(/?)$#',
         'rule' => 'plug.php?e=whosonline'
    ),   
    array(
         'cond' => '#^/passrecover(/?)$#',
         'rule' => 'plug.php?e=passrecover'
    ), 
    array(
         'cond' => '#^/plug/([a-zA-Z0-9_-]+)(/?)$#',
         'rule' => 'plug.php?e=$1'
    ), 
    array(
         'cond' => '#^/plug(/?)$#',
         'rule' => 'plug.php'
    ),    
    
    /*  Admin area rewriting */   
    array(
         'cond' => '#^/admin/([a-zA-Z0-9_-]+)(/?)$#',
         'rule' => 'admin.php?m=$1'
    ),
    array(
         'cond' => '#^/admin(/?)$#',
         'rule' => 'admin.php'
    ),
    
    /*  Users rewriting */
    array(
         'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
         'rule' => 'users.php?f=$1&s=$2&w=$3'
    ),
    array(
         'cond' => '#^/users/filter/([a-zA-Z0-9_-]+)(/?)$#',
         'rule' => 'users.php?f=$1'
    ),
    array(
         'cond' => '#^/users/group/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
         'rule' => 'users.php?f=all&gm=$1&s=$2&w=$3'
    ),
    array(
         'cond' => '#^/users/group/([0-9]+)(/?)$#',
         'rule' => 'users.php?gm=$1'
    ),
    array(
         'cond' => '#^/users/maingroup/([0-9]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
         'rule' => 'users.php?f=all&g=$1&s=$2&w=$3'
    ), 
    array(
         'cond' => '#^/users/maingroup/([0-9]+)(/?)$#',
         'rule' => 'users.php?g=$1'
    ),
    array(
         'cond' => '#^/users/([a-zA-Z]+)/([a-zA-Z]+)(/?)$#',
         'rule' => 'users.php?m=$1&a=$2'
    ),  		   
    array(
         'cond' => '#^/users/([a-zA-Z]+)/([0-9]+)(/?)$#',
         'rule' => 'users.php?m=$1&id=$2'
    ),      
    array(
         'cond' => '#^/users/([a-zA-Z]+)(/?)$#',
         'rule' => 'users.php?m=$1'
    ),    
    array(
         'cond' => '#^/users(/?)$#',
         'rule' => 'users.php'
    ),
    array(
         'cond' => '#^/register(/?)$#',
         'rule' => 'users.php?m=register'
    ),
    array(
         'cond' => '#^/login(/?)$#',
         'rule' => 'users.php?m=auth'
    ),
    
    /*  Messages rewriting */
    array(
         'cond' => '#^/message/([0-9]+)/([a-zA-Z0-9]+)(/?)$#',
         'rule' => 'message.php?msg=$1&redirect=$2'
    ), 
    array(
         'cond' => '#^/message/([0-9]+)(/?)$#',
         'rule' => 'message.php?msg=$1'
    ),                 
    
    /*  Lists rewriting */
	  array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+/%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
         'rule' => 'list.php?c=$2&s=$3&w=$4' 
    ),
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+%]+)/sort/([a-zA-Z]+)-(asc|desc)(/?)$#',
         'rule' => 'list.php?c=$1&s=$2&w=$3' 
    ),    
                     
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+/%]+)/$#',
         'rule' => 'list.php?c=$2'
    ),
    array(
         /* If you will not use the system pages set  #^/([a-zA-Z0-9_\-\+%]+)(/?)$#  */
         'cond' => '#^/([a-zA-Z0-9_\-\+%]+)/$#', 
         'rule' => 'list.php?c=$1'
         ),     
    
    /*  Pages rewriting */
    array(
         'cond' => '#^/page/([a-zA-Z]+)(/?)$#',
         'rule' => 'page.php?m=$1'
    ),
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)/download(/?)$#',
         'rule' => 'page.php?id=$2&a=dl'
    ),
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)/download(/?)$#',
         'rule' => 'page.php?al=$2&a=dl'
    ), 
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)/comments(/?)$#',
         'rule' => 'page.php?id=$2&comments=1'
    ),                  
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)/comments(/?)$#',
         'rule' => 'page.php?al=$2&comments=1'
    ),
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([0-9]+)$#',
         'rule' => 'page.php?id=$2'
    ),                   
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)$#',
         'rule' => 'page.php?al=$2'          
    ),
    
    /* For "system" pages */
    array(
         'cond' => '#^/([0-9]+)$#',
         'rule' => 'page.php?id=$1'
    ),                   
    array(
         'cond' => '#^/([a-zA-Z0-9_\-\+%]+)$#',
         'rule' => 'page.php?al=$1'          
    ),
    /*------------------*/
         
    /*  Index rewriting */
    array(
         'cond' => '#^/$#',
         'rule' => 'index.php'                
    )
);
                 

?>