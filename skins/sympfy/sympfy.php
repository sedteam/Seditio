<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=skins/sympfy/sympfy.php
Version=179
Updated=2022-jul-15
Type=Skin
Name=Sympfy
Author=Amro
Url=https://seditio.org
Description=Official skin
[END_SED]
==================== */

$cfg['pagination'] = '<li class="page-item">%s</li>';
$cfg['pagination_cur'] = '<li class="page-item active"><span class="page-link">%s</span></li>';
$cfg['pagination_arrowleft'] = "<i class=\"ic-chevron-left\"></i>"; 
$cfg['pagination_arrowright'] = "<i class=\"ic-chevron-right\"></i>";

$cfg['separator'] = "<i class=\"ic-arrow-right\"></i>";

$cfg['arrow_up'] = "<i class=\"ic-arrow-narrow-up\"></i>";
$cfg['arrow_down'] = "<i class=\"ic-arrow-narrow-down\"></i>";
$cfg['arrow_left'] = "<i class=\"ic-arrow-narrow-left\"></i>";
$cfg['arrow_right'] = "<i class=\"ic-arrow-narrow-right\"></i>";

$cfg['ic_checked'] = "<i class=\"ic-check\"></i>";
$cfg['ic_set'] = "<i class=\"ic-wand\"></i>";

$cfg['ic_folder'] = "<i class=\"ic-folder\"></i>";
$cfg['ic_gallery'] = "<i class=\"ic-gallery\"></i>";

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