<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
-----------------------
Seditio language pack
Language : English (code:en)
Localization done by : Neocrome
-----------------------
[BEGIN_SED]
File=system/core/admin/lang/en/admin.lang.php
Version=180
Updated=2025-jan-25
Type=Lang
Author=Seditio Team
Description=Admin panel
[END_SED]
==================== */

/* ====== Core ====== */

$L['core_main'] = "Main setup";
$L['core_parser'] = "Parser";             // New in v120
$L['core_rss'] = "RSS feeds";             // New in v173
$L['core_dic'] = "Directories & Extra fields";             // New in v173
$L['core_time'] = "Time and date";
$L['core_skin'] = "Skins";
$L['core_lang'] = "Languages";
$L['core_menus'] = "Menu slots";
$L['core_comments'] = "Comments";
$L['core_forums'] = "Forums";
$L['core_page'] = "Pages";
$L['core_pfs'] = "Personal file space";
$L['core_gallery'] = "Gallery";
$L['core_plug'] = "Plugins";
$L['core_pm'] = "Private messages";
$L['core_polls'] = "Polls";
$L['core_ratings'] = "Ratings";
$L['core_trash'] = "Trash can";
$L['core_users'] = "Users";
$L['core_meta'] = "HTML Meta";
$L['core_index'] = "Home page";
$L['core_menu'] = "Menu manager"; // New in v178

/* ====== Upgrade ====== */

$L['upg_upgrade'] = "Upgrade";      // New in v130
$L['upg_codeversion'] = "Code version";     // New in v130
$L['upg_sqlversion'] = "SQL database version";    // New in v130
$L['upg_codeisnewer'] = "The code is newer than the SQL  version.";    // New in v130
$L['upg_codeisolder'] = "The code is older than the SQL  version, this is unusual, and not supported.<br />You should double check that you uploaded all the files from the newest package.";    // New in v130
$L['upg_codeissame'] = "The code and SQL versions are matching.";    // New in v130
$L['upg_upgradenow'] = "It is highly recommended to upgrade the SQL database right now, click here to upgrade !";    // New in v130
$L['upg_upgradenotavail'] = "There's no upgrade available for these version numbers.";       // New in v130
$L['upg_manual'] = "If you prefer to manually upgrade the database, the SQL scripts are in the folder /docs/upgrade/.";       // New in v130
$L['upg_success'] = "The upgrade was successful, click here to continue...";       // New in v130
$L['upg_failure'] = "The upgrade failed, click here to continue...";       // New in v130
$L['upg_force'] = "For some reasons, it may happen that the Seditio version number written in the SQL database is wrong. Below is a button to force the SQL version number, this will only tag the SQL database, it will NOT perform any other change.<br />Force the SQL version number to : ";    // New in v130

/* ====== General ====== */

$L['editdeleteentries'] = "Edit or delete entries";
$L['viewdeleteentries'] = "View or delete entries";
$L['addnewentry'] = "Add a new entry";
$L['adm_purgeall'] = "Purge all";
$L['adm_listisempty'] = "List is empty";
$L['adm_totalsize'] = "Total size";
$L['adm_showall'] = "Show all";
$L['adm_area'] = "Area";
$L['adm_option'] = "Option";
$L['adm_setby'] = "Set by";
$L['adm_more'] = "More tools...";
$L['adm_from'] = "From";
$L['adm_to'] = "To";
$L['adm_confirm'] = "Press this button to confirm : ";
$L['adm_done'] = "Done";
$L['adm_failed'] = "Failed";
$L['adm_warnings'] = "Warnings";
$L['adm_valqueue'] = "Waiting for validation";
$L['adm_required'] = "(Required)";
$L['adm_clicktoedit'] = "(Click to edit)";
$L['adm_manage'] = "Manage";  // New in v150
$L['adm_pagemanager'] = "Page manager";  // New in v177
$L['adm_module_name'] = "Module name";  // New in v178
$L['adm_tool_name'] = "Tool name";  // New in v178

/* ====== Banlist ====== */

$L['adm_ipmask'] = "IP mask";
$L['adm_emailmask'] = "Email mask";
$L['adm_neverexpire'] = "Never expire";
$L['adm_help_banlist'] = "Samples for IP masks:<br />
- IPv4: 194.31.13.41, 194.31.13.*, 194.31.*.*, 194.*.*.*<br />
- IPv6: 2001:0db8:85a3:0000:0000:8a2e:0370:7334, 2001:0db8:85a3:0000:0000:8a2e:0370:*, 2001:0db8:85a3:0000:0000:*:*, 2001:0db8:85a3:*:*:*:*
<br />Samples for email masks: @hotmail.com, @yahoo (Wildcards are not supported)
<br />A single entry can contain one IP mask or one email mask or both.
<br />IPs are filtered for each and every page displayed, and email masks at user registration only.";

/* ====== Cache ====== */

$L['adm_internalcache'] = "Internal cache";
$L['adm_help_cache'] = "Not available";

/* ====== Configuration ====== */

$L['adm_help_config'] = "Not available";
$L['cfg_adminemail'] = array("Administrator's email", "Required");
$L['cfg_maintitle'] = array("Site title", "Main title for the website, required");
$L['cfg_subtitle'] = array("Description", "Optional, will be displayed after the title of the site");
$L['cfg_mainurl'] = array("Site URL", "With http://, and without ending slash !");
$L['cfg_clustermode'] = array("Cluster of servers", "Set to yes if it's a load balanced setup.");            // New in v125
$L['cfg_hostip'] = array("Server IP", "The IP of the server, optional.");
$L['cfg_gzip'] = array("Gzip", "Gzip compression of the HTML output");
$L['cfg_cache'] = array("Internal cache", "Keep it enabled for better performance");
$L['cfg_devmode'] = array("Debugging mode", "Don't let this enabled on live sites");
$L['cfg_doctypeid'] = array("Document Type", "&lt;!DOCTYPE> of the HTML layout");
$L['cfg_charset'] = array("HTML charset", "");
$L['cfg_cookiedomain'] = array("Domain for cookies", "Default: empty");
$L['cfg_cookiepath'] = array("Path for cookies", "Default: empty");
$L['cfg_cookielifetime'] = array("Maximum cookie lifetime", "In seconds");
$L['cfg_metakeywords'] = array("HTML Meta keywords (comma separated)", "Search engines");
$L['cfg_disablesysinfos'] = array("Turn off page creation time", "In footer.tpl");
$L['cfg_keepcrbottom'] = array("Keep the copyright notice in the tag {FOOTER_BOTTOMLINE}", "In footer.tpl");
$L['cfg_showsqlstats'] = array("Show SQL queries statistics", "In footer.tpl");
$L['cfg_shieldenabled'] = array("Enable the Shield", "Anti-spamming and anti-hammering");
$L['cfg_shieldtadjust'] = array("Adjust Shield timers (in %)", "The higher, the harder to spam");
$L['cfg_shieldzhammer'] = array("Anti-hammer after * fast hits", "The smaller, the faster the auto-ban 3 minutes happens");
$L['cfg_maintenance'] = array("Maintenance mode", "Wake up the technical work on the site");  // New in v175
$L['cfg_maintenancelevel'] = array("User Access Level", "Select the level of access users"); // New in v175
$L['cfg_maintenancereason'] = array("Reason maintenance", "Describe the cause of maintenance"); // New in v175
$L['cfg_multihost'] = array("Multihost mode", "To enable multiple hosts");  // New in v175
$L['cfg_absurls'] = array("Absolute URL", "Enables the use of the absolute URL");  // New in v175
$L['cfg_sefurls'] = array("SEF URLs", "To enables SEF URLs on the site");  // New in v175
$L['cfg_sefurls301'] = array("301 redirect to the SEF URLs", "Enable 301 redirect from the old URL to SEF URLs");  // New in v175
$L['cfg_dateformat'] = array("Main date mask", "Default: Y-m-d H:i");
$L['cfg_formatmonthday'] = array("Short date mask", "Default: m-d");
$L['cfg_formatyearmonthday'] = array("Medium date mask", "Default: Y-m-d");
$L['cfg_formatmonthdayhourmin'] = array("Forum date mask", "Default: m-d H:i");
$L['cfg_servertimezone'] = array("Server time zone", "Offset of the server from the GMT+00");
$L['cfg_defaulttimezone'] = array("Default time zone", "For guests and new members, from -12 to +12");
$L['cfg_timedout'] = array("Idle delay, in seconds", "After this delay, user is away");
$L['cfg_maxusersperpage'] = array("Maximum lines in userlist", "");
$L['cfg_regrequireadmin'] = array("Administrators must validate new accounts", "");
$L['cfg_regnoactivation'] = array("Skip email check for new users", "\"No\"recommended, for security reasons");
$L['cfg_useremailchange'] = array("Allow users to change their email address", "\"No\" recommended, for security reasons");
$L['cfg_usertextimg'] = array("Allow images and HTML in user signature", "\"No\" recommended, for security reasons");
$L['cfg_av_maxsize'] = array("Avatar, maximum file size", "Default: 8000 bytes");
$L['cfg_av_maxx'] = array("Avatar, maximum width", "Default: 64 pixels");
$L['cfg_av_maxy'] = array("Avatar, maximum height", "Default: 64 pixels");
$L['cfg_usertextmax'] = array("Maximum length for user signature", "Default: 300 chars");
$L['cfg_sig_maxsize'] = array("Signature, maximum file size", "Default: 50000 bytes");
$L['cfg_sig_maxx'] = array("Signature, maximum width", "Default: 468 pixels");
$L['cfg_sig_maxy'] = array("Signature, maximum height", "Default: 60 pixels");
$L['cfg_ph_maxsize'] = array("Photo, maximum file size", "Default: 8000 bytes");
$L['cfg_ph_maxx'] = array("Photo, maximum width", "Default: 96 pixels");
$L['cfg_ph_maxy'] = array("Photo, maximum height", "Default: 96 pixels");
$L['cfg_maxrowsperpage'] = array("Maximum lines in lists", "");
$L['cfg_showpagesubcatgroup'] = array("Show in groups pages from the subsections", "");   //New Sed171
$L['cfg_genseourls'] = array("Generate SEO url (auto gen* page alias)? ", "");   //New Sed178
$L['cfg_maxcommentsperpage'] = array("Maximum comments per page", "");  //New Sed173
$L['cfg_commentsorder'] = array("Sorting order for comments", "ASC - new bottom, DESC - newest on top");  //New Sed173
$L['cfg_maxtimeallowcomedit'] = array("The time allowed to edit comments", "In minutes, if 0 - editing is prohibited");  //New Sed173
$L['cfg_showcommentsonpage'] = array("Show comments on pages", "By default displays comment on the page");   //New Sed171
$L['cfg_maxcommentlenght'] = array("The maximum length of a comment", "Default: 2000 characters");  //New Sed175
$L['cfg_countcomments'] = array("Count comments", "Display the count of comments near the icon");
$L['cfg_hideprivateforums'] = array("Hide private forums", "");
$L['cfg_hottopictrigger'] = array("Posts for a topic to be 'hot'", "");
$L['cfg_maxtopicsperpage'] = array("Maximum topics or posts per page", "");
$L['cfg_antibumpforums'] = array("Anti-bump protection", "Will prevent users from posting twice in a row in the same topic");
$L['cfg_pfsuserfolder'] = array("Folder storage mode", "If enabled, will store the user files in subfolders /datas/users/USERID/... instead of prepending the USERID to the filename. Must be set at the FIRST setup of the site ONLY. As soon as a file is uploaded to a PFS, it's too late to change this.");
$L['cfg_th_amode'] = array("Thumbnails generation", "");
$L['cfg_th_x'] = array("Thumbnails, width", "Default: 112 pixels");
$L['cfg_th_y'] = array("Thumbnails, height", "Default: 84 pixel, recommended : Width x 0.75");
$L['cfg_th_border'] = array("Thumbnails, border size", "Default: 4 pixels");
$L['cfg_th_keepratio'] = array("Thumbnail, keep ratio ?", "");
$L['cfg_th_jpeg_quality'] = array("Thumbnails, Jpeg quality", "Default: 85");
$L['cfg_th_colorbg'] = array("Thumbnails, border color", "Default: 000000, hex color code");
$L['cfg_th_colortext'] = array("Thumbnails, text color", "Default: FFFFFF, hex color code");
$L['cfg_th_rel'] = array("Thumbnail rel attribute", "Default: sedthumb"); // New in v175
$L['cfg_th_dimpriority'] = array("Thumbnails, resize by", "Default: Width");       // New in v160
$L['cfg_th_textsize'] = array("Thumbnails, size of the text", "");
$L['cfg_pfs_filemask'] = array("File names based on pattern of time", "Generate file names on a pattern of time");  // New in sed172

$L['cfg_available_image_sizes'] = array("Available image resolutions", "Listed with commas, no spaces. Example: 120x80,800x600");  // New in sed180

$L['cfg_disable_gallery'] = array("Disable the gallery", "");         // New in v150
$L['cfg_gallery_gcol'] = array("Number of columns for the galleries", "Default : 4");     // New in v150
$L['cfg_gallery_bcol'] = array("Number of columns for the pictures", "Default : 6");        // New in v150
$L['cfg_gallery_logofile'] = array("Png/jpeg/Gif logo that will be added to all the new PFS images", "Leave empty to disable");        // New in v150
$L['cfg_gallery_logopos'] = array("Position of the logo in the PFS images", "Default : Bottom left");        // New in v150
$L['cfg_gallery_logotrsp'] = array("Merging level for the logo in %", "Default : 50");        // New in v150
$L['cfg_gallery_logojpegqual'] = array("Quality of the final image afer the logo is inserted, if it's a Jpeg", "Default : 90");        // New in v150
$L['cfg_gallery_imgmaxwidth'] = array("Max width in pixel for a picture displayed, if it's larger a sized-down copy will be processed", "");         // New in v150

$L['cfg_pm_maxsize'] = array("Maximum length for messages", "Default: 10000 chars");
$L['cfg_pm_allownotifications'] = array("Allow PM notifications by email", "");
$L['cfg_disablehitstats'] = array("Disable hit statistics", "Referers and hits per day");
$L['cfg_disablereg'] = array("Disable registration process", "Prevent users from registering new accounts");
$L['cfg_disablewhosonline'] = array("Disable who's online", "Automatically enabled if you turn on the Shield");
$L['cfg_defaultcountry'] = array("Default country for the new users", "2 letters country code");    // New in v130
$L['cfg_forcedefaultskin'] = array("Force the default skin for all users", "");
$L['cfg_forcedefaultlang'] = array("Force the default language for all users", "");
$L['cfg_separator'] = array("Generic separator", "Default:>");
$L['cfg_menu1'] = array("Menu slot #1<br />{PHP.cfg.menu1} in all tpl files", "");
$L['cfg_menu2'] = array("Menu slot #2<br />{PHP.cfg.menu2} in all tpl files", "");
$L['cfg_menu3'] = array("Menu slot #3<br />{PHP.cfg.menu3} in all tpl files", "");
$L['cfg_menu4'] = array("Menu slot #4<br />{PHP.cfg.menu4} in all tpl files", "");
$L['cfg_menu5'] = array("Menu slot #5<br />{PHP.cfg.menu5} in all tpl files", "");
$L['cfg_menu6'] = array("Menu slot #6<br />{PHP.cfg.menu6} in all tpl files", "");
$L['cfg_menu7'] = array("Menu slot #7<br />{PHP.cfg.menu7} in all tpl files", "");
$L['cfg_menu8'] = array("Menu slot #8<br />{PHP.cfg.menu8} in all tpl files", "");
$L['cfg_menu9'] = array("Menu slot #9<br />{PHP.cfg.menu9} in all tpl files", "");
$L['cfg_topline'] = array("Top line<br />{HEADER_TOPLINE} in header.tpl", "");
$L['cfg_banner'] = array("Banner<br />{HEADER_BANNER} in header.tpl", "");
$L['cfg_motd'] = array("Message of the day<br />{NEWS_MOTD} in index.tpl", "");
$L['cfg_bottomline'] = array("Bottom line<br />{FOOTER_BOTTOMLINE} in footer.tpl", "");
$L['cfg_freetext1'] = array("Freetext Slot #1<br />{PHP.cfg.freetext1} in all tpl files", "");
$L['cfg_freetext2'] = array("Freetext Slot #2<br />{PHP.cfg.freetext2} in all tpl files", "");
$L['cfg_freetext3'] = array("Freetext Slot #3<br />{PHP.cfg.freetext3} in all tpl files", "");
$L['cfg_freetext4'] = array("Freetext Slot #4<br />{PHP.cfg.freetext4} in all tpl files", "");
$L['cfg_freetext5'] = array("Freetext Slot #5<br />{PHP.cfg.freetext5} in all tpl files", "");
$L['cfg_freetext6'] = array("Freetext Slot #6<br />{PHP.cfg.freetext6} in all tpl files", "");
$L['cfg_freetext7'] = array("Freetext Slot #7<br />{PHP.cfg.freetext7} in all tpl files", "");
$L['cfg_freetext8'] = array("Freetext Slot #8<br />{PHP.cfg.freetext8} in all tpl files", "");
$L['cfg_freetext9'] = array("Freetext Slot #9<br />{PHP.cfg.freetext9} in all tpl files", "");
$L['cfg_extra1title'] = array("Field #1 (String), title", "");
$L['cfg_extra2title'] = array("Field #2 (String), title", "");
$L['cfg_extra3title'] = array("Field #3 (String), title", "");
$L['cfg_extra4title'] = array("Field #4 (String), title", "");
$L['cfg_extra5title'] = array("Field #5 (String), title", "");
$L['cfg_extra6title'] = array("Field #6 (Select box), title", "");
$L['cfg_extra7title'] = array("Field #7 (Select box), title", "");
$L['cfg_extra8title'] = array("Field #8 (Select box), title", "");
$L['cfg_extra9title'] = array("Field #9 (Long text), title", "");
$L['cfg_extra1tsetting'] = array("Maximum characters in this field", "");
$L['cfg_extra2tsetting'] = array("Maximum characters in this field", "");
$L['cfg_extra3tsetting'] = array("Maximum characters in this field", "");
$L['cfg_extra4tsetting'] = array("Maximum characters in this field", "");
$L['cfg_extra5tsetting'] = array("Maximum characters in this field", "");
$L['cfg_extra6tsetting'] = array("Values for the select box, comma separated", "");
$L['cfg_extra7tsetting'] = array("Values for the select box, comma separated", "");
$L['cfg_extra8tsetting'] = array("Values for the select box, comma separated", "");
$L['cfg_extra9tsetting'] = array("Maximum length for the text", "");
$L['cfg_extra1uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra2uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra3uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra4uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra5uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra6uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra7uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra8uchange'] = array("Editable in user profile ?", "");
$L['cfg_extra9uchange'] = array("Editable in user profile ?", "");
$L['cfg_disable_comments'] = array("Disable the comments", "");
$L['cfg_disable_forums'] = array("Disable the forums", "");
$L['cfg_disable_pfs'] = array("Disable the PFS", "");
$L['cfg_disable_polls'] = array("Disable the polls", "");
$L['cfg_disable_pm'] = array("Disable the private messages", "");
$L['cfg_disable_ratings'] = array("Disable the ratings", "");
$L['cfg_disable_page'] = array("Disable the pages", "");
$L['cfg_disable_plug'] = array("Disable the plugins", "");
$L['cfg_trash_prunedelay'] = array("Remove the items from the trash can after * days (Zero to keep forever)", "");
$L['cfg_trash_comment'] = array("Use the trash can for the comments", "");
$L['cfg_trash_forum'] = array("Use the trash can for the forums", "");
$L['cfg_trash_page'] = array("Use the trash can for the pages", "");
$L['cfg_trash_pm'] = array("Use the trash can for the private messages", "");
$L['cfg_trash_user'] = array("Use the trash can for the users", "");

$L['cfg_parser_vid'] = array("Allow BBcodes for the videos", "");        // New in v120
$L['cfg_parser_vid_autolink'] = array("Auto-link URLs to known video sites", "");                        // New in v120
$L['cfg_parsebbcodecom'] = array("Parse BBcode in comments and private messages", "");
$L['cfg_parsebbcodepages'] = array("Parse BBcode in pages", "");
$L['cfg_parsebbcodeusertext'] = array("Parse BBcode in user signature", "");
$L['cfg_parsebbcodeforums'] = array("Parse BBcode in forums", "");
$L['cfg_parsesmiliescom'] = array("Parse smilies in comments and private messages", "");
$L['cfg_parsesmiliespages'] = array("Parse smilies in pages", "");
$L['cfg_parsesmiliesusertext'] = array("Parse smilies in user signature", "");
$L['cfg_parsesmiliesforums'] = array("Parse smilies in forums", "");

$L['cfg_color_group'] = array("Colorize group of users", "Default: No, for better performance");  // New in v175

$L['cfg_ajax'] = array("Enable AJAX", "");  // New in v175
$L['cfg_enablemodal'] = array("Enable modal windows", "");  // New in v175

$L['cfg_hometitle'] = array("Homepage title", "Optional, for SEO"); // New in v179
$L['cfg_homemetadescription'] = array("Homepage meta description", "Optional, for SEO"); // New in v179
$L['cfg_homemetakeywords'] = array("Homepage meta keywords", "Optional, for SEO"); // New in v179

/* ====== HTML Meta ====== */

$L['cfg_defaulttitle'] = array("Default Title", "Available options: {MAINTITLE}, {SUBTITLE}");        //Sed 175
$L['cfg_indextitle'] = array("Title for Homepage", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 179
$L['cfg_listtitle'] = array("Title for lists of pages", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pagetitle'] = array("Title for pages", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}, {CATEGORY}");        //Sed 175
$L['cfg_forumstitle'] = array("Title for forums", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_userstitle'] = array("Title for users", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pmtitle'] = array("Title for PM", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_gallerytitle'] = array("Title for gallery", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pfstitle'] = array("Title for PFS", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175
$L['cfg_plugtitle'] = array("Title for plugins", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175

/* ====== Rss ====== */

$L['cfg_disable_rss'] = array("Disable RSS feeds", "");
$L['cfg_disable_rsspages'] = array("Disable RSS feed for pages", "");
$L['cfg_disable_rsscomments'] = array("Disable RSS feed for comments", "");
$L['cfg_disable_rssforums'] = array("Disable RSS feed for the forums", "");
$L['cfg_rss_timetolive'] = array("Cash time for RSS feed", "in seconds");
$L['cfg_rss_defaultcode'] = array("Default RSS feed", "enter the category code");
$L['cfg_rss_maxitems'] = array("The maximum number of rows in the RSS feed", "");

$L['adm_help_config_rss'] = "Links to open RSS feeds: <br />" . $cfg['mainurl'] . "/" . "rss (by default, the output of news categories specified in the settings) <br /> " . $cfg['mainurl'] . "/" . "rss/pages?c=XX (XX - Category code, the last pages of the category) <br />" . $cfg['mainurl'] . "/" . "rss/comments?id=XX (XX - ID page, comments page) <br />" . $cfg['mainurl'] . "/" . "rss/forums (latest posts from all sections of the forum) <br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX (XX - ID section, recent posts section) <br />" . $cfg['mainurl'] . "/" . "rss/forums?q=XX (XX - ID topic, recent posts in the topic) <br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX&q=YY (XX - ID section, YY - ID topic)";

/* ====== Forums ====== */

$L['adm_diplaysignatures'] = "Display signatures";
$L['adm_enablebbcodes'] = "Enable BBcodes";
$L['adm_enablesmilies'] = "Enable smilies";
$L['adm_enableprvtopics'] = "Allow private topics";
$L['adm_countposts'] = "Count posts";
$L['adm_autoprune'] = "Auto-prune topics after * days";
$L['adm_postcounters'] = "Check the counters";
$L['adm_help_forums'] = "Not available";
$L['adm_forum_structure'] = "Structure of the forums (categories)";
$L['adm_forum_structure_cat'] = "Structure of the forums";
$L['adm_help_forums_structure'] = "Not available";
$L['adm_defstate'] = "Default state";
$L['adm_defstate_0'] = "Folded";
$L['adm_defstate_1'] = "Unfolded";
$L['adm_parentcat'] = "Parent category";    // New in v172	

/* ====== IP search ====== */

$L['adm_searchthisuser'] = "Search for this IP in the user database";
$L['adm_dnsrecord'] = "DNS record for this address";

/* ====== Smilies ====== */

$L['adm_help_smilies'] = "Not available";

/* ====== Dictionary ====== */

$L['adm_dic_list'] = "Directories list";
$L['adm_dictionary'] = "Directory";
$L['adm_dic_title'] = "Title of the directory";
$L['adm_dic_code'] = "Code of the directory (name of extra field)";
$L['adm_dic_list'] = "List of the directories";
$L['adm_dic_term_list'] = "List of terms";
$L['adm_dic_add'] = "Add new directory";
$L['adm_dic_edit'] = "Edit directory";
$L['adm_dic_add_term'] = "Add a new term";
$L['adm_dic_term_title'] = "Title of the term";
$L['adm_dic_term_value'] = "Value of the term";
$L['adm_dic_term_defval'] = "Make a term default?";
$L['adm_dic_term_edit'] = "Edit term from directory";
$L['adm_dic_children'] = "Children directory";

$L['adm_dic_mera'] = "Unit";
$L['adm_dic_values'] = "List of values for directory";

$L['adm_dic_form_title'] = "Title for form element";
$L['adm_dic_form_desc'] = "Text for form element";
$L['adm_dic_form_size'] = "Size of text field";
$L['adm_dic_form_maxsize'] = "The maximum size of text field";
$L['adm_dic_form_cols'] = "The cols of text field";
$L['adm_dic_form_rows'] = "The rows of text field";

$L['adm_dic_extra'] = "Extra field";
$L['adm_dic_addextra'] = "Add extra field";
$L['adm_dic_editextra'] = "Edit extra field";
$L['adm_dic_extra_location'] = "Name of table";
$L['adm_dic_extra_type'] = "Data type of field";
$L['adm_dic_extra_size'] = "Length of field";

$L['adm_dic_comma_separat'] = "(values comma separated)";

$L['adm_help_dic'] = ""; //Need add

/* ====== Menu manager ====== */

$L['adm_menuitems'] = "Menu items";
$L['adm_additem'] = "Add item";
$L['adm_position'] = "Position";
$L['adm_confirm_delete'] = "Confirm delete?";
$L['adm_addmenuitem'] = "Add menu item";
$L['adm_editmenuitem'] = "Edit menu item";
$L['adm_parentitem'] = "Parent item";
$L['adm_url'] = "URL";
$L['adm_activity'] = "Active?";

/* ====== PFS ====== */

$L['adm_gd'] = "GD graphical library";
$L['adm_allpfs'] = "All PFS";
$L['adm_allfiles'] = "All files";
$L['adm_thumbnails'] = "Thumbnails";
$L['adm_orphandbentries'] = "Orphan DB entries";
$L['adm_orphanfiles'] = "Orphan files";
$L['adm_delallthumbs'] = "Delete all thumbnails";
$L['adm_rebuildallthumbs'] = "Delete and rebuild all thumbnails";
$L['adm_help_pfsthumbs'] = "Not available";
$L['adm_help_check1'] = "Not available";
$L['adm_help_check2'] = "Not available";
$L['adm_help_pfsfiles'] = "Not available";
$L['adm_help_allpfs'] = "Not available";
$L['adm_nogd'] = "The GD graphical library is not supported by this host, Seditio won't be able to create thumbnails for the PFS images. You must go into the configuration panel, tab 'Personal File Space', and set Thumbnails generation = 'Disabled'.";

/* ====== Pages ====== */

$L['adm_structure'] = "Structure of the pages (categories)";
$L['adm_syspages'] = "View the category 'system'";
$L['adm_help_page'] = "The pages that belongs to the category 'system' are not displayed in the public listings, it's to make standalone pages.";
$L['adm_sortingorder'] = "Set a default sorting order for the categories";
$L['adm_fileyesno'] = "File (yes/no)";
$L['adm_fileurl'] = "File URL";
$L['adm_filesize'] = "File size";
$L['adm_filecount'] = "File hit count";

$L['adm_tpl_mode'] = "Template mode";
$L['adm_tpl_empty'] = "Default";
$L['adm_tpl_forced'] = "Same as";
$L['adm_tpl_parent'] = "Same as the parent category";

$L['adm_enablecomments'] = "Enable Comments";   // New v173
$L['adm_enableratings'] = "Enable Ratings";     // New v173

/* ====== Polls ====== */

$L['adm_help_polls'] = "Once you created a new poll topics, select 'Edit' to add options (choices) for this poll.<br />'Delete' will delete the selected poll, the options, and all related votes.<br />'Reset' will delete all votes for the selected poll. It won't delete the poll itself or the options.<br />'Bump' will change the poll creation date to the current date, and so will make the poll 'current', top of the list.";
$L['adm_poll_title'] = "Poll title";

/* ====== Statistics ====== */

$L['adm_phpver'] = "PHP engine version";
$L['adm_zendver'] = "Zend engine version";
$L['adm_interface'] = "Interface between webserver and PHP";
$L['adm_os'] = "Operating system";
$L['adm_clocks'] = "Clocks";
$L['adm_time1'] = "#1 : Raw server time";
$L['adm_time2'] = "#2 : GMT time returned by the server";
$L['adm_time3'] = "#3 : GMT time + server offset (Seditio reference)";
$L['adm_time4'] = "#4 : Your local time, adjusted from your profile";
$L['adm_help_versions'] = "Adjust the Server time zone to have the clock #3 properlly set.<br />Clock #4 depends of the timezone setting in your profile.<br />Clocks #1 and #2 are ignored by Seditio.";
$L['adm_log'] = "System log";
$L['adm_infos'] = "Informations";
$L['adm_versiondclocks'] = "Versions and clocks";
$L['adm_checkcoreskins'] = "Check core files and skins";
$L['adm_checkcorenow'] = "Check core files now !";
$L['adm_checkingcore'] = "Checking core files...";
$L['adm_checkskins'] = "Check if all files are present in skins";
$L['adm_checkskin'] = "Check TPL files for the skin";
$L['adm_checkingskin'] = "Checking the skin...";
$L['adm_hits'] = "Hits";
$L['adm_check_ok'] = "Ok";
$L['adm_check_missing'] = "Missing";
$L['adm_ref_lowhits'] = "Purge entries where hits are lower than 5";
$L['adm_maxhits'] = "Maximum hitcount was reached %1\$s, %2\$s pages displayed this day.";
$L['adm_byyear'] = "By year";
$L['adm_bymonth'] = "By month";
$L['adm_byweek'] = "By week";

/* ====== Ratings ====== */

$L['adm_ratings_totalitems'] = "Total pages rated";
$L['adm_ratings_totalvotes'] = "Total votes";
$L['adm_help_ratings'] = "To reset a rating, simply delete it. It will be re-created with the first new vote.";

/* ====== Trash can ====== */

$L['adm_help_trashcan'] = "Here are listed the items recently deleted by the users and moderators.<br />Note that restoring a forum topic will also restore all the posts that belongs to the topic.<br />And restoring a post in a deleted topic will restore the whole topic (if available) and all the child posts.<br />&nbsp;<br />Wipe : Delete the item forever.<br />Restore : Put the item back in the live database.";

/* ====== Users ====== */

$L['adm_defauth_members'] = "Default rights for the members";
$L['adm_deflock_members'] = "Lock mask for the members";
$L['adm_defauth_guests'] = "Default rights for the guests";
$L['adm_deflock_guests'] = "Lock mask for the guests";
$L['adm_rightspergroup'] = "Rights per group";
$L['adm_copyrightsfrom'] = "Set the same rights as the group";
$L['adm_maxsizesingle'] = "PFS max size for a single file (KB)";
$L['adm_maxsizeallpfs'] = "Max size of all PFS files together (KB)";
$L['adm_rights_allow10'] = "Allowed";
$L['adm_rights_allow00'] = "Denied";
$L['adm_rights_allow11'] = "Allowed and locked for security reasons";
$L['adm_rights_allow01'] = "Denied and locked for security reasons";
$L['adm_color'] = "Color for group"; // New in v175

/* ====== Plugins ====== */

$L['adm_extplugins'] = "Extended plugins";
$L['adm_present'] = "Present";
$L['adm_missing'] = "Missing";
$L['adm_paused'] = "Paused";
$L['adm_running'] = "Running";
$L['adm_partrunning'] = "Partially running";
$L['adm_notinstalled'] = "Not installed";

$L['adm_opt_installall'] = "Install all";
$L['adm_opt_installall_explain'] = "This will install or reset all the parts of the plugin.";
$L['adm_opt_uninstallall'] = "Un-install all";
$L['adm_opt_uninstallall_explain'] = "This will disable all the parts of the plugin, but won't physically remove the files.";
$L['adm_opt_pauseall'] = "Pause all";
$L['adm_opt_pauseall_explain'] = "This will pause (disable) all the parts of the plugin.";
$L['adm_opt_unpauseall'] = "Un-pause all";
$L['adm_opt_unpauseall_explain'] = "This will un-pause (enable) all the parts of the plugin.";

/* ====== Private messages ====== */

$L['adm_pm_totaldb'] = "Private messages in the database";
$L['adm_pm_totalsent'] = "Total of private messages ever sent";
