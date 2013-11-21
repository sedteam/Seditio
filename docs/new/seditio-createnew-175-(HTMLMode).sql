--
-- Table structure for table `sed_auth`
--

CREATE TABLE IF NOT EXISTS `sed_auth` (
  `auth_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `auth_groupid` int(11) NOT NULL DEFAULT '0',
  `auth_code` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auth_option` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auth_rights` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auth_rights_lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auth_setbyuserid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`auth_id`),
  KEY `auth_groupid` (`auth_groupid`),
  KEY `auth_code` (`auth_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=186 ;

--
-- Dumping data for table `sed_auth`
--

INSERT INTO `sed_auth` (`auth_id`, `auth_groupid`, `auth_code`, `auth_option`, `auth_rights`, `auth_rights_lock`, `auth_setbyuserid`) VALUES
(1, 1, 'admin', 'a', 0, 255, 1),
(2, 2, 'admin', 'a', 0, 255, 1),
(3, 3, 'admin', 'a', 0, 255, 1),
(4, 4, 'admin', 'a', 0, 255, 1),
(5, 5, 'admin', 'a', 255, 255, 1),
(76, 6, 'admin', 'a', 1, 0, 1),
(6, 1, 'comments', 'a', 1, 254, 1),
(7, 2, 'comments', 'a', 1, 254, 1),
(8, 3, 'comments', 'a', 0, 255, 1),
(9, 4, 'comments', 'a', 3, 128, 1),
(10, 5, 'comments', 'a', 255, 255, 1),
(77, 6, 'comments', 'a', 131, 0, 1),
(46, 1, 'forums', '1', 1, 254, 1),
(47, 2, 'forums', '1', 1, 254, 1),
(48, 3, 'forums', '1', 0, 255, 1),
(49, 4, 'forums', '1', 3, 128, 1),
(50, 5, 'forums', '1', 255, 255, 1),
(85, 6, 'forums', '1', 131, 0, 1),
(51, 1, 'forums', '2', 1, 254, 1),
(52, 2, 'forums', '2', 1, 254, 1),
(53, 3, 'forums', '2', 0, 255, 1),
(54, 4, 'forums', '2', 3, 128, 1),
(55, 5, 'forums', '2', 255, 255, 1),
(86, 6, 'forums', '2', 131, 0, 1),
(87, 1, 'gallery', 'a', 1, 254, 1),
(88, 2, 'gallery', 'a', 1, 254, 1),
(89, 3, 'gallery', 'a', 0, 255, 1),
(90, 4, 'gallery', 'a', 1, 128, 1),
(91, 5, 'gallery', 'a', 255, 255, 1),
(11, 1, 'index', 'a', 1, 254, 1),
(12, 2, 'index', 'a', 1, 254, 1),
(13, 3, 'index', 'a', 0, 255, 1),
(14, 4, 'index', 'a', 1, 128, 1),
(15, 5, 'index', 'a', 255, 255, 1),
(78, 6, 'index', 'a', 131, 0, 1),
(16, 1, 'message', 'a', 1, 255, 1),
(17, 2, 'message', 'a', 1, 255, 1),
(18, 3, 'message', 'a', 1, 255, 1),
(19, 4, 'message', 'a', 1, 255, 1),
(20, 5, 'message', 'a', 255, 255, 1),
(79, 6, 'message', 'a', 131, 0, 1),
(56, 1, 'page', 'articles', 1, 254, 1),
(57, 2, 'page', 'articles', 1, 254, 1),
(58, 3, 'page', 'articles', 0, 255, 1),
(59, 4, 'page', 'articles', 3, 128, 1),
(60, 5, 'page', 'articles', 255, 255, 1),
(92, 6, 'page', 'articles', 131, 0, 1),
(71, 1, 'page', 'news', 1, 254, 1),
(72, 2, 'page', 'news', 1, 254, 1),
(73, 3, 'page', 'news', 0, 255, 1),
(74, 4, 'page', 'news', 3, 252, 1),
(75, 5, 'page', 'news', 255, 255, 1),
(95, 6, 'page', 'news', 131, 0, 1),
(61, 1, 'page', 'sample1', 1, 254, 1),
(62, 2, 'page', 'sample1', 1, 254, 1),
(63, 3, 'page', 'sample1', 0, 255, 1),
(64, 4, 'page', 'sample1', 3, 252, 1),
(65, 5, 'page', 'sample1', 255, 255, 1),
(93, 6, 'page', 'sample1', 131, 0, 1),
(66, 1, 'page', 'sample2', 1, 254, 1),
(67, 2, 'page', 'sample2', 1, 254, 1),
(68, 3, 'page', 'sample2', 0, 255, 1),
(69, 4, 'page', 'sample2', 3, 128, 1),
(70, 5, 'page', 'sample2', 255, 255, 1),
(94, 6, 'page', 'sample2', 131, 0, 1),
(21, 1, 'pfs', 'a', 0, 255, 1),
(22, 2, 'pfs', 'a', 0, 255, 1),
(23, 3, 'pfs', 'a', 0, 255, 1),
(24, 4, 'pfs', 'a', 3, 128, 1),
(25, 5, 'pfs', 'a', 255, 255, 1),
(80, 6, 'pfs', 'a', 131, 0, 1),
(101, 1, 'plug', 'adminqv', 0, 255, 0),
(98, 2, 'plug', 'adminqv', 0, 255, 0),
(99, 3, 'plug', 'adminqv', 0, 255, 0),
(100, 4, 'plug', 'adminqv', 1, 254, 0),
(96, 5, 'plug', 'adminqv', 255, 255, 0),
(97, 6, 'plug', 'adminqv', 1, 254, 0),
(107, 1, 'plug', 'ckeditor', 0, 255, 0),
(104, 2, 'plug', 'ckeditor', 0, 255, 0),
(105, 3, 'plug', 'ckeditor', 0, 255, 0),
(106, 4, 'plug', 'ckeditor', 1, 254, 0),
(102, 5, 'plug', 'ckeditor', 255, 255, 0),
(103, 6, 'plug', 'ckeditor', 1, 254, 0),
(113, 1, 'plug', 'cleaner', 0, 255, 0),
(110, 2, 'plug', 'cleaner', 0, 255, 0),
(111, 3, 'plug', 'cleaner', 0, 255, 0),
(112, 4, 'plug', 'cleaner', 1, 254, 0),
(108, 5, 'plug', 'cleaner', 255, 255, 0),
(109, 6, 'plug', 'cleaner', 1, 254, 0),
(119, 1, 'plug', 'contact', 3, 252, 0),
(116, 2, 'plug', 'contact', 3, 252, 0),
(117, 3, 'plug', 'contact', 0, 255, 0),
(118, 4, 'plug', 'contact', 3, 252, 0),
(114, 5, 'plug', 'contact', 255, 255, 0),
(115, 6, 'plug', 'contact', 3, 252, 0),
(125, 1, 'plug', 'jevix', 1, 254, 0),
(122, 2, 'plug', 'jevix', 1, 254, 0),
(123, 3, 'plug', 'jevix', 0, 255, 0),
(124, 4, 'plug', 'jevix', 1, 254, 0),
(120, 5, 'plug', 'jevix', 255, 255, 0),
(121, 6, 'plug', 'jevix', 1, 254, 0),
(131, 1, 'plug', 'massmovetopics', 0, 255, 0),
(128, 2, 'plug', 'massmovetopics', 0, 255, 0),
(129, 3, 'plug', 'massmovetopics', 0, 255, 0),
(130, 4, 'plug', 'massmovetopics', 0, 255, 0),
(126, 5, 'plug', 'massmovetopics', 255, 255, 0),
(127, 6, 'plug', 'massmovetopics', 0, 255, 0),
(137, 1, 'plug', 'news', 1, 254, 0),
(134, 2, 'plug', 'news', 1, 254, 0),
(135, 3, 'plug', 'news', 0, 255, 0),
(136, 4, 'plug', 'news', 1, 254, 0),
(132, 5, 'plug', 'news', 255, 255, 0),
(133, 6, 'plug', 'news', 1, 254, 0),
(143, 1, 'plug', 'passrecover', 1, 254, 0),
(140, 2, 'plug', 'passrecover', 1, 254, 0),
(141, 3, 'plug', 'passrecover', 0, 255, 0),
(142, 4, 'plug', 'passrecover', 1, 254, 0),
(138, 5, 'plug', 'passrecover', 255, 255, 0),
(139, 6, 'plug', 'passrecover', 1, 254, 0),
(149, 1, 'plug', 'recentitems', 1, 254, 0),
(146, 2, 'plug', 'recentitems', 1, 254, 0),
(147, 3, 'plug', 'recentitems', 0, 255, 0),
(148, 4, 'plug', 'recentitems', 1, 254, 0),
(144, 5, 'plug', 'recentitems', 255, 255, 0),
(145, 6, 'plug', 'recentitems', 1, 254, 0),
(155, 1, 'plug', 'search', 1, 254, 0),
(152, 2, 'plug', 'search', 1, 254, 0),
(153, 3, 'plug', 'search', 0, 255, 0),
(154, 4, 'plug', 'search', 1, 254, 0),
(150, 5, 'plug', 'search', 255, 255, 0),
(151, 6, 'plug', 'search', 1, 254, 0),
(161, 1, 'plug', 'skineditor', 3, 252, 0),
(158, 2, 'plug', 'skineditor', 3, 252, 0),
(159, 3, 'plug', 'skineditor', 0, 255, 0),
(160, 4, 'plug', 'skineditor', 3, 252, 0),
(156, 5, 'plug', 'skineditor', 255, 255, 0),
(157, 6, 'plug', 'skineditor', 3, 252, 0),
(167, 1, 'plug', 'statistics', 1, 254, 0),
(164, 2, 'plug', 'statistics', 1, 254, 0),
(165, 3, 'plug', 'statistics', 0, 255, 0),
(166, 4, 'plug', 'statistics', 1, 254, 0),
(162, 5, 'plug', 'statistics', 255, 255, 0),
(163, 6, 'plug', 'statistics', 1, 254, 0),
(173, 1, 'plug', 'syntaxhighlight', 1, 254, 0),
(170, 2, 'plug', 'syntaxhighlight', 1, 254, 0),
(171, 3, 'plug', 'syntaxhighlight', 0, 255, 0),
(172, 4, 'plug', 'syntaxhighlight', 1, 254, 0),
(168, 5, 'plug', 'syntaxhighlight', 255, 255, 0),
(169, 6, 'plug', 'syntaxhighlight', 1, 254, 0),
(179, 1, 'plug', 'syscheck', 3, 252, 0),
(176, 2, 'plug', 'syscheck', 3, 252, 0),
(177, 3, 'plug', 'syscheck', 0, 255, 0),
(178, 4, 'plug', 'syscheck', 3, 252, 0),
(174, 5, 'plug', 'syscheck', 255, 255, 0),
(175, 6, 'plug', 'syscheck', 3, 252, 0),
(185, 1, 'plug', 'whosonline', 1, 254, 0),
(182, 2, 'plug', 'whosonline', 1, 254, 0),
(183, 3, 'plug', 'whosonline', 0, 255, 0),
(184, 4, 'plug', 'whosonline', 1, 254, 0),
(180, 5, 'plug', 'whosonline', 255, 255, 0),
(181, 6, 'plug', 'whosonline', 1, 254, 0),
(26, 1, 'pm', 'a', 0, 255, 1),
(27, 2, 'pm', 'a', 0, 255, 1),
(28, 3, 'pm', 'a', 0, 255, 1),
(29, 4, 'pm', 'a', 3, 128, 1),
(30, 5, 'pm', 'a', 255, 255, 1),
(81, 6, 'pm', 'a', 131, 0, 1),
(31, 1, 'polls', 'a', 1, 254, 1),
(32, 2, 'polls', 'a', 1, 254, 1),
(33, 3, 'polls', 'a', 0, 255, 1),
(34, 4, 'polls', 'a', 3, 128, 1),
(35, 5, 'polls', 'a', 255, 255, 1),
(82, 6, 'polls', 'a', 131, 0, 1),
(36, 1, 'ratings', 'a', 1, 254, 1),
(37, 2, 'ratings', 'a', 1, 254, 1),
(38, 3, 'ratings', 'a', 0, 255, 1),
(39, 4, 'ratings', 'a', 3, 128, 1),
(40, 5, 'ratings', 'a', 255, 255, 1),
(83, 6, 'ratings', 'a', 131, 0, 1),
(41, 1, 'users', 'a', 0, 254, 1),
(42, 2, 'users', 'a', 0, 254, 1),
(43, 3, 'users', 'a', 0, 255, 1),
(44, 4, 'users', 'a', 3, 128, 1),
(45, 5, 'users', 'a', 255, 255, 1),
(84, 6, 'users', 'a', 3, 128, 1);

--
-- Table structure for table `sed_banlist`
--

CREATE TABLE IF NOT EXISTS `sed_banlist` (
  `banlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `banlist_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `banlist_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `banlist_reason` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `banlist_expire` int(11) DEFAULT '0',
  PRIMARY KEY (`banlist_id`),
  KEY `banlist_ip` (`banlist_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_cache`
--

CREATE TABLE IF NOT EXISTS `sed_cache` (
  `c_name` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `c_expire` int(11) NOT NULL DEFAULT '0',
  `c_auto` tinyint(1) NOT NULL DEFAULT '1',
  `c_value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`c_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `sed_com`
--

CREATE TABLE IF NOT EXISTS `sed_com` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `com_author` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `com_authorid` int(11) DEFAULT NULL,
  `com_authorip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `com_text` text COLLATE utf8_unicode_ci NOT NULL,
  `com_text_ishtml` tinyint(1) DEFAULT '1',
  `com_date` int(11) NOT NULL DEFAULT '0',
  `com_count` int(11) NOT NULL DEFAULT '0',
  `com_rating` tinyint(1) DEFAULT '0',
  `com_isspecial` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`com_id`),
  KEY `com_code` (`com_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_config`
--

CREATE TABLE IF NOT EXISTS `sed_config` (
  `config_owner` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'core',
  `config_cat` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_order` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00',
  `config_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_type` tinyint(2) NOT NULL DEFAULT '0',
  `config_value` text COLLATE utf8_unicode_ci NOT NULL,
  `config_default` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sed_config`
--

INSERT INTO `sed_config` (`config_owner`, `config_cat`, `config_order`, `config_name`, `config_type`, `config_value`, `config_default`, `config_text`) VALUES
('core', 'main', '01', 'maintitle', 1, 'Title of your site', '', ''),
('core', 'main', '02', 'subtitle', 1, 'Subtitle', '', ''),
('core', 'main', '03', 'mainurl', 1, 'http://www.yourdomain.com', '', ''),
('core', 'main', '04', 'adminemail', 1, 'admin@mysite.com', '', ''),
('core', 'main', '05', 'clustermode', 3, '0', '', ''),
('core', 'main', '05', 'hostip', 1, '999.999.999.999', '', ''),
('core', 'main', '06', 'cache', 3, '1', '', ''),
('core', 'main', '06', 'gzip', 3, '1', '', ''),
('core', 'main', '07', 'devmode', 3, '0', '', ''),
('core', 'main', '10', 'cookiedomain', 1, '', '', ''),
('core', 'main', '10', 'cookiepath', 1, '', '', ''),
('core', 'main', '10', 'cookielifetime', 2, '5184000', '', ''),
('core', 'main', '12', 'disablehitstats', 3, '0', '', ''),
('core', 'main', '20', 'shieldenabled', 3, '0', '', ''),
('core', 'main', '20', 'shieldtadjust', 2, '100', '', ''),
('core', 'main', '20', 'shieldzhammer', 2, '25', '', ''),
('core', 'time', '11', 'dateformat', 1, 'Y-m-d H:i', '', ''),
('core', 'time', '11', 'formatmonthday', 1, 'm-d', '', ''),
('core', 'time', '11', 'formatyearmonthday', 1, 'Y-m-d', '', ''),
('core', 'time', '11', 'formatmonthdayhourmin', 1, 'm-d H:i', '', ''),
('core', 'time', '11', 'servertimezone', 1, '0', '', ''),
('core', 'time', '12', 'defaulttimezone', 1, '0', '', ''),
('core', 'time', '14', 'timedout', 2, '1200', '', ''),
('core', 'skin', '02', 'forcedefaultskin', 3, '1', '', ''),
('core', 'skin', '04', 'doctypeid', 4, '8', '', ''),
('core', 'skin', '06', 'charset', 4, 'UTF-8', '', ''),
('core', 'skin', '08', 'metakeywords', 1, '', '', ''),
('core', 'skin', '08', 'separator', 1, '&gt;', '', ''),
('core', 'skin', '15', 'disablesysinfos', 3, '1', '', ''),
('core', 'skin', '15', 'keepcrbottom', 3, '1', '', ''),
('core', 'skin', '15', 'showsqlstats', 3, '0', '', ''),
('core', 'skin', '16', 'defskin', 7, '', '', ''),
('core', 'lang', '10', 'forcedefaultlang', 3, '0', '', ''),
('core', 'menus', '10', 'topline', 0, '', '', ''),
('core', 'menus', '10', 'banner', 0, '', '', ''),
('core', 'menus', '10', 'bottomline', 0, '', '', ''),
('core', 'menus', '15', 'menu1', 0, '<ul><li><a href="index.php">Home</a></li><li><a href="forums.php">Forums</a></li><li><a href="list.php?c=articles">Articles</a></li><li><a href="gallery.php">Galleries</a></li><li><a href="plug.php?e=contact">Contact</a></li></ul>', '', ''),
('core', 'menus', '15', 'menu2', 0, '', '', ''),
('core', 'menus', '15', 'menu3', 0, '', '', ''),
('core', 'menus', '15', 'menu4', 0, '', '', ''),
('core', 'menus', '15', 'menu5', 0, '', '', ''),
('core', 'menus', '15', 'menu6', 0, '', '', ''),
('core', 'menus', '15', 'menu7', 0, '', '', ''),
('core', 'menus', '15', 'menu8', 0, '', '', ''),
('core', 'menus', '15', 'menu9', 0, '', '', ''),
('core', 'menus', '20', 'freetext1', 0, '', '', ''),
('core', 'menus', '20', 'freetext2', 0, '', '', ''),
('core', 'menus', '20', 'freetext3', 0, '', '', ''),
('core', 'menus', '20', 'freetext4', 0, '', '', ''),
('core', 'menus', '20', 'freetext5', 0, '', '', ''),
('core', 'menus', '20', 'freetext6', 0, '', '', ''),
('core', 'menus', '20', 'freetext7', 0, '', '', ''),
('core', 'menus', '20', 'freetext8', 0, '', '', ''),
('core', 'menus', '20', 'freetext9', 0, '', '', ''),
('core', 'comments', '01', 'disable_comments', 3, '0', '', ''),
('core', 'comments', '04', 'showcommentsonpage', 3, '0', '', ''),
('core', 'comments', '05', 'maxcommentsperpage', 2, '30', '', ''),
('core', 'comments', '06', 'maxtimeallowcomedit', 2, '15', '', ''),
('core', 'comments', '10', 'countcomments', 3, '1', '', ''),
('core', 'comments', '11', 'commentsorder', 2, 'ASC', '', ''),
('core', 'forums', '01', 'disable_forums', 3, '0', '', ''),
('core', 'forums', '10', 'hideprivateforums', 3, '0', '', ''),
('core', 'forums', '10', 'hottopictrigger', 2, '20', '', ''),
('core', 'forums', '10', 'maxtopicsperpage', 2, '30', '', ''),
('core', 'forums', '12', 'antibumpforums', 3, '0', '', ''),
('core', 'page', '01', 'disable_page', 3, '0', '', ''),
('core', 'page', '03', 'showpagesubcatgroup', 3, '0', '', ''),
('core', 'page', '05', 'maxrowsperpage', 2, '15', '', ''),
('core', 'parser', '10', 'parser_vid', 3, '1', '', ''),
('core', 'parser', '20', 'parsebbcodeusertext', 3, '1', '', ''),
('core', 'parser', '20', 'parsebbcodecom', 3, '1', '', ''),
('core', 'parser', '20', 'parsebbcodeforums', 3, '1', '', ''),
('core', 'parser', '20', 'parsebbcodepages', 3, '1', '', ''),
('core', 'parser', '30', 'parsesmiliesusertext', 3, '0', '', ''),
('core', 'parser', '30', 'parsesmiliescom', 3, '1', '', ''),
('core', 'parser', '30', 'parsesmiliesforums', 3, '1', '', ''),
('core', 'parser', '30', 'parsesmiliespages', 3, '0', '', ''),
('core', 'rss', '01', 'disable_rss', 3, '0', '', ''),
('core', 'rss', '02', 'disable_rsspages', 3, '0', '', ''),
('core', 'rss', '03', 'disable_rsscomments', 3, '0', '', ''),
('core', 'rss', '04', 'disable_rssforums', 3, '0', '', ''),
('core', 'rss', '05', 'rss_timetolive', 2, '300', '', ''),
('core', 'rss', '06', 'rss_maxitems', 2, '30', '', ''),
('core', 'rss', '07', 'rss_defaultcode', 2, 'news', '', ''),
('core', 'pfs', '01', 'disable_pfs', 3, '0', '', ''),
('core', 'pfs', '02', 'pfs_filemask', 3, '0', '', ''),
('core', 'pfs', '10', 'th_amode', 2, 'GD2', '', ''),
('core', 'pfs', '10', 'th_x', 2, '112', '', ''),
('core', 'pfs', '10', 'th_y', 2, '84', '', ''),
('core', 'pfs', '10', 'th_border', 2, '0', '', ''),
('core', 'pfs', '10', 'th_dimpriority', 2, 'Width', '', ''),
('core', 'pfs', '10', 'th_keepratio', 3, '1', '', ''),
('core', 'pfs', '10', 'th_jpeg_quality', 2, '85', '', ''),
('core', 'pfs', '10', 'th_colorbg', 2, '000000', '', ''),
('core', 'pfs', '10', 'th_colortext', 2, 'FFFFFF', '', ''),
('core', 'pfs', '10', 'th_textsize', 2, '0', '', ''),
('core', 'gallery', '01', 'disable_gallery', 3, '0', '', ''),
('core', 'gallery', '10', 'gallery_gcol', 2, '4', '', ''),
('core', 'gallery', '11', 'gallery_bcol', 2, '6', '', ''),
('core', 'gallery', '12', 'gallery_imgmaxwidth', 2, '600', '', ''),
('core', 'gallery', '20', 'gallery_logofile', 1, '', '', ''),
('core', 'gallery', '21', 'gallery_logopos', 2, 'Bottom left', '', ''),
('core', 'gallery', '22', 'gallery_logotrsp', 2, '50', '', ''),
('core', 'gallery', '23', 'gallery_logojpegqual', 2, '90', '', ''),
('core', 'plug', '01', 'disable_plug', 3, '0', '', ''),
('core', 'pm', '01', 'disable_pm', 3, '0', '', ''),
('core', 'pm', '10', 'pm_maxsize', 2, '10000', '', ''),
('core', 'pm', '10', 'pm_allownotifications', 3, '1', '', ''),
('core', 'polls', '01', 'disable_polls', 3, '1', '', ''),
('core', 'ratings', '01', 'disable_ratings', 3, '1', '', ''),
('core', 'trash', '01', 'trash_prunedelay', 2, '7', '', ''),
('core', 'trash', '10', 'trash_comment', 3, '1', '', ''),
('core', 'trash', '11', 'trash_forum', 3, '1', '', ''),
('core', 'trash', '12', 'trash_page', 3, '1', '', ''),
('core', 'trash', '13', 'trash_pm', 3, '1', '', ''),
('core', 'trash', '14', 'trash_user', 3, '1', '', ''),
('core', 'users', '01', 'disablereg', 3, '0', '', ''),
('core', 'users', '02', 'defaultcountry', 2, '', '', ''),
('core', 'users', '03', 'disablewhosonline', 3, '0', '', ''),
('core', 'users', '05', 'maxusersperpage', 2, '50', '', ''),
('core', 'users', '07', 'regrequireadmin', 3, '0', '', ''),
('core', 'users', '10', 'regnoactivation', 3, '0', '', ''),
('core', 'users', '10', 'useremailchange', 3, '0', '', ''),
('core', 'users', '10', 'usertextimg', 3, '0', '', ''),
('core', 'users', '12', 'av_maxsize', 2, '64000', '', ''),
('core', 'users', '12', 'av_maxx', 2, '128', '', ''),
('core', 'users', '12', 'av_maxy', 2, '128', '', ''),
('core', 'users', '12', 'usertextmax', 2, '300', '', ''),
('core', 'users', '13', 'sig_maxsize', 2, '64000', '', ''),
('core', 'users', '13', 'sig_maxx', 2, '640', '', ''),
('core', 'users', '13', 'sig_maxy', 2, '100', '', ''),
('core', 'users', '14', 'ph_maxsize', 2, '64000', '', ''),
('core', 'users', '14', 'ph_maxx', 2, '256', '', ''),
('core', 'users', '14', 'ph_maxy', 2, '256', '', ''),
('core', 'users', '20', 'extra1title', 1, 'Real name', '', ''),
('core', 'users', '20', 'extra2title', 1, 'Title', '', ''),
('core', 'users', '20', 'extra3title', 1, '', '', ''),
('core', 'users', '20', 'extra4title', 1, '', '', ''),
('core', 'users', '20', 'extra5title', 1, '', '', ''),
('core', 'users', '20', 'extra6title', 1, '', '', ''),
('core', 'users', '20', 'extra7title', 1, '', '', ''),
('core', 'users', '20', 'extra8title', 1, '', '', ''),
('core', 'users', '20', 'extra9title', 1, '', '', ''),
('core', 'users', '20', 'extra1tsetting', 2, '255', '', ''),
('core', 'users', '20', 'extra2tsetting', 2, '255', '', ''),
('core', 'users', '20', 'extra3tsetting', 2, '255', '', ''),
('core', 'users', '20', 'extra4tsetting', 2, '255', '', ''),
('core', 'users', '20', 'extra5tsetting', 2, '255', '', ''),
('core', 'users', '20', 'extra6tsetting', 1, '', '', ''),
('core', 'users', '20', 'extra7tsetting', 1, '', '', ''),
('core', 'users', '20', 'extra8tsetting', 1, '', '', ''),
('core', 'users', '20', 'extra9tsetting', 1, '', '', ''),
('core', 'users', '20', 'extra1uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra2uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra3uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra4uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra5uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra6uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra7uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra8uchange', 3, '0', '', ''),
('core', 'users', '20', 'extra9uchange', 3, '0', '', ''),
('plug', 'ckeditor', '01', 'ckeditor_skin', 2, 'kama', 'kama,office2003,v2', 'Ckeditor skin'),
('plug', 'ckeditor', '01', 'ckeditor_detectlang', 2, 'Yes', 'Yes,No', 'Detect language interface from user profile'),
('plug', 'ckeditor', '02', 'ckeditor_lang', 2, 'en', 'en,ru,af,ar,eu,bn,bs,bg,ca,zh-cn,zh,hr,cs,da,nl,en-au,en-ca,en-gb,eo,et,fo,fi,fr,fr-ca,gl,ka,de,el,gu,he,hi,hu,is,it,ja,km,ko,lv,lt,ms,mn,no,nb,fa,pl,pt-br,pt,ro,sr,sr-latn,sk,sl,es,sv,th,tr,uk,vi,cy', 'Ckeditor default language'),
('plug', 'ckeditor', '03', 'ckeditor_color_toolbar', 2, '#C2CEEA', '#FFC4C4,#FFAD69,#FFCD69,#FFE569,#FFFF69,#BFEE62,#99F299,#91E6E6,#C2CEEA,#B19FEB,#CD98EA,#F299CC', 'Color Toolbar'),
('plug', 'ckeditor', '04', 'ckeditor_other_textarea', 2, 'No', 'Yes,No', 'Use Ckeditor for Other textarea'),
('plug', 'ckeditor', '05', 'ckeditor_other_toolbar', 2, 'Basic', 'Micro,Basic,Extended', 'Default toolbar for Other textarea'),
('plug', 'ckeditor', '06', 'newpagetext', 2, 'Extended', 'Micro,Basic,Extended', 'Default toolbar for Page Add textarea'),
('plug', 'ckeditor', '07', 'rpagetext', 2, 'Extended', 'Micro,Basic,Extended', 'Default toolbar for Page Edit textarea'),
('plug', 'ckeditor', '08', 'newpmtext', 2, 'Basic', 'Micro,Basic,Extended', 'Default toolbar for PM textarea'),
('plug', 'ckeditor', '09', 'rusertext', 2, 'Micro', 'Micro,Basic,Extended', 'Default toolbar for User Text textarea'),
('plug', 'ckeditor', '10', 'rtext', 2, 'Micro', 'Micro,Basic,Extended', 'Default toolbar for Comments textarea'),
('plug', 'ckeditor', '11', 'newmsg', 2, 'Basic', 'Micro,Basic,Extended', 'Default toolbar for Forums textarea'),
('plug', 'ckeditor', '12', 'newpagetext_height', 1, '400', '', 'Height for Page Add textarea'),
('plug', 'ckeditor', '13', 'rpagetext_height', 1, '400', '', 'Height for Page Edit textarea'),
('plug', 'ckeditor', '14', 'newpmtext_height', 1, '200', '', 'Height for PM textarea'),
('plug', 'ckeditor', '15', 'rusertext_height', 1, '150', '', 'Height for User Text textarea'),
('plug', 'ckeditor', '16', 'rtext_height', 1, '150', '', 'Height for Comments textarea'),
('plug', 'ckeditor', '17', 'newmsg_height', 1, '200', '', 'Height for Forums textarea'),
('plug', 'ckeditor', '18', 'ckeditor_other_height', 1, '200', '', 'Height for Other textarea'),
('plug', 'ckeditor', '99', 'ckeditor_grp5', 2, 'Default', 'Default,Micro,Basic,Extended,Full', 'Global toolbar for the group ''Administrators'),
('plug', 'ckeditor', '99', 'ckeditor_grp6', 2, 'Default', 'Default,Micro,Basic,Extended,Full', 'Global toolbar for the group ''Moderators'),
('plug', 'ckeditor', '99', 'ckeditor_grp4', 2, 'Default', 'Default,Micro,Basic,Extended,Full', 'Global toolbar for the group ''Members'),
('plug', 'cleaner', '01', 'trashcan', 2, '15', '3,5,7,10,15,30,60,120', 'Remove the trashcan items after * days (0 to disable).'),
('plug', 'cleaner', '05', 'userprune', 2, '2', '0,1,2,3,4,5,6,7', 'Delete the user accounts not activated within * days (0 to disable).'),
('plug', 'cleaner', '06', 'logprune', 2, '15', '0,1,2,3,7,15,30,60', 'Delete the log entries older than * days (0 to disable).'),
('plug', 'cleaner', '04', 'refprune', 2, '30', '0,15,30,60,120,180,365', 'Delete the referer entries older than * days (0 to disable).'),
('plug', 'cleaner', '05', 'pmnotread', 2, '120', '0,15,30,60,120,180,365', 'Delete the private messages older than * days and not read by the recipient (0 to disable).'),
('plug', 'cleaner', '06', 'pmnotarchived', 2, '180', '0,15,30,60,120,180,365', 'Delete the private messages older than * days and not archived (0 to disable).'),
('plug', 'cleaner', '07', 'pmold', 2, '365', '0,15,30,60,120,180,365', 'Delete ALL the private messages older than * days (0 to disable).'),
('plug', 'contact', '1', 'emails', 0, '', '', 'Emails, separated by commas'),
('plug', 'contact', '2', 'recipients', 0, '', '', 'Names of the recipients, separated by commas, in the same order as the emails'),
('plug', 'contact', '3', 'admincopy1', 1, '', '', 'Also send a copy to this email'),
('plug', 'contact', '4', 'admincopy2', 1, '', '', 'Also send a copy to this email'),
('plug', 'contact', '5', 'extra1', 0, '', '', 'Extra slot #1 / {PLUGIN_CONTACT_EXTRA1} in skins/.../plugin.standalone.contact.tpl'),
('plug', 'contact', '6', 'extra2', 0, '', '', 'Extra slot #2 / {PLUGIN_CONTACT_EXTRA2} in skins/.../plugin.standalone.contact.tpl'),
('plug', 'contact', '7', 'extra3', 0, '', '', 'Extra slot #3 / {PLUGIN_CONTACT_EXTRA2} in skins/.../plugin.standalone.contact.tpl'),
('plug', 'jevix', '01', 'use_xhtml', 2, 'yes', 'yes,no', 'Use XHTML standart'),
('plug', 'jevix', '02', 'use_for_admin', 2, 'yes', 'yes,no', 'Use Jevix for Administrators'),
('plug', 'news', '01', 'category', 1, 'news', '', 'Category code of the parent category'),
('plug', 'news', '02', 'maxpages', 2, '10', '0,1,2,3,4,5,6,7,8,9,10,15,20,25,30,50,100', 'Recent pages displayed'),
('plug', 'passrecover', '01', 'generate_password', 2, 'no', 'yes,no', 'Generate a new password and send to the email?'),
('plug', 'recentitems', '01', 'maxpages', 2, '5', '0,1,2,3,4,5,6,7,8,9,10,15,20,25,30', 'Recent pages displayed'),
('plug', 'recentitems', '02', 'maxtopics', 2, '5', '0,1,2,3,4,5,6,7,8,9,10,15,20,25,30', 'Recent topics in forums displayed'),
('plug', 'recentitems', '03', 'maxpolls', 2, '1', '0,1,2,3,4,5', 'Recent polls displayed'),
('plug', 'syntaxhighlight', '12', 'syntaxhighlight_theme', 2, 'Default', 'Default,Django,Eclipse,Emacs,FadeToGrey,Midnight,RDark', 'Theme Syntaxhighlight');

--
-- Table structure for table `sed_core`
--

CREATE TABLE IF NOT EXISTS `sed_core` (
  `ct_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ct_code` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ct_lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ct_id`),
  KEY `ct_code` (`ct_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `sed_core`
--

INSERT INTO `sed_core` (`ct_id`, `ct_code`, `ct_title`, `ct_version`, `ct_state`, `ct_lock`) VALUES
(1, 'admin', 'Administration panel', '100', 1, 1),
(2, 'comments', 'Comments', '100', 1, 0),
(3, 'forums', 'Forums', '100', 1, 0),
(4, 'index', 'Home page', '100', 1, 1),
(5, 'message', 'Messages', '100', 1, 1),
(6, 'page', 'Pages', '100', 1, 0),
(7, 'pfs', 'Personal File Space', '100', 1, 0),
(8, 'plug', 'Plugins', '100', 1, 0),
(9, 'pm', 'Private messages', '100', 1, 0),
(10, 'polls', 'Polls', '100', 1, 0),
(11, 'ratings', 'Ratings', '100', 1, 0),
(12, 'users', 'Users', '100', 1, 1),
(13, 'trash', 'Trash Can', '110', 1, 1),
(14, 'gallery', 'Gallery', '150', 1, 0);

--
-- Table structure for table `sed_forum_posts`
--

CREATE TABLE IF NOT EXISTS `sed_forum_posts` (
  `fp_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fp_topicid` mediumint(8) NOT NULL DEFAULT '0',
  `fp_sectionid` smallint(5) NOT NULL DEFAULT '0',
  `fp_posterid` int(11) NOT NULL DEFAULT '-1',
  `fp_postername` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fp_creation` int(11) NOT NULL DEFAULT '0',
  `fp_updated` int(11) NOT NULL DEFAULT '0',
  `fp_updater` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `fp_text` text COLLATE utf8_unicode_ci NOT NULL,
  `fp_text_ishtml` tinyint(1) DEFAULT '1',
  `fp_posterip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fp_rating` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`fp_id`),
  UNIQUE KEY `fp_topicid` (`fp_topicid`,`fp_id`),
  KEY `fp_updated` (`fp_creation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_forum_sections`
--

CREATE TABLE IF NOT EXISTS `sed_forum_sections` (
  `fs_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `fs_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fs_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fs_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_category` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_parentcat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fs_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_lt_id` int(11) NOT NULL DEFAULT '0',
  `fs_lt_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_lt_date` int(11) NOT NULL DEFAULT '0',
  `fs_lt_posterid` int(11) NOT NULL DEFAULT '-1',
  `fs_lt_postername` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fs_autoprune` int(11) NOT NULL DEFAULT '0',
  `fs_allowusertext` tinyint(1) NOT NULL DEFAULT '1',
  `fs_allowbbcodes` tinyint(1) NOT NULL DEFAULT '1',
  `fs_allowsmilies` tinyint(1) NOT NULL DEFAULT '1',
  `fs_allowprvtopics` tinyint(1) NOT NULL DEFAULT '0',
  `fs_countposts` tinyint(1) NOT NULL DEFAULT '1',
  `fs_topiccount` mediumint(8) NOT NULL DEFAULT '0',
  `fs_topiccount_pruned` int(11) DEFAULT '0',
  `fs_postcount` mediumint(8) NOT NULL DEFAULT '0',
  `fs_postcount_pruned` int(11) DEFAULT '0',
  `fs_viewcount` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fs_id`),
  KEY `fs_order` (`fs_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sed_forum_sections`
--

INSERT INTO `sed_forum_sections` (`fs_id`, `fs_state`, `fs_order`, `fs_title`, `fs_category`, `fs_parentcat`, `fs_desc`, `fs_icon`, `fs_lt_id`, `fs_lt_title`, `fs_lt_date`, `fs_lt_posterid`, `fs_lt_postername`, `fs_autoprune`, `fs_allowusertext`, `fs_allowbbcodes`, `fs_allowsmilies`, `fs_allowprvtopics`, `fs_countposts`, `fs_topiccount`, `fs_topiccount_pruned`, `fs_postcount`, `fs_postcount_pruned`, `fs_viewcount`) VALUES
(1, 0, 100, 'General discussion', 'pub', 0, 'General chat.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0),
(2, 0, 101, 'Off-topic', 'pub', 0, 'Various and off-topic.', 'system/img/admin/forums.png', 0, '', 0, 0, '', 365, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0);

--
-- Table structure for table `sed_forum_structure`
--

CREATE TABLE IF NOT EXISTS `sed_forum_structure` (
  `fn_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `fn_path` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_tpl` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_title` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_icon` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fn_defstate` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fn_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sed_forum_structure`
--

INSERT INTO `sed_forum_structure` (`fn_id`, `fn_path`, `fn_code`, `fn_tpl`, `fn_title`, `fn_desc`, `fn_icon`, `fn_defstate`) VALUES
(1, '1', 'pub', '', 'Public', '', '', 1);

--
-- Table structure for table `sed_forum_topics`
--

CREATE TABLE IF NOT EXISTS `sed_forum_topics` (
  `ft_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ft_mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ft_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ft_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ft_tag` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ft_sectionid` mediumint(8) NOT NULL DEFAULT '0',
  `ft_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ft_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ft_creationdate` int(11) NOT NULL DEFAULT '0',
  `ft_updated` int(11) NOT NULL DEFAULT '0',
  `ft_postcount` mediumint(8) NOT NULL DEFAULT '0',
  `ft_viewcount` mediumint(8) NOT NULL DEFAULT '0',
  `ft_lastposterid` int(11) NOT NULL DEFAULT '-1',
  `ft_lastpostername` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ft_firstposterid` int(11) NOT NULL DEFAULT '-1',
  `ft_firstpostername` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ft_poll` int(11) DEFAULT '0',
  `ft_movedto` int(11) DEFAULT '0',
  PRIMARY KEY (`ft_id`),
  KEY `ft_updated` (`ft_updated`),
  KEY `ft_mode` (`ft_mode`),
  KEY `ft_state` (`ft_state`),
  KEY `ft_sticky` (`ft_sticky`),
  KEY `ft_sectionid` (`ft_sectionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_groups`
--

CREATE TABLE IF NOT EXISTS `sed_groups` (
  `grp_id` int(11) NOT NULL AUTO_INCREMENT,
  `grp_alias` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_level` tinyint(2) NOT NULL DEFAULT '1',
  `grp_disabled` tinyint(1) NOT NULL DEFAULT '0',
  `grp_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `grp_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_icon` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_pfs_maxfile` int(11) NOT NULL DEFAULT '0',
  `grp_pfs_maxtotal` int(11) NOT NULL DEFAULT '0',
  `grp_ownerid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sed_groups`
--

INSERT INTO `sed_groups` (`grp_id`, `grp_alias`, `grp_level`, `grp_disabled`, `grp_hidden`, `grp_title`, `grp_desc`, `grp_icon`, `grp_pfs_maxfile`, `grp_pfs_maxtotal`, `grp_ownerid`) VALUES
(1, 'guests', 0, 0, 0, 'Guests', '', '', 0, 0, 1),
(2, 'inactive', 1, 0, 0, 'Inactive', '', '', 0, 0, 1),
(3, 'banned', 1, 0, 0, 'Banned', '', '', 0, 0, 1),
(4, 'members', 1, 0, 0, 'Members', '', '', 0, 0, 1),
(5, 'administrators', 99, 0, 0, 'Administrators', '', '', 256, 1024, 1),
(6, 'moderators', 50, 0, 0, 'Moderators', '', '', 256, 1024, 1);

--
-- Table structure for table `sed_groups_users`
--

CREATE TABLE IF NOT EXISTS `sed_groups_users` (
  `gru_userid` int(11) NOT NULL DEFAULT '0',
  `gru_groupid` int(11) NOT NULL DEFAULT '0',
  `gru_state` tinyint(1) NOT NULL DEFAULT '0',
  `gru_extra1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gru_extra2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  UNIQUE KEY `gru_groupid` (`gru_groupid`,`gru_userid`),
  KEY `gru_userid` (`gru_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `sed_logger`
--

CREATE TABLE IF NOT EXISTS `sed_logger` (
  `log_id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `log_date` int(11) NOT NULL DEFAULT '0',
  `log_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `log_name` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `log_group` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'def',
  `log_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_online`
--

CREATE TABLE IF NOT EXISTS `sed_online` (
  `online_id` int(11) NOT NULL AUTO_INCREMENT,
  `online_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_name` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_lastseen` int(11) NOT NULL DEFAULT '0',
  `online_location` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_subloc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_userid` int(11) NOT NULL DEFAULT '0',
  `online_shield` int(11) NOT NULL DEFAULT '0',
  `online_action` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_hammer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`online_id`),
  KEY `online_lastseen` (`online_lastseen`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Table structure for table `sed_pages`
--

CREATE TABLE IF NOT EXISTS `sed_pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_type` tinyint(1) DEFAULT '0',
  `page_cat` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_key` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_extra1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_extra2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_extra3` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_extra4` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_extra5` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_text` text COLLATE utf8_unicode_ci,
  `page_text_ishtml` tinyint(1) DEFAULT '1',
  `page_text2` text COLLATE utf8_unicode_ci,
  `page_author` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_ownerid` int(11) NOT NULL DEFAULT '0',
  `page_date` int(11) NOT NULL DEFAULT '0',
  `page_begin` int(11) NOT NULL DEFAULT '0',
  `page_expire` int(11) NOT NULL DEFAULT '0',
  `page_file` tinyint(1) DEFAULT NULL,
  `page_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_size` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_count` mediumint(8) unsigned DEFAULT '0',
  `page_allowcomments` tinyint(1) NOT NULL DEFAULT '1',
  `page_allowratings` tinyint(1) NOT NULL DEFAULT '1',
  `page_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `page_comcount` mediumint(8) unsigned DEFAULT '0',
  `page_filecount` mediumint(8) unsigned DEFAULT '0',
  `page_alias` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`page_id`),
  KEY `page_cat` (`page_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sed_pages`
--

INSERT INTO `sed_pages` (`page_id`, `page_state`, `page_type`, `page_cat`, `page_key`, `page_extra1`, `page_extra2`, `page_extra3`, `page_extra4`, `page_extra5`, `page_title`, `page_desc`, `page_text`, `page_text_ishtml`, `page_text2`, `page_author`, `page_ownerid`, `page_date`, `page_begin`, `page_expire`, `page_file`, `page_url`, `page_size`, `page_count`, `page_allowcomments`, `page_allowratings`, `page_rating`, `page_comcount`, `page_filecount`, `page_alias`) VALUES
(1, 0, 1, 'news', '', '', '', '', '', '', 'Welcome !', '...', 'Congratulations, your website is up and running !<br />\r\n<br />\r\nThe next step is to go in the <a href="admin.php">Administration panel</a>, tab <a href="admin.php?m=config">Configuration</a>, and there tweak the settings for the system.<br />\r\nYou''ll find more instructions and tutorials in the <a href="http://www.seditio.org/list.php?c=docs">Documentation page for Seditio at Seditio.org</a>, and technical support in our <a href="http://www.seditio.org/forums.php">discussion forums</a>.', 1, '', '', 1, 1263945600, 1263942000, 1861959600, 0, '', '', 38, 1, 1, 0.00, 0, 0, '');

--
-- Table structure for table `sed_parser`
--

CREATE TABLE IF NOT EXISTS `sed_parser` (
  `parser_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parser_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parser_type` tinyint(1) DEFAULT '0',
  `parser_mode` tinyint(1) DEFAULT '0',
  `parser_order` int(11) NOT NULL DEFAULT '0',
  `parser_bb1` mediumtext COLLATE utf8_unicode_ci,
  `parser_bb2` mediumtext COLLATE utf8_unicode_ci,
  `parser_code1` mediumtext COLLATE utf8_unicode_ci,
  `parser_code2` mediumtext COLLATE utf8_unicode_ci,
  `parser_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`parser_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

--
-- Dumping data for table `sed_parser`
--

INSERT INTO `sed_parser` (`parser_id`, `parser_title`, `parser_type`, `parser_mode`, `parser_order`, `parser_bb1`, `parser_bb2`, `parser_code1`, `parser_code2`, `parser_active`) VALUES
(1, 'Bold', 0, 0, 1, '[b]', '[/b]', '<strong>', '</strong>', 1),
(2, 'Underline', 0, 0, 2, '[u]', '[/u]', '<u>', '</u>', 1),
(3, 'Italic', 0, 0, 3, '[i]', '[/i]', '<em>', '</em>', 1),
(4, 'Horizontal ruler', 0, 0, 4, '[hr]', '', '<hr />', '', 1),
(5, 'Spacers', 0, 0, 5, '[_]', '[__]', '&nbsp;', '&nbsp; &nbsp;', 1),
(6, 'Lists 1', 0, 0, 6, '[list]', '[/list]', '<ul type="square">', '</ul>', 1),
(7, 'Lists 2', 0, 0, 7, '[li]', '[/li]', '<li>', '</li>', 1),
(8, 'Color red', 0, 0, 8, '[red]', '[/red]', '<span style="color:#F93737">', '</span>', 1),
(9, 'Color white', 0, 0, 9, '[white]', '[/white]', '<span style="color:#FFFFFF">', '</span>', 1),
(10, 'Color green', 0, 0, 10, '[green]', '[/green]', '<span style="color:#09DD09">', '</span>', 1),
(11, 'Color blue', 0, 0, 11, '[blue]', '[/blue]', '<span style="color:#018BFF">', '</span>', 1),
(12, 'Color orange', 0, 0, 12, '[orange]', '[/orange]', '<span style="color:#FF9900">', '</span>', 1),
(13, 'Color yellow', 0, 0, 13, '[yellow]', '[/yellow]', '<span style="color:#FFFF00">', '</span>', 1),
(14, 'Color purple', 0, 0, 14, '[purple]', '[/purple]', '<span style="color:#A22ADA">', '</span>', 1),
(15, 'Color black', 0, 0, 15, '[black]', '[/black]', '<span style="color:#000000">', '</span>', 1),
(16, 'Color grey', 0, 0, 16, '[grey]', '[/grey]', '<span style="color:#B9B9B9">', '</span>', 1),
(17, 'Color pink', 0, 0, 17, '[pink]', '[/pink]', '<span style="color:#FFC0FF">', '</span>', 1),
(18, 'Color sky', 0, 0, 18, '[sky]', '[/sky]', '<span style="color:#D1F4F9">', '</span>', 1),
(19, 'Color sea', 0, 0, 19, '[sea]', '[/sea]', '<span style="color:#171A97">', '</span>', 1),
(20, 'Quote', 0, 0, 20, '[quote]', '[/quote]', '<blockquote>Quote<p>', '</p></blockquote>', 1),
(21, 'BR', 0, 0, 21, '[br]', '', '<br />', '', 1),
(22, 'More', 0, 0, 22, '[more]', '', '<!--readmore-->', '', 1),
(23, 'Image 1', 0, 1, 10, '/\\[img\\]([^\\\\\\''\\;\\?[]*)\\.(jpg|jpeg|gif|png)\\[\\/img\\]/i', '', '<img src="$1.$2" alt="" />', '', 1),
(24, 'Image 2', 0, 1, 20, '/\\[img=([^\\\\\\''\\;\\?[]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/img\\]/i', '', '<a href="$1.$2"><img src="$3.$4" alt="" /></a>', '', 1),
(25, 'Thumbnail PFS', 0, 1, 30, '/\\[thumb=([^\\\\\\''\\;\\?([]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/thumb\\]/i', NULL, '<a href="javascript:picture(''pfs.php?m=view&amp;v=$3.$4'', 200,200)"><img src="$1.$2" alt="" /></a>', NULL, 1),
(26, 'Thumbnails', 0, 1, 40, '/\\[t=([^\\\\\\''\\;\\?([]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/t\\]/i', NULL, '<a href="$3.$4"><img src="$1.$2" alt="" /></a>', NULL, 1),
(27, 'Url 1', 0, 1, 50, '/\\[url\\]([^\\\\([]*)\\[\\/url\\]/i', NULL, '<a href="$1">$1</a>', NULL, 1),
(28, 'Url 2', 0, 1, 60, '/\\[url=([^\\\\\\''\\;([]*)\\]([^\\\\[]*)\\[\\/url\\]/i', NULL, '<a href="$1">$2</a>', NULL, 1),
(29, 'Colors', 0, 1, 70, '/\\[color=([0-9A-F]{6})\\]([^\\\\[]*)\\[\\/color\\]/i', '', '<span style="color:#$1">$2</span>', NULL, 1),
(30, 'Styles', 0, 1, 80, '/\\[style=([1-9]{1})\\]([^\\\\[]*)\\[\\/style\\]/i', NULL, '<span class="bbstyle$1">$2</span>', NULL, 1),
(31, 'Divs', 0, 1, 90, '/\\[div=([1-9]{1})\\]([^\\\\[]*)\\[\\/div\\]/i', NULL, '<div class="divstyle$1">$2</div>', NULL, 1),
(32, 'Email 2', 0, 1, 100, '/\\[email=([._A-z0-9-]+@[A-z0-9-]+\\.[.a-z]+)\\]([^\\\\[]*)\\[\\/email\\]/i', NULL, '<a href="mailto:$1">$2</a>', NULL, 1),
(33, 'Email 1', 0, 1, 110, '/\\[email\\]([._A-z0-9-]+@[A-z0-9-]+\\.[.a-z]+)\\[\\/email\\]/i', NULL, '<a href="mailto:$1">$1</a>', NULL, 1),
(34, 'user', 0, 1, 120, '/\\[user=([0-9]+)\\]([A-z0-9_\\. -]+)\\[\\/user\\]/i', NULL, '<a href="users.php?m=details&amp;id=$1">$2</a>', NULL, 1),
(35, 'Page 2', 0, 1, 130, '/\\[page=([0-9]+)\\]([^\\\\[]*)\\[\\/page\\]/i', NULL, '<a href="page.php?id=$1">$2</a>', NULL, 1),
(36, 'Page 1', 0, 1, 140, '/\\[page\\]([0-9]+)\\[\\/page\\]/i', NULL, '<a href="page.php?id=$1">Page #$1</a>', NULL, 1),
(37, 'Group', 0, 1, 150, '/\\[group=([0-9]+)\\]([^\\\\([]*)\\[\\/group\\]/i', NULL, '<a href="users.php?g=$1">$2</a>', NULL, 1),
(38, 'Forum topic', 0, 1, 160, '/\\[topic\\]([0-9]+)\\[\\/topic\\]/i', NULL, '<a href="forums.php?m=posts&amp;q=$1">Topic #$1</a>', NULL, 1),
(39, 'Forum post', 0, 1, 170, '/\\[post\\]([0-9]+)\\[\\/post\\]/i', NULL, '<a href="forums.php?m=posts&amp;p=$1#$1">Post #$1</a>', NULL, 1),
(40, 'Private messages', 0, 1, 180, '/\\[pm\\]([0-9]+)\\[\\/pm\\]/i', NULL, '<a href="pm.php?m=send&amp;to=$1">PM</a>', NULL, 1),
(41, 'Flag', 0, 1, 190, '/\\[f\\]([a-z][a-z])\\[\\/f\\]/i', NULL, '<a href="users.php?f=country_$1"><img src="system/img/flags/f-$1.gif" alt="" /></a>', NULL, 1),
(42, 'Acronym', 0, 1, 200, '/\\[ac=([^\\\\[]*)\\]([^\\\\[]*)\\[\\/ac\\]/i', NULL, '<acronym title="$1">$2</acronym>', NULL, 1),
(43, 'Deleted', 0, 1, 210, '/\\[del\\]([^\\\\[]*)\\[\\/del\\]/i', NULL, '<del>$1</del>', NULL, 1),
(44, 'Quote 2', 0, 1, 220, '/\\[quote=([^\\\\[]*)\\]/i', NULL, '<blockquote>$1<p>', NULL, 1),
(45, 'Spoiler', 0, 1, 230, '/\\[spoiler=([^\\\\[]*)\\]/i', '/\\[\\/spoiler\\]/i', '<div style="margin:0; margin-top:8px"><div style="margin-bottom:4px"><input type="button" value="Show : $1" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.innerText = ''''; this.value = ''Hide : : $1''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.innerText = ''''; this.value = ''Show : $1''; }"></div><div class="spoiler"><div style="display: none;">', '</div></div></div>', 1),
(46, 'Spoiler 2', 0, 1, 230, '/\\[spoiler\\]/i', '/\\[\\/spoiler\\]/i', '<div style="margin:0; margin-top:8px"><div style="margin-bottom:4px"><input type="button" value="Show" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.innerText = ''''; this.value = ''Hide''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.innerText = ''''; this.value = ''Show''; }"></div><div class="spoiler"><div style="display: none;">', '</div></div></div>', 1),
(47, 'Fold', 0, 1, 240, '/\\[fold=([^\\\\[]*)\\]/i', '/\\[\\/fold\\]/i', '<div style="margin:0;"><div class="fhead"><a href="#fold" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.value = ''$1''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.value = ''$1''; }">$1</a></div><div><div style="display: none;" class="fblock">', '</div></div></div>', 1),
(48, 'Youtube', 0, 1, 250, '/\\[youtube=([^\\\\[]*)\\]/i', NULL, '<object width="425" height="350">\r\n<param name="movie" value="http://www.youtube.com/v/$1"></param>\r\n<embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="425" height="350"></embed>\r\n</object>', NULL, 1),
(49, 'Google Video', 0, 1, 260, '/\\[googlevideo=([^\\\\[]*)\\]/i', NULL, '<embed style="width:425px; height:326px;" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=$1&hl=en-GB"> </embed>', NULL, 1),
(50, 'MetaCafe video', 0, 1, 270, '/\\[metacafe=([^\\\\[]*)\\]/i', NULL, '<embed style="width:425px; height:345px;" src="http://www.metacafe.com/fplayer/$1" width="400" height="345" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>', NULL, 1),
(51, 'Column left', 0, 1, 280, '/\\[colleft\\]([^\\\\[]*)\\[\\/colleft\\]/i', NULL, '<div class="colleft">$1</div>', NULL, 1),
(52, 'Column right', 0, 1, 290, '/\\[colright\\]([^\\\\[]*)\\[\\/colright\\]/i', NULL, '<div class="colright">$1</div>', NULL, 1),
(53, 'Center', 0, 1, 300, '/\\[center\\]([^\\\\[]*)\\[\\/center\\]/i', NULL, '<div style="text-align:center;">$1</div>', NULL, 1),
(54, 'Align left', 0, 1, 310, '/\\[left\\]([^\\\\[]*)\\[\\/left\\]/i', NULL, '<div style="text-align:left;">$1</div>', NULL, 1),
(55, 'Align right', 0, 1, 320, '/\\[right\\]([^\\\\[]*)\\[\\/right\\]/i', NULL, '<div style="text-align:right;">$1</div>', NULL, 1),
(56, 'Columns', 0, 1, 330, '/\\[c1\\:([^\\\\[]*)\\]([^\\\\[]*)\\[c2\\:([^\\\\[]*)\\]([^\\\\[]*)\\[c3\\]/i', NULL, '<table style="margin:0; vertical-align:top; width:100%;"><tr><td style="padding:0 16px 16px 0; vertical-align:top; width:$1%;">$2</td><td  style="padding:0 0 16px 16px; vertical-align:top; width:$3%;">$4</td></tr></table>', NULL, 1),
(57, 'Paragraph', 0, 0, 23, '[p]', '[/p]', '<p>', '</p>', 1);

--
-- Table structure for table `sed_pfs`
--

CREATE TABLE IF NOT EXISTS `sed_pfs` (
  `pfs_id` int(11) NOT NULL AUTO_INCREMENT,
  `pfs_userid` int(11) NOT NULL DEFAULT '0',
  `pfs_date` int(11) NOT NULL DEFAULT '0',
  `pfs_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pfs_extension` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pfs_folderid` int(11) NOT NULL DEFAULT '0',
  `pfs_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `pfs_size` int(11) NOT NULL DEFAULT '0',
  `pfs_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pfs_id`),
  KEY `pfs_userid` (`pfs_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_pfs_folders`
--

CREATE TABLE IF NOT EXISTS `sed_pfs_folders` (
  `pff_id` int(11) NOT NULL AUTO_INCREMENT,
  `pff_userid` int(11) NOT NULL DEFAULT '0',
  `pff_date` int(11) NOT NULL DEFAULT '0',
  `pff_updated` int(11) NOT NULL DEFAULT '0',
  `pff_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pff_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pff_type` tinyint(1) NOT NULL DEFAULT '0',
  `pff_sample` int(11) NOT NULL DEFAULT '0',
  `pff_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pff_id`),
  KEY `pff_userid` (`pff_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_plugins`
--

CREATE TABLE IF NOT EXISTS `sed_plugins` (
  `pl_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `pl_hook` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_code` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_part` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_order` tinyint(2) unsigned NOT NULL DEFAULT '10',
  `pl_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `sed_plugins`
--

INSERT INTO `sed_plugins` (`pl_id`, `pl_hook`, `pl_code`, `pl_part`, `pl_title`, `pl_file`, `pl_order`, `pl_active`) VALUES
(1, 'admin.home', 'adminqv', 'main', 'Admin QuickView', 'adminqv', 10, 1),
(2, 'pfs.stndl.icons', 'ckeditor', 'pfs', 'Ckeditor', 'ckeditor.pfs.stndl.icons', 10, 1),
(3, 'pfs.stndl', 'ckeditor', 'pfs', 'Ckeditor', 'ckeditor.pfs.stndl', 10, 1),
(4, 'header.first', 'ckeditor', 'Loader', 'Ckeditor', 'ckeditor', 10, 1),
(5, 'admin.home', 'cleaner', 'main', 'Cleaner', 'cleaner', 10, 1),
(6, 'standalone', 'contact', 'main', 'Contact', 'contact', 10, 1),
(7, 'import.filter', 'jevix', 'jeviximport', 'Jevix 1.3b', 'jevix.import.filter', 10, 1),
(8, 'tools', 'massmovetopics', 'admin', 'Mass-move topics in forums', 'massmovetopics.admin', 10, 1),
(9, 'index.tags', 'news', 'homepage', 'News', 'news', 10, 1),
(10, 'standalone', 'passrecover', 'main', 'Password recovery', 'passrecover', 10, 1),
(11, 'index.tags', 'recentitems', 'main', 'Recent items', 'recentitems', 10, 1),
(12, 'standalone', 'search', 'main', 'Search', 'search', 10, 1),
(13, 'tools', 'skineditor', 'admin', 'Skin editor', 'skineditor.admin', 10, 1),
(14, 'common.tool.skineditor', 'skineditor', 'common', 'Skin editor', 'skineditor.common', 10, 1),
(15, 'standalone', 'statistics', 'main', 'Statistics', 'statistics', 10, 1),
(16, 'header.first', 'syntaxhighlight', 'Loader', 'Syntaxhighlight 1.1', 'syntaxhighlight', 10, 1),
(17, 'tools', 'syscheck', 'admin', 'System check', 'syscheck.admin', 10, 1),
(18, 'standalone', 'whosonline', 'main', 'Who''s online', 'whosonline', 10, 1);

--
-- Table structure for table `sed_pm`
--

CREATE TABLE IF NOT EXISTS `sed_pm` (
  `pm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pm_state` tinyint(2) NOT NULL DEFAULT '0',
  `pm_date` int(11) NOT NULL DEFAULT '0',
  `pm_fromuserid` int(11) NOT NULL DEFAULT '0',
  `pm_fromuser` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `pm_touserid` int(11) NOT NULL DEFAULT '0',
  `pm_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `pm_text` text COLLATE utf8_unicode_ci NOT NULL,
  `pm_text_ishtml` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`pm_id`),
  KEY `pm_fromuserid` (`pm_fromuserid`),
  KEY `pm_touserid` (`pm_touserid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_polls`
--

CREATE TABLE IF NOT EXISTS `sed_polls` (
  `poll_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `poll_type` tinyint(1) DEFAULT '0',
  `poll_state` tinyint(1) NOT NULL DEFAULT '0',
  `poll_creationdate` int(11) NOT NULL DEFAULT '0',
  `poll_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`poll_id`),
  KEY `poll_creationdate` (`poll_creationdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_polls_options`
--

CREATE TABLE IF NOT EXISTS `sed_polls_options` (
  `po_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `po_pollid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `po_text` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `po_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_id`),
  KEY `po_pollid` (`po_pollid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_polls_voters`
--

CREATE TABLE IF NOT EXISTS `sed_polls_voters` (
  `pv_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pv_pollid` mediumint(8) NOT NULL DEFAULT '0',
  `pv_userid` mediumint(8) NOT NULL DEFAULT '0',
  `pv_userip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`pv_id`),
  KEY `pv_pollid` (`pv_pollid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_rated`
--

CREATE TABLE IF NOT EXISTS `sed_rated` (
  `rated_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rated_code` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rated_userid` int(11) DEFAULT NULL,
  `rated_value` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rated_id`),
  KEY `rated_code` (`rated_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_ratings`
--

CREATE TABLE IF NOT EXISTS `sed_ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rating_state` tinyint(2) NOT NULL DEFAULT '0',
  `rating_average` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rating_creationdate` int(11) NOT NULL DEFAULT '0',
  `rating_text` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rating_id`),
  KEY `rating_code` (`rating_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `sed_referers`
--

CREATE TABLE IF NOT EXISTS `sed_referers` (
  `ref_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ref_date` int(11) unsigned NOT NULL DEFAULT '0',
  `ref_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ref_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `sed_smilies`
--

CREATE TABLE IF NOT EXISTS `sed_smilies` (
  `smilie_id` int(11) NOT NULL AUTO_INCREMENT,
  `smilie_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `smilie_image` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `smilie_text` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `smilie_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`smilie_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `sed_smilies`
--

INSERT INTO `sed_smilies` (`smilie_id`, `smilie_code`, `smilie_image`, `smilie_text`, `smilie_order`) VALUES
(1, ':D', 'system/smilies/icon_biggrin.gif', 'Mister grin', 5),
(2, ':blush', 'system/smilies/icon_blush.gif', 'Blush', 45),
(3, ':con', 'system/smilies/icon_confused.gif', 'Confused', 42),
(4, ':)', 'system/smilies/icon_smile.gif', 'Smile', 1),
(5, ':cry', 'system/smilies/icon_cry.gif', 'Cry', 44),
(6, ':dontgetit', 'system/smilies/icon_dontgetit.gif', 'Don''t get it', 41),
(7, ':dozingoff', 'system/smilies/icon_dozingoff.gif', 'Dozing off', 40),
(8, ':love', 'system/smilies/icon_love.gif', 'Love', 10),
(9, ':((', 'system/smilies/icon_mad.gif', 'Mad', 50),
(10, ':|', 'system/smilies/icon_neutral.gif', 'Neutral', 43),
(11, ':no', 'system/smilies/icon_no.gif', 'No', 12),
(12, ':O_o', 'system/smilies/icon_o_o.gif', 'Suspicious', 7),
(13, ':p', 'system/smilies/icon_razz.gif', 'Razz', 6),
(14, ':(', 'system/smilies/icon_sad.gif', 'Sad', 46),
(15, ':satisfied', 'system/smilies/icon_satisfied.gif', 'Satisfied', 2),
(16, '8)', 'system/smilies/icon_cool.gif', 'Cool', 4),
(17, ':wink', 'system/smilies/icon_wink.gif', 'Wink', 3),
(18, ':yes', 'system/smilies/icon_yes.gif', 'Yes', 11);

--
-- Table structure for table `sed_stats`
--

CREATE TABLE IF NOT EXISTS `sed_stats` (
  `stat_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `stat_value` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stat_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sed_stats`
--

INSERT INTO `sed_stats` (`stat_name`, `stat_value`) VALUES
('totalpages', 1),
('totalmailsent', 0),
('totalmailpmnot', 0),
('totalpms', 0),
('totalantihammer', 0),
('textboxerprev', 0),
('version', 175),
('installed', 1),
('maxusers', 1),
('2013-06-25', 1);

--
-- Table structure for table `sed_structure`
--

CREATE TABLE IF NOT EXISTS `sed_structure` (
  `structure_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `structure_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_path` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_tpl` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_title` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_icon` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_group` tinyint(1) NOT NULL DEFAULT '0',
  `structure_order` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'title.asc',
  `structure_allowcomments` tinyint(1) NOT NULL DEFAULT '1',
  `structure_allowratings` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`structure_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sed_structure`
--

INSERT INTO `sed_structure` (`structure_id`, `structure_code`, `structure_path`, `structure_tpl`, `structure_title`, `structure_desc`, `structure_icon`, `structure_group`, `structure_order`, `structure_allowcomments`, `structure_allowratings`) VALUES
(1, 'articles', '1', '', 'Articles', '', '', 1, 'title.asc', 1, 1),
(2, 'sample1', '1.1', '', 'Sample category 1', 'Description for the Sample category 1', '', 0, 'title.asc', 1, 1),
(3, 'sample2', '1.2', '', 'Sample category 2', 'Description for the Sample category 2', '', 0, 'title.asc', 1, 1),
(4, 'news', '2', '', 'News', '', '', 0, 'date.desc', 1, 1);

--
-- Table structure for table `sed_trash`
--

CREATE TABLE IF NOT EXISTS `sed_trash` (
  `tr_id` int(11) NOT NULL AUTO_INCREMENT,
  `tr_date` int(11) unsigned NOT NULL DEFAULT '0',
  `tr_type` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tr_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tr_itemid` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tr_trashedby` int(11) NOT NULL DEFAULT '0',
  `tr_datas` mediumblob,
  PRIMARY KEY (`tr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- Table structure for table `sed_users`
--

CREATE TABLE IF NOT EXISTS `sed_users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_banexpire` int(11) DEFAULT '0',
  `user_name` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_salt` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_secret` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_passtype` tinyint(1) DEFAULT '1',
  `user_maingrp` int(11) unsigned NOT NULL DEFAULT '4',
  `user_country` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_text` text COLLATE utf8_unicode_ci NOT NULL,
  `user_text_ishtml` tinyint(1) DEFAULT '1',
  `user_avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra3` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra4` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra5` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_extra6` text COLLATE utf8_unicode_ci,
  `user_extra7` text COLLATE utf8_unicode_ci,
  `user_extra8` text COLLATE utf8_unicode_ci,
  `user_extra9` text COLLATE utf8_unicode_ci,
  `user_occupation` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_location` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_timezone` decimal(2,0) NOT NULL DEFAULT '0',
  `user_birthdate` int(11) NOT NULL DEFAULT '0',
  `user_gender` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'U',
  `user_irc` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_msn` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_icq` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_website` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_hideemail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_pmnotify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_newpm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_skin` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_lang` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_regdate` int(11) NOT NULL DEFAULT '0',
  `user_lastlog` int(11) NOT NULL DEFAULT '0',
  `user_lastvisit` int(11) NOT NULL DEFAULT '0',
  `user_lastip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_logcount` int(11) unsigned NOT NULL DEFAULT '0',
  `user_postcount` int(11) DEFAULT '0',
  `user_sid` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_lostpass` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_auth` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;