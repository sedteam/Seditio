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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `sed_parser` VALUES(1, 'Bold', 0, 0, 1, '[b]', '[/b]', '<strong>', '</strong>', 1);
INSERT INTO `sed_parser` VALUES(2, 'Underline', 0, 0, 2, '[u]', '[/u]', '<u>', '</u>', 1);
INSERT INTO `sed_parser` VALUES(3, 'Italic', 0, 0, 3, '[i]', '[/i]', '<em>', '</em>', 1);
INSERT INTO `sed_parser` VALUES(4, 'Strike text', 0, 0, 24, '[s]', '[/s]', '<span style="text-decoration:line-through">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(5, 'Horizontal ruler', 0, 0, 4, '[hr]', '', '<hr />', '', 1);
INSERT INTO `sed_parser` VALUES(6, 'Spacers', 0, 0, 5, '[_]', '[__]', '&nbsp;', '&nbsp; &nbsp;', 1);
INSERT INTO `sed_parser` VALUES(7, 'Lists 1', 0, 0, 6, '[list]', '[/list]', '<ul>', '</ul>', 1);
INSERT INTO `sed_parser` VALUES(8, 'Lists 2', 0, 0, 7, '[li]', '[/li]', '<li>', '</li>', 1);
INSERT INTO `sed_parser` VALUES(9, 'Lists 3', 0, 0, 8, '[ol]', '[/ol]', '<ol>', '</ol>', 1);
INSERT INTO `sed_parser` VALUES(10, 'Color red', 0, 0, 8, '[red]', '[/red]', '<span style="color:#F93737">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(11, 'Color white', 0, 0, 9, '[white]', '[/white]', '<span style="color:#FFFFFF">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(12, 'Color green', 0, 0, 10, '[green]', '[/green]', '<span style="color:#09DD09">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(13, 'Color blue', 0, 0, 11, '[blue]', '[/blue]', '<span style="color:#018BFF">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(14, 'Color orange', 0, 0, 12, '[orange]', '[/orange]', '<span style="color:#FF9900">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(15, 'Color yellow', 0, 0, 13, '[yellow]', '[/yellow]', '<span style="color:#FFFF00">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(16, 'Color purple', 0, 0, 14, '[purple]', '[/purple]', '<span style="color:#A22ADA">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(17, 'Color black', 0, 0, 15, '[black]', '[/black]', '<span style="color:#000000">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(18, 'Color grey', 0, 0, 16, '[grey]', '[/grey]', '<span style="color:#B9B9B9">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(19, 'Color pink', 0, 0, 17, '[pink]', '[/pink]', '<span style="color:#FFC0FF">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(20, 'Color sky', 0, 0, 18, '[sky]', '[/sky]', '<span style="color:#D1F4F9">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(21, 'Color sea', 0, 0, 19, '[sea]', '[/sea]', '<span style="color:#171A97">', '</span>', 1);
INSERT INTO `sed_parser` VALUES(22, 'Preformatted text', 0, 0, 33, '[pre]', '[/pre]', '<pre>', '</pre>', 1);
INSERT INTO `sed_parser` VALUES(23, 'Quote', 0, 0, 20, '[quote]', '[/quote]', '<blockquote>Quote<p>', '</p></blockquote>', 1);
INSERT INTO `sed_parser` VALUES(24, 'BR', 0, 0, 21, '[br]', '', '<br />', '', 1);
INSERT INTO `sed_parser` VALUES(25, 'More', 0, 0, 22, '[more]', '[/more]', '<!--readmore-->', '', 1);
INSERT INTO `sed_parser` VALUES(26, 'Image 1', 0, 1, 10, '\\[img\\]([^\\\\\\''\\;\\?[]*)\\.(jpg|jpeg|gif|png)\\[\\/img\\]', '', '<img src="$1.$2" alt="" />', '', 1);
INSERT INTO `sed_parser` VALUES(27, 'Image 2', 0, 1, 20, '\\[img=([^\\\\\\''\\;\\?[]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/img\\]', '', '<a href="$1.$2"><img src="$3.$4" alt="" /></a>', '', 1);
INSERT INTO `sed_parser` VALUES(28, 'Thumbnail PFS', 0, 1, 30, '\\[thumb=([^\\\\\\''\\;\\?([]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/thumb\\]', NULL, '<a href="javascript:sedjs.picture(''$3.$4'', 200,200)"><img src="$1.$2" alt="" /></a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(29, 'Thumbnails', 0, 1, 40, '\\[t=([^\\\\\\''\\;\\?([]*)\\.(jpg|jpeg|gif|png)\\]([^\\\\[]*)\\.(jpg|jpeg|gif|png)\\[\\/t\\]', NULL, '<a href="$3.$4" rel="sedthumb"><img src="$1.$2" alt="" /></a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(30, 'Url 1', 0, 1, 50, '\\[url\\]([^\\\\([]*)\\[\\/url\\]', NULL, '<a href="$1">$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(31, 'Url 2', 0, 1, 60, '\\[url=([^\\\\\\''\\;([]*)\\]([^\\\\[]*)\\[\\/url\\]', NULL, '<a href="$1">$2</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(32, 'Colors', 0, 1, 70, '\\[color=([0-9A-F]{6})\\]([^\\\\[]*)\\[\\/color\\]', '', '<span style="color:#$1">$2</span>', NULL, 1);
INSERT INTO `sed_parser` VALUES(33, 'Styles', 0, 1, 80, '\\[style=([1-9]{1})\\]([^\\\\[]*)\\[\\/style\\]', NULL, '<span class="bbstyle$1">$2</span>', NULL, 1);
INSERT INTO `sed_parser` VALUES(34, 'Divs', 0, 1, 90, '\\[div=([1-9]{1})\\]([^\\\\[]*)\\[\\/div\\]', NULL, '<div class="divstyle$1">$2</div>', NULL, 1);
INSERT INTO `sed_parser` VALUES(35, 'Email 2', 0, 1, 100, '\\[email=([._A-z0-9-]+@[A-z0-9-]+\\.[.a-z]+)\\]([^\\\\[]*)\\[\\/email\\]', NULL, '<a href="mailto:$1">$2</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(36, 'Email 1', 0, 1, 110, '\\[email\\]([._A-z0-9-]+@[A-z0-9-]+\\.[.a-z]+)\\[\\/email\\]', NULL, '<a href="mailto:$1">$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(37, 'User', 0, 1, 120, '\\[user=([0-9]+)\\]([A-z0-9_\\. -]+)\\[\\/user\\]', NULL, '<a href="users.php?m=details&amp;id=$1">$2</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(38, 'Page 2', 0, 1, 130, '\\[page=([0-9]+)\\]([^\\\\[]*)\\[\\/page\\]', NULL, '<a href="page.php?id=$1">$2</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(39, 'Page 1', 0, 1, 140, '\\[page\\]([0-9]+)\\[\\/page\\]', NULL, '<a href="page.php?id=$1">Page #$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(40, 'Group', 0, 1, 150, '\\[group=([0-9]+)\\]([^\\\\([]*)\\[\\/group\\]', NULL, '<a href="users.php?g=$1">$2</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(41, 'Forum topic', 0, 1, 160, '\\[topic\\]([0-9]+)\\[\\/topic\\]', NULL, '<a href="forums.php?m=posts&amp;q=$1">Topic #$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(42, 'Forum post', 0, 1, 170, '\\[post\\]([0-9]+)\\[\\/post\\]', NULL, '<a href="forums.php?m=posts&amp;p=$1#$1">Post #$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(43, 'Private messages', 0, 1, 180, '\\[pm\\]([0-9]+)\\[\\/pm\\]', NULL, '<a href="pm.php?m=send&amp;to=$1">PM</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(44, 'Flag', 0, 1, 190, '\\[f\\]([a-z][a-z])\\[\\/f\\]', NULL, '<a href="users.php?f=country_$1"><img src="system/img/flags/f-$1.gif" alt="" /></a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(45, 'Acronym', 0, 1, 200, '\\[ac=([^\\\\[]*)\\]([^\\\\[]*)\\[\\/ac\\]', NULL, '<acronym title="$1">$2</acronym>', NULL, 1);
INSERT INTO `sed_parser` VALUES(46, 'Deleted', 0, 1, 210, '\\[del\\]([^\\\\[]*)\\[\\/del\\]', NULL, '<del>$1</del>', NULL, 1);
INSERT INTO `sed_parser` VALUES(47, 'Quote 2', 0, 1, 220, '\\[quote=([^\\\\[]*)\\]', NULL, '<blockquote>$1<p>', NULL, 1);
INSERT INTO `sed_parser` VALUES(48, 'Spoiler', 0, 1, 230, '\\[spoiler=([^\\\\[]*)\\]', '\\[\\/spoiler\\]', '<div style="margin:0; margin-top:8px"><div style="margin-bottom:4px"><input type="button" class="submit btn" value="Show : $1" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.innerText = ''''; this.value = ''Hide : : $1''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.innerText = ''''; this.value = ''Show : $1''; }"></div><div class="spoiler"><div style="display: none;">', '</div></div></div>', 1);
INSERT INTO `sed_parser` VALUES(49, 'Spoiler 2', 0, 1, 230, '\\[spoiler\\]', '\\[\\/spoiler\\]', '<div style="margin:0; margin-top:8px"><div style="margin-bottom:4px"><input type="button" class="submit btn" value="Show" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.innerText = ''''; this.value = ''Hide''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.innerText = ''''; this.value = ''Show''; }"></div><div class="spoiler"><div style="display: none;">', '</div></div></div>', 1);
INSERT INTO `sed_parser` VALUES(50, 'Fold', 0, 1, 240, '\\[fold=([^\\\\[]*)\\]', '\\[\\/fold\\]', '<div style="margin:0;"><div class="fhead"><a href="#fold" onClick="if (this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display != '''') { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''''; this.value = ''$1''; } else { this.parentNode.parentNode.getElementsByTagName(''div'')[1].getElementsByTagName(''div'')[0].style.display = ''none''; this.value = ''$1''; }">$1</a></div><div><div style="display: none;" class="fblock">', '</div></div></div>', 1);
INSERT INTO `sed_parser` VALUES(51, 'Youtube', 0, 1, 250, '\\[youtube=([A-Za-z0-9\\-_=&]+)\\]', '\\[youtube\\]([A-Za-z0-9\\-_=&]+)\\[\\/youtube\\]', '<iframe width="425" height="300" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', '<iframe width="425" height="300" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', 1);
INSERT INTO `sed_parser` VALUES(52, 'Dailymotion', 0, 1, 260, '\\[dailymotion=([A-Za-z0-9\\-_=&]+)\\]', '\\[dailymotion\\]([A-Za-z0-9\\-_=&]+)\\[\\/dailymotion\\]', '<iframe width="425" height="300" src="http://www.dailymotion.com/embed/video/$1" frameborder="0" allowfullscreen></iframe>', '<iframe width="425" height="300" src="http://www.dailymotion.com/embed/video/$1" frameborder="0" allowfullscreen></iframe>', 1);
INSERT INTO `sed_parser` VALUES(53, 'MetaCafe Video', 0, 1, 270, '\\[metacafe=([A-Za-z0-9\\-_=&]+)\\]', '\\[metacafe\\]([A-Za-z0-9\\-_=&]+)\\[\\/metacafe\\]', '<iframe src="http://www.metacafe.com/embed/$1/" width="425" height="248" allowFullScreen frameborder=0></iframe>', '<iframe src="http://www.metacafe.com/embed/$1/" width="425" height="248" allowFullScreen frameborder=0></iframe>', 1);
INSERT INTO `sed_parser` VALUES(54, 'Vkontakte Video', 0, 1, 255, '\\[vk=([A-Za-z0-9\\-_=&]+)\\]', '\\[vk\\]([A-Za-z0-9\\-_=&]+)\\[\\/vk\\]', '<iframe src="http://vkontakte.ru/video_ext.php?oid=$1" width="425" height="350" frameborder="0"></iframe>', '<iframe src="http://vkontakte.ru/video_ext.php?oid=$1" width="425" height="350" frameborder="0"></iframe>', 1);
INSERT INTO `sed_parser` VALUES(55, 'Vimeo Video', 0, 1, 265, '\\[vimeo=([A-Za-z0-9\\-_=&]+)\\]', '\\[vimeo\\]([A-Za-z0-9\\-_=&]+)\\[\\/vimeo\\]', '<iframe src="http://player.vimeo.com/video/$1" width="425" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', '<iframe src="http://player.vimeo.com/video/$1" width="425" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', 1);
INSERT INTO `sed_parser` VALUES(56, 'RuTube Video', 0, 1, 275, '\\[rutube=([A-Za-z0-9\\-_=&]+)\\]', '\\[rutube]([A-Za-z0-9\\-_=&]+)\\[\\/rutube\\]', '<iframe width="425" height="281" src="http://rutube.ru/video/embed/$1" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>', '<iframe width="425" height="281" src="http://rutube.ru/video/embed/$1" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>', 1);
INSERT INTO `sed_parser` VALUES(57, 'Column left', 0, 0, 31, '[colleft]', '[/colleft]', '<div class="colleft">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(58, 'Column right', 0, 0, 32, '[colright]', '[/colright]', '<div class="colright">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(59, 'Align Center', 0, 0, 26, '[center]', '[/center]', '<div style="text-align:center">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(60, 'Align Left', 0, 0, 27, '[left]', '[/left]', '<div style="text-align:left">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(61, 'Align Right', 0, 0, 28, '[right]', '[/right]', '<div style="text-align:right;">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(62, 'Align Justify', 0, 0, 29, '[justify]', '[/justify]', '<div style="text-align:justify;">', '</div>', 1);
INSERT INTO `sed_parser` VALUES(63, 'Columns', 0, 1, 330, '\\[c1\\:([^\\\\[]*)\\]([^\\\\[]*)\\[c2\\:([^\\\\[]*)\\]([^\\\\[]*)\\[c3\\]', NULL, '<table style="margin:0; vertical-align:top; width:100%;"><tr><td style="padding:0 16px 16px 0; vertical-align:top; width:$1%;">$2</td><td  style="padding:0 0 16px 16px; vertical-align:top; width:$3%;">$4</td></tr></table>', NULL, 1);
INSERT INTO `sed_parser` VALUES(64, 'Paragraph', 0, 0, 23, '[p]', '[/p]', '<p>', '</p>', 1);
INSERT INTO `sed_parser` VALUES(65, 'PFS', 0, 1, 25, '\\[pfs\\]([^\\\\([]*)\\[\\/pfs\\]', NULL, '<a href="datas/users/$1"><img src="system/img/admin/pfs.png" alt="" />$1</a>', NULL, 1);
INSERT INTO `sed_parser` VALUES(66, 'Headers h1-h6', 0, 1, 24, '\\[h([1-6])\\](.+?)\\[/h\\1\\]', '', '<h$1>$2</h$1>', '', 1);
INSERT INTO `sed_parser` VALUES(67, 'Size 1-29', 0, 1, 25, '\\[size=([1-2]?[0-9]?)\\](.+?)\\[\\/size\\]', '', '<span style="font-size:$1pt">$2</span>', '', 1);