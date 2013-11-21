<!-- BEGIN: MAIN -->

<div id="title">
  <h2>{FORUMS_SECTIONS_PAGETITLE}</h2>
</div>

<div id="bolded-line"></div>

<div id="subtitle">
  <nav class="nav-pills">
  	<ul>
  		<li><a href="{FORUMS_SECTIONS_SEARCH}">{PHP.skinlang.forumssections.Searchinforums}</a></li>
  		<li>{FORUMS_SECTIONS_MARKALL}</li>
  		<li style="padding-top:3px;">{FORUMS_SECTIONS_GMTTIME}</li>
  	</ul>
  </nav>
</div>

<div id="page">

{FORUMS_SECTIONS_MYPOSTS}

<table class="cells">

	<thead>
	<tr>
		<th class="coltop" colspan="2">{PHP.skinlang.forumssections.Sections}  &nbsp;  &nbsp; <a href="{FORUMS_SECTIONS_FOLDALL}">{PHP.skinlang.forumssections.FoldAll}</a> / <a href="{FORUMS_SECTIONS_UNFOLDALL}">{PHP.skinlang.forumssections.UnfoldAll}</a></th>
		<th class="coltop" style="width:250px;">{PHP.skinlang.forumssections.Lastpost}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Topics}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Posts}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Views}</th>
		<th class="coltop" style="width:48px;">{PHP.skinlang.forumssections.Activity}</th>
	</tr>
	</thead>

	<!-- BEGIN: FORUMS_SECTIONS_ROW -->

	<!-- BEGIN: FORUMS_SECTIONS_ROW_CAT -->

	{FORUMS_SECTIONS_ROW_TBODY_END}
	<tbody id="{FORUMS_SECTIONS_ROW_CAT_CODE}">

	<tr>
		<td colspan="7" class="cattop" style="padding:4px;">
		<strong>{FORUMS_SECTIONS_ROW_CAT_TITLE}</strong>
		</td>
	</tr>

	{FORUMS_SECTIONS_ROW_CAT_TBODY}

	<!-- END: FORUMS_SECTIONS_ROW_CAT -->
	

	<!-- BEGIN: FORUMS_SECTIONS_ROW_SECTION -->

	<tr>
		<td style="width:32px;" class="centerall">
			<img src="{FORUMS_SECTIONS_ROW_ICON}" alt="" />
		</td>

		<td>
		<h4 style="margin:4px;"><a href="{FORUMS_SECTIONS_ROW_URL}">{FORUMS_SECTIONS_ROW_TITLE}</a></h4>
		<div class="desc">{FORUMS_SECTIONS_ROW_DESC}</div>
		
    <!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS -->
    <div class="subforums">
      <ul>	
	    <!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
			<li><a href="{FORUMS_SECTIONS_ROW_SUBFORUMS_URL}">{FORUMS_SECTIONS_ROW_SUBFORUMS_TITLE}</a></li>	
			<!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
	    </ul>    
    </div>
    <!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS -->
    
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

		<td class="centerall">
		{FORUMS_SECTIONS_ROW_VIEWCOUNT_SHORT}
		</td>

		<td class="centerall">
		{FORUMS_SECTIONS_ROW_ACTIVITY}
		</td>

	</tr>		

	<!-- END: FORUMS_SECTIONS_ROW_SECTION -->

	<!-- END: FORUMS_SECTIONS_ROW -->
	</tbody>

</table>

</div>

<!-- END: MAIN -->