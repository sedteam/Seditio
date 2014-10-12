<!-- BEGIN: MAIN -->

<div id="title">
  <h2>{FORUMS_TOPICS_PAGETITLE}</h2>
</div>

<div id="bolded-line"></div>

<div id="subtitle">
	{FORUMS_TOPICS_SUBTITLE}
</div>

<div class="units-row">
  <div class="unit-20"><a href="{FORUMS_TOPICS_NEWTOPICURL}" class="btn"><img src="skins/{PHP.skin}/img/system/newtopic.gif" alt="" /> <span style="font-size:120%;">{PHP.L.for_newtopic}</span></a></div>  
  <div class="unit-80" style="text-align:right;">{FORUMS_TOPICS_VIEWERS} {PHP.skinlang.forumstopics.Viewers} &nbsp; 	{FORUMS_TOPICS_JUMPBOX}	</div>
</div> 

<div id="page">

<!-- BEGIN: FORUMS_SECTIONS -->

<table class="cells striped">

	<thead>
	<tr>
		<th class="coltop" colspan="2">{PHP.skinlang.forumssections.Subforums} </th>
		<th class="coltop" style="width:250px;">{PHP.skinlang.forumssections.Lastpost}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Topics}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Posts}</th>
	</tr>
	</thead>
  <tbody>
	<!-- BEGIN: FORUMS_SECTIONS_ROW -->
  
	<!-- BEGIN: FORUMS_SECTIONS_ROW_SECTION -->

	<tr>
		<td style="width:32px;" class="centerall">
			<img src="{FORUMS_SECTIONS_ROW_ICON}" alt="" />
		</td>

		<td>
		<h3 style="margin:4px;"><a href="{FORUMS_SECTIONS_ROW_URL}">{FORUMS_SECTIONS_ROW_TITLE}</a></h3>
		&nbsp; {FORUMS_SECTIONS_ROW_DESC}
		</td>

		<td class="centerall">
		{FORUMS_SECTIONS_ROW_LASTPOST}<br />
		{FORUMS_SECTIONS_ROW_LASTPOSTDATE} {FORUMS_SECTIONS_ROW_LASTPOSTER}<br />
		{FORUMS_SECTIONS_ROW_TIMEAGO}
		</td>

		<td class="centerall">
		{FORUMS_SECTIONS_ROW_TOPICCOUNT_ALL}<br />
		<span class="desc">({FORUMS_SECTIONS_ROW_TOPICCOUNT})</span>
		</td>

		<td class="centerall">
		{FORUMS_SECTIONS_ROW_POSTCOUNT_ALL}<br />
		<span class="desc">({FORUMS_SECTIONS_ROW_POSTCOUNT})</span>
		</td>

	</tr>

	<!-- END: FORUMS_SECTIONS_ROW_SECTION -->

	<!-- END: FORUMS_SECTIONS_ROW -->
	</tbody>
</table>

<!-- END: FORUMS_SECTIONS -->

<!-- BEGIN: FORUMS_TOPICS_PAGINATION_TP -->
<div class="paging">
<ul class="pagination">
  <li class="prev">{FORUMS_TOPICS_PAGEPREV}</li>
  {FORUMS_TOPICS_PAGES}
  <li class="next">{FORUMS_TOPICS_PAGENEXT}</li>
</ul>
</div>
<!-- END: FORUMS_TOPICS_PAGINATION_TP -->

<table class="cells striped">
  
  <thead>
	<tr>
		<th colspan="2" class="coltop">{FORUMS_TOPICS_TITLE_TOPICS}</th>
    <th class="coltop" style="width:160px;">{FORUMS_TOPICS_TITLE_STARTED}</th>
		<th class="coltop" style="width:250px;">{FORUMS_TOPICS_TITLE_LASTPOST}</th>
		<th class="coltop" style="width:56px;">{FORUMS_TOPICS_TITLE_POSTS}</th>
		<th class="coltop" style="width:56px;">{FORUMS_TOPICS_TITLE_VIEWS}</th>
	</tr>
  </thead>
  
  <tbody>
	<!-- BEGIN: FORUMS_TOPICS_ROW -->

	<tr>
		<td style="width:32px;" class="centerall {FORUMS_TOPICS_ROW_ODDEVEN}">
		{FORUMS_TOPICS_ROW_ICON}
		</td>

		<td class="{FORUMS_TOPICS_ROW_ODDEVEN}">
		<strong><a href="{FORUMS_TOPICS_ROW_URL}">{FORUMS_TOPICS_ROW_TITLE}</a></strong><br />
		<span class="desc">{FORUMS_TOPICS_ROW_DESC} &nbsp; {FORUMS_TOPICS_ROW_PAGES}</span>
		</td>

		<td class="centerall {FORUMS_TOPICS_ROW_ODDEVEN}">
		{FORUMS_TOPICS_ROW_CREATIONDATE}<br />{FORUMS_TOPICS_ROW_FIRSTPOSTER}
		</td>

		<td class="centerall {FORUMS_TOPICS_ROW_ODDEVEN}">
		{FORUMS_TOPICS_ROW_UPDATED} {FORUMS_TOPICS_ROW_LASTPOSTER}<br />
		{FORUMS_TOPICS_ROW_TIMEAGO}
		</td>

		<td class="centerall {FORUMS_TOPICS_ROW_ODDEVEN}">
		{FORUMS_TOPICS_ROW_POSTCOUNT}
		</td>

		<td class="centerall {FORUMS_TOPICS_ROW_ODDEVEN}">
		{FORUMS_TOPICS_ROW_VIEWCOUNT}
		</td>

	</tr>

	<!-- END: FORUMS_TOPICS_ROW -->
  </tbody>
  
</table>

<!-- BEGIN: FORUMS_TOPICS_PAGINATION_BM -->
<div class="paging">
<ul class="pagination">
  <li class="prev">{FORUMS_TOPICS_PAGEPREV}</li>
  {FORUMS_TOPICS_PAGES}
  <li class="next">{FORUMS_TOPICS_PAGENEXT}</li>
</ul>
</div>
<!-- END: FORUMS_TOPICS_PAGINATION_BM -->

<table class="main">

	<tr>
		<td><img src="skins/{PHP.skin}/img/system/posts.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewposts}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_new.gif" alt="" /> :{PHP.skinlang.forumstopics.Newposts}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Sticky}</td>
	</tr>
	<tr>
		<td><img src="skins/{PHP.skin}/img/system/posts_hot.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewpostspopular}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_new_hot.gif" alt="" /> :{PHP.skinlang.forumstopics.Newpostspopular}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_new_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostssticky}</td>
	</tr>
	<tr>
		<td><img src="skins/{PHP.skin}/img/system/posts_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Locked}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_new_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostslocked}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Announcment}</td>
	</tr>
	<tr>
		<td colspan="2">
		<img src="skins/{PHP.skin}/img/system/posts_moved.gif" alt="" /> : {PHP.skinlang.forumstopics.Movedoutofthissection}</td>
		<td><img src="skins/{PHP.skin}/img/system/posts_new_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newannouncment}</td>
	</tr>

</table>

</div>

<!-- END: MAIN -->