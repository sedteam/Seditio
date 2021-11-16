<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=skins/simple/simple.php
Version=178
Updated=2014-oct-31
Type=Skin
Name=Simple
Author=Amro
Url=https://seditio.org
Description=Official skin
[END_SED]
==================== */

$cfg['pagination'] = '<li>%s</li>';
$cfg['pagination_cur'] = '<li><span>%s</span></li>';
$cfg['pagination_arrowleft'] = "<"; 
$cfg['pagination_arrowright'] = ">";

// ========= Plugin RecentItems ============================= //

$cfg['plu_mask_pages'] = "<span class=\"rec-date\">%3\$s</span>"." "."%1\$s"." ".$cfg['separator']." "."%2\$s"."<br />";
// %1\$s = Link to the category
// %2\$s = Link to the page
// %3\$s = Date

$cfg['plu_mask_topics'] =  "<span class=\"rec-date\">%2\$s</span>"." "."%3\$s"." ".$cfg['separator']." "."%4\$s"." ("."%5\$s".")<br />";
// %1\$s = "Follow" image
// %2\$s = Date
// %3\$s = Section
// %4\$s = Topic title
// %5\$s = Number of replies

$cfg['plu_mask_comments'] = "<span class=\"rec-date\">%3\$s</span> %1\$s"." ".$cfg['separator']." "."<span class=\"rec-comment\">%5\$s</span>"." ".$cfg['separator']." %2\$s<br />";
// %1\$s = Link to the comment
// %2\$s = Author
// %3\$s = Date
// %4\$s = User Avatar
// %5\$s = Comments Text

$cfg['plu_mask_polls'] =  "<div>%1\$s</div>";

?>