<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=skins/minimo/minimo.php
Version=175
Updated=2013-jul-31
Type=Skin
Name=Minimo
Author=Amro
Url=http://www.seditio.org
Description=Official skin
[END_SED]
==================== */

$cfg['pagination'] = '<li>%s</li>';
$cfg['pagination_cur'] = '<li><span>%s</span></li>';
$cfg['pagination_arrowleft'] = "←"; 
$cfg['pagination_arrowright'] = "→";

// ========= Plugin RecentItems ============================= //

$cfg['plu_mask_pages'] = "<span class=\"rec-date\"><i class=\"icons white time\"></i> %3\$s</span>"." "."%1\$s"." ".$cfg['separator']." "."%2\$s"."<br />";
// %1\$s = Link to the category
// %2\$s = Link to the page
// %3\$s = Date

$cfg['plu_mask_topics'] =  "<span class=\"rec-date\"><i class=\"icons white time\"></i> %2\$s</span>"." "."%3\$s"." ".$cfg['separator']." "."%4\$s"." ("."%5\$s".")<br />";
// %1\$s = "Follow" image
// %2\$s = Date
// %3\$s = Section
// %4\$s = Topic title
// %5\$s = Number of replies

$cfg['plu_mask_comments'] = "<span class=\"rec-date\"><i class=\"icons white time\"></i> %3\$s</span> %1\$s"." ".$cfg['separator']." "."<span class=\"rec-comment\">%5\$s</span>"." ".$cfg['separator']." <i class=\"icons grey people\"></i> %2\$s<br />";
// %1\$s = Link to the comment
// %2\$s = Author
// %3\$s = Date
// %4\$s = User Avatar
// %5\$s = Comments Text

$cfg['plu_mask_polls'] =  "<div>%1\$s</div>";

$L['hea_users'] = "<i class=\"icons grey people\"></i> ".$L['hea_users']; 

$L['hea_logout'] = "<i class=\"icons grey share\"></i> ".$L['hea_logout'];
$L['hea_profile'] = "<i class=\"icons grey briefcase\"></i> ".$L['hea_profile'];
$L['hea_private_messages'] = "<i class=\"icons grey envelope\"></i> ".$L['hea_private_messages'];

$L['hea_privatemessages'] = $L['hea_private_messages'] = "<i class=\"icons grey flag\"></i> ".$L['hea_noprivatemessages'];
$L['hea_noprivatemessages'] = $L['hea_private_messages'] = "<i class=\"icons grey envelope\"></i> ".$L['hea_noprivatemessages'];

$L['hea_mypfs'] = "<i class=\"icons grey hdd\"></i> ".$L['hea_mypfs'];
$L['hea_administration'] = "<i class=\"icons grey cog\"></i> ".$L['hea_administration'];  

?>